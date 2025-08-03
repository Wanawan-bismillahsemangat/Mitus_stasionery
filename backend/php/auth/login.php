<?php
session_start();
header('Content-Type: application/json');
require_once '../../config/db.php';
$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Email dan password wajib diisi']);
    exit;
}
$stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Email atau password salah']);
}
