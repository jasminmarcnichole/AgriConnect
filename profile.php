<?php
require 'auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html><head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - Agri-Connect</title>
  <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
  <link rel="stylesheet" href="assets/styles.css">
</head><body>
<header>
  <h1>🌾 Agri-Connect</h1>
  <nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="products.php">Products</a>
    <a href="conversations.php">Messages</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>
<div class="page-header">
  <h2>👤 My Profile</h2>
</div>
<div class="container">
  <div style="background: white; padding: 3rem; border-radius: 20px; box-shadow: 0 10px 40px var(--shadow); max-width: 700px; margin: 0 auto;">
    <div style="display: grid; gap: 1.5rem;">
      <div style="background: var(--light); padding: 1.5rem; border-radius: 10px; border-left: 4px solid var(--accent);">
        <p style="color: #888; font-size: 0.9rem; margin-bottom: 0.3rem;">Full Name</p>
        <p style="font-size: 1.3rem; font-weight: 600; color: var(--primary);"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></p>
      </div>
      <div style="background: var(--light); padding: 1.5rem; border-radius: 10px; border-left: 4px solid var(--secondary);">
        <p style="color: #888; font-size: 0.9rem; margin-bottom: 0.3rem;">Email Address</p>
        <p style="font-size: 1.3rem; font-weight: 600; color: var(--primary);"><?= htmlspecialchars($_SESSION['user']['email']) ?></p>
      </div>
      <div style="background: var(--light); padding: 1.5rem; border-radius: 10px; border-left: 4px solid var(--gold);">
        <p style="color: #888; font-size: 0.9rem; margin-bottom: 0.3rem;">Account Type</p>
        <p style="font-size: 1.3rem; font-weight: 600; color: var(--primary); text-transform: capitalize;"><?= htmlspecialchars($_SESSION['user']['role']) ?></p>
      </div>
    </div>
  </div>
</div>
</body></html>
