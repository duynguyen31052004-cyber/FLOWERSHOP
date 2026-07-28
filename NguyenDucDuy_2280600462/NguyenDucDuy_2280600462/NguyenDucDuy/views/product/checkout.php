<?php
// views/product/checkout.php
declare(strict_types=1);

include __DIR__ . '/shares/header.php';

// Helper bảo mật (Nên để trong file helpers chung, nhưng để đây tạm cũng được)
if (!function_exists('h')) {
  function h($str): string {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
  }
}

function img_url(?string $img): ?string {
  if (!$img) return null;
  $img = ltrim($img, '/');
  $img = preg_replace('#^public/#', '', $img);
  return BASE_URL . $img;
}

$cart = $cart ?? ($_SESSION['cart'] ?? []);
$total = 0.0;
$totalItems = 0;

foreach ($cart as $item) {
  $price = (float)($item['price'] ?? 0);
  $qty   = (int)($item['quantity'] ?? 0);
  $total += $price * $qty;
  $totalItems += $qty;
}

// Lấy thông tin user đăng nhập (nếu có) để điền sẵn vào form
$authUser = $_SESSION['auth'] ?? [];
?>

<style>
  body{background:#f6f7fb;}
  .card-soft{border:0;border-radius:18px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);}
  .btn-round{border-radius:12px; font-weight: 600;}
  .thumb{width:55px; height:55px; object-fit:cover; border-radius:12px; border:1px solid #eee;}
  .form-control, .form-select{border-radius:14px; padding: 12px 15px;}
  .payment-option {
      border: 2px solid #f1f1f1;
      border-radius: 15px;
      padding: 15px;
      cursor: pointer;
      transition: all 0.3s;
  }
  .payment-option:hover { border-color: #ff758c; background: #fffafa; }
  .payment-option input:checked + div { color: #ff758c; font-weight: bold; }
  .payment-option input:checked ~ .check-icon { display: block; }
</style>

<div class="container py-5">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
      <h2 class="fw-bold mb-1">🧾 Hoàn tất đơn hàng</h2>
      <div class="text-muted">An tâm mua sắm - Giao hoa tận nơi</div>
    </div>

    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary btn-round" href="<?= BASE_URL ?>product/cart">
        ⬅ Giỏ hàng
      </a>
    </div>
  </div>

  <?php if (empty($cart)): ?>
    <div class="card card-soft p-5 text-center">
        <div class="fs-1">🥀</div>
        <h4 class="mt-3">Giỏ hàng của bạn đang trống</h4>
        <a href="<?= BASE_URL ?>" class="btn btn-primary btn-round mt-3 px-4">Quay lại mua sắm ngay</a>
    </div>
  <?php else: ?>

    <div class="row g-4">

      <div class="col-lg-7">
        <div class="card card-soft shadow-sm">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-4">📍 Thông tin giao hàng</h5>

            <form method="POST" action="<?= BASE_URL ?>order/checkout" class="needs-validation" novalidate>
              
              <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Họ và tên người nhận</label>
                    <input type="text" name="name" class="form-control" placeholder="Nguyễn Văn A" required
                           value="<?= h($_POST['name'] ?? $authUser['name'] ?? '') ?>">
                    <div class="invalid-feedback">Vui lòng cung cấp tên người nhận.</div>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" placeholder="0912xxx..." required
                           value="<?= h($_POST['phone'] ?? $authUser['phone'] ?? '') ?>">
                  </div>
              </div>

              <div class="mb-4">
                <label class="form-label fw-semibold">Địa chỉ nhận hoa chi tiết</label>
                <textarea name="address" class="form-control" rows="2" placeholder="Số nhà, tên đường, phường/xã..." required><?= h($_POST['address'] ?? $authUser['address'] ?? '') ?></textarea>
              </div>

              <div class="row">
                  <div class="col-md-12 mb-3">
                      <label class="form-label fw-semibold">📅 Ngày giờ giao hoa mong muốn</label>
                      <input type="datetime-local" name="delivery_date" required class="form-control" min="<?= date('Y-m-d\TH:i') ?>">
                      <div class="form-text text-muted small">* Chúng tôi sẽ cố gắng giao đúng thời gian này (+/- 30 phút).</div>
                  </div>
                  
                  <div class="col-md-12 mb-4">
                      <label class="form-label fw-semibold">💌 Lời nhắn trên thiệp (Miễn phí)</label>
                      <textarea name="message_card" class="form-control" rows="3" placeholder="Ví dụ: Chúc mừng sinh nhật em yêu..."></textarea>
                  </div>
              </div>

              <h5 class="fw-bold mb-3">💳 Phương thức thanh toán</h5>
              <div class="row g-3 mb-4">
                  <div class="col-md-6">
                      <label class="payment-option d-flex align-items-center w-100">
                          <input type="radio" name="payment_method" value="cod" checked class="me-2">
                          <div>
                              <div class="fw-bold">Tiền mặt (COD)</div>
                              <div class="small text-muted">Thanh toán khi nhận hoa</div>
                          </div>
                      </label>
                  </div>
                  <div class="col-md-6">
                      <label class="payment-option d-flex align-items-center w-100">
                          <input type="radio" name="payment_method" value="sepay" class="me-2">
                          <div>
                              <div class="fw-bold">Chuyển khoản VietQR</div>
                              <div class="small text-muted">Xác nhận đơn hàng nhanh hơn</div>
                          </div>
                      </label>
                  </div>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-round py-3 fs-5 shadow-sm">
                  🚀 Xác nhận & Thanh toán
                </button>
              </div>
              
              <p class="text-center text-muted small mt-3">
                  Bằng cách đặt hàng, bạn đồng ý với điều khoản sử dụng của FlowerShop.
              </p>
            </form>

          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card card-soft shadow-sm sticky-top" style="top: 20px;">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-4">🌸 Đơn hàng của bạn (<?= $totalItems ?>)</h5>

            <div class="mb-4 pe-2" style="max-height: 400px; overflow-y: auto;">
                <?php foreach ($cart as $id => $item): ?>
                    <?php
                      $name   = (string)($item['name'] ?? '');
                      $price  = (float)($item['price'] ?? 0);
                      $qty    = (int)($item['quantity'] ?? 0);
                      $imgUrl = img_url($item['image'] ?? null);
                    ?>
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-light">
                        <img src="<?= h($imgUrl ?: BASE_URL.'assets/images/no-image.jpg') ?>" class="thumb shadow-sm" alt="Flower">
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark small text-truncate" style="max-width: 180px;"><?= h($name) ?></div>
                            <div class="text-muted small">SL: <?= $qty ?></div>
                        </div>
                        <div class="fw-bold text-end">
                            <?= number_format($price * $qty, 0, ',', '.') ?>đ
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="bg-light p-3 rounded-4">
              <div class="d-flex justify-content-between mb-2">
                <div class="text-muted">Thành tiền</div>
                <div class="fw-semibold"><?= number_format($total, 0, ',', '.') ?>đ</div>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <div class="text-muted">Phí vận chuyển</div>
                <div class="text-success fw-bold">Miễn phí</div>
              </div>
              <hr>
              <div class="d-flex justify-content-between align-items-end">
                <div class="fw-bold text-dark fs-5">Tổng cộng</div>
                <div class="fw-black fs-4 text-danger"><?= number_format($total, 0, ',', '.') ?>đ</div>
              </div>
            </div>

            <div class="mt-4 p-3 border border-warning rounded-4 bg-warning bg-opacity-10 d-flex gap-3">
                <span class="fs-3">🎁</span>
                <div class="small">
                    <strong>Ưu đãi:</strong> Đơn hàng của bạn đủ điều kiện nhận thiệp chúc mừng miễn phí từ cửa hàng!
                </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  <?php endif; ?>

</div>

<script>
// Bootstrap validation
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>

<?php include __DIR__ . '/shares/footer.php';?>