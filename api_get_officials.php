<?php
// Prevent any output before JSON
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

// Check if db_connection.php exists
if (!file_exists('db_connection.php')) {
    echo json_encode(['error' => 'Database connection file not found']);
    exit();
}

require_once 'db_connection.php';

try {
    // Check if connection exists
    if (!isset($pdo)) {
        throw new Exception('Database connection not established');
    }
    
    // Check if table exists
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'barangay_officials'");
    if ($tableCheck->rowCount() === 0) {
        throw new Exception('Table barangay_officials does not exist');
    }
    
    $stmt = $pdo->query("SELECT * FROM barangay_officials ORDER BY display_order ASC");
    $officials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($officials);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>