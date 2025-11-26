
<?php $__env->startSection('title', 'Thông Báo Hoạt Động'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --primary: #667eea;
        --primary-dark: #5568c7;
        --secondary: #764ba2;
        --accent: #f5576c;
        --accent-dark: #d84a5f;
        --success: #10b981;
        --info: #3b82f6;
        --card-shadow: 0 10px 40px rgba(0, 0, 0, .08);
        --card-hover-shadow: 0 20px 50px rgba(0, 0, 0, .12);
        --transition: all .4s cubic-bezier(.4, 0, .2, 1)
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        min-height: 100vh
    }

    /* Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 2.5rem 0;
        margin-bottom: 2rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, .3);
        position: relative;
        overflow: hidden
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, .1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(0)
        }

        50% {
            transform: translateY(-20px) rotate(10deg)
        }
    }

    .page-header h3 {
        color: #fff;
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0, 0, 0, .2);
        position: relative;
        z-index: 1
    }

    .page-header .subtitle {
        color: rgba(255, 255, 255, .9);
        font-size: 1rem;
        margin-top: .5rem;
        position: relative;
        z-index: 1
    }

    /* Khối Địa chỉ đỏ (Info) */
    .diachi-info {
        background: #fdf2f8;
        border: 1px solid #fbcfe8;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem
    }

    .diachi-info .info-label {
        font-weight: 600;
        color: #be185d;
        font-size: .9rem;
        display: block;
        margin-bottom: .25rem
    }

    .diachi-info .info-value {
        font-weight: 500;
        color: #333
    }

    /* Tabs */
    .custom-tabs {
        background: #fff;
        border-radius: 50px;
        padding: .5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
        display: inline-flex;
        gap: .5rem;
        margin-bottom: 2rem
    }

    .custom-tabs .nav-link {
        border: 0;
        border-radius: 50px;
        padding: .75rem 2rem;
        font-weight: 600;
        color: #64748b;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: .5rem
    }

    .custom-tabs .nav-link:hover {
        color: var(--primary);
        background: rgba(102, 126, 234, .05)
    }

    .custom-tabs .nav-link.active {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(102, 126, 234, .4)
    }

    .custom-tabs .nav-link#ctxh-tab.active {
        background: linear-gradient(135deg, var(--accent) 0%, #ff6b9d 100%);
        box-shadow: 0 4px 15px rgba(245, 87, 108, .4)
    }

    .custom-tabs .badge {
        font-size: .75rem;
        padding: .25rem .6rem;
        border-radius: 50px;
        font-weight: 700
    }

    .custom-tabs .nav-link.active .badge {
        background: rgba(255, 255, 255, .3) !important
    }

    /* Cards */
    .activity-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: var(--transition);
        border: 2px solid transparent;
        position: relative;
        animation: slideIn .5s ease forwards;
        opacity: 0
    }

    .activity-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .4s ease
    }

    .activity-card.ctxh-card::before {
        background: linear-gradient(90deg, var(--accent), #ff6b9d)
    }

    .activity-card:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: var(--card-hover-shadow);
        border-color: rgba(102, 126, 234, .2)
    }

    .activity-card.ctxh-card:hover {
        border-color: rgba(245, 87, 108, .2)
    }

    .activity-card:hover::before {
        transform: scaleX(1)
    }

    .activity-card:nth-child(1) {
        animation-delay: .1s
    }

    .activity-card:nth-child(2) {
        animation-delay: .2s
    }

    .activity-card:nth-child(3) {
        animation-delay: .3s
    }

    .activity-header {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%)
    }

    .activity-type {
        font-weight: 700;
        padding: .5rem 1.25rem;
        border-radius: 50px;
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .1)
    }

    .activity-type.drl {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: #fff
    }

    .activity-type.ctxh {
        background: linear-gradient(135deg, var(--accent) 0%, #ff6b9d 100%);
        color: #fff
    }

    .activity-points {
        font-weight: 800;
        font-size: 1.5rem;
        background: linear-gradient(135deg, var(--success), #34d399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: flex;
        align-items: center;
        gap: .25rem
    }

    .activity-body {
        padding: 2rem
    }

    .activity-body h4 {
        font-weight: 800;
        margin-bottom: 1.5rem;
        color: #1e293b;
        font-size: 1.4rem;
        line-height: 1.4
    }

    .activity-meta {
        font-size: .95rem;
        color: #64748b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .5rem 0
    }

    .activity-meta i {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f5f9;
        color: var(--primary);
        font-size: .85rem
    }

    .activity-meta.ctxh i {
        color: var(--accent)
    }

    .activity-meta strong {
        color: #1e293b;
        font-weight: 600
    }

    .activity-footer {
        padding: 1.5rem 2rem;
        background: #fafbfc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e2e8f0
    }

    .progress-info {
        display: flex;
        flex-direction: column;
        gap: .5rem
    }

    .progress-bar-wrapper {
        width: 200px;
        height: 8px;
        background: #e2e8f0;
        border-radius: 50px;
        overflow: hidden
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--success), #34d399);
        border-radius: 50px;
        transition: width .6s ease
    }

    .ctxh-card .progress-bar-fill {
        background: linear-gradient(90deg, var(--accent), #ff6b9d)
    }

    .progress-text {
        font-size: .85rem;
        color: #64748b;
        font-weight: 600
    }

    .btn-action {
        padding: .75rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: .95rem;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .1)
    }

    .btn-action-drl {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: #fff
    }

    .btn-action-drl:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, .4);
        color: #fff
    }

    .btn-action-ctxh {
        background: linear-gradient(135deg, var(--accent) 0%, #ff6b9d 100%);
        color: #fff
    }

    .btn-action-ctxh:hover {
        background: linear-gradient(135deg, var(--accent-dark) 0%, #ff6b9d 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 87, 108, .4);
        color: #fff
    }

    /* Empty */
    .empty-state {
        background: #fff;
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: var(--card-shadow);
        margin-top: 2rem
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem
    }

    .empty-state h5 {
        color: #64748b;
        font-weight: 600;
        margin-bottom: .5rem
    }

    .empty-state p {
        color: #94a3b8;
        margin: 0
    }

    /* Animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    /* === CSS MỚI CHO CARD GOM NHÓM === */
    .group-card .activity-body {
        padding: 1.5rem 2rem 1rem 2rem;
    }

    .group-days-list {
        list-style: none;
        padding: 0 2rem 1.5rem 2rem;
        margin: 0;
    }

    .day-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
        border-radius: 16px;
        margin-bottom: 1rem;
        border: 2px solid #f1f3f5;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
        gap: 1rem;
    }

    .day-item:last-child {
        margin-bottom: 0;
    }

    .day-item:hover {
        background: linear-gradient(135deg, #fff5f7 0%, #fef2f4 100%);
        border-color: rgba(245, 87, 108, .2);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 87, 108, .15);
    }

    .day-info {
        flex: 1;
        min-width: 0;
    }

    .day-info .day-date {
        font-weight: 700;
        color: #1e293b;
        font-size: 1.05rem;
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .day-info .day-time {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .day-info .day-time::before {
        content: '⏰';
        font-size: 0.9rem;
    }

    .day-slots {
        font-weight: 700;
        color: var(--accent);
        background: linear-gradient(135deg, rgba(245, 87, 108, .1), rgba(255, 107, 157, .15));
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 0.95rem;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid rgba(245, 87, 108, .2);
    }

    .day-slots i {
        font-size: 0.85rem;
    }

    .day-slots.full {
        color: #dc3545;
        background: linear-gradient(135deg, rgba(220, 53, 69, .1), rgba(220, 53, 69, .15));
        border-color: rgba(220, 53, 69, .2);
    }

    .btn-action-day {
        padding: 0.65rem 1.5rem;
        font-size: 0.875rem;
        min-width: 140px;
        text-align: center;
        white-space: nowrap;
    }

    /* Responsive */
    @media (max-width:768px) {
        .page-header h3 {
            font-size: 1.5rem
        }

        .custom-tabs {
            width: 100%;
            flex-direction: column;
            border-radius: 15px
        }

        .custom-tabs .nav-link {
            justify-content: center;
            width: 100%
        }

        .activity-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch
        }

        .progress-info {
            align-items: center
        }

        .btn-action {
            width: 100%;
            justify-content: center
        }

        .day-item {
            flex-direction: column;
            gap: 0.75rem;
            align-items: stretch;
        }

        .day-info {
            text-align: center;
        }

        .day-slots {
            text-align: center;
            margin-left: 0;
        }

        .btn-action-day {
            width: 100%;
            justify-content: center;
        }
    }

    /* === PAGINATION STYLES === */
    .pagination {
        justify-content: center;
        margin-top: 2rem;
        gap: 0.5rem;
    }

    .pagination .page-link {
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        color: var(--primary);
        padding: 0.75rem 1rem;
        font-weight: 600;
        transition: var(--transition);
        min-width: 44px;
        text-align: center;
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: #fff;
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-color: var(--primary);
        color: #fff;
        box-shadow: 0 4px 15px rgba(102, 126, 234, .4);
    }

    .pagination .page-item.disabled .page-link {
        color: #cbd5e1;
        border-color: #e2e8f0;
        cursor: not-allowed;
        opacity: 0.5;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" style="max-width:1400px;margin:0 auto;padding:2rem 1rem;">

    
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px;border:none;box-shadow:var(--card-shadow);">
        <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px;border:none;box-shadow:var(--card-shadow);">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px;border:none;box-shadow:var(--card-shadow);">
        <strong class="d-block mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra, vui lòng kiểm tra:</strong>
        <ul style="margin:0; padding-left:20px;">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="page-header">
        <div class="container">
            <h3><i class="fas fa-bell me-3"></i>Thông Báo Hoạt Động Mới Nhất</h3>
            <p class="subtitle">Khám phá và đăng ký các hoạt động rèn luyện và công tác xã hội</p>
        </div>
    </div>

    
    <div class="text-center">
        <ul class="nav custom-tabs" id="activityTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="drl-tab" data-bs-toggle="tab" data-bs-target="#drl-content" type="button" role="tab" aria-controls="drl-content" aria-selected="true">
                    <i class="fas fa-star"></i>
                    <span>Hoạt Động Rèn Luyện</span>
                    <span class="badge bg-light text-dark"><?php echo e($activitiesDRL->total()); ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ctxh-tab" data-bs-toggle="tab" data-bs-target="#ctxh-content" type="button" role="tab" aria-controls="ctxh-content" aria-selected="false">
                    <i class="fas fa-heart"></i>
                    <span>Hoạt Động CTXH</span>
                    
                    <span class="badge bg-light text-dark"><?php echo e($paginatedCtxh->total()); ?></span>
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="activityTabContent">
        
        <div class="tab-pane fade show active" id="drl-content" role="tabpanel" aria-labelledby="drl-tab">
            <?php $__empty_1 = true; $__currentLoopData = $activitiesDRL; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?> 
            <?php
            $registered = $activity->dangky_count ?? $activity->dangky->count();
            $total = (int) $activity->SoLuong;
            $percentage = $total > 0 ? min(100, max(0, ($registered / $total) * 100)) : 0;
            ?>

            <div class="activity-card drl-card"
                data-type="drl"
                data-id="<?php echo e($activity->MaHoatDong); ?>"
                data-title="<?php echo e(e($activity->TenHoatDong)); ?>"
                data-description="<?php echo e(e($activity->MoTa ?? '')); ?>"
                data-start="<?php echo e(optional($activity->ThoiGianBatDau)->format('c')); ?>"
                data-end="<?php echo e(optional($activity->ThoiGianKetThuc)->format('c')); ?>"
                data-cancel-deadline="<?php echo e(optional($activity->ThoiHanHuy)->format('c')); ?>"
                data-location="<?php echo e(e($activity->DiaDiem)); ?>"
                data-slots="<?php echo e($registered); ?> / <?php echo e($total); ?>"
                data-points="<?php echo e($activity->quydinh->DiemNhan ?? 0); ?>"
                data-semester="<?php echo e(optional($activity->hocKy)->TenHocKy ?? 'N/A'); ?>">
                <div class="activity-header">
                    <span class="activity-type drl">
                        <i class="fas fa-star"></i>
                        <?php echo e($activity->LoaiHoatDong ?? 'Rèn luyện'); ?>

                    </span>
                    <span class="activity-points">
                        <i class="fas fa-trophy"></i>
                        +<?php echo e($activity->quydinh->DiemNhan ?? 0); ?>

                    </span>
                </div>

                <div class="activity-body">
                    <h4><?php echo e($activity->TenHoatDong); ?></h4>
                    
                    
                    <?php if($activity->MoTa): ?>
                    <div class="mb-3 p-3" style="background: #f8f9fa; border-radius: 8px; border-left: 4px solid var(--primary);">
                        <strong style="color: var(--primary);">📋 Mô tả chi tiết:</strong>
                        <p class="mt-2 mb-0" style="color: #555; line-height: 1.6;"><?php echo e($activity->MoTa); ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="activity-meta">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Bắt đầu: <strong><?php echo e(optional($activity->ThoiGianBatDau)->format('d/m/Y H:i')); ?></strong></span>
                            </p>
                            <p class="activity-meta">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Địa điểm: <strong><?php echo e($activity->DiaDiem); ?></strong></span>
                            </p>
                            
                        </div>
                        <div class="col-md-6">
                            <p class="activity-meta">
                                <i class="fas fa-calendar-check"></i>
                                <span>Kết thúc: <strong><?php echo e(optional($activity->ThoiGianKetThuc)->format('d/m/Y H:i')); ?></strong></span>
                            </p>
                            <p class="activity-meta">
                                <i class="fas fa-users"></i>
                                <span>Số lượng: <strong><?php echo e($registered); ?> / <?php echo e($total); ?></strong></span>
                            </p>
                            <?php if($activity->ThoiHanHuy): ?>
                            <p class="activity-meta">
                                <i class="fas fa-times-circle"></i>
                                <span>Hạn hủy: <strong><?php echo e(optional($activity->ThoiHanHuy)->format('d/m/Y H:i')); ?></strong></span>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="activity-footer">
                    <div class="progress-info">
                        <span class="progress-text"><?php echo e(number_format($percentage, 0)); ?>% đã đăng ký</span>
                        <div class="progress-bar-wrapper">
                            <div class="progress-bar-fill" style="width: <?php echo e($percentage); ?>%"></div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <?php
                        $isRegistered_DRL = $activity->is_registered;
                        $isFull_DRL = $total > 0 && $registered >= $total;
                        ?>

                        <?php if($isRegistered_DRL): ?>
                        <button class="btn btn-action btn-success" style="background: var(--success);" disabled>
                            <i class="fas fa-check"></i> Đã Đăng Ký
                        </button>
                        <?php elseif($isFull_DRL): ?>
                        <button class="btn btn-action" style="background:#6c757d;color:#fff;" disabled>
                            <i class="fas fa-times-circle"></i> Đã Đầy
                        </button>
                        <?php else: ?>
                        <form action="<?php echo e(route('sinhvien.dangky.drl', $activity->MaHoatDong)); ?>" method="POST" style="margin:0; flex: 1;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-action btn-action-drl" style="width: 100%;">
                                <i class="fas fa-user-plus"></i> Đăng Ký Ngay
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>Chưa có hoạt động mới nào</h5>
                <p>Hiện tại chưa có thông báo hoạt động rèn luyện nào sắp diễn ra</p>
            </div>
            <?php endif; ?>

            
            <?php if($activitiesDRL->hasPages()): ?>
            <div style="display: flex; justify-content: center; margin-top: 3rem; gap: 0.5rem;">
                <?php echo e($activitiesDRL->links('pagination::bootstrap-4')); ?>

            </div>
            <?php endif; ?>
        </div>

        
        <div class="tab-pane fade" id="ctxh-content" role="tabpanel" aria-labelledby="ctxh-tab">

            
            <?php $__empty_1 = true; $__currentLoopData = $paginatedCtxh; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if($item['type'] === 'group'): ?>
                    
                    <?php
                    $activitiesInGroup = $item['data'];
                    $repActivity = $activitiesInGroup->first();
                    $totalRegistered = $activitiesInGroup->sum('dangky_count');
                    $totalSlots = $activitiesInGroup->sum('SoLuong');
                    $percentage = $totalSlots > 0 ? min(100, max(0, ($totalRegistered / $totalSlots) * 100)) : 0;
                    ?>

                    <div class="activity-card ctxh-card group-card">
                        <div class="activity-header">
                            <span class="activity-type ctxh">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <?php echo e($repActivity->LoaiHoatDong); ?>

                            </span>
                            <span class="activity-points">
                                <i class="fas fa-trophy"></i>
                                +<?php echo e($repActivity->quydinh->DiemNhan ?? 0); ?>

                            </span>
                        </div>

                        <div class="activity-body">
                            
                            <h4><?php echo e($repActivity->dotDiaChiDo->TenDot ?? 'Hoạt động Địa chỉ đỏ'); ?></h4>

                            <div class="diachi-info">
                                <div class="row">
                                    <div class="col-md-12">
                                        <span class="info-label">Địa điểm tổ chức:</span>
                                        <span class="info-value"><?php echo e($repActivity->diaDiem->TenDiaDiem ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <ul class="group-days-list">
                            <?php $__currentLoopData = $activitiesInGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayActivity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $registered = $dayActivity->dangky_count;
                            $total = $dayActivity->SoLuong;
                            $isFull_Day = $total > 0 && $registered >= $total;
                            $isRegistered_Day = $dayActivity->is_registered;
                            ?>
                            <li class="day-item"
                                data-type="ctxh"
                                data-id="<?php echo e($dayActivity->MaHoatDong); ?>"
                                data-title="<?php echo e(e($dayActivity->TenHoatDong)); ?>"
                                data-description="<?php echo e(e($dayActivity->MoTa ?? '')); ?>"
                                data-start="<?php echo e($dayActivity->ThoiGianBatDau->format('c')); ?>"
                                data-end="<?php echo e($dayActivity->ThoiGianKetThuc->format('c')); ?>"
                                data-cancel-deadline="<?php echo e(optional($dayActivity->ThoiHanHuy)->format('c')); ?>"
                                data-location="<?php echo e(e($dayActivity->DiaDiem)); ?>"
                                data-slots="<?php echo e($registered); ?> / <?php echo e($total); ?>"
                                data-points="<?php echo e($dayActivity->quydinh->DiemNhan ?? 0); ?>"
                                data-semester="Địa chỉ đỏ">
                                <div class="day-info">
                                    <div class="day-date"><?php echo e($dayActivity->ThoiGianBatDau->format('l, d/m/Y')); ?></div>
                                    <div class="day-time">
                                        <?php echo e($dayActivity->ThoiGianBatDau->format('H:i')); ?> - <?php echo e($dayActivity->ThoiGianKetThuc->format('H:i')); ?>

                                    </div>
                                    <?php if($dayActivity->MoTa): ?>
                                    <div style="margin-top: 0.5rem; padding: 0.5rem; background: #fef2f4; border-radius: 6px; border-left: 3px solid var(--accent); font-size: 0.875rem;">
                                        <strong style="color: var(--accent); display: block; margin-bottom: 0.25rem;">📋 Mô tả:</strong>
                                        <p style="color: #555; margin: 0; line-height: 1.4;"><?php echo e($dayActivity->MoTa); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <span class="day-slots <?php echo e($isFull_Day ? 'full' : ''); ?>">
                                    <i class="fas fa-users"></i>
                                    <?php echo e($registered); ?> / <?php echo e($total); ?>

                                </span>
                                <div>
                                    <?php if($isRegistered_Day): ?>
                                    <button class="btn btn-action btn-action-day btn-success" style="background: var(--success);" disabled>
                                        <i class="fas fa-check"></i> Đã Đăng Ký
                                    </button>
                                    <?php elseif($isFull_Day): ?>
                                    <button class="btn btn-action btn-action-day" style="background:#6c757d;color:#fff;" disabled>
                                        <i class="fas fa-times-circle"></i> Đã Đầy
                                    </button>
                                    <?php else: ?>
                                    <form action="<?php echo e(route('sinhvien.dangky.ctxh', $dayActivity->MaHoatDong)); ?>" method="POST" style="margin:0; display: inline-block;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-action btn-action-day btn-action-ctxh">
                                            <i class="fas fa-user-plus"></i> Đăng Ký
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php else: ?>
                    
                    <?php
                    $activity = $item['data'];
                    $registered = $activity->dangky_count ?? $activity->dangky->count();
                    $total = (int) $activity->SoLuong;
                    $percentage = $total > 0 ? min(100, max(0, ($registered / $total) * 100)) : 0;
                    $isRegistered_CTXH = $activity->is_registered;
                    $isFull_CTXH = $total > 0 && $registered >= $total;
                    ?>

                    <div class="activity-card ctxh-card"
                        data-type="ctxh"
                        data-id="<?php echo e($activity->MaHoatDong); ?>"
                        data-title="<?php echo e(e($activity->TenHoatDong)); ?>"
                        data-description="<?php echo e(e($activity->MoTa ?? '')); ?>"
                        data-start="<?php echo e(optional($activity->ThoiGianBatDau)->format('c')); ?>"
                        data-end="<?php echo e(optional($activity->ThoiGianKetThuc)->format('c')); ?>"
                        data-cancel-deadline="<?php echo e(optional($activity->ThoiHanHuy)->format('c')); ?>"
                        data-location="<?php echo e(e($activity->DiaDiem)); ?>"
                        data-slots="<?php echo e($registered); ?> / <?php echo e($total); ?>"
                        data-points="<?php echo e($activity->quydinh->DiemNhan ?? 0); ?>"
                        data-semester="N/A">
                        <div class="activity-header">
                            <span class="activity-type ctxh">
                                <i class="fas fa-heart"></i>
                                <?php echo e($activity->LoaiHoatDong ?? 'Công tác xã hội'); ?>

                            </span>
                            <span class="activity-points">
                                <i class="fas fa-trophy"></i>
                                +<?php echo e($activity->quydinh->DiemNhan ?? 0); ?>

                            </span>
                        </div>

                        <div class="activity-body">
                            <h4><?php echo e($activity->TenHoatDong); ?></h4>
                            
                            
                            <?php if($activity->MoTa): ?>
                            <div class="mb-3 p-3" style="background: #fef2f4; border-radius: 8px; border-left: 4px solid var(--accent);">
                                <strong style="color: var(--accent);">📋 Mô tả chi tiết:</strong>
                                <p class="mt-2 mb-0" style="color: #555; line-height: 1.6;"><?php echo e($activity->MoTa); ?></p>
                            </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <p class="activity-meta ctxh">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Bắt đầu: <strong><?php echo e(optional($activity->ThoiGianBatDau)->format('d/m/Y H:i')); ?></strong></span>
                                    </p>
                                    <p class="activity-meta ctxh">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Ghi chú ĐĐ: <strong><?php echo e($activity->DiaDiem); ?></strong></span>
                                    </p>
                                    
                                </div>
                                <div class="col-md-6">
                                    <p class="activity-meta ctxh">
                                        <i class="fas fa-calendar-check"></i>
                                        <span>Kết thúc: <strong><?php echo e(optional($activity->ThoiGianKetThuc)->format('d/m/Y H:i')); ?></strong></span>
                                    </p>
                                    <p class="activity-meta ctxh">
                                        <i class="fas fa-users"></i>
                                        <span>Số lượng: <strong><?php echo e($registered); ?> / <?php echo e($total); ?></strong></span>
                                    </p>
                                    <?php if($activity->ThoiHanHuy): ?>
                                    <p class="activity-meta ctxh">
                                        <i class="fas fa-times-circle"></i>
                                        <span>Hạn hủy: <strong><?php echo e(optional($activity->ThoiHanHuy)->format('d/m/Y H:i')); ?></strong></span>
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="activity-footer">
                            <div class="progress-info">
                                <span class="progress-text"><?php echo e(number_format($percentage, 0)); ?>% đã đăng ký</span>
                                <div class="progress-bar-wrapper">
                                    <div class="progress-bar-fill" style="width: <?php echo e($percentage); ?>%"></div>
                                </div>
                            </div>

                            <div style="display: flex; gap: 10px;">
                                <?php if($isRegistered_CTXH): ?>
                                <button class="btn btn-action btn-success" style="background: var(--success);" disabled>
                                    <i class="fas fa-check"></i> Đã Đăng Ký
                                </button>
                                <?php elseif($isFull_CTXH): ?>
                                <button class="btn btn-action" style="background:#6c757d;color:#fff;" disabled>
                                    <i class="fas fa-times-circle"></i> Đã Đầy
                                </button>
                                <?php else: ?>
                                <form action="<?php echo e(route('sinhvien.dangky.ctxh', $activity->MaHoatDong)); ?>" method="POST" style="margin:0; flex: 1;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-action btn-action-ctxh" style="width: 100%;">
                                        <i class="fas fa-user-plus"></i> Đăng Ký Ngay
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>Chưa có hoạt động mới nào</h5>
                <p>Hiện tại chưa có thông báo hoạt động công tác xã hội nào sắp diễn ra</p>
            </div>
            <?php endif; ?>

            
            <?php if($paginatedCtxh->hasPages()): ?>
            <div style="display: flex; justify-content: center; margin-top: 3rem; gap: 0.5rem;">
                <?php echo e($paginatedCtxh->links('pagination::bootstrap-4')); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Không cần JavaScript nữa - tất cả thông tin đã hiển thị trên card
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/thongbao_hoatdong/index.blade.php ENDPATH**/ ?>