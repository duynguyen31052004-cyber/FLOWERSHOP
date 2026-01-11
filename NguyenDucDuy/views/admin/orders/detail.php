<?php 
// views/admin/orders/detail.php
include __DIR__ . '/../../product/shares/header.php'; 

// 1. Kiểm tra xem người đang xem có phải Admin không?
$isAdmin = isset($_SESSION['auth']) && ($_SESSION['auth']['role'] ?? '') === 'admin';

// 2. Bảo vệ dữ liệu
if (empty($order) || !is_array($order)) {
    echo '<div class="container py-5"><div class="alert alert-danger">❌ Không tìm thấy đơn hàng!</div></div>';
    include __DIR__ . '/../../product/shares/footer.php';
    exit();
}
?>

<div class="container py-5">
    <div class="mb-4">
        <a href="<?= BASE_URL ?><?= $isAdmin ? 'order/index' : 'order/history' ?>" class="text-decoration-none text-muted">
            ⬅ Quay lại danh sách
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Chi tiết đơn #<?= $order['id'] ?></h5>
                    <span class="text-muted small">
                        <?= !empty($order['created_at']) ? date('H:i d/m/Y', strtotime($order['created_at'])) : '---' ?>
                    </span>
                </div>
                <div class="card-body">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>Sản phẩm</th>
                                <th class="text-center">SL</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orderDetails)): ?>
                                <?php foreach ($orderDetails as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= BASE_URL . ($item['product_image'] ?? 'assets/img/no-image.jpg') ?>" 
                                                     class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                <span class="fw-bold text-dark">
                                                    <?= htmlspecialchars($item['product_name'] ?? 'Sản phẩm đã xóa') ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">x<?= $item['quantity'] ?></td>
                                        <td class="text-end"><?= number_format((float)($item['price'] ?? 0), 0, ',', '.') ?> đ</td>
                                        <td class="text-end fw-bold">
                                            <?= number_format((float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 0), 0, ',', '.') ?> đ
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="border-top">
                            <tr>
                                <td colspan="3" class="text-end fw-bold pt-3">Tổng cộng:</td>
                                <td class="text-end fw-bold text-danger fs-5 pt-3">
                                    <?= number_format((float)($order['total_amount'] ?? 0), 0, ',', '.') ?> đ
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0 fw-bold">Thông tin khách hàng</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>👤 Tên:</strong> <?= htmlspecialchars($order['customer_name'] ?? '---') ?></p>
                    <p class="mb-2"><strong>📞 SĐT:</strong> <?= htmlspecialchars($order['customer_phone'] ?? '---') ?></p>
                    <p class="mb-3"><strong>🏠 Đ/C:</strong> <?= htmlspecialchars($order['customer_address'] ?? '---') ?></p>
                    
                    <div class="border-top pt-3 mt-3 bg-light p-3 rounded">
                        <div class="mb-3">
                            <strong class="d-block text-primary mb-1">📅 Ngày giao dự kiến:</strong>
                            <?php 
                                if (!empty($order['delivery_date'])) {
                                    echo '<span class="fw-bold text-dark">' . date('H:i - d/m/Y', strtotime($order['delivery_date'])) . '</span>';
                                } else {
                                    echo '<span class="text-muted fst-italic">Giao ngay (Không hẹn giờ)</span>';
                                }
                            ?>
                        </div>
                        <div>
                            <strong class="d-block text-danger mb-1">💌 Lời nhắn thiệp:</strong>
                            <div class="bg-white border p-2 rounded text-muted small fst-italic">
                                <?= !empty($order['message_card']) ? nl2br(htmlspecialchars($order['message_card'])) : 'Không có lời nhắn.' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 <?= $isAdmin ? 'bg-primary' : 'bg-success' ?> bg-opacity-10">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><?= $isAdmin ? 'Cập nhật trạng thái' : 'Trạng thái đơn hàng' ?></h6>
                    
                    <?php if ($isAdmin): ?>
                        <form action="<?= BASE_URL ?>order/updateStatus" method="POST">
                            <input type="hidden" name="id" value="<?= $order['id'] ?>">
                            <select name="status" class="form-select mb-3 border-primary fw-bold">
                                <option value="pending" <?= ($order['status'] == 'pending') ? 'selected' : '' ?>>🕒 Chờ xử lý</option>
                                <option value="processing" <?= ($order['status'] == 'processing') ? 'selected' : '' ?>>🚚 Đang giao hàng</option>
                                <option value="completed" <?= ($order['status'] == 'completed') ? 'selected' : '' ?>>✅ Đã giao thành công</option>
                                <option value="cancelled" <?= ($order['status'] == 'cancelled') ? 'selected' : '' ?>>❌ Đã hủy</option>
                            </select>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Lưu thay đổi</button>
                        </form>
                    <?php else: ?>
                        <?php 
                            // Định nghĩa màu sắc và chữ hiển thị
                            $status = $order['status'] ?? 'pending';
                            $statusMap = [
                                'pending' => ['label' => '🕒 Đơn hàng đang chờ xử lý', 'class' => 'alert-warning'],
                                'processing' => ['label' => '🚚 Đơn hàng đang được giao', 'class' => 'alert-info'],
                                'completed' => ['label' => '✅ Giao hàng thành công', 'class' => 'alert-success'],
                                'cancelled' => ['label' => '❌ Đơn hàng đã bị hủy', 'class' => 'alert-danger']
                            ];
                            $currentStatus = $statusMap[$status] ?? $statusMap['pending'];
                        ?>
                        <div class="alert <?= $currentStatus['class'] ?> mb-0 fw-bold text-center border-0 py-4">
                            <div class="fs-1 mb-2">
                                <?php if($status == 'processing') echo '🛵'; 
                                      elseif($status == 'completed') echo '🎉'; 
                                      elseif($status == 'cancelled') echo '🗑️'; 
                                      else echo '⏳'; ?>
                            </div>
                            <?= $currentStatus['label'] ?>
                        </div>
                        <p class="text-center text-muted small mt-2 mb-0">Cảm ơn bạn đã mua hàng tại FlowerShop!</p>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../../product/shares/footer.php'; ?>