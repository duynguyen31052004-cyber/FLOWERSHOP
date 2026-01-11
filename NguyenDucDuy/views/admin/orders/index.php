<?php 
// views/admin/orders/index.php

// 1. Định nghĩa hàm h() nếu chưa có (Tránh lỗi Call to undefined function)
if (!function_exists('h')) {
    function h($str) {
        return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
    }
}

// 2. Gán giá trị mặc định cho các biến thống kê (Tránh lỗi Undefined variable)
$totalRevenue = $totalRevenue ?? 0;
$totalOrders = $totalOrders ?? 0;
$pendingOrders = $pendingOrders ?? 0;
$completedOrders = $completedOrders ?? 0;
$orders = $orders ?? [];

// 3. Include Header
include __DIR__ . '/../../product/shares/header.php'; 
?>

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = { 
        prefix: 'tw-', 
        corePlugins: { preflight: false } // Giữ lại style của Bootstrap
    }
</script>

<div class="container tw-py-8">
    <div class="tw-flex tw-justify-between tw-items-end tw-mb-8">
        <div>
            <h2 class="tw-font-bold tw-text-3xl tw-text-gray-800">🏠 Dashboard Quản Trị</h2>
            <p class="tw-text-gray-500 tw-mb-0">Tổng quan hoạt động kinh doanh của FlowerShop.</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>order/stats" class="btn btn-primary tw-rounded-xl tw-px-4 tw-font-bold tw-shadow-md">
                📊 Xem Báo Cáo Chi Tiết
            </a>
        </div>
    </div>

    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-6 tw-mb-10">
        <div class="tw-bg-white tw-p-6 tw-rounded-3xl tw-shadow-sm tw-border tw-border-gray-100">
            <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                <div class="tw-w-12 tw-h-12 tw-bg-green-100 tw-text-green-600 tw-rounded-2xl tw-flex tw-items-center tw-justify-center tw-text-xl">💰</div>
                <span class="tw-text-green-500 tw-text-xs tw-font-bold">Thực thu</span>
            </div>
            <h3 class="tw-text-2xl tw-font-bold tw-text-gray-800"><?= number_format($totalRevenue, 0, ',', '.') ?> đ</h3>
            <p class="tw-text-gray-400 tw-text-sm tw-mt-1">Đơn hàng hoàn thành</p>
        </div>

        <div class="tw-bg-white tw-p-6 tw-rounded-3xl tw-shadow-sm tw-border tw-border-gray-100">
            <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                <div class="tw-w-12 tw-h-12 tw-bg-blue-100 tw-text-blue-600 tw-rounded-2xl tw-flex tw-items-center tw-justify-center tw-text-xl">📦</div>
                <span class="tw-text-blue-500 tw-text-xs tw-font-bold">Đơn hàng</span>
            </div>
            <h3 class="tw-text-2xl tw-font-bold tw-text-gray-800"><?= $totalOrders ?></h3>
            <p class="tw-text-gray-400 tw-text-sm tw-mt-1">Tổng số đơn đã đặt</p>
        </div>

        <div class="tw-bg-white tw-p-6 tw-rounded-3xl tw-shadow-sm tw-border tw-border-gray-100">
            <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                <div class="tw-w-12 tw-h-12 tw-bg-orange-100 tw-text-orange-600 tw-rounded-2xl tw-flex tw-items-center tw-justify-center tw-text-xl">⏳</div>
                <span class="tw-text-orange-500 tw-text-xs tw-font-bold">Cần xử lý</span>
            </div>
            <h3 class="tw-text-2xl tw-font-bold tw-text-gray-800"><?= $pendingOrders ?></h3>
            <p class="tw-text-gray-400 tw-text-sm tw-mt-1">Đơn hàng mới</p>
        </div>

        <div class="tw-bg-white tw-p-6 tw-rounded-3xl tw-shadow-sm tw-border tw-border-gray-100">
            <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                <div class="tw-w-12 tw-h-12 tw-bg-purple-100 tw-text-purple-600 tw-rounded-2xl tw-flex tw-items-center tw-justify-center tw-text-xl">✅</div>
                <span class="tw-text-purple-500 tw-text-xs tw-font-bold">Giao xong</span>
            </div>
            <h3 class="tw-text-2xl tw-font-bold tw-text-gray-800"><?= $completedOrders ?></h3>
            <p class="tw-text-gray-400 tw-text-sm tw-mt-1">Đơn thành công</p>
        </div>
    </div>

    <div class="tw-bg-white tw-rounded-3xl tw-shadow-md tw-border tw-border-gray-100 tw-overflow-hidden">
        <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-50 tw-flex tw-justify-between tw-items-center">
            <h5 class="tw-font-bold tw-mb-0">Đơn hàng gần đây</h5>
            <span class="badge bg-dark tw-rounded-full px-3"><?= count($orders) ?> đơn hàng</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle tw-mb-0">
                <thead class="tw-bg-gray-50 tw-text-gray-400 tw-text-xs tw-uppercase">
                    <tr>
                        <th class="tw-ps-6 tw-py-4">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th class="tw-text-end tw-pe-6">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Chưa có đơn hàng nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="tw-ps-6 tw-font-bold tw-text-blue-600">#<?= $order['id'] ?></td>
                                <td>
                                    <div class="tw-font-bold tw-text-gray-800"><?= h($order['customer_name'] ?? 'Khách lẻ') ?></div>
                                    <div class="tw-text-xs tw-text-gray-400"><?= h($order['customer_phone'] ?? '---') ?></div>
                                </td>
                                <td class="tw-text-sm tw-text-gray-500">
                                    <?= !empty($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : '---' ?>
                                </td>
                                <td class="tw-font-bold tw-text-danger">
                                    <?= number_format((float)($order['total_amount'] ?? 0), 0, ',', '.') ?> đ
                                </td>
                                <td>
                                    <?php 
                                        $stt = $order['status'] ?? 'pending';
                                        
                                        // Màu sắc Badge
                                        $badgeClass = match($stt) {
                                            'pending'    => 'bg-warning text-dark',
                                            'processing' => 'bg-info text-white',
                                            'completed'  => 'bg-success text-white',
                                            'cancelled'  => 'bg-danger text-white',
                                            'paid'       => 'bg-success text-white', // Thêm trạng thái đã thanh toán
                                            default      => 'bg-secondary text-white'
                                        };

                                        // Tên hiển thị
                                        $statusText = match($stt) {
                                            'pending'    => 'Chờ xử lý',
                                            'processing' => 'Đang giao',
                                            'completed'  => 'Hoàn thành',
                                            'cancelled'  => 'Đã hủy',
                                            'paid'       => 'Đã thanh toán',
                                            default      => 'Khác'
                                        };
                                    ?>
                                    <span class="badge tw-rounded-full <?= $badgeClass ?> tw-px-3 tw-py-1.5">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td class="tw-text-end tw-pe-6">
                                    <a href="<?= BASE_URL ?>order/detail/<?= $order['id'] ?>" 
                                       class="btn btn-sm btn-light tw-rounded-full tw-font-bold tw-px-4 hover:tw-bg-gray-200">
                                        Chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../product/shares/footer.php'; ?>