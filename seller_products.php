<?php
require 'auth.php';
requireRole('seller');
require 'config.php';

$userId = $_SESSION['user']['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed) && $_FILES['image']['size'] <= 5000000) {
            $image = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/products/' . $image);
        }
    }
    $stmt = $pdo->prepare('INSERT INTO products (seller_id, title, description, category, price, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$userId, $_POST['title'], $_POST['description'], $_POST['category'], $_POST['price'], $_POST['stock'], $image]);
    $_SESSION['success'] = 'Product added successfully! 🎉 Your listing is now live on the marketplace.';
    header('Location: seller_products.php');
    exit;
}
$stmt = $pdo->prepare('SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC');
$stmt->execute([$userId]);
$products = $stmt->fetchAll();

$productImages = [
  'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=800&q=80',
  'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&q=80',
  'https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=800&q=80',
  'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&q=80',
  'https://images.unsplash.com/photo-1592982537447-7d1485c86a22?w=800&q=80',
  'https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=800&q=80'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Products - Agri-Connect</title>
  <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/628/628283.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
          <li class="nav-item"><a class="nav-link active" href="seller_products.php"><i class="bi bi-box-seam me-1"></i>Add
           Products</a></li>
          <li class="nav-item"><a class="nav-link" href="conversations.php"><i class="bi bi-chat-dots me-1"></i>Messages</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['user']['full_name']) ?>
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
        <div class="col-lg-8" data-aos="fade-right">
          <h1 class="display-4 fw-bold mb-3"><i class="bi bi-box-seam me-3"></i>Your Agricultural Inventory</h1>
          <p class="lead mb-0">List your harvest, reach buyers nationwide • Turn your hard work into profit</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0" data-aos="fade-left">
          <div class="badge bg-warning text-dark px-4 py-3 fs-5">
            <i class="bi bi-box-seam me-2"></i><?= count($products) ?> Active Listings
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container my-5">
    <div class="card border-0 shadow-sm mb-5" data-aos="fade-up">
      <div class="card-body p-5">
        <div class="row align-items-center mb-4">
          <div class="col-md-8">
            <h3 class="fw-bold mb-2"><i class="bi bi-plus-circle-fill text-success me-2"></i>List Your Fresh Harvest</h3>
            <p class="text-muted mb-0">Share what you've grown with buyers who value quality. Every detail helps sell your product faster.</p>
          </div>
          <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <span class="badge bg-success-subtle text-success px-3 py-2 fs-6">
              <i class="bi bi-lightning-charge-fill me-1"></i>Quick & Easy
            </span>
          </div>
        </div>
        <form method="POST" enctype="multipart/form-data">
          <div class="mb-4">
            <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control form-control-lg" placeholder="e.g., 50kg Organic Basmati Rice, Fresh Tomatoes 100kg" required>
            <small class="text-muted">Be specific and include quantity for better visibility</small>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Detailed Description <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="5" placeholder="Tell buyers about your product: Where was it grown? Is it organic? What makes it special? Include harvest date, storage conditions, and any certifications." required></textarea>
            <small class="text-muted">Detailed descriptions increase buyer confidence and sales</small>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
            <select name="category" class="form-select form-select-lg" required>
              <option value="">Select a category</option>
              <option value="Vegetables">Vegetables</option>
              <option value="Fruits">Fruits</option>
              <option value="Grains">Grains</option>
              <option value="Seeds">Seeds</option>
              <option value="Fertilizers">Fertilizers</option>
              <option value="Tools">Tools</option>
              <option value="Livestock">Livestock</option>
              <option value="Other">Other</option>
            </select>
            <small class="text-muted">Choose the category that best describes your product</small>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Product Image <span class="text-danger">*</span></label>
            <input type="file" name="image" class="form-control form-control-lg" accept="image/*" required>
            <small class="text-muted">Upload a clear photo of your product (JPG, PNG, WEBP - Max 5MB)</small>
          </div>
          <div class="row">
            <div class="col-md-6 mb-4">
              <label class="form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
              <div class="input-group input-group-lg">
                <span class="input-group-text bg-success text-white"><i class="bi bi-box-seam"></i></span>
                <input type="number" name="stock" class="form-control" placeholder="0" min="0" required>
              </div>
              <small class="text-muted">Available quantity in stock</small>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label fw-semibold">Price per Unit (₱) <span class="text-danger">*</span></label>
              <div class="input-group input-group-lg">
                <span class="input-group-text bg-success text-white"><i class="bi bi-currency-dollar"></i></span>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
              </div>
              <small class="text-muted">Set competitive prices</small>
            </div>
          </div>
          <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">
            <i class="bi bi-rocket-takeoff me-2"></i>Publish to Marketplace Now
          </button>
        </form>
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
            <img id="profileImage" src="<?= !empty($_SESSION['user']['profile_picture']) ? 'uploads/profiles/' . htmlspecialchars($_SESSION['user']['profile_picture']) : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user']['full_name']) . '&size=150&background=6b8e23&color=fff' ?>" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #6b8e23;" alt="Profile">
            <label for="profilePictureInput" class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-2" style="cursor: pointer; border: 3px solid white;">
              <i class="bi bi-pencil-fill text-dark"></i>
            </label>
            <input type="file" id="profilePictureInput" accept="image/*" class="d-none">
          </div>
          <h4 class="fw-bold mb-1"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></h4>
          <p class="text-muted mb-3"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($_SESSION['user']['email']) ?></p>
          <span class="badge bg-success px-3 py-2"><i class="bi bi-award me-1"></i><?= ucfirst($_SESSION['user']['role']) ?></span>
          <hr class="my-4">
          <div class="text-start">
            <p class="mb-2"><strong><i class="bi bi-telephone me-2"></i>Phone:</strong> <?= htmlspecialchars($_SESSION['user']['phone'] ?? 'Not provided') ?></p>
            <p class="mb-2"><strong><i class="bi bi-geo-alt me-2"></i>Location:</strong> <?= htmlspecialchars($_SESSION['user']['location'] ?? 'Not provided') ?></p>
            <p class="mb-0"><strong><i class="bi bi-calendar me-2"></i>Member since:</strong> <?= isset($_SESSION['user']['created_at']) ? date('F Y', strtotime($_SESSION['user']['created_at'])) : 'N/A' ?></p>
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
    Swal.fire({icon: 'success', title: 'Success!', text: '<?= addslashes($_SESSION['success']) ?>', confirmButtonColor: '#6b8e23', timer: 3000});
    <?php unset($_SESSION['success']); endif; ?>

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
  </script>
</body>
</html>
