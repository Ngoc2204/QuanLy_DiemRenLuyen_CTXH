

<?php $__env->startSection('title', 'Duyệt Đăng ký Hoạt động'); ?>
<?php $__env->startSection('page_title', 'Duyệt Đăng ký Sinh viên'); ?>

<?php
// Breadcrumbs
$breadcrumbs = [
['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
['url' => route('nhanvien.duyet_dang_ky.index'), 'title' => 'Duyệt Đăng ký'],
];
?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper me-3">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold">Duyệt Đăng ký Hoạt động</h4>
                        <p class="text-muted mb-0 small">Quản lý và phê duyệt đăng ký tham gia hoạt động của sinh viên</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card modern-card mb-4">
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('nhanvien.duyet_dang_ky.index')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label for="type" class="form-label fw-semibold">
                            <i class="fa-solid fa-filter me-2 text-primary"></i>Loại Hoạt động
                        </label>
                        <select name="type" id="type" class="form-select modern-select">
                            <option value="">Tất cả loại hoạt động</option>
                            <option value="DRL" <?php echo e(request('type') == 'DRL' ? 'selected' : ''); ?>>
                                <i class="fa-solid fa-star"></i> Điểm Rèn Luyện (DRL)
                            </option>
                            <option value="CTXH" <?php echo e(request('type') == 'CTXH' ? 'selected' : ''); ?>>
                                <i class="fa-solid fa-hands-helping"></i> Công tác Xã hội (CTXH)
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <label for="date" class="form-label fw-semibold">
                            <i class="fa-solid fa-calendar-day me-2 text-primary"></i>Ngày đăng ký
                        </label>
                        <input type="date" name="date" id="date" class="form-control modern-select" value="<?php echo e(request('date')); ?>">
                    </div>

                    <div class="col-lg-2 col-md-3 col-6">
                        <button class="btn btn-primary w-100 modern-btn" type="submit">
                            <i class="fa-solid fa-search me-2"></i>Tìm kiếm
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-3 col-6">
                        <a href="<?php echo e(route('nhanvien.duyet_dang_ky.index')); ?>" class="btn btn-outline-secondary w-100 modern-btn">
                            <i class="fa-solid fa-rotate-right me-2"></i>Đặt lại
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if(request()->has('date') && request('date') != '' && $dangKys->count() > 0): ?>
    <div class="card modern-card mb-4">
        <div class="card-body p-4 bg-light-subtle">
            <form action="<?php echo e(route('nhanvien.duyet_dang_ky.batch_approve')); ?>" method="POST"
                onsubmit="return confirm('Bạn có chắc chắn muốn DUYỆT TẤT CẢ <?php echo e($dangKys->total()); ?> đăng ký cho ngày <?php echo e(request('date')); ?>?')">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="approve_date" value="<?php echo e(request('date')); ?>">
                <input type="hidden" name="approve_type" value="<?php echo e(request('type')); ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="fw-semibold text-success">
                        <i class="fa-solid fa-calendar-check me-2"></i>
                        Tìm thấy <span class="badge bg-success rounded-pill"><?php echo e($dangKys->total()); ?></span> đăng ký chờ duyệt cho ngày <?php echo e(\Carbon\Carbon::parse(request('date'))->format('d/m/Y')); ?>.
                    </div>
                    <button type="submit" class="btn btn-success modern-btn shadow-sm">
                        <i class="fa-solid fa-check-double me-2"></i>Duyệt tất cả theo ngày
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="card modern-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="text-center">#</th>
                            <th style="width: 25%;">Sinh viên</th>
                            <th style="width: 30%;">Hoạt động</th>
                            <th style="width: 12%;" class="text-center">Loại</th>
                            <th style="width: 13%;" class="text-center">Ngày đăng ký</th>
                            <th style="width: 15%;" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $dangKys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                        $hoatDong = $dk->hoatDongRel;
                        ?>
                        <tr class="table-row-hover">
                            <td class="text-center">
                                <span class="badge-number"><?php echo e($dangKys->firstItem() + $index); ?></span>
                            </td>
                            <td>
                                <?php if($dk->sinhVien): ?>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle">
                                        <span><?php echo e(strtoupper(substr($dk->sinhVien->HoTen, 0, 1))); ?></span>
                                    </div>
                                    <div class="ms-3">
                                        <div class="student-name"><?php echo e($dk->sinhVien->HoTen); ?></div>
                                        <div class="student-code"><?php echo e($dk->MSSV); ?></div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <span class="text-danger fw-semibold">
                                    <i class="fa-solid fa-exclamation-triangle me-1"></i><?php echo e($dk->MSSV); ?> (Lỗi)
                                </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($hoatDong): ?>
                                
                                <a href="<?php echo e($dk->type == 'DRL' ? route('nhanvien.hoatdong_drl.show', $hoatDong->MaHoatDong) : route('nhanvien.hoatdong_ctxh.show', $hoatDong->MaHoatDong)); ?>"
                                    target="_blank"
                                    class="activity-link">
                                    <?php echo e(Str::limit($hoatDong->TenHoatDong, 50)); ?>

                                    <i class="fa-solid fa-external-link ms-1"></i>
                                </a>
                                <div class="activity-code"><?php echo e($hoatDong->MaHoatDong); ?></div>
                                <?php else: ?>
                                
                                <span class="text-danger fw-semibold">
                                    <i class="fa-solid fa-times-circle me-1"></i>Không tìm thấy
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($dk->type == 'DRL'): ?>
                                <span class="badge-modern badge-drl">
                                    <i class="fa-solid fa-star me-1"></i>DRL
                                </span>
                                <?php else: ?>
                                <span class="badge-modern badge-ctxh">
                                    <i class="fa-solid fa-hands-helping me-1"></i>CTXH
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="date-display">
                                    <i class="fa-regular fa-calendar me-1"></i>
                                    <?php echo e(optional($dk->NgayDangKy)->format('d/m/Y')); ?>

                                </div>
                                <div class="time-display">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    <?php echo e(optional($dk->NgayDangKy)->format('H:i')); ?>

                                </div>
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    
                                    <form action="<?php echo e(route('nhanvien.duyet_dang_ky.update', ['duyet_dang_ky' => $dk->MaDangKy, 'type' => $dk->type])); ?>"
                                        method="POST"
                                        class="d-inline-block">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit"
                                            class="btn-action btn-approve"
                                            title="Phê duyệt"
                                            onclick="return confirm('Xác nhận duyệt đăng ký này?')">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>

                                    
                                    <form action="<?php echo e(route('nhanvien.duyet_dang_ky.update', ['duyet_dang_ky' => $dk->MaDangKy, 'type' => $dk->type])); ?>"
                                        method="POST"
                                        class="d-inline-block">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit"
                                            class="btn-action btn-reject"
                                            title="Từ chối"
                                            onclick="return confirm('Xác nhận từ chối đăng ký này?')">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-inbox"></i>
                                    </div>
                                    <h5 class="empty-title">Không có đăng ký chờ duyệt</h5>
                                    <p class="empty-text">Hiện tại không có đăng ký nào cần xử lý</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <?php if($dangKys->hasPages()): ?>
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    <i class="fa-solid fa-circle-info me-2"></i>
                    Hiển thị <strong><?php echo e($dangKys->firstItem()); ?></strong> - <strong><?php echo e($dangKys->lastItem()); ?></strong>
                    trong tổng số <strong><?php echo e($dangKys->total()); ?></strong> kết quả
                </div>
                <div class="pagination-links">
                    <?php echo e($dangKys->appends(request()->query())->links()); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* ===== MODERN DESIGN SYSTEM ===== */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
        --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Page Header */
    .page-header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 16px;
        color: white;
        box-shadow: var(--card-shadow);
    }

    .icon-wrapper {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    .icon-wrapper i {
        font-size: 1.75rem;
    }

    /* Modern Card */
    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
    }

    .modern-card:hover {
        box-shadow: var(--card-shadow-hover);
    }

    /* Modern Select & Inputs */
    .modern-select,
    .modern-input {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.625rem 1rem;
        transition: all 0.3s ease;
    }

    .modern-select:focus,
    .modern-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    /* Modern Button */
    .modern-btn {
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-primary.modern-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-primary.modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-outline-secondary.modern-btn {
        border: 2px solid #e5e7eb;
    }

    .btn-outline-secondary.modern-btn:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-2px);
    }

    /* Modern Table */
    .modern-table {
        font-size: 0.9375rem;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .modern-table thead th {
        font-weight: 700;
        color: #374151;
        padding: 1.25rem 1rem;
        border: none;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }

    .modern-table tbody td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .table-row-hover {
        transition: all 0.2s ease;
    }

    .table-row-hover:hover {
        background: linear-gradient(90deg, #fafbfc 0%, #f0f1f5 100%);
        transform: scale(1.01);
    }

    /* Badge Number */
    .badge-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.875rem;
    }

    /* Avatar Circle */
    .avatar-circle {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.125rem;
        flex-shrink: 0;
    }

    /* Student Info */
    .student-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 0.9375rem;
        margin-bottom: 2px;
    }

    .student-code {
        font-size: 0.8125rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* Activity Link */
    .activity-link {
        color: #1f2937;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .activity-link:hover {
        color: #667eea;
        transform: translateX(3px);
    }

    .activity-link i {
        font-size: 0.75rem;
        opacity: 0.6;
    }

    .activity-code {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 4px;
        font-weight: 500;
    }

    /* Badge Modern */
    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8125rem;
        display: inline-flex;
        align-items: center;
        letter-spacing: 0.025em;
    }

    .badge-drl {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .badge-ctxh {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
        color: white;
    }

    /* Date & Time Display */
    .date-display,
    .time-display {
        font-size: 0.8125rem;
        color: #6b7280;
        font-weight: 500;
    }

    .date-display {
        margin-bottom: 4px;
    }

    .date-display i,
    .time-display i {
        color: #9ca3af;
        font-size: 0.75rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 0.9375rem;
    }

    .btn-approve {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
        color: white;
    }

    .btn-approve:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 16px rgba(86, 171, 47, 0.3);
    }

    .btn-reject {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        color: white;
    }

    .btn-reject:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 16px rgba(235, 51, 73, 0.3);
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-icon {
        font-size: 4rem;
        color: #e5e7eb;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #9ca3af;
        font-size: 0.9375rem;
        margin: 0;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-top: 2px solid #f3f4f6;
        background: #fafbfc;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .pagination-info strong {
        color: #1f2937;
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header-card {
            padding: 1.5rem;
        }

        .icon-wrapper {
            width: 48px;
            height: 48px;
        }

        .icon-wrapper i {
            font-size: 1.5rem;
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .pagination-wrapper {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .table-row-hover {
        animation: fadeIn 0.3s ease;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/duyet_dang_ky/index.blade.php ENDPATH**/ ?>