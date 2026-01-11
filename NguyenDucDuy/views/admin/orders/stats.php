<?php
// views/admin/orders/stats.php

// 1. BẢO VỆ DỮ LIỆU: Khởi tạo giá trị mặc định nếu Controller quên truyền
$labels = $labels ?? []; 
$data = $data ?? [];     
$topProducts = $topProducts ?? []; 

// 2. Include Header
include dirname(__DIR__, 2) . '/product/shares/header.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = { prefix: 'tw-', corePlugins: { preflight: false } }
</script>

<div class="container tw-py-10">
    <div class="tw-mb-8 tw-flex tw-justify-between tw-items-end">
        <div>
            <h2 class="tw-font-bold tw-text-3xl tw-text-gray-800 tw-mb-2">📊 Thống Kê Doanh Thu</h2>
            <p class="tw-text-gray-500 tw-mb-0">Tổng quan hiệu quả kinh doanh của cửa hàng.</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>order/index" class="btn btn-outline-dark tw-rounded-xl tw-font-bold tw-shadow-sm">
                ⬅ Quản lý đơn hàng
            </a>
        </div>
    </div>

    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-6">
        
        <div class="lg:tw-col-span-2">
            <div class="tw-bg-white tw-border tw-border-gray-100 tw-shadow-sm tw-rounded-2xl tw-p-6 tw-h-full">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-6">
                    <h5 class="tw-font-bold tw-text-gray-700 tw-mb-0">Doanh thu 30 ngày gần nhất</h5>
                    <span class="tw-text-xs tw-bg-blue-50 tw-text-blue-600 tw-px-3 tw-py-1 tw-rounded-full tw-font-bold">Biểu đồ cột</span>
                </div>
                
                <div class="tw-relative tw-w-full" style="height: 400px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="tw-flex tw-flex-col tw-gap-6">
            
            <div class="tw-bg-gradient-to-br tw-from-blue-600 tw-to-blue-500 tw-text-white tw-p-6 tw-rounded-2xl tw-shadow-lg tw-shadow-blue-200">
                <div class="tw-flex tw-items-center tw-gap-3 tw-mb-2">
                    <div class="tw-p-2 tw-bg-white/20 tw-rounded-lg">💰</div>
                    <h6 class="tw-uppercase tw-text-xs tw-font-bold tw-opacity-90 tw-tracking-widest tw-mb-0">Tổng thực thu</h6>
                </div>
                <div class="tw-text-3xl tw-font-bold tw-mt-2">
                    <?= number_format(array_sum($data), 0, ',', '.') ?> đ
                </div>
                <p class="tw-text-xs tw-mt-3 tw-opacity-80 italic mb-0">
                    * Chỉ tính đơn hàng "Hoàn thành"
                </p>
            </div>

            <div class="tw-bg-white tw-border tw-border-gray-100 tw-p-6 tw-rounded-2xl tw-shadow-sm tw-flex-grow">
                <h6 class="tw-font-bold tw-text-gray-800 tw-mb-4 tw-border-b tw-pb-2">🏆 Top 5 Bán Chạy</h6>
                
                <?php if (!empty($topProducts)): ?>
                    <div class="tw-relative tw-h-[250px] tw-w-full tw-flex tw-justify-center">
                        <canvas id="topProductChart"></canvas>
                    </div>
                <?php else: ?>
                    <div class="tw-text-center tw-text-gray-400 tw-py-10">
                        <p>Chưa có dữ liệu sản phẩm</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- 1. BIỂU ĐỒ DOANH THU (BAR CHART) ---
        const labels = <?= json_encode($labels) ?>;
        const revenueData = <?= json_encode($data) ?>;
        
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        
        // Tạo màu gradient đẹp
        const gradient = ctxRevenue.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.9)'); // Xanh đậm
        gradient.addColorStop(1, 'rgba(147, 197, 253, 0.4)'); // Xanh nhạt

        new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu',
                    data: revenueData,
                    backgroundColor: gradient,
                    borderRadius: 6,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f3f4f6' },
                        ticks: {
                            callback: function(value) {
                                if(value >= 1000000) return (value/1000000) + 'Tr';
                                return (value/1000) + 'k';
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // --- 2. BIỂU ĐỒ TOP SẢN PHẨM (DOUGHNUT CHART) ---
        const topProducts = <?= json_encode($topProducts) ?>;
        
        if (topProducts.length > 0) {
            const productLabels = topProducts.map(item => item.name);
            const productData = topProducts.map(item => item.total_sold);

            const ctxProduct = document.getElementById('topProductChart').getContext('2d');
            
            new Chart(ctxProduct, {
                type: 'doughnut',
                data: {
                    labels: productLabels,
                    datasets: [{
                        data: productData,
                        backgroundColor: [
                            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
                        ],
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 11 } }
                        }
                    }
                }
            });
        }
    });
</script>

<?php include dirname(__DIR__, 2) . '/product/shares/footer.php'; ?>