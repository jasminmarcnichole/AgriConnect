<?php
require 'auth.php';
requireRole('seller');
require 'config.php';

$id = (int)($_GET['id'] ?? 0);
$userId = $_SESSION['user']['id'];

$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ? AND seller_id = ?');
$stmt->execute([$id, $userId]);
$product = $stmt->fetch();

if (!$product) {
    $_SESSION['error'] = 'Product not found or you do not have permission to edit it.';
    header('Location: seller_products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = $product['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed) && $_FILES['image']['size'] <= 5000000) {
            if ($product['image'] && file_exists('uploads/products/' . $product['image'])) {
                unlink('uploads/products/' . $product['image']);
            }
            $image = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/products/' . $image);
        }
    }
    
    $stmt = $pdo->prepare('UPDATE products SET title = ?, description = ?, category = ?, price = ?, stock = ?, image = ? WHERE id = ? AND seller_id = ?');
    $stmt->execute([$_POST['title'], $_POST['description'], $_POST['category'], $_POST['price'], $_POST['stock'], $image, $id, $userId]);
    
    $_SESSION['success'] = 'Product updated successfully!';
    header('Location: product.php?id=' . $id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Product - Agri-Connect</title>
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
          <li class="nav-item"><a class="nav-link" href="products.php"><i class="bi bi-shop me-1"></i>Marketplace</a></li>
          <li class="nav-item"><a class="nav-link" href="seller_products.php"><i class="bi bi-box-seam me-1"></i>My Products</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container my-5" style="margin-top: 100px !important;">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-success text-white p-4">
            <h3 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Product</h3>
          </div>
          <div class="card-body p-5">
            <form method="POST" enctype="multipart/form-data">
              <div class="mb-4">
                <label class="form-label fw-semibold">Current Product Image</label>
                <?php if ($product['image']): ?>
                <div class="mb-3">
                  <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="Product" class="img-fluid rounded" style="max-height: 300px;">
                </div>
                <?php endif; ?>
                <label class="form-label fw-semibold">Change Product Image</label>
                <input type="file" name="image" class="form-control form-control-lg" accept="image/*">
                <small class="text-muted">Leave empty to keep current image (JPG, PNG, WEBP - Max 5MB)</small>
              </div>

              <div class="mb-4">
                <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg" value="<?= htmlspecialchars($product['title']) ?>" required>
              </div>

              <div class="mb-4">
                <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($product['description']) ?></textarea>
              </div>

              <div class="mb-4">
                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                <select name="category" class="form-select form-select-lg" required>
                  <option value="Vegetables" <?= ($product['category'] ?? '') === 'Vegetables' ? 'selected' : '' ?>>Vegetables</option>
                  <option value="Fruits" <?= ($product['category'] ?? '') === 'Fruits' ? 'selected' : '' ?>>Fruits</option>
                  <option value="Grains" <?= ($product['category'] ?? '') === 'Grains' ? 'selected' : '' ?>>Grains</option>
                  <option value="Seeds" <?= ($product['category'] ?? '') === 'Seeds' ? 'selected' : '' ?>>Seeds</option>
                  <option value="Fertilizers" <?= ($product['category'] ?? '') === 'Fertilizers' ? 'selected' : '' ?>>Fertilizers</option>
                  <option value="Tools" <?= ($product['category'] ?? '') === 'Tools' ? 'selected' : '' ?>>Tools</option>
                  <option value="Livestock" <?= ($product['category'] ?? '') === 'Livestock' ? 'selected' : '' ?>>Livestock</option>
                  <option value="Other" <?= ($product['category'] ?? 'Other') === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4">
                  <label class="form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text bg-success text-white"><i class="bi bi-box-seam"></i></span>
                    <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" min="0" required>
                  </div>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label fw-semibold">Price (₱) <span class="text-danger">*</span></label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text bg-success text-white"><i class="bi bi-currency-dollar"></i></span>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
                  </div>
                </div>
              </div>

              <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success btn-lg flex-fill fw-bold">
                  <i class="bi bi-check-circle me-2"></i>Update Product
                </button>
                <a href="product.php?id=<?= $id ?>" class="btn btn-outline-secondary btn-lg">
                  <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
