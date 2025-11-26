

<?php $__env->startSection('title', 'Liên hệ & Phản hồi'); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* Sử dụng lại các biến màu từ trang dashboard của bạn */
    :root {
        --primary: #667eea;
        --secondary: #764ba2;
        --success: #28a745;
        --danger: #dc3545;
        --light-bg: #f8f9ff;
        --card-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    }

    .feedback-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .feedback-header {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0e7ff 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .feedback-header h4 {
        margin: 0;
        font-weight: 700;
        color: #2d3748;
    }

    .feedback-header p {
        margin: 0.25rem 0 0;
        font-size: 0.95rem;
        color: #718096;
    }

    .feedback-body {
        padding: 2rem;
    }

    /* Tùy chỉnh form controls */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }

    .form-control[readonly] {
        background: #f8f9fa;
        cursor: not-allowed;
    }

    .btn-primary-gradient {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(118, 75, 162, 0.3);
    }

    .btn-primary-gradient:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(118, 75, 162, 0.4);
    }
</style>

<div class="container-fluid" style="max-width: 1000px; margin: 0 auto; padding: 2rem 1rem;">

    <div class="feedback-card">
        <div class="feedback-header">
            <h4><i class="fas fa-paper-plane me-2" style="color: var(--primary);"></i> Gửi Phản Hồi</h4>
            <p>Chúng tôi luôn lắng nghe ý kiến của bạn để cải thiện hệ thống.</p>
        </div>

        <div class="feedback-body">

            <?php if(session('success')): ?>
            <div class="alert alert-success" role="alert" style="border-radius: 8px;">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="alert alert-danger" role="alert" style="border-radius: 8px;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo e(session('error')); ?>

            </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
            <div class="alert alert-danger" style="border-radius: 8px;">
                <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Vui lòng kiểm tra lại:</h6>
                <ul class="mb-0 ps-3">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <form action="<?php echo e(route('sinhvien.lienhe.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="HoTen" class="form-label">Họ tên</label>
                            <input type="text" id="HoTen" class="form-control" value="<?php echo e($sinhvien->HoTen); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Email" class="form-label">Email</label>
                            <input type="email" id="Email" class="form-control" value="<?php echo e($sinhvien->Email); ?>" readonly>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="LoaiPhanHoi" class="form-label">Phân loại <span class="text-danger">*</span></label>
                            <select id="LoaiPhanHoi" name="LoaiPhanHoi" class="form-select <?php $__errorArgs = ['LoaiPhanHoi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                                <option value="" selected disabled>-- Chọn loại phản hồi --</option>
                                <option value="BaoLoi" <?php echo e(old('LoaiPhanHoi') == 'BaoLoi' ? 'selected' : ''); ?>>Báo lỗi kỹ thuật</option>
                                <option value="GopY" <?php echo e(old('LoaiPhanHoi') == 'GopY' ? 'selected' : ''); ?>>Đóng góp ý kiến</option>
                                <option value="HoTro" <?php echo e(old('LoaiPhanHoi') == 'HoTro' ? 'selected' : ''); ?>>Yêu cầu hỗ trợ</option>
                                <option value="Khac" <?php echo e(old('LoaiPhanHoi') == 'Khac' ? 'selected' : ''); ?>>Khác</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="TieuDe" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" id="TieuDe" name="TieuDe" class="form-control <?php $__errorArgs = ['TieuDe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('TieuDe')); ?>" placeholder="Nhập tóm tắt vấn đề của bạn">

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="NoiDung" class="form-label">Nội dung chi tiết <span class="text-danger">*</span></label>
                    <textarea id="NoiDung" name="NoiDung" class="form-control <?php $__errorArgs = ['NoiDung'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        rows="8" placeholder="Vui lòng mô tả chi tiết vấn đề bạn gặp phải hoặc ý kiến đóng góp của bạn..."><?php echo e(old('NoiDung')); ?></textarea>

                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary-gradient">
                        <i class="fas fa-paper-plane me-2"></i> Gửi Phản Hồi
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/lienhe/create.blade.php ENDPATH**/ ?>