<?php
require_once __DIR__ . '/../models/Product.php';

class ProductController {
    private $productModel;

    public function __construct($mysqli) {
        $this->productModel = new Product($mysqli);
    }

    // Tampilkan semua produk (homepage/produk)
    public function index() {
        $products = $this->productModel->getAllProducts();
        include __DIR__ . '/../views/products.view.php';
    }

    // Tampilkan detail produk berdasarkan ID
    public function detail($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            // Produk tidak ditemukan, redirect atau tampilkan error
            header('Location: index.php?page=products');
            exit;
        }
        // Perbaiki path view detail produk
        include __DIR__ . '/../views/product-detail.php';
    }

    // Tambahkan fungsi getAllProducts agar bisa dipanggil dari index.php
    public function getAllProducts() {
        return $this->productModel->getAllProducts();
    }
}
