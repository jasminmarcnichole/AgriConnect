<?php
session_start();
require 'config.php';

$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

if (!$fullName || !$email || !$password || !in_array($role, ['buyer', 'seller'], true)) {
    exit('Invalid signup data.');
}

$stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)');
$stmt->execute([$fullName, $email, password_hash($password, PASSWORD_DEFAULT), $role]);

header('Location: index.php');
