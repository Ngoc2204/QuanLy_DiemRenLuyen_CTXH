

<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        <div class="col-lg-12 col-xl-11">

            <!-- Page Header Card -->
            <div class="page-header-card">
                <a href="<?php echo e(route('sinhvien.profile.show')); ?>" class="back-button">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="bi bi-pencil-square"></i>
                        Chỉnh sửa thông tin cá nhân
                    </h1>
                    <p class="page-subtitle">Cập nhật thông tin của bạn</p>
                </div>
            </div>

            
            <?php if($errors->any()): ?>
            <div class="alert-error-custom">
                <div class="alert-error-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div class="alert-error-content">
                    <h4 class="alert-error-title">Có lỗi xảy ra!</h4>
                    <ul class="alert-error-list">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- Main Form Card -->
            <div class="edit-card">
                <form action="<?php echo e(route('sinhvien.profile.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <!-- Read-only Information Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon bg-gray">
                                <i class="bi bi-lock-fill"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Thông tin cố định</h3>
                                <p class="section-description">Những thông tin này không thể chỉnh sửa</p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">
                                        <i class="bi bi-person-badge"></i>
                                        MSSV
                                    </label>
                                    <div class="input-readonly">
                                        <span><?php echo e($student->MSSV); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">
                                        <i class="bi bi-person"></i>
                                        Họ và Tên
                                    </label>
                                    <div class="input-readonly">
                                        <span><?php echo e($student->HoTen); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">
                                        <i class="bi bi-people"></i>
                                        Lớp
                                    </label>
                                    <div class="input-readonly">
                                        <span><?php echo e($student->lop->TenLop ?? $student->MaLop); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Editable Information Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon bg-blue">
                                <i class="bi bi-pencil-fill"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Thông tin có thể chỉnh sửa</h3>
                                <p class="section-description">Cập nhật thông tin liên hệ của bạn</p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label for="Email" class="form-label-custom required">
                                        <i class="bi bi-envelope"></i>
                                        Email
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="email"
                                            id="Email"
                                            name="Email"
                                            class="form-control-custom <?php $__errorArgs = ['Email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e(old('Email', $student->Email)); ?>"
                                            placeholder="example@email.com">
                                        <div class="input-icon">
                                            <i class="bi bi-envelope"></i>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['Email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="error-message"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label for="SDT" class="form-label-custom required">
                                        <i class="bi bi-telephone"></i>
                                        Số điện thoại
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text"
                                            id="SDT"
                                            name="SDT"
                                            class="form-control-custom <?php $__errorArgs = ['SDT'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e(old('SDT', $student->SDT)); ?>"
                                            placeholder="0123456789">
                                        <div class="input-icon">
                                            <i class="bi bi-telephone"></i>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['SDT'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="error-message"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group-custom">
                                    <label for="SoThich" class="form-label-custom">
                                        <i class="bi bi-heart"></i>
                                        Sở thích
                                    </label>
                                    <div class="textarea-wrapper">
                                        <textarea id="SoThich"
                                            name="SoThich"
                                            class="form-control-custom textarea-custom <?php $__errorArgs = ['SoThich'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            rows="4"
                                            placeholder="Chia sẻ về sở thích, đam mê của bạn..."><?php echo e(old('SoThich', $student->SoThich)); ?></textarea>
                                        <div class="textarea-icon">
                                            <i class="bi bi-heart"></i>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['SoThich'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="error-message"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-circle"></i>
                            <span>Lưu thay đổi</span>
                        </button>
                        <a href="<?php echo e(route('sinhvien.profile.show')); ?>" class="btn-cancel">
                            <i class="bi bi-x-circle"></i>
                            <span>Hủy bỏ</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Help Text -->
            <div class="help-text">
                <i class="bi bi-info-circle"></i>
                <span>Thông tin của bạn sẽ được bảo mật và chỉ sử dụng cho mục đích quản lý sinh viên</span>
            </div>
        </div>
    </div>


<style>
    .container {
        background: #f8f9fa;
        min-height: 100vh;
    }

    /* Page Header Card - FIX TRÙNG MÀU */
    .page-header-card {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        animation: fadeInDown 0.6s ease;
        background: #ffffff;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .back-button {
        width: 48px;
        height: 48px;
        background: #f0f4ff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-size: 1.25rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid #e0e7ff;
        flex-shrink: 0;
    }

    .back-button:hover {
        background: #667eea;
        color: #ffffff;
        transform: translateX(-4px);
    }

    .header-content {
        flex: 1;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title i {
        color: #667eea;
    }

    .page-subtitle {
        font-size: 0.95rem;
        color: #6b7280;
        margin: 0;
    }

    /* Error Alert */
    .alert-error-custom {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        gap: 1rem;
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.2);
        animation: shake 0.5s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }

    .alert-error-icon {
        width: 48px;
        height: 48px;
        background: rgba(239, 68, 68, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #dc2626;
        flex-shrink: 0;
    }

    .alert-error-content {
        flex: 1;
    }

    .alert-error-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #991b1b;
        margin: 0 0 0.5rem 0;
    }

    .alert-error-list {
        margin: 0;
        padding-left: 1.25rem;
        color: #b91c1c;
    }

    .alert-error-list li {
        margin-bottom: 0.25rem;
    }

    /* Edit Card */
    .edit-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Section */
    .form-section {
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .section-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #ffffff;
        flex-shrink: 0;
    }

    .bg-gray {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    }

    .bg-blue {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
    }

    .section-description {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0;
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
        margin: 2rem 0;
    }

    /* Form Groups */
    .form-group-custom {
        margin-bottom: 0;
    }

    .form-label-custom {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
    }

    .form-label-custom.required::after {
        content: "*";
        color: #ef4444;
        margin-left: 0.25rem;
    }

    .form-label-custom i {
        color: #667eea;
        font-size: 1rem;
    }

    /* Read-only Input */
    .input-readonly {
        background: #f3f4f6;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* Editable Input */
    .input-wrapper,
    .textarea-wrapper {
        position: relative;
    }

    .form-control-custom {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        font-size: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: #ffffff;
        color: #1f2937;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .form-control-custom.is-invalid {
        border-color: #ef4444;
    }

    .form-control-custom.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .textarea-custom {
        resize: vertical;
        min-height: 120px;
    }

    .input-icon,
    .textarea-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.125rem;
        pointer-events: none;
    }

    .textarea-icon {
        top: 1rem;
        transform: none;
    }

    .error-message {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #ef4444;
        font-weight: 500;
    }

    /* Action Buttons */
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 2px solid #f3f4f6;
    }

    .btn-save,
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        flex: 1;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #6b7280;
        min-width: 150px;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
        color: #374151;
    }

    /* Help Text */
    .help-text {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding: 1rem 1.25rem;
        background: #ffffff;
        border-radius: 12px;
        color: #6b7280;
        font-size: 0.875rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .help-text i {
        color: #667eea;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header-card {
            flex-direction: column;
            text-align: center;
        }

        .back-button {
            align-self: flex-start;
        }

        .edit-card {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .section-icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .section-title {
            font-size: 1.125rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-save,
        .btn-cancel {
            width: 100%;
        }
    }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/thongtin_sinhvien/profile_edit.blade.php ENDPATH**/ ?>