<?php
declare(strict_types=1);

// 1. CẤU HÌNH HIỂN THỊ LỖI (Bật lên để dễ sửa lỗi, tắt khi chạy thật)
ini_set('display_errors', '1');
error_reporting(E_ALL);

// 2. KHỞI TẠO SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. THIẾT LẬP BASE_URL TỰ ĐỘNG
// Giúp web chạy đúng dù ở thư mục gốc hay thư mục con (VD: localhost/shophoa)
$path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($path, '/') . '/');

// 4. XỬ LÝ URL (ROUTING)
// Lấy tham số url từ .htaccess truyền vào
$route = $_GET['url'] ?? '';
$route = trim($route, '/');

// Phân tách URL thành mảng: [controller, action, param1, param2...]
if ($route === '') {
    // Mặc định vào Trang chủ
    $controller = 'home';
    $action = 'index';
    $params = [];
} else {
    $parts = explode('/', $route);
    $controller = $parts[0] ?? 'home';
    $action = $parts[1] ?? 'index';
    $params = array_slice($parts, 2); // Các phần còn lại là tham số (VD: id, token)
}

// 5. CHUẨN HÓA TÊN FILE & CLASS
// Ví dụ: url là "product" -> Tên file "ProductController.php", Class "ProductController"
$controllerName = ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $controller)) . 'Controller';
$action = preg_replace('/[^a-zA-Z0-9_]/', '', $action);

// Đường dẫn tới file Controller (Sửa lại đường dẫn này nếu cấu trúc thư mục khác)
// Lưu ý: Cấu trúc của bạn đang là thư mục "NguyenDucDuy" chứa code
$controllerFile = __DIR__ . '/NguyenDucDuy/controllers/' . $controllerName . '.php';

// 6. KIỂM TRA & GỌI CONTROLLER
if (!file_exists($controllerFile)) {
    // Nếu không thấy file controller -> Báo lỗi 404 hoặc về trang chủ
    http_response_code(404);
    die("<div style='text-align:center; padding:50px; font-family:sans-serif;'>
            <h1>404 - Not Found</h1>
            <p>Không tìm thấy Controller: <strong>$controllerName</strong></p>
            <p>Đường dẫn file: $controllerFile</p>
            <a href='".BASE_URL."'>Quay về trang chủ</a>
         </div>");
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    die("Lỗi: Class <strong>$controllerName</strong> không tồn tại trong file $controllerName.php");
}

// Khởi tạo đối tượng Controller
$controllerObj = new $controllerName();

if (!method_exists($controllerObj, $action)) {
    http_response_code(404);
    die("<div style='text-align:center; padding:50px; font-family:sans-serif;'>
            <h1>404 - Action Not Found</h1>
            <p>Không tìm thấy hành động <strong>$action</strong> trong <strong>$controllerName</strong></p>
         </div>");
}

// 7. GỌI HÀM VÀ TRUYỀN THAM SỐ
// Đây là chỗ tự động truyền token vào hàm reset($token)
call_user_func_array([$controllerObj, $action], $params);