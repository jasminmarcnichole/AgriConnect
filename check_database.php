<?php
require 'config.php';

echo "<h2>Database Verification Check</h2>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";

// Check if verification_status column exists
try {
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Users Table Columns:</h3>";
    echo "<ul>";
    foreach ($columns as $column) {
        echo "<li>$column</li>";
    }
    echo "</ul>";
    
    if (in_array('verification_status', $columns)) {
        echo "<p class='success'>✅ verification_status column EXISTS</p>";
    } else {
        echo "<p class='error'>❌ verification_status column MISSING - You need to update your database!</p>";
        echo "<p class='info'>Run this SQL command:</p>";
        echo "<pre>ALTER TABLE users ADD COLUMN verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER role;</pre>";
    }
    
    if (in_array('id_document', $columns)) {
        echo "<p class='success'>✅ id_document column EXISTS</p>";
    } else {
        echo "<p class='error'>❌ id_document column MISSING - You need to update your database!</p>";
        echo "<p class='info'>Run this SQL command:</p>";
        echo "<pre>ALTER TABLE users ADD COLUMN id_document VARCHAR(255) NULL AFTER verification_status;</pre>";
    }
    
    // Check role enum values
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
    $roleInfo = $stmt->fetch();
    echo "<h3>Role Column Type:</h3>";
    echo "<pre>" . $roleInfo['Type'] . "</pre>";
    
    if (strpos($roleInfo['Type'], 'admin') !== false) {
        echo "<p class='success'>✅ 'admin' role EXISTS in enum</p>";
    } else {
        echo "<p class='error'>❌ 'admin' role MISSING from enum - You need to update your database!</p>";
        echo "<p class='info'>Run this SQL command:</p>";
        echo "<pre>ALTER TABLE users MODIFY COLUMN role ENUM('buyer', 'seller', 'admin') NOT NULL;</pre>";
    }
    
    // Check if admin user exists
    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'admin'");
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "<p class='success'>✅ Admin user EXISTS</p>";
        echo "<p class='info'>Email: " . htmlspecialchars($admin['email']) . "</p>";
    } else {
        echo "<p class='error'>❌ Admin user MISSING - You need to create an admin account!</p>";
        echo "<p class='info'>Run this SQL command:</p>";
        echo "<pre>INSERT INTO users (full_name, email, password_hash, role, verification_status) 
VALUES ('Admin', 'admin@agriconnect.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'approved');</pre>";
        echo "<p class='info'>Password will be: admin123</p>";
    }
    
    // Check sellers
    $stmt = $pdo->query("SELECT id, full_name, email, role, verification_status FROM users WHERE role = 'seller'");
    $sellers = $stmt->fetchAll();
    
    echo "<h3>Seller Accounts:</h3>";
    if (count($sellers) > 0) {
        echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th></tr>";
        foreach ($sellers as $seller) {
            echo "<tr>";
            echo "<td>" . $seller['id'] . "</td>";
            echo "<td>" . htmlspecialchars($seller['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($seller['email']) . "</td>";
            echo "<td>" . $seller['verification_status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='info'>No seller accounts found. Register a seller to test the system.</p>";
    }
    
    echo "<hr>";
    echo "<h3>Summary:</h3>";
    
    $issues = [];
    if (!in_array('verification_status', $columns)) $issues[] = "verification_status column missing";
    if (!in_array('id_document', $columns)) $issues[] = "id_document column missing";
    if (strpos($roleInfo['Type'], 'admin') === false) $issues[] = "admin role missing from enum";
    if (!$admin) $issues[] = "admin user missing";
    
    if (count($issues) > 0) {
        echo "<p class='error'><strong>❌ Database needs updates:</strong></p>";
        echo "<ul>";
        foreach ($issues as $issue) {
            echo "<li class='error'>$issue</li>";
        }
        echo "</ul>";
        echo "<p class='info'><strong>Solution:</strong> Run the updated schema.sql file:</p>";
        echo "<pre>mysql -u root -p
DROP DATABASE IF EXISTS agi_connect;
CREATE DATABASE agi_connect;
USE agi_connect;
SOURCE e:/xampp/htdocs/AgriConnect/database/schema.sql;
QUIT;</pre>";
    } else {
        echo "<p class='success'><strong>✅ Database is properly configured!</strong></p>";
        echo "<p class='info'>If you're still having issues, check:</p>";
        echo "<ul>";
        echo "<li>Make sure you're logged in as admin</li>";
        echo "<li>Check browser console for JavaScript errors (F12)</li>";
        echo "<li>Verify the seller you're trying to approve has role='seller'</li>";
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    echo "<p class='error'>Database Error: " . $e->getMessage() . "</p>";
}
?>
