<?php 
session_start();
require_once 'config.php';

// Fetch latest products
$stmt = $pdo->query("SELECT p.*, u.full_name as seller_name FROM products p JOIN users u ON p.seller_id = u.id WHERE u.verification_status = 'approved' ORDER BY p.created_at DESC LIMIT 6");
$products = $stmt->fetchAll();

$defaultImage = 'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=800&q=80';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Agri-Connect - Premium agricultural marketplace connecting farmers, suppliers, and buyers nationwide. Secure, verified, and efficient.">
  <title>Agri-Connect - Premium Agricultural Marketplace</title>
  <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/628/628283.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/premium-styles.css">
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top premium-nav">
    <div class="container-fluid px-4">
      <a class="navbar-brand d-flex align-items-center" href="#home">
        <img src="https://cdn-icons-png.flaticon.com/512/628/628283.png" alt="Logo" width="45" height="45" class="me-2">
        <span class="fw-bold fs-4">Agri<span class="text-warning">Connect</span></span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#products">Marketplace</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">About</a></li>

          <?php if (!isset($_SESSION['user'])): ?>
            <li class="nav-item"><button class="btn btn-warning text-dark fw-bold ms-3" data-bs-toggle="modal" data-bs-target="#authModal"><i class="bi bi-person-circle me-1"></i>Login / Sign Up</button></li>
            <li class="nav-item"><a href="seller_signup.php" class="btn btn-success text-white fw-bold ms-2"><i class="bi bi-shop me-1"></i>Become a Supplier</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="btn btn-warning text-dark fw-bold ms-3" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="home" class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container position-relative" style="z-index: 2;">
      <div class="row align-items-center min-vh-100">
        <div class="col-lg-7" data-aos="fade-right">
          <span class="badge bg-success-subtle text-success px-4 py-2 mb-3 fs-6"><i class="bi bi-award-fill me-2"></i>Trusted by 10,000+ Farmers</span>
          <h1 class="display-2 fw-bold text-white mb-4">Cultivating Connections, <span class="text-warning">Growing Together</span></h1>
          <p class="lead text-white-50 mb-5 fs-4">Join the most trusted agricultural marketplace where quality meets opportunity. Connect with verified farmers and suppliers nationwide, ensuring fresh produce and sustainable partnerships.</p>
          <div class="d-flex gap-3 flex-wrap">
            <?php if (!isset($_SESSION['user'])): ?>
              <button class="btn btn-warning btn-lg px-5 py-3 fw-bold" data-bs-toggle="modal" data-bs-target="#authModal"><i class="bi bi-box-arrow-in-right me-2"></i>Login / Sign Up</button>
              <a href="seller_signup.php" class="btn btn-success btn-lg px-5 py-3 fw-bold text-white"><i class="bi bi-shop me-2"></i>Become a Supplier</a>
            <?php else: ?>
              <a href="products.php" class="btn btn-warning btn-lg px-5 py-3 fw-bold"><i class="bi bi-shop me-2"></i>Browse Products</a>
              <a href="dashboard.php" class="btn btn-outline-light btn-lg px-5 py-3"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <?php endif; ?>
          </div>
          <div class="row mt-5 g-4">
            <div class="col-4"><div class="text-white text-center"><h3 class="fw-bold text-warning mb-0">10K+</h3><small class="text-white-50">Active Users</small></div></div>
            <div class="col-4"><div class="text-white text-center"><h3 class="fw-bold text-warning mb-0">50K+</h3><small class="text-white-50">Products</small></div></div>
            <div class="col-4"><div class="text-white text-center"><h3 class="fw-bold text-warning mb-0">98%</h3><small class="text-white-50">Satisfaction</small></div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-6 bg-light">
    <div class="container">
      <div class="text-center mb-5" data-aos="fade-up">
        <span class="badge bg-success-subtle text-success px-4 py-2 mb-3 fs-6">Why Choose Us</span>
        <h2 class="display-4 fw-bold text-success mb-3">Why Farmers Trust Agri-Connect</h2>
        <p class="lead text-muted">Empowering agricultural communities with cutting-edge technology</p>
      </div>
      <div class="row g-4">
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="feature-card h-100">
            <div class="feature-icon bg-success"><i class="bi bi-shield-check"></i></div>
            <h4 class="fw-bold mb-3">Bank-Level Security</h4>
            <p class="text-muted">Your transactions are protected with enterprise-grade encryption. Trade with confidence knowing every deal is secure and verified.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
          <div class="feature-card h-100">
            <div class="feature-icon bg-primary"><i class="bi bi-patch-check-fill"></i></div>
            <h4 class="fw-bold mb-3">100% Verified Sellers</h4>
            <p class="text-muted">Every seller undergoes rigorous verification. Buy from trusted farmers with proven track records of excellence.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
          <div class="feature-card h-100">
            <div class="feature-icon bg-warning"><i class="bi bi-truck"></i></div>
            <h4 class="fw-bold mb-3">Lightning-Fast Delivery</h4>
            <p class="text-muted">From farm to your doorstep in record time. Our logistics network ensures fresh produce arrives on schedule.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
          <div class="feature-card h-100">
            <div class="feature-icon bg-info"><i class="bi bi-chat-dots-fill"></i></div>
            <h4 class="fw-bold mb-3">Instant Communication</h4>
            <p class="text-muted">Chat directly with sellers in real-time. Build relationships, negotiate deals, and get answers instantly.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
          <div class="feature-card h-100">
            <div class="feature-icon bg-danger"><i class="bi bi-graph-up-arrow"></i></div>
            <h4 class="fw-bold mb-3">Smart Market Insights</h4>
            <p class="text-muted">Make data-driven decisions with real-time pricing trends, demand forecasts, and market analytics.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
          <div class="feature-card h-100">
            <div class="feature-icon bg-secondary"><i class="bi bi-globe-americas"></i></div>
            <h4 class="fw-bold mb-3">Nationwide Network</h4>
            <p class="text-muted">Access thousands of buyers and sellers across the country. Expand your reach exponentially.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Products Section -->
  <section id="products" class="py-6 bg-white">
    <div class="container">
      <div class="text-center mb-5" data-aos="fade-up">
        <span class="badge bg-success-subtle text-success px-4 py-2 mb-3 fs-6">Fresh from Farms</span>
        <h2 class="display-4 fw-bold text-success mb-3">Featured Products</h2>
        <p class="lead text-muted">Discover quality agricultural products from verified sellers</p>
      </div>
      <div class="row g-4">
        <?php if (empty($products)): ?>
          <div class="col-12 text-center py-5">
            <i class="bi bi-box-seam display-1 text-muted"></i>
            <p class="lead text-muted mt-3">No products available yet. Be the first seller!</p>
          </div>
        <?php else: ?>
          <?php foreach ($products as $product): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
              <div class="card h-100 border-0 shadow-sm product-card" style="cursor: pointer;" onclick="handleProductClick(<?= $product['id'] ?>)">
                <div style="height: 200px; background: url('<?= $product['image'] ? 'uploads/products/' . htmlspecialchars($product['image']) : $defaultImage ?>') center/cover; position: relative;">
                  <span class="badge bg-success position-absolute top-0 start-0 m-2"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                  <?php if ($product['stock'] <= 0): ?>
                  <span class="badge bg-danger position-absolute top-0 end-0 m-2"><i class="bi bi-x-circle me-1"></i>Out of Stock</span>
                  <?php elseif ($product['stock'] < 10): ?>
                  <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2"><i class="bi bi-exclamation-triangle me-1"></i>Low Stock</span>
                  <?php else: ?>
                  <span class="badge bg-info position-absolute top-0 end-0 m-2"><i class="bi bi-box-seam me-1"></i><?= $product['stock'] ?> available</span>
                  <?php endif; ?>
                </div>
                <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="text-success fw-bold mb-0">₱<?= number_format($product['price'], 2) ?></h4>
                  </div>
                  <h5 class="fw-bold mb-2"><?= htmlspecialchars($product['title']) ?></h5>
                  <p class="text-muted mb-3"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?><?= strlen($product['description']) > 100 ? '...' : '' ?></p>
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center text-muted small">
                      <i class="bi bi-person-circle me-2"></i>
                      <span><?= htmlspecialchars($product['seller_name']) ?></span>
                    </div>
                    <span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>-subtle text-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
                      <?= $product['stock'] ?> in stock
                    </span>
                  </div>
                </div>
                <div class="card-footer bg-light border-0 p-3">
                  <button class="btn btn-success w-100" onclick="event.stopPropagation(); handleProductClick(<?= $product['id'] ?>)">
                    <i class="bi bi-eye me-2"></i>View Details
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <?php if (!empty($products)): ?>
        <div class="text-center mt-5" data-aos="fade-up">
          <a href="products.php" class="btn btn-outline-success btn-lg px-5 py-3">
            <i class="bi bi-grid-3x3-gap me-2"></i>View All Products
          </a>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="py-6 stats-section">
    <div class="container">
      <div class="row g-4 text-center text-white">
        <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
          <div class="stat-card"><i class="bi bi-people-fill display-3 mb-3 text-warning"></i><h2 class="display-4 fw-bold">10,000+</h2><p class="lead">Happy Farmers & Buyers</p></div>
        </div>
        <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
          <div class="stat-card"><i class="bi bi-box-seam display-3 mb-3 text-warning"></i><h2 class="display-4 fw-bold">50,000+</h2><p class="lead">Quality Products Listed</p></div>
        </div>
        <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
          <div class="stat-card"><i class="bi bi-star-fill display-3 mb-3 text-warning"></i><h2 class="display-4 fw-bold">98%</h2><p class="lead">Customer Satisfaction</p></div>
        </div>
        <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
          <div class="stat-card"><i class="bi bi-headset display-3 mb-3 text-warning"></i><h2 class="display-4 fw-bold">24/7</h2><p class="lead">Dedicated Support</p></div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-6 bg-white">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-6" data-aos="fade-right">
          <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&q=80" alt="Agriculture" class="img-fluid rounded-4 shadow-lg">
        </div>
        <div class="col-lg-6" data-aos="fade-left">
          <span class="badge bg-success-subtle text-success px-4 py-2 mb-3 fs-6">Our Story</span>
          <h2 class="display-4 fw-bold text-success mb-4">Revolutionizing Agricultural Commerce</h2>
          <p class="lead text-muted mb-4">We're more than just a marketplace – we're a movement. Agri-Connect bridges the gap between traditional farming and modern technology, creating a sustainable ecosystem where every farmer can thrive.</p>
          <div class="row g-4 mt-4">
            <div class="col-md-6">
              <div class="d-flex align-items-start">
                <div class="flex-shrink-0"><i class="bi bi-bullseye fs-1 text-success"></i></div>
                <div class="ms-3"><h5 class="fw-bold">Our Mission</h5><p class="text-muted">Empower every farmer with technology, creating prosperity through innovation.</p></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-start">
                <div class="flex-shrink-0"><i class="bi bi-eye fs-1 text-success"></i></div>
                <div class="ms-3"><h5 class="fw-bold">Our Vision</h5><p class="text-muted">A world where every agricultural transaction is transparent and efficient.</p></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-6 cta-section">
    <div class="container text-center text-white" data-aos="zoom-in">
      <h2 class="display-3 fw-bold mb-4">Ready to Transform Your Agricultural Business?</h2>
      <p class="lead mb-5 fs-4">Join thousands of satisfied farmers and buyers who've revolutionized their trade</p>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <button class="btn btn-warning btn-lg px-5 py-3 fw-bold" data-bs-toggle="modal" data-bs-target="#authModal"><i class="bi bi-box-arrow-in-right me-2"></i>Get Started</button>
        <a href="seller_signup.php" class="btn btn-success btn-lg px-5 py-3 fw-bold text-white"><i class="bi bi-shop me-2"></i>Become a Supplier</a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4">
          <h5 class="fw-bold mb-3"><img src="https://cdn-icons-png.flaticon.com/512/628/628283.png" width="30" class="me-2">AgriConnect</h5>
          <p class="text-white-50">Cultivating connections, growing together. The premier agricultural marketplace.</p>
        </div>
        <div class="col-lg-4">
          <h5 class="fw-bold mb-3">Quick Links</h5>
          <ul class="list-unstyled">
            <li><a href="#features" class="text-white-50 text-decoration-none">Features</a></li>
            <li><a href="#about" class="text-white-50 text-decoration-none">About Us</a></li>
            <li><a href="products.php" class="text-white-50 text-decoration-none">Marketplace</a></li>
          </ul>
        </div>
        <div class="col-lg-4">
          <h5 class="fw-bold mb-3">Contact</h5>
          <p class="text-white-50"><i class="bi bi-envelope me-2"></i>support@agriconnect.com</p>
          <p class="text-white-50"><i class="bi bi-telephone me-2"></i>+1 (555) 123-4567</p>
        </div>
      </div>
      <hr class="my-4 bg-white">
      <div class="text-center text-white-50"><p class="mb-0">&copy; 2024 Agri-Connect. All rights reserved. Made with <i class="bi bi-heart-fill text-danger"></i> for farmers.</p></div>
    </div>
  </footer>

  <!-- Combined Auth Modal -->
  <div class="modal fade" id="authModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-0 bg-warning">
          <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-circle me-2"></i>Welcome to Agri-Connect</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <!-- Tabs -->
          <ul class="nav nav-pills nav-fill mb-4" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button" role="tab">
                <i class="bi bi-box-arrow-in-right me-1"></i>Login
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="signup-tab" data-bs-toggle="pill" data-bs-target="#signup" type="button" role="tab">
                <i class="bi bi-person-plus me-1"></i>Sign Up
              </button>
            </li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content" id="authTabContent">
            <!-- Login Tab -->
            <div class="tab-pane fade show active" id="login" role="tabpanel">
              <form method="POST" action="login.php">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Email Address</label>
                  <input type="email" name="email" class="form-control form-control-lg" placeholder="your@email.com" required>
                </div>
                <div class="mb-4">
                  <label class="form-label fw-semibold">Password</label>
                  <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold"><i class="bi bi-check-circle me-2"></i>Login</button>
              </form>
            </div>

            <!-- Signup Tab -->
            <div class="tab-pane fade" id="signup" role="tabpanel">
              <div class="alert alert-info border-0 mb-3">
                <small><i class="bi bi-info-circle me-1"></i>Want to sell products? <a href="seller_signup.php" class="alert-link fw-bold">Become a Supplier</a></small>
              </div>
              <form method="POST" action="signup.php">
                <input type="hidden" name="role" value="buyer">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Full Name</label>
                  <input type="text" name="full_name" class="form-control form-control-lg" placeholder="John Doe" required>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Email Address</label>
                  <input type="email" name="email" class="form-control form-control-lg" placeholder="your@email.com" required>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Password</label>
                  <input type="password" name="password" class="form-control form-control-lg" placeholder="Create a strong password" required>
                </div>
                <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold text-dark"><i class="bi bi-check-circle me-2"></i>Create Account</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    AOS.init({duration: 800, once: true});
    
    <?php if (isset($_SESSION['success'])): ?>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '<?= addslashes($_SESSION['success']) ?>',
      confirmButtonColor: '#6b8e23',
      timer: 3000,
      showConfirmButton: false
    });
    <?php unset($_SESSION['success']); endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: '<?= addslashes($_SESSION['error']) ?>',
      confirmButtonColor: '#6b8e23'
    });
    <?php unset($_SESSION['error']); endif; ?>
    
    // Handle product click - require login
    function handleProductClick(productId) {
      <?php if (!isset($_SESSION['user'])): ?>
        Swal.fire({
          icon: 'info',
          title: 'Login Required',
          text: 'Please login or signup to view product details',
          showCancelButton: true,
          confirmButtonText: 'Login / Sign Up',
          cancelButtonText: 'Cancel',
          confirmButtonColor: '#6b8e23'
        }).then((result) => {
          if (result.isConfirmed) {
            const authModal = new bootstrap.Modal(document.getElementById('authModal'));
            authModal.show();
          }
        });
      <?php else: ?>
        window.location.href = 'product.php?id=' + productId;
      <?php endif; ?>
    }
  </script>
  
  <style>
    .product-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
  </style>
</body>
</html>
