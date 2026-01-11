<?php
declare(strict_types=1);
include __DIR__ . '/shares/header.php'; 
$order = $order ?? null;
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
    :root {
        --primary: #10b981;       /* Xanh Emerald */
        --primary-dark: #047857;
        --text-main: #1e293b;
    }

    /* --- 1. HÌNH NỀN MỚI (Mesh Gradient) --- */
    .confirm-section {
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        position: relative;
        overflow: hidden;
        
        /* Phối màu Xanh Ngọc + Hồng Phấn + Trắng kem */
        background-color: #f0fdf4;
        background-image: 
            radial-gradient(at 0% 0%, hsla(340, 100%, 85%, 1) 0px, transparent 50%),
            radial-gradient(at 100% 0%, hsla(160, 100%, 80%, 1) 0px, transparent 50%),
            radial-gradient(at 100% 100%, hsla(340, 100%, 85%, 1) 0px, transparent 50%),
            radial-gradient(at 0% 100%, hsla(160, 100%, 80%, 1) 0px, transparent 50%);
    }

    /* Thêm các đốm sáng trang trí mờ ảo */
    .confirm-section::before {
        content: '';
        position: absolute;
        top: 20%; left: 10%;
        width: 300px; height: 300px;
        background: rgba(16, 185, 129, 0.15);
        filter: blur(80px);
        border-radius: 50%;
        animation: float 10s infinite ease-in-out;
    }
    .confirm-section::after {
        content: '';
        position: absolute;
        bottom: 20%; right: 10%;
        width: 350px; height: 350px;
        background: rgba(255, 117, 140, 0.2); /* Màu hồng hoa */
        filter: blur(90px);
        border-radius: 50%;
        animation: float 12s infinite ease-in-out reverse;
    }

    @keyframes float {
        0% { transform: translate(0, 0); }
        50% { transform: translate(30px, 40px); }
        100% { transform: translate(0, 0); }
    }

    /* --- 2. CARD CHÍNH (Làm nổi hơn trên nền mới) --- */
    .confirm-card {
        background: rgba(255, 255, 255, 0.95); /* Kính mờ nhẹ */
        backdrop-filter: blur(10px);
        border-radius: 2rem;
        box-shadow: 0 25px 50px -12px rgba(16, 185, 129, 0.15); /* Bóng xanh */
        overflow: hidden;
        border: 1px solid white;
        max-width: 700px;
        width: 100%;
        position: relative;
        z-index: 10; /* Đè lên hình nền */
    }

    /* Các CSS cũ giữ nguyên */
    .success-icon-box {
        width: 100px; height: 100px;
        background: #ecfdf5; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 0 0 15px #f0fdf4;
        animation: pulse-green 2s infinite;
    }
    .success-icon-box i { font-size: 3rem; color: var(--primary); }
    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 20px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .detail-box {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.5rem;
        padding: 1.5rem; height: 100%; transition: 0.3s;
    }
    .detail-box:hover {
        border-color: var(--primary); background: #fff;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.1);
    }
    
    .label-header { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; font-weight: 700; margin-bottom: 0.75rem; display: block; }
    
    .btn-home {
        background: #1f2937; color: white; border: none; padding: 0.75rem 2rem;
        border-radius: 50px; font-weight: 600; transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .btn-home:hover {
        background: var(--primary); transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3); color: white;
    }

    .btn-print {
        background: white; color: #475569; border: 1px solid #cbd5e1;
        padding: 0.75rem 2rem; border-radius: 50px; font-weight: 600; transition: all 0.3s ease;
    }
    .btn-print:hover { border-color: #1e293b; color: #1e293b; }

    .title-fix { margin-bottom: 0.5rem !important; line-height: 1.2; }
    .subtitle-fix { margin-top: 0 !important; }
</style>

<section class="section confirm-section">
    <div class="container">
        
        <?php if ($order): ?>
            <div class="confirm-card animate__animated animate__zoomIn mx-auto">
                <div class="card-content has-text-centered p-5 p-md-6">
                    
                    <div class="success-icon-box animate__animated animate__bounceIn">
                        <i class="fas fa-check"></i>
                    </div>

                    <div class="mb-6">
                        <h1 class="title is-3 has-text-grey-darker title-fix">Đặt hàng thành công!</h1>
                        <p class="subtitle is-6 has-text-grey subtitle-fix">
                            Mã đơn hàng: <strong class="has-text-primary is-size-5">#<?= htmlspecialchars((string)$order['id']) ?></strong>
                        </p>
                    </div>

                    <div class="columns is-variable is-4 has-text-left mb-6">
                        
                        <div class="column is-6">
                            <div class="detail-box">
                                <span class="label-header"><i class="fas fa-user-circle mr-1"></i> Người nhận</span>
                                <h4 class="title is-6 mb-1"><?= htmlspecialchars($order['customer_name']) ?></h4>
                                <p class="is-size-7 has-text-grey mb-2">
                                    <i class="fas fa-phone-alt mr-1"></i> <?= htmlspecialchars($order['customer_phone']) ?>
                                </p>
                                <p class="is-size-7 has-text-grey">
                                    <i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($order['customer_address']) ?>
                                </p>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="detail-box">
                                <span class="label-header"><i class="fas fa-receipt mr-1"></i> Trạng thái đơn</span>
                                <div class="mb-3">
                                    <?php 
                                    $status = strtolower($order['status'] ?? '');
                                    if ($status === 'paid' || $status === 'completed' || $status === 'hoàn thành'): 
                                    ?>
                                        <span class="tag is-success is-light is-medium is-rounded has-text-weight-bold">
                                            <i class="fas fa-check-circle mr-2"></i> Đã thanh toán
                                        </span>
                                    <?php else: ?>
                                        <span class="tag is-warning is-light is-medium is-rounded has-text-weight-bold">
                                            <i class="fas fa-clock mr-2"></i> Chờ xác nhận
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="is-size-7 has-text-grey">
                                    Thời gian: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="buttons is-centered no-print">
                        <a href="<?= BASE_URL ?>" class="button btn-home">
                            <i class="fas fa-shopping-bag mr-2"></i> Tiếp tục mua sắm
                        </a>
                        <button onclick="window.print()" class="button btn-print">
                            <i class="fas fa-print mr-2"></i> In hóa đơn
                        </button>
                    </div>

                </div>
                
                <div style="height: 6px; background: linear-gradient(90deg, #10b981, #f472b6);"></div>
            </div>

        <?php else: ?>
            <div class="confirm-card p-6 has-text-centered">
                <span class="icon is-large has-text-grey-lighter mb-4"><i class="fas fa-search fa-3x"></i></span>
                <h3 class="title is-4 has-text-grey">Không tìm thấy đơn hàng</h3>
                <a href="<?= BASE_URL ?>" class="button is-primary is-rounded mt-4">Về trang chủ</a>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include __DIR__ . '/shares/footer.php'; ?>