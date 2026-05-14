<?php
/**
 * Database Migration Script
 * Run this file once to add stock and image columns to products table
 * Access via: http://localhost/AgriConnect/migrate.php
 */

require_once 'config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Migration - AgriConnect</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { color: red; background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { color: blue; background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 10px 0; }
        h1 { color: #2d5016; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🌾 AgriConnect Database Migration</h1>
    <p>This script will add <code>stock</code> and <code>image</code> columns to the products table.</p>
";

try {
    // Check if columns already exist
    $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'stock'");
    $stockExists = $stmt->fetch();
    
    $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'image'");
    $imageExists = $stmt->fetch();
    
    if ($stockExists && $imageExists) {
        echo "<div class='info'>✓ Migration already completed! Both columns exist.</div>";
        echo "<p>Your database is up to date. You can safely delete this file.</p>";
    } else {
        // Run migration
        $migrations = [];
        
        if (!$stockExists) {
            $pdo->exec("ALTER TABLE products ADD COLUMN stock INT DEFAULT 0 AFTER price");
            $migrations[] = "Added 'stock' column";
        }
        
        if (!$imageExists) {
            $pdo->exec("ALTER TABLE products ADD COLUMN image VARCHAR(255) NULL AFTER " . ($stockExists ? "stock" : "price"));
            $migrations[] = "Added 'image' column";
        }
        
        echo "<div class='success'>";
        echo "<h3>✓ Migration Successful!</h3>";
        echo "<ul>";
        foreach ($migrations as $migration) {
            echo "<li>$migration</li>";
        }
        echo "</ul>";
        echo "</div>";
        
        echo "<div class='info'>";
        echo "<h3>Next Steps:</h3>";
        echo "<ol>";
        echo "<li>Verify the migration by checking your products table</li>";
        echo "<li>Ensure the <code>uploads/products/</code> directory exists and is writable</li>";
        echo "<li>Delete this migration file (<code>migrate.php</code>) for security</li>";
        echo "<li>Start uploading product images!</li>";
        echo "</ol>";
        echo "</div>";
    }
    
    // Show current table structure
    echo "<h3>Current Products Table Structure:</h3>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #2d5016; color: white;'><th>Column</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    
    $stmt = $pdo->query("DESCRIBE products");
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($row['Field']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<h3>✗ Migration Failed</h3>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your database connection and try again.</p>";
    echo "</div>";
}

echo "
    <hr>
    <p style='text-align: center; color: #666;'>
        <small>AgriConnect Database Migration Tool | 
        <a href='index.php'>Return to Home</a> | 
        <a href='dashboard.php'>Go to Dashboard</a>
        </small>
    </p>
</body>
</html>";
?>
