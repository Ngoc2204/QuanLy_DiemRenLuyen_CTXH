 

<?php $__env->startSection('title', 'Quản lý Đợt (Địa chỉ đỏ)'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h4 class="my-4">Quản lý Đợt (Địa chỉ đỏ)</h4>

    <!-- Nút Thêm Mới -->
    <div class="mb-3">
        <a href="<?php echo e(route('admin.dotdiachido.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm đợt mới
        </a>
    </div>

    <!-- Thông báo Session -->
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <!-- Bảng Dữ liệu -->
    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Danh sách các Đợt</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên Đợt</th>
                            <th scope="col">Ngày Bắt Đầu</th>
                            <th scope="col">Ngày Kết Thúc</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $dots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <th scope="row"><?php echo e($index + 1); ?></th>
                                <td>
                                    
                                    
                                    <?php echo e($item->TenDot); ?>

                                </td>
                                <td><?php echo e(\Carbon\Carbon::parse($item->NgayBatDau)->format('d/m/Y')); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($item->NgayKetThuc)->format('d/m/Y')); ?></td>
                                <td>
                                    <?php if($item->TrangThai == 'DangDienRa'): ?>
                                        <span class="badge bg-success">Đang diễn ra</span>
                                    <?php elseif($item->TrangThai == 'SapDienRa'): ?>
                                        <span class="badge bg-warning text-dark">Sắp diễn ra</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Đã kết thúc</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <!-- Nút Sửa -->
                                    <a href="<?php echo e(route('admin.dotdiachido.edit', $item->id)); ?>" class="btn btn-warning btn-sm" title="Sửa đợt">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Nút Xóa (dùng form) -->
                                    <form action="<?php echo e(route('admin.dotdiachido.destroy', $item->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đợt này?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa đợt">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center">Chưa có đợt nào được tạo.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Phân trang -->
            <div class="mt-3">
                <?php echo e($dots->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/dotdiachido/index.blade.php ENDPATH**/ ?>