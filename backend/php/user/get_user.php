<?php
session_start();
header('Content-Type: application/json');
require_once '../../config/db.php';
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
$stmt = $pdo->prepare('SELECT id, nama, email FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
echo json_encode(['success' => true, 'user' => $user]);
