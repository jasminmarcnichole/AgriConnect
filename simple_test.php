<?php
// Super simple test - no fancy stuff
$host = '127.0.0.1';
$db   = 'agi_connect';
$user = 'root';
$pass = '';

echo "<h1>Simple Direct Test</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} pre{background:#f4f4f4;padding:10px;}</style>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p class='success'>✅ Connected to database: <strong>$db</strong></p>";
    
    // Get all users
    echo "<h2>All Users in Database:</h2>";
    $stmt = $pdo->query("SELECT id, full_name, email, role, verification_status FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Total users: <strong>" . count($users) . "</strong></p>";
    
    if (count($users) > 0) {
        echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
        echo "<tr style='background:#6b8e23;color:white;'><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th></tr>";
        
        foreach ($users as $u) {
            echo "<tr>";
            echo "<td>" . $u['id'] . "</td>";
            echo "<td>" . htmlspecialchars($u['full_name']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($u['email']) . "</strong></td>";
            echo "<td>" . $u['role'] . "</td>";
            echo "<td>" . ($u['verification_status'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Now test the specific email
        echo "<hr>";
        echo "<h2>Testing Specific Email:</h2>";
        
        $testEmail = 'cassandralouisejasmin@gmail.com';
        echo "<p>Looking for: <code>$testEmail</code></p>";
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$testEmail]);
        $found = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($found) {
            echo "<p class='success'>✅ <strong>FOUND!</strong></p>";
            echo "<pre>" . print_r($found, true) . "</pre>";
        } else {
            echo "<p class='error'>❌ <strong>NOT FOUND</strong></p>";
            echo "<p>The email '<code>$testEmail</code>' does not exist in the database.</p>";
            
            // Check for similar emails
            echo "<h3>Checking for similar emails...</h3>";
            $stmt = $pdo->query("SELECT email FROM users WHERE email LIKE '%cassandra%' OR email LIKE '%jasmin%'");
            $similar = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (count($similar) > 0) {
                echo "<p>Found similar emails:</p>";
                echo "<ul>";
                foreach ($similar as $email) {
                    echo "<li><code>" . htmlspecialchars($email) . "</code></li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No similar emails found.</p>";
            }
            
            // Show exact bytes
            echo "<h3>Exact Email Comparison:</h3>";
            echo "<p>Looking for email with these exact bytes:</p>";
            echo "<pre>String: $testEmail\nLength: " . strlen($testEmail) . "\nHex: " . bin2hex($testEmail) . "</pre>";
            
            echo "<p>Emails in database:</p>";
            foreach ($users as $u) {
                $dbEmail = $u['email'];
                echo "<pre>String: $dbEmail\nLength: " . strlen($dbEmail) . "\nHex: " . bin2hex($dbEmail) . "\nMatch: " . ($dbEmail === $testEmail ? 'YES' : 'NO') . "</pre>";
                echo "<hr style='margin:5px 0;'>";
            }
        }
        
    } else {
        echo "<p class='error'>No users found in database!</p>";
    }
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='list_all_users.php'>View All Users</a> | <a href='fix_all.php'>System Diagnostics</a></p>";
?>
