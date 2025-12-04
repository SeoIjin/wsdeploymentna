<?php
session_start();
header('Content-Type: application/json');
require_once 'audit_trail_helper.php';

// Check if admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['ticket_id']) || !isset($data['request_type'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

$ticket_id = $data['ticket_id'];
$request_type = $data['request_type'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

try {
    // Get request details before deleting for audit trail
    $stmt = $conn->prepare("SELECT id, ticket_id, requesttype FROM requests WHERE ticket_id = ?");
    $stmt->bind_param("s", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();
    $stmt->close();
    
    if (!$request) {
        http_response_code(404);
        echo json_encode(['error' => 'Request not found']);
        exit();
    }
    
    // Delete the request
    $stmt = $conn->prepare("DELETE FROM requests WHERE ticket_id = ?");
    $stmt->bind_param("s", $ticket_id);
    
    if ($stmt->execute()) {
        // Log to audit trail
        $admin_id = $_SESSION['user_id'];
        $admin_email = $_SESSION['user_email'] ?? 'Unknown';
        logRequestDelete($admin_id, $admin_email, $ticket_id, $request_type);
        
        echo json_encode([
            'success' => true,
            'message' => 'Request deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete request']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>