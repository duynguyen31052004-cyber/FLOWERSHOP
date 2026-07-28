<?php 
// views/product/payment_qr.php
include __DIR__ . '/shares/header.php'; 
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
    :root {
        --primary-color: #2563eb; 
        --accent-color: #f59e0b;
        --bg-light: #f3f4f6;
    }

    .qr-section {
        min-height: 90vh;
        background-color: var(--bg-light);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .payment-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        max-width: 1000px;
        width: 100%;
        overflow: hidden;
        display: flex;
        flex-direction: row; 
    }

    /* Cột trái: Thông tin */
    .left-panel {
        background: linear-gradient(135deg, #c92a2a 0%, #e03131 100%); /* Màu đỏ Techcombank */
        color: white;
        padding: 3rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
    }
    
    .left-panel::before, .left-panel::after {
        content: ''; position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%;
    }
    .left-panel::before { top: -50px; left: -50px; width: 200px; height: 200px; }
    .left-panel::after { bottom: -30px; right: -30px; width: 150px; height: 150px; }

    /* Cột phải: Mã QR */
    .right-panel {
        padding: 3rem;
        flex: 1.2;
        background: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .info-group {
        margin-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        padding-bottom: 1rem;
        position: relative;
        z-index: 10;
    }
    .info-group:last-child { border-bottom: none; }
    
    .label-text { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; display: block; margin-bottom: 5px; }
    .value-text { font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; justify-content: space-between; }

    .qr-frame {
        padding: 15px; border: 2px dashed #e03131; border-radius: 15px; background: #fff5f5;
        display: inline-block; margin-bottom: 1.5rem; transition: transform 0.3s;
    }
    .qr-frame:hover { transform: scale(1.02); }
    .qr-img { width: 240px; height: 240px; object-fit: contain; display: block; border-radius: 10px; }

    .btn-copy {
        background: rgba(255,255,255,0.2); border: none; color: white; width: 30px; height: 30px;
        border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s; margin-left: 10px;
    }
    .btn-copy:hover { background: white; color: #c92a2a; }

    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 10px;
    }
    @keyframes shimmer { 0% {background-position: 200% 0;} 100% {background-position: -200% 0;} }
    .is-hidden-load { display: none !important; }

    @media (max-width: 768px) {
        .payment-card { flex-direction: column; }
        .left-panel, .right-panel { padding: 2rem; }
    }
</style>

<section class="qr-section">
    <div class="payment-card animate__animated animate__fadeInUp">
        
        <div class="left-panel">
            <h3 class="title is-4 has-text-white mb-5">
                <i class="fas fa-receipt mr-2"></i> Thông tin thanh toán
            </h3>

            <div class="info-group">
                <span class="label-text">Ngân hàng thụ hưởng</span>
                <span class="value-text">
                    TECHCOMBANK <i class="fas fa-university opacity-50"></i>
                </span>
            </div>

            <div class="info-group">
                <span class="label-text">Số tài khoản</span>
                <span class="value-text has-text-warning">
                    <span id="tk_num_text"><?= htmlspecialchars($accountNo) ?></span>
                    <button class="btn-copy" onclick="copyText('tk_num_text', '<?= $accountNo ?>')" title="Sao chép">
                        <i class="far fa-copy"></i>
                    </button>
                </span>
            </div>

            <div class="info-group">
                <span class="label-text">Chủ tài khoản</span>
                <span class="value-text"><?= htmlspecialchars($accountName) ?></span>
            </div>

            <div class="info-group">
                <span class="label-text">Số tiền cần thanh toán</span>
                <span class="value-text has-text-warning" style="font-size: 1.5rem;">
                    <?= number_format($amount, 0, ',', '.') ?> đ
                </span>
            </div>

            <div class="info-group">
                <span class="label-text">Nội dung chuyển khoản (Bắt buộc)</span>
                <span class="value-text" style="font-size: 1rem; color: #ffe3e3;">
                    <span id="content_text">THANH TOAN DON <?= $orderId ?></span>
                    <button class="btn-copy" onclick="copyText('content_text', 'THANH TOAN DON <?= $orderId ?>')">
                        <i class="far fa-copy"></i>
                    </button>
                </span>
            </div>
            
            <p class="is-size-7 has-text-white-ter mt-4" style="opacity: 0.8; z-index: 10;">
                <i class="fas fa-shield-alt mr-1"></i> Giao dịch được bảo mật bởi Techcombank.
            </p>
        </div>

        <div class="right-panel">
            <div class="has-text-centered mb-5">
                <h2 class="title is-4 has-text-grey-dark mb-2">Quét mã VietQR</h2>
                <p class="is-size-6 has-text-grey">Mở ứng dụng ngân hàng để quét</p>
            </div>

            <div id="skeleton-loader" class="skeleton" style="width: 240px; height: 240px;"></div>

            <div id="real-content" class="is-hidden-load has-text-centered">
                <div class="qr-frame">
                    <img src="<?= $qrUrl ?>" class="qr-img" alt="VietQR Code">
                </div>
                
                <div class="notification is-danger is-light is-small mb-5">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Hệ thống đang chờ thanh toán...
                </div>

                <div class="buttons is-centered">
                    <a href="<?= BASE_URL ?>" class="button is-light is-rounded">
                        <i class="fas fa-chevron-left mr-2"></i> Để sau
                    </a>
                    
                    <button id="btn-fake-pay" class="button is-danger is-rounded has-text-weight-bold shadow-lg">
                        <i class="fas fa-check-double mr-2"></i> Giả lập thanh toán ngay
                    </button>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    // 1. Hiệu ứng tải ảnh QR
    document.addEventListener("DOMContentLoaded", function() {
        const img = new Image();
        img.src = "<?= $qrUrl ?>";
        img.onload = function() {
            setTimeout(() => {
                document.getElementById('skeleton-loader').classList.add('is-hidden-load');
                document.getElementById('real-content').classList.remove('is-hidden-load');
            }, 800); 
        };
    });

    // 2. Hàm Copy
    function copyText(elementId, text) {
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.querySelector(`button[onclick*="${elementId}"]`);
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.style.background = '#10b981';
            setTimeout(() => {
                btn.innerHTML = originalIcon;
                btn.style.background = '';
            }, 1500);
        }).catch(err => console.error('Lỗi sao chép:', err));
    }

    // 3. NÚT GIẢ LẬP
    const btnFake = document.getElementById('btn-fake-pay');
    if (btnFake) {
        btnFake.addEventListener('click', function() {
            const originalHTML = btnFake.innerHTML;
            btnFake.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Đang xử lý...';
            btnFake.disabled = true;
            
            fetch('<?= BASE_URL ?>order/fake_payment_confirm/<?= $orderId ?>')
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        window.location.href = '<?= BASE_URL ?>order/orderConfirmation/<?= $orderId ?>';
                    } else {
                        alert('Lỗi: ' + (data.message || 'Không xác định'));
                        resetBtn();
                    }
                })
                .catch(err => {
                    alert('Lỗi kết nối Server!');
                    resetBtn();
                });

            function resetBtn() {
                btnFake.innerHTML = originalHTML;
                btnFake.disabled = false;
            }
        });
    }

    // 4. Polling trạng thái
    const checkStatus = setInterval(function() {
        fetch('<?= BASE_URL ?>order/check_order_status/<?= $orderId ?>')
            .then(res => res.json())
            .then(data => {
                const status = (data.status || '').toLowerCase();
                if (status === 'paid' || status === 'completed' || status === 'hoàn thành') {
                    clearInterval(checkStatus);
                    window.location.href = '<?= BASE_URL ?>order/orderConfirmation/<?= $orderId ?>';
                }
            })
            .catch(err => console.error("Waiting...", err));
    }, 3000);
</script>

<?php include __DIR__ . '/shares/footer.php'; ?>