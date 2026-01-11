<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController
{
    private PDO $db;
    private CategoryModel $categoryModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // BẢO MẬT: Chỉ Admin mới được vào Controller này
        if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        $database = new Database();
        $this->db = $database->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // Trang danh sách (index)
    public function index(): void
    {
        $categories = $this->categoryModel->getAll();
        include __DIR__ . '/../views/category/index.php';
    }

    // Trang thêm mới (Giao diện)
    public function add(): void
    {
        include __DIR__ . '/../views/category/add.php';
    }

    // Xử lý lưu danh mục mới (Logic)
    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = "Tên danh mục không được để trống!";
            header('Location: ' . BASE_URL . 'category/add');
            exit;
        }

        if ($this->categoryModel->create($name, $description)) {
            $_SESSION['success'] = "Thêm danh mục thành công!";
            header('Location: ' . BASE_URL . 'category/index');
        } else {
            $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại.";
            header('Location: ' . BASE_URL . 'category/add');
        }
    }

    // --- SỬA LỖI TẠI ĐÂY: Bỏ 'int' ở tham số $id ---
    // Trang chỉnh sửa (Giao diện)
    public function edit($id): void
    {
        // Ép kiểu sang số nguyên để an toàn
        $id = (int)$id;

        $category = $this->categoryModel->getById($id);
        if (!$category) {
            $_SESSION['error'] = "Không tìm thấy danh mục!";
            header('Location: ' . BASE_URL . 'category/index');
            exit;
        }
        include __DIR__ . '/../views/category/edit.php';
    }

    // Xử lý cập nhật (Logic)
    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = "Tên danh mục không được để trống!";
            header('Location: ' . BASE_URL . 'category/edit/' . $id);
            exit;
        }

        if ($this->categoryModel->update($id, $name, $description)) {
            $_SESSION['success'] = "Cập nhật thành công!";
            header('Location: ' . BASE_URL . 'category/index');
        } else {
            $_SESSION['error'] = "Lỗi khi cập nhật.";
            header('Location: ' . BASE_URL . 'category/edit/' . $id);
        }
    }

    // --- SỬA LỖI TẠI ĐÂY: Bỏ 'int' ở tham số $id ---
    // Xóa danh mục
    public function delete($id): void
    {
        $id = (int)$id; // Ép kiểu

        // Bạn có thể kiểm tra thêm: Nếu danh mục đang có sản phẩm thì không cho xóa
        if ($this->categoryModel->delete($id)) {
            $_SESSION['success'] = "Xóa danh mục thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi xóa.";
        }
        header('Location: ' . BASE_URL . 'category/index');
    }
}