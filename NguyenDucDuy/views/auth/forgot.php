<?php include __DIR__ . '/../product/shares/header_home.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold text-center mb-4 text-primary">Quên Mật Khẩu?</h3>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <?php if (isset($demoLink)): ?>
                        <div class="alert alert-success">
                            <strong>[DEMO CHẤM BÀI]</strong><br>
                            Hệ thống giả lập đã gửi mail. Bấm vào link dưới đây để đổi mật khẩu:<br>
                            <a href="<?= $demoLink ?>" class="fw-bold text-decoration-underline">BẤM VÀO ĐÂY ĐỂ ĐẶT LẠI MẬT KHẨU</a>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>auth/sendResetLink" method="POST">
                        <div class="mb-4">
                            <label class="form-label text-muted">Nhập email đăng ký của bạn</label>
                            <input type="email" name="email" class="form-control form-control-lg rounded-pill" placeholder="name@example.com" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill btn-lg fw-bold">Gửi Link Xác Nhận</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="<?= BASE_URL ?>auth/login" class="text-decoration-none">Quay lại Đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../product/shares/footer_home.php'; ?>