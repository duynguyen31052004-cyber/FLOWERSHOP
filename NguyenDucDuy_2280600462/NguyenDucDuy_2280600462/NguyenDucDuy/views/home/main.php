<?php 
declare(strict_types=1);

/**
 * 1. KẾT NỐI HEADER
 */
$rootViews = dirname(__DIR__); 
include $rootViews . '/product/shares/header_home.php'; 

// 2. Logic PHP (Kiểm tra quyền hạn)
$isLoggedIn = isset($_SESSION['auth']); 
$isAdmin = $isLoggedIn && ($_SESSION['auth']['role'] ?? '') === 'admin';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

// Xử lý tìm kiếm sản phẩm
if (!empty($keyword)) {
    require_once dirname(__DIR__) . '/../config/database.php';
    $db = (new Database())->getConnection();
    $sql = "SELECT p.*, c.name as category_name 
            FROM product p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.name LIKE :kw OR p.description LIKE :kw
            ORDER BY p.id DESC";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':kw', "%$keyword%");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = $products ?? [];
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
    /* =============================
       THEME VARIABLES (Rose/Nature)
       (Fallback nếu header chưa có)
    ============================== */
    :root {
        --primary-color: #ff758c;
        --accent-2: #ff4d6d;

        --bg-main: #fef2f2;          /* nền trang */
        --hero-bg: #fff9f9;          /* nền hero */
        --card-bg: #ffffff;

        --text-color: #5a5c69;
        --border-soft: rgba(0,0,0,0.08);

        --pill-hover-bg: #fff0f3;
        --shadow-primary: rgba(255, 117, 140, 0.22);
        --shadow-primary-strong: rgba(255, 117, 140, 0.35);

        --section-light: #f7f7f7;
    }

    /* Ưu tiên body[data-theme] (cách ổn định nhất) */
    body[data-theme="nature"],
    html[data-theme="nature"] body {
        --primary-color: #10b981;     /* emerald */
        --accent-2: #06b6d4;          /* cyan/teal */

        --bg-main: #f0fdf4;
        --hero-bg: #ecfdf5;

        --card-bg: #ffffff;
        --text-color: #1e293b;

        --border-soft: rgba(0,0,0,0.08);

        --pill-hover-bg: rgba(16,185,129,0.10);
        --shadow-primary: rgba(16, 185, 129, 0.20);
        --shadow-primary-strong: rgba(16, 185, 129, 0.33);

        --section-light: #ecfdf5;
    }

    /* Nền tổng trang (để chắc chắn không bị Bulma đè) */
    html, body { background: var(--bg-main) !important; }
    body { color: var(--text-color) !important; }

    /* =============================
       OVERRIDE BULMA THEO THEME
    ============================== */

    /* Button primary theo theme */
    .button.is-primary {
        background: linear-gradient(90deg, var(--primary-color), var(--accent-2)) !important;
        border-color: transparent !important;
        transition: all 0.25s ease;
        font-weight: 700;
    }
    .button.is-primary:hover {
        filter: brightness(0.98);
        box-shadow: 0 6px 18px var(--shadow-primary-strong);
        transform: translateY(-1px);
    }

    .has-text-primary-custom { color: var(--primary-color) !important; }

    /* Tag Bulma (mặc định xanh) -> đổi theo theme */
    .tag.is-primary {
        background-color: color-mix(in srgb, var(--primary-color) 18%, white) !important;
        color: var(--primary-color) !important;
        border: 1px solid color-mix(in srgb, var(--primary-color) 25%, white) !important;
        font-weight: 700;
    }

    /* Hero background theo theme */
    .hero-bg {
        background-color: var(--hero-bg) !important;
        position: relative;
        overflow: hidden;
    }

    /* Card */
    .product-card {
        border-radius: 20px;
        border: none;
        background: var(--card-bg);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        height: 100%;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 45px var(--shadow-primary);
    }
    .card-image img {
        object-fit: cover;
        height: 280px;
        width: 100%;
        display: block;
    }

    /* Category Pill */
    .category-pill {
        border-radius: 50px;
        padding: 10px 25px;
        border: 1px solid var(--border-soft);
        cursor: pointer;
        transition: all 0.25s ease;
        background: var(--card-bg);
        font-weight: 600;
        color: #555;
        user-select: none;
    }
    .category-pill:hover {
        background-color: var(--pill-hover-bg);
        color: var(--primary-color);
        transform: translateY(-1px);
    }
    .category-pill.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 8px 18px var(--shadow-primary-strong);
    }

    /* Section light của Bulma -> đổi theo theme */
    .has-background-light {
        background: var(--section-light) !important;
    }

    .search-container { position: relative; z-index: 10; }

    .social-icon {
        font-size: 24px;
        color: var(--primary-color);
        margin-right: 15px;
        transition: 0.2s;
    }
    .social-icon:hover { transform: scale(1.15); }

    .map-box {
        border-radius: 20px;
        overflow: hidden;
        border: 4px solid var(--card-bg);
        box-shadow: 0 10px 30px rgba(0,0,0,0.10);
    }

    /* Fix Isotope spacing */
    .product-item { padding-bottom: 1.5rem; }

    /* =============================
       CSS CHO PHẦN FEEDBACK
    ============================== */
    .testimonial-section {
        background-color: var(--section-light);
        padding: 4rem 1.5rem;
    }
    
    .feedback-card {
        background-color: var(--card-bg);
        border-radius: 20px;
        padding: 2rem;
        height: 100%;
        border: 1px solid var(--border-soft);
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        position: relative;
    }

    .feedback-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px var(--shadow-primary);
        border-color: var(--primary-color);
    }

    .feedback-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-color);
        margin-bottom: 1rem;
    }

    .quote-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 3rem;
        color: var(--primary-color);
        opacity: 0.1;
        font-family: serif;
    }

    /* =============================
       CSS BACK TO TOP BUTTON
    ============================== */
    #btn-back-to-top {
        position: fixed;
        bottom: 30px;
        left: 30px;
        z-index: 999;
        width: 50px;
        height: 50px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        
        /* Ẩn mặc định */
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: all 0.4s ease;
    }

    #btn-back-to-top.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    #btn-back-to-top:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px var(--shadow-primary-strong);
    }
