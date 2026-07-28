<?php
declare(strict_types=1);

$title = 'Đăng nhập - Flower Shop';

// Xử lý logic hiển thị lỗi
$error = $_SESSION['login_error'] ?? '';
$old = $_SESSION['login_old'] ?? ['email' => ''];
unset($_SESSION['login_error'], $_SESSION['login_old']);

if (!function_exists('h')) {
    function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            prefix: 'tw-', 
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Lato', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        primary: '#86efac',   // Xanh lá pastel
                        secondary: '#bbf7d0', // Xanh lá nhạt
                        dark: '#14532d'       // Xanh lá đậm
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #f0fdf4; 
            background-image: radial-gradient(#22c55e 0.5px, transparent 0.5px);
            background-size: 20px 20px;
        }
        
        .form-control:focus {
            border-color: #86efac;
            box-shadow: 0 0 0 4px rgba(134, 239, 172, 0.2);
            outline: none;
        }
    </style>
</head>
<body class="tw-h-screen tw-flex tw-items-center tw-justify-center tw-p-4">

    <div class="tw-bg-white tw-w-full tw-max-w-4xl tw-rounded-[2rem] tw-shadow-2xl tw-shadow-green-100 tw-overflow-hidden tw-flex tw-min-h-[600px]">
        
        <div class="tw-hidden md:tw-block tw-w-1/2 tw-relative tw-bg-green-100">
            <img src="https://images.unsplash.com/photo-1606041008023-472dfb5e530f?q=80&w=1000&auto=format&fit=crop" 
                 class="tw-absolute tw-inset-0 tw-w-full tw-h-full tw-object-cover" 
                 alt="White Daisy Flowers"> 
            
            <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-t tw-from-green-900/80 via-green-800/10 tw-to-transparent"></div>
            
            <div class="tw-absolute tw-bottom-10 tw-left-10 tw-right-10 tw-text-white tw-z-10">
                <h3 class="tw-font-serif tw-text-4xl tw-font-bold tw-mb-3">Chào mừng trở lại!</h3>
                <p class="tw-text-green-50 tw-text-lg tw-font-light">Vẻ đẹp tinh khôi từ thiên nhiên đang chờ đón bạn.</p>
            </div>
        </div>

        <div class="tw-w-full md:tw-w-1/2 tw-p-8 md:tw-p-12 tw-flex tw-flex-col tw-justify-center">
            
            <div class="tw-text-center tw-mb-8">
                <a href="<?= BASE_URL ?>" class="tw-text-3xl tw-font-serif tw-font-bold tw-text-dark tw-no-underline">
                    🌼 FlowerShop
                </a>
                <h2 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mt-6">Đăng Nhập</h2>
                <p class="tw-text-gray-500 tw-text-sm">Nhập thông tin tài khoản của bạn</p>
            </div>

            <?php if ($error): ?>
                <div class="tw-bg-red-50 tw-text-red-500 tw-text-sm tw-p-3 tw-rounded-lg tw-mb-6 tw-text-center border tw-border-red-100">
                    ⚠️ <?= h($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>auth/doLogin" method="POST">
                
                <div class="tw-mb-5">
                    <label class="tw-block tw-text-sm tw-font-bold tw-text-gray-700 tw-mb-2" for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-gray-50 focus:tw-bg-white form-control tw-transition-all"
                           placeholder="name@example.com" 
                           value="<?= h($old['email'] ?? '') ?>" required>
                </div>

                <div class="tw-mb-6">
                    <div class="tw-flex tw-justify-between tw-mb-2">
                        <label class="tw-block tw-text-sm tw-font-bold tw-text-gray-700" for="password">Mật khẩu</label>
                        
                        <a href="<?= BASE_URL ?>auth/forgot" class="tw-text-sm tw-text-green-600 tw-font-bold hover:tw-underline">
                            Quên mật khẩu?
                        </a>
                        
                    </div>
                    <input type="password" id="password" name="password" 
                           class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-gray-50 focus:tw-bg-white form-control tw-transition-all"
                           placeholder="••••••••" required>
                </div>

                <button type="submit" 
                        class="tw-w-full tw-bg-gradient-to-r tw-from-green-400 tw-to-green-500 tw-text-white tw-font-bold tw-py-3 tw-rounded-xl tw-shadow-lg tw-shadow-green-500/30 hover:tw-shadow-green-500/50 hover:tw-scale-[1.02] tw-transition-all">
                    Đăng Nhập Ngay
                </button>

            </form>

            <div class="tw-mt-8 tw-text-center">
                <p class="tw-text-gray-600 tw-text-sm">
                    Chưa có tài khoản? 
                    <a href="<?= BASE_URL ?>auth/register" class="tw-text-green-600 tw-font-bold hover:tw-underline">Đăng ký miễn phí</a>
                </p>
                <div class="tw-mt-6">
                    <a href="<?= BASE_URL ?>" class="tw-text-gray-400 tw-text-xs hover:tw-text-gray-600">
                        ← Quay lại trang chủ
                    </a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>