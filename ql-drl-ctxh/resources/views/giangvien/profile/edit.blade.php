@extends('layouts.giangvien')

@section('title', 'Thông tin cá nhân')
@section('page_title', 'Thông tin cá nhân')

@section('content')
<style>
    /* Keep styles similar to nhân viên view for consistent look */
    .profile-container { max-width: 1200px; margin: 0 auto; }
    .avatar-preview { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; }
</style>

<div class="profile-container">
    <div class="row g-4">
        <div class="col-lg-8">
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
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <div class="card profile-card">
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('giangvien.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                                @csrf
                                @method('PUT')

                                <div class="avatar-section text-center mb-4">
                                    @php
                                        $avatarPath = null;
                                        if (!empty($user->Avatar)) {
                                            $avatarPath = asset('storage/' . $user->Avatar);
                                        }
                                    @endphp

                                    <img id="avatarPreview" src="{{ $avatarPath ?? 'https://ui-avatars.com/api/?name=' . urlencode(old('TenGV', optional($giangvien)->TenGV ?? $user->TenDangNhap)) . '&background=fff&color=4f46e5&size=150' }}" alt="Avatar" class="avatar-preview mb-3">

                                    <div>
                                        <label for="avatarInput" class="btn btn-outline-secondary">
                                            <i class="fa-solid fa-camera me-1"></i> Chọn ảnh đại diện
                                        </label>
                                        <input type="file" name="avatar" accept="image/*" class="d-none" id="avatarInput">
                                    </div>

                                    <small class="d-block mt-2 text-muted">Kích thước tối đa: 2MB. Định dạng: jpeg, png, gif, webp</small>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Họ và tên</label>
                                        <input type="text" name="TenGV" class="form-control" value="{{ old('TenGV', optional($giangvien)->TenGV ?? $user->TenDangNhap) }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Giới tính</label>
                                        <select name="GioiTinh" class="form-select">
                                            <option value="">-- Chọn giới tính --</option>
                                            <option value="Nam" {{ old('GioiTinh', optional($giangvien)->GioiTinh) == 'Nam' ? 'selected' : '' }}>Nam</option>
                                            <option value="Nữ" {{ old('GioiTinh', optional($giangvien)->GioiTinh) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                            <option value="Khác" {{ old('GioiTinh', optional($giangvien)->GioiTinh) == 'Khác' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="Email" class="form-control" value="{{ old('Email', optional($giangvien)->Email) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" name="SDT" class="form-control" value="{{ old('SDT', optional($giangvien)->SDT) }}">
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="password" role="tabpanel">
                    <div class="card profile-card">
                        <div class="card-body">
                            <form action="{{ route('giangvien.profile.update_password') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu hiện tại</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" required>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-warning">Đổi mật khẩu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Thông tin tài khoản</div>
                <div class="card-body">
                    <p><strong>Tên đăng nhập</strong><br><span style="font-size:1.1rem;">{{ $user->TenDangNhap }}</span></p>
                    <p><strong>Vai trò</strong><br><span class="badge bg-light text-dark">{{ $user->VaiTro }}</span></p>
                    <hr>
                    <small class="text-muted">ID: {{ $user->TenDangNhap }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    avatarPreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>

@endsection
