

<?php $__env->startSection('title', 'Sửa Hoạt động CTXH'); ?>
<?php $__env->startSection('page_title', 'Chỉnh sửa Hoạt động CTXH'); ?>

<?php
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.hoatdong_ctxh.index'), 'title' => 'Hoạt động CTXH'],
        ['url' => '#', 'title' => 'Chỉnh sửa'],
    ];
?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-section { background: #f8f9fa; padding: 1.5rem; border-radius: 12px; border: 1px solid #e9ecef; }
    .section-header h6 { font-weight: 600; }
    .section-header hr { border-top: 2px solid currentColor; opacity: 0.2; }
    .form-label { font-weight: 600; color: #495057; margin-bottom: 0.5rem; display: flex; align-items: center; }
    .form-label i { width: 20px; text-align: center; }
    .form-control, .form-select { border-radius: 8px; border: 1px solid #dee2e6; padding: 0.625rem 0.875rem; transition: all 0.3s ease; }
    .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
    .form-control:disabled, .form-control[readonly] { background-color: #f8f9fa; border-color: #e9ecef; }
    .input-group-text { background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; font-weight: 500; }
    .btn { border-radius: 8px; padding: 0.625rem 1.25rem; font-weight: 500; transition: all 0.3s ease; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); }
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; }
    .btn-primary:hover { background: linear-gradient(135deg, #5568d3 0%, #653a8b 100%); }
    .alert { border-radius: 12px; }
    .card { border-radius: 12px; overflow: hidden; }
    .shadow-sm { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important; }
    .text-muted { color: #6c757d !important; }
    textarea.form-control { resize: vertical; min-height: 100px; }
    .form-control:invalid:not(:placeholder-shown) { border-color: #dc3545; }
    .form-control:valid:not(:placeholder-shown) { border-color: #28a745; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-gradient py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="mb-0">
            <i class="fa-solid fa-pen-to-square me-2"></i>
            Chỉnh sửa Hoạt động: <?php echo e($hoatdong_ctxh->TenHoatDong); ?>

        </h5>
    </div>

    <div class="card-body p-4">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fa-solid fa-exclamation-circle me-2 mt-1"></i>
                    <div class="flex-grow-1">
                        <strong>Có lỗi xảy ra:</strong>
                        <ul class="mb-0 mt-2">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-exclamation-circle me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>>

        <form action="<?php echo e(route('nhanvien.hoatdong_ctxh.update', $hoatdong_ctxh->MaHoatDong)); ?>" method="POST" id="editForm" novalidate>
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="category_tags_submitted" value="1">

            
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-primary mb-0">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        Thông tin cơ bản
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="MaHoatDong" class="form-label">
                            <i class="fa-solid fa-hashtag me-1 text-muted"></i> Mã Hoạt động
                        </label>
                        <input type="text" class="form-control" id="MaHoatDong" value="<?php echo e($hoatdong_ctxh->MaHoatDong); ?>" disabled readonly>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-heart me-1 text-muted"></i>
                                Sở thích liên quan <span class="text-danger">*</span>
                            </label>
                            <div class="interest-tags-container border rounded p-3" style="background-color: #f8f9fa; min-height: 120px;">
                                <?php $__empty_1 = true; $__currentLoopData = $interests ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="form-check form-check-inline" style="margin: 0.5rem 0;">
                                    <input class="form-check-input" type="checkbox" id="interest_<?php echo e($interest->InterestID); ?>" 
                                           name="category_tags[]" value="<?php echo e($interest->InterestID); ?>"
                                           <?php echo e(in_array($interest->InterestID, $selectedTags ?? []) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="interest_<?php echo e($interest->InterestID); ?>">
                                        <?php echo e($interest->InterestName); ?>

                                    </label>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-muted mb-0">Không có sở thích nào</p>
                                <?php endif; ?>
                            </div>
                            <?php $__errorArgs = ['category_tags'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="TenHoatDong" class="form-label">
                            <i class="fa-solid fa-heading me-1 text-muted"></i> Tên Hoạt động <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="TenHoatDong" name="TenHoatDong"
                               value="<?php echo e(old('TenHoatDong', $hoatdong_ctxh->TenHoatDong)); ?>" required>
                    </div>

                    <div class="col-md-12">
                        <label for="MoTa" class="form-label">
                            <i class="fa-solid fa-align-left me-1 text-muted"></i> Mô tả
                        </label>
                        <textarea class="form-control" id="MoTa" name="MoTa" rows="4"><?php echo e(old('MoTa', $hoatdong_ctxh->MoTa)); ?></textarea>
                    </div>

                    <div class="col-md-12">
                        <label for="DiaDiem" class="form-label">
                            <i class="fa-solid fa-location-dot me-1 text-muted"></i> Địa điểm cụ thể (Ghi chú) <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="DiaDiem" name="DiaDiem"
                               value="<?php echo e(old('DiaDiem', $hoatdong_ctxh->DiaDiem)); ?>" required>
                    </div>

                    
                </div>
            </div>

            
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-success mb-0">
                        <i class="fa-solid fa-calendar-days me-2"></i> Thời gian tổ chức
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="ThoiGianBatDau" class="form-label">
                            <i class="fa-solid fa-calendar-plus me-1 text-success"></i> Thời gian Bắt đầu <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control" id="ThoiGianBatDau" name="ThoiGianBatDau"
                               value="<?php echo e(old('ThoiGianBatDau', $hoatdong_ctxh->ThoiGianBatDau ? $hoatdong_ctxh->ThoiGianBatDau->format('Y-m-d\TH:i') : '')); ?>"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label for="ThoiGianKetThuc" class="form-label">
                            <i class="fa-solid fa-calendar-xmark me-1 text-danger"></i> Thời gian Kết thúc <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control" id="ThoiGianKetThuc" name="ThoiGianKetThuc"
                               value="<?php echo e(old('ThoiGianKetThuc', $hoatdong_ctxh->ThoiGianKetThuc ? $hoatdong_ctxh->ThoiGianKetThuc->format('Y-m-d\TH:i') : '')); ?>"
                               required>
                    </div>

                    <div class="col-md-12">
                        <label for="ThoiHanHuy" class="form-label">
                            <i class="fa-solid fa-clock-rotate-left me-1 text-warning"></i> Thời hạn Hủy đăng ký <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control" id="ThoiHanHuy" name="ThoiHanHuy"
                               value="<?php echo e(old('ThoiHanHuy', $hoatdong_ctxh->ThoiHanHuy ? $hoatdong_ctxh->ThoiHanHuy->format('Y-m-d\TH:i') : '')); ?>"
                               required>
                    </div>
                </div>
            </div>

            
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-warning mb-0">
                        <i class="fa-solid fa-chart-simple me-2"></i> Điểm số & Số lượng
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="SoLuong" class="form-label">
                            <i class="fa-solid fa-users me-1 text-primary"></i> Số lượng tối đa <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="SoLuong" name="SoLuong"
                                   value="<?php echo e(old('SoLuong', $hoatdong_ctxh->SoLuong)); ?>" min="1" required placeholder="0">
                            <span class="input-group-text">SV</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="current_registered" class="form-label">
                            <i class="fa-solid fa-user-check me-1 text-success"></i> Đã đăng ký
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="current_registered"
                                   value="<?php echo e($hoatdong_ctxh->dangKy_count ?? $hoatdong_ctxh->dangKy()->count()); ?>" disabled readonly>
                            <span class="input-group-text">SV</span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="MaQuyDinhDiem" class="form-label">
                            <i class="fa-solid fa-clipboard-list me-1 text-info"></i> Quy định điểm liên quan <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="MaQuyDinhDiem" name="MaQuyDinhDiem" required>
                            <option value="">-- Chọn quy định điểm --</option>
                            <?php $__currentLoopData = $quyDinhDiems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $maDiem => $tenCongViec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($maDiem); ?>" <?php echo e(old('MaQuyDinhDiem', $hoatdong_ctxh->MaQuyDinhDiem) == $maDiem ? 'selected' : ''); ?>>
                                    <?php echo e($maDiem); ?> - <?php echo e($tenCongViec); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>

            
            <div class="alert alert-info border-0 shadow-sm mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-lightbulb fa-2x text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="alert-heading mb-2"><i class="fa-solid fa-circle-info me-1"></i> Lưu ý khi chỉnh sửa</h6>
                        <ul class="mb-0 small">
                            <li>Nếu giảm số lượng tối đa, cần kiểm tra số lượng sinh viên đã đăng ký</li>
                            <li>Thay đổi thời gian có thể ảnh hưởng đến lịch đăng ký của sinh viên</li>
                            <li>Nếu là “Địa chỉ đỏ”, Đợt & Địa điểm chỉ hiển thị để tham chiếu.</li>
                        </ul>
                    </div>
                </div>
            </div>

            
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="<?php echo e(route('nhanvien.hoatdong_ctxh.index')); ?>" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                </a>
                <div>
                    <button type="reset" class="btn btn-outline-warning me-2">
                        <i class="fa-solid fa-rotate-left me-2"></i>Đặt lại
                    </button>
                    <button type="submit" class="btn btn-primary shadow">
                        <i class="fa-solid fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editForm');

    // Validate số lượng tối đa >= số đã đăng ký
    const soLuongInput = document.getElementById('SoLuong');
    const currentRegistered = parseInt(document.getElementById('current_registered').value || '0', 10);

    function validateSoLuong() {
        const v = parseInt(soLuongInput.value, 10);
        soLuongInput.setCustomValidity('');
        if (!isNaN(v) && v < currentRegistered) {
            soLuongInput.setCustomValidity(`Số lượng mới (${v}) không thể nhỏ hơn số lượng đã đăng ký (${currentRegistered}).`);
        }
    }

    // Validate thời gian
    const tgBatDau = document.getElementById('ThoiGianBatDau');
    const tgKetThuc = document.getElementById('ThoiGianKetThuc');
    const tgHuy = document.getElementById('ThoiHanHuy');

    function validateDates() {
        const start = tgBatDau.value ? new Date(tgBatDau.value) : null;
        const end   = tgKetThuc.value ? new Date(tgKetThuc.value) : null;
        const cancel= tgHuy.value ? new Date(tgHuy.value) : null;

        tgKetThuc.setCustomValidity('');
        tgHuy.setCustomValidity('');

        if (start && end && end <= start) {
            tgKetThuc.setCustomValidity('Thời gian kết thúc phải sau thời gian bắt đầu.');
        }
        if (start && cancel && cancel >= start) {
            tgHuy.setCustomValidity('Thời hạn hủy phải trước thời gian bắt đầu.');
        }
    }

    // Init
    validateSoLuong();
    validateDates();

    // Listeners
    soLuongInput.addEventListener('input', validateSoLuong);
    tgBatDau.addEventListener('change', validateDates);
    tgKetThuc.addEventListener('change', validateDates);
    tgHuy.addEventListener('change', validateDates);

    // Validate category_tags - at least one checkbox must be selected
    function validateCategoryTags() {
        const checkboxes = document.querySelectorAll('input[name="category_tags[]"]');
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        
        if (checkedCount === 0) {
            alert('Vui lòng chọn ít nhất một sở thích liên quan!');
            return false;
        }
        return true;
    }

    // Submit
    form.addEventListener('submit', function (e) {
        validateSoLuong();
        validateDates();
        
        if (!validateCategoryTags()) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
        
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        form.classList.add('was-validated');
    });

    // Reset
    const resetBtn = form.querySelector('button[type="reset"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            form.classList.remove('was-validated');
            tgKetThuc.setCustomValidity('');
            tgHuy.setCustomValidity('');
            soLuongInput.setCustomValidity('');
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/hoatdong_ctxh/edit.blade.php ENDPATH**/ ?>