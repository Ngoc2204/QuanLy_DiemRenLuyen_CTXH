@extends('layouts.nhanvien')

@section('title', 'Quản lý Phản hồi Sinh viên')
@section('page_title', 'Danh sách Phản hồi')

@php
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.thongbao.index'), 'title' => 'Phản hồi Sinh viên'],
    ];
@endphp

@section('content')
<div class="container-fluid px-4">
    {{-- Header Section với Statistics --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1"><i class="fa-solid fa-comments text-primary me-2"></i>Quản lý Phản hồi Sinh viên</h4>
                    <p class="text-muted mb-0">Theo dõi và xử lý phản hồi từ sinh viên</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fa-solid fa-exclamation-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Chưa xử lý</h6>
                            <h3 class="mb-0">{{ $phanHois->where('TrangThai', 'Chưa xử lý')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fa-solid fa-spinner fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Đang xử lý</h6>
                            <h3 class="mb-0">{{ $phanHois->where('TrangThai', 'Đang xử lý')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fa-solid fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Đã phản hồi</h6>
                            <h3 class="mb-0">{{ $phanHois->where('TrangThai', 'Đã phản hồi')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-info me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Content Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-0"><i class="fa-solid fa-list me-2 text-primary"></i>Danh sách Phản hồi</h5>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- Filter Section --}}
            <form method="GET" action="{{ route('nhanvien.thongbao.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-lg-4 col-md-6">
                        <label for="trang_thai" class="form-label fw-semibold">
                            <i class="fa-solid fa-filter me-1"></i>Lọc theo trạng thái
                        </label>
                        <select name="trang_thai" id="trang_thai" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Chưa xử lý" {{ request('trang_thai') == 'Chưa xử lý' ? 'selected' : '' }}>
                                🔴 Chưa xử lý
                            </option>
                            <option value="Đang xử lý" {{ request('trang_thai') == 'Đang xử lý' ? 'selected' : '' }}>
                                🟡 Đang xử lý
                            </option>
                            <option value="Đã phản hồi" {{ request('trang_thai') == 'Đã phản hồi' ? 'selected' : '' }}>
                                🟢 Đã phản hồi
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-8 col-md-6 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>Áp dụng
                        </button>
                        <a href="{{ route('nhanvien.thongbao.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="fa-solid fa-rotate-right me-2"></i>Đặt lại
                        </a>
                    </div>
                </div>
            </form>

            {{-- Table Section --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center" style="width: 5%;">#</th>
                            <th scope="col" style="width: 15%;">Sinh viên</th>
                            <th scope="col">Nội dung phản hồi</th>
                            <th scope="col" class="text-center" style="width: 12%;">Ngày gửi</th>
                            <th scope="col" class="text-center" style="width: 12%;">Trạng thái</th>
                            <th scope="col" class="text-center" style="width: 15%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($phanHois as $index => $ph)
                            <tr class="feedback-row">
                                <th scope="row" class="text-center fw-semibold">
                                    {{ $phanHois->firstItem() + $index }}
                                </th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                            <i class="fa-solid fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $ph->MSSV }}</div>
                                            <small class="text-muted">{{ $ph->sinhvien->HoTen ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="feedback-content">
                                        {{ Str::limit($ph->NoiDung, 100, '...') }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="small">
                                        <i class="fa-regular fa-calendar me-1"></i>
                                        {{ $ph->NgayGui ? \Carbon\Carbon::parse($ph->NgayGui)->format('d/m/Y') : 'N/A' }}
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fa-regular fa-clock me-1"></i>
                                        {{ $ph->NgayGui ? \Carbon\Carbon::parse($ph->NgayGui)->format('H:i') : '' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($ph->TrangThai == 'Chưa xử lý')
                                        <span class="badge bg-danger-subtle text-danger border border-danger">
                                            <i class="fa-solid fa-circle-exclamation me-1"></i>{{ $ph->TrangThai }}
                                        </span>
                                    @elseif($ph->TrangThai == 'Đang xử lý')
                                        <span class="badge bg-warning-subtle text-warning border border-warning">
                                            <i class="fa-solid fa-spinner me-1"></i>{{ $ph->TrangThai }}
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success">
                                            <i class="fa-solid fa-circle-check me-1"></i>{{ $ph->TrangThai }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('nhanvien.thongbao.show', $ph->MaPhanHoi) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Xem chi tiết"
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('nhanvien.thongbao.edit', $ph->MaPhanHoi) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Cập nhật"
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>

                                    {{-- Delete Modal --}}
                                    <div class="modal fade" id="deleteModal{{ $ph->MaPhanHoi }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-danger text-white border-0">
                                                    <h5 class="modal-title">
                                                        <i class="fa-solid fa-triangle-exclamation me-2"></i>Xác nhận xóa
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-start p-4">
                                                    <p class="mb-0">Bạn có chắc chắn muốn xóa phản hồi này không?</p>
                                                    <p class="text-muted small mb-0">Hành động này không thể hoàn tác.</p>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fa-solid fa-xmark me-1"></i>Hủy
                                                    </button>
                                                    <form action="{{ route('nhanvien.thongbao.destroy', $ph->MaPhanHoi) }}" method="POST" class="d-inline">
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
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa-solid fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                        <h5>Không có phản hồi nào</h5>
                                        <p class="mb-0">Chưa có phản hồi nào phù hợp với bộ lọc của bạn.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($phanHois->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
                    <div class="text-muted small mb-2 mb-md-0">
                        Hiển thị {{ $phanHois->firstItem() }} - {{ $phanHois->lastItem() }} 
                        trong tổng số {{ $phanHois->total() }} phản hồi
                    </div>
                    <div>
                        {{ $phanHois->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.feedback-row {
    transition: all 0.3s ease;
}

.feedback-row:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}

.feedback-content {
    line-height: 1.6;
    color: #495057;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.btn-group .btn {
    border-radius: 6px !important;
    margin: 0 2px;
}

.badge {
    padding: 8px 12px;
    font-weight: 500;
    font-size: 0.85rem;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

.bg-danger-subtle {
    background-color: #f8d7da !important;
}

.bg-warning-subtle {
    background-color: #fff3cd !important;
}

.bg-success-subtle {
    background-color: #d1e7dd !important;
}

.modal-content {
    border-radius: 12px;
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.03);
}
</style>
@endsection

@push('scripts')
<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush