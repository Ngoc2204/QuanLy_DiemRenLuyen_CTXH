

<?php $__env->startSection('title', 'Tạo Mã QR Check-In DRL'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --info-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .page-header {
        background: var(--primary-gradient);
        border-radius: 16px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-xl);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-header h1 {
        font-weight: 700;
        font-size: 1.875rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header-icon {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    .page-header-subtitle {
        font-size: 1rem;
        opacity: 0.95;
        font-weight: 400;
    }

    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .info-card, .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--gray-200);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .info-card:hover, .form-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }

    .card-header {
        padding: 1.75rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .card-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-800);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header-icon {
        width: 40px;
        height: 40px;
        background: var(--info-gradient);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .card-body {
        padding: 1.75rem;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: 10px;
        border-left: 4px solid #667eea;
        transition: all 0.2s;
    }

    .info-item:hover {
        background: var(--gray-100);
        transform: translateX(4px);
    }

    .info-icon {
        width: 36px;
        height: 36px;
        background: var(--primary-gradient);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
        min-width: 0;
    }

    .info-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-800);
        word-break: break-word;
    }

    .qr-status-alert {
        margin-top: 1.5rem;
        padding: 1.25rem;
        border-radius: 12px;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .qr-status-alert.active {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 2px solid #10b981;
    }

    .qr-status-alert-icon {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #059669;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .qr-status-alert-content {
        flex: 1;
    }

    .qr-status-alert-title {
        font-weight: 700;
        color: #065f46;
        margin-bottom: 0.375rem;
        font-size: 0.9375rem;
    }

    .qr-status-alert-text {
        font-size: 0.8125rem;
        color: #047857;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.75rem;
    }

    .form-label-icon {
        width: 24px;
        height: 24px;
        background: var(--info-gradient);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        font-size: 0.9375rem;
        transition: all 0.2s;
        background-color: white;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .form-control:hover {
        border-color: #667eea;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-error {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        color: #dc2626;
        font-size: 0.8125rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    .btn-submit {
        width: 100%;
        padding: 1rem 1.5rem;
        background: var(--success-gradient);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .info-alert {
        margin-top: 1.5rem;
        padding: 1.25rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #f59e0b;
        border-radius: 12px;
        display: flex;
        gap: 1rem;
    }

    .info-alert-icon {
        width: 32px;
        height: 32px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d97706;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .info-alert-content {
        flex: 1;
    }

    .info-alert-title {
        font-weight: 700;
        color: #92400e;
        margin-bottom: 0.375rem;
        font-size: 0.9375rem;
    }

    .info-alert-text {
        font-size: 0.8125rem;
        color: #78350f;
        line-height: 1.6;
    }

    .action-bar {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--gray-200);
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.875rem 1.5rem;
        background: white;
        color: var(--gray-700);
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        font-size: 0.9375rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: var(--gray-50);
        border-color: var(--gray-600);
        transform: translateX(-4px);
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.75rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .grid-container {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .card-header, .card-body {
            padding: 1.25rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-header-content">
        <h1>
            <div class="page-header-icon">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <span>Tạo Mã QR Check-In DRL</span>
        </h1>
        <p class="page-header-subtitle">
            Thiết lập thời gian và phát mã QR để sinh viên check-in tham gia hoạt động rèn luyện
        </p>
    </div>
</div>

<div class="grid-container">
    <!-- Card thông tin hoạt động -->
    <div class="info-card">
        <div class="card-header">
            <h2>
                <div class="card-header-icon">
                    <i class="fa-solid fa-info-circle"></i>
                </div>
                Thông tin hoạt động
            </h2>
        </div>
        <div class="card-body">
            <div class="info-list">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Tên hoạt động</div>
                        <div class="info-value"><?php echo e($hoatdong_drl->TenHoatDong); ?></div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Mã hoạt động</div>
                        <div class="info-value"><?php echo e($hoatdong_drl->MaHoatDong); ?></div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Thời gian bắt đầu</div>
                        <div class="info-value">
                            <?php echo e($hoatdong_drl->ThoiGianBatDau ? $hoatdong_drl->ThoiGianBatDau->format('H:i - d/m/Y') : 'Chưa xác định'); ?>

                        </div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fa-solid fa-hourglass-end"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Thời gian kết thúc</div>
                        <div class="info-value">
                            <?php echo e($hoatdong_drl->ThoiGianKetThuc ? $hoatdong_drl->ThoiGianKetThuc->format('H:i - d/m/Y') : 'Chưa xác định'); ?>

                        </div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Số lượng</div>
                        <div class="info-value"><?php echo e($hoatdong_drl->SoLuong); ?> sinh viên</div>
                    </div>
                </div>
            </div>

            <?php if($hoatdong_drl->CheckInToken && $hoatdong_drl->CheckInOpenAt && $hoatdong_drl->CheckInExpiresAt): ?>
                <div class="qr-status-alert active">
                    <div class="qr-status-alert-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="qr-status-alert-content">
                        <div class="qr-status-alert-title">Mã QR đang hoạt động</div>
                        <div class="qr-status-alert-text">
                            <strong>Hiệu lực:</strong> <?php echo e($hoatdong_drl->CheckInOpenAt->format('H:i - d/m/Y')); ?> 
                            đến <?php echo e($hoatdong_drl->CheckInExpiresAt->format('H:i - d/m/Y')); ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Form tạo mã QR -->
    <div class="form-card">
        <div class="card-header">
            <h2>
                <div class="card-header-icon" style="background: var(--success-gradient);">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                Phát hành mã QR
            </h2>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('nhanvien.hoatdong_drl.generate_checkin_qr', $hoatdong_drl)); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="form-label">
                        <div class="form-label-icon">
                            <i class="fa-solid fa-play"></i>
                        </div>
                        Thời gian bắt đầu quét (Mở từ)
                    </label>
                    <input type="datetime-local" 
                           name="CheckInOpenAt" 
                           class="form-control"
                           value="<?php echo e(old('CheckInOpenAt', $hoatdong_drl->ThoiGianBatDau ? $hoatdong_drl->ThoiGianBatDau->format('Y-m-d\TH:i') : '')); ?>"
                           required>
                    <?php $__errorArgs = ['CheckInOpenAt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="form-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <div class="form-label-icon" style="background: var(--danger-gradient);">
                            <i class="fa-solid fa-stop"></i>
                        </div>
                        Thời gian hết hạn (Đóng lúc)
                    </label>
                    <input type="datetime-local" 
                           name="CheckInExpiresAt" 
                           class="form-control"
                           value="<?php echo e(old('CheckInExpiresAt', $hoatdong_drl->ThoiGianKetThuc ? $hoatdong_drl->ThoiGianKetThuc->format('Y-m-d\TH:i') : '')); ?>"
                           required>
                    <?php $__errorArgs = ['CheckInExpiresAt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="form-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-rocket"></i>
                    Phát Mã QR Check-In
                </button>
            </form>

            <div class="info-alert">
                <div class="info-alert-icon">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <div class="info-alert-content">
                    <div class="info-alert-title">Lưu ý quan trọng</div>
                    <div class="info-alert-text">
                        Thời gian mở và đóng có thể điều chỉnh linh hoạt. Sinh viên chỉ có thể quét mã QR khi nằm trong khoảng thời gian này.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="action-bar">
    <a href="<?php echo e(route('nhanvien.hoatdong_drl.show', $hoatdong_drl)); ?>" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i>
        Quay lại chi tiết
    </a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/hoatdong_drl/create_checkin_qr.blade.php ENDPATH**/ ?>