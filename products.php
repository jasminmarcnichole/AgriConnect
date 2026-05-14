<?php
require 'auth.php';
requireLogin();
require 'config.php';

$stmt = $pdo->query('SELECT p.*, u.full_name seller_name, u.id seller_id FROM products p JOIN users u ON u.id = p.seller_id ORDER BY p.created_at DESC');
$products = $stmt->fetchAll();

$defaultImage = 'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=800&q=80';
$currentUserId = $_SESSION['user']['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Marketplace - Agri-Connect</title>
  <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/628/628283.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/premium-styles.css">
</head>
<body class="bg-light">
  <!-- Navigation -->
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
          <li class="nav-item"><a class="nav-link active" href="products.php"><i class="bi bi-shop me-1"></i>Marketplace</a></li>
          <li class="nav-item"><a class="nav-link" href="conversations.php"><i class="bi bi-chat-dots me-1"></i>Messages</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Header -->
  <div class="page-header">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8" data-aos="fade-right">
          <h1 class="display-4 fw-bold mb-3"><i class="bi bi-shop-window me-3"></i>Premium Product Marketplace</h1>
          <p class="lead mb-0">Discover farm-fresh quality from verified sellers nationwide • Every product, every farmer, verified for your peace of mind</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0" data-aos="fade-left">
          <div class="badge bg-success text-white px-4 py-3 fs-5">
            <i class="bi bi-check-circle-fill me-2"></i><?= count($products) ?> Products Available
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container my-5">
    <!-- Info Banner -->
    <div class="alert alert-success border-0 shadow-sm mb-5" role="alert" data-aos="fade-up">
      <div class="d-flex align-items-center">
        <i class="bi bi-info-circle-fill fs-3 me-3"></i>
        <div>
          <h5 class="alert-heading mb-1">Fresh from the Farm</h5>
          <p class="mb-0">Browse premium agricultural products handpicked by verified farmers • Quality guaranteed, satisfaction assured</p>
        </div>
      </div>
    </div>

    <!-- Products Grid -->
    <?php if (count($products) > 0): ?>
    <div class="row g-4">
      <?php foreach ($products as $index => $p): ?>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($index % 6) * 100 ?>">
        <div class="product-card">
          <div class="product-image" style="background-image: url('<?= $p['image'] ? 'uploads/products/' . htmlspecialchars($p['image']) : $defaultImage ?>');">
            <span class="product-badge pulse"><i class="bi bi-award-fill me-1"></i>Premium</span>
            <?php if ($p['stock'] <= 0): ?>
            <span class="product-badge" style="background: #dc3545; top: 50px;"><i class="bi bi-x-circle me-1"></i>Out of Stock</span>
            <?php elseif ($p['stock'] < 10): ?>
            <span class="product-badge" style="background: #ffc107; color: #000; top: 50px;"><i class="bi bi-exclamation-triangle me-1"></i>Low Stock</span>
            <?php endif; ?>
          </div>
          <div class="p-4">
            <div class="d-flex align-items-center mb-3">
              <div class="flex-shrink-0">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                  <i class="bi bi-person-fill"></i>
                </div>
              </div>
              <div class="ms-3">
                <h6 class="mb-0 fw-bold text-success"><?= htmlspecialchars($p['seller_name']) ?></h6>
                <small class="text-muted"><i class="bi bi-patch-check-fill text-success me-1"></i>Verified Seller</small>
              </div>
            </div>
            <h4 class="fw-bold mb-3"><?= htmlspecialchars($p['title']) ?></h4>
            <p class="text-muted mb-3"><?= htmlspecialchars(substr($p['description'], 0, 100)) ?><?= strlen($p['description']) > 100 ? '...' : '' ?></p>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h3 class="text-success fw-bold mb-0">₱<?= number_format((float)$p['price'], 2) ?></h3>
                <small class="text-muted">per unit</small>
              </div>
              <div class="text-end">
                <span class="badge bg-<?= $p['stock'] > 0 ? 'success' : 'danger' ?>-subtle text-<?= $p['stock'] > 0 ? 'success' : 'danger' ?> mb-1">
                  <i class="bi bi-box-seam me-1"></i><?= $p['stock'] ?> in stock
                </span>
              </div>
            </div>
            <a href="product.php?id=<?= (int)$p['id'] ?>" class="btn btn-success w-100 fw-bold">
              <i class="bi bi-eye me-2"></i>View Full Details
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state" data-aos="zoom-in">
      <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
      <h3 class="fw-bold text-muted mb-3">No Products Available</h3>
      <p class="text-muted">Check back soon for fresh agricultural products from our verified sellers!</p>
    </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>AOS.init({duration: 800, once: true});</script>
</body>
</html>
