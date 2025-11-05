

<?php $__env->startSection('title', 'Chỉnh sửa Địa điểm'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h4 class="my-4">Chỉnh sửa: <?php echo e($diadiem->TenDiaDiem); ?></h4>

    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Thông tin Địa điểm</h5>
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

            <form action="<?php echo e(route('admin.diadiem.update', ['diadiem' => $diadiem->id])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="mb-3">
                    <label for="TenDiaDiem" class="form-label">Tên Địa điểm <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="TenDiaDiem" name="TenDiaDiem" value="<?php echo e(old('TenDiaDiem', $diadiem->TenDiaDiem)); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="DiaChi" class="form-label">Địa chỉ</label>
                    <textarea class="form-control" id="DiaChi" name="DiaChi" rows="2"><?php echo e(old('DiaChi', $diadiem->DiaChi)); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="GiaTien" class="form-label">Giá vé (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="GiaTien" name="GiaTien" value="<?php echo e(old('GiaTien', $diadiem->GiaTien)); ?>" required min="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="TrangThai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select" id="TrangThai" name="TrangThai">
                                <option value="KhaDung" <?php echo e(old('TrangThai', $diadiem->TrangThai) == 'KhaDung' ? 'selected' : ''); ?>>Khả dụng</option>
                                <option value="TamNgung" <?php echo e(old('TrangThai', $diadiem->TrangThai) == 'TamNgung' ? 'selected' : ''); ?>>Tạm ngưng</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="<?php echo e(route('admin.diadiem.index')); ?>" class="btn btn-secondary">Hủy</a>
                </div>

            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/diadiem/edit.blade.php ENDPATH**/ ?>