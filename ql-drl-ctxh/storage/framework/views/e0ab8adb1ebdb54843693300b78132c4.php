
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
        --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        --card-hover-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        min-height: 100vh;
    }

    /* Header Section */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 2.5rem 0;
        margin-bottom: 2rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(10deg);
        }
    }

    .page-header h3 {
        color: white;
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        position: relative;
        z-index: 1;
    }

    .page-header .subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        margin-top: 0.5rem;
        position: relative;
        z-index: 1;
    }

    /* Custom Tabs */
    .custom-tabs {
        background: white;
        border-radius: 50px;
        padding: 0.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        display: inline-flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }

    .custom-tabs .nav-link {
        border: 0;
        border-radius: 50px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        color: #64748b;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
    }

    .custom-tabs .nav-link:hover {
        color: var(--primary);
        background: rgba(102, 126, 234, 0.05);
    }

    .custom-tabs .nav-link.active {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .custom-tabs .nav-link#ctxh-tab.active {
        background: linear-gradient(135deg, var(--accent) 0%, #ff6b9d 100%);
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
    }

    .custom-tabs .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.6rem;
        border-radius: 50px;
        font-weight: 700;
    }

    .custom-tabs .nav-link.active .badge {
        background: rgba(255, 255, 255, 0.3) !important;
    }

    /* Activity Cards */
    .activity-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: var(--transition);
        border: 2px solid transparent;
        position: relative;
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
        transition: transform 0.4s ease;
    }

    .activity-card.ctxh-card::before {
        background: linear-gradient(90deg, var(--accent), #ff6b9d);
    }

    .activity-card:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: var(--card-hover-shadow);
        border-color: rgba(102, 126, 234, 0.2);
    }

    .activity-card.ctxh-card:hover {
        border-color: rgba(245, 87, 108, 0.2);
    }

    .activity-card:hover::before {
        transform: scaleX(1);
    }

    /* Card Header */
    .activity-header {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        position: relative;
    }

    .activity-type {
        font-weight: 700;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .activity-type.drl {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
    }

    .activity-type.ctxh {
        background: linear-gradient(135deg, var(--accent) 0%, #ff6b9d 100%);
        color: white;
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
        gap: 0.25rem;
    }

    /* Card Body */
    .activity-body {
        padding: 2rem;
    }

    .activity-body h4 {
        font-weight: 800;
        margin-bottom: 1.5rem;
        color: #1e293b;
        font-size: 1.4rem;
        line-height: 1.4;
    }

    .activity-meta {
        font-size: 0.95rem;
        color: #64748b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
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
        font-size: 0.85rem;
    }

    .activity-meta.ctxh i {
        color: var(--accent);
    }

    .activity-meta strong {
        color: #1e293b;
        font-weight: 600;
    }

    /* Card Footer */
    .activity-footer {
        padding: 1.5rem 2rem;
        background: #fafbfc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e2e8f0;
    }

    .progress-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .progress-bar-wrapper {
        width: 200px;
        height: 8px;
        background: #e2e8f0;
        border-radius: 50px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--success), #34d399);
        border-radius: 50px;
        transition: width 0.6s ease;
    }

    .ctxh-card .progress-bar-fill {
        background: linear-gradient(90deg, var(--accent), #ff6b9d);
    }

    .progress-text {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
    }

    /* Buttons */
    .btn-action {
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-action-drl {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
    }

    .btn-action-drl:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-action-ctxh {
        background: linear-gradient(135deg, var(--accent) 0%, #ff6b9d 100%);
        color: white;
    }

    .btn-action-ctxh:hover {
        background: linear-gradient(135deg, var(--accent-dark) 0%, #ff6b9d 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
        color: white;
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: var(--card-shadow);
        margin-top: 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
        margin: 0;
    }

    /* Animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .activity-card {
        animation: slideIn 0.5s ease forwards;
    }

    .activity-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .activity-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .activity-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .activity-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .activity-card:nth-child(5) {
        animation-delay: 0.5s;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header h3 {
            font-size: 1.5rem;
        }

        .custom-tabs {
            width: 100%;
            flex-direction: column;
            border-radius: 15px;
        }

        .custom-tabs .nav-link {
            justify-content: center;
            width: 100%;
        }

        .activity-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .progress-info {
            align-items: center;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" style="max-width: 1400px; margin: 0 auto; padding: 2rem 1rem;">

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?php echo e(session('error')); ?>

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
                    <span class="badge bg-light text-dark"><?php echo e($activitiesDRL->count()); ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ctxh-tab" data-bs-toggle="tab" data-bs-target="#ctxh-content" type="button" role="tab" aria-controls="ctxh-content" aria-selected="false">
                    <i class="fas fa-heart"></i>
                    <span>Hoạt Động CTXH</span>
                    <span class="badge bg-light text-dark"><?php echo e($activitiesCTXH->count()); ?></span>
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="activityTabContent">

        <div class="tab-pane fade show active" id="drl-content" role="tabpanel" aria-labelledby="drl-tab">
            <?php $__empty_1 = true; $__currentLoopData = $activitiesDRL; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
            $registered = $activity->dangky->count();
            $total = $activity->SoLuong;
            $percentage = $total > 0 ? ($registered / $total) * 100 : 0;
            ?>
            <div class="activity-card drl-card">
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
                    <div class="row">
                        <div class="col-md-6">
                            <p class="activity-meta">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Bắt đầu: <strong><?php echo e($activity->ThoiGianBatDau->format('d/m/Y H:i')); ?></strong></span>
                            </p>
                            <p class="activity-meta">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Địa điểm: <strong><?php echo e($activity->DiaDiem); ?></strong></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="activity-meta">
                                <i class="fas fa-calendar-check"></i>
                                <span>Kết thúc: <strong><?php echo e($activity->ThoiGianKetThuc->format('d/m/Y H:i')); ?></strong></span>
                            </p>
                            <p class="activity-meta">
                                <i class="fas fa-users"></i>
                                <span>Số lượng: <strong><?php echo e($registered); ?> / <?php echo e($total); ?></strong></span>
                            </p>
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

                    
                    <?php
                    $isRegistered_DRL = in_array($activity->MaHoatDong, $registeredDrlIds);
                    $isFull_DRL = $registered >= $total;
                    ?>

                    <?php if($isRegistered_DRL): ?>
                    <button class="btn btn-action btn-success" style="background: var(--success); cursor: not-allowed;" disabled>
                        <i class="fas fa-check"></i>
                        Đã Đăng Ký
                    </button>
                    <?php elseif($isFull_DRL): ?>
                    <button class="btn btn-action" style="background: #6c757d; cursor: not-allowed;" disabled>
                        <i class="fas fa-times-circle"></i>
                        Đã Đầy
                    </button>
                    <?php else: ?>
                    <form action="<?php echo e(route('sinhvien.dangky.drl', $activity->MaHoatDong)); ?>" method="POST" style="margin: 0;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-action btn-action-drl">
                            <i class="fas fa-user-plus"></i>
                            Đăng Ký Ngay
                        </button>
                    </form>
                    <?php endif; ?>
                    
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>Chưa có hoạt động mới nào</h5>
                <p>Hiện tại chưa có thông báo hoạt động rèn luyện nào sắp diễn ra</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="tab-pane fade" id="ctxh-content" role="tabpanel" aria-labelledby="ctxh-tab">
            <?php $__empty_1 = true; $__currentLoopData = $activitiesCTXH; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
            $registered = $activity->dangky->count();
            $total = $activity->SoLuong;
            $percentage = $total > 0 ? ($registered / $total) * 100 : 0;
            ?>
            <div class="activity-card ctxh-card">
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
                    <div class="row">
                        <div class="col-md-6">
                            <p class="activity-meta ctxh">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Bắt đầu: <strong><?php echo e($activity->ThoiGianBatDau->format('d/m/Y H:i')); ?></strong></span>
                            </p>
                            <p class="activity-meta ctxh">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Địa điểm: <strong><?php echo e($activity->DiaDiem); ?></strong></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="activity-meta ctxh">
                                <i class="fas fa-calendar-check"></i>
                                <span>Kết thúc: <strong><?php echo e($activity->ThoiGianKetThuc->format('d/m/Y H:i')); ?></strong></span>
                            </p>
                            <p class="activity-meta ctxh">
                                <i class="fas fa-users"></i>
                                <span>Số lượng: <strong><?php echo e($registered); ?> / <?php echo e($total); ?></strong></span>
                            </p>
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

                    
                    <?php
                    $isRegistered_CTXH = in_array($activity->MaHoatDong, $registeredCtxhIds);
                    $isFull_CTXH = $registered >= $total;
                    ?>

                    <?php if($isRegistered_CTXH): ?>
                    <button class="btn btn-action btn-success" style="background: var(--success); cursor: not-allowed;" disabled>
                        <i class="fas fa-check"></i>
                        Đã Đăng Ký
                    </button>
                    <?php elseif($isFull_CTXH): ?>
                    <button class="btn btn-action" style="background: #6c757d; cursor: not-allowed;" disabled>
                        <i class="fas fa-times-circle"></i>
                        Đã Đầy
                    </button>
                    <?php else: ?>
                    <form action="<?php echo e(route('sinhvien.dangky.ctxh', $activity->MaHoatDong)); ?>" method="POST" style="margin: 0;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-action btn-action-ctxh">
                            <i class="fas fa-user-plus"></i>
                            Đăng Ký Ngay
                        </button>
                    </form>
                    <?php endif; ?>
                    
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>Chưa có hoạt động mới nào</h5>
                <p>Hiện tại chưa có thông báo hoạt động công tác xã hội nào sắp diễn ra</p>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/thongbao_hoatdong/index.blade.php ENDPATH**/ ?>