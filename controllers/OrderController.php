<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';

class OrderController {
    private $orderModel;
    private $cartModel;
    private $productModel;
    private $userId;

    public function __construct($mysqli) {
        $this->orderModel = new Order($mysqli);
        $this->cartModel = new Cart($mysqli);
        $this->productModel = new Product($mysqli);
        $this->userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
    }

    // Proses checkout dan simpan pesanan
    public function checkout() {
        $cartItems = [];
        if ($this->userId) {
            $cartItems = $this->cartModel->getCartItems($this->userId);
        } else {
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                header('Location: index.php?page=cart');
                exit;
            }
            foreach ($_SESSION['cart'] as $productId => $qty) {
                $product = $this->productModel->getProductById($productId);
                if ($product) {
                    $product['qty'] = $qty;
                    $cartItems[] = $product;
                }
            }
        }
        if (empty($cartItems)) {
            header('Location: index.php?page=cart');
            exit;
        }
        // Validasi stok sebelum checkout
        foreach ($cartItems as $item) {
            $qty = isset($item['quantity']) ? $item['quantity'] : $item['qty'];
            if ($item['stock'] < $qty) {
                header('Location: index.php?page=cart&error=stok');
                exit;
            }
        }
        // Ambil data user dari POST
        $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
        $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
        $no_hp = isset($_POST['no_hp']) ? $_POST['no_hp'] : '';
        // Simpan pesanan ke database (dengan data user)
        $orderId = $this->orderModel->placeOrder($this->userId, $cartItems, $nama, $alamat, $no_hp);
        // Kurangi stok produk
        if ($orderId) {
            foreach ($cartItems as $item) {
                $productId = isset($item['product_id']) ? $item['product_id'] : $item['id'];
                $qty = isset($item['quantity']) ? $item['quantity'] : $item['qty'];
                $this->productModel->decreaseStock($productId, $qty);
            }
        }
        // Bersihkan keranjang
        if ($this->userId) {
            $this->cartModel->clearCart($this->userId);
        } else {
            unset($_SESSION['cart']);
        }
        // Redirect ke halaman konfirmasi
        header('Location: index.php?page=order_confirm&id=' . $orderId);
        exit;
    }

    // Tampilkan halaman konfirmasi pesanan
    public function confirm($orderId) {
        $order = $this->orderModel->getOrderById($orderId);
        if (!$order) {
            header('Location: index.php');
            exit;
        }
        include __DIR__ . '/../views/order_confirm.view.php';
    }
}
