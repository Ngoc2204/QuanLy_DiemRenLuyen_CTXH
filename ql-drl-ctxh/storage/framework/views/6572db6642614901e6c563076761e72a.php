

<?php $__env->startSection('title', 'Quản lý Đợt (Địa chỉ đỏ)'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Quản lý Đợt</h2>
            <p class="text-muted mb-0">Quản lý các đợt địa chỉ đỏ</p>
        </div>
        <a href="<?php echo e(route('admin.dotdiachido.create')); ?>" class="btn btn-primary px-4 py-2 shadow-sm">
            <i class="fas fa-plus me-2"></i>Thêm đợt mới
        </a>
    </div>

    <!-- Thông báo Session -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards (Optional) -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-1">Tổng số đợt</h6>
                            <h3 class="mb-0 fw-bold"><?php echo e($dots->total()); ?></h3>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-1">Đang diễn ra</h6>
                            <h3 class="mb-0 fw-bold"><?php echo e($dots->where('TrangThai', 'DangDienRa')->count()); ?></h3>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-play-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-1">Sắp diễn ra</h6>
                            <h3 class="mb-0 fw-bold"><?php echo e($dots->where('TrangThai', 'SapDienRa')->count()); ?></h3>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng Dữ liệu -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-list me-2 text-primary"></i>Danh sách các Đợt
                </h5>
                <span class="badge bg-light text-dark border">
                    Hiển thị <?php echo e($dots->count()); ?> / <?php echo e($dots->total()); ?> đợt
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-muted fw-semibold">#</th>
                            <th scope="col" class="px-4 py-3 text-muted fw-semibold">Tên Đợt</th>
                            <th scope="col" class="px-4 py-3 text-muted fw-semibold">
                                <i class="fas fa-calendar-day me-1"></i>Ngày Bắt Đầu
                            </th>
                            <th scope="col" class="px-4 py-3 text-muted fw-semibold">
                                <i class="fas fa-calendar-check me-1"></i>Ngày Kết Thúc
                            </th>
                            <th scope="col" class="px-4 py-3 text-muted fw-semibold">Trạng thái</th>
                            <th scope="col" class="px-4 py-3 text-muted fw-semibold text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $dots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-bottom">
                                <td class="px-4 py-3">
                                    <span class="badge bg-light text-dark border"><?php echo e($dots->firstItem() + $index); ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold"><?php echo e($item->TenDot); ?></h6>
                                            
                                            
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-muted">
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($item->NgayBatDau)->format('d/m/Y')); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-muted">
                                        <i class="fas fa-calendar text-success me-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($item->NgayKetThuc)->format('d/m/Y')); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if($item->TrangThai == 'DangDienRa'): ?>
                                        <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Đang diễn ra
                                        </span>
                                    <?php elseif($item->TrangThai == 'SapDienRa'): ?>
                                        <span class="badge bg-warning-soft text-warning px-3 py-2 rounded-pill">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Sắp diễn ra
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-soft text-secondary px-3 py-2 rounded-pill">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Đã kết thúc
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <!-- Nút Sửa -->
                                        <a href="<?php echo e(route('admin.dotdiachido.edit', $item->id)); ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           data-bs-toggle="tooltip" 
                                           title="Sửa đợt">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Nút Xóa -->
                                        <form action="<?php echo e(route('admin.dotdiachido.destroy', $item->id)); ?>" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa đợt này?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Xóa đợt">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block text-muted opacity-50"></i>
                                        <h5 class="text-muted">Chưa có đợt nào</h5>
                                        <p class="mb-3">Bắt đầu bằng cách tạo đợt mới</p>
                                        <a href="<?php echo e(route('admin.dotdiachido.create')); ?>" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Thêm đợt đầu tiên
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Phân trang -->
            <?php if($dots->hasPages()): ?>
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Hiển thị <?php echo e($dots->firstItem()); ?> - <?php echo e($dots->lastItem()); ?> trong tổng số <?php echo e($dots->total()); ?> đợt
                        </div>
                        <div>
                            <?php echo e($dots->links()); ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Custom Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.icon-shape {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-success-soft {
    background-color: #d4edda;
}

.bg-warning-soft {
    background-color: #fff3cd;
}

.bg-secondary-soft {
    background-color: #e2e3e5;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.btn-group .btn {
    padding: 0.375rem 0.75rem;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>

<?php $__env->startPush('scripts'); ?>
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/dotdiachido/index.blade.php ENDPATH**/ ?>