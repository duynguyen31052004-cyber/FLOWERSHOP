<?php
declare(strict_types=1);

// ✅ ĐÚNG CẤU TRÚC: Gọi header từ thư mục shares cùng cấp
include __DIR__ . '/shares/header.php'; 

// Khởi tạo các biến để tránh lỗi Undefined nếu Controller chưa truyền sang
$errors = $errors ?? [];
$categories = $categories ?? [];
$old = $old ?? ['name' => '', 'description' => '', 'price' => '', 'category_id' => ''];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="<?= BASE_URL ?>product/list" class="btn btn-outline-secondary me-3" style="border-radius: 10px;">
                    ⬅️ Quay lại
                </a>
                <h2 class="fw-bold mb-0">🌸 Thêm Mẫu Hoa Mới</h2>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger shadow-sm" style="border-radius: 12px;">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>product/save" method="POST" enctype="multipart/form-data">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tên mẫu hoa</label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?= htmlspecialchars($old['name']) ?>" placeholder="Ví dụ: Bó hồng đỏ tình yêu" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                                <input type="number" name="price" class="form-control" 
                                       value="<?= htmlspecialchars((string)$old['price']) ?>" placeholder="Ví dụ: 500000" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Chủ đề / Dịp lễ</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Chọn loại hoa --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $old['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả & Ý nghĩa hoa</label>
                            <textarea name="description" class="form-control" rows="4" 
                                      placeholder="Nhập thông tin chi tiết về các loại hoa trong bó..."><?= htmlspecialchars($old['description']) ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Hình ảnh mẫu hoa</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Nên chọn ảnh định dạng .jpg, .png hoặc .webp</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold" style="border-radius: 15px;">
                                🚀 Đăng bán mẫu hoa này
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/shares/footer.php'; ?>
