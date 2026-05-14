<?php
require 'config.php';

// Get seller email from URL parameter
$email = $_GET['email'] ?? '';

if (!$email) {
    echo "<h2>Check Seller Login Status</h2>";
    echo "<form method='GET'>";
    echo "<label>Enter seller email:</label><br>";
    echo "<input type='email' name='email' required style='padding:10px; width:300px;'><br><br>";
    echo "<button type='submit' style='padding:10px 20px;'>Check Status</button>";
    echo "</form>";
    exit;
}

echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;} table{border-collapse:collapse;} td,th{border:1px solid #ddd;padding:10px;text-align:left;}</style>";

echo "<h2>Seller Login Debug for: " . htmlspecialchars($email) . "</h2>";

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo "<p class='error'>❌ User not found with this email</p>";
        echo "<p class='info'>Make sure the email is correct and the user is registered.</p>";
        exit;
    }
    
    echo "<h3>User Information:</h3>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Value</th><th>Status</th></tr>";
    
    // Check each field
    echo "<tr><td>ID</td><td>" . $user['id'] . "</td><td class='success'>✓</td></tr>";
    echo "<tr><td>Full Name</td><td>" . htmlspecialchars($user['full_name']) . "</td><td class='success'>✓</td></tr>";
    echo "<tr><td>Email</td><td>" . htmlspecialchars($user['email']) . "</td><td class='success'>✓</td></tr>";
    echo "<tr><td>Role</td><td>" . $user['role'] . "</td><td class='" . ($user['role'] === 'seller' ? 'success' : 'error') . "'>" . ($user['role'] === 'seller' ? '✓ Seller' : '✗ Not a seller') . "</td></tr>";
    
    // Check verification_status
    if (isset($user['verification_status'])) {
        $statusClass = $user['verification_status'] === 'approved' ? 'success' : 'error';
        $statusIcon = $user['verification_status'] === 'approved' ? '✓' : '✗';
        echo "<tr><td>Verification Status</td><td>" . $user['verification_status'] . "</td><td class='$statusClass'>$statusIcon</td></tr>";
    } else {
        echo "<tr><td>Verification Status</td><td class='error'>Column doesn't exist!</td><td class='error'>✗ Database needs update</td></tr>";
    }
    
    // Check id_document
    if (isset($user['id_document'])) {
        echo "<tr><td>ID Document</td><td>" . ($user['id_document'] ? $user['id_document'] : 'None') . "</td><td class='" . ($user['id_document'] ? 'success' : 'info') . "'>" . ($user['id_document'] ? '✓' : 'No document') . "</td></tr>";
    } else {
        echo "<tr><td>ID Document</td><td class='error'>Column doesn't exist!</td><td class='error'>✗ Database needs update</td></tr>";
    }
    
    echo "<tr><td>Created At</td><td>" . $user['created_at'] . "</td><td class='success'>✓</td></tr>";
    echo "</table>";
    
    // Login check simulation
    echo "<h3>Login Check Simulation:</h3>";
    
    $canLogin = true;
    $blockReason = '';
    
    if ($user['role'] !== 'seller') {
        $canLogin = false;
        $blockReason = "User role is '{$user['role']}', not 'seller'";
    } elseif (!isset($user['verification_status'])) {
        $canLogin = false;
        $blockReason = "verification_status column doesn't exist in database";
    } elseif ($user['verification_status'] === 'pending') {
        $canLogin = false;
        $blockReason = "Verification status is 'pending' - needs admin approval";
    } elseif ($user['verification_status'] === 'rejected') {
        $canLogin = false;
        $blockReason = "Verification status is 'rejected' - account was rejected by admin";
    } elseif ($user['verification_status'] !== 'approved') {
        $canLogin = false;
        $blockReason = "Verification status is '{$user['verification_status']}' - must be 'approved'";
    }
    
    if ($canLogin) {
        echo "<p class='success'><strong>✅ This seller CAN login!</strong></p>";
        echo "<p class='info'>All checks passed. If login still fails, check:</p>";
        echo "<ul>";
        echo "<li>Password is correct</li>";
        echo "<li>Browser cookies are enabled</li>";
        echo "<li>Session is working properly</li>";
        echo "</ul>";
    } else {
        echo "<p class='error'><strong>❌ This seller CANNOT login</strong></p>";
        echo "<p class='error'><strong>Reason:</strong> $blockReason</p>";
        
        // Provide solution
        echo "<h3>Solution:</h3>";
        if (!isset($user['verification_status'])) {
            echo "<p class='info'>Run the database update script:</p>";
            echo "<pre>ALTER TABLE users ADD COLUMN verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER role;</pre>";
        } elseif ($user['verification_status'] === 'pending') {
            echo "<p class='info'>Admin needs to approve this seller. Login as admin and approve from admin dashboard.</p>";
            echo "<p class='info'>Or manually approve with SQL:</p>";
            echo "<pre>UPDATE users SET verification_status = 'approved' WHERE email = '" . htmlspecialchars($email) . "';</pre>";
        } elseif ($user['verification_status'] === 'rejected') {
            echo "<p class='info'>Change status to approved:</p>";
            echo "<pre>UPDATE users SET verification_status = 'approved' WHERE email = '" . htmlspecialchars($email) . "';</pre>";
        }
    }
    
    // Quick fix button
    if (!$canLogin && isset($user['verification_status']) && $user['verification_status'] !== 'approved') {
        echo "<hr>";
        echo "<h3>Quick Fix:</h3>";
        echo "<form method='POST' action='debug_fix_seller.php'>";
        echo "<input type='hidden' name='user_id' value='" . $user['id'] . "'>";
        echo "<button type='submit' style='padding:10px 20px; background:#28a745; color:white; border:none; cursor:pointer;'>Approve This Seller Now</button>";
        echo "</form>";
    }
    
} catch (PDOException $e) {
    echo "<p class='error'>Database Error: " . $e->getMessage() . "</p>";
}
?>

<hr>
<p><a href="?">Check another seller</a> | <a href="check_database.php">Check Database Status</a> | <a href="admin_dashboard.php">Admin Dashboard</a></p>
