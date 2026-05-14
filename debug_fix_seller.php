<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? 0;
    
    if ($userId) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET verification_status = 'approved' WHERE id = ?");
            $stmt->execute([$userId]);
            
            if ($stmt->rowCount() > 0) {
                echo "<style>body{font-family:Arial;padding:20px;text-align:center;}</style>";
                echo "<h2 style='color:green;'>✅ Seller Approved Successfully!</h2>";
                echo "<p>The seller can now login.</p>";
                echo "<p><a href='debug_seller_login.php'>Check Another Seller</a> | <a href='admin_dashboard.php'>Admin Dashboard</a> | <a href='index.php'>Home</a></p>";
            } else {
                echo "<style>body{font-family:Arial;padding:20px;text-align:center;}</style>";
                echo "<h2 style='color:orange;'>⚠️ No Changes Made</h2>";
                echo "<p>The seller might already be approved.</p>";
                echo "<p><a href='debug_seller_login.php'>Go Back</a></p>";
            }
        } catch (PDOException $e) {
            echo "<style>body{font-family:Arial;padding:20px;text-align:center;}</style>";
            echo "<h2 style='color:red;'>❌ Error</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "<p><a href='debug_seller_login.php'>Go Back</a></p>";
        }
    }
} else {
    header('Location: debug_seller_login.php');
}
