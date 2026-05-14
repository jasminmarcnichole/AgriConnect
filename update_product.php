<?php
require 'auth.php';
requireRole('seller');
require 'config.php';

header('Content-Type: application/json');

$id = (int)($_POST['id'] ?? 0);
$userId = $_SESSION['user']['id'];

$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ? AND seller_id = ?');
$stmt->execute([$id, $userId]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

$image = $product['image'];
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
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

echo json_encode(['success' => true, 'message' => 'Product updated successfully!']);
