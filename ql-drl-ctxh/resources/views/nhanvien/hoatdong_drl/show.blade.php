@extends('layouts.nhanvien')

{{-- Đổi Title --}}
@section('title', 'Chi tiết Hoạt động DRL')
@section('page_title', 'Chi tiết Hoạt động DRL')

@php
    // Đổi route breadcrumb
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.hoatdong_drl.index'), 'title' => 'Hoạt động DRL'],
        ['url' => '#', 'title' => 'Chi tiết'],
    ];

    // Tính toán trạng thái và tỷ lệ (giữ nguyên logic, đổi biến)
    $now = now();
    $dangDienRa = $hoatdong_drl->ThoiGianBatDau <= $now && $hoatdong_drl->ThoiGianKetThuc >= $now;
    $daKetThuc = $hoatdong_drl->ThoiGianKetThuc < $now;
    $chuaBatDau = $hoatdong_drl->ThoiGianBatDau > $now;
    $tyLeDangKy = $hoatdong_drl->SoLuong > 0 ? round(($hoatdong_drl->sinhVienDangKy->count() / $hoatdong_drl->SoLuong) * 100) : 0;
@endphp

@section('content')
{{-- Header Card --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-gradient text-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">
                    {{-- Đổi Icon và biến --}}
                    <i class="fa-solid fa-clipboard-check me-2"></i>
                    {{ $hoatdong_drl->TenHoatDong }}
                </h5>
                <small class="opacity-75">Mã: {{ $hoatdong_drl->MaHoatDong }}</small>
            </div>
            <div>
                {{-- Trạng thái (giữ nguyên logic) --}}
                @if($dangDienRa)
                    <span class="badge bg-success px-3 py-2">
                        <i class="fa-solid fa-circle-play me-1"></i>Đang diễn ra
                    </span>
                @elseif($daKetThuc)
                    <span class="badge bg-secondary px-3 py-2">
                        <i class="fa-solid fa-circle-check me-1"></i>Đã kết thúc
                    </span>
                @else
                    <span class="badge bg-info px-3 py-2">
                        <i class="fa-solid fa-clock me-1"></i>Sắp diễn ra
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Left Column - Thông tin chi tiết --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fa-solid fa-circle-info me-2 text-primary"></i>
                    Thông tin chi tiết
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fa-solid fa-tag me-2 text-muted"></i>
                                Loại Hoạt động
                            </label>
                            <p class="info-value">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                    {{ $hoatdong_drl->LoaiHoatDong }} {{-- Đổi biến --}}
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- Thêm Học Kỳ --}}
                    <div class="col-md-12">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fa-solid fa-graduation-cap me-2 text-muted"></i>
                                Học Kỳ áp dụng
                            </label>
                            <p class="info-value">
                                 <span class="badge bg-info-subtle text-info px-3 py-2">
                                    {{ $hoatdong_drl->hocKy->TenHocKy ?? 'N/A' }} ({{ $hoatdong_drl->MaHocKy }}) {{-- Đổi biến --}}
                                 </span>
                            </p>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fa-solid fa-align-left me-2 text-muted"></i>
                                Mô tả
                            </label>
                            <p class="info-value text-muted">
                                {{ $hoatdong_drl->MoTa ?: 'Không có mô tả.' }} {{-- Đổi biến --}}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fa-solid fa-calendar-plus me-2 text-success"></i>
                                Thời gian Bắt đầu
                            </label>
                            <p class="info-value">
                                {{ $hoatdong_drl->ThoiGianBatDau->format('d/m/Y H:i') }} {{-- Đổi biến --}}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fa-solid fa-calendar-xmark me-2 text-danger"></i>
                                Thời gian Kết thúc
                            </label>
                            <p class="info-value">
                                {{ $hoatdong_drl->ThoiGianKetThuc->format('d/m/Y H:i') }} {{-- Đổi biến --}}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i>
                                Thời hạn Hủy đăng ký
                            </label>
                            <p class="info-value">
                                {{ $hoatdong_drl->ThoiHanHuy ? $hoatdong_drl->ThoiHanHuy->format('d/m/Y H:i') : 'Không cho phép' }} {{-- Đổi biến --}}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fa-solid fa-location-dot me-2 text-danger"></i>
                                Địa điểm
                            </label>
                            <p class="info-value">
                                {{ $hoatdong_drl->DiaDiem ?: 'Không xác định' }} {{-- Đổi biến --}}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fa-solid fa-clipboard-list me-2 text-info"></i>
                                Quy định điểm liên quan (Điểm RL) {{-- Thêm (Điểm RL) --}}
                            </label>
                            <p class="info-value">
                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                    {{-- Đổi biến và quan hệ --}}
                                    {{ $hoatdong_drl->quyDinhDiem->MaDiem ?? 'N/A' }} -
                                    {{ $hoatdong_drl->quyDinhDiem->TenCongViec ?? 'Không rõ' }}
                                    ({{ $hoatdong_drl->quyDinhDiem->DiemNhan ?? 'N/A' }} điểm)
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Danh sách sinh viên đăng ký --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fa-solid fa-users me-2 text-primary"></i>
                    Danh sách Sinh viên Đăng ký
                    {{-- Đổi biến --}}
                    <span class="badge bg-primary ms-2">{{ $hoatdong_drl->sinhVienDangKy->count() }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                {{-- Đổi biến --}}
                @if($hoatdong_drl->sinhVienDangKy->count() > 0)
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="text-center" style="width: 5%;">#</th>
                                    <th style="width: 15%;">MSSV</th>
                                    <th>Họ Tên</th>
                                    <th class="text-center" style="width: 20%;">Ngày ĐK</th>
                                    <th class="text-center" style="width: 15%;">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Đổi biến --}}
                                @foreach($hoatdong_drl->sinhVienDangKy as $index => $sv)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $sv->MSSV }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fa-solid fa-user text-primary small"></i>
                                            </div>
                                            {{ $sv->HoTen }}
                                        </div>
                                    </td>
                                    <td class="text-center small">
                                        {{ $sv->pivot->NgayDangKy ? \Carbon\Carbon::parse($sv->pivot->NgayDangKy)->format('d/m/Y H:i') : ''}}
                                    </td>
                                    <td class="text-center">
                                        @if($sv->pivot->TrangThaiDangKy == 'Chờ duyệt')
                                            <span class="badge bg-warning">
                                                <i class="fa-solid fa-hourglass-half me-1"></i>{{ $sv->pivot->TrangThaiDangKy }}
                                            </span>
                                        @elseif($sv->pivot->TrangThaiDangKy == 'Đã duyệt')
                                            <span class="badge bg-success">
                                                <i class="fa-solid fa-check-circle me-1"></i>{{ $sv->pivot->TrangThaiDangKy }}
                                            </span>
                                        @elseif($sv->pivot->TrangThaiDangKy == 'Đã hủy')
                                            <span class="badge bg-secondary">
                                                <i class="fa-solid fa-ban me-1"></i>{{ $sv->pivot->TrangThaiDangKy }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fa-solid fa-exclamation-circle me-1"></i>{{ $sv->pivot->TrangThaiDangKy ?: 'Không rõ' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-user-slash fa-3x mb-3 d-block opacity-25"></i>
                        <p class="mb-0">Chưa có sinh viên nào đăng ký hoạt động này.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right Column - Thống kê --}}
    <div class="col-lg-4">
        {{-- Statistics Cards --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fa-solid fa-chart-pie me-2 text-primary"></i>
                    Thống kê
                </h6>
            </div>
            <div class="card-body">
                {{-- Bỏ thẻ Điểm CTXH --}}
                {{-- <div class="stat-item mb-4"> ... </div> --}}

                <div class="stat-item mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <small class="text-muted d-block">Số lượng</small>
                            <h4 class="mb-0 text-primary">
                                {{-- Đổi biến --}}
                                {{ $hoatdong_drl->sinhVienDangKy->count() }}/{{ $hoatdong_drl->SoLuong }}
                            </h4>
                        </div>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar {{ $tyLeDangKy >= 80 ? 'bg-danger' : ($tyLeDangKy >= 50 ? 'bg-warning' : 'bg-success') }}"
                             role="progressbar"
                             style="width: {{ $tyLeDangKy }}%;"
                             aria-valuenow="{{ $tyLeDangKy }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted mt-1 d-block">Đã lấp đầy {{ $tyLeDangKy }}%</small>
                </div>

                <div class="stat-item mb-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fa-solid fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted d-block">Đã duyệt</small>
                            <h5 class="mb-0 text-success">
                                {{-- Đổi biến --}}
                                {{ $hoatdong_drl->sinhVienDangKy->where('pivot.TrangThaiDangKy', 'Đã duyệt')->count() }}
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="stat-item mb-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted d-block">Chờ duyệt</small>
                            <h5 class="mb-0 text-warning">
                                {{-- Đổi biến --}}
                                {{ $hoatdong_drl->sinhVienDangKy->where('pivot.TrangThaiDangKy', 'Chờ duyệt')->count() }}
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="stat-item">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted d-block">Đã hủy</small>
                            <h5 class="mb-0 text-secondary">
                                {{-- Đổi biến --}}
                                {{ $hoatdong_drl->sinhVienDangKy->where('pivot.TrangThaiDangKy', 'Đã hủy')->count() }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="card shadow-sm border-0">
            <div class="card-body d-grid gap-2">
                {{-- Đổi route và biến --}}
                <a href="{{ route('nhanvien.hoatdong_drl.edit', $hoatdong_drl->MaHoatDong) }}"
                   class="btn btn-warning">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Chỉnh sửa
                </a>
                {{-- Đổi route --}}
                <a href="{{ route('nhanvien.hoatdong_drl.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Giữ nguyên CSS --}}
<style>
.info-item { padding: 1rem 0; border-bottom: 1px solid #f0f0f0; }
.info-item:last-child { border-bottom: none; }
.info-label { font-weight: 600; color: #6c757d; font-size: 0.875rem; margin-bottom: 0.5rem; display: block; }
.info-value { margin: 0; color: #212529; font-size: 0.95rem; }
.stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
.stat-item { padding-bottom: 1rem; border-bottom: 1px solid #f0f0f0; }
.stat-item:last-child { border-bottom: none; padding-bottom: 0; }
.avatar-xs { width: 32px; height: 32px; }
.card { border-radius: 12px; overflow: hidden; }
.badge { padding: 0.5em 0.75em; font-weight: 500; border-radius: 6px; }
.progress { border-radius: 10px; background-color: #e9ecef; }
.progress-bar { border-radius: 10px; }
.table > :not(caption) > * > * { padding: 0.75rem; vertical-align: middle;}
.sticky-top { position: sticky; top: 0; z-index: 10; }
.btn { border-radius: 8px; padding: 0.5rem 1rem; font-weight: 500; transition: all 0.3s ease; }
.btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.shadow-sm { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important; }
.bg-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.table-hover tbody tr:hover { background-color: rgba(102, 126, 234, 0.05); }
</style>
@endsection