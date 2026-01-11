<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hàm helper
if (!function_exists('h')) {
    function h($str): string {
        return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
    }
}

// Xử lý giỏ hàng
$cartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += (int)($item['quantity'] ?? 0);
    }
}

// Xử lý Auth
$auth = $_SESSION['auth'] ?? null;
$isLoggedIn = !empty($auth);
$userName = $auth['name'] ?? 'Khách';
$isAdmin = $isLoggedIn && (($auth['role'] ?? '') === 'admin');
$currentUri = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isAdmin ? 'Quản trị hệ thống' : 'FlowerShop - Cửa hàng hoa' ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { prefix: 'tw-', corePlugins: { preflight: false } }
    </script>

    <style>
        :root {
            --primary: #ff758c;
            --sidebar-w: 260px;
            --header-h: 70px;
        }
        body { font-family: 'Nunito', sans-serif; background: #f8f9fc; }

        /* ==================== 1. CSS RIÊNG CHO ADMIN ==================== */
        <?php if ($isAdmin): ?>
        /* Reset layout cho Admin */
        body { overflow-x: hidden; background-color: #f3f4f6; }
        
        .admin-sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: #fff;
            border-right: 1px solid #e3e6f0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.03);
        }

        .admin-content-wrapper {
            margin-left: var(--sidebar-w); /* Đẩy nội dung sang phải */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - var(--sidebar-w));
        }

        .admin-top-bar {
            height: var(--header-h);
            background: #fff;
            border-bottom: 1px solid #e3e6f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 2rem;
        }

        .admin-main-body {
            padding: 2rem;
            flex: 1;
        }

        .nav-item-admin {
            padding: 12px 20px;
            color: #6c757d;
            font-weight: 700;
            text-decoration: none !important;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }
        .nav-item-admin:hover, .nav-item-admin.active {
            color: var(--primary);
            background: #fff0f3;
            border-left-color: var(--primary);
        }
        <?php else: ?>
        
        /* ==================== 2. CSS RIÊNG CHO USER ==================== */
        .user-header {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky; top: 0; z-index: 999;
            height: var(--header-h);
        }
        .user-nav-link {
            color: #4a5568; font-weight: 700; margin: 0 10px;
            text-decoration: none !important;
        }
        .user-nav-link:hover { color: var(--primary); }
        .main-container { padding-top: 2rem; min-height: 80vh; }
        <?php endif; ?>
        
        /* Avatar chung */
        .avatar-box {
            width: 35px; height: 35px; border-radius: 50%;
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: bold;
        }
    </style>
</head>
<body>

<?php if ($isAdmin): ?>
    <div class="d-flex">
        <nav class="admin-sidebar">
            <div class="d-flex align-items-center justify-content-center" style="height: var(--header-h); border-bottom: 1px solid #f0f0f0;">
                <a href="<?= BASE_URL ?>" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                    <span style="font-size: 24px;">🌸</span>
                    <span class="font-weight-bold h5 mb-0 text-uppercase">Flower Admin</span>
                </a>
            </div>

            <div class="py-4 px-2 tw-flex-1 tw-overflow-y-auto">
                <small class="text-uppercase text-muted font-weight-bold px-3 mb-2 d-block">Quản lý</small>
                
                <a class="nav-item-admin <?= strpos($currentUri, 'stats') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>order/stats">
                    <i class="fas fa-chart-line" style="width:20px"></i> Thống kê
                </a>
                <a class="nav-item-admin <?= strpos($currentUri, 'product') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>product/list">
                    <i class="fas fa-box" style="width:20px"></i> Sản phẩm
                </a>
                <a class="nav-item-admin <?= strpos($currentUri, 'category') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>category/index">
                    <i class="fas fa-folder" style="width:20px"></i> Danh mục
                </a>

                <small class="text-uppercase text-muted font-weight-bold px-3 mb-2 mt-4 d-block">Đơn hàng</small>
                <a class="nav-item-admin <?= strpos($currentUri, 'order/index') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>order/index">
                    <i class="fas fa-shopping-cart" style="width:20px"></i> Đơn hàng
                </a>
                <a class="nav-item-admin <?= strpos($currentUri, 'user') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>user/index">
                    <i class="fas fa-users" style="width:20px"></i> Khách hàng
                </a>
            </div>

            <div class="p-3 border-top bg-light">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-box"><?= strtoupper(substr($userName, 0, 1)) ?></div>
                    <div style="line-height: 1.2;">
                        <div class="font-weight-bold text-dark small"><?= h($userName) ?></div>
                        <a href="<?= BASE_URL ?>auth/logout" class="text-danger small font-weight-bold">Đăng xuất</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="admin-content-wrapper">
            <div class="admin-top-bar">
                <span class="text-gray-500 font-weight-bold mr-3">Dashboard Quản Trị</span>
            </div>

            <div class="admin-main-body">
<?php else: ?>
    <header class="user-header d-flex align-items-center">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="<?= BASE_URL ?>" class="text-decoration-none d-flex align-items-center gap-2">
                <span style="font-size: 28px;">🌸</span>
                <span class="h4 mb-0 font-weight-bold text-dark">FlowerShop</span>
            </a>

            <nav class="d-none d-md-flex">
                <a href="<?= BASE_URL ?>" class="user-nav-link">Trang chủ</a>
                <a href="<?= BASE_URL ?>" class="user-nav-link">Cửa hàng</a>
                <?php if($isLoggedIn): ?>
                    <a href="<?= BASE_URL ?>product/history" class="user-nav-link">Lịch sử mua</a>
                <?php endif; ?>
            </nav>

            <div class="d-flex align-items-center gap-3">
                <a href="<?= BASE_URL ?>product/cart" class="text-dark position-relative mr-2">
                    <i class="fas fa-shopping-bag fa-lg"></i>
                    <?php if ($cartCount > 0): ?>
                        <span class="badge badge-danger rounded-circle position-absolute" style="top: -8px; right: -8px; font-size: 10px;"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>

                <div class="dropdown">
                    <div class="avatar-box" style="cursor: pointer;" data-toggle="dropdown">
                        <?= strtoupper(substr($userName, 0, 1)) ?>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right shadow border-0 mt-2">
                        <?php if ($isLoggedIn): ?>
                            <div class="px-4 py-2 bg-light mb-2">
                                <small>Xin chào,</small><br><strong><?= h($userName) ?></strong>
                            </div>
                            <a class="dropdown-item" href="<?= BASE_URL ?>user/profile">Hồ sơ cá nhân</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?= BASE_URL ?>auth/logout">Đăng xuất</a>
                        <?php else: ?>
                            <a class="dropdown-item" href="<?= BASE_URL ?>auth/login">Đăng nhập</a>
                            <a class="dropdown-item" href="<?= BASE_URL ?>auth/register">Đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container main-container">
<?php endif; ?>