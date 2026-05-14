<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'config.php';

try {
    $stmt = $pdo->query('SELECT p.*, u.full_name as seller_name, u.email as seller_email, u.phone as seller_phone, u.location as seller_location, u.profile_picture as seller_profile, u.created_at as seller_since, u.verification_status FROM products p JOIN users u ON p.seller_id = u.id WHERE p.stock > 0 ORDER BY p.created_at DESC');
    $products = $stmt->fetchAll();
    
    echo "Query successful! Found " . count($products) . " products.<br><br>";
    
    if (count($products) > 0) {
        echo "<pre>";
        print_r($products[0]);
        echo "</pre>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
