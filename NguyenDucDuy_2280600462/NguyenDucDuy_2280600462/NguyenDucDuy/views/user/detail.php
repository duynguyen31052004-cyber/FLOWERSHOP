<?php include __DIR__ . '/../product/shares/header_home.php'; ?>

<style>
    body { background-color: #f9f9f9; }
    
    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(255, 117, 140, 0.1);
        border: none;
        overflow: hidden;
    }

    .profile-header {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
        padding: 40px 20px;
        text-align: center;
        color: white;
    }

    .avatar-circle {
        width: 100px;
        height: 100px;
        background: white;
        color: #ff758c;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: bold;
        margin: 0 auto 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .info-label {
        font-weight: 600;
        color: #888;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
    }

    .table-custom {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    
    .table-custom th {
        background-color: #fff0f3;
        color: #d63384;
        font-weight: 700;
        border: none;
        padding: 15px;
    }
    
    .table-custom td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-processing { background-color: #cff4fc; color: #055160; }
    .status-completed { background-color: #d1e7dd; color: #0f5132; }
    .status-cancelled { background-color: #f8d7da; color: #842029; }
</style>

<div class="container py-5">
    
    <?php if (isset($_SESSION['auth']) && $_SESSION['auth']['role'] === 'admin'): ?>
        <a href="<?= BASE_URL ?>user/index" class="btn btn-outline-secondary rounded-pill mb-4 px-4 fw-bold">
            ← Quay lại danh sách
        </a>
    <?php endif; ?>

    <?php if ($user): ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="profile-card h-100">
                    <div class="profile-header">
                        <div class="avatar-circle">
                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                        </div>
                        <h4 class="fw-bold mb-1"><?= htmlspecialchars($user['name']) ?></h4>
                        <span class="badge bg-white text-danger rounded-pill px-3 py-2 mt-2 shadow-sm">
                            <?= $user['role'] === 'admin' ? '🛡️ Quản Trị Viên' : '👤 Khách Hàng Thân Thiết' ?>
                        </span>
                    </div>
                    <div class="p-4">
                        <div class="info-label">Username</div>
                        <div class="info-value">@<?= htmlspecialchars($user['username']) ?></div>

                        <div class="info-label">Email</div>
                        <div class="info-value"><?= htmlspecialchars($user['email']) ?></div>

                        <div class="info-label">Ngày tham gia</div>
                        <div class="info-value">
                            <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <?php if ($user['role'] !== 'admin'): ?>
                    <h4 class="mb-4 fw-bold text-dark">🧾 Lịch Sử Đơn Hàng</h4>

                    <?php if (empty($orders)): ?>
                        <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                            <div style="font-size: 50px;">🛍️</div>
                            <p class="text-muted mt-3">Khách hàng này chưa có đơn hàng nào.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>Mã Đơn</th>
                                        <th>Ngày Đặt</th>
                                        <th>Tổng Tiền</th>
                                        <th>Trạng Thái</th>
                                        <th class="text-end">Chi Tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $o): ?>
                                        <tr>
                                            <td class="fw-bold text-primary">#<?= $o['id'] ?></td>
                                            <td class="text-muted">
                                                <?= date('d/m/Y H:i', strtotime($o['created_at'])) ?>
                                            </td>
                                            <td class="fw-bold text-dark">
                                                <?= number_format((float)($o['total_amount'] ?? 0), 0, ',', '.') ?> đ
                                            </td>
                                            <td>
                                                <?php 
                                                    // Xử lý màu sắc trạng thái
                                                    $sttClass = 'bg-light text-dark';
                                                    $sttText = $o['status'];
                                                    
                                                    switch($o['status']) {
                                                        case 'pending': $sttClass = 'status-pending'; $sttText = 'Chờ xử lý'; break;
                                                        case 'processing': $sttClass = 'status-processing'; $sttText = 'Đang giao'; break;
                                                        case 'completed': $sttClass = 'status-completed'; $sttText = 'Hoàn thành'; break;
                                                        case 'cancelled': $sttClass = 'status-cancelled'; $sttText = 'Đã hủy'; break;
                                                    }
                                                ?>
                                                <span class="status-badge <?= $sttClass ?>">
                                                    <?= $sttText ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= BASE_URL ?>order/detail/<?= $o['id'] ?>" class="btn btn-sm btn-light rounded-circle" title="Xem chi tiết">
                                                    👁️
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-info border-0 shadow-sm rounded-3">
                        ℹ️ Tài khoản này là <b>Quản Trị Viên (Admin)</b>, không có lịch sử mua hàng cá nhân.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <div class="text-center py-5">
            <h3 class="text-danger">⚠️ Không tìm thấy thông tin khách hàng</h3>
            <a href="<?= BASE_URL ?>user/index" class="btn btn-primary mt-3">Quay lại danh sách</a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../product/shares/footer_home.php'; ?>