<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}
// Handle logout
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

// Get user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM account WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: signin.php");
    exit();
}

// Get profile picture
$profile_picture = !empty($user['profile_picture']) ? $user['profile_picture'] : null;

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BCDRS - Community Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="design/homepage.css" rel="stylesheet" />
  <link href="design/back-button.css" rel="stylesheet" />
  <script src="javascript/homepage.js"></script>
</head>
<body>
  <!-- Header -->
<header>
  <div class="header-container">
    <div class="header-left">
      
      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRTDCuh4kIpAtR-QmjA1kTjE_8-HSd8LSt3Gw&s" alt="Barangay Logo">
      <div class="title">
        <h1>Barangay 170</h1>
        <p>Deparo, Caloocan</p>
      </div>
    </div>
    <div class="header-right">
  <!-- Notification Bell -->
  <div class="notification-wrapper">
    <button class="notification-btn" onclick="toggleNotifications()">
      <i class="fas fa-bell"></i>
      <span class="notification-badge" id="notificationBadge">0</span>
    </button>
    
    <!-- Notification Dropdown -->
    <div class="notification-dropdown" id="notificationDropdown">
      <div class="notification-header">
        <h3>Your Notifications</h3>
        <button class="notification-close-btn" onclick="closeNotifications(event)">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div id="notificationContent">
        <div class="notification-empty">
          <p>No requests yet. Submit your first request!</p>
        </div>
      </div>
      <div class="notification-footer">
        <button onclick="window.location.href='trackreq.php'">View All Requests</button>
      </div>
    </div>
  </div>

  <!-- Profile Button -->
  <button class="profile-btn" onclick="window.location.href='profile.php'">
    <i class="fas fa-user-circle"></i>
  </button>

  <!-- Profile Picture and Welcome Message -->
  <?php if ($profile_picture && file_exists($profile_picture)): ?>
    <img src="<?php echo htmlspecialchars($profile_picture); ?>" 
         alt="Profile" 
         class="header-profile-pic">
  <?php else: ?>
    <div class="header-profile-placeholder">
      <i class="fas fa-user"></i>
    </div>
  <?php endif; ?>
  <p style="font-size: 0.875rem; color: #166534; margin: 0;">Welcome <?php echo htmlspecialchars($user['first_name']); ?>!</p>

  <form method="POST" action="homepage.php" style="display: inline; margin: 0;">
    <button type="submit" name="logout" class="logout-btn">
      <i class="fas fa-sign-out-alt"></i> Logout
    </button>
  </form>
</div>
  </div>
</header>
  <!-- Main Content -->
  <div class="main-wrapper">
    <!-- Right Sidebar - Latest Updates -->
    <aside class="sidebar">
      <div class="sidebar-card">
        <div class="card-header">
          <h2 class="card-title">
            🔔 Latest Updates
          </h2>
        </div>
        <div class="card-content" id="updatesContent">
          <!-- Updates will be loaded here -->
        </div>
      </div>
    </aside>
    <!-- Center Content -->
    <main class="center-content">
      <!-- Welcome Section -->
      <section class="welcome-section">
        <div class="logo-circle">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRTDCuh4kIpAtR-QmjA1kTjE_8-HSd8LSt3Gw&s" alt="eBCsH Logo">
        </div>
        <h1>Welcome to BCDRS</h1>
        <p>Submit document-related requests to your barangay and track their progress in real time. Our system ensures transparency and efficient processing of your requests.</p>
      </section>
      <!-- Action Cards -->
      <div class="action-cards">
        <div class="action-card" onclick="window.location.href='submitreq.php'">
          <div class="action-icon">
            📝
          </div>
          <h3>Submit Request</h3>
          <p>File new requests directly to your local barangay health office</p>
          <div style="display: flex; justify-content: center;">
            <button class="btn-submit">Start a New Request</button>
          </div>
        </div>
        <div class="action-card" onclick="window.location.href='trackreq.php'">
          <div class="action-icon">
            🔍
          </div>
          <h3>Track Request</h3>
          <p>Check the status and updates on your submitted health requests</p>
          <div style="display: flex; justify-content: center;">
            <button class="btn-track">View Existing Request</button>
          </div>
        </div>
      </div>
      <!-- How it Works -->
      <section class="how-it-works">
        <h2>How it Works</h2>
        <div class="steps">
          <div class="step">
            <div class="step-icon">
              ⬆️
            </div>
            <h3>Submit</h3>
            <p>Fill out the request form with your details and submit it to the barangay health office</p>
          </div>
          <div class="step">
            <div class="step-icon">
              🔔
            </div>
            <h3>Track</h3>
            <p>Monitor your request's status in real-time and receive notifications for any updates</p>
          </div>
          <div class="step">
            <div class="step-icon">
              ✅
            </div>
            <h3>Receive</h3>
            <p>Get notified whenever your request is approved and ready for pickup or delivery</p>
          </div>
        </div>
      </section>
    </main>
  </div>
  <!-- Footer -->
  <footer>
    <div class="footer-container">
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
      <div class="footer-copyright">
        <p>© 2025 Barangay 170, Deparo, Caloocan. All rights reserved.</p>
        <p>Barangay Citizen Document Request System (BCDRS)</p>
      </div>
    </div>
  </footer>
</body>
</html>