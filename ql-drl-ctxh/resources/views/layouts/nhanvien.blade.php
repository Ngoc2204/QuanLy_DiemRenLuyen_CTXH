<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hệ thống Nghiệp vụ - Nhân viên')</title>

    {{-- Link CSS Bootstrap (từ CDN hoặc local đều được) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"> --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="..." crossorigin="anonymous">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* === Variables === */
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #60a5fa;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --dark: #1e293b;
            --darker: #0f172a;
            --light: #f8fafc;
            --border: #e2e8f0;
            --sidebar-width: 280px;
            --navbar-height: 70px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--light);
            color: #334155;
            line-height: 1.6;
        }

        /* === Sidebar === */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--dark) 0%, var(--darker) 100%);
            color: #fff;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        /* Logo Section - Fixed */
        .sidebar .logo-section {
            position: sticky;
            top: 0;
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            background: linear-gradient(180deg, var(--dark) 0%, var(--darker) 100%);
            z-index: 10;
        }

        .sidebar .logo-section img {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            margin-bottom: 12px;
            background: rgba(255, 255, 255, 0.05);
            padding: 8px;
        }

        .sidebar .logo-section h5 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            line-height: 1.4;
            color: #fff;
        }

        /* Navigation - Scrollable */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 20px 12px;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.4);
            padding: 16px 12px 8px;
            margin-top: 8px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin: 4px 0;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
            position: relative;
        }

        .sidebar-nav a i {
            width: 20px;
            margin-right: 12px;
            font-size: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .sidebar-nav a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: #fff;
            border-radius: 0 4px 4px 0;
        }

        .sidebar-nav a.text-danger:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
        }

        /* === Navbar === */
        .navbar-custom {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--navbar-height);
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            z-index: 999;
            box-shadow: var(--shadow-sm);
        }

        .navbar-custom h5 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: var(--light);
            border-radius: 10px;
            border: 1px solid var(--border);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
        }

        .user-role {
            font-size: 12px;
            color: var(--secondary);
        }

        /* === Main Content === */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: calc(var(--navbar-height) + 32px) 32px 32px;
            min-height: 100vh;
        }

        /* Breadcrumb */
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .breadcrumb-custom .breadcrumb-item+.breadcrumb-item::before {
            content: "›";
            color: var(--secondary);
        }

        .breadcrumb-custom a {
            color: var(--secondary);
            text-decoration: none;
        }

        .breadcrumb-custom a:hover {
            color: var(--primary);
        }

        .breadcrumb-custom .active {
            color: var(--dark);
            font-weight: 500;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-size: 14px;
            box-shadow: var(--shadow);
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }

        .alert-info {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            color: #075985;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 20px 24px;
            font-weight: 600;
            font-size: 16px;
            color: var(--dark);
        }

        .card-body {
            padding: 24px;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }

        /* Footer */
        footer {
            text-align: center;
            color: var(--secondary);
            font-size: 14px;
            margin-top: 60px;
            padding: 24px 0;
            border-top: 1px solid var(--border);
        }

        /* === Responsive === */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 0;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .navbar-custom {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding: calc(var(--navbar-height) + 20px) 16px 20px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-content>* {
            animation: fadeIn 0.3s ease;
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-section">
            <img src="{{ asset('images/logo_huit.png') }}" alt="HUIT Logo">
            <h5>Hệ thống Quản lý<br>DRL & CTXH</h5>
        </div>

        <!-- NEW: Sidebar Navigation cho Nhân viên -->
        <nav class="sidebar-nav">
            <div class="nav-section-title">Menu chính</div>

            <a href="{{ route('nhanvien.home') }}" class="{{ request()->routeIs('nhanvien.home') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Bảng điều khiển</span>
            </a>

            <div class="nav-section-title">Nghiệp vụ</div>

            <a href="{{ route('nhanvien.thongbao.index') }}" class="{{ request()->routeIs('nhanvien.thongbao*') ? 'active' : '' }}">
                <i class="fa-solid fa-bullhorn"></i>
                <span>Quản lý Thông báo</span>
            </a>

            <a href="{{ route('nhanvien.hoatdong_ctxh.index') }}" class="{{ request()->routeIs('nhanvien.hoatdong_ctxh*') ? 'active' : '' }}"> {{-- Sửa tên route ở đây --}}
                <i class="fa-solid fa-hand-holding-heart"></i>
                <span>Hoạt động CTXH</span>
            </a>

            <a href="{{ route('nhanvien.hoatdong_drl.index') }}" class="{{ request()->routeIs('nhanvien.hoatdong_drl*') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>Hoạt động DRL</span>
            </a>

            <a href="{{ route('nhanvien.duyet_dang_ky.index') }}" class="{{ request()->routeIs('nhanvien.duyetsinhvien*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-check"></i>
                <span>Duyệt đăng ký SV</span>
            </a>

            <a href="{{ route('nhanvien.dieuchinh_drl.index') }}" class="{{ request()->routeIs('nhanvien.dieuchinh*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-pen"></i>
                <span>Điều chỉnh điểm rèn luyện</span>
            </a>

            <div class="nav-section-title">Quản lý thanh toán</div>

            <a href="{{ route('nhanvien.thanhtoan.index') }}"
                class="{{ request()->routeIs('nhanvien.thanhtoan.index*') ? 'active' : '' }}">

                <i class="fa-solid fa-file-invoice-dollar"></i> {{-- <-- Đổi icon --}}

                <span>Quản lý thanh toán CTXH</span> {{-- (Tên này chính xác hơn) --}}
            </a>

            <div class="nav-section-title">Báo cáo & thống kê</div>

            <a href="{{ route('nhanvien.thongke.index') }}" class="{{ request()->routeIs('nhanvien.thongke.index*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-area"></i>
                <span>Báo cáo & Thống kê</span>
            </a>


            <div class="nav-section-title">Tài khoản</div>

            <a href="{{ route('nhanvien.profile.edit') }}" class="{{ request()->routeIs('nhanvien.profile*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear"></i>
                <span>Thông tin cá nhân</span>
            </a>

            <a href="{{ route('logout') }}" class="text-danger"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Đăng xuất</span>
            </a>
        </nav>
    </div>

    <!-- Navbar -->
    <div class="navbar-custom">
        <h5>@yield('page_title', 'Trang nghiệp vụ')</h5>
        <div class="navbar-right">
            <div class="user-info">
                @php
                    $avatar = optional(Auth::user())->Avatar ?? null;
                    $avatarUrl = null;
                    if ($avatar && file_exists(storage_path('app/public/' . $avatar))) {
                        $avatarUrl = asset('storage/' . $avatar);
                    }
                @endphp
                <div class="user-avatar">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="avatar" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                    @else
                        {{ strtoupper(substr(Auth::user()->TenDangNhap ?? 'N', 0, 1)) }}
                    @endif
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->TenDangNhap ?? 'Nhân viên' }}</div>
                    <div class="user-role">Nhân viên</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid py-4">
            {{-- Include partial for alerts --}}

            @if(isset($breadcrumbs))
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    @foreach($breadcrumbs as $breadcrumb)
                    @if($loop->last)
                    <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                    @else
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                    </li>
                    @endif
                    @endforeach
                </ol>
            </nav>
            @endif

            {{-- Remove specific alert sections if using the partial --}}
            {{-- @if(session('success')) ... @endif --}}
            {{-- @if(session('error')) ... @endif --}}
            {{-- @if(session('warning')) ... @endif --}}
            {{-- @if(session('info')) ... @endif --}}

            @yield('content')

            <footer>
                <div>© {{ date('Y') }} - Hệ thống Quản lý DRL & CTXH</div>
                <div style="font-size: 12px; margin-top: 4px; color: #94a3b8;">
                    Trường Đại Học Công Thương TP.HCM
                </div>
            </footer>
        </div>
    </main>


    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Scripts -->
    {{-- Đảm bảo chỉ có MỘT dòng bootstrap.bundle.min.js và nó nằm TRƯỚC @stack --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    {{-- <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script> --}}

    <script>
        // Auto-hide alerts after 5 seconds using Bootstrap's Alert component
        document.addEventListener('DOMContentLoaded', function() {
            const alertList = document.querySelectorAll('.alert:not(.alert-dismissible)'); // Find alerts without close button initially
            alertList.forEach(function(alert) {
                // Add dismissible functionality dynamically if needed, or rely on timeout
                // For timeout dismissal:
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert); // Create Bootstrap Alert instance
                    bsAlert.close(); // Use Bootstrap's close method
                }, 5000); // 5 seconds
            });

            // Handle alerts that are already dismissible
            const dismissibleAlertList = document.querySelectorAll('.alert.alert-dismissible');
            dismissibleAlertList.forEach(function(alert) {
                setTimeout(() => {
                    const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });

        // Mobile sidebar toggle (if needed)
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }
    </script>

    @stack('scripts')
</body>

</html>