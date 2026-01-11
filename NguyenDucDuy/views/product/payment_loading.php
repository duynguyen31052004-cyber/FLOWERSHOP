<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang xử lý thanh toán...</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        /* Hiệu ứng mờ dần khi chuyển trang */
        body { animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-xl text-center max-w-md w-full border border-gray-100">
        <div class="flex justify-center gap-4 mb-6 opacity-70">
            <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" class="h-8 object-contain">
            <img src="https://vnpay.vn/s1/statics.vnpay.vn/2023/9/06ncktiwd6dc1694418196384.png" class="h-8 object-contain">
            <img src="https://img.vietqr.io/image/VietinBank-113366668888-compact.png" class="h-8 object-contain">
        </div>

        <div class="flex justify-center mb-6">
            <div class="loader"></div>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-2">Đang kết nối cổng thanh toán...</h2>
        <p class="text-gray-500 text-sm mb-6">Vui lòng không tắt trình duyệt. Quá trình này có thể mất vài giây.</p>

        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-[3000ms] ease-out w-0" id="progressBar"></div>
        </div>
        <p class="text-xs text-gray-400">Secured by VNPAY</p>
    </div>

    <script>
        // 1. Chạy thanh tiến trình giả cho đẹp
        setTimeout(() => {
            const bar = document.getElementById('progressBar');
            if(bar) bar.style.width = '100%';
        }, 100);

        // 2. SAU 3 GIÂY -> CHUYỂN ĐẾN TRANG QR CODE
        setTimeout(function() {
            // 👇 QUAN TRỌNG: Chuyển sang hàm payment() trong Controller
            window.location.href = "<?= BASE_URL ?>order/payment/<?= $orderId ?>";
        }, 3000); 
    </script>
</body>
</html>
</body>
</html>