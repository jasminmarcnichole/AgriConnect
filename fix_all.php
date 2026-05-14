<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agri-Connect System Diagnostics</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2d5016; border-bottom: 3px solid #6b8e23; padding-bottom: 10px; }
        h2 { color: #6b8e23; margin-top: 30px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .info { color: #17a2b8; }
        .card { background: #f8f9fa; padding: 20px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #6b8e23; }
        .btn { display: inline-block; padding: 10px 20px; background: #6b8e23; color: white; text-decoration: none; border-radius: 5px; margin: 5px; border: none; cursor: pointer; }
        .btn:hover { background: #5a7a1e; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
        th { background: #6b8e23; color: white; }
        tr:nth-child(even) { background: #f8f9fa; }
        .status-badge { padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-warning { background: #ffc107; color: black; }
        pre { background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Agri-Connect System Diagnostics</h1>
        <p>This page checks your system and helps fix common issues.</p>

        <?php
        require 'config.php';

        // Check 1: Database Structure
        echo "<h2>1. Database Structure Check</h2>";
        echo "<div class='card'>";
        
        try {
            $stmt = $pdo->query("SHOW COLUMNS FROM users");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $hasVerificationStatus = in_array('verification_status', $columns);
            $hasIdDocument = in_array('id_document', $columns);
            
            $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
            $roleColumn = $stmt->fetch();
            $hasAdminRole = strpos($roleColumn['Type'], 'admin') !== false;
            
            echo "<table>";
            echo "<tr><th>Check</th><th>Status</th><th>Action</th></tr>";
            
            echo "<tr><td>verification_status column</td><td>";
            if ($hasVerificationStatus) {
                echo "<span class='success'>✅ EXISTS</span>";
            } else {
                echo "<span class='error'>❌ MISSING</span>";
            }
            echo "</td><td>";
            if (!$hasVerificationStatus) {
                echo "<form method='POST' style='display:inline;'><input type='hidden' name='fix' value='add_verification_status'><button type='submit' class='btn'>Fix Now</button></form>";
            }
            echo "</td></tr>";
            
            echo "<tr><td>id_document column</td><td>";
            if ($hasIdDocument) {
                echo "<span class='success'>✅ EXISTS</span>";
            } else {
                echo "<span class='error'>❌ MISSING</span>";
            }
            echo "</td><td>";
            if (!$hasIdDocument) {
                echo "<form method='POST' style='display:inline;'><input type='hidden' name='fix' value='add_id_document'><button type='submit' class='btn'>Fix Now</button></form>";
            }
            echo "</td></tr>";
            
            echo "<tr><td>Admin role in enum</td><td>";
            if ($hasAdminRole) {
                echo "<span class='success'>✅ EXISTS</span>";
            } else {
                echo "<span class='error'>❌ MISSING</span>";
            }
            echo "</td><td>";
            if (!$hasAdminRole) {
                echo "<form method='POST' style='display:inline;'><input type='hidden' name='fix' value='add_admin_role'><button type='submit' class='btn'>Fix Now</button></form>";
            }
            echo "</td></tr>";
            
            echo "</table>";
            
        } catch (PDOException $e) {
            echo "<p class='error'>Database Error: " . $e->getMessage() . "</p>";
        }
        
        echo "</div>";

        // Check 2: Admin Account
        echo "<h2>2. Admin Account Check</h2>";
        echo "<div class='card'>";
        
        try {
            $stmt = $pdo->query("SELECT * FROM users WHERE role = 'admin'");
            $admins = $stmt->fetchAll();
            
            if (count($admins) > 0) {
                echo "<p class='success'>✅ Admin account exists</p>";
                echo "<table>";
                echo "<tr><th>Email</th><th>Name</th><th>Status</th></tr>";
                foreach ($admins as $admin) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($admin['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($admin['full_name']) . "</td>";
                    echo "<td><span class='badge-success status-badge'>Active</span></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='error'>❌ No admin account found</p>";
                echo "<form method='POST'><input type='hidden' name='fix' value='create_admin'><button type='submit' class='btn'>Create Admin Account</button></form>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
        }
        
        echo "</div>";

        // Check 3: Sellers Status
        echo "<h2>3. Seller Accounts Status</h2>";
        echo "<div class='card'>";
        
        try {
            $stmt = $pdo->query("SELECT id, full_name, email, verification_status FROM users WHERE role = 'seller' ORDER BY created_at DESC");
            $sellers = $stmt->fetchAll();
            
            if (count($sellers) > 0) {
                echo "<table>";
                echo "<tr><th>Name</th><th>Email</th><th>Status</th><th>Action</th></tr>";
                foreach ($sellers as $seller) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($seller['full_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($seller['email']) . "</td>";
                    echo "<td>";
                    
                    $status = $seller['verification_status'] ?? 'unknown';
                    if ($status === 'approved') {
                        echo "<span class='badge-success status-badge'>APPROVED</span>";
                    } elseif ($status === 'pending') {
                        echo "<span class='badge-warning status-badge'>PENDING</span>";
                    } elseif ($status === 'rejected') {
                        echo "<span class='badge-danger status-badge'>REJECTED</span>";
                    } else {
                        echo "<span class='badge-danger status-badge'>UNKNOWN</span>";
                    }
                    
                    echo "</td>";
                    echo "<td>";
                    
                    if ($status !== 'approved') {
                        echo "<form method='POST' style='display:inline;'>";
                        echo "<input type='hidden' name='fix' value='approve_seller'>";
                        echo "<input type='hidden' name='seller_id' value='" . $seller['id'] . "'>";
                        echo "<button type='submit' class='btn'>Approve</button>";
                        echo "</form>";
                    } else {
                        echo "<span class='success'>✓ Can Login</span>";
                    }
                    
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='info'>ℹ️ No seller accounts found. Register a seller to test the system.</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
        }
        
        echo "</div>";

        // Handle fixes
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fix'])) {
            echo "<h2>🔧 Applying Fix...</h2>";
            echo "<div class='card'>";
            
            try {
                switch ($_POST['fix']) {
                    case 'add_verification_status':
                        $pdo->exec("ALTER TABLE users ADD COLUMN verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER role");
                        echo "<p class='success'>✅ Added verification_status column</p>";
                        break;
                        
                    case 'add_id_document':
                        $pdo->exec("ALTER TABLE users ADD COLUMN id_document VARCHAR(255) NULL AFTER verification_status");
                        echo "<p class='success'>✅ Added id_document column</p>";
                        break;
                        
                    case 'add_admin_role':
                        $pdo->exec("ALTER TABLE users MODIFY COLUMN role ENUM('buyer', 'seller', 'admin') NOT NULL");
                        echo "<p class='success'>✅ Added admin role to enum</p>";
                        break;
                        
                    case 'create_admin':
                        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role, verification_status) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute(['Admin', 'admin@agriconnect.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'approved']);
                        echo "<p class='success'>✅ Created admin account</p>";
                        echo "<p class='info'>Email: admin@agriconnect.com<br>Password: admin123</p>";
                        break;
                        
                    case 'approve_seller':
                        $sellerId = $_POST['seller_id'] ?? 0;
                        $stmt = $pdo->prepare("UPDATE users SET verification_status = 'approved' WHERE id = ?");
                        $stmt->execute([$sellerId]);
                        echo "<p class='success'>✅ Seller approved successfully!</p>";
                        break;
                }
                
                echo "<p><a href='fix_all.php' class='btn'>Refresh Page</a></p>";
                
            } catch (PDOException $e) {
                echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
            }
            
            echo "</div>";
        }

        // Quick Links
        echo "<h2>🔗 Quick Links</h2>";
        echo "<div class='card'>";
        echo "<a href='index.php' class='btn'>Home</a>";
        echo "<a href='admin_dashboard.php' class='btn'>Admin Dashboard</a>";
        echo "<a href='debug_seller_login.php' class='btn'>Debug Seller Login</a>";
        echo "<a href='check_database.php' class='btn'>Detailed Database Check</a>";
        echo "</div>";

        // Summary
        echo "<h2>📊 System Summary</h2>";
        echo "<div class='card'>";
        
        $allGood = $hasVerificationStatus && $hasIdDocument && $hasAdminRole && count($admins) > 0;
        
        if ($allGood) {
            echo "<p class='success' style='font-size: 18px;'>✅ System is properly configured!</p>";
            echo "<p>All database checks passed. You can now:</p>";
            echo "<ul>";
            echo "<li>Register sellers with ID upload</li>";
            echo "<li>Login as admin to approve sellers</li>";
            echo "<li>Approved sellers can login and access features</li>";
            echo "</ul>";
        } else {
            echo "<p class='error' style='font-size: 18px;'>⚠️ System needs configuration</p>";
            echo "<p>Use the 'Fix Now' buttons above to resolve issues.</p>";
        }
        
        echo "</div>";
        ?>
    </div>
</body>
</html>
