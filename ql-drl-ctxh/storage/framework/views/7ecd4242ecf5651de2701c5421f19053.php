
<?php $__env->startSection('title', 'Điểm rèn luyện theo kỳ'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --primary: #667eea;
        --secondary: #764ba2;
        --success: #10b981;
        --warning: #f59e0b;
        --info: #3b82f6;
        --danger: #ef4444;
    }

    .page-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Header Card */
    .header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .header-card h3 {
        color: white;
        font-weight: 700;
        margin: 0;
        font-size: 1.8rem;
        position: relative;
        z-index: 1;
    }

    .header-card .subtitle {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1rem;
        margin-top: 0.5rem;
        position: relative;
        z-index: 1;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .filter-card select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .filter-card select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }

    .filter-card .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .filter-card .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    /* Stat Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, transparent 100%);
        border-radius: 0 16px 0 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        border-color: var(--primary);
    }

    .stat-card .icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card .icon-wrapper.bg-info { 
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); 
    }
    .stat-card .icon-wrapper.bg-success { 
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%); 
    }
    .stat-card .icon-wrapper.bg-danger { 
        background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); 
    }
    .stat-card .icon-wrapper.bg-warning { 
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); 
    }

    .stat-card .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #212529;
        line-height: 1;
    }

    .stat-card .stat-value.text-danger {
        color: var(--danger);
    }

    .stat-card .stat-rank {
        display: inline-block;
        margin-top: 0.75rem;
        padding: 0.35rem 0.75rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        border: none;
    }

    .table-card .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }

    .table-card h5 {
        color: #212529;
        font-weight: 600;
        margin: 0;
    }

    .table-custom {
        margin: 0;
    }

    .table-custom thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        color: #495057;
        padding: 1rem;
    }

    .table-custom tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
    }

    .table-custom tbody tr {
        transition: all 0.2s ease;
    }

    .table-custom tbody tr:hover {
        background: #f8f9ff;
        transform: scale(1.01);
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    .activity-name {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.25rem;
    }

    .activity-code {
        color: #6c757d;
        font-size: 0.85rem;
    }

    .badge-points {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .badge-points.positive {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .badge-points.negative {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
    }

    .empty-state i {
        color: #cbd5e1;
        margin-bottom: 1.5rem;
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

    /* Responsive */
    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: 1fr;
        }
        
        .header-card {
            padding: 1.5rem;
        }

        .header-card h3 {
            font-size: 1.5rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="container-fluid" style="max-width: 1400px; margin: 0 auto; padding: 0 1rem;">

        <!-- Header -->
        <div class="header-card">
            <h3><i class="fas fa-medal me-2"></i>Điểm Rèn Luyện Theo Học Kỳ</h3>
            <p class="subtitle">Theo dõi chi tiết điểm cộng, điểm trừ và xếp loại rèn luyện của bạn</p>
        </div>

        <!-- Filter -->
        <div class="filter-card">
            <form action="<?php echo e(route('sinhvien.diem_ren_luyen')); ?>" method="GET" class="d-flex flex-wrap align-items-center gap-3 justify-content-center">
                <label for="hocky" class="form-label mb-0 fw-semibold">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Chọn học kỳ:
                </label>
                <select name="hocky" id="hocky" class="form-select" style="max-width: 350px;">
                    <?php if($allHocKys->isEmpty()): ?>
                        <option value="">Chưa có dữ liệu học kỳ</option>
                    <?php else: ?>
                        <?php $__currentLoopData = $allHocKys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($hk->MaHocKy); ?>" 
                                <?php echo e(($currentHocKy && $currentHocKy->MaHocKy == $hk->MaHocKy) ? 'selected' : ''); ?>>
                                <?php echo e($hk->TenHocKy); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-2"></i>Xem kết quả
                </button>
            </form>
        </div>

        <?php if(!$currentHocKy): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-inbox fa-4x"></i>
                <h5>Chưa có dữ liệu</h5>
                <p>Không tìm thấy thông tin học kỳ. Vui lòng chọn học kỳ khác để xem chi tiết.</p>
            </div>
        <?php else: ?>
            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="icon-wrapper bg-info">
                        <i class="fas fa-flag"></i>
                    </div>
                    <div class="stat-label">Điểm Khởi Tạo</div>
                    <div class="stat-value"><?php echo e(number_format($diemGoc, 0)); ?></div>
                </div>

                <div class="stat-card">
                    <div class="icon-wrapper bg-success">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="stat-label">Điểm Cộng Thêm</div>
                    <div class="stat-value">+<?php echo e(number_format($diemCongThem, 0)); ?></div>
                </div>

                <div class="stat-card">
                    <div class="icon-wrapper bg-danger">
                        <i class="fas fa-minus-circle"></i>
                    </div>
                    <div class="stat-label">Điểm Trừ Đi</div>
                    <div class="stat-value text-danger"><?php echo e(number_format($diemTruDi, 0)); ?></div>
                </div>

                <div class="stat-card">
                    <div class="icon-wrapper bg-warning">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-label">Tổng Điểm</div>
                    <div class="stat-value"><?php echo e(number_format($tongDiemMoi, 0)); ?></div>
                    <span class="stat-rank"><?php echo e($xepLoaiMoi); ?></span>
                </div>
            </div>

            <!-- Activities Table -->
            <div class="table-card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-list-check me-2 text-primary"></i>
                        Chi tiết hoạt động - <?php echo e($currentHocKy->TenHocKy); ?>

                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">STT</th>
                                    <th>Tên hoạt động</th>
                                    <th class="text-center" style="width: 150px;">Ngày ghi nhận</th>
                                    <th class="text-center" style="width: 150px;">Điểm</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $cacHoatDongDaThamGia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dangKy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php 
                                        $diem = $dangKy->hoatdong && $dangKy->hoatdong->quydinh ? $dangKy->hoatdong->quydinh->DiemNhan : 0; 
                                    ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?php echo e($index + 1); ?></td>
                                        <td>
                                            <div class="activity-name"><?php echo e($dangKy->hoatdong->TenHoatDong ?? 'Không rõ tên'); ?></div>
                                            <div class="activity-code"><?php echo e($dangKy->hoatdong->MaHoatDong ?? 'N/A'); ?></div>
                                        </td>
                                        <td class="text-center">
                                            <?php if($dangKy->CheckOutAt): ?>
                                                <?php echo e(\Carbon\Carbon::parse($dangKy->CheckOutAt)->format('d/m/Y')); ?>

                                            <?php else: ?>
                                                <?php echo e(\Carbon\Carbon::parse($dangKy->NgayDangKy)->format('d/m/Y')); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($diem >= 0): ?>
                                                <span class="badge-points positive">
                                                    <i class="fas fa-plus me-1"></i><?php echo e($diem); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="badge-points negative">
                                                    <i class="fas fa-minus me-1"></i><?php echo e(abs($diem)); ?>

                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-muted mb-0">Chưa có hoạt động nào trong học kỳ này</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/diem_ren_luyen/index.blade.php ENDPATH**/ ?>