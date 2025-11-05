

<?php $__env->startSection('title', 'Thêm nhân viên'); ?>
<?php $__env->startSection('page_title', 'Thêm nhân viên mới'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* VARIABLES (Giữ nguyên từ giao diện Khoa) */
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --primary-light: #818cf8;
        --success: #10b981;
        --danger: #ef4444;
        --dark: #1e293b;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-600: #475569;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    /* Container & Layout */
    .page-wrapper {
        max-width: 900px;
        /* Tăng chiều rộng để chứa nhiều trường hơn */
        margin: 0 auto;
    }

    /* Breadcrumb */
    .breadcrumb-modern {
        background: transparent;
        padding: 0;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .breadcrumb-modern a {
        color: var(--gray-600);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .breadcrumb-modern a:hover {
        color: var(--primary);
    }

    .breadcrumb-modern .separator {
        color: var(--gray-600);
    }

    .breadcrumb-modern .active {
        color: var(--primary);
        font-weight: 600;
    }

    /* Form Card Container */
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray-200);
        overflow: hidden;
    }

    /* Form Card Header (Gradient & Shapes) */
    .form-card-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .form-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .form-card-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .form-card-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .form-card-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 0;
    }

    .form-card-icon {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .form-card-title-text h1 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }

    .form-card-title-text p {
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-size: 0.9375rem;
    }

    /* Back Button */
    .btn-back-modern {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0.625rem 1.25rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-back-modern:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        transform: translateX(-4px);
    }

    /* Form Card Body */
    .form-card-body {
        padding: 2.5rem;
    }

    /* Alert (Validation) */
    .alert-modern {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: start;
        gap: 0.75rem;
    }

    .alert-danger-modern {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.05));
        border-left: 4px solid var(--danger);
        color: #991b1b;
    }

    .alert-danger-modern i {
        font-size: 1.25rem;
        margin-top: 2px;
    }

    .alert-danger-modern strong {
        display: block;
        margin-bottom: 0.25rem;
        font-weight: 700;
    }

    /* Form Group & Label */
    .form-group-modern {
        margin-bottom: 1.75rem;
    }

    .form-label-modern {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.625rem;
        font-size: 0.9375rem;
    }

    .form-label-modern i {
        color: var(--primary);
        font-size: 1rem;
    }

    .required-mark {
        color: var(--danger);
        margin-left: 2px;
    }

    .input-wrapper {
        position: relative;
    }

    /* Input Icon inside field */
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-600);
        font-size: 1rem;
        z-index: 1;
    }

    /* Input/Select/Textarea Styling */
    .form-control-modern,
    .form-select-modern {
        width: 100%;
        padding: 0.875rem 1rem;
        padding-left: 3rem;
        /* Để chừa chỗ cho icon */
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        background: white;
        color: var(--dark);
        appearance: none;
        /* Reset appearance cho select */
    }

    .form-select-modern {
        padding-right: 1.5rem;
        /* Điều chỉnh lại padding-right cho select */
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 0.75rem 0.75rem;
    }

    .form-control-modern:focus,
    .form-select-modern:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    /* Invalid State */
    .form-control-modern.is-invalid,
    .form-select-modern.is-invalid {
        border-color: var(--danger);
        padding-right: 3rem;
    }

    .form-control-modern.is-invalid:focus,
    .form-select-modern.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    /* Invalid Icon for Input/Select */
    .invalid-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--danger);
        font-size: 1.125rem;
        z-index: 2;
        /* Đảm bảo icon nằm trên select arrow */
    }

    /* Error Message below field */
    .error-message {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    .error-message i {
        font-size: 0.875rem;
    }

    /* Hint Text below field */
    .input-hint {
        font-size: 0.8125rem;
        color: var(--gray-600);
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .input-hint i {
        font-size: 0.75rem;
    }

    /* Layout for multi-column form inside body */
    .form-row-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1rem;
        /* Adjust margin if needed */
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
        border-top: 2px solid var(--gray-100);
    }

    /* Buttons */
    .btn-modern {
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9375rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        text-decoration: none;
    }

    /* Sử dụng .btn-success-modern cho nút "Lưu" */
    .btn-success-modern {
        background: linear-gradient(135deg, var(--success), #059669);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-success-modern:hover {
        background: linear-gradient(135deg, #059669, var(--success));
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-success-modern:active {
        transform: translateY(0);
    }

    .btn-secondary-modern {
        background: white;
        color: var(--gray-600);
        border: 2px solid var(--gray-200);
    }

    .btn-secondary-modern:hover {
        background: var(--gray-50);
        border-color: var(--gray-600);
        color: var(--gray-600);
    }

    /* Info Box */
    .form-info-box {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(79, 70, 229, 0.1));
        border: 2px solid rgba(99, 102, 241, 0.2);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 2rem;
    }

    .form-info-box-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .form-info-box-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .form-info-box-title {
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
    }

    .form-info-box-content {
        color: var(--gray-600);
        font-size: 0.875rem;
        line-height: 1.6;
        margin: 0;
    }

    .form-info-box-content ul {
        margin: 0.5rem 0 0 0;
        padding-left: 1.25rem;
    }

    .form-info-box-content li {
        margin-bottom: 0.375rem;
    }

    /* Media Queries */
    @media (max-width: 768px) {
        .form-card-header {
            padding: 1.5rem;
        }

        .form-card-title {
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }

        .form-card-title-text h1 {
            font-size: 1.5rem;
        }

        .btn-back-modern {
            width: 100%;
            justify-content: center;
        }

        .form-card-body {
            padding: 1.5rem;
        }

        .form-row-grid {
            grid-template-columns: 1fr;
            /* Stack columns on mobile */
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-modern {
            width: 100%;
            justify-content: center;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <!-- Breadcrumb -->
    <nav class="breadcrumb-modern">
        <a href="<?php echo e(route('admin.nhanvien.index')); ?>">
            <i class="fa-solid fa-briefcase"></i>
            Quản lý nhân viên
        </a>
        <span class="separator">/</span>
        <span class="active">Thêm nhân viên mới</span>
    </nav>

    <!-- Form Card -->
    <div class="form-card">
        <!-- Header -->
        <div class="form-card-header">
            <div class="form-card-header-content">
                <div class="form-card-title">
                    <div class="form-card-icon">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div class="form-card-title-text">
                        <h1>Thêm nhân viên mới</h1>
                        <p>Nhập thông tin chi tiết để tạo hồ sơ nhân viên mới</p>
                    </div>
                </div>
                <a href="<?php echo e(route('admin.nhanvien.index')); ?>" class="btn-back-modern">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="form-card-body">
            
            <?php if($errors->any()): ?>
            <div class="alert-modern alert-danger-modern">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <strong>Lỗi nhập liệu!</strong>
                    <p style="margin: 0;">Vui lòng kiểm tra và sửa các thông tin lỗi bên dưới.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Info Box -->
            <div class="form-info-box">
                <div class="form-info-box-header">
                    <div class="form-info-box-icon">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h6 class="form-info-box-title">Hướng dẫn</h6>
                </div>
                <div class="form-info-box-content">
                    <p>Lưu ý khi thêm nhân viên mới:</p>
                    <ul>
                        <li>**Mã nhân viên** là duy nhất và bắt buộc.</li>
                        <li>Các trường có dấu <span class="required-mark">*</span> là bắt buộc.</li>
                    </ul>
                </div>
            </div>

            <!-- Form -->
            <form action="<?php echo e(route('admin.nhanvien.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                
                <div class="form-row-grid">
                    <div class="form-group-modern">
                        <label for="MaNV" class="form-label-modern">
                            <i class="fa-solid fa-id-badge"></i>
                            Mã nhân viên
                            <span class="required-mark">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fa-solid fa-barcode"></i>
                            <input type="text" 
                                   id="MaNV" 
                                   name="MaNV" 
                                   value="<?php echo e(old('MaNV')); ?>"
                                   class="form-control-modern <?php $__errorArgs = ['MaNV'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Ví dụ: NV001" 
                                   required 
                                   autofocus>
                            <?php $__errorArgs = ['MaNV'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <?php $__errorArgs = ['MaNV'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message">
                            <i class="fa-solid fa-circle-xmark"></i>
                            <?php echo e($message); ?>

                        </div>
                        <?php else: ?>
                        <div class="input-hint">
                            <i class="fa-solid fa-circle-info"></i>
                            Mã nhân viên là trường duy nhất và không thể thay đổi.
                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group-modern">
                        <label for="TenNV" class="form-label-modern">
                            <i class="fa-solid fa-user"></i>
                            Họ và tên
                            <span class="required-mark">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fa-solid fa-pen-nib"></i>
                            <input type="text" 
                                   id="TenNV" 
                                   name="TenNV" 
                                   value="<?php echo e(old('TenNV')); ?>"
                                   class="form-control-modern <?php $__errorArgs = ['TenNV'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Nhập họ và tên đầy đủ" 
                                   required>
                            <?php $__errorArgs = ['TenNV'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <?php $__errorArgs = ['TenNV'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message">
                            <i class="fa-solid fa-circle-xmark"></i>
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="form-row-grid">
                    <div class="form-group-modern">
                        <label for="Email" class="form-label-modern">
                            <i class="fa-solid fa-envelope"></i>
                            Email
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fa-solid fa-at"></i>
                            <input type="email" 
                                   id="Email" 
                                   name="Email" 
                                   value="<?php echo e(old('Email')); ?>"
                                   class="form-control-modern <?php $__errorArgs = ['Email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Ví dụ: email@example.com">
                            <?php $__errorArgs = ['Email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <?php $__errorArgs = ['Email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message">
                            <i class="fa-solid fa-circle-xmark"></i>
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group-modern">
                        <label for="SDT" class="form-label-modern">
                            <i class="fa-solid fa-phone"></i>
                            Số điện thoại
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fa-solid fa-phone-volume"></i>
                            <input type="text" 
                                   id="SDT" 
                                   name="SDT" 
                                   value="<?php echo e(old('SDT')); ?>"
                                   class="form-control-modern <?php $__errorArgs = ['SDT'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Ví dụ: 0987654321">
                            <?php $__errorArgs = ['SDT'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <?php $__errorArgs = ['SDT'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><i class="fa-solid fa-circle-xmark"></i><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="form-group-modern">
                    <label for="GioiTinh" class="form-label-modern">
                        <i class="fa-solid fa-venus-mars"></i>
                        Giới tính
                        <span class="required-mark">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fa-solid fa-people-arrows"></i>
                        <select id="GioiTinh" 
                                name="GioiTinh"
                                class="form-select-modern <?php $__errorArgs = ['GioiTinh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                required>
                            <option value="">-- Chọn giới tính --</option>
                            <option value="Nam" <?php echo e(old('GioiTinh') == 'Nam' ? 'selected' : ''); ?>>Nam</option>
                            <option value="Nữ" <?php echo e(old('GioiTinh') == 'Nữ' ? 'selected' : ''); ?>>Nữ</option>
                            <option value="Khác" <?php echo e(old('GioiTinh') == 'Khác' ? 'selected' : ''); ?>>Khác</option>
                        </select>
                        <?php $__errorArgs = ['GioiTinh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <i class="invalid-icon fa-solid fa-circle-exclamation" style="right: 3rem;"></i>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php $__errorArgs = ['GioiTinh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><i class="fa-solid fa-circle-xmark"></i><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="<?php echo e(route('admin.nhanvien.index')); ?>" class="btn-modern btn-secondary-modern">
                        <i class="fa-solid fa-xmark"></i>
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn-modern btn-success-modern">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Lưu nhân viên
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/nhanvien/create.blade.php ENDPATH**/ ?>