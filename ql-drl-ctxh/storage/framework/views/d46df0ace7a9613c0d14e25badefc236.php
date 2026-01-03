
<?php $__env->startSection('title', 'Quản lý Thanh toán (Chờ duyệt)'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --primary-color: #4f46e5;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #3b82f6;
        --light-bg: #f9fafb;
        --border-color: #e5e7eb;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #6366f1 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .page-header h3 {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        opacity: 0.9;
        margin-bottom: 0;
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .stats-card .icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stats-card.pending .icon {
        background: #fef3c7;
        color: var(--warning-color);
    }

    .stats-card.total .icon {
        background: #dbeafe;
        color: var(--info-color);
    }

    .search-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1px solid #d1d5db;
        padding: 0.625rem 1rem;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.625rem 1.25rem;
        transition: all 0.2s;
    }

    .btn-primary {
        background: var(--primary-color);
        border: none;
    }

    .btn-primary:hover {
        background: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
    }

    .data-table-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border-color);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: var(--light-bg);
        color: #374151;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }

    .table tbody tr {
        transition: all 0.2s;
    }

    .table tbody tr:hover {
        background-color: #fafbfc;
    }

    .badge {
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.025em;
    }

    .badge-cash {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .badge-online {
        background-color: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    .badge-id {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        font-family: 'Courier New', monospace;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: 1px solid #fbbf24;
        display: inline-block;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
    }

    .student-details .name {
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.125rem;
    }

    .student-details .mssv {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .amount-display {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--danger-color);
    }

    .btn-action {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-success {
        background: var(--success-color);
        border: none;
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
    }

    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background: var(--light-bg);
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem;
        border-radius: 16px 16px 0 0;
    }

    .modal-title {
        font-weight: 700;
        color: #111827;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 1.5rem;
    }

    .list-group-item {
        border: none;
        padding: 0.875rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #9ca3af;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
    }

    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .id-group {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }

    .id-badge {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
        width: fit-content;
    }

    .id-badge.primary {
        background: #dbeafe;
        color: #1e40af;
    }

    .id-badge.secondary {
        background: #e5e7eb;
        color: #374151;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .table {
            font-size: 0.875rem;
        }

        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="page-header">
        <h3><i class="fas fa-money-check-alt me-2"></i>Quản lý Thanh toán</h3>
        <p>Xác nhận và quản lý các giao dịch thanh toán đang chờ xử lý</p>
    </div>

    
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="stats-card pending">
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h6 class="text-muted mb-1">Chờ xác nhận</h6>
                <h3 class="mb-0"><?php echo e($thanhToans->total()); ?> giao dịch</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card total">
                <div class="icon">
                    <i class="fas fa-coins"></i>
                </div>
                <h6 class="text-muted mb-1">Tổng giá trị</h6>
                <h3 class="mb-0"><?php echo e(number_format($thanhToans->sum('TongTien'), 0, ',', '.')); ?> đ</h3>
            </div>
        </div>
    </div>

    
    <div class="search-card mb-4">
        <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Bộ lọc tìm kiếm</h6>
        <form action="<?php echo e(route('nhanvien.thanhtoan.index')); ?>" method="GET" class="row g-3">
            <div class="col-md-5">
                <label for="search_mssv" class="form-label">
                    <i class="fas fa-search me-1"></i>Tìm theo MSSV
                </label>
                <input type="text" class="form-control" id="search_mssv" name="search_mssv" value="<?php echo e(request('search_mssv')); ?>" placeholder="Nhập MSSV sinh viên...">
            </div>
            <div class="col-md-4">
                <label for="phuong_thuc" class="form-label">
                    <i class="fas fa-credit-card me-1"></i>Phương thức thanh toán
                </label>
                <select class="form-select" id="phuong_thuc" name="phuong_thuc">
                    <option value="">Tất cả phương thức</option>
                    <option value="TienMat" <?php echo e(request('phuong_thuc') == 'TienMat' ? 'selected' : ''); ?>>Tiền mặt</option>
                    <option value="Online" <?php echo e(request('phuong_thuc') == 'Online' ? 'selected' : ''); ?>>Chuyển khoản Online</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Tìm kiếm
                </button>
            </div>
        </form>
    </div>

    
    <div class="data-table-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Mã giao dịch</th>
                        <th scope="col">Sinh viên</th>
                        <th scope="col">Hoạt động</th>
                        <th scope="col">Số tiền</th>
                        <th scope="col">Phương thức</th>
                        <th scope="col">Nội dung CK</th>
                        <th scope="col" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $thanhToans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                    $donDangKy = $tt->dangKyHoatDong;
                    $sinhVien = $donDangKy?->sinhvien;
                    $hoatDong = $donDangKy?->hoatdong;
                    $initials = $sinhVien ? strtoupper(substr($sinhVien->HoTen, 0, 2)) : '??';
                    ?>
                    <tr>
                        <td>
                            <div class="id-group">
                                <span class="id-badge primary">HD-<?php echo e($tt->id); ?></span>
                                <?php if($donDangKy): ?>
                                <span class="id-badge secondary">DK-<?php echo e($donDangKy->MaDangKy); ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php if($sinhVien): ?>
                            <div class="student-info">
                                
                                <div class="student-details">
                                    <div class="name"><?php echo e($sinhVien->HoTen); ?></div>
                                    <div class="mssv"><?php echo e($sinhVien->MSSV); ?></div>
                                </div>
                            </div>
                            <?php else: ?>
                            <span class="text-muted">Không có thông tin</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($hoatDong): ?>
                            <div class="fw-semibold text-dark"><?php echo e($hoatDong->TenHoatDong); ?></div>
                            <?php else: ?>
                            <span class="text-muted fst-italic">Hoạt động đã bị xóa</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="amount-display"><?php echo e(number_format($tt->TongTien, 0, ',', '.')); ?> đ</div>
                        </td>
                        <td>
                            <?php if($tt->PhuongThuc == 'TienMat'): ?>
                            <span class="badge badge-cash">
                                <i class="fas fa-money-bill-wave me-1"></i>Tiền mặt
                            </span>
                            <?php elseif($tt->PhuongThuc == 'Online'): ?>
                            <span class="badge badge-online">
                                <i class="fas fa-university me-1"></i>Online
                            </span>
                            <?php else: ?>
                            <span class="badge bg-light text-dark">Chưa chọn</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($donDangKy): ?>
                            <div class="badge-id">DK<?php echo e($donDangKy->MaDangKy); ?></div>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#confirmModal-<?php echo e($tt->id); ?>">
                                <i class="fas fa-check-circle me-1"></i>Xác nhận
                            </button>
                        </td>
                    </tr>

                    
                    <div class="modal fade" id="confirmModal-<?php echo e($tt->id); ?>" tabindex="-1" aria-labelledby="modalLabel-<?php echo e($tt->id); ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel-<?php echo e($tt->id); ?>">
                                        <i class="fas fa-check-circle text-success me-2"></i>Xác nhận thanh toán
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="<?php echo e(route('nhanvien.thanhtoan.xacnhan', $tt->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="modal-body">
                                        <div class="alert alert-info mb-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Vui lòng kiểm tra kỹ thông tin trước khi xác nhận
                                        </div>

                                        <ul class="list-group list-group-flush mb-3">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="text-muted">
                                                    <i class="fas fa-user me-2"></i>Sinh viên
                                                </span>
                                                <strong><?php echo e($sinhVien?->HoTen); ?> (<?php echo e($sinhVien?->MSSV); ?>)</strong>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="text-muted">
                                                    <i class="fas fa-calendar-alt me-2"></i>Hoạt động
                                                </span>
                                                <strong><?php echo e($hoatDong?->TenHoatDong ?? 'N/A'); ?></strong>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="text-muted">
                                                    <i class="fas fa-money-bill-wave me-2"></i>Số tiền
                                                </span>
                                                <strong class="text-danger fs-5"><?php echo e(number_format($tt->TongTien, 0, ',', '.')); ?> đ</strong>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="text-muted">
                                                    <i class="fas fa-credit-card me-2"></i>Phương thức
                                                </span>
                                                <strong>
                                                    <?php if($tt->PhuongThuc == 'TienMat'): ?>
                                                    <span class="badge badge-cash">Tiền mặt</span>
                                                    <?php else: ?>
                                                    <span class="badge badge-online">Online</span>
                                                    <?php endif; ?>
                                                </strong>
                                            </li>
                                        </ul>

                                        <?php if($tt->PhuongThuc == 'Online'): ?>
                                        <div class="mb-3">
                                            <label for="ma_giao_dich-<?php echo e($tt->id); ?>" class="form-label">
                                                <i class="fas fa-hashtag me-1"></i>Mã giao dịch ngân hàng
                                            </label>
                                            <input type="text" class="form-control" name="ma_giao_dich" id="ma_giao_dich-<?php echo e($tt->id); ?>" placeholder="Nhập mã giao dịch (nếu có)...">
                                            <small class="text-muted">Mã này sẽ giúp đối soát dễ dàng hơn</small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>Hủy bỏ
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check-circle me-2"></i>Xác nhận thanh toán
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h5>Không có giao dịch nào</h5>
                                <p>Hiện tại không có giao dịch nào đang chờ xác nhận</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($thanhToans->hasPages()): ?>
        <div class="p-3 border-top">
            <?php echo e($thanhToans->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/thanhtoan/index.blade.php ENDPATH**/ ?>