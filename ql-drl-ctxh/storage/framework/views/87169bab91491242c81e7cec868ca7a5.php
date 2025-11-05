 

<?php $__env->startSection('title', 'Tạo Đợt (Địa chỉ đỏ) mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h4 class="my-4">Tạo Đợt (Địa chỉ đỏ) mới</h4>

    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Thông tin Đợt</h5>
        </div>
        <div class="card-body">
            
            <!-- Hiển thị lỗi Validate -->
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.dotdiachido.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="mb-3">
                    <label for="TenDot" class="form-label">Tên Đợt <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="TenDot" name="TenDot" value="<?php echo e(old('TenDot')); ?>" placeholder="VD: Chiến dịch Địa chỉ đỏ T11/2025" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="NgayBatDau" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="NgayBatDau" name="NgayBatDau" value="<?php echo e(old('NgayBatDau')); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="mb-3">
                            <label for="NgayKetThuc" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="NgayKetThuc" name="NgayKetThuc" value="<?php echo e(old('NgayKetThuc')); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="TrangThai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                    <select class="form-select" id="TrangThai" name="TrangThai">
                        <option value="SapDienRa" <?php echo e(old('TrangThai') == 'SapDienRa' ? 'selected' : ''); ?>>Sắp diễn ra</option>
                        <option value="DangDienRa" <?php echo e(old('TrangThai') == 'DangDienRa' ? 'selected' : ''); ?>>Đang diễn ra</option>
                        <option value="DaKetThuc" <?php echo e(old('TrangThai') == 'DaKetThuc' ? 'selected' : ''); ?>>Đã kết thúc</option>
                    </select>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Lưu Đợt</button>
                    <a href="<?php echo e(route('admin.dotdiachido.index')); ?>" class="btn btn-secondary">Hủy</a>
                </div>

            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/dotdiachido/create.blade.php ENDPATH**/ ?>