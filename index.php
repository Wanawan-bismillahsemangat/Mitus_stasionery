<?php
// index.php - Home & router utama
session_start();
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/controllers/ProductController.php';

// Routing & proteksi halaman
$page = $_GET['page'] ?? 'home';
$protectedPages = ['cart', 'checkout', 'orders'];
if (in_array($page, $protectedPages) && empty($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

// Controller
$mysqli = new mysqli('localhost', 'root', '', 'mitus_stationery');
$productController = new ProductController($mysqli);

if ($page === 'home') {
    $products = $productController->getAllProducts(); // FIX: akses method langsung dari controller
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/home.php';
    include __DIR__ . '/views/footer.php';
    exit;
}

// Routing ke view/controller lain
switch ($page) {
    case 'checkout_proses':
        require_once __DIR__ . '/controllers/OrderController.php';
        $orderController = new OrderController($mysqli);
        $orderController->checkout();
        break;
    case 'product_detail':
        if (!isset($_GET['id'])) { header('Location: index.php'); exit; }
        $productController->detail($_GET['id']);
        break;
    case 'cart':
        require_once __DIR__ . '/controllers/CartController.php';
        $cartController = new CartController($mysqli);
        $cartController->index();
        break;
    case 'cart_add':
        require_once __DIR__ . '/controllers/CartController.php';
        $cartController = new CartController($mysqli);
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
        $cartController->add($_GET['id'], $qty);
        break;
    case 'cart_remove':
        require_once __DIR__ . '/controllers/CartController.php';
        $cartController = new CartController($mysqli);
        $id = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : null);
        if ($id) {
            $cartController->remove($id);
        } else {
            header('Location: index.php?page=cart');
        }
        break;
    case 'cart_update':
        require_once __DIR__ . '/controllers/CartController.php';
        require_once __DIR__ . '/models/Product.php';
        $cartController = new CartController($mysqli);
        $reflection = new ReflectionClass($cartController);
        $userIdProp = $reflection->getProperty('userId');
        $userIdProp->setAccessible(true);
        $userId = $userIdProp->getValue($cartController);
        $cartModelProp = $reflection->getProperty('cartModel');
        $cartModelProp->setAccessible(true);
        $cartModel = $cartModelProp->getValue($cartController);
        $productModel = new Product($mysqli);
        if (isset($_POST['qty']) && is_array($_POST['qty'])) {
            $stokDisesuaikan = false;
            foreach ($_POST['qty'] as $productId => $qty) {
                $qty = max(1, (int)$qty);
                $product = $productModel->getProductById($productId);
                if (!$product) {
                    continue;
                }
                if ($qty > $product['stock']) {
                    $qty = (int)$product['stock'];
                    $stokDisesuaikan = true;
                }
                if ($userId) {
                    $cartModel->setQty($userId, $productId, $qty);
                } else {
                    $_SESSION['cart'][$productId] = $qty;
                    session_write_close();
                }
            }
            if ($stokDisesuaikan) {
                $redirect = 'index.php?page=cart&notif=stok_disesuaikan';
            } else if (isset($_POST['checkout']) && $_POST['checkout'] == '1') {
                session_write_close();
                // Tampilkan notifikasi qty updated sebelum checkout
                $redirect = 'index.php?page=cart&notif=qty_updated_checkout&checkout=1';
            } else {
                $redirect = 'index.php?page=cart&notif=qty_updated';
            }
            header('Location: ' . $redirect);
            exit;
        }
        if (isset($_POST['checkout']) && $_POST['checkout'] == '1') {
            header('Location: index.php?page=checkout');
            exit;
        }
        header('Location: index.php?page=cart');
        exit;
    case 'checkout':
        // Ambil data order dari session/cart
        require_once __DIR__ . '/controllers/CartController.php';
        $cartController = new CartController($mysqli);
        $reflection = new ReflectionClass($cartController);
        $cartModelProp = $reflection->getProperty('cartModel');
        $cartModelProp->setAccessible(true);
        $cartModel = $cartModelProp->getValue($cartController);
        $order_id = 'ORDER-' . time();
        $gross_amount = isset($_SESSION['checkout_total']) ? $_SESSION['checkout_total'] : 100000;
        $customer_name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Nama User';
        $customer_email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : 'user@email.com';
        $customer_phone = isset($_SESSION['user']['phone']) ? $_SESSION['user']['phone'] : '08123456789';
        $item_details = [];
        if (isset($_SESSION['user']['id'])) {
            $cartItems = $cartModel->getCartItems($_SESSION['user']['id']);
            foreach ($cartItems as $item) {
                $item_details[] = [
                    'id' => $item['product_id'],
                    'price' => (int)$item['price'],
                    'quantity' => (int)$item['quantity'],
                    'name' => $item['name'],
                ];
            }
        } else if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            require_once __DIR__ . '/models/Product.php';
            $productModel = new Product($mysqli);
            foreach ($_SESSION['cart'] as $productId => $qty) {
                $product = $productModel->getProductById($productId);
                if ($product) {
                    $item_details[] = [
                        'id' => $product['id'],
                        'price' => (int)$product['price'],
                        'quantity' => (int)$qty,
                        'name' => $product['name'],
                    ];
                }
            }
        }
        // Tampilkan tampilan checkout baru
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/checkout.view.php';
        include __DIR__ . '/views/footer.php';
        exit;
    case 'order_confirm':
        require_once __DIR__ . '/controllers/OrderController.php';
        $orderController = new OrderController($mysqli);
        $orderController->confirm($_GET['id']);
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/controllers/AuthController.php';
            exit;
        }
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/register.php';
        include __DIR__ . '/views/footer.php';
        break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/controllers/AuthController.php';
            exit;
        }
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/login.php';
        include __DIR__ . '/views/footer.php';
        break;
    case 'admin_produk':
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/admin_produk.php';
        include __DIR__ . '/views/footer.php';
        break;
    case 'admin_pesanan':
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/admin_pesanan.php';
        include __DIR__ . '/views/footer.php';
        break;
    case 'admin_statistik':
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/admin_statistik.php';
        include __DIR__ . '/views/footer.php';
        break;
    case 'admin_dashboard':
        include __DIR__ . '/admin/dashboard.php';
        break;
    case 'orders':
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/orders.php';
        include __DIR__ . '/views/footer.php';
        break;
    case 'cart_count':
        header('Content-Type: application/json');
        $count = 0;
        if (isset($_SESSION['user']['id'])) {
            require_once __DIR__ . '/models/Cart.php';
            $cartModel = new Cart($mysqli);
            $items = $cartModel->getCartItems($_SESSION['user']['id']);
            foreach ($items as $item) {
                $qty = isset($item['quantity']) ? $item['quantity'] : (isset($item['qty']) ? $item['qty'] : 1);
                $count += (int)$qty;
            }
        } else if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $qty) {
                $count += (int)$qty;
            }
        }
        echo json_encode(['count' => $count]);
        exit;
    default:
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/404.php';
        include __DIR__ . '/views/footer.php';
        break;
}
