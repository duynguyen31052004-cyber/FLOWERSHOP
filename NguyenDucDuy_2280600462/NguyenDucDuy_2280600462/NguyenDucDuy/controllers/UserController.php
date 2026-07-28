<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private UserModel $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $db = (new Database())->getConnection();
        $this->userModel = new UserModel($db);
    }
    // controllers/UserController.php
public function profile() {
    // 1. Kiểm tra đăng nhập
    if (empty($_SESSION['auth'])) {
        header('Location: ' . BASE_URL . 'auth/login');
        exit();
    }

    $userId = (int)$_SESSION['auth']['id'];

    // 2. XỬ LÝ LƯU THÔNG TIN (Nếu có bấm nút Lưu)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($this->userModel->updateProfile($userId, $name, $phone, $address)) {
            // Cập nhật lại tên trong Session để Header thay đổi ngay
            $_SESSION['auth']['name'] = $name;
            $_SESSION['success'] = "Đã cập nhật thông tin thành công!";
            header('Location: ' . BASE_URL . 'user/profile');
            exit();
        }
    }

    // 3. QUAN TRỌNG: Lấy dữ liệu để hiển thị ra View
    // Biến $user này chính là cái mà View đang báo thiếu
    $user = $this->userModel->getUserById($userId);

    if (!$user) {
        die("Không tìm thấy thông tin tài khoản.");
    }

    // 4. Gọi giao diện
    include __DIR__ . '/../views/user/profile.php';
}

    // ✅ Chỉ ADMIN mới được truy cập
    private function requireAdmin(): void
    {
        if (empty($_SESSION['auth']) || ($_SESSION['auth']['role'] ?? '') !== 'admin') {
            header('Content-Type: text/html; charset=utf-8'); // Đảm bảo hiển thị tiếng Việt lỗi
            http_response_code(403);
            die('❌ Bạn không có quyền truy cập trang này');
        }
    }

    // ================= DANH SÁCH USER (Sửa tên từ index -> list) =================
    public function list(): void
    {
        $this->requireAdmin();

        $keyword = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $users = $this->userModel->getUsers($keyword, $limit, $offset);
        $totalUsers = $this->userModel->countUsers($keyword);
        $totalPages = (int)ceil($totalUsers / $limit);

        // Lưu ý: Đảm bảo file view list.php cập nhật lại các link/form thành user/list nếu cần
        include __DIR__ . '/../views/user/list.php';
    }

    // ✅ Thêm hàm index (Alias) để tránh lỗi nếu có link cũ gọi user/index
    public function index(): void
    {
        $this->list();
    }

    // ================= CHI TIẾT USER =================
    public function detail($id): void
    {
        $this->requireAdmin();
        $id = (int)$id;

        $user = $this->userModel->getUserById($id);

        // ✅ ADMIN thì KHÔNG lấy lịch sử đơn hàng
        if ($user && ($user['role'] ?? '') !== 'admin') {
            $orders = $this->userModel->getOrdersByUser($id);
        } else {
            $orders = [];
        }

        include __DIR__ . '/../views/user/detail.php';
    }
}