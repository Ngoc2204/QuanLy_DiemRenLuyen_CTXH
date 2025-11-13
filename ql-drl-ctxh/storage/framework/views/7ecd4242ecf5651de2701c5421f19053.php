
<?php $__env->startSection('title', 'Điểm rèn luyện theo kỳ'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary: #667eea;
        --primary-light: #7c8ff5;
        --primary-dark: #5568d3;
        --secondary: #764ba2;
        --success: #10b981;
        --success-light: #6ee7b7;
        --warning: #f59e0b;
        --info: #3b82f6;
        --danger: #ef4444;
        --dark: #1a202c;
        --light: #f7fafc;
        --border: #e2e8f0;
    }

    .page-wrapper {
        background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
        min-height: 100vh;
        padding: 2.5rem 0;
    }

    /* Header Card */
    .header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 15px 50px rgba(102, 126, 234, 0.25);
        position: relative;
        overflow: hidden;
        animation: slideDown 0.6s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(30px); }
    }

    .header-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .header-card h3 {
        color: white;
        font-weight: 700;
        margin: 0;
        font-size: 2rem;
        position: relative;
        z-index: 1;
        letter-spacing: -0.5px;
    }

    .header-card .subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        margin-top: 0.75rem;
        position: relative;
        z-index: 1;
        font-weight: 400;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 18px;
        padding: 1.75rem 2rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border);
        animation: slideUp 0.6s ease-out 0.1s both;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .filter-card form {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .filter-card .form-label {
        color: var(--dark);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0;
    }

    .filter-card select {
        border-radius: 12px;
        border: 2px solid var(--border);
        padding: 0.85rem 1.25rem;
        transition: all 0.3s ease;
        font-size: 1rem;
        color: var(--dark);
        background-color: white;
        cursor: pointer;
        max-width: 350px;
    }

    .filter-card select:hover {
        border-color: var(--primary);
        background-color: #f9f9ff;
    }

    .filter-card select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .filter-card .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border: none;
        padding: 0.85rem 1.75rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-card .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.35);
    }

    .filter-card .btn-primary:active {
        transform: translateY(-1px);
    }

    /* Stat Card */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 2.5rem;
        animation: slideUp 0.6s ease-out 0.2s both;
    }

    .stat-card {
        background: white;
        border-radius: 18px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.12) 0%, transparent 100%);
        border-radius: 0 18px 0 100%;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        border-color: var(--primary);
    }

    .stat-card .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 1.75rem;
        color: white;
        position: relative;
        z-index: 1;
    }

    .stat-card .icon-wrapper.bg-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    }

    .stat-card .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.75rem;
    }

    .stat-card .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--dark);
        line-height: 1;
        margin-bottom: 1rem;
    }

    .stat-rank {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        position: relative;
        z-index: 1;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        border: none;
        animation: slideUp 0.6s ease-out 0.3s both;
    }

    .table-card .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e8f0f8 100%);
        border-bottom: 2px solid var(--border);
        padding: 1.5rem 2rem;
    }

    .table-card h5 {
        color: var(--dark);
        font-weight: 700;
        margin: 0;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-custom {
        margin: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        border-bottom: 2px solid var(--border);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #6c757d;
        padding: 1.25rem 1rem;
    }

    .table-custom tbody td {
        padding: 1.5rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
        color: var(--dark);
    }

    .table-custom tbody tr {
        transition: all 0.2s ease;
    }

    .table-custom tbody tr:hover {
        background: linear-gradient(135deg, #f9faff 0%, #f0f4f8 100%);
        box-shadow: inset 0 0 0 1px rgba(102, 126, 234, 0.1);
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    .table-info {
        background: linear-gradient(135deg, #e0f2fe 0%, #f0f7ff 100%);
        font-weight: 600;
    }

    .activity-name {
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.35rem;
    }

    .activity-code {
        color: #6c757d;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .badge {
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        display: inline-block;
    }

    .badge.bg-success-soft {
        background-color: #d1fae5;
        color: #065f46;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
    }

    .badge.bg-warning-soft {
        background-color: #fef3c7;
        color: #92400e;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15);
    }

    .badge.bg-primary-soft {
        background-color: #e0f2fe;
        color: #075985;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
    }

    .badge.bg-secondary-soft {
        background-color: #e5e7eb;
        color: #4b5563;
        box-shadow: 0 2px 8px rgba(107, 114, 128, 0.15);
    }

    .badge-points {
        display: inline-block;
        padding: 0.6rem 1.25rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .badge-points.positive {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .badge-points.negative {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }

    .badge-points {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #6c757d;
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 18px;
        padding: 4.5rem 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    .empty-state i {
        color: #cbd5e1;
        margin-bottom: 1.5rem;
        opacity: 0.6;
    }

    .empty-state h5 {
        color: #64748b;
        font-weight: 700;
        margin-bottom: 0.75rem;
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
            padding: 2rem 1.5rem;
        }

        .header-card h3 {
            font-size: 1.6rem;
        }

        .filter-card form {
            flex-direction: column;
            justify-content: center;
        }

        .filter-card select {
            max-width: 100%;
            width: 100%;
        }

        .table-custom thead th,
        .table-custom tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
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
            <form action="<?php echo e(route('sinhvien.diem_ren_luyen')); ?>" method="GET">
                <label for="hocky" class="form-label">
                    <i class="fas fa-calendar-alt"></i>Chọn học kỳ:
                </label>
                <select name="hocky" id="hocky" class="form-select">
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
                    <i class="fas fa-filter"></i>Xem kết quả
                </button>
            </form>
        </div>

        <?php if(!$currentHocKy): ?>
        <div class="empty-state">
            <i class="fas fa-inbox fa-4x"></i>
            <h5>Chưa có dữ liệu</h5>
            <p>Không tìm thấy thông tin học kỳ. Vui lòng chọn học kỳ khác để xem chi tiết.</p>
        </div>
        <?php else: ?>
        <div class="stats-container">
            <div class="stat-card">
                <div class="icon-wrapper bg-warning">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-label">TỔNG ĐIỂM RÈN LUYỆN</div>
                <div class="stat-value"><?php echo e(number_format($tongDiemMoi, 0)); ?></div>
                <span class="stat-rank"><?php echo e($xepLoaiMoi); ?></span>
            </div>
        </div>

        <div class="table-card">
            <div class="card-header">
                <h5>
                    <i class="fas fa-list-check"></i>
                    Chi tiết các mục ảnh hưởng đến điểm - <?php echo e($currentHocKy->TenHocKy); ?>

                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60px;">STT</th>
                                <th>Mục ghi nhận / Tên hoạt động</th>
                                <th class="text-center" style="width: 150px;">Ngày ghi nhận</th>
                                <th class="text-center" style="width: 150px;">Trạng thái</th>
                                <th class="text-center" style="width: 150px;">Điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $cacMucAnhHuongDenDiem; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $muc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                            $diem = $muc->diem;
                            $isBaseOrAdjust = in_array($muc->loai, ['goc', 'dieu_chinh']);
                            $isPositive = $diem >= 0;

                            $statusClass = '';
                            if ($muc->trang_thai === 'Đã tham gia' || $muc->loai === 'goc') $statusClass = 'badge bg-success-soft';
                            elseif ($muc->trang_thai === 'Chưa tham gia') $statusClass = 'badge bg-warning-soft';
                            elseif ($muc->trang_thai === 'Điều chỉnh') $statusClass = 'badge bg-primary-soft';
                            else $statusClass = 'badge bg-secondary-soft';
                            ?>
                            <tr class="<?php echo e($muc->loai == 'goc' ? 'table-info' : ''); ?>">
                                <td class="text-center fw-bold"><?php echo e($index + 1); ?></td>
                                <td>
                                    <div class="activity-name"><?php echo e($muc->ten); ?></div>
                                    <div class="activity-code"><?php echo e($muc->ma); ?></div>
                                </td>
                                <td class="text-center"><?php echo e($muc->ngay); ?></td>
                                <td class="text-center">
                                    <span class="<?php echo e($statusClass); ?>"><?php echo e($muc->trang_thai); ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if($isBaseOrAdjust): ?>
                                    <span class="badge-points <?php echo e($isPositive ? 'positive' : 'negative'); ?>">
                                        <?php echo e($isPositive ? '+' : ''); ?><?php echo e(number_format($diem, 0)); ?>

                                    </span>
                                    <?php elseif($diem != 0 && $muc->trang_thai == 'Đã tham gia'): ?>
                                    <span class="badge-points <?php echo e($isPositive ? 'positive' : 'negative'); ?>">
                                        <?php echo e($isPositive ? '+' : ''); ?><?php echo e(number_format($diem, 0)); ?>

                                    </span>
                                    <?php else: ?>
                                    <span class="badge-points">0</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Chưa có dữ liệu nào được ghi nhận trong học kỳ này</p>
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