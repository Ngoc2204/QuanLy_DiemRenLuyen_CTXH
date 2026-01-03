

<?php $__env->startSection('title', 'Thêm Địa điểm mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.home')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.diadiem.index')); ?>">Địa điểm</a></li>
            <li class="breadcrumb-item active">Thêm mới</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold">
                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                Thêm Địa điểm mới
            </h2>
            <p class="text-muted mb-0">Nhập thông tin địa điểm (địa chỉ đỏ) du lịch</p>
        </div>
        <a href="<?php echo e(route('admin.diadiem.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Thông tin Địa điểm
                    </h5>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Alert Errors -->
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="alert-heading mb-2">Vui lòng kiểm tra lại thông tin!</h6>
                                    <ul class="mb-0 ps-3">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('admin.diadiem.store')); ?>" method="POST" id="formThemDiaDiem">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Tên Địa điểm -->
                        <div class="mb-4">
                            <label for="TenDiaDiem" class="form-label fw-semibold">
                                Tên Địa điểm <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-building text-muted"></i>
                                </span>
                                <input 
                                    type="text" 
                                    class="form-control form-control-lg <?php $__errorArgs = ['TenDiaDiem'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="TenDiaDiem" 
                                    name="TenDiaDiem" 
                                    value="<?php echo e(old('TenDiaDiem')); ?>" 
                                    placeholder="VD: Dinh Độc Lập, Nhà thờ Đức Bà..." 
                                    required
                                >
                                <?php $__errorArgs = ['TenDiaDiem'];
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
                            <small class="text-muted">Nhập tên đầy đủ và chính xác của địa điểm</small>
                        </div>
                        
                        <!-- Địa chỉ -->
                        <div class="mb-4">
                            <label for="DiaChi" class="form-label fw-semibold">
                                Địa chỉ chi tiết
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-map-marked-alt text-muted"></i>
                                </span>
                                <textarea 
                                    class="form-control <?php $__errorArgs = ['DiaChi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="DiaChi" 
                                    name="DiaChi" 
                                    rows="3" 
                                    placeholder="VD: 135 Nam Kỳ Khởi Nghĩa, Phường Bến Nghé, Quận 1, TP.HCM"
                                ><?php echo e(old('DiaChi')); ?></textarea>
                                <?php $__errorArgs = ['DiaChi'];
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
                            <small class="text-muted">Ghi rõ số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố</small>
                        </div>

                        <div class="row">
                            <!-- Giá vé -->
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="GiaTien" class="form-label fw-semibold">
                                        Giá vé tham quan <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-ticket-alt text-muted"></i>
                                        </span>
                                        <input 
                                            type="number" 
                                            class="form-control form-control-lg <?php $__errorArgs = ['GiaTien'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="GiaTien" 
                                            name="GiaTien" 
                                            value="<?php echo e(old('GiaTien', 0)); ?>" 
                                            required 
                                            min="0"
                                            step="1000"
                                        >
                                        <span class="input-group-text bg-light">VNĐ</span>
                                        <?php $__errorArgs = ['GiaTien'];
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
                                    <small class="text-muted">Nhập 0 nếu tham quan miễn phí</small>
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="TrangThai" class="form-label fw-semibold">
                                        Trạng thái <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-toggle-on text-muted"></i>
                                        </span>
                                        <select class="form-select form-select-lg <?php $__errorArgs = ['TrangThai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="TrangThai" name="TrangThai">
                                            <option value="KhaDung" <?php echo e(old('TrangThai', 'KhaDung') == 'KhaDung' ? 'selected' : ''); ?>>
                                                ✓ Khả dụng
                                            </option>
                                            <option value="TamNgung" <?php echo e(old('TrangThai') == 'TamNgung' ? 'selected' : ''); ?>>
                                                ✕ Tạm ngưng
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
                                    <small class="text-muted">Chọn trạng thái hiển thị địa điểm</small>
                                </div>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info border-0 bg-light" role="alert">
                            <div class="d-flex">
                                <i class="fas fa-info-circle fa-lg text-info me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading mb-2">Lưu ý khi thêm địa điểm:</h6>
                                    <ul class="mb-0 small ps-3">
                                        <li>Các trường có dấu <span class="text-danger">*</span> là bắt buộc</li>
                                        <li>Tên địa điểm nên ngắn gọn, dễ nhớ</li>
                                        <li>Giá vé có thể cập nhật sau nếu chưa rõ</li>
                                        <li>Địa điểm "Tạm ngưng" sẽ không hiển thị trên hệ thống</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                            <a href="<?php echo e(route('admin.diadiem.index')); ?>" class="btn btn-lg btn-light px-4">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-lg btn-primary px-4">
                                <i class="fas fa-plus-circle me-2"></i>Thêm Địa điểm
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Helper Card (Optional) -->
        <div class="col-xl-4 col-lg-12 mt-4 mt-xl-0">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Hướng dẫn nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary rounded-circle" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">1</span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Nhập tên địa điểm</h6>
                            <small class="text-muted">Tên chính xác giúp du khách dễ tìm kiếm</small>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary rounded-circle" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">2</span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Điền địa chỉ đầy đủ</h6>
                            <small class="text-muted">Giúp xác định vị trí chính xác trên bản đồ</small>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary rounded-circle" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">3</span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Cập nhật giá vé</h6>
                            <small class="text-muted">Thông tin quan trọng cho du khách</small>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary rounded-circle" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">4</span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Chọn trạng thái</h6>
                            <small class="text-muted">Quản lý hiển thị địa điểm trên hệ thống</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body text-center py-4">
                    <i class="fas fa-map-marked-alt fa-3x text-danger mb-3"></i>
                    <h6 class="text-muted mb-0">Địa chỉ đỏ</h6>
                    <p class="small text-muted mb-0">Di tích lịch sử quan trọng</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.input-group-text {
    border: 1px solid #dee2e6;
}

.card {
    transition: transform 0.2s;
}

.btn {
    transition: all 0.2s;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.alert {
    border-left: 4px solid;
}

.alert-danger {
    border-left-color: #dc3545;
}

.alert-info {
    border-left-color: #0dcaf0;
}

.breadcrumb {
    background: transparent;
    padding: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    font-size: 1.2rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format giá tiền khi nhập
    const giatienInput = document.getElementById('GiaTien');
    if (giatienInput) {
        giatienInput.addEventListener('blur', function() {
            let value = parseInt(this.value) || 0;
            // Làm tròn đến nghìn
            value = Math.round(value / 1000) * 1000;
            this.value = value;
        });
    }

    // Xác nhận trước khi submit
    const form = document.getElementById('formThemDiaDiem');
    if (form) {
        form.addEventListener('submit', function(e) {
            const tenDiaDiem = document.getElementById('TenDiaDiem').value;
            if (!confirm('Xác nhận thêm địa điểm "' + tenDiaDiem + '"?')) {
                e.preventDefault();
            }
        });
    }

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/diadiem/create.blade.php ENDPATH**/ ?>