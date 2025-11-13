
<?php $__env->startSection('title', 'Kết quả điểm danh'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
  <?php
    $status = $status ?? 'error';
    $message = $message ?? 'Lỗi không xác định.';
    $map = [
      'success' => ['alert-success','<i class="fas fa-check-circle me-2"></i>'],
      'warning' => ['alert-warning','<i class="fas fa-exclamation-triangle me-2"></i>'],
      'error'   => ['alert-danger','<i class="fas fa-times-circle me-2"></i>'],
    ];
    [$cls,$icon] = $map[$status] ?? $map['error'];
  ?>

  <div class="alert <?php echo e($cls); ?> d-flex align-items-center" role="alert">
    <?php echo $icon; ?> <div><?php echo $message; ?></div>
  </div>

  <a href="<?php echo e(url()->previous()); ?>" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i>Quay lại
  </a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/scan_result.blade.php ENDPATH**/ ?>