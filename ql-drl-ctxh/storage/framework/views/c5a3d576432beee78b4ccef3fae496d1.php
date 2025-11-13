

<?php $__env->startSection('title', 'Thông tin cá nhân'); ?>
<?php $__env->startSection('page_title', 'Thông tin cá nhân'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .profile-tabs .nav-link {
        color: #64748b;
        border: none;
        padding: 12px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
    }
    
    .profile-tabs .nav-link:hover {
        color: #4f46e5;
        background: #f8fafc;
    }
    
    .profile-tabs .nav-link.active {
        color: #4f46e5;
        background: transparent;
        border-bottom-color: #4f46e5;
    }
    
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }
    
    .profile-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .profile-card .card-body {
        padding: 2rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .avatar-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .avatar-preview:hover {
        transform: scale(1.05);
    }
    
    .avatar-upload-btn {
        background: white;
        color: #4f46e5;
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .avatar-upload-btn:hover {
        border-color: #4f46e5;
        background: #f8fafc;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
        color: white;
    }
    
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 16px;
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .info-card .card-header {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        padding: 1.25rem;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .info-card .card-body {
        padding: 1.5rem;
    }
    
    .info-card p {
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-card strong {
        opacity: 0.9;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    
    .info-card hr {
        border-color: rgba(255, 255, 255, 0.2);
        margin: 1.5rem 0;
    }
    
    .alert {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .alert-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .file-info-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    
    .password-icon {
        font-size: 3rem;
        color: #f59e0b;
        margin-bottom: 1rem;
        display: block;
        text-align: center;
    }
</style>

<div class="profile-container">
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Tab điều hướng -->
            <ul class="nav nav-tabs profile-tabs mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                        <i class="fa-solid fa-user me-2"></i> Thông tin cá nhân
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                        <i class="fa-solid fa-lock me-2"></i> Đổi mật khẩu
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Tab 1: Thông tin cá nhân -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <div class="card profile-card">
                        <div class="card-body">
                            <?php if(session('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fa-solid fa-circle-check me-2"></i>
                                    <?php echo e(session('success')); ?>

                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo e(route('nhanvien.profile.update')); ?>" method="POST" enctype="multipart/form-data" id="profileForm">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <!-- Avatar Section -->
                                <div class="avatar-section">
                                    <div class="text-center">
                                        <?php
                                            $avatarPath = null;
                                            if (!empty($user->Avatar)) {
                                                $avatarPath = asset('storage/' . $user->Avatar);
                                            }
                                        ?>

                                        <img id="avatarPreview" 
                                             src="<?php echo e($avatarPath ?? 'https://ui-avatars.com/api/?name=' . urlencode(old('TenNV', optional($nhanvien)->TenNV ?? $user->TenDangNhap)) . '&background=fff&color=4f46e5&size=150'); ?>" 
                                             alt="Avatar" 
                                             class="avatar-preview mb-3">
                                        
                                        <div id="fileInfo" class="file-info-badge" style="display: none;"></div>
                                        
                                        <div class="mt-3">
                                            <label for="avatarInput" class="avatar-upload-btn">
                                                <i class="fa-solid fa-camera me-2"></i> Chọn ảnh đại diện
                                            </label>
                                            <input type="file" name="avatar" accept="image/*" class="d-none <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="avatarInput">
                                        </div>
                                        
                                        <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                            <div class="text-white mt-2">
                                                <i class="fa-solid fa-circle-exclamation me-1"></i>
                                                <?php echo e($message); ?>

                                            </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        
                                        <small class="text-white d-block mt-2 opacity-75">
                                            <i class="fa-solid fa-info-circle me-1"></i>
                                            Kích thước tối đa: 2MB. Định dạng: jpeg, png, gif, webp
                                        </small>
                                    </div>
                                </div>

                                <!-- Form Fields -->
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fa-solid fa-user me-1"></i> Họ và tên
                                        </label>
                                        <input type="text" name="TenNV" class="form-control <?php $__errorArgs = ['TenNV'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('TenNV', optional($nhanvien)->TenNV ?? $user->TenDangNhap)); ?>" required>
                                        <?php $__errorArgs = ['TenNV'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fa-solid fa-venus-mars me-1"></i> Giới tính
                                        </label>
                                        <select name="GioiTinh" class="form-select">
                                            <option value="">-- Chọn giới tính --</option>
                                            <option value="Nam" <?php echo e(old('GioiTinh', optional($nhanvien)->GioiTinh) == 'Nam' ? 'selected' : ''); ?>>Nam</option>
                                            <option value="Nữ" <?php echo e(old('GioiTinh', optional($nhanvien)->GioiTinh) == 'Nữ' ? 'selected' : ''); ?>>Nữ</option>
                                            <option value="Khác" <?php echo e(old('GioiTinh', optional($nhanvien)->GioiTinh) == 'Khác' ? 'selected' : ''); ?>>Khác</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fa-solid fa-envelope me-1"></i> Email
                                        </label>
                                        <input type="email" name="Email" class="form-control <?php $__errorArgs = ['Email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('Email', optional($nhanvien)->Email)); ?>">
                                        <?php $__errorArgs = ['Email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fa-solid fa-phone me-1"></i> Số điện thoại
                                        </label>
                                        <input type="text" name="SDT" class="form-control <?php $__errorArgs = ['SDT'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('SDT', optional($nhanvien)->SDT)); ?>">
                                        <?php $__errorArgs = ['SDT'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-save me-2"></i> Lưu thay đổi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Đổi mật khẩu -->
                <div class="tab-pane fade" id="password" role="tabpanel">
                    <div class="card profile-card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <i class="fa-solid fa-shield-halved password-icon"></i>
                                <h5 class="mb-2">Bảo mật tài khoản</h5>
                                <p class="text-muted">Thay đổi mật khẩu để bảo vệ tài khoản của bạn</p>
                            </div>

                            <?php if(session('password_success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fa-solid fa-circle-check me-2"></i>
                                    <?php echo e(session('password_success')); ?>

                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo e(route('nhanvien.profile.update_password')); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fa-solid fa-lock me-1"></i> Mật khẩu hiện tại
                                    </label>
                                    <input type="password" name="current_password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fa-solid fa-key me-1"></i> Mật khẩu mới
                                    </label>
                                    <input type="password" name="new_password" class="form-control <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fa-solid fa-check-double me-1"></i> Xác nhận mật khẩu mới
                                    </label>
                                    <input type="password" name="new_password_confirmation" class="form-control <?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fa-solid fa-rotate me-2"></i> Đổi mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card info-card">
                <div class="card-header">
                    <i class="fa-solid fa-id-card me-2"></i> Thông tin tài khoản
                </div>
                <div class="card-body">
                    <p>
                        <strong>Tên đăng nhập</strong><br>
                        <span style="font-size: 1.1rem;"><?php echo e($user->TenDangNhap); ?></span>
                    </p>
                    <p>
                        <strong>Vai trò</strong><br>
                        <span class="badge bg-light text-dark px-3 py-2" style="font-size: 0.9rem;">
                            <i class="fa-solid fa-user-tie me-1"></i> <?php echo e($user->VaiTro); ?>

                        </span>
                    </p>
                    <hr>
                    <small class="opacity-75">
                        <i class="fa-solid fa-fingerprint me-1"></i> ID: <?php echo e($user->TenDangNhap); ?>

                    </small>
                </div>
            </div>

            <!-- Thẻ thông tin bổ sung -->
            <div class="card profile-card mt-4">
                <div class="card-body text-center">
                    <i class="fa-solid fa-lightbulb text-warning" style="font-size: 2.5rem; margin-bottom: 1rem;"></i>
                    <h6 class="mb-2">Mẹo bảo mật</h6>
                    <p class="text-muted small mb-0">
                        Sử dụng mật khẩu mạnh và thay đổi định kỳ để bảo vệ tài khoản của bạn.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript: Avatar Preview + File Info -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const fileInfo = document.getElementById('fileInfo');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Show file info
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                fileInfo.style.display = 'inline-block';
                fileInfo.innerHTML = `<i class="fa-solid fa-image me-2"></i>${file.name} (${sizeMB} MB)`;

                // Preview image
                const reader = new FileReader();
                reader.onload = function(event) {
                    avatarPreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                fileInfo.style.display = 'none';
                fileInfo.textContent = '';
            }
        });
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/profile/edit.blade.php ENDPATH**/ ?>