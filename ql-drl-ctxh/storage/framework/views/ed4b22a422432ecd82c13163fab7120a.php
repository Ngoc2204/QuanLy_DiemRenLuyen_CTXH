

<?php $__env->startSection('title', 'Thông tin sinh viên'); ?>
<?php $__env->startSection('page_title', 'Chi tiết sinh viên'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* SAO CHÉP TOÀN BỘ CSS TỪ FILE MẪU CỦA BẠN */
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

    .page-wrapper {
        max-width: 900px; /* Tăng nhẹ độ rộng cho trang chi tiết */
        margin: 0 auto;
    }

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

    /* Đổi tên .form-card thành .profile-card cho ngữ nghĩa */
    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray-200);
        overflow: hidden;
    }

    .profile-card-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .profile-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .profile-card-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .profile-card-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .profile-card-title {
        display: flex;
        align-items: center;
        gap: 1.25rem; /* Tăng khoảng cách */
        margin: 0;
    }

    .profile-card-icon {
        width: 80px; /* Lớn hơn cho avatar */
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 20px; /* Bo tròn hơn */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 6px; /* Thêm padding để chứa ảnh */
    }

    /* CSS cho avatar bên trong icon */
    .profile-card-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 16px; /* Bo tròn nhỏ hơn container */
        box-shadow: var(--shadow);
    }

    .profile-card-title-text h1 {
        font-size: 2rem; /* Lớn hơn cho tên */
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }

    .profile-card-title-text p {
        margin: 0.35rem 0 0 0;
        opacity: 0.9;
        font-size: 1rem; /* Lớn hơn cho MSSV */
        font-weight: 500;
        background: rgba(0,0,0,0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        display: inline-block;
    }

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

    .profile-card-body {
        padding: 2.5rem;
    }

    /* CSS MỚI: Tiêu đề cho các phần thông tin */
    .info-section-title {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-top: 1rem;
        margin-bottom: 1.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--gray-100);
    }
    .info-section-title:first-child {
        margin-top: 0;
    }
    .info-section-title i {
        font-size: 1.1rem;
    }

    /* CSS MỚI: Lưới hiển thị thông tin */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr; /* 1 cột trên mobile */
        gap: 1.75rem;
    }

    /* 2 cột trên tablet và lớn hơn */
    @media (min-width: 768px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    /* 2 cột trên desktop lớn */
    @media (min-width: 992px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        /* Cho mục sở thích chiếm 2 cột */
        .info-grid .full-width {
             grid-column: span 2 / span 2;
        }
    }


    /* CSS MỚI: Từng mục thông tin */
    .info-item {
        position: relative;
        background: var(--gray-50);
        padding: 1.25rem;
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        transition: all 0.2s ease;
    }
    .info-item:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow);
        border-color: var(--primary-light);
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 0.6rem;
        font-size: 0.9rem;
    }

    .info-label i {
        color: var(--primary);
        font-size: 1rem;
        width: 20px;
        text-align: center;
    }

    .info-value {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--dark);
        word-break: break-word;
        padding-left: 2.1rem; /* Căn lề với text của label */
    }

    .info-value .empty {
        font-style: italic;
        color: var(--gray-600);
        font-weight: 500;
    }

    /* CSS Responsive */
    @media (max-width: 768px) {
        .profile-card-header {
            padding: 1.5rem;
        }

        .profile-card-title {
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }

        .profile-card-title-text h1 {
            font-size: 1.75rem;
        }
        .profile-card-title-text p {
            font-size: 0.9rem;
        }

        .btn-back-modern {
            width: 100%;
            justify-content: center;
        }

        .profile-card-body {
            padding: 1.5rem;
        }
        
        /* Chuyển về 1 cột trên mobile */
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <!-- Breadcrumb -->
    <nav class="breadcrumb-modern">
        <a href="<?php echo e(route('admin.sinhvien.index')); ?>">
            <i class="fa-solid fa-users"></i>
            Quản lý sinh viên
        </a>
        <span class="separator">/</span>
        <span class="active">Chi tiết sinh viên</span>
    </nav>

    <!-- Profile Card -->
    <div class="profile-card">
        <!-- Header -->
        <div class="profile-card-header">
            <div class="profile-card-header-content">
                <div class="profile-card-title">
                    <div class="profile-card-icon">
                        <!-- Avatar -->
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($sinhvien->HoTen)); ?>&background=fff&color=4f46e5&font-size=0.4&bold=true" 
                             alt="Avatar của <?php echo e($sinhvien->HoTen); ?>">
                    </div>
                    <div class="profile-card-title-text">
                        <h1><?php echo e($sinhvien->HoTen); ?></h1>
                        <p>MSSV: <?php echo e($sinhvien->MSSV); ?></p>
                    </div>
                </div>
                <a href="<?php echo e(route('admin.sinhvien.index')); ?>" class="btn-back-modern">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="profile-card-body">
            
            <!-- Thông tin cá nhân -->
            <h2 class="info-section-title">
                <i class="fa-solid fa-user-circle"></i>
                Thông tin cá nhân
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <label class="info-label"><i class="fa-solid fa-cake-candles"></i>Ngày sinh</label>
                    <div class="info-value">
                        <?php echo e(\Carbon\Carbon::parse($sinhvien->NgaySinh)->format('d/m/Y')); ?>

                    </div>
                </div>

                <div class="info-item">
                    <label class="info-label"><i class="fa-solid fa-venus-mars"></i>Giới tính</label>
                    <div class="info-value">
                        <?php echo e($sinhvien->GioiTinh); ?>

                    </div>
                </div>
                
                <div class="info-item">
                    <label class="info-label"><i class="fa-solid fa-envelope"></i>Email</label>
                    <div class="info-value">
                        <?php echo $sinhvien->Email ? $sinhvien->Email : '<span class="empty">—</span>'; ?>

                    </div>
                </div>

                <div class="info-item">
                    <label class="info-label"><i class="fa-solid fa-phone"></i>Số điện thoại</label>
                    <div class="info-value">
                        <?php echo $sinhvien->SDT ? $sinhvien->SDT : '<span class="empty">—</span>'; ?>

                    </div>
                </div>

            </div>

            <!-- Thông tin học vấn -->
            <h2 class="info-section-title">
                <i class="fa-solid fa-graduation-cap"></i>
                Thông tin học vấn
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <label class="info-label"><i class="fa-solid fa-school"></i>Khoa</label>
                    <div class="info-value">
                        <?php echo $sinhvien->lop->khoa->TenKhoa ?? '<span class="empty">—</span>'; ?>

                    </div>
                </div>

                <div class="info-item">
                    <label class="info-label"><i class="fa-solid fa-users"></i>Lớp</label>
                    <div class="info-value">
                        <?php echo $sinhvien->lop->TenLop ?? '<span class="empty">—</span>'; ?>

                    </div>
                </div>

                <div class="info-item">
                    <label class="info-label"><i class="fa-solid fa-calendar-days"></i>Năm nhập học</label>
                    <div class="info-value">
                        <?php echo $sinhvien->NamNhapHoc ?? '<span class="empty">—</span>'; ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/sinhvien/show.blade.php ENDPATH**/ ?>