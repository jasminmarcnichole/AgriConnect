<?php
require 'config.php';

echo "<style>
body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
.container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
h1 { color: #2d5016; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
th { background: #6b8e23; color: white; }
tr:nth-child(even) { background: #f8f9fa; }
.badge { padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; display: inline-block; }
.badge-buyer { background: #17a2b8; color: white; }
.badge-seller { background: #ffc107; color: black; }
.badge-admin { background: #dc3545; color: white; }
.badge-approved { background: #28a745; color: white; }
.badge-pending { background: #ffc107; color: black; }
.badge-rejected { background: #dc3545; color: white; }
.success { color: #28a745; }
.error { color: #dc3545; }
.info { color: #17a2b8; }
.btn { display: inline-block; padding: 8px 15px; background: #6b8e23; color: white; text-decoration: none; border-radius: 5px; margin: 2px; font-size: 12px; border: none; cursor: pointer; }
.btn-small { padding: 5px 10px; font-size: 11px; }
</style>";

echo "<div class='container'>";
echo "<h1>👥 All Users in Database</h1>";

try {
    // Get all users
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
    
    if (count($users) === 0) {
        echo "<p class='error'>❌ No users found in database!</p>";
        echo "<p class='info'>The database might be empty or not properly set up.</p>";
    } else {
        echo "<p class='success'>✅ Found " . count($users) . " user(s) in database</p>";
        
        // Count by role
        $buyers = array_filter($users, fn($u) => $u['role'] === 'buyer');
        $sellers = array_filter($users, fn($u) => $u['role'] === 'seller');
        $admins = array_filter($users, fn($u) => $u['role'] === 'admin');
        
        echo "<p><strong>Summary:</strong> ";
        echo count($buyers) . " Buyers, ";
        echo count($sellers) . " Sellers, ";
        echo count($admins) . " Admins";
        echo "</p>";
        
        echo "<table>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Name</th>";
        echo "<th>Email</th>";
        echo "<th>Role</th>";
        echo "<th>Verification Status</th>";
        echo "<th>ID Document</th>";
        echo "<th>Created</th>";
        echo "<th>Actions</th>";
        echo "</tr>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['full_name']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($user['email']) . "</strong></td>";
            
            // Role badge
            echo "<td>";
            $roleClass = 'badge-' . $user['role'];
            echo "<span class='badge $roleClass'>" . strtoupper($user['role']) . "</span>";
            echo "</td>";
            
            // Verification status
            echo "<td>";
            if (isset($user['verification_status'])) {
                $statusClass = 'badge-' . $user['verification_status'];
                echo "<span class='badge $statusClass'>" . strtoupper($user['verification_status']) . "</span>";
            } else {
                echo "<span class='badge' style='background:#6c757d;color:white;'>N/A</span>";
            }
            echo "</td>";
            
            // ID Document
            echo "<td>";
            if (isset($user['id_document']) && $user['id_document']) {
                echo "<a href='uploads/id_documents/" . $user['id_document'] . "' target='_blank' class='btn btn-small'>View</a>";
            } else {
                echo "<span style='color:#999;'>None</span>";
            }
            echo "</td>";
            
            echo "<td>" . date('M d, Y', strtotime($user['created_at'])) . "</td>";
            
            // Actions
            echo "<td>";
            
            // Test login button
            echo "<form method='POST' action='test_login.php' style='display:inline;' target='_blank'>";
            echo "<input type='hidden' name='email' value='" . htmlspecialchars($user['email']) . "'>";
            echo "<button type='submit' class='btn btn-small' style='background:#17a2b8;'>Test Login</button>";
            echo "</form>";
            
            // Approve button for sellers
            if ($user['role'] === 'seller' && isset($user['verification_status']) && $user['verification_status'] !== 'approved') {
                echo "<form method='POST' style='display:inline;'>";
                echo "<input type='hidden' name='approve_user' value='" . $user['id'] . "'>";
                echo "<button type='submit' class='btn btn-small' style='background:#28a745;'>Approve</button>";
                echo "</form>";
            }
            
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Handle approval
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_user'])) {
            $userId = $_POST['approve_user'];
            $stmt = $pdo->prepare("UPDATE users SET verification_status = 'approved' WHERE id = ?");
            $stmt->execute([$userId]);
            echo "<script>alert('User approved!'); window.location.reload();</script>";
        }
        
        // Show sellers specifically
        if (count($sellers) > 0) {
            echo "<h2>🌾 Seller Accounts Details</h2>";
            echo "<table>";
            echo "<tr><th>Email</th><th>Status</th><th>Can Login?</th><th>Copy Email</th></tr>";
            
            foreach ($sellers as $seller) {
                echo "<tr>";
                echo "<td><strong>" . htmlspecialchars($seller['email']) . "</strong></td>";
                
                $status = $seller['verification_status'] ?? 'unknown';
                echo "<td><span class='badge badge-$status'>" . strtoupper($status) . "</span></td>";
                
                echo "<td>";
                if ($status === 'approved') {
                    echo "<span class='success'>✅ YES</span>";
                } else {
                    echo "<span class='error'>❌ NO (" . $status . ")</span>";
                }
                echo "</td>";
                
                echo "<td>";
                echo "<button class='btn btn-small' onclick=\"navigator.clipboard.writeText('" . htmlspecialchars($seller['email']) . "'); alert('Email copied!');\">Copy Email</button>";
                echo "</td>";
                
                echo "</tr>";
            }
            
            echo "</table>";
            
            echo "<div style='background:#fff3cd; padding:15px; border-radius:5px; margin:20px 0;'>";
            echo "<h3 style='margin-top:0;'>📋 How to Test Login:</h3>";
            echo "<ol>";
            echo "<li>Copy the seller's email using the button above</li>";
            echo "<li>Go to <a href='index.php' target='_blank'>Login Page</a></li>";
            echo "<li>Paste the email</li>";
            echo "<li>Enter the password you used during registration</li>";
            echo "<li>If you forgot the password, use the 'Test Login' button to see details</li>";
            echo "</ol>";
            echo "</div>";
        }
    }
    
    // Show database info
    echo "<h2>🗄️ Database Information</h2>";
    echo "<table>";
    echo "<tr><th>Database Name</th><td>" . $pdo->query("SELECT DATABASE()")->fetchColumn() . "</td></tr>";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<tr><th>Columns in users table</th><td>" . implode(', ', $columns) . "</td></tr>";
    
    echo "</table>";
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Database Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='fix_all.php' class='btn'>System Diagnostics</a> ";
echo "<a href='admin_dashboard.php' class='btn'>Admin Dashboard</a> ";
echo "<a href='index.php' class='btn'>Home</a></p>";

echo "</div>";
?>
