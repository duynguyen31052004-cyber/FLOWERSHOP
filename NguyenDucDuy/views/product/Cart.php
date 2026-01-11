<?php
declare(strict_types=1);
include __DIR__ . '/shares/header.php';

if (!function_exists('h')) {
    function h($str): string { return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }
}
function img_url(?string $img): ?string {
    if (!$img) return null;
    return BASE_URL . preg_replace('#^public/#', '', ltrim($img, '/'));
}

$cart = $cart ?? ($_SESSION['cart'] ?? []);
$totalItems = 0;
$totalMoney = 0.0;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        prefix: 'tw-', 
        theme: {
            extend: {
                colors: { primary: '#ff758c', secondary: '#ff7eb3', dark: '#2d3436' },
                boxShadow: { 'premium': '0 20px 50px rgba(0,0,0,0.05)' }
            }
        },
        corePlugins: { preflight: false }
    }
</script>

<style>
    body { background: #fffafa; }
    .cart-card { background: white; border-radius: 2.5rem; border: 1px solid rgba(255, 117, 140, 0.1); }
    .btn-grad-pink {
        background: linear-gradient(to right, #ff758c 0%, #ff7eb3 51%, #ff758c 100%);
        background-size: 200% auto; transition: 0.5s;
    }
    .btn-grad-pink:hover { background-position: right center; color: white; }
    .qty-btn {
        width: 30px; height: 30px; border-radius: 50%; border: 1px solid #eee;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        background: white; transition: all 0.2s;
    }
    .qty-btn:hover { background: #ff758c; color: white; border-color: #ff758c; }
</style>

<div class="container tw-py-16 animate__animated animate__fadeIn">
    <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-items-end tw-mb-10 tw-gap-4">
        <div>
            <h6 class="tw-text-primary tw-font-bold tw-uppercase tw-tracking-widest tw-mb-2">Shopping Cart</h6>
            <h2 class="tw-text-4xl tw-font-black tw-text-dark" style="font-family: 'Playfair Display', serif;">Giỏ Hàng Của Bạn</h2>
        </div>
        <a href="<?= BASE_URL ?>" class="tw-bg-white tw-text-dark tw-no-underline tw-px-6 tw-py-3 tw-rounded-full tw-font-bold tw-border tw-border-gray-100 hover:tw-bg-gray-50 tw-shadow-sm">⬅ Tiếp tục chọn hoa</a>
    </div>

    <div class="row g-5">
        <div class="col-lg-8">
            <div class="cart-card tw-shadow-premium tw-overflow-hidden tw-p-8">
                <?php if (empty($cart)): ?>
                    <div class="tw-py-24 tw-text-center">
                        <h3 class="tw-text-2xl tw-font-bold tw-text-dark tw-mb-4">Giỏ hàng đang trống 🥀</h3>
                        <a href="<?= BASE_URL ?>" class="btn-grad-pink tw-text-white tw-no-underline tw-px-10 tw-py-4 tw-rounded-full tw-font-bold tw-shadow-lg tw-inline-block">Đi tới cửa hàng ngay ➝</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="tw-text-gray-400 tw-text-xs tw-uppercase tw-tracking-widest">
                                <tr class="tw-border-b tw-border-gray-50">
                                    <th class="tw-pb-6">Sản phẩm</th>
                                    <th class="tw-pb-6 tw-text-center">Số lượng</th>
                                    <th class="tw-pb-6 tw-text-right">Tổng cộng</th>
                                    <th class="tw-pb-6 tw-text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $id => $item): ?>
                                    <?php
                                        $id = (int)$id;
                                        $price = (float)($item['price'] ?? 0);
                                        $qty = (int)($item['quantity'] ?? 0);
                                        $lineTotal = $price * $qty;
                                        $totalItems += $qty;
                                        $totalMoney += $lineTotal;
                                    ?>
                                    <tr class="tw-border-b tw-border-gray-50/50">
                                        <td class="tw-py-8">
                                            <div class="tw-flex tw-items-center tw-gap-4">
                                                <img src="<?= h(img_url($item['image'])) ?>" class="tw-w-20 tw-h-20 tw-rounded-xl tw-object-cover tw-shadow-sm">
                                                <div>
                                                    <h5 class="tw-font-bold tw-text-gray-800 tw-mb-1">
                                                        <a href="<?= BASE_URL ?>product/detail/<?= $id ?>" class="tw-no-underline tw-text-inherit"><?= h($item['name']) ?></a>
                                                    </h5>
                                                    <p class="tw-text-primary tw-font-bold tw-text-sm"><?= number_format($price, 0, ',', '.') ?> đ</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="tw-text-center">
                                            <div class="tw-inline-flex tw-items-center tw-bg-gray-50 tw-rounded-full tw-px-2 tw-py-1">
                                                <button class="qty-btn" onclick="updateQty(<?= $id ?>, -1)">-</button>
                                                <input type="text" id="qty-<?= $id ?>" value="<?= $qty ?>" readonly 
                                                       class="tw-w-10 tw-bg-transparent tw-border-none tw-text-center tw-font-bold tw-text-dark tw-text-sm focus:tw-outline-none">
                                                <button class="qty-btn" onclick="updateQty(<?= $id ?>, 1)">+</button>
                                            </div>
                                        </td>
                                        <td class="tw-text-right">
                                            <span id="line-total-<?= $id ?>" class="tw-font-black tw-text-gray-800">
                                                <?= number_format($lineTotal, 0, ',', '.') ?> đ
                                            </span>
                                        </td>
                                        <td class="tw-text-center">
                                            <a href="<?= BASE_URL ?>product/removeFromCart/<?= $id ?>" onclick="return confirm('Xóa sản phẩm này?')" class="tw-text-gray-300 hover:tw-text-red-500 tw-text-xl">✕</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tw-mt-8 tw-flex tw-justify-between tw-items-center">
                        <a href="<?= BASE_URL ?>product/clearCart" onclick="return confirm('Xóa hết giỏ hàng?')" class="tw-text-gray-400 tw-text-sm tw-font-bold hover:tw-text-red-500 tw-no-underline">🗑️ Làm sạch giỏ hàng</a>
                        <p class="tw-text-gray-500 tw-text-sm">Tổng <span id="total-items-count" class="tw-text-dark tw-font-bold"><?= $totalItems ?></span> sản phẩm</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="cart-card tw-p-8 tw-shadow-premium tw-sticky tw-top-24">
                <h4 class="tw-text-xl tw-font-black tw-text-dark tw-mb-8">Tóm tắt đơn hàng</h4>
                <div class="tw-space-y-4 tw-mb-8">
                    <div class="tw-flex tw-justify-between tw-text-gray-500">
                        <span>Tạm tính</span>
                        <span id="sub-total"><?= number_format($totalMoney, 0, ',', '.') ?> đ</span>
                    </div>
                    <div class="tw-flex tw-justify-between tw-text-gray-500">
                        <span>Phí vận chuyển</span>
                        <span class="tw-text-green-500 tw-font-bold">Miễn phí</span>
                    </div>
                    <hr class="tw-border-gray-50">
                    <div class="tw-flex tw-justify-between tw-items-end">
                        <span class="tw-font-bold tw-text-dark">Tổng thanh toán</span>
                        <span class="tw-text-3xl tw-font-black tw-text-primary">
                            <span id="grand-total"><?= number_format($totalMoney, 0, ',', '.') ?></span> <span class="tw-text-sm tw-font-bold">đ</span>
                        </span>
                    </div>
                </div>
                <?php if (!empty($cart)): ?>
                    <a href="<?= BASE_URL ?>product/checkout" class="btn-grad-pink tw-block tw-text-center tw-text-white tw-no-underline tw-py-5 tw-rounded-full tw-font-bold tw-text-lg tw-shadow-lg">Tiến Hành Thanh Toán ✨</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateQty(productId, change) {
        const qtyInput = document.getElementById(`qty-${productId}`);
        let currentQty = parseInt(qtyInput.value);
        let newQty = currentQty + change;

        if (newQty < 1) return; // Không cho giảm dưới 1

        // Cập nhật giao diện ngay lập tức để cảm giác nhanh hơn
        qtyInput.value = newQty;

        $.ajax({
            url: '<?= BASE_URL ?>product/updateCartAjax',
            method: 'POST',
            data: { id: productId, qty: newQty },
            success: function(res) {
                if (res.status === 'success') {
                    // Cập nhật giá tiền từng dòng
                    $(`#line-total-${productId}`).text(res.lineTotalFmt);
                    
                    // Cập nhật tổng tiền bên phải
                    $('#sub-total').text(res.totalMoneyFmt + ' đ');
                    $('#grand-total').text(res.totalMoneyFmt);
                    
                    // Cập nhật số lượng tổng
                    $('#total-items-count').text(res.totalItems);
                } else {
                    alert('Lỗi: ' + res.message);
                    qtyInput.value = currentQty; // Quay lại số cũ nếu lỗi
                }
            },
            error: function() {
                alert('Lỗi kết nối server!');
                qtyInput.value = currentQty;
            }
        });
    }
</script>

<?php include __DIR__ . '/shares/footer.php'; ?>