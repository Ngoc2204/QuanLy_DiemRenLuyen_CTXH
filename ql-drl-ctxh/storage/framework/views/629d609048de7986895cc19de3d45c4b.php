
<?php $__env->startSection('title', 'Chi tiết Thông Báo'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --primary: #667eea;
        --secondary: #764ba2;
        --accent: #f5576c;
        --success: #10b981;
    }

    .breadcrumb {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .notification-detail {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border-left: 5px solid var(--primary);
    }

    .notification-detail.CTXH {
        border-left-color: var(--accent);
    }

    .notification-detail.DIEM {
        border-left-color: var(--success);
    }

    .detail-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #f0e7ff 100%);
        padding: 2rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: start;
    }

    .detail-header.CTXH {
        background: linear-gradient(135deg, #fff5f7 0%, #ffe5eb 100%);
    }

    .detail-header.DIEM {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }

    .header-content h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .header-info {
        display: flex;
        gap: 2rem;
        margin-top: 1rem;
        font-size: 0.95rem;
        color: #64748b;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge {
        display: inline-block;
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .badge-primary {
        background: rgba(102, 126, 234, 0.15);
        color: #667eea;
    }

    .badge-danger {
        background: rgba(245, 87, 108, 0.15);
        color: #f5576c;
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }

    .detail-body {
        padding: 2rem;
    }

    .detail-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--primary);
        font-size: 1.3rem;
    }

    .content-text {
        color: #555;
        line-height: 1.8;
        font-size: 1rem;
    }

    .notification-meta {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid #e5e7eb;
    }

    .meta-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .meta-item:last-child {
        border-bottom: none;
    }

    .meta-label {
        font-weight: 600;
        color: #64748b;
    }

    .meta-value {
        color: #2d3748;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }

    .btn-secondary {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-secondary:hover {
        background: rgba(102, 126, 234, 0.1);
    }

    @media (max-width: 768px) {
        .detail-header {
            flex-direction: column;
        }

        .header-info {
            flex-direction: column;
            gap: 1rem;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" style="max-width: 900px; margin: 0 auto; padding: 2rem 1rem;">

    
    <nav class="breadcrumb">
        <a href="<?php echo e(route('sinhvien.home')); ?>" style="color: #667eea; text-decoration: none;">
            <i class="fas fa-home me-1"></i>Trang chủ
        </a>
        <span style="margin: 0 0.5rem; color: #cbd5e1;">/</span>
        <a href="<?php echo e(route('sinhvien.tintuc.index')); ?>" style="color: #667eea; text-decoration: none;">
            <i class="fas fa-bell me-1"></i>Thông báo
        </a>
        <span style="margin: 0 0.5rem; color: #cbd5e1;">/</span>
        <span style="color: #64748b;">Chi tiết</span>
    </nav>

    
    <div class="notification-detail <?php echo e($thongBao->Loai); ?>">
        
        
        <div class="detail-header <?php echo e($thongBao->Loai); ?>">
            <div class="header-content">
                <h2>
                    <?php if($thongBao->Loai == 'DRL'): ?>
                        <i class="fas fa-star" style="color: #667eea;"></i>
                    <?php elseif($thongBao->Loai == 'CTXH'): ?>
                        <i class="fas fa-heart" style="color: #f5576c;"></i>
                    <?php elseif($thongBao->Loai == 'DIEM'): ?>
                        <i class="fas fa-medal" style="color: #10b981;"></i>
                    <?php else: ?>
                        <i class="fas fa-clock" style="color: #f59e0b;"></i>
                    <?php endif; ?>
                    <?php echo e($thongBao->TieuDe); ?>

                </h2>
                <div class="header-info">
                    <div class="info-item">
                        <i class="fas fa-calendar-alt"></i>
                        <?php echo e(\Carbon\Carbon::parse($thongBao->ThoiGian)->format('H:i, d/m/Y')); ?>

                    </div>
                    <div class="info-item">
                        <i class="fas fa-tag"></i>
                        <span class="badge <?php echo e('badge-' . strtolower($thongBao->Loai)); ?>">
                            <?php if($thongBao->Loai == 'DRL'): ?>
                                Rèn Luyện
                            <?php elseif($thongBao->Loai == 'CTXH'): ?>
                                Công Tác Xã Hội
                            <?php elseif($thongBao->Loai == 'DIEM'): ?>
                                Điểm Rèn Luyện
                            <?php else: ?>
                                Nhắc Nhở
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            
            <div>
                <?php if($thongBao->TrangThai == 'Đã duyệt' || $thongBao->TrangThai == 'Đã chốt'): ?>
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(16, 185, 129, 0.15); color: #10b981; padding: 0.75rem 1.5rem; border-radius: 20px; font-weight: 600;">
                        <i class="fas fa-check-circle"></i>
                        <?php echo e($thongBao->TrangThai); ?>

                    </span>
                <?php elseif($thongBao->TrangThai == 'Bị từ chối'): ?>
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(239, 68, 68, 0.15); color: #ef4444; padding: 0.75rem 1.5rem; border-radius: 20px; font-weight: 600;">
                        <i class="fas fa-times-circle"></i>
                        <?php echo e($thongBao->TrangThai); ?>

                    </span>
                <?php elseif($thongBao->TrangThai == 'Sắp diễn ra'): ?>
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(245, 158, 11, 0.15); color: #f59e0b; padding: 0.75rem 1.5rem; border-radius: 20px; font-weight: 600;">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo e($thongBao->TrangThai); ?>

                    </span>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="detail-body">
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Nội dung
                </div>
                <div class="content-text">
                    <?php echo nl2br(e($thongBao->NoiDung)); ?>

                </div>
            </div>

            
            <div class="notification-meta">
                <div class="meta-item">
                    <span class="meta-label">Loại thông báo:</span>
                    <span class="meta-value">
                        <?php if($thongBao->Loai == 'DRL'): ?>
                            Hoạt động Rèn Luyện
                        <?php elseif($thongBao->Loai == 'CTXH'): ?>
                            Hoạt động Công Tác Xã Hội
                        <?php elseif($thongBao->Loai == 'DIEM'): ?>
                            Cập nhật Điểm
                        <?php else: ?>
                            Nhắc Nhở
                        <?php endif; ?>
                    </span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Mã thông báo:</span>
                    <span class="meta-value"><?php echo e($thongBao->Ma); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Trạng thái:</span>
                    <span class="meta-value"><?php echo e($thongBao->TrangThai); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Thời gian nhận:</span>
                    <span class="meta-value"><?php echo e(\Carbon\Carbon::parse($thongBao->ThoiGian)->format('H:i:s, d/m/Y')); ?></span>
                </div>
            </div>

            
            <div class="action-buttons">
                <a href="<?php echo e(route('sinhvien.tintuc.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại danh sách
                </a>
                
                <?php if($thongBao->Loai == 'DRL' || $thongBao->Loai == 'CTXH'): ?>
                    <a href="<?php echo e(route('sinhvien.thongbao_hoatdong')); ?>" class="btn btn-primary">
                        <i class="fas fa-eye"></i>
                        Xem hoạt động
                    </a>
                <?php elseif($thongBao->Loai == 'DIEM'): ?>
                    <a href="<?php echo e(route('sinhvien.diem_ren_luyen')); ?>" class="btn btn-primary">
                        <i class="fas fa-chart-line"></i>
                        Xem chi tiết điểm
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/tintuc/show.blade.php ENDPATH**/ ?>