</style>

<?php if (!$isAdmin): ?>
<section class="hero is-medium hero-bg">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-vcentered">
                <div class="column is-6 animate__animated animate__fadeInLeft">
                    <span class="tag is-primary is-light is-rounded is-medium mb-4">✨ Ưu đãi 30% cho khách hàng mới</span>
                    <h1 class="title is-1 is-size-2-mobile" style="font-family: 'Playfair Display', serif;">
                        Gói trọn <span class="has-text-primary-custom">Yêu Thương</span><br>vào từng nhành hoa
                    </h1>
                    <p class="subtitle is-5 has-text-grey my-5">
                        Sứ mệnh của FlowerShop là giúp bạn truyền tải thông điệp ý nghĩa nhất qua vẻ đẹp tinh khôi của hoa.
                    </p>
                    <div class="buttons">
                        <a href="#product-section" class="button is-primary is-rounded is-medium">🛍️ Mua Sắm Ngay</a>
                    </div>
                </div>
                <div class="column is-6 has-text-centered animate__animated animate__zoomIn">
                    <figure class="image is-4by3">
                        <img src="https://images.unsplash.com/photo-1526047932273-341f2a7631f9?q=80&w=1000&auto=format&fit=crop"
                             alt="Flower Banner"
                             style="border-radius: 50px; box-shadow: 0 20px 50px rgba(0,0,0,0.1);">
                    </figure>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php 
$marginTopStyle = $isAdmin ? 'margin-top: 120px; padding-bottom: 20px;' : 'margin-top: -30px;'; 
?>

<div class="container search-container" style="<?= $marginTopStyle ?>">
    <?php if ($isAdmin): ?>
        <div class="has-text-centered mb-5"></div>
    <?php endif; ?>

    <div class="columns is-centered">
        <div class="column is-8">
            <form action="<?= BASE_URL ?>" method="GET">
                <div class="field has-addons">
                    <div class="control is-expanded has-icons-left">
                        <input class="input is-rounded is-medium" type="text" name="q"
                               placeholder="Bạn tìm hoa gì? (Hồng, Lan, Cúc...)"
                               value="<?= htmlspecialchars($keyword) ?>">
                        <span class="icon is-small is-left"><i class="fas fa-search"></i></span>
                    </div>
                    <div class="control">
                        <button class="button is-primary is-rounded is-medium">Tìm kiếm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="is-flex is-justify-content-center is-flex-wrap-wrap mt-5 filter-button-group" style="gap: 15px;">
        <div class="category-pill active" data-filter="*">🌸 Tất Cả</div>
        <div class="category-pill" data-filter="Sinh Nhật">🎂 Sinh Nhật</div>
        <div class="category-pill" data-filter="Lễ Cưới">💍 Lễ Cưới</div>
        <div class="category-pill" data-filter="Kỉ Niệm">🧸 Kỉ Niệm</div>
        <div class="category-pill" data-filter="Khai Trương">🏢 Khai Trương</div>
    </div>
