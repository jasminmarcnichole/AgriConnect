<?php
// Simple test to check if database is ready
require 'config.php';

header('Content-Type: application/json');

try {
    // Check if verification_status column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'verification_status'");
    $hasVerificationStatus = $stmt->rowCount() > 0;
    
    // Check if id_document column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'id_document'");
    $hasIdDocument = $stmt->rowCount() > 0;
    
    // Check if admin role exists
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
    $roleColumn = $stmt->fetch();
    $hasAdminRole = strpos($roleColumn['Type'], 'admin') !== false;
    
    // Check if admin user exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
    $adminCount = $stmt->fetch()['count'];
    
    $allGood = $hasVerificationStatus && $hasIdDocument && $hasAdminRole && $adminCount > 0;
    
    echo json_encode([
        'success' => $allGood,
        'checks' => [
            'verification_status_column' => $hasVerificationStatus,
            'id_document_column' => $hasIdDocument,
            'admin_role_exists' => $hasAdminRole,
            'admin_user_exists' => $adminCount > 0,
            'admin_count' => $adminCount
        ],
        'message' => $allGood ? 'Database is ready!' : 'Database needs updates. Run quick_fix.sql'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
