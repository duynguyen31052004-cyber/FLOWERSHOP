<?php
declare(strict_types=1);
$title = 'Đăng ký tài khoản - Flower Shop';

$error = $_SESSION['register_error'] ?? '';
$old = $_SESSION['register_old'] ?? ['name' => '', 'email' => '', 'username' => ''];
unset($_SESSION['register_error'], $_SESSION['register_old']);
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
                        primary: '#60a5fa',
                        secondary: '#93c5fd',
                        dark: '#1e293b'
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #eff6ff; 
            background-image: radial-gradient(#3b82f6 0.5px, transparent 0.5px);
            background-size: 20px 20px;
        }
        .form-control:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="tw-min-h-screen tw-flex tw-items-center tw-justify-center tw-p-4 tw-py-10">

    <div class="tw-bg-white tw-w-full tw-max-w-5xl tw-rounded-[2rem] tw-shadow-2xl tw-shadow-blue-100 tw-overflow-hidden tw-flex tw-flex-col md:tw-flex-row">
        
        <div class="tw-w-full md:tw-w-1/2 tw-p-8 md:tw-p-12 tw-flex tw-flex-col tw-justify-center order-2 md:order-1">
            
            <div class="tw-mb-8">
                <h1 class="tw-font-serif tw-text-3xl tw-font-bold tw-text-gray-800 tw-mb-2">Tạo tài khoản mới</h1>
                <p class="tw-text-gray-500 tw-text-sm">Nhập thông tin cá nhân để tham gia cùng chúng tôi</p>
            </div>

            <?php if ($error): ?>
                <div class="tw-bg-red-50 tw-text-red-500 tw-text-sm tw-p-3 tw-rounded-lg tw-mb-6 tw-text-center border tw-border-red-100">
                    ⚠️ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>auth/doRegister" method="POST" class="needs-validation" novalidate>
                
                <div class="tw-mb-4">
                    <label class="tw-block tw-text-sm tw-font-bold tw-text-gray-700 tw-mb-1">Họ và tên</label>
                    <input type="text" name="name" class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-slate-50 focus:tw-bg-white form-control tw-transition-all"
                           placeholder="Nguyễn Văn A" required value="<?= htmlspecialchars((string)$old['name']) ?>">
                </div>

                <div class="tw-mb-4">
                    <label class="tw-block tw-text-sm tw-font-bold tw-text-gray-700 tw-mb-1">Tên đăng nhập (Username)</label>
                    <input type="text" name="username" class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-slate-50 focus:tw-bg-white form-control tw-transition-all"
                           placeholder="nguyenvana123" required value="<?= htmlspecialchars((string)($old['username'] ?? '')) ?>">
                </div>

                <div class="tw-mb-4">
                    <label class="tw-block tw-text-sm tw-font-bold tw-text-gray-700 tw-mb-1">Email</label>
                    <input type="email" name="email" class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-slate-50 focus:tw-bg-white form-control tw-transition-all"
                           placeholder="email@example.com" required value="<?= htmlspecialchars((string)$old['email']) ?>">
                </div>

                <div class="tw-flex tw-gap-4 tw-mb-2">
                    <div class="tw-w-1/2">
                        <label class="tw-block tw-text-sm tw-font-bold tw-text-gray-700 tw-mb-1">Mật khẩu</label>
                        <input type="password" name="password" class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-slate-50 focus:tw-bg-white form-control tw-transition-all" required minlength="6">
                    </div>
                    <div class="tw-w-1/2">
                        <label class="tw-block tw-text-sm tw-font-bold tw-text-gray-700 tw-mb-1">Xác nhận</label>
                        <input type="password" name="confirm_password" class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-slate-50 focus:tw-bg-white form-control tw-transition-all" required>
                    </div>
                </div>
                <p class="tw-text-xs tw-text-gray-400 tw-mb-6 tw-italic">* Mật khẩu phải có ít nhất 6 ký tự.</p>

                <button type="submit" class="tw-w-full tw-bg-gradient-to-r tw-from-blue-400 tw-to-blue-500 tw-text-white tw-font-bold tw-py-3 tw-rounded-xl tw-shadow-lg tw-shadow-blue-500/30 hover:tw-shadow-blue-500/50 hover:tw-scale-[1.02] tw-transition-all">
                    Đăng Ký Tài Khoản
                </button>

                <div class="tw-mt-6 tw-text-center">
                    <p class="tw-text-gray-600 tw-text-sm">
                        Đã có tài khoản? <a href="<?= BASE_URL ?>auth/login" class="tw-text-primary tw-font-bold hover:tw-underline">Đăng nhập ngay</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="tw-hidden md:tw-block tw-w-1/2 tw-relative order-1 md:order-2 tw-bg-blue-400">
            <img src="https://images.unsplash.com/photo-1468327768560-75b778cbb551?q=80&w=1000&auto=format&fit=crop" 
                 class="tw-absolute tw-inset-0 tw-w-full tw-h-full tw-object-cover" 
                 alt="Register Flowers"
                 onerror="this.style.display='none'">
            
            <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-b tw-from-transparent tw-to-blue-900/70"></div>
            
            <div class="tw-absolute tw-bottom-10 tw-left-10 tw-right-10 tw-text-white tw-z-10 tw-text-right">
                <h3 class="tw-font-serif tw-text-4xl tw-font-bold tw-mb-4">Tham gia cùng <br> cộng đồng yêu hoa</h3>
                <p class="tw-text-blue-100 tw-text-lg">Cập nhật xu hướng hoa nghệ thuật mới nhất mỗi ngày.</p>
            </div>
        </div>

    </div>

</body>
</html>