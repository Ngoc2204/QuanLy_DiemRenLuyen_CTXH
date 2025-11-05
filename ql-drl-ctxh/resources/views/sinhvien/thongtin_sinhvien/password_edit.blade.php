@extends('layouts.sinhvien')
@section('title', 'Đổi mật khẩu')

@section('content')
<div class="container-fluid" style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        {{-- Header --}}
        <div class="card-header bg-white text-center py-4 border-0">
            <div class="mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#667eea" class="bi bi-shield-lock" viewBox="0 0 16 16">
                    <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                    <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415z"/>
                </svg>
            </div>
            <h3 class="mb-1" style="color: #333; font-weight: 600;">Đổi mật khẩu</h3>
            <p class="text-muted mb-0 small">Cập nhật mật khẩu của bạn</p>
        </div>

        <div class="card-body p-4">
            {{-- Hiển thị thông báo thành công --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #28a745;">
                    <strong>Thành công!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Hiển thị lỗi chung hoặc lỗi mật khẩu hiện tại --}}
            @if ($errors->has('current_password'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #dc3545;">
                    <strong>Lỗi!</strong> {{ $errors->first('current_password') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('sinhvien.thongtin_sinhvien.password_edit') }}" method="POST">
                @csrf

                {{-- Mật khẩu hiện tại --}}
                <div class="mb-4">
                    <label for="current_password" class="form-label" style="font-weight: 500; color: #555;">
                        Mật khẩu hiện tại
                    </label>
                    <input type="password" 
                           class="form-control @error('current_password') is-invalid @enderror" 
                           id="current_password" 
                           name="current_password"
                           placeholder="Nhập mật khẩu hiện tại"
                           style="padding: 12px; border-radius: 10px; border: 1px solid #ddd;"
                           required>
                </div>

                {{-- Mật khẩu mới --}}
                <div class="mb-4">
                    <label for="new_password" class="form-label" style="font-weight: 500; color: #555;">
                        Mật khẩu mới
                    </label>
                    <input type="password" 
                           class="form-control @error('new_password') is-invalid @enderror" 
                           id="new_password" 
                           name="new_password"
                           placeholder="Nhập mật khẩu mới"
                           style="padding: 12px; border-radius: 10px; border: 1px solid #ddd;"
                           required>
                    @error('new_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="text-muted">Mật khẩu phải có ít nhất 8 ký tự</small>
                </div>

                {{-- Xác nhận mật khẩu mới --}}
                <div class="mb-4">
                    <label for="new_password_confirmation" class="form-label" style="font-weight: 500; color: #555;">
                        Xác nhận mật khẩu mới
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="new_password_confirmation" 
                           name="new_password_confirmation"
                           placeholder="Nhập lại mật khẩu mới"
                           style="padding: 12px; border-radius: 10px; border: 1px solid #ddd;"
                           required>
                </div>

                <div class="d-grid">
                    <button type="submit" 
                            class="btn btn-primary btn-lg" 
                            style="background-color: #667eea; border: none; border-radius: 10px; padding: 12px; font-weight: 500;">
                        Cập nhật mật khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }
    
    .btn-primary:hover {
        background-color: #5568d3 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        transition: all 0.2s ease;
    }

    .card {
        transition: all 0.3s ease;
    }
</style>
@endsection