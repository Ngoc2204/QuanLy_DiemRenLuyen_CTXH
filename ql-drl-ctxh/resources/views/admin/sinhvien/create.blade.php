@extends('layouts.admin')

@section('title', 'Thêm sinh viên')
@section('page_title', 'Thêm sinh viên mới')

@push('styles')
<style>
    
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

    .btn-primary-modern {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .btn-primary-modern:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        color: white;
    }

    .btn-primary-modern:active {
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
@endpush

@section('content')
<div class="page-wrapper">
    <!-- Breadcrumb -->
    <nav class="breadcrumb-modern">
        <a href="{{ route('admin.sinhvien.index') }}">
            <i class="fa-solid fa-graduation-cap"></i>
            Quản lý sinh viên
        </a>
        <span class="separator">/</span>
        <span class="active">Thêm sinh viên mới</span>
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
                        <h1>Thêm sinh viên mới</h1>
                        <p>Nhập thông tin chi tiết để tạo hồ sơ sinh viên mới</p>
                    </div>
                </div>
                <a href="{{ route('admin.sinhvien.index') }}" class="btn-back-modern">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="form-card-body">
            {{-- Alert/Validation Error --}}
            @if ($errors->any())
            <div class="alert-modern alert-danger-modern">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <strong>Lỗi nhập liệu!</strong>
                    <p style="margin: 0;">Vui lòng kiểm tra và sửa các thông tin lỗi bên dưới.</p>
                </div>
            </div>
            @endif

            <!-- Info Box -->
            <div class="form-info-box">
                <div class="form-info-box-header">
                    <div class="form-info-box-icon">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h6 class="form-info-box-title">Hướng dẫn</h6>
                </div>
                <div class="form-info-box-content">
                    <p>Lưu ý khi thêm sinh viên mới:</p>
                    <ul>
                        <li>**Mã sinh viên (MSSV)** là duy nhất và bắt buộc.</li>
                        <li>Ngày sinh phải được nhập chính xác.</li>
                        <li>Sinh viên cần được gán vào một **Lớp** và **Khoa** hiện có.</li>
                    </ul>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.sinhvien.store') }}" method="POST">
                @csrf

                {{-- Row 1: MSSV & HoTen (Full Width) --}}
                <div class="form-group-modern">
                    <label for="MSSV" class="form-label-modern">
                        <i class="fa-solid fa-id-badge"></i>
                        Mã sinh viên
                        <span class="required-mark">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fa-solid fa-barcode"></i>
                        <input type="text"
                            id="MSSV"
                            name="MSSV"
                            value="{{ old('MSSV') }}"
                            class="form-control-modern @error('MSSV') is-invalid @enderror"
                            placeholder="Ví dụ: 2001223456"
                            required
                            autofocus>
                        @error('MSSV')
                        <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                        @enderror
                    </div>
                    @error('MSSV')
                    <div class="error-message">
                        <i class="fa-solid fa-circle-xmark"></i>
                        {{ $message }}
                    </div>
                    @else
                    <div class="input-hint">
                        <i class="fa-solid fa-circle-info"></i>
                        Mã số sinh viên (MSSV) là trường duy nhất và không thể thay đổi.
                    </div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label for="HoTen" class="form-label-modern">
                        <i class="fa-solid fa-user"></i>
                        Họ và tên
                        <span class="required-mark">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fa-solid fa-pen-nib"></i>
                        <input type="text"
                            id="HoTen"
                            name="HoTen"
                            value="{{ old('HoTen') }}"
                            class="form-control-modern @error('HoTen') is-invalid @enderror"
                            placeholder="Nhập họ và tên đầy đủ của sinh viên"
                            required>
                        @error('HoTen')
                        <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                        @enderror
                    </div>
                    @error('HoTen')
                    <div class="error-message">
                        <i class="fa-solid fa-circle-xmark"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Row 2: NgaySinh & Email (2 Columns) --}}
                <div class="form-row-grid">

                    <div class="form-group-modern">
                        <label for="NgaySinh" class="form-label-modern">
                            <i class="fa-solid fa-calendar-days"></i>
                            Ngày sinh
                            <span class="required-mark">*</span>
                        </label>
                        <div class="input-wrapper">
                            {{-- Icon not needed for date input --}}
                            <input type="date"
                                id="NgaySinh"
                                name="NgaySinh"
                                value="{{ old('NgaySinh') }}"
                                class="form-control-modern @error('NgaySinh') is-invalid @enderror"
                                required>
                            @error('NgaySinh')
                            <i class="invalid-icon fa-solid fa-circle-exclamation" style="right: 0.75rem;"></i>
                            @enderror
                        </div>
                        @error('NgaySinh')
                        <div class="error-message">
                            <i class="fa-solid fa-circle-xmark"></i>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

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
                                value="{{ old('Email') }}"
                                class="form-control-modern @error('Email') is-invalid @enderror"
                                placeholder="Ví dụ: sinhvien@huit.edu.vn">
                            @error('Email')
                            <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                            @enderror
                        </div>
                        @error('Email')
                        <div class="error-message">
                            <i class="fa-solid fa-circle-xmark"></i>
                            {{ $message }}
                        </div>
                        @else
                        <div class="input-hint">
                            <i class="fa-solid fa-circle-info"></i>
                            Email liên lạc của sinh viên (không bắt buộc).
                        </div>
                        @enderror
                    </div>

                </div>

                {{-- Row: GioiTinh & SDT --}}
                <div class="form-row-grid">
                    <div class="form-group-modern">
                        <label for="GioiTinh" class="form-label-modern">
                            <i class="fa-solid fa-venus-mars"></i>
                            Giới tính
                            <span class="required-mark">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fa-solid fa-people-arrows"></i>
                            <select id="GioiTinh" name="GioiTinh" class="form-select-modern @error('GioiTinh') is-invalid @enderror" required>
                                <option value="">-- Chọn giới tính --</option>
                                <option value="Nam" {{ old('GioiTinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ old('GioiTinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                <option value="Khác" {{ old('GioiTinh') == 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('GioiTinh')
                            <i class="invalid-icon fa-solid fa-circle-exclamation" style="right: 3rem;"></i>
                            @enderror
                        </div>
                        @error('GioiTinh')
                        <div class="error-message"><i class="fa-solid fa-circle-xmark"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-modern">
                        <label for="SDT" class="form-label-modern">
                            <i class="fa-solid fa-phone"></i>
                            Số điện thoại
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fa-solid fa-phone-volume"></i>
                            <input type="text" id="SDT" name="SDT" value="{{ old('SDT') }}" class="form-control-modern @error('SDT') is-invalid @enderror" placeholder="Ví dụ: 0987654321">
                            @error('SDT')
                            <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                            @enderror
                        </div>
                        @error('SDT')
                        <div class="error-message"><i class="fa-solid fa-circle-xmark"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                {{-- Row 3: MaLop  --}}
                <div class="form-row-grid">

                    <div class="form-group-modern">
                        <label for="MaLop" class="form-label-modern">
                            <i class="fa-solid fa-people-group"></i>
                            Lớp
                            <span class="required-mark">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fa-solid fa-book-open"></i>
                            <select id="MaLop" name="MaLop" class="form-select-modern @error('MaLop') is-invalid @enderror" required>
                                <option value="" disabled {{ old('MaLop') ? '' : 'selected' }}>-- Chọn lớp học --</option>
                                @foreach($lops as $lop)
                                <option value="{{ $lop->MaLop }}" {{ old('MaLop') == $lop->MaLop ? 'selected' : '' }}>
                                    {{ $lop->TenLop }}
                                </option>
                                @endforeach
                            </select>
                            @error('MaLop')
                            <i class="invalid-icon fa-solid fa-circle-exclamation" style="right: 3rem;"></i>
                            @enderror
                        </div>
                        @error('MaLop')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                </div>

                {{-- Row: Năm nhập học & Sở thích --}}
                <div class="form-row-grid">
                    <div class="form-group-modern">
                        <label for="NamNhapHoc" class="form-label-modern">
                            <i class="fa-solid fa-calendar-days"></i>
                            Năm nhập học
                            <span class="required-mark">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fa-solid fa-graduation-cap"></i>
                            <input type="number" id="NamNhapHoc" name="NamNhapHoc"
                                value="{{ old('NamNhapHoc', date('Y')) }}"
                                min="1900" max="{{ date('Y') }}"
                                class="form-control-modern @error('NamNhapHoc') is-invalid @enderror"
                                placeholder="Ví dụ: 2022, 2023, 2024"
                                required>
                            @error('NamNhapHoc')
                            <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                            @enderror
                        </div>
                        @error('NamNhapHoc')
                        <div class="error-message"><i class="fa-solid fa-circle-xmark"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-modern">
                        <label for="SoThich" class="form-label-modern">
                            <i class="fa-solid fa-heart"></i>
                            Sở thích
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fa-solid fa-star"></i>
                            <input type="text" id="SoThich" name="SoThich"
                                value="{{ old('SoThich') }}"
                                class="form-control-modern @error('SoThich') is-invalid @enderror"
                                placeholder="Ví dụ: Đọc sách, nghe nhạc, đá bóng...">
                        </div>
                    </div>
                </div>



                <!-- Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.sinhvien.index') }}" class="btn-modern btn-secondary-modern">
                        <i class="fa-solid fa-xmark"></i>
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn-modern btn-primary-modern">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Lưu sinh viên
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection