<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController
{
    private PDO $db;
    private UserModel $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $database = new Database();
        $conn = $database->getConnection();

        if (!$conn instanceof PDO) {
            die('Lỗi kết nối hệ thống dữ liệu.');
        }

        $this->db = $conn;
        $this->userModel = new UserModel($this->db);
    }

    public function login(): void
    {
        if (!empty($_SESSION['auth'])) {
            header('Location: ' . BASE_URL . 'product/list');
            exit();
        }

        $error = $_SESSION['login_error'] ?? '';
        $old = $_SESSION['login_old'] ?? ['email' => ''];
        unset($_SESSION['login_error'], $_SESSION['login_old']);

        include __DIR__ . '/../views/auth/login.php';
    }

    public function doLogin(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        $_SESSION['login_old'] = ['email' => $email];

        if ($email === '' || $password === '') {
            $_SESSION['login_error'] = 'Vui lòng nhập đầy đủ thông tin.';
            header('Location: ' . BASE_URL . 'auth/login');
            exit(); 
        }

        $user = $this->userModel->getUserByEmail($email);

        if (!$user || !password_verify($password, (string)$user['password_hash'])) {
            $_SESSION['login_error'] = 'Tài khoản hoặc mật khẩu không chính xác.';
            header('Location: ' . BASE_URL . 'auth/login');
            exit(); 
        }

        $_SESSION['auth'] = [
            'id' => (int)$user['id'],
            'name' => (string)$user['name'],
            'email' => (string)$user['email'],
            'role' => (string)$user['role'],
        ];

        unset($_SESSION['login_old']);
        header('Location: ' . BASE_URL . 'home/main');
        exit();
    }

    public function register(): void
    {
        if (!empty($_SESSION['auth'])) {
            header('Location: ' . BASE_URL . 'product/list');
            exit();
        }

        $error = $_SESSION['register_error'] ?? '';
        $old = $_SESSION['register_old'] ?? ['name' => '', 'email' => '', 'username' => ''];
        unset($_SESSION['register_error'], $_SESSION['register_old']);

        include __DIR__ . '/../views/auth/register.php';
    }

    public function doRegister(): void
    {
        // ... (Phần nhận dữ liệu giữ nguyên) ...
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $confirm = (string)($_POST['confirm_password'] ?? '');
        // ... (Phần validate giữ nguyên) ...

        // 1. SỬA LỖI TÊN HÀM TÌM EMAIL
        if ($this->userModel->getUserByEmail($email)) {
            $_SESSION['register_error'] = 'Email này đã tồn tại.';
            header('Location: ' . BASE_URL . 'auth/register');
            exit();
        }

        // Tạo tài khoản
        // 2. SỬA LỖI TÊN HÀM ĐĂNG KÝ (createUser -> register)
        // Lưu ý: Hàm register trong Model của bạn đang nhận 5 tham số: ($name, $email, $password, $phone, $address)
        // Bạn cần đảm bảo truyền đủ hoặc sửa Model nếu muốn. Tạm thời mình truyền chuỗi rỗng cho phone/address để không lỗi.
        $ok = $this->userModel->register($name, $email, $password, '', ''); 

        if (!$ok) {
            $_SESSION['register_error'] = 'Lỗi hệ thống, vui lòng thử lại.';
            header('Location: ' . BASE_URL . 'auth/register');
            exit();
        }

        unset($_SESSION['register_old']);
        $_SESSION['login_success'] = 'Đăng ký thành công! Hãy đăng nhập.';
        header('Location: ' . BASE_URL . 'auth/login');
        exit();
    }

    public function logout(): void
    {
        unset($_SESSION['auth'], $_SESSION['cart']);
        header('Location: ' . BASE_URL . 'auth/login');
        exit();
    }
    public function processLogin() {
    validateCsrf(); // Chống tấn công giả mạo đăng nhập
    
    $email = $_POST['email'];
    // ... xử lý đăng nhập ...
}
// Thêm vào class AuthController

// Hiển thị form nhập email
public function forgot() {
    include __DIR__ . '/../views/auth/forgot.php';
}

// Xử lý tạo link reset (DEMO: Hiện link luôn không cần gửi mail)
public function sendResetLink() {
    $email = trim($_POST['email'] ?? '');
    $user = $this->userModel->getUserByEmail($email);

    if (!$user) {
        $error = "Email này chưa đăng ký!";
        include __DIR__ . '/../views/auth/forgot.php';
        return;
    }

    // Tạo token ngẫu nhiên
    $token = bin2hex(random_bytes(32));
    $this->userModel->setResetToken($email, $token);

    // Tạo link reset
    $resetLink = BASE_URL . "auth/reset/" . $token;

    // --- CHẾ ĐỘ DEMO ĐỒ ÁN ---
    // Thay vì gửi mail, ta truyền link này sang View để hiện ra cho Giáo viên bấm luôn
    $successMessage = "Hệ thống đã gửi link xác nhận!";
    $demoLink = $resetLink; // Biến này để hiện link giả lập
    
    include __DIR__ . '/../views/auth/forgot.php';
}

// Hiển thị form nhập mật khẩu mới
// Thêm "= null" để cho phép token bị trống mà không báo lỗi Fatal Error
public function reset($token = null) {
        // 1. Kiểm tra nếu không có token trên đường dẫn
        if ($token === null) {
            die("Lỗi: Đường dẫn không hợp lệ hoặc thiếu mã xác thực (Token).");
        }

        // 2. Kiểm tra token có hợp lệ trong Database không
        $user = $this->userModel->getUserByResetToken($token);

        if (!$user) {
            // Nếu không tìm thấy user hoặc token hết hạn
            die("Lỗi: Link xác thực không hợp lệ hoặc đã hết hạn! Vui lòng thực hiện lại.");
        }

        // 3. QUAN TRỌNG: Hiển thị giao diện đặt lại mật khẩu
        // Đây là dòng code bị thiếu khiến màn hình trắng
        include __DIR__ . '/../views/auth/reset.php';
    }
// Xử lý lưu mật khẩu mới
public function updateNewPassword() {
    $token = $_POST['token'] ?? '';
    $pass = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($pass !== $confirm) {
        $error = "Mật khẩu xác nhận không khớp!";
        $user = $this->userModel->getUserByResetToken($token); // Lấy lại user để load lại form
        include __DIR__ . '/../views/auth/reset.php';
        return;
    }

    $user = $this->userModel->getUserByResetToken($token);
    if ($user) {
        $this->userModel->updatePasswordByToken($user['id'], $pass);
        header('Location: ' . BASE_URL . 'auth/login?msg=reset_success');
        exit;
    } else {
        die("Lỗi: Token hết hạn.");
    }
}
}