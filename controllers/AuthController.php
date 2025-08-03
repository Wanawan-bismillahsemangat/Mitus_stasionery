<?php
// Hanya jalankan session_start jika belum ada session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Gunakan koneksi MySQLi, bukan PDO
$mysqli = new mysqli('localhost', 'root', '', 'mitus_stationery');
require_once __DIR__ . '/../models/User.php';

$userModel = new User($mysqli);

// Register
if (isset($_POST['register'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $errors = [];
    if (!$name || !$email || !$password || !$confirm) {
        $errors[] = 'Semua field wajib diisi';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid';
    }
    if ($password !== $confirm) {
        $errors[] = 'Konfirmasi password tidak sama';
    }
    if ($userModel->getUserByEmail($email)) {
        $errors[] = 'Email sudah terdaftar';
    }
    if (empty($errors)) {
        $userModel->register($name, $email, $password);
         $user = $userModel->getUserByEmail($email);
        $_SESSION['user'] = $user;
         header('Location: /Mitus_Web_proyek/index.php');
        exit;
    } else {
        $_SESSION['register_error'] = implode('<br>', $errors);
        header('Location: /index.php?page=register');
        exit;
    }
}

// Login
if (isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $errors = [];
    if (!$email || !$password) {
        $errors[] = 'Email dan password wajib diisi';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid';
    }
    $user = $userModel->login($email, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        // Redirect sesuai role
        if ($user['role'] === 'admin') {
            header('Location: /Mitus_Web_proyek/admin/dashboard.php');
        } else {
            header('Location: /Mitus_Web_proyek/index.php');
        }
        exit;
    } else {
        $errors[] = 'Email atau password salah';
        $_SESSION['login_error'] = implode('<br>', $errors);
        header('Location: /index.php?page=login');
        exit;
    }
}
