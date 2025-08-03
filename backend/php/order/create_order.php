<?php
session_start();
header('Content-Type: application/json');
require_once '../../config/db.php';
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$items = $data['items'] ?? [];
if (!$items || !is_array($items)) {
    echo json_encode(['success' => false, 'message' => 'Data order tidak valid']);
    exit;
}
$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, created_at) VALUES (?, NOW())');
    $stmt->execute([$_SESSION['user_id']]);
    $order_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, qty) VALUES (?, ?, ?)');
    foreach ($items as $item) {
        $stmt->execute([$order_id, $item['id'], $item['qty']]);
    }
    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan order']);
}
