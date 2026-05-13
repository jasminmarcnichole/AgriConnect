<?php
require 'auth.php';
requireLogin();
require 'config.php';

$stmt = $pdo->query('SELECT p.*, u.full_name seller_name FROM products p JOIN users u ON u.id = p.seller_id ORDER BY p.created_at DESC');
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html><head><title>Product Offer</title><link rel="stylesheet" href="assets/styles.css"></head><body>
<h2>Product Offer</h2>
<a href="dashboard.php">Back to Dashboard</a>
<div class="cards">
<?php foreach ($products as $p): ?>
  <div class="card">
    <h3><?= htmlspecialchars($p['title']) ?></h3>
    <p>Seller: <?= htmlspecialchars($p['seller_name']) ?></p>
    <p>Price: $<?= number_format((float)$p['price'], 2) ?></p>
    <a href="product.php?id=<?= (int)$p['id'] ?>">View info</a>
  </div>
<?php endforeach; ?>
</div>
</body></html>
