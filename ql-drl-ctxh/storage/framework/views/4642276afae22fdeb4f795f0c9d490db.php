

<?php $__env->startSection('title', 'Quản lý nhân viên'); ?>
<?php $__env->startSection('page_title', 'Danh sách nhân viên'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    body {
        background-color: #f5f7fa;
    }

    .page-header {
        background: white;
        padding: 1.5rem 0;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    }

    .page-header h4 {
        margin: 0;
        color: #1e293b;
        font-weight: 600;
    }

    /* Modern Stats Cards - Horizontal Layout */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* Icon colors for employee section */
    .stat-card:nth-child(1) .stat-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-card:nth-child(2) .stat-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .stat-card:nth-child(3) .stat-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .stat-card:nth-child(4) .stat-icon {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }


    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .stat-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h5 {
        margin: 0;
        color: #1e293b;
        font-weight: 600;
        font-size: 1.125rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-add {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }

    /* Search Box */
    .search-box {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 0;
    }

    .search-box form {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        gap: 0.5rem;
    }

    .search-box .form-control,
    .search-box .form-select {
        min-width: 180px;
    }

    .form-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        height: 42px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .search-actions {
        display: flex;
        gap: 0.5rem;
        height: 100%;
    }

    .btn-search {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        flex: 1;
        transition: all 0.3s ease;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 42px;
        width: 42px;
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #000;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-reset:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #475569;
        transform: translateY(-2px);
    }

    /* Table */
    .table-container {
        padding: 1.5rem;
    }

    .table {
        margin: 0;
        width: 100%;
    }

    .table-responsive {
        width: 100%;
    }

    .table thead th {
        background: #f8fafc;
        color: #475569;
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.8125rem;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: none;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-info {
        background: #3b82f6;
        color: white;
    }

    .btn-info:hover {
        background: #2563eb;
        color: white;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
        color: white;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 1rem;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pagination-info {
        color: #64748b;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 1.25rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng nhân viên</div>
                
                <div class="stat-value"><?php echo e($totalNhanViens ?? 0); ?></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Kết quả tìm kiếm</div>
                <div class="stat-value"><?php echo e($nhanviens->total()); ?></div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="main-card">
        <div class="card-header">
            <h5>
                <i class="fa-solid fa-briefcase"></i>
                Danh sách nhân viên
            </h5>
            <a href="<?php echo e(route('admin.nhanvien.create')); ?>" class="btn-add">
                <i class="fa-solid fa-plus"></i>
                Thêm mới
            </a>
        </div>

        <!-- Search Form -->
        <div class="search-box">
            <form method="GET" action="<?php echo e(route('admin.nhanvien.index')); ?>" class="search-form d-flex align-items-center gap-2 flex-wrap">
                <input type="text" name="keyword" value="<?php echo e(request('keyword')); ?>"
                    class="form-control flex-grow-1" style="min-width: 700px;"
                    placeholder="Nhập mã NV hoặc tên nhân viên...">

                <button type="submit" class="btn-search px-4">
                    <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                </button>

                <?php if(request()->hasAny(['keyword'])): ?>
                <a href="<?php echo e(route('admin.nhanvien.index')); ?>" class="btn-reset" title="Đặt lại">
                    <i class="fa-solid fa-refresh"></i>
                </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">STT</th>
                            <th>Mã nhân viên</th>
                            <th>Họ và tên</th>
                            <th>Email</th>
                            <th>SĐT</th>
                            <th style="width: 130px;" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $nhanviens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $nv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-muted"><?php echo e($nhanviens->firstItem() + $index); ?></td>
                            <td><strong style="color: #667eea;"><?php echo e($nv->MaNV); ?></strong></td>
                            <td>
                                <span style="font-weight: 500;"><?php echo e($nv->TenNV); ?></span>
                            </td>
                            <td><?php echo e($nv->Email ?? '—'); ?></td>
                            <td><?php echo e($nv->SDT ?? '—'); ?></td>
                            <td class="text-center">
                                
                                <a href="<?php echo e(route('admin.nhanvien.show', $nv->MaNV)); ?>"
                                    class="btn-action btn-info me-1" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.nhanvien.edit', $nv->MaNV)); ?>"
                                    class="btn-action btn-warning me-1" title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="<?php echo e(route('admin.nhanvien.destroy', $nv->MaNV)); ?>"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa nhân viên này?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-action btn-danger" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fa-solid fa-inbox"></i>
                                    <p class="mb-2">Không tìm thấy nhân viên nào</p>
                                    <?php if(request()->hasAny(['keyword'])): ?>
                                    <a href="<?php echo e(route('admin.nhanvien.index')); ?>" class="btn btn-sm btn-outline-primary">
                                        Xem tất cả nhân viên
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if($nhanviens->hasPages()): ?>
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Hiển thị <?php echo e($nhanviens->firstItem()); ?> - <?php echo e($nhanviens->lastItem()); ?>

                trong tổng số <strong><?php echo e($nhanviens->total()); ?></strong> nhân viên
            </div>
            <div>
                <?php echo e($nhanviens->appends(request()->query())->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/nhanvien/index.blade.php ENDPATH**/ ?>