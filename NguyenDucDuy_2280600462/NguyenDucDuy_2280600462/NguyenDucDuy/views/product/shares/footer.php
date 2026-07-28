<?php if ($isAdmin): ?>
            </div> </div> </div> <?php else: ?>
    </div> <footer class="bg-white border-top mt-5 pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-pink-500 font-weight-bold mb-3">🌸 FlowerShop</h5>
                    <p class="text-muted small">Trao gửi yêu thương qua từng cánh hoa. Chất lượng hoa tươi tốt nhất TP.HCM.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="font-weight-bold mb-3">Liên kết nhanh</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted">Về chúng tôi</a></li>
                        <li><a href="<?= BASE_URL ?>product/list" class="text-muted">Sản phẩm</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="font-weight-bold mb-3">Liên hệ</h6>
                    <p class="text-muted small mb-1"><i class="fas fa-map-marker-alt mr-2"></i> 122 Bùi Đình Túy, Bình Thạnh</p>
                    <p class="text-muted small"><i class="fas fa-phone mr-2"></i> 0915 136 743</p>
                </div>
            </div>
            <div class="text-center mt-4 pt-3 border-top text-muted small">
                &copy; 2025 FlowerShop. All rights reserved.
            </div>
        </div>
    </footer>

    <div style="position: fixed; right: 20px; bottom: 30px; z-index: 9999; display: flex; flex-direction: column; gap: 10px;">
        <a href="https://zalo.me/0915136743" target="_blank" class="shadow-sm d-flex align-items-center justify-content-center text-white text-decoration-none" style="width: 50px; height: 50px; background: #0068ff; border-radius: 50%; font-weight: bold; font-size: 10px;">ZALO</a>
        <a href="tel:0915136743" class="shadow-sm d-flex align-items-center justify-content-center text-dark text-decoration-none" style="width: 50px; height: 50px; background: #ffc107; border-radius: 50%;"><i class="fas fa-phone-alt"></i></a>
    </div>
    <?php endif; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(event) {
        event.preventDefault();
        const link = event.currentTarget.getAttribute('href');
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Dữ liệu sẽ không thể khôi phục!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Xóa ngay',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = link;
        });
    }
</script>
</body>
</html>