<?php
session_start();
require 'config.php';

$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = 'buyer'; // Only buyers can register through this form

if (!$fullName || !$email || !$password) {
    $_SESSION['error'] = 'Please fill in all fields correctly.';
    header('Location: index.php');
    exit;
}

// Check if email already exists
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $_SESSION['error'] = 'This email is already registered. Please login instead.';
    header('Location: index.php');
    exit;
}

$verificationStatus = 'approved'; // Buyers are auto-approved

$stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, role, verification_status) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([$fullName, $email, password_hash($password, PASSWORD_DEFAULT), $role, $verificationStatus]);

$_SESSION['success'] = 'Account created successfully! Welcome to Agri-Connect, ' . htmlspecialchars($fullName) . '! 🎉';
header('Location: index.php');
exit;
