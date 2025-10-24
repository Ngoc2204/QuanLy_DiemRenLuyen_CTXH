@extends('layouts.admin')

@section('title', 'Thêm lớp')
@section('page_title', 'Thêm lớp mới')

@push('styles')
<style>
    /*
     * Custom styles for modern form elements
     * Copied from the 'Thêm khoa' file to ensure consistency
     */
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
        max-width: 800px;
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
        padding: 0.875rem 1rem;
        padding-left: 3rem; /* Add padding for the icon */
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        background: white;
        color: var(--dark);
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

    .form-control-modern.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }
    
    /* Special fix for select element to remove left padding when no icon is intended */
    .form-control-modern.form-select-modern {
        padding-left: 1rem;
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
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <!-- Breadcrumb -->
    <nav class="breadcrumb-modern">
        <a href="{{ route('admin.lop.index') }}">
            <i class="fa-solid fa-users-line"></i>
            Quản lý lớp
        </a>
        <span class="separator">/</span>
        <span class="active">Thêm lớp mới</span>
    </nav>

    <!-- Form Card -->
    <div class="form-card">
        <!-- Header -->
        <div class="form-card-header">
            <div class="form-card-header-content">
                <div class="form-card-title">
                    <div class="form-card-icon">
                        <i class="fa-solid fa-users-line"></i>
                    </div>
                    <div class="form-card-title-text">
                        <h1>Thêm lớp mới</h1>
                        <p>Nhập thông tin để tạo lớp học mới trong hệ thống</p>
                    </div>
                </div>
                <a href="{{ route('admin.lop.index') }}" class="btn-back-modern">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>

        <!-- Body -->
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

            <!-- Info Box -->
            <div class="form-info-box">
                <div class="form-info-box-header">
                    <div class="form-info-box-icon">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h6 class="form-info-box-title">Hướng dẫn</h6>
                </div>
                <div class="form-info-box-content">
                    <p>Lưu ý khi thêm lớp mới:</p>
                    <ul>
                        <li>Mã lớp phải là duy nhất và không được trùng lặp</li>
                        <li>Lớp học cần phải thuộc về một Khoa chủ quản</li>
                        <li>Các trường có dấu <span class="required-mark">*</span> là bắt buộc</li>
                    </ul>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.lop.store') }}" method="POST">
                @csrf
                
                <!-- Mã lớp -->
                <div class="form-group-modern">
                    <label for="MaLop" class="form-label-modern">
                        <i class="fa-solid fa-hashtag"></i>
                        Mã lớp
                        <span class="required-mark">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fa-solid fa-barcode"></i>
                        <input type="text" 
                               id="MaLop" 
                               name="MaLop" 
                               value="{{ old('MaLop') }}"
                               class="form-control-modern @error('MaLop') is-invalid @enderror" 
                               placeholder="Ví dụ: 13DHTH01, 13DHBM01..."
                               required
                               autofocus>
                        @error('MaLop')
                            <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                        @enderror
                    </div>
                    @error('MaLop')
                        <div class="error-message">
                            <i class="fa-solid fa-circle-xmark"></i>
                            {{ $message }}
                        </div>
                    @else
                        <div class="input-hint">
                            <i class="fa-solid fa-circle-info"></i>
                            Mã lớp nên ngắn gọn, viết hoa, và là duy nhất trong hệ thống
                        </div>
                    @enderror
                </div>

                <!-- Tên lớp -->
                <div class="form-group-modern">
                    <label for="TenLop" class="form-label-modern">
                        <i class="fa-solid fa-graduation-cap"></i>
                        Tên lớp
                        <span class="required-mark">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fa-solid fa-pen"></i>
                        <input type="text" 
                               id="TenLop" 
                               name="TenLop" 
                               value="{{ old('TenLop') }}"
                               class="form-control-modern @error('TenLop') is-invalid @enderror" 
                               placeholder="Ví dụ: 13 Đại học tin học 01"
                               required>
                        @error('TenLop')
                            <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                        @enderror
                    </div>
                    @error('TenLop')
                        <div class="error-message">
                            <i class="fa-solid fa-circle-xmark"></i>
                            {{ $message }}
                        </div>
                    @else
                        <div class="input-hint">
                            <i class="fa-solid fa-circle-info"></i>
                            Tên đầy đủ và rõ ràng của lớp học
                        </div>
                    @enderror
                </div>

                <!-- Khoa (Foreign Key) -->
                <div class="form-group-modern">
                    <label for="MaKhoa" class="form-label-modern">
                        <i class="fa-solid fa-building-columns"></i>
                        Khoa chủ quản
                        <span class="required-mark">*</span>
                    </label>
                    <div class="input-wrapper">
                        <!-- Select fields often don't display the icon cleanly, so we omit the input-icon class here -->
                        <select id="MaKhoa"
                                name="MaKhoa"
                                class="form-control-modern form-select-modern @error('MaKhoa') is-invalid @enderror"
                                required>
                            <option value="">-- Chọn khoa --</option>
                            <!-- Giả định biến $khoas chứa danh sách các khoa được truyền từ Controller -->
                            @if (isset($khoas))
                                @foreach($khoas as $khoa)
                                    <option value="{{ $khoa->MaKhoa }}" {{ old('MaKhoa') == $khoa->MaKhoa ? 'selected' : '' }}>
                                        {{ $khoa->TenKhoa }} ({{ $khoa->MaKhoa }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('MaKhoa')
                            <i class="invalid-icon fa-solid fa-circle-exclamation"></i>
                        @enderror
                    </div>
                    @error('MaKhoa')
                        <div class="error-message">
                            <i class="fa-solid fa-circle-xmark"></i>
                            {{ $message }}
                        </div>
                    @else
                        <div class="input-hint">
                            <i class="fa-solid fa-circle-info"></i>
                            Chọn khoa mà lớp học này trực thuộc
                        </div>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.lop.index') }}" class="btn-modern btn-secondary-modern">
                        <i class="fa-solid fa-xmark"></i>
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn-modern btn-primary-modern">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Lưu lớp
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection