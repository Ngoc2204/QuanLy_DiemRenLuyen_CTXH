@extends('layouts.nhanvien')

@section('title', 'Quản lý Hoạt động CTXH')
@section('page_title', 'Danh sách Hoạt động CTXH')

@php
// Breadcrumbs
$breadcrumbs = [
['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
['url' => route('nhanvien.hoatdong_ctxh.index'), 'title' => 'Hoạt động CTXH'],
];
@endphp

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-gradient d-flex justify-content-between align-items-center py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="mb-0 text-white">
            <i class="fa-solid fa-hand-holding-heart me-2"></i>
            Danh sách Hoạt động Cộng đồng
        </h5>
        <a href="{{ route('nhanvien.hoatdong_ctxh.create') }}" class="btn btn-light btn-sm shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> Thêm mới
        </a>
    </div>
    <div class="card-body p-4">
        {{-- Success/Error Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Search Form --}}
        <form method="GET" action="{{ route('nhanvien.hoatdong_ctxh.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-10">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fa-solid fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                            placeholder="Tìm kiếm theo tên hoạt động..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-fill shadow-sm" type="submit">
                            <i class="fa-solid fa-search me-1"></i> Tìm
                        </button>
                        <a href="{{ route('nhanvien.hoatdong_ctxh.index') }}"
                            class="btn btn-outline-secondary shadow-sm"
                            title="Làm mới">
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>

        {{-- Statistics Cards (Giữ nguyên) --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-calendar-check fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Tổng hoạt động</h6>
                                <h4 class="mb-0 text-primary">{{ $hoatDongs->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-users fa-2x text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Tổng đăng ký</h6>
                                <h4 class="mb-0 text-success">{{ $hoatDongs->sum('dang_ky_count') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-chart-line fa-2x text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Tỷ lệ lấp đầy</h6>
                                <h4 class="mb-0 text-info">
                                    {{ $hoatDongs->sum('SoLuong') > 0 ? round(($hoatDongs->sum('dang_ky_count') / $hoatDongs->sum('SoLuong')) * 100, 1) : 0 }}%
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center" style="width: 5%;">#</th>
                        <th scope="col">Thông tin hoạt động</th>
                        {{-- --- THÊM CỘT NÀY --- --}}
                        <th scope="col" style="width: 15%;">Đợt / Địa điểm</th>
                        <th scope="col" class="text-center" style="width: 15%;">Thời gian</th>
                        <th scope="col" class="text-center" style="width: 12%;">Số lượng</th>
                        <th scope="col" class="text-center" style="width: 10%;">Trạng thái</th>
                        <th scope="col" class="text-center" style="width: 15%;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hoatDongs as $index => $hd)
                    @php
                    $now = now();
                    $dangDienRa = $hd->ThoiGianBatDau <= $now && $hd->ThoiGianKetThuc >= $now;
                        $daKetThuc = $hd->ThoiGianKetThuc < $now;
                            $chuaBatDau=$hd->ThoiGianBatDau > $now;
                            $tyLe = $hd->SoLuong > 0 ? round(($hd->dang_ky_count / $hd->SoLuong) * 100) : 0;
                            @endphp
                            <tr>
                                <td class="text-center fw-bold">{{ $hoatDongs->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                            {{-- --- SỬA ICON DỰA TRÊN LOẠI HOẠT ĐỘNG --- --}}
                                            @if($hd->LoaiHoatDong == 'Địa chỉ đỏ')
                                            <i class="fa-solid fa-map-location-dot text-primary"></i>
                                            @else
                                            <i class="fa-solid fa-hand-holding-heart text-primary"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('nhanvien.hoatdong_ctxh.show', $hd->MaHoatDong) }}"
                                                class="text-decoration-none fw-semibold text-dark">
                                                {{ $hd->TenHoatDong }}
                                            </a>
                                            <div class="small text-muted mt-1">
                                                {{-- --- SỬA HIỂN THỊ LOẠI --- --}}
                                                <i class="fa-solid fa-tag me-1"></i>
                                                {{ $hd->LoaiHoatDong }} ({{ $hd->quydinh->TenCongViec ?? 'N/A' }})
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- --- THÊM KHỐI TD NÀY --- --}}
                                <td>
                                    <div class="small">
                                        <div class="fw-semibold text-dark mb-1">
                                            <i class="fa-solid fa-calendar-days me-1 text-primary"></i>
                                            @if($hd->LoaiHoatDong == 'Địa chỉ đỏ')
                                            {{ $hd->dotDiaChiDo->TenDot ?? 'N/A' }}
                                            @else
                                            <span class="text-muted fst-italic">Không áp dụng</span>
                                            @endif
                                        </div>
                                        <div class="text-muted">
                                            <i class="fa-solid fa-location-dot me-1 text-danger"></i>
                                            @if($hd->LoaiHoatDong == 'Địa chỉ đỏ')
                                            {{ $hd->diaDiem->TenDiaDiem ?? 'N/A' }}
                                            @else
                                            {{ $hd->DiaDiem }} {{-- Hiển thị địa điểm cụ thể --}}
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="small">
                                        <div class="text-success mb-1">
                                            <i class="fa-solid fa-play me-1"></i>
                                            {{ $hd->ThoiGianBatDau->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="text-danger">
                                            <i class="fa-solid fa-stop me-1"></i>
                                            {{ $hd->ThoiGianKetThuc->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="mb-1">
                                        <span class="badge bg-primary">{{ $hd->dang_ky_count }}/{{ $hd->SoLuong }}</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar {{ $tyLe >= 80 ? 'bg-danger' : ($tyLe >= 50 ? 'bg-warning' : 'bg-success') }}"
                                            role="progressbar"
                                            style="width: {{ $tyLe }}%;"
                                            aria-valuenow="{{ $tyLe }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $tyLe }}%</small>
                                </td>
                                <td class="text-center">
                                    @if($dangDienRa)
                                    <span class="badge bg-success">
                                        <i class="fa-solid fa-circle-play me-1"></i>Đang diễn ra
                                    </span>
                                    @elseif($daKetThuc)
                                    <span class="badge bg-secondary">
                                        <i class="fa-solid fa-circle-check me-1"></i>Đã kết thúc
                                    </span>
                                    @else
                                    <span class="badge bg-info">
                                        <i class="fa-solid fa-clock me-1"></i>Sắp diễn ra
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('nhanvien.hoatdong_ctxh.show', $hd->MaHoatDong) }}"
                                            class="btn btn-sm btn-info text-white"
                                            title="Xem chi tiết">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('nhanvien.hoatdong_ctxh.edit', $hd->MaHoatDong) }}"
                                            class="btn btn-sm btn-warning text-white"
                                            title="Chỉnh sửa">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $hd->MaHoatDong }}"
                                            title="Xóa">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $hd->MaHoatDong }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $hd->MaHoatDong }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $hd->MaHoatDong }}">
                                                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                                        Xác nhận xóa
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start p-4">
                                                    <p class="mb-3">Bạn có chắc chắn muốn xóa hoạt động <strong class="text-danger">{{ $hd->TenHoatDong }}</strong> không?</p>
                                                    <div class="alert alert-warning mb-0">
                                                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                                        <strong>Cảnh báo:</strong> Hành động này không thể hoàn tác.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fa-solid fa-xmark me-1"></i>Hủy
                                                    </button>
                                                    <form action="{{ route('nhanvien.hoatdong_ctxh.destroy', $hd->MaHoatDong) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fa-solid fa-trash-can me-1"></i>Xóa
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                {{-- Sửa colspan="7" --}}
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa-solid fa-inbox fa-3x mb-3 d-block"></i>
                                        <h5>Không có hoạt động nào</h5>
                                        <p class="mb-0">Hãy thêm hoạt động mới để bắt đầu</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($hoatDongs->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Hiển thị {{ $hoatDongs->firstItem() }} - {{ $hoatDongs->lastItem() }} trong tổng số {{ $hoatDongs->total() }} kết quả
            </div>
            <div>
                {{ $hoatDongs->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    /* (Giữ nguyên style của bạn) */
    .avatar-sm {
        width: 40px;
        height: 40px;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header {
        border: none;
    }

    .btn {
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .table> :not(caption)>*>* {
        padding: 1rem 0.75rem;
    }

    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }

    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }

    .progress-bar {
        border-radius: 10px;
    }

    .modal-content {
        border-radius: 12px;
    }

    .btn-group .btn {
        border-radius: 0;
    }

    .btn-group .btn:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .btn-group .btn:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
</style>
@endsection