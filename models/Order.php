<?php
class Order {
    private $conn;
    public function __construct($mysqli) {
        $this->conn = $mysqli;
    }

    public function placeOrder($user_id, $cart_items) {
        $total = 0;
        foreach ($cart_items as $item) {
            // Jika array dari tabel cart
            if (isset($item['price']) && isset($item['quantity'])) {
                $total += $item['price'] * $item['quantity'];
            } else if (isset($item['price']) && isset($item['qty'])) { // dari session
                $total += $item['price'] * $item['qty'];
            }
        }
        $this->conn->begin_transaction();
        try {
            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, order_date, status, total) VALUES (?, NOW(), 'pending', ?)");
            $stmt->bind_param('id', $user_id, $total);
            $stmt->execute();
            $order_id = $this->conn->insert_id;
            $itemStmt = $this->conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cart_items as $item) {
                $product_id = isset($item['product_id']) ? $item['product_id'] : $item['id'];
                $quantity = isset($item['quantity']) ? $item['quantity'] : $item['qty'];
                $price = $item['price'];
                $itemStmt->bind_param('iiid', $order_id, $product_id, $quantity, $price);
                $itemStmt->execute();
            }
            $this->conn->commit();
            return $order_id;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function getOrdersByUser($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllOrders() {
        $result = $this->conn->query("SELECT * FROM orders ORDER BY order_date DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Tambahkan fungsi getOrderById
    public function getOrderById($order_id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        if ($order) {
            // Ambil juga item pesanan
            $itemStmt = $this->conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $itemStmt->bind_param('i', $order_id);
            $itemStmt->execute();
            $itemsResult = $itemStmt->get_result();
            $order['items'] = $itemsResult->fetch_all(MYSQLI_ASSOC);
        }
        return $order;
    }

    public function countOrders() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM orders");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function getTotalSales() {
        $result = $this->conn->query("SELECT SUM(total) as total_sales FROM orders WHERE status = 'completed' OR status = 'selesai'");
        $row = $result->fetch_assoc();
        return $row['total_sales'] ?? 0;
    }
}
