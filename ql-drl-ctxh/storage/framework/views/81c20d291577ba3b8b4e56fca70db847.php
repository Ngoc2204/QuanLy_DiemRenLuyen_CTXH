
<?php $__env->startSection('title', 'Quản lý Đăng ký'); ?>

<?php $__env->startPush('styles'); ?>

<style>
    :root {
        --primary: #667eea;
        --secondary: #764ba2;
        --accent: #f5576c;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --gray: #6c757d;
        --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 2.5rem 0;
        margin-bottom: 2rem;
        border-radius: 20px;
    }
    .page-header h3 { color: white; font-weight: 800; margin: 0; }
    .page-header .subtitle { color: rgba(255, 255, 255, 0.9); font-size: 1rem; margin-top: 0.5rem; }

    .custom-tabs {
        background: white; border-radius: 50px; padding: 0.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); display: inline-flex;
        gap: 0.5rem; margin-bottom: 2rem;
    }
    .custom-tabs .nav-link {
        border: 0; border-radius: 50px; padding: 0.75rem 2rem;
        font-weight: 600; color: #64748b; transition: var(--transition);
        display: flex; align-items: center; gap: 0.5rem;
    }
    .custom-tabs .nav-link:hover { color: var(--primary); background: rgba(102, 126, 234, 0.05); }
    .custom-tabs .nav-link.active {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .custom-tabs .nav-link#ctxh-tab.active {
        background: linear-gradient(135deg, var(--accent) 0%, #ff6b9d 100%);
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
    }
    .custom-tabs .badge { font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: 50px; }
    .custom-tabs .nav-link.active .badge { background: rgba(255, 255, 255, 0.3) !important; }

    .activity-card {
        background: white; border-radius: 20px; box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem; overflow: hidden; transition: var(--transition);
    }
    .activity-header {
        padding: 1.5rem; display: flex; justify-content: space-between;
        align-items: center; background: #f8f9fa;
    }
    .activity-type {
        font-weight: 700; padding: 0.5rem 1.25rem; border-radius: 50px;
        font-size: 0.75rem; text-transform: uppercase;
    }
    .activity-type.drl { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; }
    .activity-type.ctxh { background: linear-gradient(135deg, var(--accent) 0%, #ff6b9d 100%); color: white; }
    .activity-points { font-weight: 800; font-size: 1.2rem; color: var(--success); }
    .activity-body { padding: 1.5rem 2rem; }
    .activity-body h4 { font-weight: 700; margin-bottom: 1rem; color: #333; }
    .activity-meta { font-size: 0.9rem; color: #64748b; margin-bottom: 0.75rem; }
    .activity-meta i { margin-right: 8px; color: var(--primary); }
    .activity-meta.ctxh i { color: var(--accent); }
    .activity-footer {
        padding: 1rem 2rem; background: #fafbfc;
        display: flex; justify-content: space-between; align-items: center;
        border-top: 1px solid #e2e8f0;
    }
    .status-badge {
        font-weight: 600; padding: 0.5rem 1rem; border-radius: 50px;
        font-size: 0.85rem;
    }
    .status-badge.cho-duyet { background-color: rgba(245, 158, 11, 0.1); color: #d97706; }
    .status-badge.da-duyet { background-color: rgba(16, 185, 129, 0.1); color: #059669; }
    .status-badge.bi-tu-choi { background-color: rgba(239, 68, 68, 0.1); color: #dc2626; }
    
    .btn-cancel {
        background: var(--danger); color: white; border: none;
        padding: 0.6rem 1.2rem; border-radius: 50px; font-weight: 600;
        font-size: 0.9rem; transition: var(--transition);
    }
    .btn-cancel:hover { background: #dc2626; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3); }
    .btn-cancel:disabled { background: var(--gray); opacity: 0.7; cursor: not-allowed; }

    .empty-state { background: white; border-radius: 20px; padding: 4rem 2rem; text-align: center; box-shadow: var(--card-shadow); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" style="max-width: 1400px; margin: 0 auto; padding: 2rem 1rem;">

    
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="page-header">
        <div class="container">
            <h3><i class="fas fa-tasks me-3"></i>Quản Lý Đăng Ký</h3>
            <p class="subtitle">Theo dõi và hủy các hoạt động bạn đã đăng ký</p>
        </div>
    </div>

    
    <div class="text-center">
        <ul class="nav custom-tabs" id="activityTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="drl-tab" data-bs-toggle="tab" data-bs-target="#drl-content" type="button" role="tab" aria-controls="drl-content" aria-selected="true">
                    <i class="fas fa-star"></i>
                    <span>Đã Đăng Ký DRL</span>
                    <span class="badge bg-light text-dark"><?php echo e($drlRegistrations->count()); ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ctxh-tab" data-bs-toggle="tab" data-bs-target="#ctxh-content" type="button" role="tab" aria-controls="ctxh-content" aria-selected="false">
                    <i class="fas fa-heart"></i>
                    <span>Đã Đăng Ký CTXH</span>
                    <span class="badge bg-light text-dark"><?php echo e($ctxhRegistrations->count()); ?></span>
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="activityTabContent">
        
        
        <div class="tab-pane fade show active" id="drl-content" role="tabpanel" aria-labelledby="drl-tab">
            <?php $__empty_1 = true; $__currentLoopData = $drlRegistrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $activity = $registration->hoatdong; // Lấy thông tin hoạt động
                    
                    // Logic kiểm tra Hủy
                    $canCancel = (!$activity->ThoiHanHuy || $currentTime->lt($activity->ThoiHanHuy));
                    
                    // Logic Badge Trạng Thái
                    $statusClass = '';
                    if ($registration->TrangThaiDangKy == 'Chờ duyệt') $statusClass = 'cho-duyet';
                    elseif ($registration->TrangThaiDangKy == 'Đã duyệt') $statusClass = 'da-duyet';
                    elseif ($registration->TrangThaiDangKy == 'Bị từ chối') $statusClass = 'bi-tu-choi';
                ?>
                
                <div class="activity-card drl-card">
                    <div class="activity-header">
                        <span class="activity-type drl">
                            <i class="fas fa-star"></i> <?php echo e($activity->LoaiHoatDong ?? 'Rèn luyện'); ?>

                        </span>
                        <span class="activity-points">
                            +<?php echo e($activity->quydinh->DiemNhan ?? 0); ?> điểm
                        </span>
                    </div>
                    <div class="activity-body">
                        <h4><?php echo e($activity->TenHoatDong); ?></h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="activity-meta">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Bắt đầu: <strong><?php echo e(\Carbon\Carbon::parse($activity->ThoiGianBatDau)->format('d/m/Y H:i')); ?></strong></span>
                                </p>
                                <p class="activity-meta">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Địa điểm: <strong><?php echo e($activity->DiaDiem); ?></strong></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="activity-meta">
                                    <i class="fas fa-calendar-times"></i>
                                    <span>Hạn hủy: <strong><?php echo e($activity->ThoiHanHuy ? \Carbon\Carbon::parse($activity->ThoiHanHuy)->format('d/m/Y H:i') : 'Không có'); ?></strong></span>
                                </p>
                                <p class="activity-meta">
                                    <i class="fas fa-calendar-day"></i>
                                    <span>Ngày ĐK: <strong><?php echo e(\Carbon\Carbon::parse($registration->NgayDangKy)->format('d/m/Y H:i')); ?></strong></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="activity-footer">
                        <span class="status-badge <?php echo e($statusClass); ?>">
                            <?php echo e($registration->TrangThaiDangKy); ?>

                        </span>
                        
                        <?php if($canCancel): ?>
                            <form action="<?php echo e(route('sinhvien.quanly_dangky.huy_drl', $registration->MaDangKy)); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đăng ký hoạt động này?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-cancel">
                                    <i class="fas fa-times-circle me-1"></i> Hủy Đăng Ký
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn-cancel" disabled>
                                <i class="fas fa-lock me-1"></i> Đã qua hạn hủy
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>Bạn chưa đăng ký hoạt động rèn luyện nào</h5>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="tab-pane fade" id="ctxh-content" role="tabpanel" aria-labelledby="ctxh-tab">
            <?php $__empty_1 = true; $__currentLoopData = $ctxhRegistrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $activity = $registration->hoatdong;
                    $canCancel = (!$activity->ThoiHanHuy || $currentTime->lt($activity->ThoiHanHuy));
                    $statusClass = '';
                    if ($registration->TrangThaiDangKy == 'Chờ duyệt') $statusClass = 'cho-duyet';
                    elseif ($registration->TrangThaiDangKy == 'Đã duyệt') $statusClass = 'da-duyet';
                    elseif ($registration->TrangThaiDangKy == 'Bị từ chối') $statusClass = 'bi-tu-choi';
                ?>
                
                <div class="activity-card ctxh-card">
                    <div class="activity-header">
                        <span class="activity-type ctxh">
                            <i class="fas fa-heart"></i> <?php echo e($activity->LoaiHoatDong ?? 'Công tác xã hội'); ?>

                        </span>
                        <span class="activity-points">
                            +<?php echo e($activity->quydinh->DiemNhan ?? 0); ?> điểm
                        </span>
                    </div>
                    <div class="activity-body">
                        <h4><?php echo e($activity->TenHoatDong); ?></h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="activity-meta ctxh">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Bắt đầu: <strong><?php echo e(\Carbon\Carbon::parse($activity->ThoiGianBatDau)->format('d/m/Y H:i')); ?></strong></span>
                                </p>
                                <p class="activity-meta ctxh">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Địa điểm: <strong><?php echo e($activity->DiaDiem); ?></strong></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="activity-meta ctxh">
                                    <i class="fas fa-calendar-times"></i>
                                    <span>Hạn hủy: <strong><?php echo e($activity->ThoiHanHuy ? \Carbon\Carbon::parse($activity->ThoiHanHuy)->format('d/m/Y H:i') : 'Không có'); ?></strong></span>
                                </p>
                                <p class="activity-meta ctxh">
                                    <i class="fas fa-calendar-day"></i>
                                    <span>Ngày ĐK: <strong><?php echo e(\Carbon\Carbon::parse($registration->NgayDangKy)->format('d/m/Y H:i')); ?></strong></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="activity-footer">
                        <span class="status-badge <?php echo e($statusClass); ?>">
                            <?php echo e($registration->TrangThaiDangKy); ?>

                        </span>
                        
                        <?php if($canCancel): ?>
                            <form action="<?php echo e(route('sinhvien.quanly_dangky.huy_ctxh', $registration->MaDangKy)); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đăng ký hoạt động này?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-cancel">
                                    <i class="fas fa-times-circle me-1"></i> Hủy Đăng Ký
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn-cancel" disabled>
                                <i class="fas fa-lock me-1"></i> Đã qua hạn hủy
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                 <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>Bạn chưa đăng ký hoạt động CTXH nào</h5>
                </div>
            <?php endif; ?>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/quanly_dangky/index.blade.php ENDPATH**/ ?>