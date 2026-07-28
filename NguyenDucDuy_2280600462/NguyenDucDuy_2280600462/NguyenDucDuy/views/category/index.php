<?php 
// 1. Header đúng theo yêu cầu của bạn
include __DIR__ . '/../../views/product/shares/header.php'; 
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">📂 Quản Lý Danh Mục</h2>
            <p class="text-muted">Danh sách các loại hoa hiện có trong hệ thống</p>
        </div>
        <a href="<?= BASE_URL ?>category/add" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
            <span class="fs-5 me-1">+</span> Thêm Danh Mục
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            ✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            ❌ <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="py-3 ps-4" width="5%">ID</th>
                        <th class="py-3" width="25%">Tên Danh Mục</th>
                        <th class="py-3" width="40%">Mô Tả</th>
                        <th class="py-3" width="15%">Ngày Tạo</th>
                        <th class="py-3 pe-4 text-end" width="15%">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="fs-1 mb-2">🗂️</div>
                                Chưa có danh mục nào. Hãy thêm mới!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#<?= $cat['id'] ?></td>
                                
                                <td>
                                    <span class="fw-bold text-primary fs-6">
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </span>
                                </td>
                                
                                <td class="text-muted text-truncate" style="max-width: 300px;">
                                    <?= htmlspecialchars($cat['description'] ?? 'Không có mô tả') ?>
                                </td>
                                
                                <td class="text-muted small">
                                    <?= date('d/m/Y', strtotime($cat['created_at'])) ?>
                                </td>
                                
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>category/edit/<?= $cat['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Sửa">
                                            ✏️
                                        </a>
                                        <a href="<?= BASE_URL ?>category/delete/<?= $cat['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('⚠️ CẢNH BÁO: Xóa danh mục này có thể làm ẩn các sản phẩm thuộc về nó.\nBạn có chắc chắn muốn xóa?');"
                                           title="Xóa">
                                            🗑️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// 2. Footer tương ứng
include __DIR__ . '/../../views/product/shares/footer.php'; 
?>