@extends('layouts.admin')
@section('title', 'Chọn lớp cố vấn')
@section('page_title', 'Gán lớp cố vấn') {{-- Thêm page_title nếu layout của bạn sử dụng --}}

@push('styles')
<style>
    /* SAO CHÉP TOÀN BỘ CSS TỪ FILE MẪU CỦA BẠN */
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --primary-light: #818cf8;
        --success: #10b981;
        --success-dark: #059669; /* Thêm màu success đậm */
        --danger: #ef4444;
        --warning: #f59e0b;
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
        max-width: 900px;
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
        color: var(--primary); /* Hoặc màu success nếu muốn */
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
        /* Sử dụng màu success cho trang gán lớp */
        background: linear-gradient(135deg, var(--success) 0%, var(--success-dark) 100%);
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

     /* Form Group & Label */
     .form-group-modern {
        margin-bottom: 1.75rem; /* Giữ khoảng cách giữa các phần tử */
    }

    .form-label-modern {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 1rem; /* Tăng khoảng cách dưới label chính */
        font-size: 1rem; /* Làm label chính to hơn chút */
    }

    .form-label-modern i {
        color: var(--success); /* Đổi icon label sang màu xanh */
        font-size: 1.1rem;
    }

    /* Grid layout cho checkboxes */
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* 2-3 cột tùy màn hình */
        gap: 1rem 1.5rem; /* Khoảng cách giữa các checkbox */
        padding-left: 0.5rem; /* Căn lề nhẹ */
    }

    /* Styling cho từng checkbox item */
    .form-check-modern {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background-color: var(--gray-50);
        padding: 0.875rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--gray-200);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .form-check-modern:hover {
        background-color: var(--gray-100);
        border-color: var(--success-light); /* Màu success nhạt khi hover */
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .form-check-input-modern {
        width: 1.25em;
        height: 1.25em;
        margin-top: 0; /* Reset margin */
        cursor: pointer;
        accent-color: var(--success); /* Màu khi check */
        border-radius: 4px;
        border: 1px solid var(--gray-600);
    }

    .form-check-input-modern:checked {
        background-color: var(--success);
        border-color: var(--success);
    }

    .form-check-label-modern {
        margin-bottom: 0; /* Reset margin */
        font-weight: 500;
        color: var(--dark);
        cursor: pointer;
        flex-grow: 1; /* Cho label chiếm hết phần còn lại */
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

    /* Nút Lưu (Success) */
    .btn-success-modern {
        background: linear-gradient(135deg, var(--success), var(--success-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-success-modern:hover {
        background: linear-gradient(135deg, var(--success-dark), var(--success));
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
         /* Đổi màu info box sang xanh lá */
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(5, 150, 105, 0.1));
        border: 2px solid rgba(16, 185, 129, 0.2);
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
        /* Đổi màu icon info box */
        background: linear-gradient(135deg, var(--success), var(--success-dark));
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .form-info-box-title {
        font-weight: 700;
        color: var(--success-dark); /* Đổi màu tiêu đề info box */
        margin: 0;
    }

    .form-info-box-content {
        color: var(--gray-600);
        font-size: 0.875rem;
        line-height: 1.6;
        margin: 0;
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

        .checkbox-grid {
            grid-template-columns: 1fr; /* 1 cột trên mobile */
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
    <nav class="breadcrumb-modern">
        <a href="{{ route('admin.giangvien.index') }}">
            <i class="fa-solid fa-chalkboard-user"></i>
            Quản lý giảng viên
        </a>
        <span class="separator">/</span>
        <span class="active">Gán lớp cố vấn</span>
    </nav>

    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-header-content">
                <div class="form-card-title">
                    <div class="form-card-icon">
                        <i class="fa-solid fa-people-group"></i> {{-- Thay icon --}}
                    </div>
                    <div class="form-card-title-text">
                        <h1>Chọn lớp cố vấn</h1>
                        <p>Gán lớp cố vấn cho: {{ $giangvien->TenGV }}</p>
                    </div>
                </div>
                {{-- Cập nhật route cho nút quay lại --}}
                <a href="{{ route('admin.giangvien.index') }}" class="btn-back-modern">
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
                    <p style="margin: 0;">Vui lòng kiểm tra lại lựa chọn của bạn.</p>
                </div>
            </div>
            @endif

            <div class="form-info-box">
                <div class="form-info-box-header">
                    <div class="form-info-box-icon">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h6 class="form-info-box-title">Hướng dẫn</h6>
                </div>
                <div class="form-info-box-content">
                    Chọn các lớp mà giảng viên <strong>{{ $giangvien->TenGV }}</strong> sẽ làm cố vấn học tập. Bạn có thể chọn nhiều lớp.
                </div>
            </div>

            <form action="{{ route('admin.giangvien.assign.post', $giangvien->MaGV) }}" method="POST">
                @csrf

                {{-- Thêm label cho khu vực chọn lớp --}}
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fa-solid fa-list-check"></i>
                        Danh sách lớp học
                    </label>

                    {{-- Grid chứa checkboxes --}}
                    <div class="checkbox-grid">
                        @forelse($lops as $lop)
                        <label class="form-check-modern"> {{-- Bọc input và label trong label lớn để click được cả vùng --}}
                            <input class="form-check-input-modern"
                                   type="checkbox"
                                   name="lop[]"
                                   value="{{ $lop->MaLop }}"
                                   {{ $giangvien->lopPhuTrach->contains($lop->MaLop) ? 'checked' : '' }}>
                            <span class="form-check-label-modern">{{ $lop->TenLop }}</span>
                        </label>
                        @empty
                            <p class="text-muted fst-italic">Không có lớp học nào để gán.</p>
                        @endforelse
                    </div>
                    {{-- Hiển thị lỗi nếu có cho trường lop[] --}}
                     @error('lop')
                     <div class="error-message mt-3">
                         <i class="fa-solid fa-circle-xmark"></i> {{ $message }}
                     </div>
                     @enderror
                </div>


                <div class="form-actions">
                    {{-- Cập nhật route cho nút Hủy bỏ/Quay lại nếu cần --}}
                    <a href="{{ route('admin.giangvien.index') }}" class="btn-modern btn-secondary-modern">
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