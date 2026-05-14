<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->query("SELECT id, full_name, email, role, verification_status, id_document, created_at FROM users WHERE role = 'seller' ORDER BY verification_status = 'pending' DESC, created_at DESC");
$sellers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Agri-Connect</title>
  <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/628/628283.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/premium-styles.css">
</head>
<body class="bg-light">
  <nav class="navbar navbar-dark bg-success">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1"><i class="bi bi-shield-check me-2"></i>Admin Dashboard</span>
      <a href="logout.php" class="btn btn-outline-light"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
  </nav>

  <div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-people me-2"></i>Seller Verification Management</h2>
    
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card text-center border-warning">
          <div class="card-body">
            <h3 class="text-warning"><?= count(array_filter($sellers, fn($s) => $s['verification_status'] === 'pending')) ?></h3>
            <p class="mb-0">Pending Verification</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center border-success">
          <div class="card-body">
            <h3 class="text-success"><?= count(array_filter($sellers, fn($s) => $s['verification_status'] === 'approved')) ?></h3>
            <p class="mb-0">Approved Sellers</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center border-danger">
          <div class="card-body">
            <h3 class="text-danger"><?= count(array_filter($sellers, fn($s) => $s['verification_status'] === 'rejected')) ?></h3>
            <p class="mb-0">Rejected</p>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow">
      <div class="card-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Status</th>
              <th>Registered</th>
              <th>ID Document</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sellers as $seller): ?>
            <tr>
              <td><?= htmlspecialchars($seller['full_name']) ?></td>
              <td><?= htmlspecialchars($seller['email']) ?></td>
              <td>
                <?php if ($seller['verification_status'] === 'pending'): ?>
                  <span class="badge bg-warning">Pending</span>
                <?php elseif ($seller['verification_status'] === 'approved'): ?>
                  <span class="badge bg-success">Approved</span>
                <?php else: ?>
                  <span class="badge bg-danger">Rejected</span>
                <?php endif; ?>
              </td>
              <td><?= date('M d, Y', strtotime($seller['created_at'])) ?></td>
              <td>
                <?php if ($seller['id_document']): ?>
                  <a href="uploads/id_documents/<?= $seller['id_document'] ?>" target="_blank" class="btn btn-sm btn-info"><i class="bi bi-file-earmark-text"></i> View</a>
                <?php else: ?>
                  <span class="text-muted">N/A</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($seller['verification_status'] === 'pending'): ?>
                  <button class="btn btn-sm btn-success" onclick="updateStatus(<?= $seller['id'] ?>, 'approved')"><i class="bi bi-check-circle"></i> Approve</button>
                  <button class="btn btn-sm btn-danger" onclick="updateStatus(<?= $seller['id'] ?>, 'rejected')"><i class="bi bi-x-circle"></i> Reject</button>
                <?php else: ?>
                  <button class="btn btn-sm btn-secondary" onclick="updateStatus(<?= $seller['id'] ?>, 'pending')"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function updateStatus(userId, status) {
      Swal.fire({
        title: 'Are you sure?',
        text: `You want to ${status} this seller?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6b8e23',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!'
      }).then((result) => {
        if (result.isConfirmed) {
          // Show loading
          Swal.fire({
            title: 'Processing...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
          
          fetch('admin_verify_seller.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({user_id: userId, status: status})
          })
          .then(response => {
            // Log the response for debugging
            console.log('Response status:', response.status);
            
            // Get the response text first
            return response.text().then(text => {
              console.log('Response text:', text);
              
              // Try to parse as JSON
              try {
                return JSON.parse(text);
              } catch (e) {
                console.error('JSON parse error:', e);
                console.error('Response was:', text);
                throw new Error('Server returned invalid JSON. Check browser console for details.');
              }
            });
          })
          .then(data => {
            if (data.success) {
              Swal.fire('Success!', data.message, 'success').then(() => location.reload());
            } else {
              Swal.fire('Error!', data.message || 'Failed to update status', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error!', 'An error occurred: ' + error.message, 'error');
          });
        }
      });
    }
  </script>
</body>
</html>
