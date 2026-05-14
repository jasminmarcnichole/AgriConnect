<?php
require 'auth.php';
requireLogin();
require 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
    
    if (!in_array($file['type'], $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        exit;
    }
    
    if ($file['size'] > 5000000) {
        echo json_encode(['success' => false, 'message' => 'File too large (max 5MB)']);
        exit;
    }
    
    $uploadDir = 'uploads/profiles/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('profile_') . '.' . $ext;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $stmt = $pdo->prepare('UPDATE users SET profile_picture = ? WHERE id = ?');
        $stmt->execute([$filename, $_SESSION['user']['id']]);
        $_SESSION['user']['profile_picture'] = $filename;
        
        echo json_encode(['success' => true, 'image_url' => $filepath]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Upload failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
}
