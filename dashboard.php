<?php
require 'auth.php';
requireLogin();
require 'config.php';
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html><head><title>Dashboard</title><link rel="stylesheet" href="assets/styles.css"></head><body>
<h2>Welcome, <?= htmlspecialchars($user['full_name']) ?> (<?= htmlspecialchars($user['role']) ?>)</h2>
<nav>
  <a href="products.php">Product Offer</a>
  <a href="profile.php">Profile</a>
  <?php if ($user['role'] === 'seller'): ?><a href="seller_products.php">My Products</a><?php endif; ?>
  <a href="conversations.php">Conversations</a>
  <a href="logout.php">Logout</a>
</nav>
</body></html>
