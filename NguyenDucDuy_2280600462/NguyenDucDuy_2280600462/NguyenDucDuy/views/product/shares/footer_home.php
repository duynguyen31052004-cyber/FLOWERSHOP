<footer class="tw-bg-gray-900 tw-text-gray-300 tw-pt-20 tw-pb-10 tw-mt-auto">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <div class="tw-text-3xl tw-font-serif tw-font-bold tw-text-white tw-mb-6">
                    🌸 FlowerShop
                </div>
                <p class="tw-leading-relaxed tw-mb-6 tw-text-gray-400">
                    Nơi cảm xúc thăng hoa cùng vẻ đẹp của thiên nhiên. Chúng tôi cam kết mang đến những sản phẩm hoa tươi chất lượng nhất, giúp bạn trao gửi yêu thương trọn vẹn.
                </p>
                <div class="tw-flex tw-gap-3">
                    <a href="#" class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-white/10 tw-flex tw-items-center tw-justify-center tw-text-white hover:tw-bg-primary hover:tw-text-white tw-transition-colors tw-no-underline">F</a>
                    <a href="#" class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-white/10 tw-flex tw-items-center tw-justify-center tw-text-white hover:tw-bg-primary hover:tw-text-white tw-transition-colors tw-no-underline">I</a>
                    <a href="#" class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-white/10 tw-flex tw-items-center tw-justify-center tw-text-white hover:tw-bg-primary hover:tw-text-white tw-transition-colors tw-no-underline">T</a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h4 class="tw-text-lg tw-font-bold tw-text-white tw-mb-6">Khám Phá</h4>
                <ul class="list-unstyled tw-space-y-3">
                    <li><a href="#" class="tw-text-gray-400 hover:tw-text-primary tw-transition-colors tw-no-underline">Về chúng tôi</a></li>
                    <li><a href="<?= BASE_URL ?>product/list" class="tw-text-gray-400 hover:tw-text-primary tw-transition-colors tw-no-underline">Sản phẩm</a></li>
                    <li><a href="#" class="tw-text-gray-400 hover:tw-text-primary tw-transition-colors tw-no-underline">Câu chuyện hoa</a></li>
                    <li><a href="#" class="tw-text-gray-400 hover:tw-text-primary tw-transition-colors tw-no-underline">Liên hệ</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h4 class="tw-text-lg tw-font-bold tw-text-white tw-mb-6">Liên Hệ</h4>
                <ul class="list-unstyled tw-space-y-4">
                    <li class="tw-flex tw-gap-3 tw-items-start">
                        <span class="tw-text-primary tw-mt-1">📍</span>
                        <span>122 Bùi Đình Túy, Bình Thạnh, TP.HCM</span>
                    </li>
                    <li class="tw-flex tw-gap-3 tw-items-center">
                        <span class="tw-text-primary">📞</span>
                        <span>0915 136 743</span>
                    </li>
                    <li class="tw-flex tw-gap-3 tw-items-center">
                        <span class="tw-text-primary">✉️</span>
                        <span>ducduy@flowershop.vn</span>
                    </li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6">
                 <h4 class="tw-text-lg tw-font-bold tw-text-white tw-mb-6">Đăng Ký</h4>
                 <p class="tw-text-sm tw-mb-4 tw-text-gray-400">Nhận thông báo về các mẫu hoa mới và ưu đãi đặc biệt.</p>
                 <div class="tw-relative">
                    <input type="email" placeholder="Email của bạn..." class="form-control tw-rounded-full tw-py-3 tw-px-5 tw-bg-white/10 tw-border-none tw-text-white placeholder:tw-text-gray-500 focus:tw-ring-2 focus:tw-ring-primary focus:tw-bg-white/20">
                    <button class="tw-absolute tw-right-1.5 tw-top-1.5 tw-bg-primary tw-w-9 tw-h-9 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-white hover:tw-bg-white hover:tw-text-primary tw-transition-colors tw-border-none">
                        ➝
                    </button>
                 </div>
            </div>
        </div>

        <hr class="tw-border-gray-800 tw-my-10">

        <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-items-center tw-text-sm tw-text-gray-500">
            <div class="tw-mb-4 md:tw-mb-0">
                &copy; <?= date('Y') ?> FlowerShop. All rights reserved.
            </div>
            <div class="tw-flex tw-gap-6">
                <a href="#" class="tw-text-gray-500 hover:tw-text-white tw-no-underline">Điều khoản</a>
                <a href="#" class="tw-text-gray-500 hover:tw-text-white tw-no-underline">Chính sách bảo mật</a>
            </div>
        </div>
    </div>
    
</footer>
</body>
</html>