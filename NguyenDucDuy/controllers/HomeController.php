<?php
declare(strict_types=1);

// Import các file cần thiết
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ProductModel.php';

class HomeController
{
    private PDO $db;
    private ProductModel $productModel;

    public function __construct()
    {
        // Kết nối Database
        $database = new Database();
        $conn = $database->getConnection();
        if (!$conn) {
            die("Lỗi kết nối cơ sở dữ liệu tại HomeController.");
        }
        $this->db = $conn;
        $this->productModel = new ProductModel($this->db);
    }

    /**
     * Router mặc định sẽ tìm hàm này khi vào trang chủ.
     * Ta gọi lại logic của hàm main() để tránh viết lại code.
     */
    public function index()
    {
        $this->main();
    }

    // Hàm main (đã sửa: hiển thị tất cả sản phẩm)
    public function main()
    {
        // Lấy tất cả sản phẩm (Cách A)
        $products = $this->productModel->getProducts();

        // Gọi giao diện
        include __DIR__ . '/../views/home/main.php';
    }
}
