


<?php
    $activity = $hoatdong_ctxh ?? $hoatdong_drl;
    $activityType = isset($hoatdong_ctxh) ? 'hoatdong_ctxh' : 'hoatdong_drl';
    $pageTitle = 'Ghi Nhận Kết Quả: ' . $activity->TenHoatDong;

    // Breadcrumbs (Giả định rằng route đã được tạo tương ứng)
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.' . $activityType . '.index'), 'title' => ($activityType == 'hoatdong_ctxh' ? 'Hoạt động CTXH' : 'Hoạt động DRL')],
        ['url' => route('nhanvien.' . $activityType . '.show', $activity), 'title' => 'Chi tiết'],
        ['url' => '#', 'title' => 'Ghi nhận'],
    ];
?>

<?php $__env->startSection('title', $pageTitle); ?>
<?php $__env->startSection('page_title', $pageTitle); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Custom styles cho trang này */
    .table thead th { vertical-align: middle; }
    .table td { vertical-align: middle; }
    .status-badge { font-size: 0.75rem; padding: 0.4em 0.8em; border-radius: 12px; font-weight: 600; }
    .table-responsive { max-height: 70vh; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 8px; }
    .sticky-header { position: sticky; top: 0; z-index: 10; background: #f8f9fa; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .student-name { font-weight: 600; color: #1f2937; font-size: 0.9375rem; }
    .student-code { font-size: 0.8125rem; color: #6b7280; }
    .form-select-sm { max-width: 160px; }
    .badge-check { padding: 0.35rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; }
    .badge-check.check-in { background-color: #e7f8f0; color: #0d9255; }
    .badge-check.check-out { background-color: #fef3c7; color: #f59e0b; }
    .badge-check.check-null { background-color: #f3f4f6; color: #9ca3af; }
    .card-header h5 { font-weight: 600; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-gradient py-3 text-white" style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);">
        <h5 class="mb-0">
            <i class="fa-solid fa-marker me-2"></i>
            Ghi nhận kết quả tham gia: <?php echo e($activity->TenHoatDong); ?>

        </h5>
        <small>Mã: <?php echo e($activity->MaHoatDong); ?></small>
    </div>

    
    <form action="<?php echo e(route('nhanvien.' . $activityType . '.update_ket_qua', $activity)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="card-body p-0">
            <?php if($sinhViens->isNotEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="sticky-header">
                            <tr>
                                <th class="text-center" style="width: 5%;">#</th>
                                <th>Sinh viên (MSSV)</th>
                                <th class="text-center" style="width: 15%;">Đăng ký</th>
                                <th class="text-center" style="width: 15%;">Check-In</th>
                                <th class="text-center" style="width: 15%;">Check-Out</th>
                                <th class="text-center" style="width: 25%;">Trạng thái CUỐI CÙNG</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $sinhViens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $pivot = $sv->pivot;
                                    $isDisabled = $pivot->TrangThaiDangKy != 'Đã duyệt';
                                ?>
                                <tr <?php if($isDisabled): ?> class="table-secondary" <?php endif; ?>>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td>
                                        <div class="student-name"><?php echo e($sv->HoTen ?? 'Không rõ'); ?></div>
                                        <div class="student-code"><?php echo e($sv->MSSV); ?></div>
                                    </td>
                                    <td class="text-center">
                                         <?php if($isDisabled): ?>
                                            <span class="badge-status badge-secondary"><?php echo e($pivot->TrangThaiDangKy); ?></span>
                                         <?php else: ?>
                                            <span class="badge-status badge-success"><?php echo e($pivot->TrangThaiDangKy); ?></span>
                                         <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($pivot->CheckInAt): ?>
                                            <span class="badge-check check-in"><?php echo e(\Carbon\Carbon::parse($pivot->CheckInAt)->format('H:i')); ?></span>
                                        <?php else: ?>
                                            <span class="badge-check check-null">Chưa có</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($pivot->CheckOutAt): ?>
                                            <span class="badge-check check-out"><?php echo e(\Carbon\Carbon::parse($pivot->CheckOutAt)->format('H:i')); ?></span>
                                        <?php else: ?>
                                            <span class="badge-check check-null">Chưa có</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        
                                        <input type="hidden" name="results[<?php echo e($pivot->MaDangKy); ?>][MaDangKy]" value="<?php echo e($pivot->MaDangKy); ?>">
                                        
                                        <select name="results[<?php echo e($pivot->MaDangKy); ?>][TrangThaiThamGia]" 
                                                class="form-select form-select-sm" 
                                                style="margin: 0 auto;"
                                                <?php echo e($isDisabled ? 'disabled' : ''); ?>>
                                            
                                            <option value="Đã tham gia" <?php echo e($pivot->TrangThaiThamGia == 'Đã tham gia' ? 'selected' : ''); ?>>Đã tham gia</option>
                                            <option value="Vắng" <?php echo e($pivot->TrangThaiThamGia == 'Vắng' ? 'selected' : ''); ?>>Vắng</option>
                                            <option value="Chưa tổng kết" <?php echo e($pivot->TrangThaiThamGia == 'Chưa tổng kết' ? 'selected' : ''); ?>>Chưa tổng kết</option>
                                        </select>
                                        <?php if($isDisabled): ?>
                                            <small class="text-danger mt-1 d-block">Không thể sửa</small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-user-slash fa-3x mb-3 d-block opacity-25"></i>
                    <p class="mb-0">Không có sinh viên Đã duyệt để ghi nhận kết quả.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="card-footer d-flex justify-content-end p-3">
            <a href="<?php echo e(route('nhanvien.' . $activityType . '.show', $activity)); ?>" class="btn btn-outline-secondary me-2">
                <i class="fa-solid fa-arrow-left me-2"></i> Quay lại Chi tiết
            </a>
            <button type="submit" class="btn btn-success shadow-sm">
                <i class="fa-solid fa-save me-2"></i> Lưu Ghi nhận
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/hoatdong_drl/ghi_nhan_ket_qua.blade.php ENDPATH**/ ?>