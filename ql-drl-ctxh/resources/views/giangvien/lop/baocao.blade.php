@extends('layouts.giangvien')

@section('title', 'Báo cáo & Xuất file')
@section('page_title', 'Báo cáo & Xuất file')

@php
$breadcrumbs = [
    ['url' => route('giangvien.home'), 'title' => 'Bảng điều khiển'],
    ['url' => route('giangvien.lop.baocao'), 'title' => 'Báo cáo'],
];
@endphp

@section('content')
{{-- Stats Cards (Hiện khi đã chọn lớp và học kỳ) --}}
@if ($selectedLop && $selectedHocKy)
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="stats-icon bg-primary-subtle text-primary">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stats-content">
                <div class="stats-label">Tổng sinh viên</div>
                <div class="stats-value">{{ $sinhviens->total() }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="stats-icon bg-success-subtle text-success">
                <i class="fa-solid fa-star"></i>
            </div>
            <div class="stats-content">
                <div class="stats-label">ĐTB Điểm DRL</div>
                <div class="stats-value">
                    {{ number_format($sinhviens->avg(fn($sv) => $sv->diemRenLuyen->first()->TongDiem ?? 0), 1) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="stats-icon bg-info-subtle text-info">
                <i class="fa-solid fa-hands-helping"></i>
            </div>
            <div class="stats-content">
                <div class="stats-label">ĐTB Điểm CTXH</div>
                <div class="stats-value">
                    {{ number_format($sinhviens->avg(fn($sv) => $sv->diemCtxh->TongDiem ?? 0), 1) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="stats-icon bg-warning-subtle text-warning">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <div class="stats-content">
                <div class="stats-label">Xếp loại Xuất sắc</div>
                <div class="stats-value">
                    {{ $sinhviens->filter(fn($sv) => optional($sv->diemRenLuyen->first())->XepLoai == 'Xuất sắc')->count() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Filter Card --}}
<div class="card modern-card">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex align-items-center">
            <div class="header-icon">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <h5 class="mb-0 fw-bold">Bộ lọc báo cáo</h5>
        </div>
    </div>
    
    <div class="card-body p-4">
        <form method="GET" action="{{ route('giangvien.lop.baocao') }}">
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <label for="ma_lop" class="form-label fw-semibold">
                        <i class="fa-solid fa-school me-1 text-primary"></i>
                        Lớp Cố Vấn 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="ma_lop" id="ma_lop" class="form-select modern-select" required>
                        <option value="">-- Chọn lớp cố vấn --</option>
                        @foreach($lopCoVanList as $lop)
                            <option value="{{ $lop->MaLop }}" {{ $lop->MaLop == $selectedLop ? 'selected' : '' }}>
                                {{ $lop->TenLop }} ({{ $lop->MaLop }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 col-md-6">
                    <label for="hoc_ky" class="form-label fw-semibold">
                        <i class="fa-solid fa-calendar-days me-1 text-success"></i>
                        Học Kỳ 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="hoc_ky" id="hoc_ky" class="form-select modern-select" required>
                        <option value="">-- Chọn học kỳ --</option>
                        @foreach($hocKys as $hocKy)
                            <option value="{{ $hocKy->MaHocKy }}" {{ $hocKy->MaHocKy == $selectedHocKy ? 'selected' : '' }}>
                                {{ $hocKy->TenHocKy }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 col-md-12">
                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-fill modern-btn" type="submit">
                            <i class="fa-solid fa-eye me-2"></i>Xem Báo Cáo
                        </button>
                        <a href="{{ route('giangvien.lop.baocao.export', request()->query()) }}" 
                           class="btn btn-success flex-fill modern-btn {{ (!$selectedLop || !$selectedHocKy) ? 'disabled' : '' }}" 
                           onclick="return {{ (!$selectedLop || !$selectedHocKy) ? 'false' : 'true' }}"
                           title="{{ (!$selectedLop || !$selectedHocKy) ? 'Vui lòng chọn lớp và học kỳ trước' : 'Xuất báo cáo Excel' }}">
                            <i class="fa-solid fa-file-excel me-2"></i>Xuất Excel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Data Table --}}
@if ($selectedLop && $selectedHocKy)
<div class="card modern-card mt-4">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <div class="header-icon">
                    <i class="fa-solid fa-chart-bar"></i>
                </div>
                <h5 class="mb-0 fw-bold">Bảng điểm tổng hợp</h5>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    <i class="fa-solid fa-school me-1"></i>{{ $selectedLop }}
                </span>
                <span class="badge bg-success-subtle text-success px-3 py-2">
                    <i class="fa-solid fa-calendar me-1"></i>HK: {{ $selectedHocKy }}
                </span>
                <span class="badge bg-info-subtle text-info px-3 py-2">
                    <i class="fa-solid fa-users me-1"></i>{{ $sinhviens->total() }} SV
                </span>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table modern-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">STT</th>
                        <th style="width: 12%;">MSSV</th>
                        <th style="width: 28%;">Họ và Tên</th>
                        <th class="text-center" style="width: 15%;">
                            <i class="fa-solid fa-graduation-cap me-1"></i>
                            Điểm DRL
                        </th>
                        <th class="text-center" style="width: 20%;">
                            <i class="fa-solid fa-medal me-1"></i>
                            Xếp Loại DRL
                        </th>
                        <th class="text-center" style="width: 20%;">
                            <i class="fa-solid fa-hands-helping me-1"></i>
                            Điểm CTXH
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sinhviens as $index => $sv)
                    @php
                        $diemDRL = $sv->diemRenLuyen->first();
                        $diemCTXH = $sv->diemCtxh;
                        $tongDiemDRL = $diemDRL->TongDiem ?? 0;
                        $tongDiemCTXH = $diemCTXH->TongDiem ?? 0;
                    @endphp
                    <tr class="table-row-hover">
                        <td class="text-center">
                            <div class="index-badge">{{ $sinhviens->firstItem() + $index }}</div>
                        </td>
                        <td>
                            <div class="student-code">
                                <i class="fa-solid fa-id-card me-1 text-muted"></i>
                                {{ $sv->MSSV }}
                            </div>
                        </td>
                        <td>
                            <div class="student-name">
                                <i class="fa-solid fa-user me-1 text-primary"></i>
                                {{ $sv->HoTen }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="score-badge score-drl">
                                <div class="score-value">{{ $tongDiemDRL }}</div>
                                <div class="score-label">điểm</div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($diemDRL && $diemDRL->XepLoai)
                                @php
                                    $xepLoai = $diemDRL->XepLoai;
                                    $badgeClass = match($xepLoai) {
                                        'Xuất sắc' => 'rank-excellent',
                                        'Tốt' => 'rank-good',
                                        'Khá' => 'rank-fair',
                                        'Trung bình' => 'rank-average',
                                        default => 'rank-weak'
                                    };
                                @endphp
                                <span class="rank-badge {{ $badgeClass }}">
                                    @if($xepLoai == 'Xuất sắc')
                                        <i class="fa-solid fa-crown me-1"></i>
                                    @elseif($xepLoai == 'Tốt')
                                        <i class="fa-solid fa-star me-1"></i>
                                    @endif
                                    {{ $xepLoai }}
                                </span>
                            @else
                                <span class="rank-badge rank-none">
                                    <i class="fa-solid fa-minus me-1"></i>
                                    Chưa có
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="score-badge score-ctxh">
                                <div class="score-value">{{ $tongDiemCTXH }}</div>
                                <div class="score-label">điểm</div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fa-solid fa-user-slash"></i>
                                </div>
                                <h5 class="empty-title">Không có dữ liệu</h5>
                                <p class="empty-text">Không tìm thấy sinh viên nào trong lớp này.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($sinhviens->hasPages())
        <div class="card-footer bg-white border-top">
            <div class="pagination-wrapper">
                {{ $sinhviens->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

@else
{{-- Empty State - Chưa chọn lọc --}}
<div class="card modern-card mt-4">
    <div class="card-body text-center py-5">
        <div class="empty-state-large">
            <div class="empty-icon-large">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <h4 class="empty-title-large">Bắt đầu xem báo cáo</h4>
            <p class="empty-text-large">
                Vui lòng chọn <strong>Lớp cố vấn</strong> và <strong>Học kỳ</strong> ở trên,<br>
                sau đó nhấn <strong>"Xem Báo Cáo"</strong> để tải dữ liệu.
            </p>
            <div class="empty-steps">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-text">Chọn lớp</div>
                </div>
                <div class="step-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-text">Chọn học kỳ</div>
                </div>
                <div class="step-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-text">Xem báo cáo</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
@import url('https://pastebin.com/raw/L8C35G0J');

/* Stats Cards */
.stats-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.stats-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stats-content {
    flex: 1;
}

.stats-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.stats-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #212529;
    line-height: 1;
}

/* Card Header */
.card-header {
    padding: 1.25rem 1.5rem;
}

.header-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    margin-right: 1rem;
}

/* Form Elements */
.form-label {
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.modern-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.625rem 1rem;
    transition: all 0.3s ease;
}

.modern-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

/* Buttons */
.modern-btn {
    border-radius: 8px;
    padding: 0.625rem 1.25rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary.modern-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-primary.modern-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-success.modern-btn {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.btn-success.modern-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(17, 153, 142, 0.4);
}

.btn.disabled {
    pointer-events: auto !important;
    cursor: not-allowed !important;
    opacity: 0.5;
    filter: grayscale(50%);
}

/* Table */
.modern-table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    padding: 1rem;
    border: none;
}

.modern-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

.table-row-hover {
    transition: all 0.2s ease;
}

.table-row-hover:hover {
    background-color: #f8f9fa;
}

/* Index Badge */
.index-badge {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Student Info */
.student-code {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.student-name {
    font-weight: 600;
    color: #212529;
    font-size: 0.95rem;
}

/* Score Badges */
.score-badge {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    min-width: 70px;
}

.score-drl {
    background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
    border: 2px solid #667eea;
}

.score-ctxh {
    background: linear-gradient(135deg, #11998e20 0%, #38ef7d20 100%);
    border: 2px solid #11998e;
}

.score-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #212529;
    line-height: 1;
}

.score-label {
    font-size: 0.7rem;
    color: #6c757d;
    text-transform: uppercase;
    margin-top: 0.25rem;
}

/* Rank Badges */
.rank-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
}

.rank-excellent {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.rank-good {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.rank-fair {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.rank-average {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
}

.rank-weak {
    background: linear-gradient(135deg, #a8a8a8 0%, #d0d0d0 100%);
    color: white;
}

.rank-none {
    background: #e9ecef;
    color: #6c757d;
}

/* Empty State */
.empty-state {
    padding: 2rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
}

.empty-title {
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.75rem;
}

.empty-text {
    color: #6c757d;
    font-size: 0.95rem;
}

/* Empty State Large */
.empty-state-large {
    padding: 3rem 2rem;
}

.empty-icon-large {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    color: white;
    font-size: 3rem;
}

.empty-title-large {
    font-weight: 700;
    color: #212529;
    margin-bottom: 1rem;
}

.empty-text-large {
    color: #6c757d;
    font-size: 1.05rem;
    line-height: 1.8;
    margin-bottom: 2rem;
}

/* Steps */
.empty-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.step-number {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
}

.step-text {
    font-size: 0.875rem;
    font-weight: 600;
    color: #495057;
}

.step-arrow {
    color: #dee2e6;
    font-size: 1.5rem;
}

/* Badges in Header */
.badge {
    font-weight: 500;
    border-radius: 8px;
}

/* Pagination */
.pagination-wrapper {
    padding: 1rem;
    display: flex;
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-card {
        padding: 1rem;
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .stats-value {
        font-size: 1.5rem;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .modern-table {
        font-size: 0.875rem;
    }
    
    .score-badge {
        padding: 0.4rem 0.7rem;
        min-width: 60px;
    }
    
    .score-value {
        font-size: 1.1rem;
    }
    
    .empty-steps {
        gap: 0.5rem;
    }
    
    .step-arrow {
        display: none;
    }
}
</style>
@endpush