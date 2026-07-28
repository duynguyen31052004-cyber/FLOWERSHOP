<?php
// views/product/history.php
include dirname(__DIR__) . '/product/shares/header_home.php';
?>

<div class="tw-h-24 tw-bg-dark"></div> 

<div class="container tw-py-10">
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-8">
        <h2 class="tw-text-3xl tw-font-bold tw-text-gray-800">📜 Lịch Sử Mua Hàng</h2>
        
        <a href="<?= BASE_URL ?>" class="btn btn-outline-primary tw-rounded-full">← Tiếp tục mua sắm</a>
    </div>

    <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-gray-100 tw-overflow-hidden">
        <?php if (empty($orders)): ?>
            <div class="tw-text-center tw-py-16">
                <div class="tw-text-6xl tw-mb-4">📭</div>
                <h4 class="tw-font-bold tw-text-gray-600">Bạn chưa có đơn hàng nào</h4>
                <p class="tw-text-gray-400 tw-mb-6">Hãy dạo một vòng cửa hàng và chọn những bông hoa đẹp nhất nhé!</p>
                
                <a href="<?= BASE_URL ?>" class="btn btn-primary tw-rounded-full tw-px-6">Đến cửa hàng ngay</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="tw-bg-gray-50 tw-text-gray-500 tw-uppercase tw-text-xs">
                        <tr>
                            <th class="tw-py-4 tw-px-6">Mã đơn</th>
                            <th class="tw-py-4 tw-px-6">Ngày đặt</th>
                            <th class="tw-py-4 tw-px-6">Tổng tiền</th>
                            <th class="tw-py-4 tw-px-6">Trạng thái</th>
                            <th class="tw-py-4 tw-px-6 tw-text-end">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="tw-px-6 tw-py-4 tw-font-bold text-primary">#<?= $order['id'] ?></td>
                                <td class="tw-px-6 tw-py-4 tw-text-gray-600">
                                    <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-font-bold tw-text-red-500">
                                    <?= number_format((float)($order['real_total_amount'] ?? 0), 0, ',', '.') ?> đ
                                </td>
                               <td class="tw-px-6 tw-py-4">
    <?php 
        // Chuẩn hóa trạng thái về chữ thường để so sánh chính xác
        $stt = strtolower(trim((string)$order['status']));
        
        // Bảng dịch trạng thái sang tiếng Việt và màu sắc tương ứng
        $badges = [
            'pending'    => ['bg' => 'warning', 'txt' => '⏳ Chờ xử lý'],
            'processing' => ['bg' => 'info',    'txt' => '🚚 Đang giao'],
            'completed'  => ['bg' => 'success', 'txt' => '✅ Hoàn thành'],
            'paid'       => ['bg' => 'success', 'txt' => '✅ Đã thanh toán'], // Thêm dòng này
            'cancelled'  => ['bg' => 'danger',  'txt' => '❌ Đã hủy'],
        ];

        // Lấy thông tin hiển thị, nếu không có trong danh sách thì hiện nguyên gốc
        $b = $badges[$stt] ?? ['bg' => 'secondary', 'txt' => ucfirst($stt)];
    ?>
    <span class="badge bg-<?= $b['bg'] ?> rounded-pill px-3">
        <?= $b['txt'] ?>
    </span>
</td>
                                <td class="tw-px-6 tw-py-4 tw-text-end">
                                    <a href="<?= BASE_URL ?>product/orderDetail/<?= $order['id'] ?>" class="btn btn-sm btn-light tw-rounded-full">
                                        👁️ Xem
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include dirname(__DIR__) . '/product/shares/footer_home.php'; ?>