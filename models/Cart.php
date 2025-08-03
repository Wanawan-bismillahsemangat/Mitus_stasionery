<?php
class Cart {
    private $conn;
    public function __construct($mysqli) {
        $this->conn = $mysqli;
    }

    public function addToCart($user_id, $product_id, $quantity) {
        // Cek produk valid
        $cekProduk = $this->conn->prepare("SELECT id FROM products WHERE id = ?");
        $cekProduk->bind_param('i', $product_id);
        $cekProduk->execute();
        $cekProduk->store_result();
        if ($cekProduk->num_rows === 0) {
            // Produk tidak ada, jangan insert ke cart
            return false;
        }
        // Cek jika sudah ada, update qty, jika belum insert
        $stmt = $this->conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param('ii', $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $newQty = $row['quantity'] + $quantity;
            $update = $this->conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $update->bind_param('ii', $newQty, $row['id']);
            return $update->execute();
        } else {
            $insert = $this->conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $insert->bind_param('iii', $user_id, $product_id, $quantity);
            return $insert->execute();
        }
    }

    public function getCartItems($user_id) {
        $stmt = $this->conn->prepare("SELECT c.*, p.name, p.price, p.image, p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function removeItem($user_id, $product_id) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param('ii', $user_id, $product_id);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            echo '<div style="color:red;font-weight:bold">DEBUG: Tidak ada baris yang dihapus. user_id=' . $user_id . ', product_id=' . $product_id . '</div>';
        }
        if ($stmt->error) {
            echo '<div style="color:red;font-weight:bold">ERROR DELETE: ' . $stmt->error . '</div>';
        }
        return $stmt->affected_rows > 0;
    }

    public function clearCart($user_id) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param('i', $user_id);
        return $stmt->execute();
    }

    // Set qty produk di cart user login
    public function setQty($user_id, $product_id, $qty) {
        // Jika qty <= 0, hapus item
        if ($qty <= 0) {
            return $this->removeItem($user_id, $product_id);
        }
        // Cek apakah sudah ada
        $stmt = $this->conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param('ii', $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $update = $this->conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $update->bind_param('ii', $qty, $row['id']);
            $success = $update->execute();
            if (!$success) {
                echo '<div style="color:red;font-weight:bold">ERROR UPDATE QTY: ' . $update->error . '</div>';
            }
            return $success;
        } else {
            $insert = $this->conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $insert->bind_param('iii', $user_id, $product_id, $qty);
            $success = $insert->execute();
            if (!$success) {
                echo '<div style="color:red;font-weight:bold">ERROR INSERT QTY: ' . $insert->error . '</div>';
            }
            return $success;
        }
    }
}
