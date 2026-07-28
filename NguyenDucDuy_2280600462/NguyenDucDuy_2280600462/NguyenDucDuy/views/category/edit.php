<?php include __DIR__ . '/../../views/product/shares/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-warning text-dark p-4 rounded-top-4">
                    <h4 class="mb-0 fw-bold">✏️ Chỉnh Sửa Danh Mục</h4>
                </div>
                <div class="card-body p-5">
                    <form action="<?= BASE_URL ?>category/update" method="POST">
                        <input type="hidden" name="id" value="<?= $category['id'] ?>">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tên Danh Mục (*)</label>
                            <input type="text" name="name" class="form-control form-control-lg" 
                                   value="<?= htmlspecialchars($category['name']) ?>" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Mô Tả Ngắn</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($category['description']) ?></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>category/index" class="btn btn-outline-secondary px-4">Quay lại</a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold text-white">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../views/product/shares/footer.php'; ?>