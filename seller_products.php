<?php
require 'auth.php';
requireRole('seller');
require 'config.php';

$userId = $_SESSION['user']['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO products (seller_id, title, description, price) VALUES (?, ?, ?, ?)');
    $stmt->execute([$userId, $_POST['title'], $_POST['description'], $_POST['price']]);
}
$stmt = $pdo->prepare('SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC');
$stmt->execute([$userId]);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html><head><title>My Products</title></head><body>
<h2>My Products</h2>
<form method="POST">
  <input name="title" placeholder="Product title" required>
  <textarea name="description" placeholder="Description" required></textarea>
  <input type="number" step="0.01" name="price" placeholder="Price" required>
  <button>Add Product</button>
</form>
<ul>
<?php foreach ($products as $p): ?>
<li><?= htmlspecialchars($p['title']) ?> - $<?= number_format((float)$p['price'],2) ?></li>
<?php endforeach; ?>
</ul>
<a href="dashboard.php">Back</a>
</body></html>
