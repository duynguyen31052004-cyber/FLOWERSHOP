<?php
// 1. Dùng Header Admin chuẩn
include __DIR__ . '/../product/shares/header.php'; 
?>

<style>
    body { background-color: #f3f4f6; } /* Nền xám nhẹ cho toàn trang */
    .user-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .table-custom th {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
        color: #9ca3af;
        background-color: #f9fafb;
        padding: 16px 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    .table-custom td {
        padding: 20px 24px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }
    .avatar-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 48px;
        height: 48px;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        box-shadow: 0 4px 10px rgba(118, 75, 162, 0.3);
    }
    .role-badge {
        padding: 6px 12px;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .role-admin {
        background-color: #fee2e2;
        color: #ef4444;
    }
    .role-user {
        background-color: #d1fae5;
        color: #10b981;
    }
    .search-input {
        border: 2px solid transparent;
        background-color: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .search-input:focus {
        border-color: #8b5cf6;
        box-shadow: 0 4px 20px rgba(139, 92, 246, 0.15);
        outline: none;
    }
    .hover-row:hover {
        background-color: #fcfaff;
    }
</style>

<div class="container py-5">
    
    <div class="row align-items-center mb-5 gy-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1" style="font-family: 'Nunito', sans-serif;">
                👥 Quản Lý Khách Hàng
            </h2>
            <p class="text-muted mb-0">
                Hiện có <strong class="text-primary"><?= $totalUsers ?? 0 ?></strong> người dùng trong hệ thống
            </p>
        </div>
        
        <div class="col-md-6">
            <form method="get" class="d-flex justify-content-md-end position-relative">
                <input type="hidden" name="url" value="user/list">
                <div class="position-relative w-100" style="max-width: 400px;">
                    <input type="text" 
                           name="search" 
                           class="form-control rounded-pill py-3 px-4 ps-5 search-input border-0" 
                           placeholder="Tìm kiếm theo tên, email..." 
                           value="<?= htmlspecialchars($keyword ?? '') ?>">
                    <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                        🔍
                    </span>
                    <button class="btn btn-primary rounded-pill px-4 position-absolute top-50 end-0 translate-middle-y me-1 fw-bold" style="height: 40px; line-height: 1;">
                        Tìm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="user-card">
        <div class="table-responsive">
            <table class="table table-custom mb-0 w-100">
                <thead>
                    <tr>
                        <th width="40%">Thông Tin Tài Khoản</th>
                        <th width="20%">Vai Trò</th>
                        <th width="20%">Ngày Tham Gia</th>
                        <th width="20%" class="text-end">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="opacity-50 mb-3" style="font-size: 4rem;">🕵️</div>
                                <h5 class="text-muted fw-bold">Không tìm thấy người dùng nào</h5>
                                <p class="text-muted small">Thử tìm kiếm với từ khóa khác xem sao!</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <tr class="hover-row transition-all">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-gradient flex-shrink-0">
                                            <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                        </div>
                                        
                                        <div>
                                            <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($u['name']) ?></div>
                                            <div class="d-flex flex-column small">
                                                <span class="text-muted">📧 <?= htmlspecialchars($u['email']) ?></span>
                                                <span class="text-secondary" style="font-size: 0.75rem;">@<?= htmlspecialchars($u['username']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <span class="role-badge role-admin">
                                            🛡️ Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="role-badge role-user">
                                            👤 Khách hàng
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="text-dark fw-semibold">
                                        <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                                    </div>
                                    <div class="text-muted small">
                                        <?= date('H:i', strtotime($u['created_at'])) ?>
                                    </div>
                                </td>

                                <td class="text-end">
                                    <a href="<?= BASE_URL ?>user/detail/<?= $u['id'] ?>" 
                                       class="btn btn-light text-primary fw-bold rounded-pill px-4 shadow-sm hover:shadow-md transition-all"
                                       style="background-color: #f0f4ff;">
                                        Chi tiết ➝
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="d-flex justify-content-center mt-5">
            <nav>
                <ul class="pagination pagination-lg gap-2">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link rounded-circle d-flex align-items-center justify-content-center border-0 shadow-sm fw-bold <?= ($i == $page) ? 'bg-primary text-white' : 'text-dark bg-white' ?>" 
                               href="?url=user/list&page=<?= $i ?>&search=<?= urlencode($keyword ?? '') ?>"
                               style="width: 45px; height: 45px;">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>

</div>

<?php 
// 2. Footer Admin
include __DIR__ . '/../product/shares/footer.php'; 
?>