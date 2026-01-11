<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$auth = $_SESSION['auth'] ?? null;
$isLoggedIn = !empty($auth);
$isAdmin = $isLoggedIn && ($auth['role'] ?? '') === 'admin';
$userName = $auth['name'] ?? 'Khách';

$cartCount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += (int)($item['quantity'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>FlowerShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            prefix: 'tw-', 
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Quicksand', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: { primary: '#ff758c', secondary: '#ff7eb3', dark: '#2d3436' }
                }
            },
            corePlugins: { preflight: false } 
        }
    </script>
</head>
<body class="tw-bg-gray-50 tw-font-sans">
<style>
    /* Định nghĩa biến màu nếu chưa có */
    :root {
        --accent-color: #ffc107; /* Màu vàng cho nút điện thoại */
    }

    /* 7. NÚT LIÊN HỆ NỔI (FLOATING BUTTONS) */
    .floating-contact {
        position: fixed;
        right: 25px;
        bottom: 30px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .float-btn {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.6rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transition: all 0.3s;
        text-decoration: none !important; /* Bỏ gạch chân */
    }

    .float-btn:hover {
        transform: scale(1.1);
        color: white;
    }

    /* Nút Zalo */
    .btn-zalo {
        background: #0068ff;
        font-weight: bold;
        font-family: sans-serif;
        font-size: 1rem;
        border: 2px solid white;
    } 

    /* Nút Messenger */
    .btn-mess {
        background: #0084ff;
    }

    /* Nút Gọi điện (Rung lắc) */
    .btn-phone {
        background: var(--accent-color);
        color: #212529; /* Chữ màu đen cho nổi trên nền vàng */
        animation: pulse-phone 2s infinite;
    }

    @keyframes pulse-phone {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
</style>

<div class="floating-contact">
    
    <a href="https://zalo.me/0915136743" target="_blank" class="float-btn btn-zalo">
        Zalo
    </a>

    <a href="https://m.me/YOUR_FACEBOOK_ID" target="_blank" class="float-btn btn-mess">
        <i class="fab fa-facebook-messenger"></i>
    </a>

    <a href="tel:0915136743" class="float-btn btn-phone">
        <i class="fas fa-phone-alt"></i>
    </a>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<footer class="text-center text-lg-start bg-light text-muted mt-5">
    </footer>
<nav class="tw-absolute tw-top-0 tw-left-0 tw-w-full tw-z-50 tw-py-4">
    <div class="container">
        <div class="tw-flex tw-justify-between tw-items-center tw-bg-white/80 tw-backdrop-blur-md tw-rounded-full tw-px-6 tw-py-3 tw-shadow-sm">
            
            <a href="<?= BASE_URL ?>" class="tw-no-underline hover:tw-no-underline tw-flex tw-items-center tw-gap-2">
                <span class="tw-text-2xl">🌸</span>
                <div class="tw-text-2xl tw-font-bold tw-font-serif tw-text-gray-800">
                    FlowerShop
                </div>
            </a>

            <div class="tw-flex tw-items-center tw-gap-4">
                <?php if ($isLoggedIn): ?>
                    <span class="tw-text-gray-600 tw-text-sm tw-hidden md:tw-inline-block tw-font-bold">
                        Xin chào, <?= htmlspecialchars($userName) ?>
                    </span>

                    <?php if ($isAdmin): ?>
                        <a href="<?= BASE_URL ?>product/list"
                           class="tw-bg-gray-800 tw-text-white tw-px-4 tw-py-2 tw-rounded-full tw-text-sm tw-font-bold hover:tw-bg-black tw-transition-all tw-no-underline tw-flex tw-items-center tw-gap-2">
                            <span>🛠️</span> Quản lý
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>product/history" class="tw-text-gray-600 hover:tw-text-primary tw-text-xl tw-no-underline" title="Lịch sử">📜</a>
                        <a href="<?= BASE_URL ?>product/cart" class="tw-relative tw-text-gray-600 hover:tw-text-primary tw-text-xl tw-no-underline">
                            🛒
                            <?php if ($cartCount > 0): ?>
                                <span class="tw-absolute -tw-top-2 -tw-right-2 tw-bg-red-500 tw-text-white tw-text-[10px] tw-font-bold tw-w-4 tw-h-4 tw-flex tw-items-center tw-justify-center tw-rounded-full">
                                    <?= $cartCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <a href="<?= BASE_URL ?>auth/logout" class="tw-text-red-500 tw-text-sm tw-font-bold tw-no-underline hover:tw-underline">
                        Đăng xuất
                    </a>

                <?php else: ?>
                    <a href="<?= BASE_URL ?>auth/login" class="tw-text-gray-600 tw-font-bold hover:tw-text-primary tw-no-underline">Đăng nhập</a>
                    <a href="<?= BASE_URL ?>auth/register" class="tw-bg-primary tw-text-white tw-px-4 tw-py-2 tw-rounded-full tw-font-bold tw-shadow-lg hover:tw-bg-secondary tw-transition-all tw-no-underline">
                        Đăng ký
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>