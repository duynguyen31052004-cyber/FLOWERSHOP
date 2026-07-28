<?php
// views/product/detail.php
include __DIR__ . '/shares/header_home.php'; 

// --- KIỂM TRA QUYỀN ---
$isLoggedIn = isset($_SESSION['auth']); 
$isAdmin = $isLoggedIn && ($_SESSION['auth']['role'] ?? '') === 'admin'; 
?>

<div class="tw-h-24 tw-bg-dark"></div>

<div class="container tw-py-10">
    <nav class="tw-text-sm tw-mb-6 tw-text-gray-500">
        <a href="<?= BASE_URL ?>" class="hover:tw-text-primary tw-no-underline">Trang chủ</a> 
        <span class="tw-mx-2">/</span> 
        <span class="tw-text-gray-800 fw-bold"><?= htmlspecialchars($product['name'] ?? '') ?></span>
    </nav>

    <div class="row g-5">
        <div class="col-md-6">
            <div class="tw-rounded-3xl tw-overflow-hidden tw-shadow-lg tw-border-4 tw-border-white">
                <?php if (!empty($product['image'])): ?>
                    <img src="<?= BASE_URL . $product['image'] ?>" class="tw-w-full tw-object-cover hover:tw-scale-105 tw-transition-transform tw-duration-500" alt="<?= htmlspecialchars($product['name']) ?>">
                <?php else: ?>
                    <div class="tw-h-96 tw-bg-gray-100 tw-flex tw-items-center tw-justify-center text-muted">Chưa có ảnh</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <h1 class="tw-font-serif tw-text-4xl tw-font-bold tw-text-dark tw-mb-2">
                <?= htmlspecialchars($product['name'] ?? '') ?>
            </h1>
            
            <div class="tw-text-2xl tw-font-bold tw-text-primary tw-mb-4">
                <?= number_format((float)($product['price'] ?? 0), 0, ',', '.') ?> đ
            </div>

            <p class="tw-text-gray-600 tw-leading-relaxed tw-mb-6">
                <?= nl2br(htmlspecialchars($product['description'] ?? '')) ?>
            </p>
            
            <div class="tw-bg-gray-50 tw-p-4 tw-rounded-xl tw-mb-6 tw-border tw-border-gray-100">
                <div class="tw-flex tw-items-center tw-gap-3 tw-mb-2">
                    <span class="tw-text-green-600">✔</span> <span>Hoa tươi 100% nhập trong ngày</span>
                </div>
                <div class="tw-flex tw-items-center tw-gap-3">
                    <span class="tw-text-green-600">✔</span> <span>Giao hàng hỏa tốc 2H</span>
                </div>
            </div>

            <div class="tw-flex tw-gap-3">
                <?php if (!$isAdmin): ?>
                    <?php 
                        $cartUrl = $isLoggedIn 
                            ? BASE_URL . "product/addToCart/" . $product['id'] 
                            : BASE_URL . "auth/login";
                    ?>
                    <a href="<?= $cartUrl ?>" 
                       class="btn btn-primary btn-lg tw-rounded-full tw-px-8 tw-font-bold tw-shadow-glow hover:tw-scale-105 tw-transition-transform">
                        🛒 Thêm Vào Giỏ
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>product/edit/<?= $product['id'] ?>" 
                       class="btn btn-warning btn-lg tw-rounded-full tw-px-8 tw-font-bold tw-shadow-glow hover:tw-scale-105 tw-transition-transform tw-text-white">
                        ✏️ Sửa Sản Phẩm
                    </a>
                <?php endif; ?>

                <a href="tel:0901234567" class="btn btn-outline-dark btn-lg tw-rounded-full tw-px-6 tw-font-bold">
                    📞 Tư Vấn
                </a>
            </div>
        </div>
    </div>

    <div class="tw-mt-16">
        <h3 class="tw-font-bold tw-text-2xl tw-mb-6 tw-border-b tw-pb-2">Đánh Giá Sản Phẩm (<?= count($comments) ?>)</h3>

        <div class="row">
            <div class="col-md-5 tw-mb-8">
                <div class="tw-bg-white tw-p-6 tw-rounded-2xl tw-shadow-card tw-border tw-border-gray-100">
                    <h5 class="tw-font-bold tw-mb-4">Viết đánh giá của bạn</h5>
                    
                    <?php if ($isLoggedIn): ?>
                        <?php if ($isAdmin): ?>
                            <div class="tw-bg-yellow-50 tw-text-yellow-800 tw-p-4 tw-rounded-xl tw-text-center tw-border tw-border-yellow-100">
                                <div class="tw-text-3xl tw-mb-2">👑</div>
                                <span class="tw-font-bold">Quản trị viên</span><br>
                                <span class="tw-text-sm">Để phản hồi khách hàng, hãy nhấn nút "Phản hồi" bên dưới mỗi bình luận.</span>
                                <div class="tw-mt-3">
                                    <a href="<?= BASE_URL ?>comment/index" class="tw-text-sm tw-text-yellow-700 tw-underline tw-font-bold">Quản lý bình luận tại đây</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <form action="<?= BASE_URL ?>product/postComment" method="POST">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <div class="tw-mb-3">
                                    <label class="tw-block tw-text-sm tw-font-bold tw-mb-1">Đánh giá sao:</label>
                                    <select name="rating" class="form-select tw-rounded-lg">
                                        <option value="5">⭐⭐⭐⭐⭐ (Tuyệt vời)</option>
                                        <option value="4">⭐⭐⭐⭐ (Tốt)</option>
                                        <option value="3">⭐⭐⭐ (Bình thường)</option>
                                        <option value="2">⭐⭐ (Tệ)</option>
                                        <option value="1">⭐ (Rất tệ)</option>
                                    </select>
                                </div>
                                <div class="tw-mb-3">
                                    <label class="tw-block tw-text-sm tw-font-bold tw-mb-1">Nội dung:</label>
                                    <textarea name="content" class="form-control tw-rounded-lg" rows="3" placeholder="Chia sẻ cảm nhận của bạn..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-dark tw-w-full tw-rounded-lg tw-font-bold">Gửi đánh giá</button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="tw-text-center tw-py-4">
                            <p class="tw-text-gray-500 tw-mb-3">Bạn cần đăng nhập để viết bình luận.</p>
                            <a href="<?= BASE_URL ?>auth/login" class="btn btn-outline-primary tw-rounded-full tw-px-6">Đăng nhập ngay</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-7">
                <?php if (empty($comments)): ?>
                    <div class="tw-text-gray-500 tw-italic">Chưa có đánh giá nào. Hãy là người đầu tiên!</div>
                <?php else: ?>
                    <div class="tw-space-y-6">
                        <?php 
                        // Lọc bình luận gốc (không có parent_id)
                        $mainComments = array_filter($comments, fn($c) => empty($c['parent_id']));
                        // Lọc danh sách phản hồi
                        $replies = array_filter($comments, fn($c) => !empty($c['parent_id']));
                        
                        foreach ($mainComments as $cmt): 
                        ?>
                            <div class="tw-bg-gray-50 tw-p-5 tw-rounded-2xl tw-border tw-border-gray-100">
                                <div class="tw-flex tw-justify-between tw-mb-2">
                                    <div class="tw-flex tw-items-center tw-gap-2">
                                        <div class="tw-font-bold tw-text-dark">
                                            <?= htmlspecialchars($cmt['user_name']) ?>
                                        </div>
                                    </div>
                                    <div class="tw-text-xs tw-text-gray-400">
                                        <?= date('d/m/Y', strtotime($cmt['created_at'])) ?>
                                    </div>
                                </div>
                                <div class="tw-text-yellow-500 tw-text-sm tw-mb-2">
                                    <?= str_repeat('⭐', (int)$cmt['rating']) ?>
                                </div>
                                <p class="tw-text-gray-700 tw-mb-3">
                                    <?= nl2br(htmlspecialchars($cmt['content'])) ?>
                                </p>

                                <?php foreach ($replies as $reply): ?>
                                    <?php if ($reply['parent_id'] == $cmt['id']): ?>
                                        <div class="tw-ml-8 tw-mt-4 tw-p-4 tw-rounded-xl tw-bg-blue-50 tw-border-l-4 tw-border-blue-500">
                                            <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                                                <span class="tw-font-bold tw-text-blue-700">💌 Phản hồi từ FlowerShop</span>
                                                <span class="tw-text-[10px] tw-text-gray-400"><?= date('d/m/Y', strtotime($reply['created_at'])) ?></span>
                                            </div>
                                            <p class="tw-text-sm tw-text-gray-700 tw-mb-0">
                                                <?= nl2br(htmlspecialchars($reply['content'])) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if ($isAdmin): ?>
                                    <div class="tw-mt-4">
                                        <button class="btn btn-sm btn-link tw-text-blue-600 tw-p-0 tw-no-underline tw-font-bold tw-text-xs" 
                                                onclick="document.getElementById('reply-box-<?= $cmt['id'] ?>').classList.toggle('tw-hidden')">
                                            💬 Phản hồi khách hàng
                                        </button>
                                        
                                        <div id="reply-box-<?= $cmt['id'] ?>" class="tw-hidden tw-mt-3">
                                            <form action="<?= BASE_URL ?>product/replyComment" method="POST">
                                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                <input type="hidden" name="parent_id" value="<?= $cmt['id'] ?>">
                                                <textarea name="content" class="form-control tw-text-sm tw-rounded-xl" rows="2" placeholder="Viết phản hồi của bạn..." required></textarea>
                                                <div class="tw-flex tw-justify-end tw-mt-2">
                                                    <button type="submit" class="btn btn-primary btn-sm tw-rounded-lg tw-px-4 tw-font-bold">Gửi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($relatedProducts)): ?>
