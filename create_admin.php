<?php
// create_admin.php
declare(strict_types=1);

// 👇 SỬA DÒNG NÀY: Thêm '/NguyenDucDuy' vào đường dẫn
require_once __DIR__ . '/NguyenDucDuy/config/database.php';

try {
    // 1. Kết nối Database
    $database = new Database();
    $conn = $database->getConnection();
    
    if (!$conn) {
        die("Lỗi kết nối Database.");
    }

    // 2. Thông tin Admin mẫu
    $name = "Administrator";
    $username = "admin";
    $email = "admin@gmail.com";
    $raw_password = "123456"; // Mật khẩu: 123456
    $role = "admin";

    // 3. Mã hóa mật khẩu chuẩn PHP
    $password_hash = password_hash($raw_password, PASSWORD_DEFAULT);

    // 4. Kiểm tra và Cập nhật/Tạo mới
    $checkSql = "SELECT id FROM users WHERE email = :email";
    $stmt = $conn->prepare($checkSql);
    $stmt->execute([':email' => $email]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($exists) {
        $sql = "UPDATE users 
                SET password_hash = :hash, role = :role, name = :name, username = :username
                WHERE email = :email";
        $msg = "Đã CẬP NHẬT lại mật khẩu cho admin@gmail.com thành công!";
    } else {
        $sql = "INSERT INTO users (name, email, password_hash, role, username, created_at) 
                VALUES (:name, :email, :hash, :role, :username, NOW())";
        $msg = "Đã TẠO MỚI tài khoản Admin thành công!";
    }

    $stmt = $conn->prepare($sql);
    $params = [
        ':name' => $name,
        ':email' => $email,
        ':hash' => $password_hash,
        ':role' => $role,
        ':username' => $username
    ];

    if ($stmt->execute($params)) {
        echo "<h1 style='color:green'>✅ " . $msg . "</h1>";
        echo "<h3>Thông tin đăng nhập:</h3>";
        echo "<ul>";
        echo "<li>Email: <b>$email</b></li>";
        echo "<li>Password: <b>$raw_password</b></li>";
        echo "</ul>";
        echo "<a href='NguyenDucDuy/auth/login'>👉 Bấm vào đây để Đăng nhập ngay</a>";
    } else {
        echo "<h1 style='color:red'>❌ Có lỗi khi chạy SQL</h1>";
        print_r($stmt->errorInfo());
    }

} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>