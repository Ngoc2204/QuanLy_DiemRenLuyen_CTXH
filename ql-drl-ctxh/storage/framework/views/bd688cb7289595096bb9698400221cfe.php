

<?php $__env->startSection('title', 'Quản lý Ban Cán Sự Lớp'); ?>
<?php $__env->startSection('page_title', 'Quản lý Ban Cán Sự Lớp'); ?>

<?php
$breadcrumbs = [
    ['url' => route('giangvien.home'), 'title' => 'Bảng điều khiển'],
    ['url' => route('giangvien.lop.bancansu'), 'title' => 'Ban Cán Sự Lớp'],
];
?>

<?php $__env->startSection('content'); ?>

<div class="row g-3 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="stats-card">
            <div class="stats-icon bg-primary-subtle text-primary">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stats-content">
                <div class="stats-label">Tổng sinh viên</div>
                <div class="stats-value"><?php echo e($sinhviens->count()); ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stats-card">
            <div class="stats-icon bg-success-subtle text-success">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="stats-content">
                <div class="stats-label">Có chức vụ</div>
                <div class="stats-value"><?php echo e($sinhviens->filter(fn($sv) => $sv->chucVus->first())->count()); ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stats-card">
            <div class="stats-icon bg-info-subtle text-info">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div class="stats-content">
                <div class="stats-label">Lớp cố vấn</div>
                <div class="stats-value"><?php echo e($lopCoVanList->count()); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card modern-card">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex align-items-center">
            <div class="header-icon">
                <i class="fa-solid fa-filter"></i>
            </div>
            <h5 class="mb-0 fw-bold">Bộ lọc và tìm kiếm</h5>
        </div>
    </div>
    
    <div class="card-body p-4">
        
        <form method="GET" action="<?php echo e(route('giangvien.lop.bancansu')); ?>">
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <label for="ma_lop" class="form-label fw-semibold">
                        <i class="fa-solid fa-school me-1 text-primary"></i>
                        Lớp Cố Vấn
                    </label>
                    <select name="ma_lop" id="ma_lop" class="form-select modern-select">
                        <?php $__currentLoopData = $lopCoVanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($lop->MaLop); ?>" <?php echo e($lop->MaLop == $selectedLop ? 'selected' : ''); ?>>
                                <?php echo e($lop->MaLop); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-lg-4 col-md-6">
                    <label for="hoc_ky" class="form-label fw-semibold">
                        <i class="fa-solid fa-calendar-days me-1 text-success"></i>
                        Học Kỳ
                    </label>
                    <select name="hoc_ky" id="hoc_ky" class="form-select modern-select">
                        <?php $__currentLoopData = $hocKys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hocKy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($hocKy->MaHocKy); ?>" <?php echo e($hocKy->MaHocKy == $selectedHocKy ? 'selected' : ''); ?>>
                                <?php echo e($hocKy->TenHocKy); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-lg-2 col-md-12">
                    <label for="search" class="form-label fw-semibold">
                        <i class="fa-solid fa-magnifying-glass me-1 text-info"></i>
                        Tìm kiếm sinh viên
                    </label>
                    <input type="text" name="search" id="search" class="form-control modern-select" 
                           value="<?php echo e($search ?? ''); ?>" placeholder="Nhập tên hoặc MSSV...">
                </div>

                <div class="col-lg-2 col-md-12">
                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-fill modern-btn" type="submit" title="Áp dụng bộ lọc">
                            <i class="fa-solid fa-filter me-1"></i> Lọc
                        </button>
                        <a href="<?php echo e(route('giangvien.lop.bancansu')); ?>" 
                           class="btn btn-outline-secondary modern-btn" 
                           title="Đặt lại bộ lọc">
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="card modern-card mt-4">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="header-icon">
                    <i class="fa-solid fa-list"></i>
                </div>
                <h5 class="mb-0 fw-bold">Danh sách sinh viên</h5>
            </div>
            <span class="badge bg-primary rounded-pill"><?php echo e($sinhviens->count()); ?> sinh viên</span>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table modern-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">STT</th>
                        <th style="width: 15%;">MSSV</th>
                        <th style="width: 30%;">Họ và Tên</th>
                        <th class="text-center" style="width: 25%;">
                            Chức vụ 
                            <span class="badge bg-info-subtle text-info ms-1">HK: <?php echo e($selectedHocKy); ?></span>
                        </th>
                        <th class="text-center" style="width: 25%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $sinhviens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $chucVuHienTai = $sv->chucVus->first();
                    ?>
                    <tr class="table-row-hover">
                        <td class="text-center">
                            <div class="index-badge"><?php echo e($index + 1); ?></div>
                        </td>
                        <td>
                            <div class="student-code">
                                <i class="fa-solid fa-id-card me-1 text-muted"></i>
                                <?php echo e($sv->MSSV); ?>

                            </div>
                        </td>
                        <td>
                            <div class="student-info">
                                <div class="student-name">
                                    <i class="fa-solid fa-user me-1 text-primary"></i>
                                    <?php echo e($sv->HoTen); ?>

                                </div>
                            </div>
                        </td>
                        
                        <form action="<?php echo e(route('giangvien.lop.bancansu.update')); ?>" method="POST" class="role-update-form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="mssv" value="<?php echo e($sv->MSSV); ?>">
                            <input type="hidden" name="ma_lop" value="<?php echo e($selectedLop); ?>">
                            <input type="hidden" name="ma_hoc_ky" value="<?php echo e($selectedHocKy); ?>">
                            
                            <td class="text-center">
                                <select name="chuc_vu" class="form-select form-select-sm modern-select role-select" style="min-width: 160px;">
                                    <option value="none">-- Không có chức vụ --</option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role); ?>" 
                                            <?php echo e(($chucVuHienTai && $chucVuHienTai->ChucVu == $role) ? 'selected' : ''); ?>>
                                            <?php echo e($role); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="submit" class="btn btn-sm btn-primary modern-btn">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu thay đổi
                                </button>
                            </td>
                        </form>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fa-solid fa-user-slash"></i>
                                </div>
                                <h5 class="empty-title">Không tìm thấy sinh viên</h5>
                                <p class="empty-text text-muted">
                                    Không có sinh viên nào phù hợp với điều kiện tìm kiếm.<br>
                                    Vui lòng thử lại với bộ lọc khác.
                                </p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php if(session('view_name') == 'giangvien.lop.bancansu_empty'): ?>
