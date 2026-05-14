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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Conversations - Agri-Connect</title>
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
          <?php if ($user['role'] === 'seller'): ?>
          <li class="nav-item"><a class="nav-link" href="seller_products.php"><i class="bi bi-box-seam me-1"></i>Add Products</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link active" href="conversations.php"><i class="bi bi-chat-dots me-1"></i>Messages</a></li>
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
        <div class="col-lg-8" data-aos="fade-right">
          <h1 class="display-4 fw-bold mb-3"><i class="bi bi-chat-dots-fill me-3"></i>Your Active Conversations</h1>
          <p class="lead mb-0">Connect directly with <?= $user['role']==='buyer' ? 'farmers and suppliers' : 'interested buyers' ?> • Build relationships, close deals</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0" data-aos="fade-left">
          <div class="badge bg-primary text-white px-4 py-3 fs-5">
            <i class="bi bi-chat-square-text me-2"></i><?= count($rows) ?> Conversations
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container my-5">
    <?php if (count($rows) > 0): ?>
    <div class="row g-4">
      <?php foreach ($rows as $index => $r): ?>
      <div class="col-12" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
        <div class="card border-0 shadow-sm hover-lift">
          <div class="card-body p-4">
            <div class="row align-items-center">
              <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                  <div class="flex-shrink-0">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                      <i class="bi bi-person-fill"></i>
                    </div>
                  </div>
                  <div class="ms-3">
                    <h4 class="fw-bold mb-1"><?= htmlspecialchars($user['role']==='buyer' ? $r['seller_name'] : $r['buyer_name']) ?></h4>
                    <p class="text-muted mb-0">
                      <i class="bi bi-patch-check-fill text-success me-1"></i>
                      <?= $user['role']==='buyer' ? 'Verified Seller' : 'Interested Buyer' ?>
                    </p>
                  </div>
                </div>
                <?php if (!empty($r['product_title'])): ?>
                <div class="alert alert-success-subtle border-success mb-0">
                  <i class="bi bi-box-seam text-success me-2"></i>
                  <strong>Discussing:</strong> <?= htmlspecialchars($r['product_title']) ?>
                </div>
                <?php endif; ?>
              </div>
              <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="chat.php?seller_id=<?= (int)$r['seller_id'] ?>&buyer_id=<?= (int)$r['buyer_id'] ?>&product_id=<?= (int)$r['product_id'] ?>" class="btn btn-success btn-lg fw-bold">
                  <i class="bi bi-chat-dots-fill me-2"></i>Continue Chat
                </a>
                <div class="mt-2">
                  <small class="text-muted"><i class="bi bi-clock me-1"></i>Started <?= date('M d, Y', strtotime($r['created_at'])) ?></small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state" data-aos="zoom-in">
      <div class="empty-state-icon"><i class="bi bi-chat-dots"></i></div>
      <h3 class="fw-bold text-muted mb-3">No Conversations Yet</h3>
      <p class="text-muted mb-4">
        <?= $user['role']==='buyer' 
          ? 'Browse products and start chatting with sellers to get the best deals on fresh agricultural products!' 
          : 'When buyers contact you about your products, conversations will appear here.' ?>
      </p>
      <a href="products.php" class="btn btn-success btn-lg fw-bold">
        <i class="bi bi-shop me-2"></i>Browse Marketplace
      </a>
    </div>
    <?php endif; ?>
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

  <style>
    .hover-lift {
      transition: all 0.3s ease;
    }
    .hover-lift:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.15) !important;
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    AOS.init({duration: 800, once: true});

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
