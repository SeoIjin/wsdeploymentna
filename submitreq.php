<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

// Handle logout from header
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_destroy();
    header("Location: sign-in.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['logout'])) {
    $requesttype = $_POST['requesttype'];
    $description = trim($_POST['description']);
    $priority = isset($_POST['priority']) ? $_POST['priority'] : '';
    $user_id = $_SESSION['user_id'];

    // Validation
    if (empty($requesttype) || empty($description) || empty($priority)) {
        $error = "All fields are required.";
    } else {
        // Generate unique random ticket ID
        $year = date('Y');
        $max_attempts = 100; // Prevent infinite loop
        $attempts = 0;
        
        do {
            // Generate random 6-digit number
            $random_number = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $ticket_id = 'BHR-' . $year . '-' . $random_number;
            
            // Check if ticket_id already exists
            $check_stmt = $conn->prepare("SELECT id FROM requests WHERE ticket_id = ?");
            $check_stmt->bind_param("s", $ticket_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $exists = $check_result->num_rows > 0;
            $check_stmt->close();
            
            $attempts++;
        } while ($exists && $attempts < $max_attempts);
        
        // If we couldn't find a unique ID after max attempts, show error
        if ($exists) {
            $error = "Unable to generate unique ticket ID. Please try again.";
        } else {
            // Get user email for the name field
            $stmt = $conn->prepare("SELECT email, contact_number FROM account WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($user_email, $contact_number);
            $stmt->fetch();
            $stmt->close();

            // Insert request with priority instead of contact
            $stmt = $conn->prepare("INSERT INTO requests (ticket_id, user_id, fullname, priority, contact, requesttype, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $fullname = $user_email;
            $stmt->bind_param("sisssss", $ticket_id, $user_id, $fullname, $priority, $contact_number, $requesttype, $description);
            if ($stmt->execute()) {
                $success = "Request submitted successfully! Your Ticket ID is: <strong>$ticket_id</strong>. Use it to track your request.";
            } else {
                $error = "Error submitting request. Please try again.";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Submit Request — eBCsH</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { 
      margin: 0; 
      padding: 0; 
      box-sizing: border-box; 
      font-family: 'Poppins', sans-serif; 
    }
    
    body { 
      min-height: 100vh;
      background: #DAF1DE;
      color: #333; 
    }

    /* Header */
    header { 
      background: white;
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
      padding: 1rem 2rem; 
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .header-left { 
      display: flex; 
      align-items: center;
      cursor: pointer;
      background: transparent;
      border: none;
      text-decoration: none;
      color: inherit;
      transition: opacity 0.3s;
    }

    .header-left:hover {
      opacity: 0.8;
    }
    
    .header-left img { 
      height: 50px;
      width: 50px;
      margin-right: 10px;
      border-radius: 50%;
    }
    
    .header-title-wrap {
      text-align: left;
    }
    
    .header-title-wrap .title { 
      font-size: 1rem; 
      font-weight: 500;
      color: #000;
    }
    
    .header-title-wrap .subtitle {
      font-size: 0.875rem;
      opacity: 0.7;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .profile-btn {
      background: #16a34a;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 6px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.3s;
      text-decoration: none;
    }

    .profile-btn:hover {
      background: #15803d;
    }
    
    .logout-btn { 
      background: #FD7E7E;
      color: #fff; 
      border: none; 
      padding: 10px 20px; 
      border-radius: 6px; 
      font-weight: 600;
      font-size: 0.875rem;
      cursor: pointer;
      transition: background 0.3s;
    }
    
    .logout-btn:hover {
      background: #fc6b6b;
    }
    
    .logout-btn i { 
      margin-right: 6px; 
    }

    /* Main Content */
    .main-container {
      max-width: 1000px;
      margin: 28px auto;
      padding: 0 16px;
    }

    .form-container { 
      max-width: 700px;
      margin: 20px auto;
      background: white;
      padding: 32px; 
      border-radius: 16px; 
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .form-title { 
      font-size: 28px; 
      font-weight: 600; 
      color: #2e7d3a; 
      margin-bottom: 6px; 
    }
    
    .form-description { 
      color: #556; 
      margin-bottom: 20px;
      line-height: 1.6;
      font-size: 15px;
    }
    
    form { 
      display: flex; 
      flex-direction: column; 
      gap: 20px; 
    }
    
    .form-group { 
      display: flex; 
      flex-direction: column; 
    }
    
    .form-label { 
      font-weight: 500; 
      color: #249c3b; 
      margin-bottom: 6px;
      font-size: 14px;
    }
    
    select, textarea, input[type="text"] { 
      padding: 10px; 
      border-radius: 8px; 
      border: 1px solid #e6efe6; 
      background: #fbfffb; 
      outline: none;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;

      /* Add these three lines: */
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
    }

    select:focus, textarea:focus, input[type="text"]:focus {
      border-color: #249c3b;
    }
    
    textarea { 
      min-height: 120px;
      resize: vertical;
    }

    select {
      cursor: pointer;
    }

    /* Priority Badge Styles */
    .priority-option-low::before {
      content: '🟢 ';
    }
    
    .priority-option-medium::before {
      content: '🟡 ';
    }
    
    .priority-option-high::before {
      content: '🔴 ';
    }
    
    .submit-button { 
      background: #00B050;
      color: #fff; 
      padding: 12px; 
      border-radius: 8px; 
      border: none; 
      font-weight: 600;
      font-size: 15px;
      cursor: pointer;
      transition: background 0.3s;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .submit-button:hover {
      background: #009944;
    }
    
    .message { 
      padding: 12px; 
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
    }
    
    .success { 
      background: #e6f8ec; 
      color: #1b6b2b;
    }
    
    .error { 
      background: #fff0f0; 
      color: #942020;
    }

    /* Footer */
    footer {
      background: white;
      border-top: 1px solid #dcfce7;
      margin-top: 48px;
    }

    .footer-content {
      max-width: 1000px;
      margin: 0 auto;
      padding: 32px 24px;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 32px;
      margin-bottom: 24px;
    }

    .footer-section {
      text-align: center;
    }

    .footer-section h3 {
      font-size: 18px;
      color: #14532d;
      font-weight: 600;
      margin-bottom: 16px;
    }

    .footer-section-content {
      display: inline-block;
      text-align: left;
    }

    .footer-item {
      margin-bottom: 12px;
      font-size: 15px;
    }

    .footer-item-label {
      color: #15803d;
      font-weight: 500;
      margin-bottom: 4px;
    }

    .footer-item-value {
      color: #166534;
      font-size: 14px;
    }

    .footer-hospital {
      margin-bottom: 12px;
    }

    .footer-hospital-name {
      color: #15803d;
      font-weight: 500;
    }

    .footer-hospital-phone {
      color: #166534;
      font-size: 14px;
    }

    .footer-copyright {
      border-top: 1px solid #dcfce7;
      padding-top: 24px;
      text-align: center;
      color: #15803d;
      font-size: 15px;
    }

    .footer-copyright p {
      margin-bottom: 8px;
    }

    @media (max-width: 768px) { 
      header {
        padding: 12px 16px;
      }

      .header-left img {
        height: 40px;
        width: 40px;
      }

      .main-container {
        padding: 0 12px;
      }

      .form-container {
        padding: 24px;
      }

      .form-title {
        font-size: 24px;
      }

      .footer-grid {
        grid-template-columns: 1fr;
        gap: 24px;
      }
    }
    .back-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f3f4f6;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
        margin-right: 12px;
    }

    .back-btn:hover {
        background: #e5e7eb;
    }

    .back-btn i {
        color: #374151;
    }

    /* Update header-left to include the back button */
    .header-left {
        display: flex;
        align-items: center;
        gap: 0; /* Remove gap since we're using margin-right on back-btn */
    }

    /* Modal Overlay */
.modal-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1000;
  align-items: center;
  justify-content: center;
}

.modal-overlay.active {
  display: flex;
}

/* Modal Container */
.modal-container {
  background: white;
  border-radius: 12px;
  max-width: 500px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Modal Header */
.modal-header {
  background: #2e7d3a;
  color: white;
  padding: 1.25rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-radius: 12px 12px 0 0;
}

.modal-header h2 {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
}

.modal-close-btn {
  background: none;
  border: none;
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  transition: background 0.2s;
}

.modal-close-btn:hover {
  background: rgba(255, 255, 255, 0.2);
}

/* Modal Body */
.modal-body {
  padding: 1.5rem;
}

.preview-item {
  margin-bottom: 1.25rem;
  padding-bottom: 1.25rem;
  border-bottom: 1px solid #e5e7eb;
}

.preview-item:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.preview-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #249c3b;
  margin-bottom: 0.5rem;
  display: block;
}

.preview-value {
  font-size: 0.9375rem;
  color: #333;
  line-height: 1.6;
  white-space: pre-wrap;
  word-break: break-word;
}

.preview-priority {
  display: inline-block;
  padding: 0.375rem 0.75rem;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.875rem;
}

.preview-priority.high {
  background: #fee2e2;
  color: #991b1b;
}

.preview-priority.medium {
  background: #fef3c7;
  color: #92400e;
}

.preview-priority.low {
  background: #d1fae5;
  color: #065f46;
}

/* Modal Footer */
.modal-footer {
  padding: 1.5rem;
  background: #f9fafb;
  border-top: 1px solid #e5e7eb;
  display: flex;
  gap: 0.75rem;
  justify-content: flex-end;
}

.modal-btn {
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  border: none;
  font-weight: 600;
  font-size: 0.9375rem;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-cancel {
  background: #e5e7eb;
  color: #374151;
}

.btn-cancel:hover {
  background: #d1d5db;
}

.btn-confirm {
  background: #00B050;
  color: white;
}

.btn-confirm:hover {
  background: #009944;
}

@media (max-width: 768px) {
  .modal-container {
    width: 95%;
    max-height: 85vh;
  }

  .modal-header {
    padding: 1rem 1.25rem;
  }

  .modal-header h2 {
    font-size: 1.125rem;
  }

  .modal-body {
    padding: 1.25rem;
  }

  .modal-footer {
    flex-direction: column-reverse;
    gap: 0.5rem;
  }

  .modal-btn {
    width: 100%;
  }
}
  </style>
  <link href="design/back-button.css" rel="stylesheet">
</head>
<body>
  <header>
  <a href="homepage.php" class="header-left">
    <button 
        class="back-btn" 
        onclick="event.preventDefault(); window.location.href='homepage.php'"
        title="Go back"
    >
        <i class="fas fa-arrow-left"></i>
    </button>
    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRTDCuh4kIpAtR-QmjA1kTjE_8-HSd8LSt3Gw&s">
    <div class="header-title-wrap">
        <div class="title">Barangay 170</div>
        <div class="subtitle">Community Portal</div>
    </div>
  </a>
    <div class="header-right">
      <!-- Profile Button -->
      <a href="profile.php" class="profile-btn">
        <i class="fas fa-user-circle"></i>
      </a>

      <!-- Logout Button -->
      <form method="POST" action="submitreq.php" style="display:inline; margin: 0;">
        <button type="submit" name="logout" class="logout-btn">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </form>
    </div>
  </header>

  <div class="main-container">
    <div class="form-container">
      <h1 class="form-title">Submit New Request</h1>
      <p class="form-description">
        Please provide detailed information about your request or concern. 
        The barangay will process it and notify you when ready.
      </p>

      <?php if ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
      <?php elseif ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
      <?php endif; ?>

      <!--🔴 HIGH	Buhay, Kaligtasan, at Kagalingan (Life, Safety, Welfare)	Blotter/Incident Report Copy, Certificate of Indigency, Certification for PWD, Certification for Solo Parent, Water/Electric Connection Certificate
          🟡 MEDIUM	Pangkabuhayan at Pangunahing Regulasyon (Livelihood & Key Regulation)	Barangay Clearance, Business Clearance, Barangay ID, Construction/Renovation Permit, Livelihood Program Certificate, No Derogatory Record, Street Vending Clearance
          🟢 LOW	Pang-Administratibo at Suporta (Administrative & Supportive)	Certificate of Residency, Household Membership Certificate, Endorsement Letter, Event Permit, Guardianship Certificate, No Objection (CNO) / Clearance of No Objection -->        
      <form method="POST" action="submitreq.php">
        <div class="form-group">
          <label class="form-label" for="requesttype">Request Type</label>
          <select id="requesttype" name="requesttype" required>
            <option value="" disabled selected>Select the type of request</option>
            <option value="Barangay Blotter / Incident Report Copy">Barangay Blotter / Incident Report Copy</option>
            <option value="Barangay Business Clearance">Barangay Business Clearance</option>
            <option value="Barangay Certificate for Livelihood Program Application">Barangay Certificate for Livelihood Program Application</option>
            <option value="Barangay Certificate for Water/Electric Connection (Proof of Occupancy/Ownership)">Barangay Certificate for Water/Electric Connection (Proof of Occupancy/Ownership)</option>
            <option value="Barangay Certificate of Guardianship">Barangay Certificate of Guardianship</option>
            <option value="Barangay Certificate of Household Membership">Barangay Certificate of Household Membership</option>
            <option value="Barangay Certificate of No Derogatory Record">Barangay Certificate of No Derogatory Record</option>
            <option value="Barangay Certificate of No Objection (CNO)">Barangay Certificate of No Objection (CNO)</option>
            <option value="Barangay Certification for PWD">Barangay Certification for PWD</option>
            <option value="Barangay Certification for Solo Parent (Referral for DSWD)">Barangay Certification for Solo Parent (Referral for DSWD)</option>
            <option value="Barangay Clearance">Barangay Clearance</option>
            <option value="Barangay Clearance for Street Vending">Barangay Clearance for Street Vending</option>
            <option value="Barangay Construction / Renovation Permit">Barangay Construction / Renovation Permit</option>
            <option value="Barangay Endorsement Letter">Barangay Endorsement Letter</option>
            <option value="Barangay Event Permit (Sound Permit, Activity Permit)">Barangay Event Permit (Sound Permit, Activity Permit)</option>
            <option value="Barangay ID">Barangay ID</option>
            <option value="Certificate of Indigency">Certificate of Indigency</option>
            <option value="Certificate of Residency">Certificate of Residency</option>
            <option value="Clearance of No Objection">Clearance of No Objection</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="priority">Request Priority</label>
          <select id="priority" name="priority" required readonly style="pointer-events: none; background-color: #f3f4f6;">
            <option value="" disabled selected>Select priority level</option>
            <option value="LOW" class="priority-option-low">🟢 Low - Standard urgency</option>
            <option value="MEDIUM" class="priority-option-medium">🟡 Medium - Moderate urgency</option>
            <option value="HIGH" class="priority-option-high">🔴 High - Urgent request</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="description">Purpose of Request</label>
          <textarea id="description" name="description" placeholder="Provide details, symptoms, timeline, or assistance needed." required></textarea>
        </div>

        <button type="submit" class="submit-button">Submit</button>
      </form>
    </div>
  </div>

  <div class="modal-overlay" id="confirmationModal">
  <div class="modal-container">
    <div class="modal-header">
      <h2>Confirm Request Submission</h2>
      <button class="modal-close-btn" onclick="closeConfirmationModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    
    <div class="modal-body">
      <div class="preview-item">
        <span class="preview-label">Request Type</span>
        <div class="preview-value" id="preview-requesttype"></div>
      </div>
      
      <div class="preview-item">
        <span class="preview-label">Priority Level</span>
        <div class="preview-value">
          <span class="preview-priority" id="preview-priority"></span>
        </div>
      </div>
      
      <div class="preview-item">
        <span class="preview-label">Purpose of Request</span>
        <div class="preview-value" id="preview-description"></div>
      </div>
    </div>
    
    <div class="modal-footer">
      <button type="button" class="modal-btn btn-cancel" onclick="closeConfirmationModal()">
        Cancel
      </button>
      <button type="button" class="modal-btn btn-confirm" onclick="confirmSubmission()">
        Confirm & Submit
      </button>
    </div>
  </div>
</div>

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <div class="footer-grid">
        <!-- Barangay Health Office -->
        <div class="footer-section">
          <h3>🏢 Barangay Health Office</h3>
          <div class="footer-section-content">
            <div class="footer-item">
              <div class="footer-item-label">📍 Address</div>
              <div class="footer-item-value">Deparo, Caloocan City, Metro Manila</div>
            </div>
            <div class="footer-item">
              <div class="footer-item-label">📞 Hotline</div>
              <div class="footer-item-value">(02) 8123-4567</div>
            </div>
            <div class="footer-item">
              <div class="footer-item-label">📧 Email</div>
              <div class="footer-item-value">K1contrerascris@gmail.com</div>
            </div>
            <div class="footer-item">
              <div class="footer-item-label">🕐 Office Hours</div>
              <div class="footer-item-value">Mon-Fri, 8:00 AM - 5:00 PM</div>
            </div>
          </div>
        </div>

        <!-- Emergency Hotlines -->
        <div class="footer-section">
          <h3>📞 Emergency Hotlines</h3>
          <div class="footer-section-content">
            <div class="footer-item">
              <span class="footer-item-label" style="min-width: 80px; display: inline-block;">Police</span>
              <span class="footer-item-value">(02) 8426-4663</span>
            </div>
            <div class="footer-item">
              <span class="footer-item-label" style="min-width: 80px; display: inline-block;">BFP</span>
              <span class="footer-item-value">(02) 8245 0849</span>
            </div>
          </div>
        </div>

        <!-- Hospitals Near Barangay -->
        <div class="footer-section">
          <h3>🏥 Hospitals Near Barangay</h3>
          <div class="footer-section-content">
            <div class="footer-hospital">
              <div class="footer-hospital-name">Camarin Doctors Hospital</div>
              <div class="footer-hospital-phone">(02) 2-7004-2881</div>
            </div>
            <div class="footer-hospital">
              <div class="footer-hospital-name">Caloocan City North Medical</div>
              <div class="footer-hospital-phone">(02) 8288 7077</div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Copyright -->
      <div class="footer-copyright">
        <p>© 2025 Barangay 170, Deparo, Caloocan. All rights reserved.</p>
        <p>Barangay Citizen Document Request System (BCDRS)</p>
      </div>
    </div>
  </footer>
  <script>
  // Define priority mapping based on request types
const priorityMapping = {
  // 🔴 HIGH - Life, Safety, Welfare
  "Barangay Blotter / Incident Report Copy": "HIGH",
  "Certificate of Indigency": "HIGH",
  "Barangay Certification for PWD": "HIGH",
  "Barangay Certification for Solo Parent (Referral for DSWD)": "HIGH",
  "Barangay Certificate for Water/Electric Connection (Proof of Occupancy/Ownership)": "HIGH",
  
  // 🟡 MEDIUM - Livelihood & Key Regulation
  "Barangay Clearance": "MEDIUM",
  "Barangay Business Clearance": "MEDIUM",
  "Barangay ID": "MEDIUM",
  "Barangay Construction / Renovation Permit": "MEDIUM",
  "Barangay Certificate for Livelihood Program Application": "MEDIUM",
  "Barangay Certificate of No Derogatory Record": "MEDIUM",
  "Barangay Clearance for Street Vending": "MEDIUM",
  
  // 🟢 LOW - Administrative & Supportive
  "Certificate of Residency": "LOW",
  "Barangay Certificate of Household Membership": "LOW",
  "Barangay Endorsement Letter": "LOW",
  "Barangay Event Permit (Sound Permit, Activity Permit)": "LOW",
  "Barangay Certificate of Guardianship": "LOW",
  "Barangay Certificate of No Objection (CNO)": "LOW",
  "Clearance of No Objection": "LOW"
};

document.addEventListener('DOMContentLoaded', function() {
  const requestTypeSelect = document.getElementById('requesttype');
  const prioritySelect = document.getElementById('priority');
  // FIXED: Use a more specific selector to get the request form, not the logout form
  const requestForm = document.querySelector('form[method="POST"][action="submitreq.php"]:not([style])');
  
  requestTypeSelect.addEventListener('change', function() {
    const selectedRequest = this.value;
    const autoPriority = priorityMapping[selectedRequest];
    
    if (autoPriority) {
      prioritySelect.value = autoPriority;
      
      // Visual feedback
      prioritySelect.style.transition = 'all 0.3s ease';
      prioritySelect.style.backgroundColor = '#e6f8ec';
      
      setTimeout(() => {
        prioritySelect.style.backgroundColor = '#fbfffb';
      }, 1000);
    }
  });
  
  // Intercept form submission to show confirmation modal
  requestForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate form
    if (!requestForm.checkValidity()) {
      requestForm.reportValidity();
      return;
    }
    
    // Get form values
    const requestType = requestTypeSelect.value;
    const priority = prioritySelect.value;
    const description = document.getElementById('description').value;
    
    // Populate preview
    document.getElementById('preview-requesttype').textContent = requestType;
    document.getElementById('preview-description').textContent = description;
    
    const priorityBadge = document.getElementById('preview-priority');
    priorityBadge.textContent = getPriorityText(priority);
    priorityBadge.className = 'preview-priority ' + priority.toLowerCase();
    
    // Show modal
    document.getElementById('confirmationModal').classList.add('active');
  });
});

function getPriorityText(priority) {
  const priorityTexts = {
    'HIGH': '🔴 High - Urgent request',
    'MEDIUM': '🟡 Medium - Moderate urgency',
    'LOW': '🟢 Low - Standard urgency'
  };
  return priorityTexts[priority] || priority;
}

function closeConfirmationModal() {
  document.getElementById('confirmationModal').classList.remove('active');
}

function confirmSubmission() {
  // Submit the request form (not the logout form)
  const requestForm = document.querySelector('form[method="POST"][action="submitreq.php"]:not([style])');
  requestForm.submit();
}

// Close modal when clicking outside
document.getElementById('confirmationModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeConfirmationModal();
  }
});
</script>
</body>
</html>