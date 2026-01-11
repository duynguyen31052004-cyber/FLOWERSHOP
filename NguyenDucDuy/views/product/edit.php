<?php
declare(strict_types=1);

// 1. KIỂM TRA QUYỀN ADMIN
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['auth'] ?? [];

// Nếu không phải admin thì chặn
if (($user['role'] ?? '') !== 'admin') {
    $redirectUrl = defined('BASE_URL') ? BASE_URL . "product/list" : "/";
    header("Location: $redirectUrl"); exit;
}

/**
 * 2. NHÚNG HEADER (SỬA LỖI ĐƯỜNG DẪN TẠI ĐÂY)
 * Sử dụng __DIR__ . '/shares/header.php' vì thư mục shares nằm cùng cấp với edit.php
 */
$headerPath = __DIR__ . '/shares/header.php';
if (file_exists($headerPath)) {
    include $headerPath;
} else {
    // Fallback nếu không tìm thấy (đề phòng)
    echo "<div class='alert alert-warning'>Không tìm thấy file Header tại: $headerPath</div>";
}

// Khởi tạo biến tránh lỗi
$product = $product ?? []; 
$categories = $categories ?? [];
$errors = $errors ?? [];
?>

<style>
    .edit-card { border: none; border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1); overflow: hidden; }
    .form-control, .form-select { border-radius: 12px; padding: 12px 15px; border: 1px solid #e9ecef; transition: 0.3s; }
    .form-control:focus, .form-select:focus { border-color: #10b981; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-label { font-weight: 600; color: #374151; font-size: 0.9rem; margin-bottom: 8px; }
    
    .img-preview-box { width: 100%; height: 250px; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 16px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
    .img-preview-box img { width: 100%; height: 100%; object-fit: cover; }
    .img-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); opacity: 0; transition: 0.3s; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; }
    .img-preview-box:hover .img-overlay { opacity: 1; }
    
    .btn-save { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 50px; padding: 12px 30px; font-weight: 600; transition: 0.3s; }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); }
</style>

<div class="container py-5">
    
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Chỉnh sửa sản phẩm</h2>
            <p class="text-muted small">Cập nhật thông tin chi tiết cho mẫu hoa #<?= $product['id'] ?? 0 ?></p>
        </div>
        <a href="<?= BASE_URL ?>product/list" class="btn btn-light rounded-pill px-4 fw-bold">
            <i class="fas fa-times me-2"></i> Hủy bỏ
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger rounded-4 mb-4 shadow-sm">
            <ul class="mb-0 ps-3">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>product/update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= (int)($product['id'] ?? 0) ?>">
        
        <?php if(function_exists('csrfField')) echo csrfField(); ?>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card edit-card h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-info-circle me-2"></i> Thông tin chung</h5>
                        
                        <div class="mb-4">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars((string)($product['name'] ?? '')) ?>" required>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Danh mục</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= (isset($product['category_id']) && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Giá bán (VNĐ)</label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control" value="<?= (int)($product['price'] ?? 0) ?>" required min="0">
                                    <span class="input-group-text bg-light border-start-0 rounded-end-3">₫</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Mô tả sản phẩm</label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Mô tả chi tiết về sản phẩm..."><?= htmlspecialchars((string)($product['description'] ?? '')) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card edit-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-image me-2"></i> Hình ảnh</h5>
                        
                        <div class="img-preview-box mb-3" onclick="document.getElementById('imgInput').click()">
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?= BASE_URL . $product['image'] ?>" id="previewImg">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300?text=No+Image" id="previewImg">
                            <?php endif; ?>
                            
                            <div class="img-overlay rounded-4">
                                <span><i class="fas fa-camera fa-2x"></i><br>Đổi ảnh</span>
                            </div>
                        </div>
                        
                        <input type="file" name="image" id="imgInput" class="form-control" accept="image/*" onchange="previewFile(this)">
                        <div class="form-text mt-2 text-center">Cho phép: JPG, PNG, WEBP (Max 2MB)</div>
                    </div>
                </div>

                <div class="card edit-card bg-light border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Xác nhận</h5>
                        <p class="small text-muted mb-4">Vui lòng kiểm tra kỹ thông tin trước khi lưu thay đổi.</p>
                        
                        <button type="submit" class="btn btn-save w-100 text-white shadow-sm mb-2">
                            <i class="fas fa-save me-2"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewFile(input) {
        var file = input.files[0];
        if(file){
            var reader = new FileReader();
            reader.onload = function(){
                document.getElementById('previewImg').src = reader.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>

<?php 
// 3. NHÚNG FOOTER (SỬA LỖI ĐƯỜNG DẪN TẠI ĐÂY)
$footerPath = __DIR__ . '/shares/footer.php';
if (file_exists($footerPath)) {
    include $footerPath;
}
?>