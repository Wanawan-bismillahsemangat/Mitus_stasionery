<?php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';

class CartController {
    private $cartModel;
    private $productModel;
    private $userId;

    public function __construct($mysqli) {
        $this->cartModel = new Cart($mysqli);
        $this->productModel = new Product($mysqli);
        $this->userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
    }

    // Tambahkan produk ke keranjang
    public function add($productId, $qty = 1) {
        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
            $ref .= (strpos($ref, '?') !== false ? '&' : '?') . 'error=stok';
            header('Location: ' . $ref);
            exit;
        }
        $stok = (int)$product['stock'];
        if ($stok <= 0) {
            $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
            $ref .= (strpos($ref, '?') !== false ? '&' : '?') . 'error=stok';
            header('Location: ' . $ref);
            exit;
        }
        $qty = max(1, (int)$qty);
        if ($this->userId) {
            $items = $this->cartModel->getCartItems($this->userId);
            $currentQty = 0;
            foreach ($items as $item) {
                if ($item['product_id'] == $productId) {
                    $currentQty = (int)$item['quantity'];
                    break;
                }
            }
            if ($currentQty + $qty > $stok) {
                $qtyToAdd = max(0, $stok - $currentQty);
                if ($qtyToAdd > 0) {
                    $this->cartModel->addToCart($this->userId, $productId, $qtyToAdd);
                    $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
                    $ref .= (strpos($ref, '?') !== false ? '&' : '?') . 'notif=stok_disesuaikan';
                    header('Location: ' . $ref);
                    exit;
                } else {
                    $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
                    $ref .= (strpos($ref, '?') !== false ? '&' : '?') . 'error=stok';
                    header('Location: ' . $ref);
                    exit;
                }
            }
            $this->cartModel->addToCart($this->userId, $productId, $qty);
        } else {
            $currentQty = isset($_SESSION['cart'][$productId]) ? (int)$_SESSION['cart'][$productId] : 0;
            if ($currentQty + $qty > $stok) {
                $qtyToAdd = max(0, $stok - $currentQty);
                if ($qtyToAdd > 0) {
                    $_SESSION['cart'][$productId] += $qtyToAdd;
                    $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
                    $ref .= (strpos($ref, '?') !== false ? '&' : '?') . 'notif=stok_disesuaikan';
                    header('Location: ' . $ref);
                    exit;
                } else {
                    $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
                    $ref .= (strpos($ref, '?') !== false ? '&' : '?') . 'error=stok';
                    header('Location: ' . $ref);
                    exit;
                }
            }
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] += $qty;
            } else {
                $_SESSION['cart'][$productId] = $qty;
            }
        }
        // Cek jika request dari AJAX/fetch, jangan redirect
        $isAjax = (
            (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
            (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
        );
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang']);
            exit;
        }
        header('Location: index.php?page=cart');
        exit;
    }

    // Tampilkan isi keranjang
    public function index() {
        $cartItems = [];
        if ($this->userId) {
            $cartItems = $this->cartModel->getCartItems($this->userId);
        } else {
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                $cartItems = [];
            } else {
                foreach ($_SESSION['cart'] as $productId => $qty) {
                    $product = $this->productModel->getProductById($productId);
                    if ($product) {
                        $product['qty'] = $qty;
                        $cartItems[] = $product;
                    }
                }
            }
        }
        include __DIR__ . '/../views/cart.php';
    }

    // Hapus item dari keranjang
    public function remove($productId) {
        if ($this->userId) {
            $this->cartModel->removeItem($this->userId, $productId);
        } else {
            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
            }
        }
        header('Location: index.php?page=cart&notif=item_deleted');
        exit;
    }
}
