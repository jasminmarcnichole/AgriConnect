<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Become a Supplier - Agri-Connect</title>
  <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/628/628283.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/premium-styles.css">
</head>
<body class="bg-light">
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top premium-nav">
    <div class="container-fluid px-4">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="https://cdn-icons-png.flaticon.com/512/628/628283.png" alt="Logo" width="45" height="45" class="me-2">
        <span class="fw-bold fs-4">Agri<span class="text-warning">Connect</span></span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="index.php#home">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#features">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#about">About</a></li>
          <li class="nav-item"><a class="nav-link" href="products.php">Marketplace</a></li>
          <?php if (!isset($_SESSION['user'])): ?>
            <li class="nav-item"><a href="index.php" class="btn btn-warning text-dark fw-bold ms-3"><i class="bi bi-person-circle me-1"></i>Login / Sign Up</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="btn btn-warning text-dark fw-bold ms-3" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card border-0 shadow-lg">
          <div class="card-header bg-success text-white text-center py-4">
            <h3 class="mb-0 fw-bold"><i class="bi bi-shop me-2"></i>Become a Verified Supplier</h3>
            <p class="mb-0 mt-2">Join thousands of successful farmers selling on Agri-Connect</p>
          </div>
          <div class="card-body p-5">
            <div class="alert alert-info border-0 mb-4">
              <h5 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Requirements & Process</h5>
              <ul class="mb-0">
                <li>Valid government-issued ID (Driver's License, Passport, National ID)</li>
                <li>Clear photo or scan showing your name and signature</li>
                <li>Admin verification required (typically 24-48 hours)</li>
                <li>Email notification upon approval</li>
              </ul>
            </div>

            <form method="POST" action="seller_register.php" enctype="multipart/form-data">
              <h5 class="fw-bold mb-3 text-success">Personal Information</h5>
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                  <input type="text" name="full_name" class="form-control form-control-lg" placeholder="Juan Dela Cruz" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control form-control-lg" placeholder="your@email.com" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                  <input type="password" name="password" class="form-control form-control-lg" placeholder="Minimum 6 characters" required minlength="6">
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Phone Number</label>
                  <input type="tel" name="phone" class="form-control form-control-lg" placeholder="+63 912 345 6789">
                </div>
              </div>

              <h5 class="fw-bold mb-3 text-success">Verification Documents</h5>
              <div class="mb-4">
                <label class="form-label fw-semibold">Valid ID Document <span class="text-danger">*</span></label>
                <input type="file" name="id_document" class="form-control form-control-lg" accept=".jpg,.jpeg,.png,.pdf" required>
                <small class="text-muted">Accepted: JPG, PNG, PDF (Max 5MB). Must show your name and signature clearly.</small>
              </div>

              <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">
                  I agree to the <a href="#" class="text-success">Terms & Conditions</a> and confirm that all information provided is accurate
                </label>
              </div>

              <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">
                <i class="bi bi-check-circle me-2"></i>Submit Application
              </button>
            </form>
          </div>
        </div>

        <div class="text-center mt-4">
          <p class="text-muted">Already have an account? <a href="index.php" class="text-success fw-semibold" data-bs-toggle="modal" data-bs-target="#loginModal">Login here</a></p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    <?php if (isset($_SESSION['success'])): ?>
    Swal.fire({
      icon: 'success',
      title: 'Application Submitted!',
      text: '<?= addslashes($_SESSION['success']) ?>',
      confirmButtonColor: '#6b8e23'
    }).then(() => {
      window.location.href = 'index.php';
    });
    <?php unset($_SESSION['success']); endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: '<?= addslashes($_SESSION['error']) ?>',
      confirmButtonColor: '#6b8e23'
    });
    <?php unset($_SESSION['error']); endif; ?>
  </script>
</body>
</html>
