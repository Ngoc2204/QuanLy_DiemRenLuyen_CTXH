

<?php $__env->startSection('title', 'Bảng điều khiển'); ?>
<?php $__env->startSection('page_title', 'Bảng điều khiển'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .stats-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
    }
    
    .stats-card.primary {
        --gradient-start: #3b82f6;
        --gradient-end: #2563eb;
    }
    
    .stats-card.success {
        --gradient-start: #10b981;
        --gradient-end: #059669;
    }
    
    .stats-card.warning {
        --gradient-start: #f59e0b;
        --gradient-end: #d97706;
    }
    
    .stats-card.danger {
        --gradient-start: #ef4444;
        --gradient-end: #dc2626;
    }
    
    .stats-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 16px;
    }
    
    .stats-icon.primary {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
    }
    
    .stats-icon.success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #059669;
    }
    
    .stats-icon.warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
    }
    
    .stats-icon.danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
    }
    
    .stats-value {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        margin: 12px 0;
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stats-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .chart-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }
    
    .chart-card .card-header {
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        border-bottom: 2px solid #e5e7eb;
        font-weight: 700;
        font-size: 1.125rem;
        color: #1f2937;
        padding: 1.25rem 1.5rem;
    }
    
    .notification-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .notification-card .card-header {
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        border-bottom: 2px solid #e5e7eb;
        font-weight: 700;
        font-size: 1.125rem;
        color: #1f2937;
        padding: 1.25rem 1.5rem;
    }
    
    .notification-item {
        padding: 1rem 1.5rem;
        border-left: 4px solid #3b82f6;
        background: white;
        margin-bottom: 0.75rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    
    .notification-item:hover {
        background: #f9fafb;
        transform: translateX(4px);
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        font-size: 18px;
    }
    
    .notification-title {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }
    
    .notification-time {
        font-size: 0.75rem;
        color: #9ca3af;
    }
    
    .badge-new {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        font-size: 0.625rem;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Thống kê tổng quan -->
<div class="row g-4 mb-4">
    <div class="col-md-3 animate-fade-in" style="animation-delay: 0.1s">
        <div class="stats-card primary">
            <div class="card-body text-center p-4">
                <div class="stats-icon primary mx-auto">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <p class="stats-label mb-0">Tổng sinh viên</p>
                <div class="stats-value"><?php echo e(number_format($tongSinhVien ?? 0)); ?></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 animate-fade-in" style="animation-delay: 0.2s">
        <div class="stats-card success">
            <div class="card-body text-center p-4">
                <div class="stats-icon success mx-auto">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <p class="stats-label mb-0">Hoạt động DRL</p>
                <div class="stats-value"><?php echo e(number_format($tongHoatDongDRL ?? 0)); ?></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 animate-fade-in" style="animation-delay: 0.3s">
        <div class="stats-card warning">
            <div class="card-body text-center p-4">
                <div class="stats-icon warning mx-auto">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <p class="stats-label mb-0">Hoạt động CTXH</p>
                <div class="stats-value"><?php echo e(number_format($tongHoatDongCTXH ?? 0)); ?></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 animate-fade-in" style="animation-delay: 0.4s">
        <div class="stats-card danger">
            <div class="card-body text-center p-4">
                <div class="stats-icon danger mx-auto">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <p class="stats-label mb-0">Thông báo mới</p>
                <div class="stats-value"><?php echo e(number_format($tongThongBao ?? 0)); ?></div>
            </div>
        </div>
    </div>
    
</div>

<!-- Biểu đồ thống kê -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4 animate-fade-in" style="animation-delay: 0.5s">
        <div class="chart-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-chart-pie me-2"></i>Thống kê Hoạt động DRL</span>
                <span class="badge bg-primary"><?php echo e(date('Y')); ?></span>
            </div>
            <div class="card-body p-4">
                <canvas id="chartDRL" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4 animate-fade-in" style="animation-delay: 0.6s">
        <div class="chart-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-chart-pie me-2"></i>Thống kê hoạt động CTXH</span>
                <span class="badge bg-success"><?php echo e(date('Y')); ?></span>
            </div>
            <div class="card-body p-4">
                <canvas id="chartCTXH" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Thông báo gần đây -->
<div class="animate-fade-in" style="animation-delay: 0.7s">
    <div class="notification-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fa-solid fa-bell me-2"></i>Thông báo gần đây</span>
            <?php if(isset($thongBaoMoi) && count($thongBaoMoi) > 0): ?>
                <span class="badge-new"><?php echo e(count($thongBaoMoi)); ?> mới</span>
            <?php endif; ?>
        </div>
        <div class="card-body p-4">
            <?php if(isset($thongBaoMoi) && $thongBaoMoi->count() > 0): ?>
                <?php $__currentLoopData = $thongBaoMoi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="notification-item d-flex align-items-start">
                    <div class="notification-icon flex-shrink-0 me-3">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="notification-title"><?php echo e($tb->TieuDe); ?></div>
                        <div class="notification-time">
                            <i class="fa-regular fa-clock me-1"></i>
                            
                            <?php if($tb->created_at instanceof \Carbon\Carbon): ?>
                                <?php echo e($tb->created_at->format('d/m/Y H:i')); ?>

                            <?php else: ?>
                                <?php echo e($tb->created_at); ?>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa-regular fa-bell-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Không có thông báo mới</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Cấu hình màu sắc
    const colors = {
        primary: '#3b82f6',
        success: '#10b981',
        warning: '#f59e0b',
        danger: '#ef4444',
        info: '#06b6d4',
        purple: '#8b5cf6'
    };

    const gradients = {
        primary: ['#3b82f6', '#2563eb'],
        success: ['#10b981', '#059669'],
        warning: ['#f59e0b', '#d97706'],
        danger: ['#ef4444', '#dc2626'],
        info: ['#06b6d4', '#0891b2']
    };

    // === SỬA LẠI BIỂU ĐỒ DRL ===
    // Biểu đồ DRL (Doughnut) - Giống hệt CTXH
    const ctxDRL = document.getElementById('chartDRL').getContext('2d');
    new Chart(ctxDRL, {
        type: 'doughnut',
        data: {
            // Lấy labels từ controller
            labels: <?php echo json_encode($labelsDRL ?? ['Hoàn thành', 'Chưa hoàn thành']); ?>,
            datasets: [{
                // Lấy data từ controller
                data: <?php echo json_encode($dataDRL ?? [0, 0]); ?>,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)', // Màu Primary (Hoàn thành)
                    'rgba(245, 158, 11, 0.8)' // Màu Warning (Chưa hoàn thành)
                ],
                borderWidth: 0,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: { size: 13, weight: '600' },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.parsed;
                            const percentage = (total > 0) ? ((value / total) * 100).toFixed(1) : 0;
                            return ' ' + value + ' lượt (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });

    // Biểu đồ CTXH (Doughnut) - Giữ nguyên
    const ctxCTXH = document.getElementById('chartCTXH').getContext('2d');
    new Chart(ctxCTXH, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($labelsCTXH ?? ['Hoàn thành', 'Chưa hoàn thành']); ?>,
            datasets: [{
                data: <?php echo json_encode($dataCTXH ?? [0, 0]); ?>,
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)', // Màu Success (Hoàn thành)
                    'rgba(245, 158, 11, 0.8)' // Màu Warning (Chưa hoàn thành)
                ],
                borderWidth: 0,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: { size: 13, weight: '600' },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.parsed;
                            const percentage = (total > 0) ? ((value / total) * 100).toFixed(1) : 0;
                            return ' ' + value + ' lượt (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/dashboard.blade.php ENDPATH**/ ?>