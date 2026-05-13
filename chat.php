<?php
require 'auth.php';
requireLogin();
require 'config.php';

$user = $_SESSION['user'];
$sellerId = (int)($_GET['seller_id'] ?? 0);
$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : null;

if ($user['role'] === 'seller') {
    $sellerId = $user['id'];
    $buyerId = (int)($_GET['buyer_id'] ?? 0);
} else {
    $buyerId = $user['id'];
}

$stmt = $pdo->prepare('SELECT id FROM conversations WHERE buyer_id=? AND seller_id=? AND (product_id <=> ?)');
$stmt->execute([$buyerId, $sellerId, $productId]);
$convo = $stmt->fetch();
if (!$convo) {
    $stmt = $pdo->prepare('INSERT INTO conversations (buyer_id, seller_id, product_id) VALUES (?, ?, ?)');
    $stmt->execute([$buyerId, $sellerId, $productId]);
    $convoId = (int)$pdo->lastInsertId();
} else {
    $convoId = (int)$convo['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $stmt = $pdo->prepare('INSERT INTO messages (conversation_id, sender_id, message) VALUES (?, ?, ?)');
    $stmt->execute([$convoId, $user['id'], trim($_POST['message'])]);
}

$stmt = $pdo->prepare('SELECT m.*, u.full_name FROM messages m JOIN users u ON u.id=m.sender_id WHERE conversation_id=? ORDER BY m.created_at ASC');
$stmt->execute([$convoId]);
$msgs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html><head><title>Chat</title></head><body>
<h2>Conversation</h2>
<div>
<?php foreach ($msgs as $m): ?>
<p><strong><?= htmlspecialchars($m['full_name']) ?>:</strong> <?= htmlspecialchars($m['message']) ?></p>
<?php endforeach; ?>
</div>
<form method="POST">
  <input name="message" placeholder="Type message" required>
  <button>Send</button>
</form>
<a href="conversations.php">Back to conversations</a>
</body></html>
