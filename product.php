<?php
require 'auth.php';
requireLogin();
require 'config.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT p.*, u.full_name seller_name, u.id seller_id FROM products p JOIN users u ON p.seller_id=u.id WHERE p.id=?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
  header('Location: products.php');
  exit;
}

$isOwnProduct = isset($_SESSION['user']) && $_SESSION['user']['id'] == $product['seller_id'];

$productImages = [
  'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=1200&q=80',
  'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=1200&q=80',
  'https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=1200&q=80',
  'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&q=80',
  'https://images.unsplash.com/photo-1592982537447-7d1485c86a22?w=1200&q=80',
  'https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=1200&q=80',
  'https://images.unsplash.com/photo-1595855759920-86582396756a?w=1200&q=80',
  'https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?w=1200&q=80'
];
$productImage = $productImages[$id % count($productImages)];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($product['title']) ?> - Agri-Connect</title>
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
          <li class="nav-item"><a class="nav-link" href="conversations.php"><i class="bi bi-chat-dots me-1"></i>Messages</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container my-5" style="margin-top: 100px !important;">
    <nav aria-label="breadcrumb" data-aos="fade-right">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="products.php" class="text-decoration-none">Marketplace</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($product['title']) ?></li>
      </ol>
    </nav>

    <div class="row g-5">
      <!-- Product Image -->
      <div class="col-lg-6" data-aos="fade-right">
        <div class="card border-0 shadow-lg overflow-hidden">
          <div style="height: 500px; background: url('<?= $product['image'] ? 'uploads/products/' . htmlspecialchars($product['image']) : $productImage ?>') center/cover; position: relative;">
            <div class="position-absolute top-0 start-0 m-3">
              <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-patch-check-fill me-2"></i>Verified Product</span>
            </div>
            <div class="position-absolute top-0 end-0 m-3">
              <?php if ($product['stock'] > 0): ?>
              <span class="badge bg-success text-white px-3 py-2 fs-6 me-2"><i class="bi bi-box-seam me-2"></i><?= $product['stock'] ?> in stock</span>
              <?php else: ?>
              <span class="badge bg-danger text-white px-3 py-2 fs-6 me-2"><i class="bi bi-x-circle me-2"></i>Out of Stock</span>
              <?php endif; ?>
              <span class="badge bg-warning text-dark px-3 py-2 fs-6"><i class="bi bi-star-fill me-2"></i>Premium Quality</span>
            </div>
          </div>
        </div>
        
        <!-- Trust Badges -->
        <div class="row g-3 mt-3">
          <div class="col-4">
            <div class="card border-0 shadow-sm text-center p-3">
              <i class="bi bi-shield-check text-success fs-2 mb-2"></i>
              <small class="fw-semibold">Quality Assured</small>
            </div>
          </div>
          <div class="col-4">
            <div class="card border-0 shadow-sm text-center p-3">
              <i class="bi bi-truck text-primary fs-2 mb-2"></i>
              <small class="fw-semibold">Fast Delivery</small>
            </div>
          </div>
          <div class="col-4">
            <div class="card border-0 shadow-sm text-center p-3">
              <i class="bi bi-lock-fill text-warning fs-2 mb-2"></i>
              <small class="fw-semibold">Secure Payment</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Product Details -->
      <div class="col-lg-6" data-aos="fade-left">
        <div class="card border-0 shadow-sm p-4 mb-4">
          <h1 class="display-5 fw-bold text-success mb-3"><?= htmlspecialchars($product['title']) ?></h1>
          <p class="lead text-muted mb-4">Farm-fresh quality delivered directly from verified agricultural suppliers</p>
          
          <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            <span class="trust-badge"><i class="bi bi-check-circle-fill me-2"></i>Quality Assured</span>
            <span class="trust-badge"><i class="bi bi-truck me-2"></i>Fast Delivery</span>
            <span class="trust-badge"><i class="bi bi-shield-check me-2"></i>Secure Payment</span>
          </div>

          <div class="card bg-success-subtle border-success p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <small class="text-muted text-uppercase fw-semibold d-block mb-2">Best Price</small>
                <h2 class="display-4 fw-bold text-success mb-0">₱<?= number_format((float)$product['price'], 2) ?></h2>
                <small class="text-muted">Competitive market rate</small>
              </div>
              <div class="text-end">
                <?php if ($product['stock'] > 0): ?>
                <div class="badge bg-success px-3 py-2 fs-6 mb-2">
                  <i class="bi bi-check-circle-fill me-2"></i>In Stock
                </div>
                <div class="text-muted small"><i class="bi bi-box-seam me-1"></i><?= $product['stock'] ?> units available</div>
                <?php else: ?>
                <div class="badge bg-danger px-3 py-2 fs-6 mb-2">
                  <i class="bi bi-x-circle-fill me-2"></i>Out of Stock
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <?php if ($isOwnProduct): ?>
          <div class="alert alert-info mb-3">
            <i class="bi bi-info-circle me-2"></i>This is your product listing
          </div>
          <a href="edit_product.php?id=<?= (int)$product['id'] ?>" class="btn btn-primary btn-lg w-100 fw-bold mb-3">
            <i class="bi bi-pencil-square me-2"></i>Edit Product Information
          </a>
          <?php else: ?>
          <?php if ($product['stock'] > 0): ?>
          <a href="chat.php?seller_id=<?= (int)$product['seller_id'] ?>&product_id=<?= (int)$product['id'] ?>" class="btn btn-warning btn-lg w-100 fw-bold mb-3">
            <i class="bi bi-chat-dots-fill me-2"></i>Contact Seller Now - Get Your Quote!
          </a>
          <?php else: ?>
          <button class="btn btn-secondary btn-lg w-100 fw-bold mb-3" disabled>
            <i class="bi bi-x-circle me-2"></i>Currently Out of Stock
          </button>
          <?php endif; ?>
          <?php endif; ?>
          
          <div class="text-center text-muted">
            <small><i class="bi bi-shield-check me-1"></i>100% Secure Transaction • Your information is protected</small>
          </div>
        </div>

        <!-- Seller Info -->
        <div class="card border-0 shadow-sm overflow-hidden">
          <div class="card-header" style="background: linear-gradient(135deg, #2d5016, #6b8e23); color: white; padding: 1.5rem;">
            <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2"></i>Trusted Seller Information</h5>
          </div>
          <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
              <div class="flex-shrink-0">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; font-size: 2rem;">
                  <i class="bi bi-person-fill"></i>
                </div>
              </div>
              <div class="ms-3">
                <h4 class="fw-bold mb-1"><?= htmlspecialchars($product['seller_name']) ?></h4>
                <p class="text-muted mb-0"><i class="bi bi-patch-check-fill text-success me-1"></i>Verified Agricultural Supplier</p>
              </div>
            </div>
            <div class="row g-3">
              <div class="col-4 text-center">
                <div class="card bg-light border-0 p-3">
                  <h4 class="text-warning fw-bold mb-0">4.9<i class="bi bi-star-fill ms-1"></i></h4>
                  <small class="text-muted">Rating</small>
                </div>
              </div>
              <div class="col-4 text-center">
                <div class="card bg-light border-0 p-3">
                  <h4 class="text-success fw-bold mb-0">500+</h4>
                  <small class="text-muted">Products Sold</small>
                </div>
              </div>
              <div class="col-4 text-center">
                <div class="card bg-light border-0 p-3">
                  <h4 class="text-primary fw-bold mb-0">99%</h4>
                  <small class="text-muted">Positive</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Description -->
    <div class="row mt-5">
      <div class="col-12" data-aos="fade-up">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-5">
            <h3 class="fw-bold mb-4"><i class="bi bi-file-text text-success me-2"></i>Product Description</h3>
            <p class="lead text-muted" style="line-height: 2;"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Why Buy Section -->
    <div class="row mt-5">
      <div class="col-12" data-aos="fade-up">
        <div class="card border-0 shadow-sm bg-success-subtle">
          <div class="card-body p-5">
            <h3 class="fw-bold text-center mb-5"><i class="bi bi-star-fill text-warning me-2"></i>Why Buy From Agri-Connect?</h3>
            <div class="row g-4">
              <div class="col-md-3 col-sm-6">
                <div class="text-center">
                  <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                    <i class="bi bi-check-circle-fill"></i>
                  </div>
                  <h5 class="fw-bold">Quality Guaranteed</h5>
                  <p class="text-muted mb-0">100% authentic products</p>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="text-center">
                  <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                    <i class="bi bi-shield-check"></i>
                  </div>
                  <h5 class="fw-bold">Secure Payment</h5>
                  <p class="text-muted mb-0">Protected transactions</p>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="text-center">
                  <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                    <i class="bi bi-truck"></i>
                  </div>
                  <h5 class="fw-bold">Fast Delivery</h5>
                  <p class="text-muted mb-0">Quick & reliable shipping</p>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="text-center">
                  <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                    <i class="bi bi-headset"></i>
                  </div>
                  <h5 class="fw-bold">24/7 Support</h5>
                  <p class="text-muted mb-0">Always here to help</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>AOS.init({duration: 800, once: true});</script>
</body>
</html>
