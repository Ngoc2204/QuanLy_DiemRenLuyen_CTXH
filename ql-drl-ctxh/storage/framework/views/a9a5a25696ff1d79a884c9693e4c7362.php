

<?php $__env->startSection('title', 'Phân bổ Số lượng Hoạt động'); ?>
<?php $__env->startSection('page_title', 'Phân bổ Số lượng Hoạt động'); ?>

<?php
$breadcrumbs = [
    ['url' => route('giangvien.home'), 'title' => 'Bảng điều khiển'],
    ['url' => route('giangvien.hoatdong.phanbo.index'), 'title' => 'Phân bổ số lượng'],
];
?>

<?php $__env->startSection('content'); ?>
<div class="card modern-card">
    <div class="card-header modern-card-header">
        <i class="fa-solid fa-sliders me-2"></i>Danh sách Hoạt động DRL bạn phụ trách
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table modern-table mb-0">
                <thead>
                    <tr>
                        <th>Hoạt động</th>
                        <th class="text-center">Học kỳ</th>
                        <th class="text-center">Tổng Số Lượng</th>
                        <th class="text-center">Đã Phân Bổ</th>
                        <th class="text-center">Đã Đăng Ký</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $hoatDongs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Kiểm tra trạng thái phân bổ
                        $isAllocated = ($hd->SoLuong == $hd->DaPhanBo);
                    ?>
                    <tr class="table-row-hover">
                        <td>
                            <div class="student-name"><?php echo e($hd->TenHoatDong); ?></div>
                            <div class="student-code"><?php echo e($hd->MaHoatDong); ?></div>
                        </td>
                        <td class="text-center"><?php echo e($hd->hocKy->TenHocKy ?? $hd->MaHocKy); ?></td>
                        <td class="text-center fw-bold text-primary"><?php echo e($hd->SoLuong); ?></td>
                        <td class="text-center fw-bold <?php echo e($isAllocated ? 'text-success' : 'text-danger'); ?>">
                            <?php echo e((int)$hd->DaPhanBo); ?>

                            <?php if(!$isAllocated): ?>
                                <i class="fa-solid fa-triangle-exclamation ms-1" title="Chưa khớp"></i>
                            <?php endif; ?>
                        </td>
                        <td class="text-center fw-bold"><?php echo e($hd->dangky_count); ?></td>
                        <td class="text-center">
                            <a href="<?php echo e(route('giangvien.hoatdong.phanbo.edit', $hd->MaHoatDong)); ?>" class="btn btn-sm <?php echo e($isAllocated ? 'btn-outline-primary' : 'btn-primary'); ?>">
                                <i class="fa-solid fa-sliders me-1"></i> <?php echo e($isAllocated ? 'Chỉnh sửa' : 'Phân bổ'); ?>

                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
                                <h5 class="empty-title">Không có hoạt động nào</h5>
                                <p class="empty-text">Bạn hiện không được gán phụ trách hoạt động nào.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($hoatDongs->hasPages()): ?>
        <div class="pagination-wrapper">
            <?php echo e($hoatDongs->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
@import url('https://pastebin.com/raw/L8C35G0J'); /* CSS Chung */
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.giangvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/giangvien/phanbo/index.blade.php ENDPATH**/ ?>