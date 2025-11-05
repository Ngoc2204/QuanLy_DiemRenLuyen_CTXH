

<?php $__env->startSection('title', 'Quản lý Quy định điểm ctxh'); ?>
<?php $__env->startSection('page_title', 'Danh sách Quy định Điểm Công Tác Xã Hội'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* SAO CHÉP CSS TỪ GIAO DIỆN MẪU (Sinh viên/Nhân viên) */
    body {
        background-color: #f5f7fa;
    }

    /* Bỏ thẻ thống kê vì không có dữ liệu */

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-top: 2rem;
        /* Thêm margin top nếu không có thẻ thống kê */
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
        text-decoration: none;
        /* Thêm */
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
        /* Giữ trên 1 hàng */
        align-items: center;
        gap: 0.75rem;
        /* Tăng gap */
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
        /* flex: 1; */
        /* Bỏ flex 1 nếu không muốn nút chiếm hết */
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
        color: #475569;
        /* Đổi màu icon */
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        /* Thêm */
    }

    .btn-reset:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #1e293b;
        /* Đậm hơn khi hover */
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

    /* Badge cho điểm */
    .badge-diem {
        background-color: #dcfce7;
        /* Màu xanh lá nhạt */
        color: #15803d;
        /* Màu xanh lá đậm */
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.875rem;
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

    .table td.text-center {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        /* Khoảng cách giữa các nút */
    }


    .btn-action:hover {
        transform: translateY(-2px);
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

    /* Empty State */
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

    .empty-state .btn-outline-primary {
        /* Style lại nút trong empty state */
        border-color: #667eea;
        color: #667eea;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    .empty-state .btn-outline-primary:hover {
        background-color: #667eea;
        color: white;
    }


    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        /* Thêm wrap cho mobile */
        gap: 1rem;
        /* Thêm gap */
    }

    .pagination-info {
        color: #64748b;
        font-size: 0.875rem;
    }

    /* Style phân trang bootstrap 5 */
    .pagination {
        margin-bottom: 0;
    }

    .page-item .page-link {
        color: #667eea;
        border-radius: 6px;
        margin: 0 2px;
        border: 1px solid #e2e8f0;
    }

    .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
        z-index: 1;
        /* Fix active link bị che */
    }

    .page-item.disabled .page-link {
        color: #cbd5e1;
        border-color: #e2e8f0;
    }

    .page-item .page-link:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
        z-index: 2;
        /* Fix hover link bị che */
    }


    /* Responsive */
    @media (max-width: 768px) {
        .search-box form {
            flex-wrap: wrap;
            /* Cho phép xuống dòng trên mobile */
        }

        .search-box .form-control {
            min-width: 0;
            /* Bỏ min-width trên mobile */
            flex-grow: 1;
            /* Input chiếm phần lớn */
        }

        .btn-search,
        .btn-reset {
            width: auto;
            /* Nút tự động co giãn */
        }

        .pagination-wrapper {
            flex-direction: column;
            /* Stack phân trang trên mobile */
            align-items: flex-start;
        }

    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

    <!-- Main Card -->
    <div class="main-card">
        <div class="card-header">
            <h5>
                <i class="fa-solid fa-clipboard-check text-primary"></i>
                Danh sách Quy định Điểm Công Tác Xã Hội
            </h5>
            
            <a href="<?php echo e(route('admin.quydinhdiemctxh.create')); ?>" class="btn-add">
                <i class="fa-solid fa-plus"></i>
                Thêm Quy định
            </a>
        </div>

        <!-- Search Form -->
        <div class="search-box">
            
            <form method="GET" action="<?php echo e(route('admin.quydinhdiemctxh.index')); ?>">
                <input type="text" name="keyword" value="<?php echo e(request('keyword')); ?>"
                    class="form-control flex-grow-1"
                    placeholder="Tìm theo tên công việc...">

                <button type="submit" class="btn-search">
                    <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                </button>

                <?php if(request()->has('keyword')): ?>
                <a href="<?php echo e(route('admin.quydinhdiemctxh.index')); ?>" class="btn-reset" title="Đặt lại">
                    <i class="fa-solid fa-refresh"></i>
                </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Table -->
        <div class="table-container">
            <!-- Success Message -->
            <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table align-middle"> 
                    <thead>
                        <tr>
                            <th style="width: 100px;">Mã điểm</th>
                            <th>Tên công việc</th>
                            <th style="width: 120px;">Điểm nhận</th>
                            <th class="text-center" style="width: 130px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $quydinhctxhs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong style="color:#6366f1;"><?php echo e($qd->MaDiem); ?></strong></td>
                            <td><?php echo e($qd->TenCongViec); ?></td>
                            
                            <td><span class="badge-diem"><?php echo e($qd->DiemNhan); ?></span></td>
                            <td class="text-center">
                                
                                <a href="<?php echo e(route('admin.quydinhdiemctxh.edit', $qd->MaDiem)); ?>" class="btn-action btn-warning me-1" title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                
                                <form action="<?php echo e(route('admin.quydinhdiemctxh.destroy', $qd->MaDiem)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa quy định này?');">
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
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fa-solid fa-inbox"></i>
                                    <p>Chưa có quy định điểm nào được thiết lập</p>
                                    
                                    <a href="<?php echo e(route('admin.quydinhdiemctxh.create')); ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fa-solid fa-plus me-1"></i> Thêm mới
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        
        <?php if($quydinhctxhs->hasPages()): ?>
        <div class="pagination-wrapper">
            <div class="pagination-info">
                
                Hiển thị <?php echo e($quydinhctxhs->firstItem()); ?> - <?php echo e($quydinhctxhs->lastItem()); ?> / tổng <?php echo e($quydinhctxhs->total()); ?>

            </div>
            <div>
                
                <?php echo e($quydinhctxhs->appends(request()->query())->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/quydinhdiemctxh/index.blade.php ENDPATH**/ ?>