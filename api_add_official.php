<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connection.php';
require_once 'audit_trail_helper.php';

// Check if admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Handle file upload
$profile_picture = null;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $file_type = $_FILES['profile_picture']['type'];
    
    if (!in_array($file_type, $allowed_types)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, and GIF allowed.']);
        exit();
    }
    
    // Create uploads directory if it doesn't exist
    $upload_dir = 'uploads/officials/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Generate unique filename
    $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $new_filename = 'official_' . time() . '_' . uniqid() . '.' . $file_extension;
    $upload_path = $upload_dir . $new_filename;
    
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
        $profile_picture = $upload_path;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to upload file']);
        exit();
    }
}

$name = $_POST['name'] ?? '';
$position = $_POST['position'] ?? '';

if (empty($name) || empty($position)) {
    http_response_code(400);
    echo json_encode(['error' => 'Name and position are required']);
    exit();
}

try {
    // Get the highest display_order
    $stmt = $pdo->query("SELECT MAX(display_order) as max_order FROM barangay_officials");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_order = ($result['max_order'] ?? 0) + 1;
    
    // Insert new official
    $stmt = $pdo->prepare("INSERT INTO barangay_officials (name, position, profile_picture, display_order) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $position, $profile_picture, $next_order]);
    
    $official_id = $pdo->lastInsertId();
    
    // Log to audit trail
    $admin_id = $_SESSION['user_id'];
    $admin_email = $_SESSION['user_email'] ?? 'Unknown';
    logOfficialAdd($admin_id, $admin_email, $name, $position);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Official added successfully',
        'id' => $official_id,
        'profile_picture' => $profile_picture
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>