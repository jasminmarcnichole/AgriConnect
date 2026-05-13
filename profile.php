<?php
require 'auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html><head><title>Profile</title></head><body>
<h2>Profile</h2>
<p>Name: <?= htmlspecialchars($_SESSION['user']['full_name']) ?></p>
<p>Email: <?= htmlspecialchars($_SESSION['user']['email']) ?></p>
<p>Role: <?= htmlspecialchars($_SESSION['user']['role']) ?></p>
<a href="dashboard.php">Back</a>
</body></html>
