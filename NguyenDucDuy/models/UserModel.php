<?php
declare(strict_types=1);

class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($name, $email, $password, $phone, $address) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $username = explode('@', $email)[0];
        $sql = "INSERT INTO users (name, email, password_hash, username, role) VALUES (?, ?, ?, ?, 'user')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $email, $hashed_password, $username]);
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password_hash'] ?? '')) {
            return $user;
        }
        return false;
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function setResetToken($email, $token) {
        $sql = "UPDATE users SET reset_token = :token, reset_expiry = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':token' => $token, ':email' => $email]);
    }

    public function getUserByResetToken($token) {
        $sql = "SELECT * FROM users WHERE reset_token = :token AND reset_expiry > NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePasswordByToken($userId, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash = :pass, reset_token = NULL, reset_expiry = NULL WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':pass' => $hashed, ':id' => $userId]);
    }

    public function getUsers($keyword = '', $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM users WHERE name LIKE :kw OR email LIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':kw', "%$keyword%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUsers($keyword = '') {
        $sql = "SELECT COUNT(*) as total FROM users WHERE name LIKE ? OR email LIKE ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["%$keyword%", "%$keyword%"]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    // --- ĐÃ SỬA: Đổi $this->conn thành $this->db để khớp với hàm construct ---
    public function getUserById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function getOrdersByUser($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- ĐÃ SỬA: Đổi $this->conn thành $this->db ---
    public function updateProfile(int $id, string $name, string $phone, string $address): bool {
        $sql = "UPDATE users SET name = :name, phone = :phone, address = :address WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':phone' => $phone,
            ':address' => $address
        ]);
    }
}