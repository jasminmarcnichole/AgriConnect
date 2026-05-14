<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'auth.php';
requireLogin();
require 'config.php';
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Agri-Connect</title>
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
          <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
          <?php if ($user['role'] === 'seller'): ?>
          <li class="nav-item"><a class="nav-link" href="seller_products.php"><i class="bi bi-box-seam me-1"></i>Add Products</a></li>
          <?php endif; ?>
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

  <!-- Page Header -->
  <div class="page-header">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8" data-aos="fade-right">
          <h1 class="display-4 fw-bold mb-3">Welcome Back, <?= htmlspecialchars($user['full_name']) ?>! <i class="bi bi-hand-thumbs-up-fill text-warning"></i></h1>
          <p class="lead mb-0">
            <?= $user['role'] === 'seller' 
              ? '<i class="bi bi-shop me-2"></i>Your Premium Seller Dashboard - Manage your agricultural empire and grow your business' 
              : '<i class="bi bi-cart-check me-2"></i>Your Buyer Dashboard - Discover quality agricultural products from verified farmers' ?>
          </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0" data-aos="fade-left">
          <div class="badge bg-warning text-dark px-4 py-3 fs-5">
            <i class="bi bi-award-fill me-2"></i><?= ucfirst($user['role']) ?> Account
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container my-5">
    <?php if ($user['role'] === 'buyer'): ?>
    <!-- Products Section for Buyers -->
    <div class="card border-0 shadow-sm mb-5" data-aos="fade-up" data-aos-delay="200">
      <div class="card-body p-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-grid-fill text-success me-2"></i>Available Products</h4>
        
        <!-- Search and Filter -->
        <div class="row g-3 mb-4">
          <div class="col-lg-6">
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
              <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
            </div>
          </div>
          <div class="col-lg-6">
            <select id="categoryFilter" class="form-select">
              <option value="">All Categories</option>
              <option value="Vegetables">Vegetables</option>
              <option value="Fruits">Fruits</option>
              <option value="Grains">Grains</option>
              <option value="Seeds">Seeds</option>
              <option value="Fertilizers">Fertilizers</option>
              <option value="Tools">Tools</option>
              <option value="Livestock">Livestock</option>
              <option value="Other">Other</option>
            </select>
          </div>
        </div>

        <!-- Products Grid -->
        <div id="productsContainer" class="row g-4">
          <?php
          try {
            $stmt = $pdo->query('SELECT p.*, u.full_name as seller_name, u.email as seller_email, u.created_at as seller_since, u.verification_status FROM products p JOIN users u ON p.seller_id = u.id WHERE p.stock > 0 ORDER BY p.created_at DESC');
            $products = $stmt->fetchAll();
            echo '<!-- Found ' . count($products) . ' products -->';
          } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            $products = [];
          }
          
          if (count($products) > 0):
            echo '<!-- Displaying ' . count($products) . ' products -->';
            foreach ($products as $product):
          ?>
          <div class="col-lg-3 col-md-4 col-sm-6 product-item" data-title="<?= strtolower(htmlspecialchars($product['title'])) ?>" data-category="<?= htmlspecialchars($product['category'] ?? 'Other') ?>">
            <div class="card border-0 shadow-sm h-100 product-card">
              <div class="position-relative">
                <?php if ($product['image']): ?>
                <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['title']) ?>" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                  <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                </div>
                <?php endif; ?>
                <span class="badge bg-success position-absolute top-0 end-0 m-2">
                  <i class="bi bi-box-seam me-1"></i><?= $product['stock'] ?> in stock
                </span>
              </div>
              <div class="card-body">
                <h6 class="fw-bold mb-2"><?= htmlspecialchars($product['title']) ?></h6>
                <p class="text-muted small mb-2"><?= htmlspecialchars(substr($product['description'], 0, 60)) ?>...</p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="text-success fw-bold fs-5">₱<?= number_format($product['price'], 2) ?></span>
                  <span class="badge bg-light text-dark"><?= htmlspecialchars($product['category'] ?? 'Other') ?></span>
                </div>
                <small class="text-muted d-block mb-3"><i class="bi bi-person me-1"></i><?= htmlspecialchars($product['seller_name']) ?></small>
                <button class="btn btn-success btn-sm w-100" onclick="showProductModal(<?= htmlspecialchars(json_encode($product)) ?>)"><i class="bi bi-eye me-1"></i>View Details</button>
              </div>
            </div>
          </div>
          <?php 
            endforeach;
          else:
          ?>
          <div class="col-12 text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
            <p class="text-muted mt-3">No products available at the moment</p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php else: ?>
    <!-- Seller's Products -->
    <div class="card border-0 shadow-sm mb-5" data-aos="fade-up">
      <div class="card-body p-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-grid-3x3-gap-fill text-success me-2"></i>Your Active Listings</h4>
        
        <?php
        $stmt = $pdo->prepare('SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC');
        $stmt->execute([$user['id']]);
        $sellerProducts = $stmt->fetchAll();
        
        if (count($sellerProducts) > 0):
        ?>
        <div class="row g-4">
          <?php foreach ($sellerProducts as $p): ?>
          <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 product-card">
              <div class="position-relative">
                <?php if ($p['image']): ?>
                <img src="uploads/products/<?= htmlspecialchars($p['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['title']) ?>" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                  <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                </div>
                <?php endif; ?>
                <span class="badge bg-success position-absolute top-0 end-0 m-2">
                  <i class="bi bi-box-seam me-1"></i><?= $p['stock'] ?> in stock
                </span>
                <?php if ($p['stock'] <= 0): ?>
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                  <i class="bi bi-x-circle me-1"></i>Out of Stock
                </span>
                <?php elseif ($p['stock'] < 10): ?>
                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                  <i class="bi bi-exclamation-triangle me-1"></i>Low Stock
                </span>
                <?php endif; ?>
              </div>
              <div class="card-body">
                <h6 class="fw-bold mb-2"><?= htmlspecialchars($p['title']) ?></h6>
                <p class="text-muted small mb-2"><?= htmlspecialchars(substr($p['description'], 0, 60)) ?>...</p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="text-success fw-bold fs-5">₱<?= number_format($p['price'], 2) ?></span>
                  <span class="badge bg-light text-dark"><?= htmlspecialchars($p['category'] ?? 'Other') ?></span>
                </div>
                <small class="text-muted d-block mb-3"><i class="bi bi-calendar me-1"></i><?= date('M d, Y', strtotime($p['created_at'])) ?></small>
                <button class="btn btn-outline-success btn-sm w-100" onclick='openEditModal(<?= json_encode($p) ?>)'><i class="bi bi-pencil me-1"></i>Edit Product</button>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
          <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
          <h5 class="text-muted mt-3">Your Storefront is Empty</h5>
          <p class="text-muted">Start listing your agricultural products. Thousands of buyers are waiting!</p>
          <a href="seller_products.php" class="btn btn-success mt-3"><i class="bi bi-plus-circle me-2"></i>Add Your First Product</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Product Details Modal -->
  <div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold" id="productModalTitle"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <img id="productModalImage" src="" class="img-fluid rounded" style="width: 100%; height: 300px; object-fit: cover;" alt="Product">
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <span class="badge bg-light text-dark" id="productModalCategory"></span>
                <span class="badge bg-success ms-2" id="productModalStock"></span>
              </div>
              <h3 class="text-success fw-bold mb-3" id="productModalPrice"></h3>
              <p class="text-muted" id="productModalDescription"></p>
              <hr>
              <h6 class="fw-bold mb-2"><i class="bi bi-person-circle me-2"></i>Seller Information</h6>
              <p class="mb-2" id="productModalSeller" style="cursor: pointer; color: #6b8e23;" onclick="showSellerModal()"><i class="bi bi-person me-2"></i><span id="sellerNameLink" class="text-decoration-underline"></span></p>
              <button class="btn btn-success w-100" id="messageSellerBtn"><i class="bi bi-chat-dots me-2"></i>Message Seller</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Product Modal -->
  <div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Product</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editProductForm" enctype="multipart/form-data">
            <input type="hidden" id="editProductId" name="id">
            <div class="mb-3">
              <label class="form-label fw-semibold">Current Image</label>
              <div class="mb-2">
                <img id="editCurrentImage" src="" class="img-fluid rounded" style="max-height: 200px;">
              </div>
              <label class="form-label fw-semibold">Change Image</label>
              <input type="file" name="image" id="editImage" class="form-control" accept="image/*">
              <small class="text-muted">Leave empty to keep current image</small>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
              <input type="text" name="title" id="editTitle" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
              <textarea name="description" id="editDescription" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
              <select name="category" id="editCategory" class="form-select" required>
                <option value="Vegetables">Vegetables</option>
                <option value="Fruits">Fruits</option>
                <option value="Grains">Grains</option>
                <option value="Seeds">Seeds</option>
                <option value="Fertilizers">Fertilizers</option>
                <option value="Tools">Tools</option>
                <option value="Livestock">Livestock</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Stock <span class="text-danger">*</span></label>
                <input type="number" name="stock" id="editStock" class="form-control" min="0" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Price (₱) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="price" id="editPrice" class="form-control" required>
              </div>
            </div>
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-circle me-2"></i>Update Product</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Seller Info Modal -->
  <div class="modal fade" id="sellerInfoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold"><i class="bi bi-shop me-2"></i>Seller Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <img id="sellerProfileImage" src="" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #6b8e23;" alt="Seller">
          <h4 class="fw-bold mb-1" id="sellerFullName"></h4>
          <div id="sellerVerifiedBadge" class="mb-3"></div>
          <hr class="my-3">
          <div class="text-start">
            <p class="mb-2" id="sellerEmail"></p>
            <p class="mb-2" id="sellerPhone"></p>
            <p class="mb-2" id="sellerLocation"></p>
            <p class="mb-0" id="sellerSince"></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Profile Modal -->
  <div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold"><i class="bi bi-person-circle me-2"></i>My Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <div class="position-relative d-inline-block mb-4">
            <img id="profileImage" src="<?= !empty($user['profile_picture']) ? 'uploads/profiles/' . htmlspecialchars($user['profile_picture']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name']) . '&size=150&background=6b8e23&color=fff' ?>" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #6b8e23;" alt="Profile">
            <label for="profilePictureInput" class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-2" style="cursor: pointer; border: 3px solid white;">
              <i class="bi bi-pencil-fill text-dark"></i>
            </label>
            <input type="file" id="profilePictureInput" accept="image/*" class="d-none">
          </div>
          <h4 class="fw-bold mb-1"><?= htmlspecialchars($user['full_name']) ?></h4>
          <p class="text-muted mb-3"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($user['email']) ?></p>
          <span class="badge bg-success px-3 py-2"><i class="bi bi-award me-1"></i><?= ucfirst($user['role']) ?></span>
          <hr class="my-4">
          <div class="text-start">
            <p class="mb-2"><strong><i class="bi bi-telephone me-2"></i>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></p>
            <p class="mb-2"><strong><i class="bi bi-geo-alt me-2"></i>Location:</strong> <?= htmlspecialchars($user['location'] ?? 'Not provided') ?></p>
            <p class="mb-0"><strong><i class="bi bi-calendar me-2"></i>Member since:</strong> <?= isset($user['created_at']) ? date('F Y', strtotime($user['created_at'])) : 'N/A' ?></p>
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
    
    function openEditModal(product) {
      document.getElementById('editProductId').value = product.id;
      document.getElementById('editTitle').value = product.title;
      document.getElementById('editDescription').value = product.description;
      document.getElementById('editCategory').value = product.category || 'Other';
      document.getElementById('editStock').value = product.stock;
      document.getElementById('editPrice').value = product.price;
      document.getElementById('editCurrentImage').src = product.image ? 'uploads/products/' + product.image : '';
      new bootstrap.Modal(document.getElementById('editProductModal')).show();
    }
    
    document.getElementById('editProductForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      try {
        const response = await fetch('update_product.php', {
          method: 'POST',
          body: formData
        });
        const result = await response.json();
        if (result.success) {
          Swal.fire({icon: 'success', title: 'Success!', text: result.message}).then(() => location.reload());
        } else {
          Swal.fire({icon: 'error', title: 'Error', text: result.message});
        }
      } catch (error) {
        Swal.fire({icon: 'error', title: 'Error', text: 'Failed to update product'});
      }
    });
    
    <?php if (isset($_SESSION['success'])): ?>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '<?= addslashes($_SESSION['success']) ?>',
      confirmButtonColor: '#6b8e23',
      timer: 3000
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

    // Search and Filter functionality
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const productItems = document.querySelectorAll('.product-item');

    function filterProducts() {
      const searchTerm = searchInput?.value.toLowerCase() || '';
      const selectedCategory = categoryFilter?.value || '';

      productItems.forEach(item => {
        const title = item.dataset.title;
        const category = item.dataset.category;
        const matchesSearch = title.includes(searchTerm);
        const matchesCategory = !selectedCategory || category === selectedCategory;

        if (matchesSearch && matchesCategory) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    }

    searchInput?.addEventListener('input', filterProducts);
    categoryFilter?.addEventListener('change', filterProducts);

    // Profile picture upload
    document.getElementById('profilePictureInput').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const formData = new FormData();
        formData.append('profile_picture', file);
        
        fetch('upload_profile.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            document.getElementById('profileImage').src = data.image_url;
            Swal.fire({icon: 'success', title: 'Profile picture updated!', timer: 2000, showConfirmButton: false});
          } else {
            Swal.fire({icon: 'error', title: 'Upload failed', text: data.message});
          }
        })
        .catch(() => Swal.fire({icon: 'error', title: 'Upload failed'}));
      }
    });

    // Product modal
    let currentProduct = null;
    function showProductModal(product) {
      currentProduct = product;
      document.getElementById('productModalTitle').textContent = product.title;
      document.getElementById('productModalImage').src = product.image ? 'uploads/products/' + product.image : 'https://via.placeholder.com/300x300?text=No+Image';
      document.getElementById('productModalCategory').textContent = product.category || 'Other';
      document.getElementById('productModalStock').innerHTML = '<i class="bi bi-box-seam me-1"></i>' + product.stock + ' in stock';
      document.getElementById('productModalPrice').textContent = '₱' + parseFloat(product.price).toLocaleString('en-PH', {minimumFractionDigits: 2});
      document.getElementById('productModalDescription').textContent = product.description;
      document.getElementById('sellerNameLink').textContent = product.seller_name;
      document.getElementById('messageSellerBtn').onclick = () => window.location.href = 'conversations.php?seller_id=' + product.seller_id;
      new bootstrap.Modal(document.getElementById('productModal')).show();
    }

    // Seller info modal
    function showSellerModal() {
      if (!currentProduct) return;
      const sellerImg = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(currentProduct.seller_name) + '&size=120&background=6b8e23&color=fff';
      document.getElementById('sellerProfileImage').src = sellerImg;
      document.getElementById('sellerFullName').textContent = currentProduct.seller_name;
      document.getElementById('sellerVerifiedBadge').innerHTML = currentProduct.verification_status === 'approved' ? '<span class="badge bg-success px-3 py-2"><i class="bi bi-patch-check-fill me-1"></i>Verified Seller</span>' : '<span class="badge bg-secondary px-3 py-2"><i class="bi bi-person me-1"></i>Seller</span>';
      document.getElementById('sellerEmail').innerHTML = '<strong><i class="bi bi-envelope me-2"></i>Email:</strong> ' + (currentProduct.seller_email || 'Not provided');
      document.getElementById('sellerPhone').innerHTML = '<strong><i class="bi bi-telephone me-2"></i>Phone:</strong> Not provided';
      document.getElementById('sellerLocation').innerHTML = '<strong><i class="bi bi-geo-alt me-2"></i>Location:</strong> Not provided';
      document.getElementById('sellerSince').innerHTML = '<strong><i class="bi bi-calendar me-2"></i>Member since:</strong> ' + (currentProduct.seller_since ? new Date(currentProduct.seller_since).toLocaleDateString('en-US', {month: 'long', year: 'numeric'}) : 'N/A');
      new bootstrap.Modal(document.getElementById('sellerInfoModal')).show();
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
