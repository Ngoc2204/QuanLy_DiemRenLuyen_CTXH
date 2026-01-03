<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng thông tin sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="<?php echo e(asset('images/favicon.png')); ?>">

    <style>
        /* ... CSS của bạn (giữ nguyên) ... */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
        }

        body {
            background-color: #f0f2f5;
        }

        /* Header */
        .header {
            background: #ffffff;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 90%;
            max-width: 1400px;
            padding-left: 60px;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        /* Sửa: Thêm class mới để kiểm soát ảnh logo */
        .logo-img {
            height: 40px;
            /* Điều chỉnh chiều cao logo theo ý bạn */
            width: auto;
            /* Tự động tính toán chiều rộng */
            object-fit: contain;
            /* Đảm bảo logo không bị méo */
        }

        .search-bar form {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            padding: 11px 45px 11px 18px;
            border-radius: 30px;
            border: none;
            width: 350px;
            outline: none;
            transition: all 0.3s ease;
            background: #f0f2f5;
            color: #333;
            font-size: 14px;
            backdrop-filter: blur(10px);
        }

        .search-bar input::placeholder {
            color: #888;
        }

        .search-bar input:focus {
            background: #e9ecef;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            width: 380px;
        }

        .search-bar button {
            position: absolute;
            right: 12px;
            border: none;
            background: none;
            color: #555;
            cursor: pointer;
            font-size: 16px;
            transition: color 0.2s;
        }

        .search-bar button:hover {
            color: #111;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-link {
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 20px;
            transition: all 0.3s;
            background: transparent;
        }

        .nav-link:hover {
            background: #f0f2f5;
            transform: translateY(-2px);
        }

        .notification {
            position: relative;
        }

        .notif-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #fff;
            font-size: 10px;
            padding: 3px 6px;
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(245, 87, 108, 0.4);
        }

        .user-menu {
            position: relative;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .user-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
            transition: all 0.3s;
            padding: 8px 15px;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.1);
        }

        .user-toggle:hover {
            background: #f0f2f5;
        }

        .user-menu img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
        }

        .user-menu .dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border-radius: 12px;
            list-style: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            display: none;
            /* Sửa: Sẽ được điều khiển bằng JS */
            width: 200px;
            z-index: 1000;
            overflow: hidden;
            animation: fadeInDown 0.3s ease;
            /* Giữ lại animation */
        }

        /* Sửa: Thêm class .open để JS điều khiển */
        .user-menu.open .dropdown {
            display: block;
        }

        .user-menu .dropdown::before {
            content: "";
            position: absolute;
            top: -6px;
            right: 20px;
            border-width: 6px;
            border-style: solid;
            border-color: transparent transparent white transparent;
        }

        .user-menu .dropdown li {
            border-bottom: 1px solid #f0f0f0;
        }

        .user-menu .dropdown li:last-child {
            border-bottom: none;
        }

        .user-menu .dropdown a {
            display: block;
            padding: 12px 18px;
            color: #333;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
        }

        .user-menu .dropdown a:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* Sửa: Xóa :hover ở đây */
        /* .user-menu:hover .dropdown { ... } */

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Menu Toggle */
        .menu-toggle {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #333;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 1001;
        }

        .menu-toggle:hover {
            background: #f0f2f5;
        }

        /* Sidebar */
        .left-menu {
            position: fixed;
            top: 70px;
            left: 0;
            width: 280px;
            height: calc(100vh - 70px);
            background: white;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999;
        }

        .left-menu::-webkit-scrollbar {
            width: 6px;
        }

        .left-menu::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .left-menu::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        .left-menu.active {
            transform: translateX(0);
        }

        .menu-list {
            list-style: none;
            margin: 0;
            padding: 15px 0;
        }

        .menu-item {
            margin: 4px 12px;
            border-radius: 10px;
            overflow: hidden;
        }

        .menu-link,
        .menu-parent {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            color: #475569;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 10px;
            font-size: 14px;
        }

        .menu-link i,
        .menu-parent i:first-child {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
            color: #667eea;
        }

        .menu-parent .arrow {
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.3s;
        }

        .menu-link:hover,
        .menu-parent:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            color: #667eea;
            transform: translateX(4px);
        }

        .menu-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .menu-link.active i {
            color: white;
        }

        .menu-child {
            display: none;
            background: #f8f9fa;
            margin: 8px 0;
            border-radius: 8px;
            overflow: hidden;

        }

        .menu-child li a {
            display: block;
            padding: 11px 20px 11px 52px;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s;
            position: relative;
        }

        .menu-child li a::before {
            content: "";
            position: absolute;
            left: 32px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            background: #cbd5e1;
            border-radius: 50%;
            transition: all 0.2s;
        }

        .menu-child li a:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.05);
            padding-left: 56px;
        }

        .menu-child li a:hover::before {
            background: #667eea;
            transform: translateY(-50%) scale(1.3);
        }

        .menu-child li a.active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%);
            color: #667eea;
            font-weight: 600;
            border-left: 3px solid #667eea;
        }

        .menu-child li a.active::before {
            background: #667eea;
        }

        .menu-item.open .menu-child {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }

            to {
                opacity: 1;
                max-height: 500px;
            }
        }

        .menu-item.open .arrow {
            transform: rotate(180deg);
        }

        .menu-parent.active {
            color: #667eea;
            font-weight: 600;
        }

        .menu-parent.active i:first-child {
            color: #667eea;
        }

        /* Overlay */
        .menu-overlay {
            position: fixed;
            top: 70px;
            left: 0;
            width: 100%;
            height: calc(100vh - 70px);
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
            display: none;
            z-index: 900;
        }

        .menu-overlay.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Main Content */
        .main-content {
            padding: 30px 8%;
            min-height: calc(100vh - 70px);
        }

        .content-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .content-card h2 {
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 24px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                padding-left: 50px;
                width: 95%;
            }

            .search-bar {
                display: none;
            }

            .user-name {
                display: none;
            }

            .left-menu {
                width: 260px;
            }

            .main-content {
                padding: 20px 5%;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <div class="menu-toggle" id="menu-toggle">
                <i class="fa-solid fa-bars"></i>
            </div>

            <div class="logo">
                <img src="<?php echo e(asset('images/sv_logo_dashboard.png')); ?>" alt="HUIT Portal" class="logo-img">
            </div>

            

            <div class="header-right">
                <a href="<?php echo e(route('sinhvien.home')); ?>" class="nav-link">
                    <i class="fa-solid fa-house"></i> Trang chủ
                </a>

                <a href="<?php echo e(route('sinhvien.tintuc.index')); ?>" class="nav-link notification">
                    <i class="fa fa-bell"></i>
                    <?php if(isset($unreadCount) && $unreadCount > 0): ?>
                    <span class="notif-badge"><?php echo e($unreadCount); ?></span>
                    <?php endif; ?>
                    Tin tức
                </a>

                <!-- Sửa: Thêm ID cho user-menu -->
                <div class="user-menu" id="user-menu">
                    <div class="user-toggle" id="user-toggle">

                        <!-- SỬA 1: AVATAR ĐỘNG -->
                        <?php
                            $svAvatar = Auth::user()->Avatar ?? null;
                            $svAvatarUrl = null;
                            if ($svAvatar && file_exists(storage_path('app/public/' . $svAvatar))) {
                                $svAvatarUrl = asset('storage/' . $svAvatar);
                            }
                        ?>
                        <?php if($svAvatarUrl): ?>
                            <img src="<?php echo e($svAvatarUrl); ?>" alt="User">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->sinhvien->HoTen ?? Auth::user()->TenDangNhap)); ?>&background=667eea&color=fff" alt="User">
                        <?php endif; ?>

                        <!-- SỬA 2: TÊN ĐỘNG -->
                        <span class="user-name"><?php echo e(Auth::user()->sinhvien->HoTen ?? Auth::user()->TenDangNhap); ?></span>

                        <i class="fa-solid fa-caret-down"></i>
                    </div>
                    <ul class="dropdown">
                        <li><a href="<?php echo e(route('sinhvien.profile.show')); ?>"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a></li>
                        <li><a href="<?php echo e(route('sinhvien.thongtin_sinhvien.password_edit')); ?>"><i class="fa-solid fa-key"></i> Đổi mật khẩu</a></li>

                        <!-- SỬA 3: FORM LOGOUT ĐÚNG -->
                        <li>
                            <a href="<?php echo e(route('logout')); ?>"
                                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                            </a>
                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="left-menu" id="left-menu">
        <ul class="menu-list">
            <li class="menu-item">
                <a href="<?php echo e(route('sinhvien.home')); ?>" class="menu-link active">
                    <i class="fa-solid fa-house"></i>
                    <span>Trang chủ</span>
                </a>
            </li>

            <li class="menu-item has-child">
                <div class="menu-parent">
                    <i class="fa-solid fa-user"></i>
                    <span>Thông tin chung</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </div>
                <ul class="menu-child">
                    <li><a href="<?php echo e(route('sinhvien.profile.show')); ?>">Thông tin sinh viên</a></li>
                    <li><a href="<?php echo e(route('sinhvien.profile.edit')); ?>">Chỉnh sửa thông tin</a></li>
                </ul>
            </li>

            <li class="menu-item has-child">
                <div class="menu-parent">
                    <i class="fa-solid fa-medal"></i>
                    <span>Điểm rèn luyện</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </div>
                <ul class="menu-child">
                    <li><a href="<?php echo e(route('sinhvien.diem_ren_luyen')); ?>">Tổng điểm rèn luyện theo kỳ</a></li>
                    
                </ul>
            </li>

            <li class="menu-item has-child">
                <div class="menu-parent">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                    <span>Công tác xã hội</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </div>
                <ul class="menu-child">
                    <li><a href="<?php echo e(route('sinhvien.diem_cong_tac_xa_hoi')); ?>"<li><a href="#">Trạng thái hoạt động đã đăng ký</a></li>

                </ul>
            </li>


            <li class="menu-item">
                <a href="<?php echo e(route('sinhvien.thongbao_hoatdong')); ?>" class="menu-link">
                    <i class="fa-solid fa-bell"></i>
                    <span>Thông báo hoạt động</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="<?php echo e(route('sinhvien.activities_recommended.index')); ?>" class="menu-link">
                    <i class="fa-solid fa-magic"></i>
                    <span>Hoạt động đề xuất</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="<?php echo e(route('sinhvien.lienhe.create')); ?>" class="menu-link">
                    <i class="fa-solid fa-circle-question"></i>
                    <span>Liên hệ</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Overlay -->
    <div class="menu-overlay" id="menu-overlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid py-4">
            

            <?php if(isset($breadcrumbs)): ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($loop->last): ?>
                    <li class="breadcrumb-item active"><?php echo e($breadcrumb['title']); ?></li>
                    <?php else: ?>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e($breadcrumb['url']); ?>"><?php echo e($breadcrumb['title']); ?></a>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ol>
            </nav>
            <?php endif; ?>

            
            
            
            
            

            <?php echo $__env->yieldContent('content'); ?>


        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const leftMenu = document.getElementById('left-menu');
        const overlay = document.getElementById('menu-overlay');

        // Sửa: Thêm biến cho menu user
        const userMenu = document.getElementById('user-menu');
        const userToggle = document.getElementById('user-toggle');

        // Toggle sidebar
        menuToggle.addEventListener('click', () => {
            leftMenu.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            leftMenu.classList.remove('active');
            overlay.classList.remove('active');
            userMenu.classList.remove('open'); // Đóng user menu khi click overlay
        });

        // Sửa: Thêm sự kiện click cho user menu
        userToggle.addEventListener('click', () => {
            userMenu.classList.toggle('open');
        });

        // Sửa: Thêm sự kiện click ra ngoài để đóng user menu
        document.addEventListener('click', (e) => {
            if (!userMenu.contains(e.target) && userMenu.classList.contains('open')) {
                userMenu.classList.remove('open');
            }
        });

        // Toggle submenu
        document.querySelectorAll('.menu-parent').forEach(parent => {
            parent.addEventListener('click', () => {
                const item = parent.parentElement;
                document.querySelectorAll('.menu-item.has-child').forEach(otherItem => {
                    if (otherItem !== item) otherItem.classList.remove('open');
                });
                item.classList.toggle('open');
            });
        });

        // Active menu handling
        document.querySelectorAll('.menu-link, .menu-child li a').forEach(link => {
            link.addEventListener('click', (e) => {
                // Sửa: Xóa e.preventDefault();
                // e.preventDefault(); 

                document.querySelectorAll('.menu-link.active, .menu-child li a.active, .menu-parent.active')
                    .forEach(el => el.classList.remove('active'));

                link.classList.add('active');

                const parent = link.closest('.has-child');
                if (parent) {
                    const parentBtn = parent.querySelector('.menu-parent');
                    parentBtn.classList.add('active');
                    parent.classList.add('open');
                }

                localStorage.setItem('activeMenuHref', link.getAttribute('href'));
            });
        });
    </script>

    <?php echo $__env->yieldPushContent('styles'); ?>
</body>

</html><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/layouts/sinhvien.blade.php ENDPATH**/ ?>