<?php
// Thiết lập BASE_URL cho dự án trên Localhost
// Bạn hãy thay đổi tên thư mục NguyenDucDuy_2280600462 nếu có khác biệt
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/NguyenDucDuy_2280600462/');
}

class Database {
    private string $host = "localhost";
    private string $db_name = "my_store";
    private string $username = "root";
    private string $password = "root";
    public ?PDO $conn = null;

    public function getConnection(): ?PDO {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8mb4");
        } catch(PDOException $exception) {
            echo "Lỗi kết nối: " . $exception->getMessage();
        }
        return $this->conn;
    }
}