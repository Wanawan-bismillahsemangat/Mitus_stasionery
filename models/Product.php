<?php
class Product {
    private $conn;
    public function __construct($mysqli) {
        $this->conn = $mysqli;
    }

    public function getAllProducts() {
        $result = $this->conn->query("SELECT * FROM products");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function searchProducts($keyword) {
        $like = "%{$keyword}%";
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE name LIKE ? OR category LIKE ? OR description LIKE ?");
        $stmt->bind_param('sss', $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Admin only
    public function addProduct($name, $category, $description, $price, $stock, $image) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, category, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssdis', $name, $category, $description, $price, $stock, $image);
        return $stmt->execute();
    }

    public function updateProduct($id, $name, $category, $description, $price, $stock, $image) {
        $stmt = $this->conn->prepare("UPDATE products SET name=?, category=?, description=?, price=?, stock=?, image=? WHERE id=?");
        $stmt->bind_param('sssdisi', $name, $category, $description, $price, $stock, $image, $id);
        return $stmt->execute();
    }

    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function countProducts() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM products");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    // Mengurangi stok produk
    public function decreaseStock($productId, $qty) {
        $stmt = $this->conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
        $stmt->bind_param('iii', $qty, $productId, $qty);
        return $stmt->execute();
    }
}
