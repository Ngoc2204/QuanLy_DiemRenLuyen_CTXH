

<?php $__env->startSection('title', 'Kết quả tìm kiếm'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .search-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .search-form {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.2);
    }

    .search-form input {
        width: 100%;
        padding: 14px 20px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .search-form input:focus {
        outline: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .search-results {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .result-card {
        background: white;
        border-radius: 14px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .result-card.ctxh {
        border-left-color: #f5576c;
    }

    .result-card.registered {
        border-left-color: #10b981;
    }

    .result-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .result-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.8rem;
    }

    .badge-drl {
        background: rgba(102, 126, 234, 0.15);
        color: #667eea;
    }

    .badge-ctxh {
        background: rgba(245, 87, 108, 0.15);
        color: #f5576c;
    }

    .badge-registered {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }

    .result-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.8rem;
        line-height: 1.4;
    }

    .result-meta {
        font-size: 0.9rem;
        color: #718096;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .result-status {
        display: inline-block;
        padding: 0.3rem 0.6rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        margin-top: 0.8rem;
    }

    .result-status.rejected {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }

    .result-status.pending {
        background: rgba(255, 193, 7, 0.15);
        color: #ff9800;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background: white;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-text {
        font-size: 1.1rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-top: 2rem;
        margin-bottom: 1.2rem;
        padding-bottom: 0.8rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .results-count {
        color: #718096;
        font-size: 0.95rem;
    }
</style>

<div class="search-container">
    
    <div class="search-form">
        <form action="<?php echo e(route('sinhvien.search')); ?>" method="get">
            <input type="text" name="q" value="<?php echo e($query); ?>" placeholder="Tìm kiếm hoạt động, địa điểm..." autofocus>
        </form>
    </div>

    <?php if($query && $total_results > 0): ?>
        
        <div style="margin-bottom: 1.5rem;">
            <p class="results-count">
                <i class="fas fa-check-circle"></i> Tìm thấy <strong><?php echo e($total_results); ?></strong> kết quả cho "<strong><?php echo e($query); ?></strong>"
            </p>
        </div>

        
        <?php if($registered_results->count() > 0): ?>
            <div class="section-title">
                <i class="fas fa-bookmark me-2" style="color: #10b981;"></i>Hoạt động đã đăng ký
            </div>
            <div class="search-results">
                <?php $__currentLoopData = $registered_results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="result-card registered">
                        <span class="result-badge badge-registered">
                            <?php echo e($activity->type == 'DRL' ? 'Rèn Luyện' : 'CTXH'); ?>

                        </span>
                        
                        <h3 class="result-title"><?php echo e($activity->TenHoatDong); ?></h3>
                        
                        <?php if($activity->ThoiGianBatDau): ?>
                            <div class="result-meta">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo e(\Carbon\Carbon::parse($activity->ThoiGianBatDau)->format('d/m/Y H:i')); ?>

                            </div>
                        <?php endif; ?>
                        
                        <?php if($activity->DiaDiem): ?>
                            <div class="result-meta">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo e(substr($activity->DiaDiem, 0, 40)); ?><?php echo e(strlen($activity->DiaDiem) > 40 ? '...' : ''); ?>

                            </div>
                        <?php endif; ?>

                        <?php if($activity->TrangThaiDangKy == 'Đã duyệt'): ?>
                            <span class="result-status"><?php echo e($activity->TrangThaiDangKy); ?></span>
                        <?php elseif($activity->TrangThaiDangKy == 'Bị từ chối'): ?>
                            <span class="result-status rejected"><?php echo e($activity->TrangThaiDangKy); ?></span>
                        <?php else: ?>
                            <span class="result-status pending"><?php echo e($activity->TrangThaiDangKy); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        
        <?php if($drl_results->count() > 0): ?>
            <div class="section-title">
                <i class="fas fa-medal me-2" style="color: #667eea;"></i>Hoạt động rèn luyện
            </div>
            <div class="search-results">
                <?php $__currentLoopData = $drl_results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="#" style="text-decoration: none; color: inherit;">
                        <div class="result-card">
                            <span class="result-badge badge-drl">Rèn Luyện</span>
                            
                            <h3 class="result-title"><?php echo e($activity->TenHoatDong); ?></h3>
                            
                            <?php if($activity->ThoiGianBatDau): ?>
                                <div class="result-meta">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?php echo e(\Carbon\Carbon::parse($activity->ThoiGianBatDau)->format('d/m/Y H:i')); ?>

                                </div>
                            <?php endif; ?>
                            
                            <?php if($activity->DiaDiem): ?>
                                <div class="result-meta">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo e(substr($activity->DiaDiem, 0, 40)); ?><?php echo e(strlen($activity->DiaDiem) > 40 ? '...' : ''); ?>

                                </div>
                            <?php endif; ?>

                            <?php if($activity->quydinh && $activity->quydinh->DiemNhan): ?>
                                <div class="result-meta">
                                    <i class="fas fa-star" style="color: #ffc107;"></i>
                                    <strong><?php echo e($activity->quydinh->DiemNhan); ?> điểm</strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        
        <?php if($ctxh_results->count() > 0): ?>
            <div class="section-title">
                <i class="fas fa-heart me-2" style="color: #f5576c;"></i>Hoạt động công tác xã hội
            </div>
            <div class="search-results">
                <?php $__currentLoopData = $ctxh_results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="#" style="text-decoration: none; color: inherit;">
                        <div class="result-card ctxh">
                            <span class="result-badge badge-ctxh">CTXH</span>
                            
                            <h3 class="result-title"><?php echo e($activity->TenHoatDong); ?></h3>
                            
                            <?php if($activity->ThoiGianBatDau): ?>
                                <div class="result-meta">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?php echo e(\Carbon\Carbon::parse($activity->ThoiGianBatDau)->format('d/m/Y H:i')); ?>

                                </div>
                            <?php endif; ?>
                            
                            <?php if($activity->DiaDiem): ?>
                                <div class="result-meta">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo e(substr($activity->DiaDiem, 0, 40)); ?><?php echo e(strlen($activity->DiaDiem) > 40 ? '...' : ''); ?>

                                </div>
                            <?php endif; ?>

                            <?php if($activity->quydinh && $activity->quydinh->DiemNhan): ?>
                                <div class="result-meta">
                                    <i class="fas fa-star" style="color: #ffc107;"></i>
                                    <strong><?php echo e($activity->quydinh->DiemNhan); ?> điểm</strong>
                                </div>
                            <?php endif; ?>

                            <?php if($activity->LoaiHoatDong): ?>
                                <div class="result-meta">
                                    <i class="fas fa-tag"></i>
                                    <?php echo e($activity->LoaiHoatDong); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

    <?php elseif($query): ?>
        
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-search"></i>
            </div>
            <p class="empty-text">Không tìm thấy kết quả cho "<strong><?php echo e($query); ?></strong>"</p>
            <p style="color: #94a3b8; font-size: 0.95rem;">Hãy thử với từ khóa khác hoặc bộ lọc khác</p>
        </div>
    <?php else: ?>
        
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-search"></i>
            </div>
            <p class="empty-text">Tìm kiếm hoạt động</p>
            <p style="color: #94a3b8; font-size: 0.95rem;">Nhập từ khóa hoặc tên hoạt động để tìm kiếm</p>
        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/search/index.blade.php ENDPATH**/ ?>