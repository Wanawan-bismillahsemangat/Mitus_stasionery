<?php
header('Content-Type: application/json');
require_once '../../config/db.php';
$stmt = $pdo->query('SELECT id, nama, harga, gambar, deskripsi FROM products');
$produk = $stmt->fetchAll();
echo json_encode($produk);

