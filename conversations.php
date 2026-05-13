<?php
require 'auth.php';
requireLogin();
require 'config.php';

$user = $_SESSION['user'];
if ($user['role'] === 'buyer') {
    $stmt = $pdo->prepare('SELECT c.*, u.full_name seller_name, p.title product_title FROM conversations c JOIN users u ON u.id=c.seller_id LEFT JOIN products p ON p.id=c.product_id WHERE c.buyer_id=? ORDER BY c.created_at DESC');
    $stmt->execute([$user['id']]);
} else {
    $stmt = $pdo->prepare('SELECT c.*, u.full_name buyer_name, p.title product_title FROM conversations c JOIN users u ON u.id=c.buyer_id LEFT JOIN products p ON p.id=c.product_id WHERE c.seller_id=? ORDER BY c.created_at DESC');
    $stmt->execute([$user['id']]);
}
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html><head><title>Conversations</title></head><body>
<h2>Conversations</h2>
<ul>
<?php foreach ($rows as $r): ?>
<li>
  <?= htmlspecialchars($user['role']==='buyer' ? $r['seller_name'] : $r['buyer_name']) ?>
  <?php if (!empty($r['product_title'])): ?> (<?= htmlspecialchars($r['product_title']) ?>)<?php endif; ?>
  <a href="chat.php?seller_id=<?= (int)$r['seller_id'] ?>&buyer_id=<?= (int)$r['buyer_id'] ?>&product_id=<?= (int)$r['product_id'] ?>">Open chat profile</a>
</li>
<?php endforeach; ?>
</ul>
<a href="dashboard.php">Back</a>
</body></html>
