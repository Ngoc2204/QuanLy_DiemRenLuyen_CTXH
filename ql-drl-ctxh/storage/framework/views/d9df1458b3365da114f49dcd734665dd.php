

<?php $__env->startSection('title', 'Quản lý Địa điểm'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <div>
            <h2 class="mb-1 fw-bold text-dark">
                <i class="fas fa-map-marked-alt text-danger me-2"></i>
                Quản lý Địa điểm
            </h2>
            <p class="text-muted mb-0">Quản lý các địa chỉ đỏ và địa điểm du lịch</p>
        </div>
        <a href="<?php echo e(route('admin.diadiem.create')); ?>" class="btn btn-danger btn-lg shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>Thêm địa điểm mới
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Tổng địa điểm</h6>
                            <h3 class="mb-0 fw-bold"><?php echo e($diaDiems->total()); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Đang hoạt động</h6>
                            <h3 class="mb-0 fw-bold text-success"><?php echo e($diaDiems->where('TrangThai', 'KhaDung')->count()); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-pause-circle fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Tạm ngưng</h6>
                            <h3 class="mb-0 fw-bold text-warning"><?php echo e($diaDiems->where('TrangThai', '!=', 'KhaDung')->count()); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-ticket-alt fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Giá TB</h6>
                            <h3 class="mb-0 fw-bold text-info"><?php echo e(number_format($diaDiems->avg('GiaTien') ?? 0, 0, ',', '.')); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-list me-2 text-danger"></i>Danh sách Địa điểm
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>In danh sách
                    </button>
                    <button class="btn btn-outline-success btn-sm">
                        <i class="fas fa-file-excel me-1"></i>Xuất Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="text-center" style="width: 60px;">#</th>
                            <th scope="col" style="width: 25%;">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>Tên Địa điểm
                            </th>
                            <th scope="col" style="width: 30%;">
                                <i class="fas fa-map-pin text-primary me-1"></i>Địa chỉ
                            </th>
                            <th scope="col" class="text-end" style="width: 15%;">
                                <i class="fas fa-money-bill-wave text-success me-1"></i>Giá vé
                            </th>
                            <th scope="col" class="text-center" style="width: 12%;">
                                <i class="fas fa-toggle-on text-info me-1"></i>Trạng thái
                            </th>
                            <th scope="col" class="text-center" style="width: 130px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $diaDiems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-center fw-semibold text-muted">
                                    <?php echo e(($diaDiems->currentPage() - 1) * $diaDiems->perPage() + $index + 1); ?>

                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 rounded p-2 me-2">
                                            <i class="fas fa-landmark text-danger"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold text-dark"><?php echo e($item->TenDiaDiem); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="fas fa-location-dot me-1 text-primary"></i><?php echo e(Str::limit($item->DiaChi, 50)); ?>

                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-success-subtle text-success fs-6 fw-semibold px-3 py-2">
                                        <?php echo e(number_format($item->GiaTien, 0, ',', '.')); ?> ₫
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if($item->TrangThai == 'KhaDung'): ?>
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Khả dụng
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary px-3 py-2">
                                            <i class="fas fa-ban me-1"></i>Tạm ngưng
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('admin.diadiem.edit', $item->id)); ?>" 
                                           class="btn btn-warning btn-sm" 
                                           title="Chỉnh sửa"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.diadiem.destroy', $item->id)); ?>" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa điểm \'<?php echo e($item->TenDiaDiem); ?>\' không?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Xóa"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        <h5>Chưa có địa điểm nào</h5>
                                        <p class="mb-3">Hãy thêm địa điểm đầu tiên của bạn</p>
                                        <a href="<?php echo e(route('admin.diadiem.create')); ?>" class="btn btn-danger">
                                            <i class="fas fa-plus me-2"></i>Thêm địa điểm mới
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination Footer -->
        <?php if($diaDiems->hasPages()): ?>
        <div class="card-footer bg-white border-top">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Hiển thị <?php echo e($diaDiems->firstItem() ?? 0); ?> - <?php echo e($diaDiems->lastItem() ?? 0); ?> 
                    trong tổng số <?php echo e($diaDiems->total()); ?> địa điểm
                </div>
                <div>
                    <?php echo e($diaDiems->links()); ?>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Custom Styles */
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
}

.table > tbody > tr {
    transition: background-color 0.2s;
}

.table > tbody > tr:hover {
    background-color: rgba(220, 53, 69, 0.05);
}

.btn-group .btn {
    transition: all 0.2s;
}

.btn-group .btn:hover {
    transform: scale(1.1);
    z-index: 2;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* Print Styles */
@media print {
    .btn, .card-footer, .alert {
        display: none !important;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .btn-group {
        flex-direction: column;
    }
}
</style>

<script>
// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/diadiem/index.blade.php ENDPATH**/ ?>