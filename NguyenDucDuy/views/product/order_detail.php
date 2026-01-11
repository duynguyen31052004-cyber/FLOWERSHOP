<?php
declare(strict_types=1);

/**
 * 1. ĐƯỜNG DẪN HEADER
 * shares nằm cùng cấp với file này trong thư mục product.
 */
include __DIR__ . '/shares/header_home.php'; 

$order = $order ?? null;
$items = $items ?? $orderDetails ?? [];
?>

<style>
    /* CSS DÀNH RIÊNG CHO CHẾ ĐỘ IN */
    @media print {
        /* Ẩn triệt để thanh Menu/Header, Footer và các thành phần thừa */
        header, nav, footer, .no-print, .tw-h-24, [class*="header"], 
        .zalo-chat-widget, .fb-customerchat { 
            display: none !important; 
        }

        /* Ép nội dung hóa đơn sát lên trên cùng của giấy in */
        body { 
            background: white !important; 
            margin: 0 !important; 
            padding: 0 !important; 
        }

        .invoice-card { 
            max-width: 100% !important; 
            margin: 0 !important; 
            box-shadow: none !important; 
            border: none !important;
            width: 100% !important;
        }

        /* Giữ lại màu sắc gradient của banner khi in */
        .header-banner {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border-radius: 0 !important;
        }
    }

    /* Giao diện hiển thị trên trình duyệt Web */
    .invoice-card { 
        max-width: 850px; 
        margin: 40px auto; 
        border-radius: 2rem; 
        overflow: hidden; 
        background: #fff; 
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); 
    }
    .header-banner { 
        background: linear-gradient(135deg, #059669 0%, #10b981 100%); 
        color: white; 
        padding: 50px 30px; 
        text-align: center; 
    }
    .status-badge { 
        display: inline-block; 
        padding: 8px 24px; 
        border-radius: 50px; 
        font-weight: 700; 
        font-size: 0.85rem; 
        border: 1px solid rgba(255,255,255,0.3); 
    }
    
    /* Màu sắc trạng thái khớp với dữ liệu Admin */
    .pill-completed { background: #dcfce7 !important; color: #166534 !important; border: 1px solid #bbf7d0 !important; }
    .pill-shipping { background: #dbeafe !important; color: #1e40af !important; border: 1px solid #bfdbfe !important; }
    .pill-canceled { background: #fee2e2 !important; color: #b91c1c !important; border: 1px solid #fecaca !important; }
    .pill-pending { background: #fef3c7 !important; color: #92400e !important; border: 1px solid #fde68a !important; }

    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 30px; }
    .info-card { background: #f9fafb; padding: 20px; border-radius: 1.5rem; border: 1px solid #e5e7eb; text-align: left; }
    .product-row { display: flex; align-items: center; padding: 15px 30px; border-bottom: 1px solid #f3f4f6; }
    .product-img { width: 65px; height: 65px; object-fit: cover; border-radius: 15px; margin-right: 20px; }
    
    .total-bar { background: #1f2937; color: white; padding: 25px 40px; display: flex; justify-content: space-between; align-items: center; }
    
    /* Style mới cho thông tin liên hệ cửa hàng */
    .shop-contact { padding: 30px; background: #fff; border-top: 2px dashed #e2e8f0; }
</style>

<div class="tw-h-24 tw-bg-dark no-print"></div>

<div class="invoice-card animate__animated animate__fadeIn">
    <?php if ($order): ?>
        <div class="header-banner">
            <div class="animate__animated animate__bounceIn mb-3"><i class="fas fa-file-invoice-dollar fa-4x"></i></div>
            <h2 class="fw-bold">HÓA ĐƠN THANH TOÁN</h2>
            <div class="mt-2 text-white-50">Mã đơn hàng: <strong>#<?= htmlspecialchars((string)$order['id']) ?></strong></div>
            
            <div class="mt-4">
    <?php 
    /**
     * ĐỒNG BỘ TRẠNG THÁI (Đã sửa lỗi hiển thị 'Paid')
     */
    $status_raw = trim((string)($order['status'] ?? '')); 
    $status = mb_strtolower($status_raw, 'UTF-8');
    
    switch ($status) {
        // Thêm 'paid' vào nhóm hoàn thành
        case 'hoàn thành': 
        case 'completed': 
        case 'paid': 
            $label = '✅ ĐÃ THANH TOÁN'; 
            $class = 'pill-completed'; 
            break;
            
        case 'đang giao': 
        case 'shipping': 
        case 'processing':
            $label = '🚚 ĐANG GIAO HÀNG'; 
            $class = 'pill-shipping'; 
            break;
            
        case 'đã hủy': 
        case 'canceled': 
        case 'cancelled':
            $label = '❌ ĐÃ HỦY'; 
            $class = 'pill-canceled'; 
            break;
            
        default:
            $label = '⏳ CHỜ XỬ LÝ'; 
            $class = 'pill-pending'; 
            break;
    }
    ?>
    <div class="status-badge <?= $class ?>">
        <?= $label ?>
    </div>
</div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <small class="text-muted fw-bold text-uppercase">Người nhận</small>
                <div class="fw-bold fs-5 mt-1"><?= htmlspecialchars($order['customer_name'] ?? '') ?></div>
                <div class="text-muted"><?= htmlspecialchars($order['customer_phone'] ?? '') ?></div>
                <div class="small mt-2 text-secondary"><?= htmlspecialchars($order['customer_address'] ?? '') ?></div>
            </div>
            <div class="info-card">
                <small class="text-muted fw-bold text-uppercase">Thời gian đặt</small>
                <div class="fw-bold fs-5 mt-1"><?= date('d/m/Y', strtotime($order['created_at'])) ?></div>
                <div class="text-muted small">Lúc: <?= date('H:i', strtotime($order['created_at'])) ?></div>
            </div>
        </div>

        <?php foreach ($items as $item): ?>
            <div class="product-row">
                <?php $img = !empty($item['product_image']) ? BASE_URL . $item['product_image'] : ''; ?>
                <img src="<?= $img ?>" class="product-img no-print" onerror="this.src='<?= BASE_URL ?>uploads/default.jpg'">
                <div class="flex-grow-1 text-start">
                    <div class="fw-bold text-dark"><?= htmlspecialchars($item['product_name']) ?></div>
                    <div class="small text-muted"><?= number_format((float)$item['price'], 0, ',', '.') ?> đ</div>
                </div>
                <div class="text-end">
                    <div class="small text-muted">x<?= $item['quantity'] ?></div>
                    <div class="fw-bold text-dark"><?= number_format((float)($item['price'] * $item['quantity']), 0, ',', '.') ?> đ</div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="total-bar">
            <div class="text-start">
                <div class="small text-gray-400 fw-bold text-uppercase">Tổng thanh toán</div>
                <div class="fs-2 fw-bold text-success"><?= number_format((float)($order['total_amount'] ?? 0), 0, ',', '.') ?> đ</div>
            </div>
            <div class="no-print d-flex gap-2">
                <button onclick="window.print()" class="btn btn-outline-light px-4 py-2 fw-bold shadow-sm" style="border-radius: 12px;">
                    <i class="fas fa-print me-2"></i> In hóa đơn
                </button>
                <a href="<?= BASE_URL ?>" class="btn btn-success px-4 py-2 fw-bold text-white shadow-sm" style="border-radius: 12px;">
                    Mua tiếp
                </a>
            </div>
        </div>

        <div class="shop-contact text-center">
            <div class="row">
                <div class="col-md-6 text-md-start mb-3 mb-md-0">
                    <p class="tw-text-sm tw-font-bold tw-text-gray-400 tw-uppercase tw-mb-2">📍 Địa chỉ cửa hàng</p>
                    <p class="tw-text-gray-600 tw-text-sm tw-mb-0">122/28/13 Bùi Đình Túy,  Bình Thạnh, TP.HCM</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="tw-text-sm tw-font-bold tw-text-gray-400 tw-uppercase tw-mb-2">📞 Hotline hỗ trợ</p>
                    <p class="tw-text-emerald-600 tw-font-bold tw-text-lg tw-mb-0">090 123 4567</p>
                </div>
            </div>
            <div class="tw-mt-6 tw-pt-6 tw-border-t tw-border-gray-50 tw-text-xs tw-text-gray-400 tw-italic">
                Cảm ơn quý khách đã tin tưởng và chọn FlowerShop để gửi trao yêu thương!
            </div>
        </div>

    <?php else: ?>
        <div class="p-5 text-center">
            <h4 class="text-muted">Không tìm thấy thông tin đơn hàng này.</h4>
            <a href="<?= BASE_URL ?>" class="btn btn-success mt-3 rounded-pill px-4">Quay lại cửa hàng</a>
        </div>
    <?php endif; ?>
</div>

<?php 
echo '<div class="no-print">';
include __DIR__ . '/shares/footer_home.php'; 
echo '</div>';
?>