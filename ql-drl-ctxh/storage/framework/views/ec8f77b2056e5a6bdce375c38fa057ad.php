
<?php $__env->startSection('title', 'Tất cả hoạt động'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Style cho trang tin tức */
    .thongbao-item {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
        padding: 0;
        overflow: hidden;
        border-left: 5px solid #667eea;
    }
    
    /* Phân biệt màu cho DRL và CTXH */
    .thongbao-item.drl {
        border-left-color: #667eea;
    }
    .thongbao-item.ctxh {
        border-left-color: #f5576c;
    }

    .thongbao-header {
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
    }

    .thongbao-header .badge {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.5em 1em;
    }

    .thongbao-item h5 {
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
        padding: 1rem 1.5rem 0 1.5rem;
    }
    .thongbao-item .thongbao-date {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 1rem;
        padding: 0 1.5rem;
    }
    .thongbao-item .thongbao-content {
        color: #555;
        line-height: 1.6;
        padding: 0 1.5rem 1.5rem 1.5rem;
    }

    /* --- CSS CHO BỘ LỌC --- */
    :root {
        --primary: #667eea;
        --secondary: #764ba2;
        --accent: #f5576c;
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
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
        text-decoration: none;
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

    /* === CSS PHÂN TRANG === */
    .pagination {
        display: flex;
        gap: 0.5rem;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .pagination .page-item {
        list-style: none;
    }

    .pagination .page-link {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        color: #64748b;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 45px;
        background: white;
    }

    .pagination .page-link:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(102, 126, 234, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-color: var(--primary);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .pagination .page-item.disabled .page-link {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #cbd5e1;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination .page-item.disabled .page-link:hover {
        transform: none;
        box-shadow: none;
    }

    /* Arrows (Previous/Next) */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-weight: 700;
        padding: 0.75rem 1.5rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    
    <div class="page-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2.5rem 0; margin-bottom: 2rem; border-radius: 20px;">
        <div class="container">
            <h3 style="color: white; font-weight: 800; margin: 0; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
                <i class="fa fa-history me-3"></i>Thông Báo Hoạt Động
            </h3>
            <p style="color: rgba(255, 255, 255, 0.9); font-size: 1rem; margin-top: 0.5rem; margin-bottom: 0;">
                Tất cả hoạt động DRL và CTXH
            </p>
        </div>
    </div>

    <!-- ===== BỘ LỌC (TABS) ===== -->
    <div class="text-center">
        <div class="nav custom-tabs" role="tablist">
            <!-- Tab Tất Cả -->
            <a href="<?php echo e(route('sinhvien.news.index', ['type' => 'all'])); ?>" 
               class="nav-link <?php echo e($currentFilter == 'all' ? 'active' : ''); ?>" 
               role="tab">
               <i class="fas fa-list"></i>
               <span>Tất cả</span>
            </a>
            
            <!-- Tab DRL -->
            <a href="<?php echo e(route('sinhvien.news.index', ['type' => 'drl'])); ?>" 
               class="nav-link <?php echo e($currentFilter == 'drl' ? 'active' : ''); ?>" 
               role="tab">
               <i class="fas fa-star"></i>
               <span>Hoạt Động Rèn Luyện</span>
            </a>
            
            <!-- Tab CTXH -->
            <a href="<?php echo e(route('sinhvien.news.index', ['type' => 'ctxh'])); ?>" 
               id="ctxh-tab"
               class="nav-link <?php echo e($currentFilter == 'ctxh' ? 'active' : ''); ?>" 
               role="tab">
               <i class="fas fa-heart"></i>
               <span>Hoạt Động CTXH</span>
            </a>
        </div>
    </div>
    <!-- ===== KẾT THÚC BỘ LỌC ===== -->

    <?php if($thongBaos->isEmpty()): ?>
        <div class="empty-state" style="background: white; border-radius: 20px; padding: 4rem 2rem; text-align: center;">
            <i class="fas fa-inbox" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
            <h5>Không tìm thấy hoạt động nào</h5>
            <p style="color: #94a3b8; margin: 0;">Không có hoạt động nào khớp với bộ lọc của bạn.</p>
        </div>
    <?php else: ?>
        <?php $__currentLoopData = $thongBaos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <div class="thongbao-item <?php echo e($activity->Loai == 'DRL' ? 'drl' : 'ctxh'); ?>">
                
                <div class="thongbao-header">
                    <?php if($activity->Loai == 'DRL'): ?>
                        <span class="badge bg-primary">Hoạt Động Rèn Luyện</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Hoạt Động CTXH</span>
                    <?php endif; ?>
                    
                    <span style="font-weight: 700; color: #10b981;">
                        <i class="fas fa-trophy"></i>
                        +<?php echo e($activity->quydinh->DiemNhan ?? 0); ?> điểm
                    </span>
                </div>

                <h5><?php echo e($activity->TenHoatDong); ?></h5>
                
                <p class="thongbao-date">
                    <i class="fa fa-calendar-alt me-2"></i> 
                    Bắt đầu: <?php echo e(\Carbon\Carbon::parse($activity->ThoiGianBatDau)->format('H:i, d/m/Y')); ?>

                    <br>
                    <i class="fa fa-calendar-check me-2"></i>
                    Kết thúc: <?php echo e(\Carbon\Carbon::parse($activity->ThoiGianKetThuc)->format('H:i, d/m/Y')); ?>

                </p>

                <div class="thongbao-content">
                    <p><strong>Địa điểm:</strong> <?php echo e($activity->DiaDiem ?? 'Chưa cập nhật'); ?></p>
                    <p><strong>Mô tả:</strong> <?php echo e($activity->MoTa ?? 'Không có mô tả'); ?></p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($thongBaos->links()); ?>

        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/tintuc/index.blade.php ENDPATH**/ ?>