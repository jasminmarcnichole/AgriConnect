<?php
session_start();
require 'config.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    $_SESSION['error'] = 'Invalid email or password. Please try again.';
    header('Location: index.php');
    exit;
}

// Check if verification_status column exists
if (!isset($user['verification_status'])) {
    // Column doesn't exist, allow login (backward compatibility)
    $_SESSION['user'] = [
        'id' => $user['id'],
        'full_name' => $user['full_name'],
        'email' => $user['email'],
        'role' => $user['role']
    ];
    
    if ($user['role'] === 'admin') {
        $_SESSION['success'] = 'Welcome back, Admin!';
        header('Location: admin_dashboard.php');
    } else {
        $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($user['full_name']) . '! 🎉';
        header('Location: dashboard.php');
    }
    exit;
}

// Check if seller account is pending verification
if ($user['role'] === 'seller' && $user['verification_status'] === 'pending') {
    $_SESSION['error'] = 'Your seller account is pending admin verification. Please wait for approval.';
    header('Location: index.php');
    exit;
}

if ($user['role'] === 'seller' && $user['verification_status'] === 'rejected') {
    $_SESSION['error'] = 'Your seller account has been rejected. Please contact support.';
    header('Location: index.php');
    exit;
}

$_SESSION['user'] = [
    'id' => $user['id'],
    'full_name' => $user['full_name'],
    'email' => $user['email'],
    'role' => $user['role']
];

// Redirect admin to admin dashboard
if ($user['role'] === 'admin') {
    $_SESSION['success'] = 'Welcome back, Admin!';
    header('Location: admin_dashboard.php');
    exit;
}

$_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($user['full_name']) . '! 🎉';
header('Location: dashboard.php');
exit;
