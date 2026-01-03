@extends('layouts.admin')

@section('title', 'Chỉnh sửa sinh viên')
@section('page_title', 'Cập nhật thông tin sinh viên')

@push('styles')
<style>
    
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --primary-light: #818cf8;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b; /* Thêm màu warning */
        --warning-dark: #d97706;
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
        max-width: 900px; /* Rộng hơn một chút cho form nhiều cột */
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

    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray-200);
        overflow: hidden;
    }

    .form-card-header {
        /* Sử dụng màu warning cho trang chỉnh sửa */
        background: linear-gradient(135deg, var(--warning) 0%, var(--warning-dark) 100%);
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

    .form-card-body {
        padding: 2.5rem;
    }

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

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-600);
        font-size: 1rem;
        z-index: 1;
    }

    .form-control-modern {
        width: 100%;
        padding: 0.875rem 1rem; /* Bỏ padding-left: 3rem mặc định */
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        background: white;
        color: var(--dark);
    }
    
    /* Chỉ thêm padding khi có input-wrapper */
    .input-wrapper .form-control-modern {
        padding-left: 3rem; 
    }

    .form-control-modern:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .form-control-modern.is-invalid {
        border-color: var(--danger);
        padding-right: 3rem;
    }
    
    .input-wrapper .form-control-modern.is-invalid {
        padding-right: 3rem;
        padding-left: 3rem;
    }

    .form-control-modern.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }
    
    /* Thêm style cho readonly */
    .form-control-modern[readonly] {
        background-color: var(--gray-100);
        color: var(--gray-600);
        cursor: not-allowed;
    }
    .form-control-modern[readonly]:focus {
        border-color: var(--gray-200);
        box-shadow: none;
    }


    .invalid-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--danger);
        font-size: 1.125rem;
    }

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

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
        border-top: 2px solid var(--gray-100);
    }

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

    /* Đổi nút primary thành màu xanh (success) cho hành động "Lưu" */
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

    .form-info-box {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(217, 119, 6, 0.1));
        border: 2px solid rgba(245, 158, 11, 0.2);
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
        background: linear-gradient(135deg, var(--warning), var(--warning-dark));
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .form-info-box-title {
        font-weight: 700;
        color: var(--warning-dark);
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

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-modern {
            width: 100%;
            justify-content: center;
        }
        
        /* Stack các cột form trên mobile */
        .row > [class*="col-"] {
            padding-left: 0;
            padding-right: 0;
        }
        .row {
            margin-left: 0;
            margin-right: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <nav class="breadcrumb-modern">
        <a href="{{ route('admin.sinhvien.index') }}">
            <i class="fa-solid fa-users"></i>
            Quản lý sinh viên
        </a>
        <span class="separator">/</span>
        <span class="active">Cập nhật sinh viên</span>
    </nav>

    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-header-content">
                <div class="form-card-title">
                    <div class="form-card-icon">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div class="form-card-title-text">
                        <h1>Cập nhật sinh viên</h1>
                        <p>Điều chỉnh thông tin cho: {{ $sinhvien->HoTen }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.sinhvien.index') }}" class="btn-back-modern">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>

        <div class="form-card-body">
            @if ($errors->any())
                <div class="alert-modern alert-danger-modern">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        <strong>Có lỗi xảy ra!</strong>
                        <p style="margin: 0;">Vui lòng kiểm tra lại thông tin đã nhập.</p>
                    </div>
                </div>
            @endif

            <div class="form-info-box">
                <div class="form-info-box-header">
                    <div class="form-info-box-icon">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h6 class="form-info-box-title">Lưu ý khi cập nhật</h6>
                </div>
                <div class="form-info-box-content">
                    <ul>
                        <li>Trường <strong>Mã sinh viên</strong> và <strong>Khoa</strong> không thể thay đổi.</li>
                        <li>Các trường có dấu <span class="required-mark">*</span> là bắt buộc.</li>
                        <li>Hãy đảm bảo Email và SĐT là chính xác để liên lạc khi cần.</li>
                    </ul>
                </div>
            </div>

            <form action="{{ route('admin.sinhvien.update', $sinhvien->MSSV) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="MSSV" class="form-label-modern">
                                <i class="fa-solid fa-hashtag"></i>
                                Mã số sinh viên
                            </label>
                            <div class="input-wrapper">
                                <i class="input-icon fa-solid fa-barcode"></i>
                                <input type="text" 
                                       id="MSSV" 
                                       class="form-control-modern" 
                                       value="{{ $sinhvien->MSSV }}"
                                       readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="HoTen" class="form-label-modern">
                                <i class="fa-solid fa-user"></i>
                                Họ và tên
                                <span class="required-mark">*</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="input-icon fa-solid fa-pen"></i>
                                <input type="text" 
                                       id="HoTen" 
                                       name="HoTen" 
                                       value="{{ old('HoTen', $sinhvien->HoTen) }}"
                                       class="form-control-modern @error('HoTen') is-invalid @enderror" 
                                       placeholder="Nhập họ và tên..."
                                       required>
                                @error('HoTen')
                                    <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                                @enderror
                            </div>
                            @error('HoTen')
                                <div class="error-message">
                                    <i class="fa-solid fa-circle-xmark"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group-modern">
                            <label for="NgaySinh" class="form-label-modern">
                                <i class="fa-solid fa-cake-candles"></i>
                                Ngày sinh
                                <span class="required-mark">*</span>
                            </label>
                            <input type="date" 
                                   id="NgaySinh"
                                   name="NgaySinh"
                                   value="{{ old('NgaySinh', $sinhvien->NgaySinh) }}"
                                   class="form-control-modern @error('NgaySinh') is-invalid @enderror" 
                                   required>
                            @error('NgaySinh')
                                <div class="error-message">
                                    <i class="fa-solid fa-circle-xmark"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group-modern">
                            <label for="GioiTinh" class="form-label-modern">
                                <i class="fa-solid fa-venus-mars"></i>
                                Giới tính
                                <span class="required-mark">*</span>
                            </label>
                             <select name="GioiTinh" id="GioiTinh" class="form-control-modern @error('GioiTinh') is-invalid @enderror" required>
                                <option value="Nam" {{ old('GioiTinh', $sinhvien->GioiTinh) == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ old('GioiTinh', $sinhvien->GioiTinh) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                <option value="Khác" {{ old('GioiTinh', $sinhvien->GioiTinh) == 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('GioiTinh')
                                <div class="error-message">
                                    <i class="fa-solid fa-circle-xmark"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
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
                                       value="{{ old('Email', $sinhvien->Email) }}"
                                       class="form-control-modern @error('Email') is-invalid @enderror" 
                                       placeholder="vidu@email.com">
                                @error('Email')
                                    <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                                @enderror
                            </div>
                            @error('Email')
                                <div class="error-message">
                                    <i class="fa-solid fa-circle-xmark"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                         <div class="form-group-modern">
                            <label for="SDT" class="form-label-modern">
                                <i class="fa-solid fa-phone"></i>
                                Số điện thoại
                            </label>
                            <div class="input-wrapper">
                                <i class="input-icon fa-solid fa-mobile-screen"></i>
                                <input type="text" 
                                       id="SDT" 
                                       name="SDT" 
                                       value="{{ old('SDT', $sinhvien->SDT) }}"
                                       class="form-control-modern @error('SDT') is-invalid @enderror" 
                                       placeholder="Nhập số điện thoại...">
                                @error('SDT')
                                    <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                                @enderror
                            </div>
                            @error('SDT')
                                <div class="error-message">
                                    <i class="fa-solid fa-circle-xmark"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group-modern">
                            <label for="MaLop" class="form-label-modern">
                                <i class="fa-solid fa-users"></i>
                                Lớp
                                <span class="required-mark">*</span>
                            </label>
                            <select name="MaLop" id="MaLop" class="form-control-modern @error('MaLop') is-invalid @enderror" required>
                                <option value="">-- Chọn lớp --</option>
                                @foreach($lops as $lop)
                                    <option value="{{ $lop->MaLop }}" {{ old('MaLop', $sinhvien->MaLop) == $lop->MaLop ? 'selected' : '' }}>
                                        {{ $lop->TenLop }}
                                    </option>
                                @endforeach
                            </select>
                            @error('MaLop')
                                <div class="error-message">
                                    <i class="fa-solid fa-circle-xmark"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group-modern">
                            <label for="Khoa" class="form-label-modern">
                                <i class="fa-solid fa-school"></i>
                                Khoa
                            </label>
                            <div class="input-wrapper">
                                <i class="input-icon fa-solid fa-building-columns"></i>
                                <input type="text" 
                                       id="Khoa" 
                                       class="form-control-modern" 
                                       value="{{ $sinhvien->lop->khoa->TenKhoa ?? 'Vui lòng chọn lớp' }}"
                                       readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group-modern">
                            <label for="NamNhapHoc" class="form-label-modern">
                                <i class="fa-solid fa-calendar-days"></i>
                                Năm nhập học
                                <span class="required-mark">*</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="input-icon fa-solid fa-graduation-cap"></i>
                                <input type="number" 
                                       id="NamNhapHoc"
                                       name="NamNhapHoc"
                                       value="{{ old('NamNhapHoc', $sinhvien->NamNhapHoc) }}"
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
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.sinhvien.index') }}" class="btn-modern btn-secondary-modern">
                        <i class="fa-solid fa-xmark"></i>
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn-modern btn-success-modern">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection