

<?php $__env->startSection('title', 'Chi tiết Phản hồi'); ?>
<?php $__env->startSection('page_title', 'Chi tiết Phản hồi Sinh viên'); ?>

<?php
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.thongbao.index'), 'title' => 'Phản hồi Sinh viên'],
        ['url' => '#', 'title' => 'Chi tiết'],
    ];
?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <div>
                    <h4 class="mb-1">
                        <i class="fa-solid fa-circle-info text-primary me-2"></i>Chi tiết Phản hồi #<?php echo e($thongbao->MaPhanHoi); ?>

                    </h4>
                    <p class="text-muted mb-0">Xem thông tin chi tiết và trạng thái xử lý phản hồi</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('nhanvien.thongbao.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Danh sách
                    </a>
                    <a href="<?php echo e(route('nhanvien.thongbao.edit', $thongbao->MaPhanHoi)); ?>" class="btn btn-primary">
                        <i class="fa-solid fa-pen-to-square me-2"></i>Cập nhật
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">
                                <i class="fa-solid fa-flag me-1"></i>Trạng thái xử lý
                            </h6>
                            <div>
                                <?php if($thongbao->TrangThai == 'Chưa xử lý'): ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger px-4 py-2 fs-6">
                                        <i class="fa-solid fa-circle-exclamation me-2"></i><?php echo e($thongbao->TrangThai); ?>

                                    </span>
                                <?php elseif($thongbao->TrangThai == 'Đang xử lý'): ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning px-4 py-2 fs-6">
                                        <i class="fa-solid fa-spinner me-2"></i><?php echo e($thongbao->TrangThai); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success border border-success px-4 py-2 fs-6">
                                        <i class="fa-solid fa-circle-check me-2"></i><?php echo e($thongbao->TrangThai); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="status-icon-container">
                                <?php if($thongbao->TrangThai == 'Chưa xử lý'): ?>
                                    <i class="fa-solid fa-hourglass-start fa-3x text-danger opacity-50"></i>
                                <?php elseif($thongbao->TrangThai == 'Đang xử lý'): ?>
                                    <i class="fa-solid fa-gears fa-3x text-warning opacity-50"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-circle-check fa-3x text-success opacity-50"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient text-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-user-graduate me-2"></i>Thông tin Sinh viên
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="info-icon bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fa-solid fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small mb-0">Họ và Tên</label>
                                        <p class="mb-0 fw-semibold"><?php echo e($thongbao->sinhvien->HoTen ?? 'Không tìm thấy thông tin SV'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="info-icon bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fa-solid fa-id-card text-success"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small mb-0">Mã số sinh viên</label>
                                        <p class="mb-0 fw-semibold"><?php echo e($thongbao->MSSV); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="info-icon bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fa-regular fa-calendar text-info"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small mb-0">Ngày gửi</label>
                                        <p class="mb-0 fw-semibold">
                                            <?php echo e($thongbao->NgayGui ? \Carbon\Carbon::parse($thongbao->NgayGui)->format('d/m/Y') : 'N/A'); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="info-icon bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fa-regular fa-clock text-warning"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small mb-0">Thời gian</label>
                                        <p class="mb-0 fw-semibold">
                                            <?php echo e($thongbao->NgayGui ? \Carbon\Carbon::parse($thongbao->NgayGui)->format('H:i:s') : 'N/A'); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-message text-primary me-2"></i>Nội dung Phản hồi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="feedback-content-box">
                        <div class="feedback-quote-icon">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>
                        <div class="feedback-text">
                            <?php echo nl2br(e($thongbao->NoiDung)); ?>

                        </div>
                        <div class="feedback-quote-icon-end">
                            <i class="fa-solid fa-quote-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            
            

            

            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <a href="<?php echo e(route('nhanvien.thongbao.index')); ?>" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="fa-solid fa-arrow-left me-2"></i>Quay lại danh sách
                        </a>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('nhanvien.thongbao.edit', $thongbao->MaPhanHoi)); ?>" class="btn btn-primary btn-lg px-4">
                                <i class="fa-solid fa-pen-to-square me-2"></i>Cập nhật trạng thái
                            </a>
                            <?php if($thongbao->TrangThai == 'Đã phản hồi'): ?>
                            <button class="btn btn-outline-success btn-lg px-4" onclick="window.print()">
                                <i class="fa-solid fa-print me-2"></i>In chi tiết
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.info-item {
    padding: 1rem;
    border-radius: 8px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.info-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.feedback-content-box {
    position: relative;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid #667eea;
    border-radius: 8px;
    padding: 2rem;
    min-height: 150px;
}

.feedback-quote-icon {
    position: absolute;
    top: 10px;
    left: 15px;
    font-size: 2rem;
    color: #667eea;
    opacity: 0.2;
}

.feedback-quote-icon-end {
    position: absolute;
    bottom: 10px;
    right: 15px;
    font-size: 2rem;
    color: #667eea;
    opacity: 0.2;
}

.feedback-text {
    line-height: 1.8;
    color: #495057;
    white-space: pre-wrap;
    word-wrap: break-word;
    font-size: 1.05rem;
    position: relative;
    z-index: 1;
}

.notes-content {
    line-height: 1.8;
    color: #856404;
    background: #fff3cd;
    border-radius: 8px;
    padding: 1rem;
    border-left: 4px solid #ffc107;
}

.status-icon-container {
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.7;
    }
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #667eea, #764ba2);
}

.timeline-item {
    position: relative;
    padding-bottom: 2rem;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 2px currentColor;
}

.timeline-content {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.timeline-content:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.bg-danger-subtle {
    background-color: #f8d7da !important;
}

.bg-warning-subtle {
    background-color: #fff3cd !important;
}

.bg-success-subtle {
    background-color: #d1e7dd !important;
}

.badge {
    font-weight: 500;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #66328b 100%);
}

@media print {
    .btn, .card-header .btn, nav, header {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn {
        width: 100%;
    }
    
    .timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -15px;
        width: 15px;
        height: 15px;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll animation for timeline
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateX(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.timeline-item').forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        item.style.transition = 'all 0.5s ease';
        observer.observe(item);
    });

    // Add tooltips for action buttons
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/thongbao/show.blade.php ENDPATH**/ ?>