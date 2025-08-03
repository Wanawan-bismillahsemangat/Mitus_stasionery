<?php
// Simple PHP Router for Mitus E-Commerce
// Letakkan file ini di root project (Mitus_Web_proyek/routes.php)
// Akses: http://localhost/Mitus_Web_proyek/routes.php?page=login

$page = $_GET['page'] ?? 'index';
$allowed = ['index', 'login', 'register', 'cart', 'dashboard', 'lokasi'];
if (!in_array($page, $allowed)) {
    $page = 'index';
}
$file = __DIR__ . "/frontend/{$page}.html";
if (file_exists($file)) {
    readfile($file);
} else {
    http_response_code(404);
    echo '404 Not Found';
}