<div class="container tw-py-16 tw-border-t tw-border-gray-100 tw-mt-16">
    <div class="tw-text-center tw-mb-12">
        <h6 class="tw-text-primary tw-font-bold tw-uppercase tw-tracking-widest tw-mb-2">Có thể bạn sẽ thích</h6>
        <h2 class="tw-text-3xl tw-font-black tw-text-dark" style="font-family: 'Playfair Display', serif;">Sản Phẩm Tương Tự</h2>
    </div>

    <div class="row g-4">
        <?php foreach ($relatedProducts as $rp): ?>
            <div class="col-6 col-lg-3">
                <div class="tw-bg-white tw-rounded-3xl tw-overflow-hidden tw-shadow-sm hover:tw-shadow-xl tw-transition-all tw-duration-300 tw-group tw-border tw-border-gray-50 tw-h-full">
                    <div class="tw-relative tw-h-64 tw-overflow-hidden">
                        <a href="<?= BASE_URL ?>product/detail/<?= $rp['id'] ?>">
                            <img src="<?= BASE_URL . ($rp['image'] ?? 'assets/images/no-image.jpg') ?>" 
                                 class="tw-w-full tw-h-full tw-object-cover tw-transition-transform tw-duration-700 group-hover:tw-scale-110" 
                                 alt="<?= htmlspecialchars($rp['name']) ?>">
                        </a>
                        <div class="tw-absolute tw-inset-0 tw-bg-black/20 tw-opacity-0 group-hover:tw-opacity-100 tw-transition-opacity tw-flex tw-items-center tw-justify-center">
                             <a href="<?= BASE_URL ?>product/detail/<?= $rp['id'] ?>" class="tw-bg-white tw-text-dark tw-px-4 tw-py-2 tw-rounded-full tw-font-bold tw-text-sm hover:tw-bg-primary hover:tw-text-white tw-no-underline tw-transition-colors shadow-lg">
                                Xem chi tiết
                             </a>
                        </div>
                    </div>

                    <div class="tw-p-5 tw-text-center">
                        <h3 class="tw-font-bold tw-text-gray-800 tw-mb-2 tw-text-base tw-truncate">
                            <a href="<?= BASE_URL ?>product/detail/<?= $rp['id'] ?>" class="tw-no-underline tw-text-inherit hover:tw-text-primary">
                                <?= htmlspecialchars($rp['name']) ?>
                            </a>
                        </h3>
                        <div class="tw-text-primary tw-font-black tw-text-lg">
                            <?= number_format((float)$rp['price'], 0, ',', '.') ?> đ
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/shares/footer_home.php'; ?>