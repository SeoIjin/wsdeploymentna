<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
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

// Check if user is admin
$is_admin = ($user['usertype'] === 'admin');

// Get join date
$join_date = isset($user['created_at']) ? date('F j, Y', strtotime($user['created_at'])) : date('F j, Y');

// Get profile picture
$profile_picture = !empty($user['profile_picture']) ? $user['profile_picture'] : null;

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Barangay 170</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="design/profile.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            min-height: 100vh;
            background: #DAF1DE;
            --header-height: 72px;
            padding-top: var(--header-height); /* reserve space for fixed header */
        }

        /* Header Styles */
        header {
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            z-index: 1000;
        }

        .logo-section {
            display: flex;
            align-items: center;
            cursor: pointer;
            background: transparent;
            border: none;
            transition: opacity 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .logo-section:hover {
            opacity: 0.8;
        }

        .logo-img {
            height: 50px;
            margin-right: 10px;
            border-radius: 50%;
        }

        .logo-text {
            text-align: left;
        }

        .logo-text > div:first-child {
            font-weight: 500;
            font-size: 1rem;
        }

        .logo-text > div:last-child {
            opacity: 0.7;
            font-size: 0.875rem;
        }

        .logout-btn {
            background: #FD7E7E;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #fc6565;
        }

        .logout-btn i {
            margin-right: 6px;
        }

        /* Main Container */
        .main-container {
            max-width: 900px;
            margin: 1.75rem auto;
            padding: 0 1rem;
        }

        /* Profile Card */
        .profile-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* Profile Header */
        .profile-header {
            background: linear-gradient(to right, #16a34a, #15803d);
            padding: 3rem 2rem;
            text-align: center;
        }

        .profile-picture-container {
            position: relative;
            display: inline-block;
            margin: 0 auto 1rem;
        }

        .profile-avatar {
            background: white;
            border-radius: 50%;
            width: 8rem;
            height: 8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }

        .profile-avatar i {
            font-size: 4rem;
            color: #16a34a;
        }

        .profile-picture-img {
            width: 8rem;
            height: 8rem;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }

        .change-picture-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #16a34a;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            transition: background 0.3s;
        }

        .change-picture-btn:hover {
            background: #15803d;
        }

        #profilePictureInput {
            display: none;
        }

        #profilePictureMessage {
            margin-top: 0.75rem;
            margin-bottom: 1rem;
        }

        .profile-header h1 {
            color: white;
            font-size: 1.875rem;
            margin-bottom: 0.5rem;
        }

        .profile-email {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #dcfce7;
        }

        /* Profile Info Section */
        .profile-info {
            padding: 2rem;
        }

        .profile-info h2 {
            font-size: 1.5rem;
            color: #14532d;
            margin-bottom: 1.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-card {
            background: #f0fdf4;
            padding: 1.25rem;
            border-radius: 0.5rem;
            border: 1px solid #dcfce7;
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .info-icon {
            background: #16a34a;
            padding: 0.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-icon i {
            color: white;
            font-size: 1.25rem;
        }

        .info-card h3 {
            color: #15803d;
            margin: 0;
            font-size: 1rem;
        }

        .info-card p {
            color: #166534;
            margin: 0;
            margin-left: 2.75rem;
        }

        .info-card p.capitalize {
            text-transform: capitalize;
        }

        /* Editable Field Styles */
        .editable-field {
            position: relative;
        }

        .edit-button {
            background: #16a34a;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
            margin-left: 0.5rem;
            transition: background 0.3s;
        }

        .edit-button:hover {
            background: #15803d;
        }

        .edit-input {
            width: 100%;
            padding: 8px;
            border: 2px solid #16a34a;
            border-radius: 4px;
            font-size: 0.9375rem;
            margin-top: 0.5rem;
        }

        .edit-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .save-button {
            background: #16a34a;
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background 0.3s;
        }

        .save-button:hover {
            background: #15803d;
        }

        .cancel-button {
            background: #dc2626;
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background 0.3s;
        }

        .cancel-button:hover {
            background: #b91c1c;
        }

        .success-message {
            background: #d1fae5;
            color: #065f46;
            padding: 10px;
            border-radius: 4px;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            text-align: center;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 4px;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            text-align: center;
        }

        /* Valid ID Image */
        .id-image {
            margin-left: 2.75rem;
            margin-top: 0.5rem;
        }

        .id-image img {
            width: 100%;
            max-width: 200px;
            height: auto;
            border-radius: 0.5rem;
            border: 1px solid #dcfce7;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .id-image p {
            font-size: 0.875rem;
            color: #166534;
            margin-top: 0.5rem;
            margin-left: 0;
        }

        /* Account Status */
        .account-status {
            margin-top: 2rem;
            background: #ecfdf5;
            border-left: 4px solid #16a34a;
            padding: 1.25rem;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .account-status h3 {
            color: #14532d;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-dot {
            width: 0.75rem;
            height: 0.75rem;
            background: #16a34a;
            border-radius: 50%;
        }

        .status-text {
            color: #166534;
        }

        /* Admin Info */
        .admin-info {
            margin-top: 1.5rem;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1.25rem;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .admin-info h3 {
            color: #92400e;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .admin-info p {
            color: #78350f;
            margin: 0;
        }

        /* Quick Actions */
        .quick-actions {
            margin-top: 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-card {
            background: white;
            padding: 1.25rem;
            border-radius: 0.5rem;
            border: 2px solid #dcfce7;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .action-card:hover {
            border-color: #16a34a;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .action-card.logout:hover {
            border-color: #ef4444;
        }

        .action-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .action-card h3 {
            color: #14532d;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }

        .action-card.logout h3 {
            color: #dc2626;
        }

        .action-card p {
            font-size: 0.875rem;
            color: #166534;
            margin: 0;
        }

        .action-card.logout p {
            color: #991b1b;
        }

        /* Footer */
        footer {
            background: white;
            border-top: 1px solid #dcfce7;
            margin-top: 3rem;
        }

        .footer-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .footer-section {
            text-align: center;
        }

        .footer-section h3 {
            font-size: 1.125rem;
            color: #14532d;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-content {
            display: inline-block;
            text-align: left;
        }

        .footer-item {
            margin-bottom: 0.75rem;
            font-size: 0.9375rem;
        }

        .footer-label {
            color: #15803d;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .footer-value {
            color: #166534;
            font-size: 0.875rem;
        }

        .emergency-item {
            margin-bottom: 0.75rem;
            font-size: 0.9375rem;
        }

        .emergency-item span:first-child {
            color: #15803d;
            min-width: 80px;
            display: inline-block;
        }

        .emergency-item span:last-child {
            color: #166534;
        }

        .hospital-item {
            margin-bottom: 0.75rem;
        }

        .hospital-name {
            color: #15803d;
            font-weight: 500;
        }

        .hospital-phone {
            color: #166534;
            font-size: 0.875rem;
        }

        .footer-copyright {
            border-top: 1px solid #dcfce7;
            padding-top: 1.5rem;
            text-align: center;
            color: #15803d;
            font-size: 0.9375rem;
        }

        .footer-copyright p {
            margin-bottom: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            /* Keep header fixed and left-aligned on small screens */
            :root { --header-height: 64px; }
            header {
                flex-direction: row !important;
                align-items: center !important;
                justify-content: flex-start !important;
                padding: 0.5rem 1rem !important;
                height: var(--header-height) !important;
            }

            /* Ensure logo-section and back button align left */
            .logo-section {
                justify-content: flex-start !important;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .profile-header {
                padding: 2rem 1rem;
            }

            .profile-info {
                padding: 1.5rem 1rem;
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
    /* Change Password Section */
.change-password-section {
    margin-top: 1.5rem;
    background: #f0f9ff;
    border-left: 4px solid #3b82f6;
    padding: 1.25rem;
    border-radius: 0 0.5rem 0.5rem 0;
}

.change-password-section h3 {
    color: #1e40af;
    margin-bottom: 1rem;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.password-form {
    display: grid;
    gap: 1rem;
}

.password-input-group {
    position: relative;
}

.password-input-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #1e40af;
    font-weight: 500;
    font-size: 0.875rem;
}

.password-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.password-input {
    width: 100%;
    padding: 10px 40px 10px 12px;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    font-size: 0.9375rem;
    background: white;
    outline: none;
    transition: border-color 0.3s;
}

.password-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.password-toggle {
    position: absolute;
    right: 12px;
    background: transparent;
    border: none;
    cursor: pointer;
    color: #6b7280;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.password-toggle:hover {
    color: #3b82f6;
}

.password-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 0.5rem;
}

.change-password-btn {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: background 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.change-password-btn:hover {
    background: #2563eb;
}

.cancel-password-btn {
    background: #6b7280;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: background 0.3s;
}

.cancel-password-btn:hover {
    background: #4b5563;
}

.toggle-password-form-btn {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: background 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.toggle-password-form-btn:hover {
    background: #2563eb;
}

#passwordFormContainer {
    margin-top: 1rem;
}
        </style>
</head>
<body>
    <header>
    <a href="homepage.php" class="logo-section">
        <button 
            class="back-btn" 
            onclick="event.preventDefault(); window.location.href='homepage.php'"
            title="Go back"
        >
            <i class="fas fa-arrow-left"></i>
        </button>
        <img 
            src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRTDCuh4kIpAtR-QmjA1kTjE_8-HSd8LSt3Gw&s" 
            alt="Logo"
            class="logo-img"
        />
            <div class="logo-text">
                <div>Barangay 170</div>
                <div>Community Portal</div>
            </div>
        </a>
    </header>

    <div class="main-container">
        <div class="profile-card">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-picture-container">
                    <?php if ($profile_picture && file_exists($profile_picture)): ?>
                        <img src="<?php echo htmlspecialchars($profile_picture); ?>" 
                             alt="Profile Picture" 
                             class="profile-picture-img"
                             id="profilePicturePreview">
                    <?php else: ?>
                        <div class="profile-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    <?php endif; ?>
                    <button type="button" class="change-picture-btn" onclick="document.getElementById('profilePictureInput').click()">
                        <i class="fas fa-camera"></i>
                    </button>
                    <input type="file" id="profilePictureInput" accept="image/*" onchange="uploadProfilePicture(this)">
                </div>
                <div id="profilePictureMessage"></div>
                
                <h1><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']); ?></h1>
                <div class="profile-email">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="profile-info">
                <h2>Profile Information</h2>
                
                <div class="info-grid">
                    <!-- Contact Number -->
                    <div class="info-card editable-field">
                        <div class="info-card-header">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h3>Contact Number</h3>
                        </div>
                        <p id="contactDisplay">
                            <?php echo htmlspecialchars($user['contact_number']); ?>
                            <button type="button" class="edit-button" onclick="editContact()">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </p>
                        <div id="contactEdit" style="display: none;">
                            <input type="text" 
                                   id="contactInput" 
                                   class="edit-input" 
                                   value="<?php echo htmlspecialchars($user['contact_number']); ?>"
                                   placeholder="Enter contact number">
                            <div class="edit-actions">
                                <button type="button" class="save-button" onclick="saveContact()">
                                    <i class="fas fa-check"></i> Save
                                </button>
                                <button type="button" class="cancel-button" onclick="cancelContactEdit()">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </div>
                        <div id="contactMessage"></div>
                    </div>

                    <!-- Account Type -->
<div class="info-card">
    <div class="info-card-header">
        <div class="info-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h3>Account Type</h3>
    </div>
    <p>
        <?php 
        if ($is_admin) {
            echo 'Admin (Barangay Official)';
        } else {
            echo ' User ';
            if (isset($user['resident_type']) && !empty($user['resident_type'])) {
                echo ' - ' . ucfirst($user['resident_type']);
            }
        }
        ?>
    </p>
</div>

                    <!-- Member Since -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <h3>Member Since</h3>
                        </div>
                        <p><?php echo $join_date; ?></p>
                    </div>

                    <?php if (isset($user['barangay']) && !empty($user['barangay'])): ?>
                    <!-- Barangay -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3>Address</h3>
                        </div>
                        <p><?php echo htmlspecialchars($user['barangay']); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($user['id_type']) && !empty($user['id_type'])): ?>
                    <!-- Valid ID Type -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <h3>Valid ID Type</h3>
                        </div>
                        <p class="capitalize"><?php echo htmlspecialchars(str_replace('-', ' ', $user['id_type'])); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($user['file_path']) && !empty($user['file_path'])): ?>
                    <!-- Valid ID Document -->
                    <div class="info-card" style="grid-column: 1 / -1;">
                        <div class="info-card-header">
                            <div class="info-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <h3>Valid ID Document</h3>
                        </div>
                        <div class="id-image">
                            <img 
                                src="<?php echo htmlspecialchars($user['file_path']); ?>" 
                                alt="Valid ID"
                            />
                            <p>ID verified during registration</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Account Status -->
                <div class="account-status">
                    <h3>Account Status</h3>
                    <div class="status-indicator">
                        <span class="status-dot"></span>
                        <span class="status-text">Active and in good standing</span>
                    </div>
                </div>

                <!-- Change Password Section -->
<div class="change-password-section">
    <h3>
        <i class="fas fa-lock"></i>
        Change Password
    </h3>
    <button type="button" class="toggle-password-form-btn" onclick="togglePasswordForm()">
        <i class="fas fa-key"></i>
        <span id="togglePasswordText">Change Password</span>
    </button>
    
    <div id="passwordFormContainer" style="display: none;">
        <form class="password-form" onsubmit="changePassword(event)">
            <div class="password-input-group">
                <label for="currentPassword">Current Password</label>
                <div class="password-input-wrapper">
                    <input 
                        type="password" 
                        id="currentPassword" 
                        class="password-input" 
                        placeholder="Enter current password"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePasswordVisibility('currentPassword', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="password-input-group">
                <label for="newPassword">New Password</label>
                <div class="password-input-wrapper">
                    <input 
                        type="password" 
                        id="newPassword" 
                        class="password-input" 
                        placeholder="Enter new password (min. 6 characters)"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePasswordVisibility('newPassword', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="password-input-group">
                <label for="confirmNewPassword">Confirm New Password</label>
                <div class="password-input-wrapper">
                    <input 
                        type="password" 
                        id="confirmNewPassword" 
                        class="password-input" 
                        placeholder="Confirm new password"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePasswordVisibility('confirmNewPassword', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div id="passwordMessage"></div>

            <div class="password-actions">
                <button type="submit" class="change-password-btn">
                    <i class="fas fa-check"></i>
                    Update Password
                </button>
                <button type="button" class="cancel-password-btn" onclick="togglePasswordForm()">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

                <!-- Additional Info for Admin -->
                <?php if ($is_admin): ?>
                <div class="admin-info">
                    <h3>Admin Access</h3>
                    <p>
                        You have administrative privileges to manage health requests, 
                        update request statuses, and send notifications to citizens.
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <?php if (!$is_admin): ?>
            <a href="submitreq.php" class="action-card">
                <div class="action-icon">📝</div>
                <h3>Submit Request</h3>
                <p>Create a new health request</p>
            </a>

            <a href="trackreq.php" class="action-card">
                <div class="action-icon">🔍</div>
                <h3>Track Request</h3>
                <p>Check your request status</p>
            </a>
            <?php else: ?>
            <a href="admin-dashboard.php" class="action-card">
                <div class="action-icon">📊</div>
                <h3>Admin Dashboard</h3>
                <p>Manage all health requests</p>
            </a>
            <?php endif; ?>

            <form method="POST" style="display: contents;">
                <button type="submit" name="logout" class="action-card logout" style="text-align: center;">
                    <div class="action-icon">🚪</div>
                    <h3>Logout</h3>
                    <p>Sign out of your account</p>
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Barangay Health Office -->
                <div class="footer-section">
                    <h3>🏢 Barangay Health Office</h3>
                    <div class="footer-content">
                        <div class="footer-item">
                            <div class="footer-label">📍 Address</div>
                            <div class="footer-value">Deparo, Caloocan City, Metro Manila</div>
                        </div>
                        <div class="footer-item">
                            <div class="footer-label">📞 Hotline</div>
                            <div class="footer-value">(02) 8123-4567</div>
                        </div>
                        <div class="footer-item">
                            <div class="footer-label">📧 Email</div>
                            <div class="footer-value">K1contrerascris@gmail.com</div>
                        </div>
                        <div class="footer-item">
                            <div class="footer-label">🕐 Office Hours</div>
                            <div class="footer-value">Mon-Fri, 8:00 AM - 5:00 PM</div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Hotlines -->
                <div class="footer-section">
                    <h3>📞 Emergency Hotlines</h3>
                    <div class="footer-content">
                        <div class="emergency-item">
                            <span>Police</span>
                            <span>(02) 8426-4663</span>
                        </div>
                        <div class="emergency-item">
                            <span>BFP</span>
                            <span>(02) 8245 0849</span>
                        </div>
                    </div>
                </div>

                <!-- Hospitals Near Barangay -->
                <div class="footer-section">
                    <h3>🏥 Hospitals Near Barangay</h3>
                    <div class="footer-content">
                        <div class="hospital-item">
                            <div class="hospital-name">Camarin Doctors Hospital</div>
                            <div class="hospital-phone">(02) 2-7004-2881</div>
                        </div>
                        <div class="hospital-item">
                            <div class="hospital-name">Caloocan City North Medical</div>
                            <div class="hospital-phone">(02) 8288 7077</div>
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
        // Contact Number Editing
function editContact() {
    document.getElementById('contactDisplay').style.display = 'none';
    document.getElementById('contactEdit').style.display = 'block';
    document.getElementById('contactInput').focus();
}

function cancelContactEdit() {
    document.getElementById('contactDisplay').style.display = 'block';
    document.getElementById('contactEdit').style.display = 'none';
    document.getElementById('contactMessage').innerHTML = '';
}

async function saveContact() {
    const contactInput = document.getElementById('contactInput');
    const contactNumber = contactInput.value.trim();
    
    if (!contactNumber) {
        showMessage('contactMessage', 'Contact number cannot be empty', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('update_contact', '1');
    formData.append('contact_number', contactNumber);
    
    try {
        const response = await fetch('api_update_profile.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update display
            document.getElementById('contactDisplay').innerHTML = 
                contactNumber + 
                ' <button type="button" class="edit-button" onclick="editContact()"><i class="fas fa-edit"></i> Edit</button>';
            
            cancelContactEdit();
            showMessage('contactMessage', result.message, 'success');
            
            // Clear success message after 3 seconds
            setTimeout(() => {
                document.getElementById('contactMessage').innerHTML = '';
            }, 3000);
        } else {
            showMessage('contactMessage', result.error, 'error');
        }
    } catch (error) {
        showMessage('contactMessage', 'An error occurred. Please try again.', 'error');
    }
}

// Profile Picture Upload
async function uploadProfilePicture(input) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    
    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!allowedTypes.includes(file.type)) {
        showMessage('profilePictureMessage', 'Invalid file type. Only JPG, PNG, and GIF allowed', 'error');
        return;
    }
    
    // Validate file size (5MB)
    if (file.size > 5 * 1024 * 1024) {
        showMessage('profilePictureMessage', 'File size too large. Maximum 5MB allowed', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('profile_picture', file);
    
    try {
        showMessage('profilePictureMessage', 'Uploading...', 'success');
        
        const response = await fetch('api_update_profile.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update preview
            const preview = document.getElementById('profilePicturePreview');
            if (preview) {
                preview.src = result.filepath + '?' + new Date().getTime(); // Add timestamp to force reload
            } else {
                // If no preview exists, create one
                const container = document.querySelector('.profile-picture-container');
                const avatar = container.querySelector('.profile-avatar');
                if (avatar) avatar.remove();
                
                const img = document.createElement('img');
                img.src = result.filepath + '?' + new Date().getTime();
                img.alt = 'Profile Picture';
                img.className = 'profile-picture-img';
                img.id = 'profilePicturePreview';
                container.insertBefore(img, container.firstChild);
            }
            
            showMessage('profilePictureMessage', result.message, 'success');
            
            // Clear success message after 3 seconds
            setTimeout(() => {
                document.getElementById('profilePictureMessage').innerHTML = '';
            }, 3000);
        } else {
            showMessage('profilePictureMessage', result.error, 'error');
        }
    } catch (error) {
        showMessage('profilePictureMessage', 'An error occurred. Please try again.', 'error');
    }
    
    // Clear the input
    input.value = '';
}

// Toggle Password Form
function togglePasswordForm() {
    const container = document.getElementById('passwordFormContainer');
    const toggleText = document.getElementById('togglePasswordText');
    const toggleBtn = document.querySelector('.toggle-password-form-btn');
    
    if (container.style.display === 'none') {
        container.style.display = 'block';
        toggleText.textContent = 'Hide Form';
        toggleBtn.querySelector('i').className = 'fas fa-eye-slash';
    } else {
        container.style.display = 'none';
        toggleText.textContent = 'Change Password';
        toggleBtn.querySelector('i').className = 'fas fa-key';
        // Clear form
        document.getElementById('currentPassword').value = '';
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmNewPassword').value = '';
        document.getElementById('passwordMessage').innerHTML = '';
    }
}

// Toggle Password Visibility
function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Change Password
async function changePassword(event) {
    event.preventDefault();
    
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmNewPassword = document.getElementById('confirmNewPassword').value;
    
    // Validation
    if (newPassword.length < 6) {
        showMessage('passwordMessage', 'New password must be at least 6 characters long', 'error');
        return;
    }
    
    if (newPassword !== confirmNewPassword) {
        showMessage('passwordMessage', 'New passwords do not match', 'error');
        return;
    }
    
    if (currentPassword === newPassword) {
        showMessage('passwordMessage', 'New password must be different from current password', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('change_password', '1');
    formData.append('current_password', currentPassword);
    formData.append('new_password', newPassword);
    
    try {
        showMessage('passwordMessage', 'Updating password...', 'success');
        
        const response = await fetch('api_update_profile.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('passwordMessage', result.message, 'success');
            
            // Clear form after 2 seconds
            setTimeout(() => {
                document.getElementById('currentPassword').value = '';
                document.getElementById('newPassword').value = '';
                document.getElementById('confirmNewPassword').value = '';
                togglePasswordForm();
            }, 2000);
        } else {
            showMessage('passwordMessage', result.error, 'error');
        }
    } catch (error) {
        showMessage('passwordMessage', 'An error occurred. Please try again.', 'error');
    }
}

// Helper function to show messages
function showMessage(elementId, message, type) {
    const element = document.getElementById(elementId);
    element.innerHTML = `<div class="${type}-message">${message}</div>`;
}
    </script>
</body>
</html>