<?php include __DIR__ . '/../product/shares/header.php'; ?>

<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { prefix: 'tw-', corePlugins: { preflight: false } }</script>

<div class="container tw-py-12">
    <div class="tw-max-w-2xl tw-mx-auto">
        <div class="tw-bg-white tw-rounded-3xl tw-shadow-xl tw-overflow-hidden tw-border tw-border-gray-100">
            <div class="tw-bg-gradient-to-r tw-from-blue-600 tw-to-blue-400 tw-p-8 tw-text-white">
                <h2 class="tw-text-2xl tw-font-bold tw-mb-1">👤 Thông tin cá nhân</h2>
                <p class="tw-opacity-80 tw-text-sm">Quản lý và cập nhật thông tin tài khoản của bạn</p>
            </div>

            <div class="tw-p-8">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success tw-rounded-xl tw-mb-6 tw-font-bold">
                        ✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>user/profile" method="POST">
                    <div class="tw-mb-5">
                        <label class="tw-block tw-text-gray-700 tw-font-bold tw-mb-2">Họ và tên</label>
                        <input type="text" name="name" class="form-control tw-rounded-xl tw-py-3" 
                               value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                    </div>

                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-5 tw-mb-5">
                        <div>
                            <label class="tw-block tw-text-gray-700 tw-font-bold tw-mb-2">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control tw-rounded-xl tw-py-3" 
                                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                        </div>
                        <div>
                            <label class="tw-block tw-text-gray-700 tw-font-bold tw-mb-2">Email (Không thể sửa)</label>
                            <input type="email" class="form-control tw-rounded-xl tw-py-3 tw-bg-gray-50" 
                                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled>
                        </div>
                    </div>

                    <div class="tw-mb-8">
                        <label class="tw-block tw-text-gray-700 tw-font-bold tw-mb-2">Địa chỉ mặc định</label>
                        <textarea name="address" class="form-control tw-rounded-xl" rows="3" placeholder="Nhập địa chỉ của bạn..."><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>

                    <div class="tw-flex tw-gap-3">
                        <button type="submit" class="btn btn-primary tw-rounded-xl tw-px-8 tw-py-3 tw-font-bold tw-flex-grow">
                            🚀 Lưu thay đổi
                        </button>
                      
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../product/shares/footer.php'; ?>