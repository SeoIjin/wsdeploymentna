<?php
// audit_trail_helper.php - Helper functions for audit trail logging

function logAuditTrail($admin_id, $admin_email, $action_type, $action_description, $target_type = null, $target_id = null, $old_value = null, $new_value = null) {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "users";

    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            error_log("Audit Trail DB Connection Failed: " . $conn->connect_error);
            return false;
        }

        // Get IP address
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        // Handle proxies
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }

        // Get user agent
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

        // Prepare statement
        $stmt = $conn->prepare("INSERT INTO audit_trail (admin_id, admin_email, action_type, action_description, target_type, target_id, old_value, new_value, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("isssssssss", 
            $admin_id, 
            $admin_email, 
            $action_type, 
            $action_description, 
            $target_type, 
            $target_id, 
            $old_value, 
            $new_value, 
            $ip_address, 
            $user_agent
        );
        
        $result = $stmt->execute();
        
        $stmt->close();
        $conn->close();
        
        return $result;
    } catch (Exception $e) {
        error_log("Audit Trail Logging Error: " . $e->getMessage());
        return false;
    }
}

// Specific logging functions for different actions
function logAdminLogin($admin_id, $admin_email) {
    return logAuditTrail(
        $admin_id,
        $admin_email,
        'LOGIN',
        'Admin logged into the system',
        'system',
        null
    );
}

function logAdminLogout($admin_id, $admin_email) {
    return logAuditTrail(
        $admin_id,
        $admin_email,
        'LOGOUT',
        'Admin logged out from the system',
        'system',
        null
    );
}

function logRequestUpdate($admin_id, $admin_email, $ticket_id, $old_status, $new_status, $message) {
    $description = "Updated request $ticket_id status from '$old_status' to '$new_status'";
    if ($message) {
        $description .= " with message: " . substr($message, 0, 100);
    }
    
    return logAuditTrail(
        $admin_id,
        $admin_email,
        'STATUS_CHANGE',
        $description,
        'request',
        $ticket_id,
        $old_status,
        $new_status
    );
}

function logRequestDelete($admin_id, $admin_email, $ticket_id, $request_type) {
    return logAuditTrail(
        $admin_id,
        $admin_email,
        'REQUEST_DELETE',
        "Deleted request $ticket_id (Type: $request_type)",
        'request',
        $ticket_id,
        "Request: $request_type",
        null
    );
}

function logPriorityChange($admin_id, $admin_email, $ticket_id, $old_priority, $new_priority) {
    return logAuditTrail(
        $admin_id,
        $admin_email,
        'PRIORITY_CHANGE',
        "Changed priority for request $ticket_id from '$old_priority' to '$new_priority'",
        'request',
        $ticket_id,
        $old_priority,
        $new_priority
    );
}

function logNotificationAdd($admin_id, $admin_email, $notification_type, $title) {
    return logAuditTrail(
        $admin_id,
        $admin_email,
        'NOTIFICATION_ADD',
        "Added new $notification_type notification: '$title'",
        'notification',
        null,
        null,
        $title
    );
}

function logNotificationDelete($admin_id, $admin_email, $notification_id, $title) {
    return logAuditTrail(
        $admin_id,
        $admin_email,
        'NOTIFICATION_DELETE',
        "Deleted notification: '$title'",
        'notification',
        $notification_id,
        $title,
        null
    );
}

function logUserView($admin_id, $admin_email, $user_id, $user_email) {
    return logAuditTrail(
        $admin_id,
        $admin_email,
        'USER_VIEW',
        "Viewed profile of user: $user_email",
        'user',
        $user_id
    );
}

function logOfficialAdd($admin_id, $admin_email, $name, $position) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        INSERT INTO audit_trail (admin_id, admin_email, action_type, action_description, target_type, new_value, ip_address, user_agent)
        VALUES (?, ?, 'OFFICIAL_ADD', ?, 'official', ?, ?, ?)
    ");
    
    $description = "Added new barangay official: '$name' ($position)";
    $new_value = "$name - $position";
    
    $stmt->execute([
        $admin_id,
        $admin_email,
        $description,
        $new_value,
        $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
        $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
    ]);
}

function logOfficialUpdate($admin_id, $admin_email, $official_id, $old_name, $new_name, $old_position, $new_position) {
    $changes = [];
    if ($old_name !== $new_name) {
        $changes[] = "name from '$old_name' to '$new_name'";
    }
    if ($old_position !== $new_position) {
        $changes[] = "position from '$old_position' to '$new_position'";
    }
    
    $description = "Updated barangay official: " . implode(', ', $changes);
    
    return logAuditTrail(
        $admin_id,
        $admin_email,
        'OFFICIAL_UPDATE',
        $description,
        'official',
        $official_id,
        "$old_name - $old_position",
        "$new_name - $new_position"
    );
}

function logOfficialDelete($admin_id, $admin_email, $official_id, $official_name, $position) {
    return logAuditTrail(
        $admin_id,
        $admin_email,
        'OFFICIAL_DELETE',
        "Deleted barangay official: '$official_name' ($position)",
        'official',
        $official_id,
        "$official_name - $position",
        null
    );
}

// Add this function to audit_trail_helper.php

function logRequestReject($admin_id, $admin_email, $ticket_id, $request_type, $rejection_reason) {
    global $pdo;
    
    $action_description = "Rejected request {$ticket_id} (Type: {$request_type}). Reason: {$rejection_reason}";
    
    $stmt = $pdo->prepare("
        INSERT INTO audit_trail 
        (admin_id, admin_email, action_type, action_description, target_type, target_id, old_value, new_value, ip_address, user_agent) 
        VALUES (?, ?, 'REQUEST_REJECT', ?, 'request', ?, ?, 'REJECTED', ?, ?)
    ");
    
    $stmt->execute([
        $admin_id,
        $admin_email,
        $action_description,
        $ticket_id,
        $request_type,
        $_SERVER['REMOTE_ADDR'] ?? null,
        $_SERVER['HTTP_USER_AGENT'] ?? null
    ]);
}


?>