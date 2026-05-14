<?php
echo "<style>
body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
.container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
h1 { color: #2d5016; }
.success { color: #28a745; font-weight: bold; }
.error { color: #dc3545; font-weight: bold; }
.info { color: #17a2b8; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
th { background: #6b8e23; color: white; }
pre { background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 5px; overflow-x: auto; }
.box { background: #f8f9fa; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #6b8e23; }
</style>";

echo "<div class='container'>";
echo "<h1>🔍 Database Connection Diagnostic</h1>";

// Test email
$testEmail = 'cassandralouisejasmin@gmail.com';

echo "<p>Testing with email: <strong>" . htmlspecialchars($testEmail) . "</strong></p>";

// Load config
require 'config.php';

echo "<h2>1. Config.php Settings</h2>";
echo "<div class='box'>";
echo "<table>";
echo "<tr><th>Setting</th><th>Value</th></tr>";

// Get connection info from PDO
$host = $pdo->query("SELECT @@hostname")->fetchColumn();
$database = $pdo->query("SELECT DATABASE()")->fetchColumn();
$user = $pdo->query("SELECT USER()")->fetchColumn();

echo "<tr><td>Host</td><td>" . htmlspecialchars($host) . "</td></tr>";
echo "<tr><td>Database</td><td><strong>" . htmlspecialchars($database) . "</strong></td></tr>";
echo "<tr><td>User</td><td>" . htmlspecialchars($user) . "</td></tr>";
echo "</table>";
echo "</div>";

echo "<h2>2. Direct Query Test</h2>";
echo "<div class='box'>";

try {
    // Test 1: Count all users
    $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    echo "<p class='success'>✅ Total users in database: <strong>$count</strong></p>";
    
    // Test 2: List all emails
    echo "<p><strong>All emails in database:</strong></p>";
    $stmt = $pdo->query("SELECT id, email FROM users ORDER BY id");
    $allUsers = $stmt->fetchAll();
    
    echo "<table>";
    echo "<tr><th>ID</th><th>Email</th><th>Length</th><th>Hex</th></tr>";
    foreach ($allUsers as $user) {
        $emailHex = bin2hex($user['email']);
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td><code>" . htmlspecialchars($user['email']) . "</code></td>";
        echo "<td>" . strlen($user['email']) . "</td>";
        echo "<td><small>" . $emailHex . "</small></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test 3: Search for specific email
    echo "<h3>3. Searching for: " . htmlspecialchars($testEmail) . "</h3>";
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$testEmail]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p class='success'>✅ FOUND with prepared statement!</p>";
        echo "<table>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        foreach ($user as $key => $value) {
            if (!is_numeric($key)) {
                echo "<tr><td>$key</td><td>" . htmlspecialchars($value) . "</td></tr>";
            }
        }
        echo "</table>";
    } else {
        echo "<p class='error'>❌ NOT FOUND with prepared statement</p>";
    }
    
    // Test 4: Try with LIKE
    echo "<h3>4. Trying with LIKE search...</h3>";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email LIKE ?");
    $stmt->execute(['%' . $testEmail . '%']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p class='success'>✅ FOUND with LIKE search!</p>";
    } else {
        echo "<p class='error'>❌ NOT FOUND with LIKE search</p>";
    }
    
    // Test 5: Try case-insensitive
    echo "<h3>5. Trying case-insensitive search...</h3>";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(?)");
    $stmt->execute([$testEmail]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p class='success'>✅ FOUND with case-insensitive search!</p>";
    } else {
        echo "<p class='error'>❌ NOT FOUND with case-insensitive search</p>";
    }
    
    // Test 6: Check for hidden characters
    echo "<h3>6. Checking for hidden characters...</h3>";
    $stmt = $pdo->query("SELECT id, email, LENGTH(email) as len, HEX(email) as hex FROM users");
    $users = $stmt->fetchAll();
    
    echo "<p>Looking for emails that might match...</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Email</th><th>Length</th><th>Match?</th></tr>";
    
    foreach ($users as $u) {
        $matches = (trim($u['email']) === $testEmail);
        $class = $matches ? 'success' : '';
        echo "<tr class='$class'>";
        echo "<td>" . $u['id'] . "</td>";
        echo "<td><code>" . htmlspecialchars($u['email']) . "</code></td>";
        echo "<td>" . $u['len'] . " chars</td>";
        echo "<td>" . ($matches ? "✅ YES!" : "No") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test 7: Raw SQL
    echo "<h3>7. Raw SQL Test</h3>";
    echo "<p>Run this in phpMyAdmin:</p>";
    echo "<pre>SELECT * FROM users WHERE email = '" . $testEmail . "';</pre>";
    
    // Test 8: Check table name
    echo "<h3>8. Verify Table Structure</h3>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Tables in database: " . implode(', ', $tables) . "</p>";
    
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    echo "<p>Columns in users table:</p>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Test with different config
echo "<h2>9. Testing Different Connection</h2>";
echo "<div class='box'>";
echo "<p>Let's try connecting directly without config.php:</p>";

try {
    $testPdo = new PDO("mysql:host=127.0.0.1;dbname=agi_connect;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    $stmt = $testPdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$testEmail]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p class='success'>✅ FOUND with direct connection!</p>";
        echo "<p>This means config.php might have an issue.</p>";
    } else {
        echo "<p class='error'>❌ NOT FOUND with direct connection either</p>";
        echo "<p>This confirms the email truly doesn't exist in the database.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p class='error'>Connection failed: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Solution
echo "<h2>🎯 Diagnosis Result</h2>";
echo "<div class='box'>";
echo "<p><strong>Based on the tests above:</strong></p>";
echo "<ul>";
echo "<li>If user is found in test 3-5: The email exists and should work</li>";
echo "<li>If user is NOT found in any test: The email in the table is different (check test 6 for exact match)</li>";
echo "<li>If found in test 9 but not test 3: config.php is connecting to wrong database</li>";
echo "</ul>";

echo "<h3>Quick Fix Options:</h3>";
echo "<ol>";
echo "<li><strong>Copy exact email from test 2 table above</strong> - Use that exact string</li>";
echo "<li><strong>Update email in database:</strong></li>";
echo "</ol>";

echo "<pre>UPDATE users SET email = '" . $testEmail . "' WHERE id = [USER_ID];</pre>";

echo "<p><strong>Or create a new test user:</strong></p>";
echo "<pre>INSERT INTO users (full_name, email, password_hash, role, verification_status) 
VALUES ('Test User', '" . $testEmail . "', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'seller', 'approved');</pre>";
echo "<p class='info'>Password will be: test123</p>";

echo "</div>";

echo "</div>";
?>
