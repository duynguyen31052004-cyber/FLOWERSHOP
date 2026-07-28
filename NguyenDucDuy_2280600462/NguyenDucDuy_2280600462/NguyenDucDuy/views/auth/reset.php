<?php
// Khởi tạo biến để tránh lỗi
$token = $token ?? ''; 
$error = $error ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { prefix: 'tw-' }
    </script>
    <style>
        body { background-color: #f0fdf4; font-family: sans-serif; }
    </style>
</head>
<body class="tw-min-h-screen tw-flex tw-items-center tw-justify-center tw-p-4">

    <div class="tw-max-w-md tw-w-full tw-bg-white tw-p-8 tw-rounded-3xl tw-shadow-xl">
        
        <div class="tw-text-center tw-mb-8">
            <div class="tw-text-5xl tw-mb-4">🔐</div>
            <h2 class="tw-text-3xl tw-font-bold tw-text-gray-800">Mật Khẩu Mới</h2>
            <p class="tw-text-gray-500 tw-mt-2">Nhập mật khẩu mới cho tài khoản của bạn.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="tw-bg-red-50 tw-text-red-600 tw-p-3 tw-rounded-lg tw-mb-6 tw-text-center tw-text-sm tw-font-bold tw-border tw-border-red-100">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>auth/updateNewPassword" method="POST">
            
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            
            <div class="tw-space-y-5">
                <div>
                    <label class="tw-block tw-text-sm tw-font-bold tw-text-gray-700 tw-mb-1">Mật khẩu mới</label>
                    <input type="password" name="password" required 
                           class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-gray-300 tw-bg-gray-50 focus:tw-bg-white focus:tw-border-green-500 focus:tw-outline-none tw-transition-all"
                           placeholder="••••••••">
                </div>

                <div>
                    <label class="tw-block tw-text-sm tw-font-bold tw-text-gray-700 tw-mb-1">Xác nhận mật khẩu</label>
                    <input type="password" name="confirm_password" required 
                           class="tw-w-full tw-px-4 tw-py-3 tw-rounded-xl tw-border tw-border-gray-300 tw-bg-gray-50 focus:tw-bg-white focus:tw-border-green-500 focus:tw-outline-none tw-transition-all"
                           placeholder="••••••••">
                </div>

                <button type="submit" 
                        class="tw-w-full tw-py-3 tw-rounded-xl tw-font-bold tw-text-white tw-bg-gradient-to-r tw-from-green-500 tw-to-emerald-600 hover:tw-shadow-lg hover:tw-scale-[1.02] tw-transition-all">
                    Lưu Thay Đổi
                </button>
            </div>

            <div class="tw-text-center tw-mt-6">
                <a href="<?= BASE_URL ?>auth/login" class="tw-text-sm tw-font-bold tw-text-green-600 hover:tw-underline">
                    ← Quay lại Đăng nhập
                </a>
            </div>
        </form>
    </div>

</body>
</html>