</div>

<section id="product-section" class="section">
    <div class="container">
        <div class="has-text-centered mb-6">
            <p class="has-text-primary-custom is-uppercase has-text-weight-bold is-size-7">Bộ sưu tập hoa</p>
            <h2 class="title is-2">Gợi Ý Cho Bạn Hôm Nay</h2>
        </div>

        <div class="columns is-multiline" id="product-grid">
            <?php if (empty($products)): ?>
                <div class="column is-12 has-text-centered py-6">
                    <div class="is-size-1">🥀</div>
                    <p class="has-text-grey">Không tìm thấy sản phẩm nào.</p>
                    <a href="<?= BASE_URL ?>" class="button is-text">Xóa tìm kiếm</a>
                </div>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <div class="column is-6-tablet is-3-desktop product-item"
                         data-category="<?= htmlspecialchars((string)($p['category_name'] ?? '')) ?>">
                        <div class="product-card">
                            <div class="card-image is-relative">
                                <a href="<?= BASE_URL ?>product/detail/<?= $p['id'] ?>">
                                    <figure class="image">
                                        <img src="<?= !empty($p['image']) ? BASE_URL . $p['image'] : 'https://via.placeholder.com/300x300?text=No+Image' ?>"
                                             alt="flower">
                                    </figure>
                                </a>
                                <span class="tag is-primary is-light" style="position: absolute; top: 10px; left: 10px; border-radius: 10px;">
                                    <?= htmlspecialchars((string)($p['category_name'] ?? 'Hoa')) ?>
                                </span>
                            </div>
                            <div class="card-content has-text-centered">
                                <h3 class="title is-5 mb-2">
                                    <a href="<?= BASE_URL ?>product/detail/<?= $p['id'] ?>" class="has-text-dark">
                                        <?= htmlspecialchars((string)$p['name']) ?>
                                    </a>
                                </h3>
                                <p class="title is-4 has-text-primary-custom mb-4">
                                    <?= number_format((float)$p['price'], 0, ',', '.') ?> đ
                                </p>
                                <div class="buttons is-centered are-small">
                                    <?php if (!$isAdmin): ?>
                                        <a href="<?= $isLoggedIn ? BASE_URL . "product/addToCart/" . $p['id'] : BASE_URL . "auth/login" ?>"
                                           class="button is-primary is-rounded is-outlined">🛒 Thêm</a>
                                    <?php endif; ?>

                                    <?php if ($isAdmin): ?>
                                        <a href="<?= BASE_URL ?>product/edit/<?= $p['id'] ?>" class="button is-warning is-rounded">✏️ Sửa</a>
                                    <?php else: ?>
                                        <a href="<?= BASE_URL ?>product/detail/<?= $p['id'] ?>" class="button is-light is-rounded">👁️ Xem</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!$isAdmin): ?>
    <section class="section has-background-light">
        <div class="container">
            <div class="columns">
                <div class="column is-7">
                    <h3 class="title is-4 mb-4">📍 Vị trí cửa hàng</h3>
                    <div class="map-box">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.1264383120614!2d106.71230131474898!3d10.801614992304564!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317528a45951a741%3A0x2f345f7470f1a141!2zS0hVIFbhu7BDIEjhu5IgQ0jDjCBNSU5I!5e0!3m2!1svi!2s!4v1634567890123"
                                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="column is-5">
                    <h3 class="title is-4 mb-4">📱 Kết nối với chúng tôi</h3>
                    <div class="box" style="border-radius: 20px;">
                        <p class="mb-4">Theo dõi FlowerShop để nhận thông tin ưu đãi mới nhất hằng ngày.</p>
                        <div class="is-flex mb-4">
                            <a href="https://www.facebook.com/duwwduyydz" class="social-icon"><i class="fab fa-facebook"></i></a>
                            <a href="https://www.instagram.com/_teddybear31/?hl=en" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.tiktok.com/@duwwduyy" class="social-icon"><i class="fab fa-tiktok"></i></a>
                            <a href="https://www.youtube.com/" class="social-icon"><i class="fab fa-youtube"></i></a>
                        </div>
                        <hr>
                        <p class="is-size-7"><strong>Hotline:</strong> 0915 136 743 <br><strong>Email:</strong> ducduy@flowershop.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="section has-background-white">
        <div class="container">
            <div class="columns">
                <div class="column is-4">
                    <div class="box has-text-centered py-6">
                        <span class="is-size-1">🌿</span>
                        <h4 class="title is-4 mt-4">Hoa Tươi Mỗi Ngày</h4>
                        <p class="has-text-grey">Nhập mới mỗi sáng từ Đà Lạt.</p>
                    </div>
                </div>
                <div class="column is-4">
                    <div class="box has-text-centered py-6">
                        <span class="is-size-1">🚀</span>
                        <h4 class="title is-4 mt-4">Giao Hàng Siêu Tốc</h4>
                        <p class="has-text-grey">Giao trong 2h nội thành.</p>
                    </div>
                </div>
                <div class="column is-4">
                    <div class="box has-text-centered py-6">
                        <span class="is-size-1">🎨</span>
                        <h4 class="title is-4 mt-4">Thiết Kế Theo Yêu Cầu</h4>
                        <p class="has-text-grey">Florist chuyên nghiệp thực hiện.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonial-section">
        <div class="container">
            <div class="has-text-centered mb-6">
                <span class="tag is-primary is-light is-rounded is-medium mb-2">💌 Love Stories</span>
                <h2 class="title is-2">Khách Hàng Nói Gì Về Chúng Tôi?</h2>
                <p class="subtitle is-6 has-text-grey">Hơn 10.000 khách hàng đã tin tưởng lựa chọn FlowerShop</p>
            </div>

            <div class="columns">
                <div class="column is-4">
                    <div class="feedback-card">
                        <span class="quote-icon">❝</span>
                        <div class="is-flex is-align-items-center mb-4">
                            <figure class="image is-64x64 mr-3">
                                <img class="is-rounded feedback-avatar" src="https://cdn.tienphong.vn/images/a6bf4f60924201126af6849ca45a3980233e23f03ef3498b951a7cad48f2cc3dc9ecc4de1bda431fec8abb99e453b056ba58842243581e9b11829b47dd076e6a4d693419db2695b8deb3f1e1e812ef1853167410c8042e295100f4dc6991a0e2013bd205c97fd5aef7ddf19075048ee8/472256571-1167112814781105-1579606309934709000-n-2478-4557.jpg" alt="Khách hàng">
                            </figure>
                            <div>
                                <p class="title is-5 mb-1">Sơn Tùng</p>
                               
                                <p class="is-size-7 has-text-warning">⭐⭐⭐⭐⭐</p>
                            </div>
                        </div>
                        <p class="is-italic has-text-grey-dark">
                            "Mình đặt bó hoa hồng tặng mẹ nhân ngày sinh nhật, hoa tươi và gói rất đẹp. Mẹ mình thích lắm, cảm ơn shop đã giao hàng đúng hẹn!"
                        </p>
                    </div>
                </div>

                <div class="column is-4">
                    <div class="feedback-card">
                        <span class="quote-icon">❝</span>
                        <div class="is-flex is-align-items-center mb-4">
                            <figure class="image is-64x64 mr-3">
                                <img class="is-rounded feedback-avatar" src="https://scontent.fsgn8-3.fna.fbcdn.net/v/t39.30808-6/556031133_2051264655684951_7576384608420635110_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=PCxRq2AxVyoQ7kNvwHPPYKu&_nc_oc=AdmD59P36X8dp3aOgIpiOUXOo5Ijg2jh8Y1WA2RdWcPfDtvjmgQjmkNalxedM7oY_RwUVe-qVjA129Q5NLei960x&_nc_zt=23&_nc_ht=scontent.fsgn8-3.fna&_nc_gid=qUTAw99ezXjqRdJ7L34kTg&oh=00_AfovqkyuFjvXHY5ksDNz4A_E3v3jw_oHE_pNa33CNuqeMg&oe=695D24D7" alt="Khách hàng">
                            </figure>
                            <div>
                                <p class="title is-5 mb-1">Trường Trãi</p>
                              
                                <p class="is-size-7 has-text-warning">⭐⭐⭐⭐⭐</p>
                            </div>
                        </div>
                        <p class="is-italic has-text-grey-dark">
                            "Dịch vụ 2H siêu tốc thật sự cứu cánh cho mình. Hoa Lan hồ điệp rất sang trọng, đối tác của mình khen nức nở. Sẽ ủng hộ dài dài."
                        </p>
                    </div>
                </div>

                <div class="column is-4">
                    <div class="feedback-card">
                        <span class="quote-icon">❝</span>
                        <div class="is-flex is-align-items-center mb-4">
                            <figure class="image is-64x64 mr-3">
                                <img class="is-rounded feedback-avatar" src="https://scontent.fsgn8-3.fna.fbcdn.net/v/t39.30808-6/493326487_1295128775512314_755349893340339622_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=a5f93a&_nc_ohc=-v_O3bQuVtEQ7kNvwEcEwnR&_nc_oc=Adk9yfWTS3wvAvE_7ynQ5HGPF_47QvGNV9WbpdV4Fapn8nS_4a3Hyuj4nfvANOLDB_bwdPvaZN_RYcsa4NRfGw1d&_nc_zt=23&_nc_ht=scontent.fsgn8-3.fna&_nc_gid=CuXL51VPTgq9rc-AXw0Dug&oh=00_AfpP3i2SuCeJ9GAGezixgGgJxhdY3UyBJ-2AJCxC_j2n7g&oe=695D062A" alt="Khách hàng">
                            </figure>
                            <div>
                                <p class="title is-5 mb-1">Bùi Trang</p>
                        
                                <p class="is-size-7 has-text-warning">⭐⭐⭐⭐⭐</p>
                            </div>
                        </div>
                        <p class="is-italic has-text-grey-dark">
                            "Mình thích cách nhân viên tư vấn, rất nhiệt tình và gu thẩm mỹ tốt. Theme shop màu hồng xinh xỉu, đặt hoa ở đây cảm giác rất yên tâm."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<button id="btn-back-to-top" class="button is-primary is-rounded" title="Lên đầu trang">
    <span class="icon">
        <i class="fas fa-arrow-up"></i>
    </span>
