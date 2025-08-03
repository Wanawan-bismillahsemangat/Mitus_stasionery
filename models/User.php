<?php
class User {
    private $conn;
    public function __construct($mysqli) {
        $this->conn = $mysqli;
    }

    public function register($name, $email, $password) {
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param('sss', $name, $email, $hash);
        return $stmt->execute();
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT id, name, email, role FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function countUsers() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM users");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
