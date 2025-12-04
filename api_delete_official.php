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

$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing official ID']);
    exit();
}

try {
    // Get official info before deleting - Add this block
    $stmt = $pdo->prepare("SELECT name, position FROM barangay_officials WHERE id = ?");
    $stmt->execute([$id]);
    $official = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$official) {
        http_response_code(404);
        echo json_encode(['error' => 'Official not found']);
        exit();
    }
    
    // Delete official
    $stmt = $pdo->prepare("DELETE FROM barangay_officials WHERE id = ?");
    $stmt->execute([$id]);
    
    // Log to audit trail - Add these lines
$admin_id = $_SESSION['user_id'];
$admin_email = $_SESSION['user_email'] ?? 'Unknown';
logOfficialDelete($admin_id, $admin_email, $id, $official['name'], $official['position']);;
    
    echo json_encode(['success' => true, 'message' => 'Official deleted successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>