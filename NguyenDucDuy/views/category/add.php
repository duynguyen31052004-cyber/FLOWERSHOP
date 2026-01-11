<?php include __DIR__ . '/../../views/product/shares/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-success text-white p-4 rounded-top-4">
                    <h4 class="mb-0 fw-bold">✨ Thêm Danh Mục Mới</h4>
                </div>
                <div class="card-body p-5">
                    <form action="<?= BASE_URL ?>category/store" method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tên Danh Mục (*)</label>
                            <input type="text" name="name" class="form-control form-control-lg" placeholder="Ví dụ: Hoa Sinh Nhật" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Mô Tả Ngắn</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Mô tả về loại hoa này..."></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>category/index" class="btn btn-outline-secondary px-4">Quay lại</a>
                            <button type="submit" class="btn btn-success px-5 fw-bold">Lưu Danh Mục</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../views/product/shares/footer.php'; ?>