</button>

<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
<script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var grid = document.querySelector('#product-grid');

    if (grid) {
        imagesLoaded(grid, function() {
            var iso = new Isotope(grid, {
                itemSelector: '.product-item',
                layoutMode: 'fitRows',
                percentPosition: true
            });

            var filterGroup = document.querySelector('.filter-button-group');
            if (filterGroup) {
                filterGroup.addEventListener('click', function(event) {
                    if (!event.target.classList.contains('category-pill')) return;

                    var buttons = filterGroup.querySelectorAll('.category-pill');
                    buttons.forEach(btn => btn.classList.remove('active'));
                    event.target.classList.add('active');

                    var filterValue = event.target.getAttribute('data-filter');

                    iso.arrange({
                        filter: function(itemElem) {
                            if (filterValue === '*') return true;
                            var itemCat = itemElem.getAttribute('data-category');
                            return itemCat && itemCat.toLowerCase().trim() === filterValue.toLowerCase().trim();
                        }
                    });
                });
            }
        });
    }

    /* =============================
       LOGIC BACK TO TOP
    ============================== */
    const btnBackToTop = document.getElementById('btn-back-to-top');

    if (btnBackToTop) {
        // 1. Lắng nghe sự kiện cuộn chuột
        window.addEventListener('scroll', function() {
            // Nếu cuộn xuống quá 300px thì hiện nút
            if (window.scrollY > 300) {
                btnBackToTop.classList.add('show');
            } else {
                btnBackToTop.classList.remove('show');
            }
        });

        // 2. Xử lý khi click vào nút
        btnBackToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // Cuộn mượt mà
            });
        });
    }
});
</script>

<?php include $rootViews . '/product/shares/footer_home.php'; ?>