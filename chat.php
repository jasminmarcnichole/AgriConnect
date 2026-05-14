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
    header('Location: chat.php?seller_id='.$sellerId.'&buyer_id='.$buyerId.'&product_id='.$productId);
    exit;
}

$stmt = $pdo->prepare('SELECT m.*, u.full_name FROM messages m JOIN users u ON u.id=m.sender_id WHERE conversation_id=? ORDER BY m.created_at ASC');
$stmt->execute([$convoId]);
$msgs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat - Agri-Connect</title>
  <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/628/628283.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/premium-styles.css">
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark premium-nav">
    <div class="container-fluid px-4">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="https://cdn-icons-png.flaticon.com/512/628/628283.png" alt="Logo" width="40" height="40" class="me-2">
        <span class="fw-bold fs-4">Agri<span class="text-warning">Connect</span></span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="conversations.php"><i class="bi bi-chat-dots me-1"></i>Messages</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($user['full_name']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="bi bi-person me-2"></i>Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="page-header">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <h1 class="display-4 fw-bold mb-3"><i class="bi bi-chat-square-text-fill me-3"></i>Live Negotiation Room</h1>
          <p class="lead mb-0">Discuss prices, quantities, delivery terms • Build trust through direct communication</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
          <div class="badge bg-success text-white px-4 py-3 fs-6">
            <i class="bi bi-shield-check me-2"></i>Secure & Private
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="chat-container">
          <div class="chat-header">
            <div class="d-flex align-items-center">
              <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                <i class="bi bi-lock-fill fs-4"></i>
              </div>
              <div>
                <h5 class="mb-0 fw-bold">Secure Private Chat</h5>
                <small class="opacity-75">Your conversation is end-to-end protected</small>
              </div>
            </div>
          </div>

          <div class="chat-messages" id="chatMessages">
            <?php if (count($msgs) === 0): ?>
            <div class="text-center py-5">
              <i class="bi bi-chat-heart display-1 text-success opacity-25 mb-3"></i>
              <h5 class="text-muted">Start the conversation!</h5>
              <p class="text-muted">Introduce yourself and discuss the product details.</p>
            </div>
            <?php endif; ?>

            <?php foreach ($msgs as $m): ?>
            <div class="d-flex mb-3 <?= $m['sender_id'] === $user['id'] ? 'justify-content-end' : 'justify-content-start' ?>">
              <div class="message-bubble <?= $m['sender_id'] === $user['id'] ? 'message-sent' : 'message-received' ?>" style="max-width: 70%;">
                <div class="d-flex align-items-center mb-2">
                  <div class="<?= $m['sender_id'] === $user['id'] ? 'bg-white bg-opacity-25' : 'bg-success' ?> text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                    <i class="bi bi-person-fill"></i>
                  </div>
                  <strong class="<?= $m['sender_id'] === $user['id'] ? 'text-white' : 'text-success' ?>"><?= htmlspecialchars($m['full_name']) ?></strong>
                </div>
                <p class="mb-2" style="line-height: 1.6;"><?= nl2br(htmlspecialchars($m['message'])) ?></p>
                <small class="<?= $m['sender_id'] === $user['id'] ? 'text-white-50' : 'text-muted' ?>">
                  <i class="bi bi-clock me-1"></i><?= date('M d, g:i A', strtotime($m['created_at'])) ?>
                </small>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="bg-white p-4 border-top">
            <form method="POST" class="d-flex gap-3">
              <input type="text" name="message" class="form-control form-control-lg" placeholder="Type your message... Ask about quantity, delivery, pricing, or quality" required autofocus>
              <button type="submit" class="btn btn-success btn-lg px-4 fw-bold">
                <i class="bi bi-send-fill me-2"></i>Send
              </button>
            </form>
            <div class="mt-3 text-center">
              <small class="text-muted">
                <i class="bi bi-shield-check text-success me-1"></i>
                Messages are secure and only visible to you and the other party
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Auto-scroll to bottom of chat
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Auto-refresh messages every 5 seconds using AJAX
    let lastMessageCount = <?= count($msgs) ?>;
    setInterval(() => {
      fetch(window.location.href)
        .then(response => response.text())
        .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const newMessages = doc.getElementById('chatMessages');
          if (newMessages) {
            const currentMessages = document.getElementById('chatMessages');
            const newCount = newMessages.children.length;
            if (newCount > lastMessageCount) {
              currentMessages.innerHTML = newMessages.innerHTML;
              currentMessages.scrollTop = currentMessages.scrollHeight;
              lastMessageCount = newCount;
            }
          }
        })
        .catch(err => console.error('Failed to refresh messages:', err));
    }, 5000);
  </script>
</body>
</html>
