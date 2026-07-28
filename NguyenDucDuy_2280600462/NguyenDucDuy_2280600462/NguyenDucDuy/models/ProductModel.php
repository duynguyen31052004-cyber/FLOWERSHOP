<?php
declare(strict_types=1);

class ProductModel 
{
    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // 1. Lấy danh sách sản phẩm (Có tìm kiếm + Phân trang)
    public function getProducts(string $keyword = '', int $limit = 12, int $offset = 0): array 
    {
        // Câu lệnh SQL: Tìm theo tên HOẶC mô tả
        $sql = "SELECT p.*, c.name as category_name 
                FROM product p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.name LIKE :keyword OR p.description LIKE :keyword
                ORDER BY p.id DESC 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        
        // Gán giá trị tham số
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Đếm tổng số sản phẩm tìm thấy (Để tính số trang)
    public function countProducts(string $keyword = ''): int 
    {
        $sql = "SELECT COUNT(*) as total 
                FROM product 
                WHERE name LIKE :keyword OR description LIKE :keyword";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    // 3. Lấy 1 sản phẩm theo ID
    public function getProductById(int $id): ?array 
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM product p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // 4. Thêm sản phẩm mới
    public function addProduct($name, $desc, $price, $categoryId, $image): bool 
    {
        $sql = "INSERT INTO product (name, description, price, category_id, image, created_at) 
                VALUES (:name, :desc, :price, :cat, :img, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':desc' => $desc,
            ':price' => $price,
            ':cat' => $categoryId,
            ':img' => $image
        ]);
    }

    // 5. Cập nhật sản phẩm
    public function updateProduct($id, $name, $desc, $price, $categoryId, $image = null): bool 
    {
        if ($image) {
            $sql = "UPDATE product SET name=:name, description=:desc, price=:price, category_id=:cat, image=:img WHERE id=:id";
            $params = [':name'=>$name, ':desc'=>$desc, ':price'=>$price, ':cat'=>$categoryId, ':img'=>$image, ':id'=>$id];
        } else {
            $sql = "UPDATE product SET name=:name, description=:desc, price=:price, category_id=:cat WHERE id=:id";
            $params = [':name'=>$name, ':desc'=>$desc, ':price'=>$price, ':cat'=>$categoryId, ':id'=>$id];
        }
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // 6. Xóa sản phẩm
    public function deleteProduct(int $id): bool 
    {
        $stmt = $this->conn->prepare("DELETE FROM product WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    // ... (Các hàm cũ)

    // Lấy 4 sản phẩm cùng danh mục (trừ sản phẩm đang xem)
    public function getRelatedProducts(int $categoryId, int $excludeId, int $limit = 4): array 
    {
        $sql = "SELECT * FROM product 
                WHERE category_id = :catId 
                AND id != :excludeId 
                ORDER BY RAND() 
                LIMIT :limit"; // Dùng RAND() để mỗi lần F5 sẽ ra gợi ý khác nhau
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':catId', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':excludeId', $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Trong file models/ProductModel.php
public function searchProducts($keyword) {
    $keyword = "%$keyword%";
    // Tìm theo tên sản phẩm, lấy 5 cái đầu tiên để gợi ý
    $sql = "SELECT id, name, price, image FROM products WHERE name LIKE ? LIMIT 5";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$keyword]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}