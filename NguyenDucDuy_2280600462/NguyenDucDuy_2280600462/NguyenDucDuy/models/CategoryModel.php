<?php
declare(strict_types=1);

class CategoryModel
{
    private PDO $conn;
    private string $table_name = "categories";

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // 1. Lấy tất cả danh mục
    public function getAll(): array
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy 1 danh mục theo ID
    public function getById(int $id): ?array
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // 3. Thêm mới danh mục
    public function create(string $name, string $description): bool
    {
        $query = "INSERT INTO " . $this->table_name . " (name, description, created_at) VALUES (:name, :desc, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':desc', $description);
        return $stmt->execute();
    }

    // 4. Cập nhật danh mục
    public function update(int $id, string $name, string $description): bool
    {
        $query = "UPDATE " . $this->table_name . " SET name = :name, description = :desc WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':desc', $description);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // 5. Xóa danh mục
    public function delete(int $id): bool
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}