<div align="center">
  <h1 align="center">🌸 Flower Shop E-Commerce Platform</h1>
  <p align="center">
    <strong>Hệ Thống Website Bán Hoa Tươi Chuyên Nghiệp</strong>
  </p>
  <p align="center">
    <img src="https://img.shields.io/badge/PHP-Custom_MVC-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP MVC" />
    <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
    <img src="https://img.shields.io/badge/Bootstrap-Frontend-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap" />
  </p>
</div>

Một nền tảng thương mại điện tử hoàn chỉnh dành cho cửa hàng bán hoa tươi, được thiết kế và xây dựng hoàn toàn từ đầu (from scratch) dựa trên kiến trúc **Custom MVC (Model-View-Controller)** bằng PHP thuần. Hệ thống đảm bảo tính mở rộng, bảo mật và dễ dàng bảo trì.

---

## 🚀 Công Nghệ Sử Dụng

- **Backend:** PHP (Thuần / Custom MVC Architecture)
- **Frontend:** HTML5, CSS3, Vanilla JavaScript, Bootstrap
- **Cơ sở dữ liệu:** MySQL (Relational Database)
- **Thanh toán:** Tích hợp cổng thanh toán mã QR (QR Payment)

---

## ✨ Tính Năng Nổi Bật

### 🛒 Dành Cho Khách Hàng (Customer User)
- **Xác thực bảo mật:** Đăng nhập, đăng ký, quên mật khẩu (Mã hóa mật khẩu an toàn).
- **Mua sắm:** Xem danh mục hoa, chi tiết sản phẩm, lọc và tìm kiếm hoa.
- **Giỏ hàng (Shopping Cart):** Thêm/sửa/xóa sản phẩm trong giỏ hàng mượt mà.
- **Thanh toán (Checkout):** Quy trình đặt hàng tiện lợi, tích hợp thanh toán qua **mã QR**.
- **Quản lý đơn hàng:** Theo dõi lịch sử đặt hàng và trạng thái đơn hàng (Order History).

### 🛡️ Dành Cho Quản Trị Viên (Admin)
- **Role-based Access Control (RBAC):** Phân quyền truy cập chặt chẽ giữa User bình thường và Admin.
- **Admin Dashboard:** Bảng điều khiển tổng quan cung cấp các báo cáo thống kê (`stats.php`) về doanh thu, số lượng đơn hàng theo thời gian thực.
- **Quản lý Sản phẩm (CRUD):** Thêm, sửa, xóa, ẩn/hiện các mẫu hoa tươi.
- **Quản lý Danh mục (CRUD):** Phân loại hoa theo chủ đề (Hoa sinh nhật, hoa khai trương, hoa cưới...).
- **Xử lý Đơn hàng:** Xem thông tin đặt hàng của khách, cập nhật trạng thái đơn hàng.

---

## 📂 Cấu Trúc Thư Mục (Custom MVC)

Dự án áp dụng chặt chẽ mô hình **MVC** để tách biệt giao diện, dữ liệu và luồng xử lý:

```text
NGUYENDUCDUY_WEBSITESHOPHOATUOI/
├── Controllers/       # Xử lý logic và điều hướng (ProductController, UserController...)
├── Models/            # Tương tác trực tiếp với Database MySQL (Product, User, Order...)
├── Views/             # Giao diện người dùng HTML/PHP
│   ├── Admin/         # Giao diện dành riêng cho quản trị viên
│   ├── Client/        # Giao diện cửa hàng cho khách
│   └── Layouts/       # Header, Footer dùng chung
├── Config/            # Cấu hình kết nối Database (db.php)
├── Public/            # Chứa file tĩnh: CSS, JS, Images
├── index.php          # Entry point (Routing) định tuyến mọi request
└── README.md
```

---

## ⚙️ Hướng Dẫn Cài Đặt Khởi Chạy

### Yêu cầu hệ thống
- XAMPP / WAMP / MAMP (Hỗ trợ PHP 7.x hoặc 8.x)
- MySQL Server

### Các bước cài đặt

**1. Clone mã nguồn về máy**
```bash
git clone https://github.com/DuwwDuyy/FLOWERSHOP.git
```
*Chuyển toàn bộ thư mục dự án vào thư mục `htdocs` (nếu dùng XAMPP) hoặc `www` (nếu dùng WAMP).*

**2. Thiết lập Cơ sở dữ liệu (Database)**
- Mở **phpMyAdmin** (thường ở địa chỉ `http://localhost/phpmyadmin`).
- Tạo một Database mới với tên (ví dụ: `shop_hoa_tuoi`).
- Import file SQL (ví dụ: `database.sql`) có sẵn trong source code vào database vừa tạo để nạp cấu trúc bảng và dữ liệu mẫu.

**3. Cấu hình kết nối Database**
- Mở file cấu hình database (ví dụ: `Config/db.php` hoặc `connection.php`).
- Chỉnh sửa thông tin kết nối cho phù hợp với máy local của bạn:
```php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "shop_hoa_tuoi";
```

**4. Khởi chạy Website**
- Mở trình duyệt và truy cập vào đường dẫn: `http://localhost/NGUYENDUCDUY_WEBSITESHOPHOATUOI/`
- Tận hưởng thành quả! 🎉

---

## 📬 Liên Hệ

- **Tác giả:** Nguyễn Đức Duy
- **Email:** duynguyen31052004@gmail.com
- **GitHub:** [https://github.com/DuwwDuyy](https://github.com/DuwwDuyy)
