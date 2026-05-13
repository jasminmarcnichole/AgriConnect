<?php
session_start();

function requireLogin() {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?show=login');
        exit;
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['user']['role'] !== $role) {
        http_response_code(403);
        exit('Forbidden: insufficient permissions.');
    }
}
