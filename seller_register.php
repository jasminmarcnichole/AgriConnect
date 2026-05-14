<?php
session_start();
require 'config.php';

$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$phone = trim($_POST['phone'] ?? '');

if (!$fullName || !$email || !$password) {
    $_SESSION['error'] = 'Please fill in all required fields.';
    header('Location: seller_signup.php');
    exit;
}

// Check if email already exists
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $_SESSION['error'] = 'This email is already registered. Please login instead.';
    header('Location: seller_signup.php');
    exit;
}

// Validate ID document upload
if (!isset($_FILES['id_document']) || $_FILES['id_document']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = 'Please upload a valid ID document.';
    header('Location: seller_signup.php');
    exit;
}

$allowed = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
$fileType = $_FILES['id_document']['type'];

if (!in_array($fileType, $allowed)) {
    $_SESSION['error'] = 'Only JPG, PNG, or PDF files are allowed.';
    header('Location: seller_signup.php');
    exit;
}

if ($_FILES['id_document']['size'] > 5242880) { // 5MB
    $_SESSION['error'] = 'ID document must be less than 5MB.';
    header('Location: seller_signup.php');
    exit;
}

$uploadDir = 'uploads/id_documents/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$extension = pathinfo($_FILES['id_document']['name'], PATHINFO_EXTENSION);
$idDocument = uniqid('id_', true) . '.' . $extension;
move_uploaded_file($_FILES['id_document']['tmp_name'], $uploadDir . $idDocument);

// Insert seller with pending status
$stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, role, verification_status, id_document) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->execute([$fullName, $email, password_hash($password, PASSWORD_DEFAULT), 'seller', 'pending', $idDocument]);

$_SESSION['success'] = 'Application submitted successfully! Your account is pending admin approval. You will receive an email notification once verified (typically within 24-48 hours).';
header('Location: seller_signup.php');
exit;