<div class="card modern-card mt-4">
    <div class="card-body text-center py-5">
        <div class="empty-state">
            <div class="empty-icon warning">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h5 class="empty-title">Chưa được phân công</h5>
            <p class="empty-text">
                Bạn hiện chưa được phân công làm cố vấn học tập cho bất kỳ lớp nào.<br>
                Vui lòng liên hệ phòng đào tạo để được hỗ trợ.
            </p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
@import url('https://pastebin.com/raw/L8C35G0J');

/* Stats Cards */
.stats-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.stats-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stats-content {
    flex: 1;
}

.stats-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.stats-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #212529;
    line-height: 1;
}

/* Card Header */
.card-header {
    padding: 1.25rem 1.5rem;
}

.header-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    margin-right: 1rem;
}

/* Form Styling */
.form-label {
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.modern-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.625rem 1rem;
    transition: all 0.3s ease;
}

.modern-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

/* Table Styling */
.modern-table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    padding: 1rem;
    border: none;
}

.modern-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

.table-row-hover {
    transition: all 0.2s ease;
}

.table-row-hover:hover {
    background-color: #f8f9fa;
    transform: scale(1.002);
}

/* Index Badge */
.index-badge {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Student Info */
.student-code {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.student-name {
    font-weight: 600;
    color: #212529;
    font-size: 0.95rem;
}

/* Role Select */
.role-select {
    font-size: 0.875rem;
}

/* Button Styling */
.modern-btn {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.875rem;
}

.btn-primary.modern-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-primary.modern-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-outline-secondary.modern-btn {
    border: 2px solid #dee2e6;
}

.btn-outline-secondary.modern-btn:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
    transform: translateY(-2px);
}

/* Empty State */
.empty-state {
    padding: 2rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
}

.empty-icon.warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.empty-title {
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.75rem;
}

.empty-text {
    color: #6c757d;
    font-size: 0.95rem;
    line-height: 1.6;
}

/* Badge */
.badge {
    padding: 0.5rem 0.875rem;
    font-weight: 500;
    font-size: 0.8rem;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-card {
        padding: 1rem;
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .stats-value {
        font-size: 1.5rem;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .modern-table {
        font-size: 0.875rem;
    }
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.giangvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/giangvien/lop/bancansu.blade.php ENDPATH**/ ?>