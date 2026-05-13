<?php
require 'auth.php';
requireLogin();
require 'config.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT p.*, u.full_name seller_name, u.id seller_id FROM products p JOIN users u ON p.seller_id=u.id WHERE p.id=?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) exit('Product not found');
?>
<!DOCTYPE html>
<html><head><title>Product</title><link rel="stylesheet" href="assets/styles.css"></head><body>
<h2><?= htmlspecialchars($product['title']) ?></h2>
<p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
<p>Seller: <?= htmlspecialchars($product['seller_name']) ?></p>
<p>Price: $<?= number_format((float)$product['price'], 2) ?></p>
<a href="chat.php?seller_id=<?= (int)$product['seller_id'] ?>&product_id=<?= (int)$product['id'] ?>">Contact seller / Chat</a>
</body></html>
