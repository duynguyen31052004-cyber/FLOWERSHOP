<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CommentModel.php';

class CommentController {
    private $db;
    private $commentModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->commentModel = new CommentModel($this->db);
    }

    // Hàm bảo mật: Chỉ Admin mới được vào
    private function requireAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Kiểm tra quyền Admin
        if (!isset($_SESSION['auth']) || trim($_SESSION['auth']['role']) !== 'admin') {
            // Nếu không phải admin, chuyển hướng về trang chủ hoặc báo lỗi
            header('Location: ' . BASE_URL); 
            exit;
        }
    }

    // 1. Hiển thị danh sách bình luận (Trang Quản Trị)
    public function index() {
        $this->requireAdmin();
        $comments = $this->commentModel->getAllComments();
        
        // Load View Admin
        // Đảm bảo bạn đã tạo file này trong thư mục views/admin/comments/
        include __DIR__ . '/../views/admin/comments/index.php';
    }

    // 2. Xóa bình luận
    public function delete($id) {
        $this->requireAdmin();
        $this->commentModel->deleteComment($id);
        
        // Xóa xong quay lại trang danh sách
        // Lưu ý: Redirect về 'comment/index' (số ít) để khớp với Controller
        header('Location: ' . BASE_URL . 'comment/index');
        exit;
    }
}