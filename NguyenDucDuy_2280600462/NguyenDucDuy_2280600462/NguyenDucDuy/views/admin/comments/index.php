<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Bình Luận</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rating-star { color: #f1c40f; }
        .product-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">💬 Quản Lý Đánh Giá & Bình Luận</h2>
            <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary ...">
    ⬅️ Quay về trang web</a>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase text-xs fw-bold">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Đánh giá</th>
                            <th style="width: 30%;">Nội dung</th>
                            <th>Ngày gửi</th>
                            <th class="text-end pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($comments)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    Chưa có bình luận nào.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($comments as $cmt): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#<?= $cmt['id'] ?></td>
                                
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars((string)$cmt['user_name']) ?></div>
                                    <div class="small text-muted">ID: <?= $cmt['user_id'] ?></div>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if(!empty($cmt['product_image'])): ?>
                                            <img src="<?= BASE_URL . $cmt['product_image'] ?>" class="product-thumb">
                                        <?php endif; ?>
                                        <a href="<?= BASE_URL ?>product/detail/<?= $cmt['product_id'] ?>" target="_blank" class="text-decoration-none fw-bold text-dark">
                                            <?= htmlspecialchars((string)$cmt['product_name']) ?>
                                        </a>
                                    </div>
                                </td>

                                <td>
                                    <div class="rating-star">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <?= $i <= $cmt['rating'] ? '★' : '☆' ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="badge bg-<?= $cmt['rating'] >= 4 ? 'success' : ($cmt['rating'] == 3 ? 'warning' : 'danger') ?>">
                                        <?= $cmt['rating'] ?>/5
                                    </span>
                                </td>

                                <td>
                                    <p class="mb-0 text-secondary text-sm fst-italic">
                                        "<?= htmlspecialchars((string)$cmt['content']) ?>"
                                    </p>
                                </td>

                                <td class="text-muted small">
                                    <?= date('d/m/Y H:i', strtotime($cmt['created_at'])) ?>
                                </td>

                                <td class="text-end pe-4">
                                    <a href="<?= BASE_URL ?>comment/delete/<?= $cmt['id'] ?>" 
                                       onclick="return confirm('Bạn có chắc muốn xóa bình luận này không? Hành động này không thể hoàn tác!')"
                                       class="btn btn-sm btn-danger rounded-pill px-3">
                                        🗑️ Xóa
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
<style>
    /* Định nghĩa biến màu nếu chưa có */
    :root {
        --accent-color: #ffc107; /* Màu vàng cho nút điện thoại */
    }

    /* 7. NÚT LIÊN HỆ NỔI (FLOATING BUTTONS) */
    .floating-contact {
        position: fixed;
        right: 25px;
        bottom: 30px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .float-btn {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.6rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transition: all 0.3s;
        text-decoration: none !important; /* Bỏ gạch chân */
    }

    .float-btn:hover {
        transform: scale(1.1);
        color: white;
    }

    /* Nút Zalo */
    .btn-zalo {
        background: #0068ff;
        font-weight: bold;
        font-family: sans-serif;
        font-size: 1rem;
        border: 2px solid white;
    } 

    /* Nút Messenger */
    .btn-mess {
        background: #0084ff;
    }

    /* Nút Gọi điện (Rung lắc) */
    .btn-phone {
        background: var(--accent-color);
        color: #212529; /* Chữ màu đen cho nổi trên nền vàng */
        animation: pulse-phone 2s infinite;
    }

    @keyframes pulse-phone {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
</style>

<div class="floating-contact">
    
    <a href="https://zalo.me/0915136743" target="_blank" class="float-btn btn-zalo">
        Zalo
    </a>

    <a href="https://m.me/YOUR_FACEBOOK_ID" target="_blank" class="float-btn btn-mess">
        <i class="fab fa-facebook-messenger"></i>
    </a>

    <a href="tel:0915136743" class="float-btn btn-phone">
        <i class="fas fa-phone-alt"></i>
    </a>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<footer class="text-center text-lg-start bg-light text-muted mt-5">
    </footer>
</body>
</html>