<?php
declare(strict_types=1);

include __DIR__ . '/shares/header.php';

$products = $products ?? [];

// =========================================================================
// 1. LOGIC KIỂM TRA QUYỀN
// =========================================================================
$currentUser = $_SESSION['auth'] ?? [];
$roleRaw = $currentUser['role'] ?? ''; 
$roleClean = trim(strtolower((string)$roleRaw));
$isAdmin = ($roleClean === 'admin');

// =========================================================================
// 2. TỐI ƯU TIÊU ĐỀ
// =========================================================================
$pageTitle = $isAdmin ? '🌸 Quản Lý Kho Hoa' : '✨ Danh Sách Mẫu Hoa Tuyển Chọn';
?>

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        prefix: 'tw-', 
        corePlugins: { preflight: false } 
    }
</script>

<div class="container tw-py-8">
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-8 tw-bg-white tw-p-6 tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-100">
        <div>
            <h2 class="tw-text-2xl tw-font-bold tw-text-gray-800"><?= $pageTitle ?></h2>
            <p class="tw-text-sm tw-text-gray-500 tw-mt-1">Hiện có: <span class="tw-font-bold tw-text-blue-600"><?= count($products) ?></span> mẫu hoa đang bán</p>
        </div>
        
        <div class="tw-flex tw-gap-3">
            <?php if ($isAdmin): ?>
                <a href="<?= BASE_URL ?>product/add" class="btn btn-primary tw-flex tw-items-center tw-gap-2 tw-rounded-lg tw-font-semibold tw-shadow-lg tw-shadow-blue-500/30 hover:tw-translate-y-[-2px] tw-transition-transform">
                    <span class="tw-text-lg">+</span> Thêm Mẫu Hoa
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="tw-bg-white tw-rounded-2xl tw-shadow-md tw-overflow-hidden tw-border tw-border-gray-100">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="tw-bg-gray-50 tw-text-gray-500 tw-text-xs tw-uppercase tw-tracking-wider">
                    <tr>
                        <th class="tw-py-4 tw-px-6 tw-font-bold">Hình ảnh</th>
                        <th class="tw-py-4 tw-px-6 tw-font-bold">Thông tin sản phẩm</th>
                        <th class="tw-py-4 tw-px-6 tw-font-bold">Danh mục</th>
                        <th class="tw-py-4 tw-px-6 tw-font-bold">Giá bán</th>
                        <th class="tw-py-4 tw-px-6 tw-font-bold tw-text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="tw-divide-y tw-divide-gray-100">
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="5" class="tw-text-center tw-py-12">
                                <div class="tw-flex tw-flex-col tw-items-center tw-text-gray-400">
                                    <div class="tw-text-6xl tw-mb-3">🥀</div>
                                    <p>Chưa có sản phẩm nào.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <tr class="tw-group hover:tw-bg-blue-50/50 tw-transition-colors">
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-w-16 tw-h-16 tw-rounded-xl tw-overflow-hidden tw-border tw-border-gray-200">
                                        <?php if (!empty($p['image'])): ?>
                                            <a href="<?= BASE_URL ?>product/detail/<?= $p['id'] ?>">
                                                <img src="<?= BASE_URL . $p['image'] ?>" class="tw-w-full tw-h-full tw-object-cover group-hover:tw-scale-110 tw-transition-transform tw-duration-300">
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">No Img</span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="tw-px-6 tw-py-4">
                                    <h5 class="tw-font-bold tw-text-gray-800 tw-mb-1">
                                        <a href="<?= BASE_URL ?>product/detail/<?= $p['id'] ?>" class="tw-no-underline hover:tw-text-blue-600">
                                            <?= htmlspecialchars((string)$p['name']) ?>
                                        </a>
                                    </h5>
                                    <p class="tw-text-sm tw-text-gray-500 tw-truncate" style="max-width: 200px;">
                                        <?= htmlspecialchars((string)($p['description'] ?? '')) ?>
                                    </p>
                                </td>

                                <td class="tw-px-6 tw-py-4">
                                    <span class="badge bg-light text-dark">
                                        🏷️ <?= htmlspecialchars($p['category_name'] ?? 'Khác') ?>
                                    </span>
                                </td>

                                <td class="tw-px-6 tw-py-4 tw-text-red-500 tw-font-bold">
                                    <?= number_format((float)$p['price'], 0, ',', '.') ?> ₫
                                </td>

                                <td class="tw-px-6 tw-py-4 tw-text-right">
                                    <div class="tw-flex tw-justify-end tw-gap-2">
                                        
                                        <?php if (!$isAdmin): ?>
                                            <a href="<?= BASE_URL ?>product/addToCart/<?= $p['id'] ?>" 
                                               class="btn btn-sm btn-outline-success tw-rounded-full tw-w-9 tw-h-9 tw-flex tw-items-center tw-justify-center"
                                               title="Thêm vào giỏ">
                                                🛒
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($isAdmin): ?>
                                            <a href="<?= BASE_URL ?>product/edit/<?= $p['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary tw-rounded-full tw-w-9 tw-h-9 tw-flex tw-items-center tw-justify-center"
                                               title="Sửa">
                                                ✏️
                                            </a>

                                            <a href="<?= BASE_URL ?>product/delete/<?= $p['id'] ?>" 
                                                class="btn btn-sm btn-outline-danger tw-rounded-full tw-w-9 tw-h-9 tw-flex tw-items-center tw-justify-center"
                                                    data-confirm="Hành động này không thể hoàn tác! Bạn chắc chắn muốn xóa mẫu hoa này?"
                                                        onclick="confirmDelete(event)"
                                                                     title="Xóa">
                                                 🗑️
</a>
                                        <?php endif; ?>
                                        
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

<?php include __DIR__ . '/shares/footer.php'; ?>