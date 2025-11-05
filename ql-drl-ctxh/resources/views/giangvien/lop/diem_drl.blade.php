@extends('layouts.giangvien')

@section('title', 'Xem điểm Rèn Luyện')
@section('page_title', 'Xem điểm Rèn Luyện')

@php
$breadcrumbs = [
    ['url' => route('giangvien.home'), 'title' => 'Bảng điều khiển'],
    ['url' => route('giangvien.lop.diem_drl'), 'title' => 'Xem điểm Rèn Luyện'],
];
@endphp

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="header-section">
                <div class="header-content">
                    <h1 class="header-title">
                        <i class="fa-solid fa-book-open me-3"></i>Điểm Rèn Luyện
                    </h1>
                    <p class="header-subtitle">Quản lý và theo dõi điểm rèn luyện của sinh viên theo học kỳ</p>
                </div>
                <div class="header-stats">
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                        <div class="stat-info">
                            <span class="stat-label">Học kỳ hiện tại</span>
                            <span class="stat-value">HK{{ $selectedHocKy }}</span>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-info">
                            <span class="stat-label">Tổng sinh viên</span>
                            <span class="stat-value">{{ $sinhviens->total() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card">
                <form method="GET" action="{{ route('giangvien.lop.diem_drl') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-5 col-md-6">
                            <label for="hoc_ky" class="form-label fw-semibold mb-2">
                                <i class="fa-solid fa-calendar-days me-2"></i>Chọn Học Kỳ
                            </label>
                            <select name="hoc_ky" id="hoc_ky" class="form-select filter-select" onchange="document.getElementById('filterForm').submit();">
                                @foreach($hocKys as $hocKy)
                                    <option value="{{ $hocKy->MaHocKy }}" {{ $hocKy->MaHocKy == $selectedHocKy ? 'selected' : '' }}>
                                        {{ $hocKy->TenHocKy }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5 col-md-6">
                            <label for="ma_lop" class="form-label fw-semibold mb-2">
                                <i class="fa-solid fa-filter me-2"></i>Lọc theo Lớp
                            </label>
                            <select name="ma_lop" id="ma_lop" class="form-select filter-select">
                                <option value="">-- Tất cả các lớp --</option>
                                @foreach($lopCoVanList as $lop)
                                    <option value="{{ $lop->MaLop }}" {{ $lop->MaLop == $selectedLop ? 'selected' : '' }}>
                                        {{ $lop->TenLop }} ({{ $lop->MaLop }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-12 d-flex gap-2">
                            <button class="btn btn-filter flex-grow-1" type="submit">
                                <i class="fa-solid fa-magnifying-glass me-2"></i>Tìm kiếm
                            </button>
                            @if($selectedLop)
                                <a href="{{ route('giangvien.lop.diem_drl', ['hoc_ky' => $selectedHocKy]) }}" class="btn btn-light" title="Reset lớp">
                                    <i class="fa-solid fa-redo"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="row">
        <div class="col-12">
            <div class="table-card">
                @forelse ($sinhviens as $index => $sv)
                    @if($index === 0)
                    <div class="table-responsive">
                        <table class="table data-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%;">
                                        <span class="table-header-text">STT</span>
                                    </th>
                                    <th style="width: 15%;">
                                        <span class="table-header-text">MSSV</span>
                                    </th>
                                    <th style="width: 30%;">
                                        <span class="table-header-text">Họ Tên</span>
                                    </th>
                                    <th style="width: 18%;">
                                        <span class="table-header-text">Lớp</span>
                                    </th>
                                    <th class="text-center" style="width: 16%;">
                                        <span class="table-header-text">Điểm HK{{ $selectedHocKy }}</span>
                                    </th>
                                    <th class="text-center" style="width: 16%;">
                                        <span class="table-header-text">Xếp Loại</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                                <tr class="table-row">
                                    <td class="text-center">
                                        <span class="row-number">{{ $sinhviens->firstItem() + $index }}</span>
                                    </td>
                                    <td>
                                        <span class="student-id">{{ $sv->MSSV }}</span>
                                    </td>
                                    <td>
                                        <span class="student-name">{{ $sv->HoTen }}</span>
                                    </td>
                                    <td>
                                        <span class="class-badge">{{ $sv->MaLop }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="score-badge {{ $sv->TongDiem >= 80 ? 'score-high' : ($sv->TongDiem >= 60 ? 'score-medium' : ($sv->TongDiem >= 40 ? 'score-low' : 'score-critical')) }}">
                                            {{ $sv->TongDiem ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($sv->XepLoai)
                                            <span class="xep-loai-badge xep-loai-{{ strtolower($sv->XepLoai) }}">
                                                {{ $sv->XepLoai }}
                                            </span>
                                        @else
                                            <span class="xep-loai-badge xep-loai-none">Chưa có</span>
                                        @endif
                                    </td>
                                </tr>
                    @if($loop->last)
                            </tbody>
                        </table>
                    </div>
                    @endif
                @empty
                    <div class="empty-state-container">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fa-solid fa-inbox"></i>
                            </div>
                            <h5 class="empty-title">Không có dữ liệu</h5>
                            <p class="empty-text">Không tìm thấy sinh viên nào khớp với bộ lọc của bạn.</p>
                            @if($selectedLop)
                                <a href="{{ route('giangvien.lop.diem_drl', ['hoc_ky' => $selectedHocKy]) }}" class="btn btn-sm btn-outline-primary mt-3">
                                    <i class="fa-solid fa-redo me-2"></i>Xóa bộ lọc
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse

                {{-- Pagination --}}
                @if($sinhviens->hasPages())
                <div class="pagination-container">
                    {{ $sinhviens->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root {
    --primary-color: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #eef2ff;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --border-radius: 12px;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

* {
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #f8f7ff 0%, #f0e7ff 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container-fluid {
    padding: 2rem;
}

/* Header Section */
.header-section {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: var(--shadow-md);
    margin-bottom: 1rem;
}

.header-content h1 {
    margin: 0;
    color: #1f2937;
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.header-subtitle {
    margin: 0.5rem 0 0 2.5rem;
    color: #6b7280;
    font-size: 0.95rem;
}

.header-stats {
    display: flex;
    gap: 1.5rem;
}

.stat-box {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary-color);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border-radius: 50%;
    font-size: 1.25rem;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 500;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--primary-dark);
}

/* Filter Card */
.filter-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
}

.filter-select {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background-color: white;
}

.filter-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    outline: none;
}

.btn-filter {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    white-space: nowrap;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: white;
}

.btn-filter:active {
    transform: translateY(0);
}

.btn-light {
    border: 2px solid #e5e7eb;
    color: #6b7280;
    background: white;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.btn-light:hover {
    background: #f9fafb;
    border-color: var(--primary-color);
    color: var(--primary-color);
}

/* Table Card */
.table-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.data-table {
    margin-bottom: 0;
}

.data-table thead {
    background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
    border-bottom: 2px solid #e9d5ff;
}

.table-header-text {
    color: #4f46e5;
    font-weight: 700;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-row {
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.table-row:hover {
    background: #faf8ff;
    box-shadow: inset 0 0 0 1px rgba(99, 102, 241, 0.05);
}

.row-number {
    color: #9ca3af;
    font-weight: 600;
    font-size: 0.95rem;
}

.student-id {
    font-family: 'Courier New', monospace;
    color: #374151;
    font-weight: 600;
    font-size: 0.95rem;
}

.student-name {
    color: #1f2937;
    font-weight: 500;
    font-size: 0.95rem;
}

.class-badge {
    display: inline-block;
    background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
    color: var(--primary-dark);
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.85rem;
    border-left: 3px solid var(--primary-color);
}

.score-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.95rem;
    min-width: 60px;
    text-align: center;
}

.score-high {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    box-shadow: 0 0 0 1px #6ee7b7;
}

.score-medium {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #78350f;
    box-shadow: 0 0 0 1px #fcd34d;
}

.score-low {
    background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
    color: #7f1d1d;
    box-shadow: 0 0 0 1px #f87171;
}

.score-critical {
    background: linear-gradient(135deg, #fecdd3 0%, #fca5a5 100%);
    color: #7f1d1d;
    box-shadow: 0 0 0 1px #f87171;
}

/* Xếp Loại Badge */
.xep-loai-badge {
    display: inline-block;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.2s ease;
}

.xep-loai-tốt {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    box-shadow: 0 0 0 1px #6ee7b7;
}

.xep-loai-khá {
    background: linear-gradient(135deg, #bfdbfe 0%, #93c5fd 100%);
    color: #1e40af;
    box-shadow: 0 0 0 1px #60a5fa;
}

.xep-loai-trung-bình {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #78350f;
    box-shadow: 0 0 0 1px #fcd34d;
}

.xep-loai-yếu {
    background: linear-gradient(135deg, #fecdd3 0%, #fca5a5 100%);
    color: #7f1d1d;
    box-shadow: 0 0 0 1px #f87171;
}

.xep-loai-none {
    background: #f3f4f6;
    color: #9ca3af;
    box-shadow: 0 0 0 1px #e5e7eb;
}

/* Empty State */
.empty-state-container {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.empty-icon {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.empty-title {
    color: #374151;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #9ca3af;
    font-size: 0.95rem;
    margin: 0;
}

/* Pagination */
.pagination-container {
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
    background: #faf8ff;
    display: flex;
    justify-content: center;
}

.pagination {
    gap: 0.5rem;
}

.pagination .page-link {
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    color: var(--primary-color);
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    background: var(--primary-light);
    border-color: var(--primary-color);
}

.pagination .page-item.active .page-link {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

/* Responsive */
@media (max-width: 768px) {
    .header-section {
        flex-direction: column;
        gap: 1.5rem;
    }

    .header-content h1 {
        font-size: 1.5rem;
    }

    .header-subtitle {
        margin-left: 2rem;
    }

    .header-stats {
        width: 100%;
        justify-content: center;
        flex-wrap: wrap;
    }

    .stat-box {
        flex: 1;
        min-width: 200px;
    }

    .table-responsive {
        font-size: 0.85rem;
    }

    .student-id,
    .student-name,
    .score-badge,
    .xep-loai-badge {
        font-size: 0.85rem;
    }

    .filter-card {
        padding: 1rem;
    }

    .d-flex {
        display: flex;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .flex-grow-1 {
        flex-grow: 1;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 1rem;
    }

    .header-content h1 {
        font-size: 1.25rem;
    }

    .header-subtitle {
        margin-left: 1.5rem;
        font-size: 0.85rem;
    }

    .stat-box {
        padding: 0.75rem;
        min-width: 150px;
    }

    .stat-icon {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1rem;
    }

    .stat-value {
        font-size: 1.25rem;
    }

    .filter-card {
        padding: 0.75rem;
    }

    .btn-filter {
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
    }

    .d-flex {
        display: flex;
        flex-direction: column;
    }

    .gap-2 {
        gap: 0.5rem;
    }
}
</style>
@endpush