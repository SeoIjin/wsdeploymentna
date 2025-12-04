<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle contact number update
if (isset($_POST['update_contact'])) {
    $contact_number = trim($_POST['contact_number']);
    
    if (empty($contact_number)) {
        echo json_encode(['success' => false, 'error' => 'Contact number cannot be empty']);
        exit();
    }
    
    $sql = "UPDATE account SET contact_number = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $contact_number, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Contact number updated successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update contact number']);
    }
    
    $stmt->close();
}

// Handle profile picture upload
if (isset($_FILES['profile_picture'])) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    $file = $_FILES['profile_picture'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'File upload error']);
        exit();
    }
    
    if (!in_array($file['type'], $allowed_types)) {
        echo json_encode(['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, and GIF allowed']);
        exit();
    }
    
    if ($file['size'] > $max_size) {
        echo json_encode(['success' => false, 'error' => 'File size too large. Maximum 5MB allowed']);
        exit();
    }
    
    // Create uploads directory if it doesn't exist
    $upload_dir = 'uploads/profile_pictures/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . $user_id . '_' . time() . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Get old profile picture to delete it
        $sql = "SELECT profile_picture FROM account WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $old_data = $result->fetch_assoc();
        
        // Delete old profile picture if exists and not default
        if ($old_data && !empty($old_data['profile_picture']) && file_exists($old_data['profile_picture'])) {
            unlink($old_data['profile_picture']);
        }
        
        // Update database
        $sql = "UPDATE account SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $filepath, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Profile picture updated successfully', 'filepath' => $filepath]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update database']);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
    }
}

// Handle Change Password
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    
    // Validate new password length
    if (strlen($new_password) < 6) {
        echo json_encode(['success' => false, 'error' => 'New password must be at least 6 characters long']);
        exit();
    }
    
    // Get current password from database
    $stmt = $conn->prepare("SELECT password FROM account WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();
    
    // Verify current password (plain text comparison)
    if ($current_password !== $stored_password) {
        echo json_encode(['success' => false, 'error' => 'Current password is incorrect']);
        exit();
    }
    
    // Update password (store as plain text)
    $stmt = $conn->prepare("UPDATE account SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update password']);
    }
    
    $stmt->close();
}

$conn->close();
?>