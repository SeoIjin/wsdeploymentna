<?php
session_start();
require_once 'audit_trail_helper.php';

// Check admin authentication
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = isset($data['user_id']) ? intval($data['user_id']) : 0;
$action = isset($data['action']) ? $data['action'] : ''; // 'disable' or 'enable'

if ($userId <= 0 || !in_array($action, ['disable', 'enable'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit();
}

// Prevent admin from disabling their own account
if ($userId == $_SESSION['user_id']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'You cannot disable your own account']);
    exit();
}

// Get user details before update
$stmt = $conn->prepare("SELECT email, first_name, last_name, is_disabled FROM account WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'User not found']);
    exit();
}

$newStatus = ($action === 'disable') ? 1 : 0;
$oldStatus = $user['is_disabled'];

// Update account status
$stmt = $conn->prepare("UPDATE account SET is_disabled = ? WHERE id = ?");
$stmt->bind_param("ii", $newStatus, $userId);

if ($stmt->execute()) {
    $fullName = $user['first_name'] . ' ' . $user['last_name'];
    $actionText = ($action === 'disable') ? 'Disabled' : 'Enabled';
    
    // Log to audit trail
    logAuditTrail(
        $_SESSION['user_id'],
        $_SESSION['user_email'],
        'ACCOUNT_' . strtoupper($action),
        "$actionText account for user: $fullName ({$user['email']})",
        'account',
        (string)$userId,
        $oldStatus ? 'disabled' : 'enabled',
        $newStatus ? 'disabled' : 'enabled'
    );
    
    echo json_encode([
        'success' => true,
        'message' => "Account {$actionText} successfully",
        'is_disabled' => $newStatus
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to update account status']);
}

$stmt->close();
$conn->close();
?>