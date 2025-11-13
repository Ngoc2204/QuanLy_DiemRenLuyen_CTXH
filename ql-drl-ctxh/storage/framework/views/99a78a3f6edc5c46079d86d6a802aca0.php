

<?php $__env->startSection('content'); ?>

<style>
    .container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Page Header Card */
    .page-header-card {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        animation: fadeInDown 0.6s ease;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 1.75rem 2rem;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.25rem;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .back-button:hover {
        transform: translateX(-6px) scale(1.05);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .header-content {
        flex: 1;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title i {
        -webkit-text-fill-color: #667eea;
        background: none;
    }

    .page-subtitle {
        font-size: 0.95rem;
        color: #6b7280;
        margin: 0;
        font-weight: 500;
    }

    /* Error Alert */
    .alert-error-custom {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        gap: 1.25rem;
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.25);
        animation: shake 0.5s ease;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        75% { transform: translateX(8px); }
    }

    .alert-error-icon {
        width: 56px;
        height: 56px;
        background: rgba(239, 68, 68, 0.15);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
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
        margin: 0 0 0.75rem 0;
    }

    .alert-error-list {
        margin: 0;
        padding-left: 1.25rem;
        color: #b91c1c;
        font-weight: 500;
    }

    .alert-error-list li {
        margin-bottom: 0.5rem;
    }

    /* Edit Card */
    .edit-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        animation: fadeInUp 0.6s ease;
        border: 1px solid rgba(255, 255, 255, 0.3);
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
        margin-bottom: 2.5rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .section-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        position: relative;
        overflow: hidden;
    }

    .section-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
        transform: rotate(45deg);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    .bg-gray {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    }

    .bg-blue {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .section-title {
        font-size: 1.375rem;
        font-weight: 800;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
        letter-spacing: -0.025em;
    }

    .section-description {
        font-size: 0.9375rem;
        color: #6b7280;
        margin: 0;
        font-weight: 500;
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.3), transparent);
        margin: 2.5rem 0;
        position: relative;
    }

    .section-divider::after {
        content: '✦';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 0 1rem;
        color: #667eea;
        font-size: 1rem;
    }

    /* Form Groups */
    .form-group-custom {
        margin-bottom: 0;
    }

    .form-label-custom {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        font-size: 0.9375rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .form-label-custom.required::after {
        content: "*";
        color: #ef4444;
        margin-left: 0.25rem;
        font-size: 1.125rem;
    }

    .form-label-custom i {
        color: #667eea;
        font-size: 1.125rem;
    }

    /* Read-only Input */
    .input-readonly {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border: 2px solid #e5e7eb;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        font-size: 1rem;
        color: #4b5563;
        font-weight: 600;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .input-readonly::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #6b7280, #9ca3af);
    }

    /* Editable Input */
    .input-wrapper,
    .textarea-wrapper {
        position: relative;
    }

    .form-control-custom {
        width: 100%;
        padding: 1rem 1.25rem 1rem 3.25rem;
        font-size: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 14px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #ffffff;
        color: #1f2937;
        font-weight: 500;
    }

    .form-control-custom:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        transform: translateY(-1px);
    }

    .form-control-custom.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }

    .form-control-custom.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
    }

    .textarea-custom {
        resize: vertical;
        min-height: 140px;
        line-height: 1.6;
    }

    .input-icon,
    .textarea-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.25rem;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus ~ .input-icon,
    .form-control-custom:focus ~ .textarea-icon {
        color: #667eea;
        transform: translateY(-50%) scale(1.1);
    }

    .textarea-icon {
        top: 1.25rem;
        transform: none;
    }

    .error-message {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.625rem;
        font-size: 0.875rem;
        color: #ef4444;
        font-weight: 600;
    }

    .error-message::before {
        content: '⚠';
        font-size: 1rem;
    }

    /* Avatar Section */
    .avatar-upload-section {
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
        border-radius: 16px;
        padding: 1.5rem;
        border: 2px dashed #c7d2fe;
        transition: all 0.3s ease;
    }

    .avatar-upload-section:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, #e0e7ff 0%, #d5deff 100%);
    }

    .avatar-preview-container {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .avatar-preview-wrapper {
        position: relative;
    }

    #avatarPreview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .avatar-preview-wrapper:hover #avatarPreview {
        transform: scale(1.05) rotate(3deg);
        box-shadow: 0 12px 32px rgba(102, 126, 234, 0.3);
    }

    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }

    .avatar-preview-wrapper:hover .avatar-overlay {
        opacity: 1;
    }

    .avatar-overlay i {
        color: white;
        font-size: 2rem;
    }

    .avatar-info {
        flex: 1;
    }

    #studentFileInfo {
        display: inline-block;
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .form-control-custom[type="file"] {
        padding: 1rem 1.25rem;
        cursor: pointer;
        border: 2px dashed #cbd5e1;
        background: white;
    }

    .form-control-custom[type="file"]:hover {
        border-color: #667eea;
        background: #f0f4ff;
    }

    /* Action Buttons */
    .form-actions {
        display: flex;
        gap: 1.25rem;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 2px solid #f3f4f6;
    }

    .btn-save,
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        padding: 1rem 2.5rem;
        font-size: 1.0625rem;
        font-weight: 700;
        border-radius: 14px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
        flex: 1;
        position: relative;
        overflow: hidden;
    }

    .btn-save::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-save:hover::before {
        left: 100%;
    }

    .btn-save:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 36px rgba(102, 126, 234, 0.5);
    }

    .btn-save:active {
        transform: translateY(-1px);
    }

    .btn-cancel {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #4b5563;
        min-width: 180px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-cancel:hover {
        background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
        color: #1f2937;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    /* Help Text */
    .help-text {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
        padding: 1.25rem 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        color: #6b7280;
        font-size: 0.9375rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        font-weight: 500;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .help-text i {
        color: #667eea;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 1rem 0;
        }

        .page-header-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.5rem;
        }

        .back-button {
            align-self: flex-start;
        }

        .edit-card {
            padding: 1.5rem;
            border-radius: 20px;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .section-icon {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-save,
        .btn-cancel {
            width: 100%;
        }

        .avatar-preview-container {
            flex-direction: column;
            text-align: center;
        }

        #avatarPreview {
            width: 100px;
            height: 100px;
        }
    }
</style>

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
                <p class="page-subtitle">Cập nhật và hoàn thiện hồ sơ của bạn</p>
            </div>
        </div>

        
        <?php if($errors->any()): ?>
        <div class="alert-error-custom">
            <div class="alert-error-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="alert-error-content">
                <h4 class="alert-error-title">Vui lòng kiểm tra lại thông tin!</h4>
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
            <form action="<?php echo e(route('sinhvien.profile.update')); ?>" method="POST" enctype="multipart/form-data" id="studentProfileForm">
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
                                    Mã số sinh viên
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
                                    Lớp học
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

                        <div class="col-12">
                            <div class="form-group-custom">
                                <label class="form-label-custom">
                                    <i class="bi bi-camera"></i>
                                    Ảnh đại diện
                                </label>
                                <div class="avatar-upload-section">
                                    <div class="avatar-preview-container">
                                        <?php
                                            $avatarUrl = null;
                                            if (!empty(Auth::user()->Avatar) && file_exists(storage_path('app/public/' . Auth::user()->Avatar))) {
                                                $avatarUrl = asset('storage/' . Auth::user()->Avatar);
                                            }
                                        ?>

                                        <div class="avatar-preview-wrapper">
                                            <img id="avatarPreview" 
                                                 src="<?php echo e($avatarUrl ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->HoTen) . '&background=667eea&color=fff&size=150'); ?>" 
                                                 alt="Avatar">
                                            <div class="avatar-overlay">
                                                <i class="bi bi-camera-fill"></i>
                                            </div>
                                        </div>
                                        <div class="avatar-info">
                                            <small class="text-muted d-block mb-2" id="studentFileInfo"></small>
                                            <small class="text-muted d-block">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Kích thước tối đa: 2MB. Định dạng: jpeg, png, webp, gif
                                            </small>
                                        </div>
                                    </div>

                                    <input type="file" 
                                           name="avatar" 
                                           accept="image/*" 
                                           class="form-control-custom <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="studentAvatarInput">
                                    <?php $__errorArgs = ['avatar'];
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
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Lưu thay đổi</span>
                    </button>
                    <a href="<?php echo e(route('sinhvien.profile.show')); ?>" class="btn-cancel">
                        <i class="bi bi-x-circle-fill"></i>
                        <span>Hủy bỏ</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Help Text -->
        <div class="help-text">
            <i class="bi bi-shield-check-fill"></i>
            <span>Thông tin của bạn sẽ được bảo mật và chỉ sử dụng cho mục đích quản lý sinh viên</span>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('studentAvatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const fileInfo = document.getElementById('studentFileInfo');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                fileInfo.textContent = `📎 ${file.name} (${sizeMB} MB)`;
                fileInfo.style.display = 'inline-block';

                const reader = new FileReader();
                reader.onload = function(event) {
                    avatarPreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                fileInfo.textContent = '';
                fileInfo.style.display = 'none';
            }
        });
    }

    // Add floating animation to section icons
    const sectionIcons = document.querySelectorAll('.section-icon');
    sectionIcons.forEach((icon, index) => {
        icon.style.animation = `float 3s ease-in-out ${index * 0.5}s infinite`;
    });

    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('.btn-save, .btn-cancel');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple-effect');

            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });
});

// Add floating animation keyframes
const style = document.createElement('style');
style.textContent = `
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    .btn-save, .btn-cancel {
        position: relative;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/thongtin_sinhvien/profile_edit.blade.php ENDPATH**/ ?>