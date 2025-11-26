

<?php $__env->startSection('title', 'Chỉnh sửa Đợt'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mt-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('admin.home')); ?>" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('admin.dotdiachido.index')); ?>" class="text-decoration-none">
                    Quản lý Đợt
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold text-dark">
                <i class="fas fa-edit text-primary me-2"></i>Chỉnh sửa Đợt
            </h2>
            <p class="text-muted mb-0"><?php echo e($dotdiachido->TenDot); ?></p>
        </div>
        <a href="<?php echo e(route('admin.dotdiachido.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <!-- Hiển thị lỗi Validate -->
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-triangle fa-lg me-3 mt-1"></i>
                <div class="flex-grow-1">
                    <h6 class="mb-2 fw-bold">Vui lòng kiểm tra lại thông tin:</h6>
                    <ul class="mb-0 ps-3">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-info-circle text-primary me-2"></i>Thông tin Đợt
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('admin.dotdiachido.update', ['dotdiachido' => $dotdiachido->id])); ?>" 
                          method="POST" 
                          id="editDotForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Tên Đợt -->
                        <div class="mb-4">
                            <label for="TenDot" class="form-label fw-semibold">
                                <i class="fas fa-tag text-primary me-2"></i>Tên Đợt 
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg <?php $__errorArgs = ['TenDot'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="TenDot" 
                                   name="TenDot"
                                   value="<?php echo e(old('TenDot', $dotdiachido->TenDot)); ?>" 
                                   placeholder="Nhập tên đợt..."
                                   required>
                            <?php $__errorArgs = ['TenDot'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Tên đợt nên ngắn gọn và dễ hiểu
                            </small>
                        </div>

                        <!-- Ngày Bắt đầu & Kết thúc -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="NgayBatDau" class="form-label fw-semibold">
                                    <i class="fas fa-calendar-day text-success me-2"></i>Ngày bắt đầu 
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-calendar text-muted"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control border-start-0 <?php $__errorArgs = ['NgayBatDau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="NgayBatDau" 
                                           name="NgayBatDau"
                                           value="<?php echo e(old('NgayBatDau', \Carbon\Carbon::parse($dotdiachido->NgayBatDau)->format('Y-m-d'))); ?>" 
                                           required>
                                    <?php $__errorArgs = ['NgayBatDau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="NgayKetThuc" class="form-label fw-semibold">
                                    <i class="fas fa-calendar-check text-danger me-2"></i>Ngày kết thúc 
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-calendar text-muted"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control border-start-0 <?php $__errorArgs = ['NgayKetThuc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="NgayKetThuc" 
                                           name="NgayKetThuc"
                                           value="<?php echo e(old('NgayKetThuc', \Carbon\Carbon::parse($dotdiachido->NgayKetThuc)->format('Y-m-d'))); ?>" 
                                           required>
                                    <?php $__errorArgs = ['NgayKetThuc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Duration Info -->
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 mb-4" id="durationInfo" style="display: none;">
                            <i class="fas fa-clock me-2"></i>
                            <span id="durationText"></span>
                        </div>

                        <!-- Trạng thái -->
                        <div class="mb-4">
                            <label for="TrangThai" class="form-label fw-semibold">
                                <i class="fas fa-flag text-warning me-2"></i>Trạng thái 
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg <?php $__errorArgs = ['TrangThai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="TrangThai" 
                                    name="TrangThai">
                                <option value="SapDienRa" <?php echo e(old('TrangThai', $dotdiachido->TrangThai) == 'SapDienRa' ? 'selected' : ''); ?>>
                                    🟡 Sắp diễn ra
                                </option>
                                <option value="DangDienRa" <?php echo e(old('TrangThai', $dotdiachido->TrangThai) == 'DangDienRa' ? 'selected' : ''); ?>>
                                    🟢 Đang diễn ra
                                </option>
                                <option value="DaKetThuc" <?php echo e(old('TrangThai', $dotdiachido->TrangThai) == 'DaKetThuc' ? 'selected' : ''); ?>>
                                    ⚫ Đã kết thúc
                                </option>
                            </select>
                            <?php $__errorArgs = ['TrangThai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?php echo e(route('admin.dotdiachido.index')); ?>" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-save me-2"></i>Cập nhật Đợt
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card border-0 shadow-sm mt-4 bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-lightbulb text-warning me-2"></i>Lưu ý khi chỉnh sửa
                    </h6>
                    <ul class="mb-0 small text-muted">
                        <li class="mb-2">Ngày kết thúc phải sau ngày bắt đầu</li>
                        <li class="mb-2">Trạng thái sẽ tự động cập nhật dựa trên ngày hiện tại</li>
                        <li class="mb-2">Các trường đánh dấu <span class="text-danger">*</span> là bắt buộc</li>
                        <li>Thay đổi sẽ ảnh hưởng đến tất cả các suất trong đợt này</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Styles */
.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

.input-group-text {
    transition: all 0.3s ease;
}

.form-control:focus + .input-group-text,
.input-group:focus-within .input-group-text {
    background-color: #e7f1ff;
    border-color: #667eea;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn {
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    font-size: 1.2em;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 0 1rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('NgayBatDau');
    const endDateInput = document.getElementById('NgayKetThuc');
    const durationInfo = document.getElementById('durationInfo');
    const durationText = document.getElementById('durationText');

    function calculateDuration() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (startDateInput.value && endDateInput.value) {
            const diffTime = endDate - startDate;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays > 0) {
                durationInfo.style.display = 'block';
                durationText.textContent = `Thời gian diễn ra: ${diffDays} ngày (từ ${startDate.toLocaleDateString('vi-VN')} đến ${endDate.toLocaleDateString('vi-VN')})`;
            } else if (diffDays === 0) {
                durationInfo.style.display = 'block';
                durationText.textContent = 'Đợt diễn ra trong cùng một ngày';
            } else {
                durationInfo.style.display = 'none';
            }
        }
    }

    // Validate dates
    function validateDates() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (startDateInput.value && endDateInput.value && endDate < startDate) {
            endDateInput.setCustomValidity('Ngày kết thúc phải sau ngày bắt đầu');
        } else {
            endDateInput.setCustomValidity('');
        }
    }

    startDateInput.addEventListener('change', function() {
        calculateDuration();
        validateDates();
    });

    endDateInput.addEventListener('change', function() {
        calculateDuration();
        validateDates();
    });

    // Initial calculation
    calculateDuration();

    // Form submission confirmation
    document.getElementById('editDotForm').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        } else {
            // Optional: Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang cập nhật...';
            submitBtn.disabled = true;
        }
        this.classList.add('was-validated');
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/dotdiachido/edit.blade.php ENDPATH**/ ?>