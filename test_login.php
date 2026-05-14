<?php
require 'config.php';

echo "<style>
body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
.container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
h1 { color: #2d5016; }
.success { color: #28a745; font-weight: bold; }
.error { color: #dc3545; font-weight: bold; }
.info { color: #17a2b8; }
.warning { color: #ffc107; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
th { background: #6b8e23; color: white; }
.btn { display: inline-block; padding: 10px 20px; background: #6b8e23; color: white; text-decoration: none; border-radius: 5px; margin: 5px; border: none; cursor: pointer; }
input[type='email'], input[type='password'] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
label { font-weight: bold; display: block; margin-top: 15px; }
</style>";

echo "<div class='container'>";
echo "<h1>🔐 Test Login System</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    echo "<h2>Login Attempt for: " . htmlspecialchars($email) . "</h2>";
    
    try {
        // Step 1: Check if user exists
        echo "<h3>Step 1: Checking if user exists...</h3>";
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            echo "<p class='error'>❌ FAILED: User not found with email: " . htmlspecialchars($email) . "</p>";
            echo "<p class='info'>This email is not registered in the database.</p>";
            echo "<p><a href='list_all_users.php' class='btn'>View All Users</a></p>";
        } else {
            echo "<p class='success'>✅ PASSED: User found!</p>";
            
            // Show user details
            echo "<table>";
            echo "<tr><th>Field</th><th>Value</th></tr>";
            echo "<tr><td>ID</td><td>" . $user['id'] . "</td></tr>";
            echo "<tr><td>Full Name</td><td>" . htmlspecialchars($user['full_name']) . "</td></tr>";
            echo "<tr><td>Email</td><td>" . htmlspecialchars($user['email']) . "</td></tr>";
            echo "<tr><td>Role</td><td><strong>" . $user['role'] . "</strong></td></tr>";
            
            if (isset($user['verification_status'])) {
                echo "<tr><td>Verification Status</td><td><strong>" . $user['verification_status'] . "</strong></td></tr>";
            } else {
                echo "<tr><td>Verification Status</td><td><span class='error'>Column doesn't exist</span></td></tr>";
            }
            
            echo "</table>";
            
            // Step 2: Check password
            echo "<h3>Step 2: Checking password...</h3>";
            if ($password) {
                if (password_verify($password, $user['password_hash'])) {
                    echo "<p class='success'>✅ PASSED: Password is correct!</p>";
                } else {
                    echo "<p class='error'>❌ FAILED: Password is incorrect!</p>";
                    echo "<p class='info'>The password you entered doesn't match the stored hash.</p>";
                    echo "<p class='warning'>If you forgot the password, you can reset it in the database.</p>";
                }
            } else {
                echo "<p class='warning'>⚠️ SKIPPED: No password provided</p>";
            }
            
            // Step 3: Check verification status (for sellers)
            if ($user['role'] === 'seller') {
                echo "<h3>Step 3: Checking seller verification status...</h3>";
                
                if (!isset($user['verification_status'])) {
                    echo "<p class='error'>❌ FAILED: verification_status column doesn't exist!</p>";
                    echo "<p class='info'>Database needs to be updated.</p>";
                } elseif ($user['verification_status'] === 'pending') {
                    echo "<p class='error'>❌ BLOCKED: Verification status is 'pending'</p>";
                    echo "<p class='info'>Admin needs to approve this seller.</p>";
                    echo "<form method='POST' action='debug_fix_seller.php'>";
                    echo "<input type='hidden' name='user_id' value='" . $user['id'] . "'>";
                    echo "<button type='submit' class='btn'>Approve This Seller Now</button>";
                    echo "</form>";
                } elseif ($user['verification_status'] === 'rejected') {
                    echo "<p class='error'>❌ BLOCKED: Verification status is 'rejected'</p>";
                    echo "<p class='info'>This seller was rejected by admin.</p>";
                } elseif ($user['verification_status'] === 'approved') {
                    echo "<p class='success'>✅ PASSED: Verification status is 'approved'</p>";
                } else {
                    echo "<p class='error'>❌ UNKNOWN: Verification status is '" . $user['verification_status'] . "'</p>";
                }
            } else {
                echo "<h3>Step 3: Verification check...</h3>";
                echo "<p class='info'>ℹ️ Not a seller, no verification needed</p>";
            }
            
            // Final verdict
            echo "<hr>";
            echo "<h2>🎯 Final Verdict:</h2>";
            
            $canLogin = true;
            $reasons = [];
            
            if (!$password) {
                $canLogin = false;
                $reasons[] = "No password provided for testing";
            } elseif (!password_verify($password, $user['password_hash'])) {
                $canLogin = false;
                $reasons[] = "Password is incorrect";
            }
            
            if ($user['role'] === 'seller') {
                if (!isset($user['verification_status'])) {
                    $canLogin = false;
                    $reasons[] = "verification_status column missing";
                } elseif ($user['verification_status'] === 'pending') {
                    $canLogin = false;
                    $reasons[] = "Verification status is 'pending'";
                } elseif ($user['verification_status'] === 'rejected') {
                    $canLogin = false;
                    $reasons[] = "Verification status is 'rejected'";
                } elseif ($user['verification_status'] !== 'approved') {
                    $canLogin = false;
                    $reasons[] = "Verification status is '" . $user['verification_status'] . "' (must be 'approved')";
                }
            }
            
            if ($canLogin) {
                echo "<div style='background:#d4edda; padding:20px; border-radius:5px; border-left:5px solid #28a745;'>";
                echo "<h3 style='color:#155724; margin-top:0;'>✅ LOGIN SHOULD WORK!</h3>";
                echo "<p style='color:#155724;'>All checks passed. This user should be able to login successfully.</p>";
                echo "<p><a href='index.php' class='btn'>Try Logging In</a></p>";
                echo "</div>";
            } else {
                echo "<div style='background:#f8d7da; padding:20px; border-radius:5px; border-left:5px solid #dc3545;'>";
                echo "<h3 style='color:#721c24; margin-top:0;'>❌ LOGIN WILL FAIL</h3>";
                echo "<p style='color:#721c24;'><strong>Reasons:</strong></p>";
                echo "<ul style='color:#721c24;'>";
                foreach ($reasons as $reason) {
                    echo "<li>" . $reason . "</li>";
                }
                echo "</ul>";
                echo "</div>";
            }
        }
        
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Database Error: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
    echo "<p><a href='test_login.php' class='btn'>Test Another User</a> <a href='list_all_users.php' class='btn'>View All Users</a></p>";
    
} else {
    // Show form
    echo "<p>Enter credentials to test the login system and see detailed diagnostics.</p>";
    
    echo "<form method='POST'>";
    echo "<label>Email Address:</label>";
    echo "<input type='email' name='email' required placeholder='user@example.com'>";
    
    echo "<label>Password:</label>";
    echo "<input type='password' name='password' placeholder='Enter password (optional for basic check)'>";
    
    echo "<button type='submit' class='btn' style='width:100%; margin-top:20px;'>Test Login</button>";
    echo "</form>";
    
    echo "<hr>";
    echo "<p class='info'>💡 <strong>Tip:</strong> You can test without a password to just check if the user exists and their verification status.</p>";
    
    echo "<p><a href='list_all_users.php' class='btn'>View All Users</a> <a href='fix_all.php' class='btn'>System Diagnostics</a></p>";
}

echo "</div>";
?>
