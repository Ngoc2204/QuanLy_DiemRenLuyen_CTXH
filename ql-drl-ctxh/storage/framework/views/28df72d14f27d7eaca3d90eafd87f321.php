

<?php $__env->startSection('title', 'Chi tiết Phân bổ'); ?>
<?php $__env->startSection('page_title', 'Phân bổ Số lượng'); ?>

<?php
$breadcrumbs = [
    ['url' => route('giangvien.home'), 'title' => 'Bảng điều khiển'],
    ['url' => route('giangvien.hoatdong.phanbo.index'), 'title' => 'Phân bổ số lượng'],
    ['url' => '#', 'title' => 'Chi tiết'],
];
$totalSlots = $hoatdong_drl->SoLuong;
?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('giangvien.hoatdong.phanbo.update', $hoatdong_drl)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="row g-4">
        
        <div class="col-lg-8">
            <div class="card modern-card">
                <div class="card-header modern-card-header">
                    <i class="fa-solid fa-users-line me-2"></i>Phân bổ cho các Khoa
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table modern-table mb-0">
                            <thead>
                                <tr>
                                    <th>Tên Khoa</th>
                                    <th>Mã Khoa</th>
                                    <th style="width: 30%;">Số lượng phân bổ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $khoas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $khoa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $sl = $existingAllocations[$khoa->MaKhoa] ?? 0;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="student-name"><?php echo e($khoa->TenKhoa); ?></div>
                                        </td>
                                        <td>
                                            <div class="student-code"><?php echo e($khoa->MaKhoa); ?></div>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   class="form-control allocation-input" 
                                                   name="so_luong_khoa[<?php echo e($khoa->MaKhoa); ?>]" 
                                                   value="<?php echo e(old('so_luong_khoa.'.$khoa->MaKhoa, $sl)); ?>"
                                                   min="0"
                                                   max="<?php echo e($totalSlots); ?>"
                                                   oninput="updateAllocation()">
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            <div class="card modern-card sticky-top" style="top: 90px;">
                <div class="card-header modern-card-header">
                    <i class="fa-solid fa-chart-pie me-2"></i>Tổng kết Phân bổ
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="info-label">Hoạt động:</label>
                        <p class="info-value"><?php echo e($hoatdong_drl->TenHoatDong); ?></p>
                    </div>
                    <hr>
                    
                    <div class_ ="info-item mb-3">
                        <label class="info-label">TỔNG SỐ LƯỢNG CỦA HOẠT ĐỘNG:</label>
                        <h2 class="info-value fw-bold text-primary" id="total-slots"><?php echo e($totalSlots); ?></h2>
                    </div>

                    <div class="info-item mb-3">
                        <label class="info-label">SỐ LƯỢNG ĐÃ PHÂN BỔ:</label>
                        <h2 class="info-value fw-bold" id="allocated-slots">0</h2>
                    </div>
                    
                    <div class="info-item mb-4">
                        <label class="info-label">SỐ LƯỢNG CÒN LẠI:</label>
                        <h2 class="info-value fw-bold" id="remaining-slots"><?php echo e($totalSlots); ?></h2>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary modern-btn" id="save-button">
                            <i class="fa-solid fa-save me-2"></i>Lưu Phân Bổ
                        </button>
                        <a href="<?php echo e(route('giangvien.hoatdong.phanbo.index')); ?>" class="btn btn-outline-secondary modern-btn">
                            <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
@import url('https://pastebin.com/raw/L8C35G0J'); /* CSS Chung */
.info-label {
    font-weight: 600; color: #6c757d;
    font-size: 0.875rem; margin-bottom: 0.25rem; display: block;
    text-transform: uppercase;
}
.info-value { margin: 0; color: #212529; font-size: 1.1rem; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Hàm JS để tính toán số lượng còn lại
    function updateAllocation() {
        const totalSlots = <?php echo e($totalSlots); ?>;
        let allocated = 0;
        
        const inputs = document.querySelectorAll('.allocation-input');
        inputs.forEach(input => {
            let value = parseInt(input.value);
            if (!isNaN(value) && value > 0) {
                allocated += value;
            }
        });

        const remaining = totalSlots - allocated;

        const allocatedEl = document.getElementById('allocated-slots');
        const remainingEl = document.getElementById('remaining-slots');
        const saveButton = document.getElementById('save-button');

        allocatedEl.textContent = allocated;
        remainingEl.textContent = remaining;

        // Đổi màu và disable/enable nút
        if (remaining < 0) {
            remainingEl.className = 'info-value fw-bold text-danger';
            saveButton.disabled = true;
            saveButton.textContent = 'Tổng vượt quá!';
        } else if (remaining > 0) {
            remainingEl.className = 'info-value fw-bold text-warning';
            saveButton.disabled = true;
            saveButton.textContent = 'Chưa phân bổ hết!';
        } else {
            remainingEl.className = 'info-value fw-bold text-success';
            saveButton.disabled = false;
            saveButton.innerHTML = '<i class="fa-solid fa-save me-2"></i>Lưu Phân Bổ';
        }
    }

    // Chạy lần đầu khi tải trang
    document.addEventListener('DOMContentLoaded', updateAllocation);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.giangvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/giangvien/phanbo/edit.blade.php ENDPATH**/ ?>