

<?php $__env->startSection('title', 'Xem điểm CTXH'); ?>
<?php $__env->startSection('page_title', 'Xem điểm CTXH'); ?>

<?php
$breadcrumbs = [
    ['url' => route('giangvien.home'), 'title' => 'Bảng điều khiển'],
    ['url' => route('giangvien.lop.diem_ctxh'), 'title' => 'Xem điểm CTXH'],
];
?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="header-section">
                <div class="header-content">
                    <h1 class="header-title">
                        <i class="fa-solid fa-chart-simple me-3"></i>Điểm Công Tác Xã Hội
                    </h1>
                    <p class="header-subtitle">Quản lý và theo dõi điểm công tác xã hội của sinh viên</p>
                </div>
                <div class="header-stats">
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-info">
                            <span class="stat-label">Tổng sinh viên</span>
                            <span class="stat-value"><?php echo e($sinhviens->total()); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card">
                <form method="GET" action="<?php echo e(route('giangvien.lop.diem_ctxh')); ?>" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-8">
                            <label for="ma_lop" class="form-label fw-semibold mb-2">
                                <i class="fa-solid fa-filter me-2"></i>Lọc theo Lớp
                            </label>
                            <select name="ma_lop" id="ma_lop" class="form-select filter-select">
                                <option value="">-- Tất cả các lớp --</option>
                                <?php $__currentLoopData = $lopCoVanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($lop->MaLop); ?>" <?php echo e($lop->MaLop == $selectedLop ? 'selected' : ''); ?>>
                                        <?php echo e($lop->TenLop); ?> (<?php echo e($lop->MaLop); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <button class="btn btn-filter w-100" type="submit">
                                <i class="fa-solid fa-magnifying-glass me-2"></i>Tìm kiếm
                            </button>
                            <?php if($selectedLop): ?>
                                <a href="<?php echo e(route('giangvien.lop.diem_ctxh')); ?>" class="btn btn-light w-100 mt-2">
                                    <i class="fa-solid fa-redo me-2"></i>Reset
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="table-card">
                <?php $__empty_1 = true; $__currentLoopData = $sinhviens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if($index === 0): ?>
                    <div class="table-responsive">
                        <table class="table data-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%;">
                                        <span class="table-header-text">STT</span>
                                    </th>
                                    <th style="width: 18%;">
                                        <span class="table-header-text">MSSV</span>
                                    </th>
                                    <th style="width: 32%;">
                                        <span class="table-header-text">Họ Tên</span>
                                    </th>
                                    <th style="width: 23%;">
                                        <span class="table-header-text">Lớp</span>
                                    </th>
                                    <th class="text-center" style="width: 22%;">
                                        <span class="table-header-text">Tổng Điểm CTXH</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                    <?php endif; ?>
                                <tr class="table-row">
                                    <td class="text-center">
                                        <span class="row-number"><?php echo e($sinhviens->firstItem() + $index); ?></span>
                                    </td>
                                    <td>
                                        <span class="student-id"><?php echo e($sv->MSSV); ?></span>
                                    </td>
                                    <td>
                                        <span class="student-name"><?php echo e($sv->HoTen); ?></span>
                                    </td>
                                    <td>
                                        <span class="class-badge"><?php echo e($sv->MaLop); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            $tongDiem = $sv->diemCtxh->TongDiem ?? 0;
                                        ?>
                                        <span class="score-badge <?php echo e($tongDiem >= 80 ? 'score-high' : ($tongDiem >= 60 ? 'score-medium' : 'score-low')); ?>">
                                            <?php echo e($tongDiem); ?>

                                        </span>
                                    </td>
                                </tr>
                    <?php if($loop->last): ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state-container">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fa-solid fa-inbox"></i>
                            </div>
                            <h5 class="empty-title">Không có dữ liệu</h5>
                            <p class="empty-text">Không tìm thấy sinh viên nào khớp với bộ lọc của bạn.</p>
                            <?php if($selectedLop): ?>
                                <a href="<?php echo e(route('giangvien.lop.diem_ctxh')); ?>" class="btn btn-sm btn-outline-primary mt-3">
                                    <i class="fa-solid fa-redo me-2"></i>Xóa bộ lọc
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php if($sinhviens->hasPages()): ?>
                <div class="pagination-container">
                    <?php echo e($sinhviens->appends(request()->query())->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
:root {
    --primary-color: #3b82f6;
    --primary-dark: #1e40af;
    --primary-light: #eff6ff;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --border-radius: 12px;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

* {
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container-fluid {
    padding: 2rem;
}

/* Header Section */
.header-section {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: var(--shadow-md);
    margin-bottom: 1rem;
}

.header-content h1 {
    margin: 0;
    color: #1f2937;
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.header-subtitle {
    margin: 0.5rem 0 0 2.5rem;
    color: #6b7280;
    font-size: 0.95rem;
}

.header-stats {
    display: flex;
    gap: 1.5rem;
}

.stat-box {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary-color);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-color);
    color: white;
    border-radius: 50%;
    font-size: 1.25rem;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 500;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--primary-dark);
}

/* Filter Card */
.filter-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
}

.filter-select {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.filter-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

.btn-filter {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: white;
}

.btn-filter:active {
    transform: translateY(0);
}

/* Table Card */
.table-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.data-table {
    margin-bottom: 0;
}

.data-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 2px solid #e2e8f0;
}

.table-header-text {
    color: #475569;
    font-weight: 700;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-row {
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.table-row:hover {
    background: #f8fafc;
    box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.05);
}

.row-number {
    color: #9ca3af;
    font-weight: 600;
    font-size: 0.95rem;
}

.student-id {
    font-family: 'Courier New', monospace;
    color: #374151;
    font-weight: 600;
    font-size: 0.95rem;
}

.student-name {
    color: #1f2937;
    font-weight: 500;
    font-size: 0.95rem;
}

.class-badge {
    display: inline-block;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    color: var(--primary-dark);
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.85rem;
    border-left: 3px solid var(--primary-color);
}

.score-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.95rem;
    min-width: 60px;
    text-align: center;
}

.score-high {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    box-shadow: 0 0 0 1px #6ee7b7;
}

.score-medium {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #78350f;
    box-shadow: 0 0 0 1px #fcd34d;
}

.score-low {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #7f1d1d;
    box-shadow: 0 0 0 1px #fca5a5;
}

/* Empty State */
.empty-state-container {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.empty-icon {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.empty-title {
    color: #374151;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #9ca3af;
    font-size: 0.95rem;
    margin: 0;
}

/* Pagination */
.pagination-container {
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
    background: #fafbfc;
    display: flex;
    justify-content: center;
}

.pagination {
    gap: 0.5rem;
}

.pagination .page-link {
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    color: var(--primary-color);
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    background: var(--primary-light);
    border-color: var(--primary-color);
}

.pagination .page-item.active .page-link {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

/* Responsive */
@media (max-width: 768px) {
    .header-section {
        flex-direction: column;
        gap: 1.5rem;
    }

    .header-content h1 {
        font-size: 1.5rem;
    }

    .header-subtitle {
        margin-left: 2rem;
    }

    .header-stats {
        width: 100%;
        justify-content: center;
    }

    .stat-box {
        flex: 1;
        min-width: 200px;
    }

    .table-responsive {
        font-size: 0.85rem;
    }

    .student-id,
    .student-name,
    .score-badge {
        font-size: 0.85rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 1rem;
    }

    .header-content h1 {
        font-size: 1.25rem;
    }

    .header-subtitle {
        margin-left: 1.5rem;
        font-size: 0.85rem;
    }

    .filter-card {
        padding: 1rem;
    }

    .btn-filter {
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
    }
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.giangvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/giangvien/lop/diem_ctxh.blade.php ENDPATH**/ ?>