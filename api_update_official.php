<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connection.php';
require_once 'audit_trail_helper.php'; // This line should be here

// Check if admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['name']) || !isset($data['position'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

try {
    // Get old values first - IMPORTANT: Do this BEFORE updating
    $stmt = $pdo->prepare("SELECT name, position FROM barangay_officials WHERE id = ?");
    $stmt->execute([$data['id']]);
    $oldData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$oldData) {
        http_response_code(404);
        echo json_encode(['error' => 'Official not found']);
        exit();
    }

// Handle file upload for update
$profile_picture = $oldData['profile_picture'] ?? null;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $file_type = $_FILES['profile_picture']['type'];
    
    if (!in_array($file_type, $allowed_types)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, and GIF allowed.']);
        exit();
    }
    
    $upload_dir = 'uploads/officials/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $new_filename = 'official_' . time() . '_' . uniqid() . '.' . $file_extension;
    $upload_path = $upload_dir . $new_filename;
    
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
        // Delete old picture if exists
        if ($profile_picture && file_exists($profile_picture)) {
            unlink($profile_picture);
        }
        $profile_picture = $upload_path;
    }
}
    
    // Update official
    $stmt = $pdo->prepare("UPDATE barangay_officials SET name = ?, position = ?, profile_picture = ? WHERE id = ?");
$stmt->execute([$data['name'], $data['position'], $profile_picture, $data['id']]);
    
    // Log to audit trail
    $admin_id = $_SESSION['user_id'];
    $admin_email = $_SESSION['user_email'] ?? 'Unknown';
    logOfficialUpdate(
        $admin_id, 
        $admin_email, 
        $data['id'], 
        $oldData['name'], 
        $data['name'], 
        $oldData['position'], 
        $data['position']
    );
    
    echo json_encode(['success' => true, 'message' => 'Official updated successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>