@extends('layouts.nhanvien')

{{-- Sử dụng biến $hoatdong_ctxh hoặc $hoatdong_drl được truyền vào --}}
@php
    $activity = $hoatdong_ctxh ?? $hoatdong_drl;
    $activityType = isset($hoatdong_ctxh) ? 'hoatdong_ctxh' : 'hoatdong_drl';
    $pageTitle = 'Ghi Nhận Kết Quả: ' . $activity->TenHoatDong;

    // Breadcrumbs
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.' . $activityType . '.index'), 'title' => ($activityType == 'hoatdong_ctxh' ? 'Hoạt động CTXH' : 'Hoạt động DRL')],
        ['url' => route('nhanvien.' . $activityType . '.show', $activity), 'title' => 'Chi tiết'],
        ['url' => '#', 'title' => 'Ghi nhận'],
    ];
@endphp

@section('title', $pageTitle)
@section('page_title', $pageTitle)

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .page-header {
        background: var(--primary-gradient);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
    }

    .page-header h4 {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header .activity-code {
        font-size: 0.875rem;
        opacity: 0.95;
        font-weight: 500;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid var(--gray-200);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-card-icon.total { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .stat-card-icon.checkin { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .stat-card-icon.checkout { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
    .stat-card-icon.pending { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; }

    .stat-card-label {
        font-size: 0.875rem;
        color: var(--gray-600);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-700);
        margin-top: 0.25rem;
    }

    .main-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-md);
        overflow: hidden;
        border: 1px solid var(--gray-200);
    }

    .table-container {
        overflow-x: auto;
        max-height: 65vh;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .modern-table thead th {
        background: var(--gray-50);
        color: var(--gray-700);
        font-weight: 600;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 2px solid var(--gray-200);
        white-space: nowrap;
    }

    .modern-table tbody tr {
        transition: all 0.2s;
        border-bottom: 1px solid var(--gray-200);
    }

    .modern-table tbody tr:hover {
        background-color: var(--gray-50);
    }

    .modern-table tbody tr.disabled-row {
        background-color: #fafafa;
        opacity: 0.7;
    }

    .modern-table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    .student-details {
        flex: 1;
        min-width: 0;
    }

    .student-name {
        font-weight: 600;
        color: var(--gray-700);
        font-size: 0.9375rem;
        margin-bottom: 0.125rem;
    }

    .student-code {
        font-size: 0.8125rem;
        color: var(--gray-600);
        font-family: 'Courier New', monospace;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8125rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge.approved {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-badge.pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-badge.rejected {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .time-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        font-family: 'Courier New', monospace;
    }

    .time-badge.checkin {
        background-color: #d1fae5;
        color: #065f46;
        border: 2px solid #10b981;
    }

    .time-badge.checkout {
        background-color: #fef3c7;
        color: #92400e;
        border: 2px solid #f59e0b;
    }

    .time-badge.empty {
        background-color: var(--gray-100);
        color: var(--gray-600);
        border: 2px dashed var(--gray-200);
    }

    .modern-select {
        padding: 0.625rem 0.875rem;
        border: 2px solid var(--gray-200);
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        background-color: white;
        cursor: pointer;
        min-width: 180px;
    }

    .modern-select:hover {
        border-color: #667eea;
    }

    .modern-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .modern-select:disabled {
        background-color: var(--gray-100);
        cursor: not-allowed;
        opacity: 0.6;
    }

    .disabled-notice {
        display: inline-block;
        margin-top: 0.375rem;
        font-size: 0.75rem;
        color: var(--danger-color);
        font-weight: 600;
    }

    .action-bar {
        background: var(--gray-50);
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid var(--gray-200);
    }

    .btn-modern {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-modern.btn-back {
        background-color: white;
        color: var(--gray-700);
        border: 2px solid var(--gray-200);
    }

    .btn-modern.btn-back:hover {
        background-color: var(--gray-50);
        border-color: var(--gray-600);
    }

    .btn-modern.btn-save {
        background: var(--success-color);
        color: white;
        box-shadow: var(--shadow-md);
    }

    .btn-modern.btn-save:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--gray-200);
        margin-bottom: 1.5rem;
    }

    .empty-state-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: var(--gray-600);
        font-size: 0.9375rem;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .stats-cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .table-container {
            max-height: 50vh;
        }

        .action-bar {
            flex-direction: column;
            gap: 1rem;
        }

        .action-bar > * {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h4>
        <i class="fa-solid fa-clipboard-check"></i>
        {{ $activity->TenHoatDong }}
    </h4>
    <div class="activity-code">
        <i class="fa-solid fa-barcode me-1"></i>
        Mã hoạt động: {{ $activity->MaHoatDong }}
    </div>
</div>

@if($sinhViens->isNotEmpty())
    @php
        $total = $sinhViens->count();
        $checkedIn = $sinhViens->filter(fn($sv) => $sv->pivot->CheckInAt)->count();
        $checkedOut = $sinhViens->filter(fn($sv) => $sv->pivot->CheckOutAt)->count();
        $pending = $sinhViens->filter(fn($sv) => $sv->pivot->TrangThaiThamGia == 'Chưa tổng kết')->count();
    @endphp

    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-card-icon total">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-card-label">Tổng số sinh viên</div>
            <div class="stat-card-value">{{ $total }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon checkin">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div class="stat-card-label">Đã check-in</div>
            <div class="stat-card-value">{{ $checkedIn }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon checkout">
                <i class="fa-solid fa-user-clock"></i>
            </div>
            <div class="stat-card-label">Đã check-out</div>
            <div class="stat-card-value">{{ $checkedOut }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon pending">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div class="stat-card-label">Chưa tổng kết</div>
            <div class="stat-card-value">{{ $pending }}</div>
        </div>
    </div>
@endif

<form action="{{ route('nhanvien.' . $activityType . '.update_ket_qua', $activity) }}" method="POST">
    @csrf
    <div class="main-card">
        @if($sinhViens->isNotEmpty())
            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th style="min-width: 250px;">Sinh viên</th>
                            <th class="text-center" style="width: 140px;">Trạng thái ĐK</th>
                            <th class="text-center" style="width: 140px;">Check-In</th>
                            <th class="text-center" style="width: 140px;">Check-Out</th>
                            <th class="text-center" style="min-width: 240px;">Kết quả tham gia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sinhViens as $index => $sv)
                            @php
                                $pivot = $sv->pivot;
                                $isDisabled = $pivot->TrangThaiDangKy != 'Đã duyệt';
                                $initials = collect(explode(' ', $sv->HoTen ?? 'SV'))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
                            @endphp
                            <tr class="{{ $isDisabled ? 'disabled-row' : '' }}">
                                <td class="text-center">
                                    <strong>{{ $index + 1 }}</strong>
                                </td>
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar">{{ $initials }}</div>
                                        <div class="student-details">
                                            <div class="student-name">{{ $sv->HoTen ?? 'Không rõ' }}</div>
                                            <div class="student-code">{{ $sv->MSSV }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($pivot->TrangThaiDangKy == 'Đã duyệt')
                                        <span class="status-badge approved">
                                            <i class="fa-solid fa-circle-check"></i>
                                            {{ $pivot->TrangThaiDangKy }}
                                        </span>
                                    @elseif($pivot->TrangThaiDangKy == 'Chờ duyệt')
                                        <span class="status-badge pending">
                                            <i class="fa-solid fa-clock"></i>
                                            {{ $pivot->TrangThaiDangKy }}
                                        </span>
                                    @else
                                        <span class="status-badge rejected">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                            {{ $pivot->TrangThaiDangKy }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($pivot->CheckInAt)
                                        <span class="time-badge checkin">
                                            <i class="fa-solid fa-right-to-bracket"></i>
                                            {{ \Carbon\Carbon::parse($pivot->CheckInAt)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="time-badge empty">
                                            <i class="fa-solid fa-minus"></i>
                                            Chưa có
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($pivot->CheckOutAt)
                                        <span class="time-badge checkout">
                                            <i class="fa-solid fa-right-from-bracket"></i>
                                            {{ \Carbon\Carbon::parse($pivot->CheckOutAt)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="time-badge empty">
                                            <i class="fa-solid fa-minus"></i>
                                            Chưa có
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <input type="hidden" name="results[{{ $pivot->MaDangKy }}][MaDangKy]" value="{{ $pivot->MaDangKy }}">
                                    
                                    @php
                                        // Tự động xác định trạng thái dựa trên check-in/out
                                        $currentStatus = $pivot->TrangThaiThamGia;
                                        
                                        // Nếu chưa có giá trị hoặc null, tự động set dựa trên check-in/out
                                        if (empty($currentStatus) || $currentStatus == 'Chưa tổng kết') {
                                            if ($pivot->CheckInAt && $pivot->CheckOutAt) {
                                                $currentStatus = 'Đã tham gia';
                                            } elseif (!$pivot->CheckInAt && !$pivot->CheckOutAt) {
                                                $currentStatus = 'Chưa tổng kết';
                                            } else {
                                                $currentStatus = 'Chưa tổng kết';
                                            }
                                        }
                                    @endphp
                                    
                                    <select name="results[{{ $pivot->MaDangKy }}][TrangThaiThamGia]" 
                                            class="modern-select" 
                                            {{ $isDisabled ? 'disabled' : '' }}>
                                        <option value="Chưa tổng kết" {{ $currentStatus == 'Chưa tổng kết' ? 'selected' : '' }}>
                                            ⏳ Chưa tổng kết
                                        </option>
                                        <option value="Đã tham gia" {{ $currentStatus == 'Đã tham gia' ? 'selected' : '' }}>
                                            ✓ Đã tham gia
                                        </option>
                                        <option value="Vắng" {{ $currentStatus == 'Vắng' ? 'selected' : '' }}>
                                            ✗ Vắng
                                        </option>
                                    </select>
                                    
                                    @if($isDisabled)
                                        <span class="disabled-notice">
                                            <i class="fa-solid fa-lock"></i> Không thể chỉnh sửa
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fa-solid fa-users-slash"></i>
                </div>
                <div class="empty-state-title">Chưa có sinh viên nào</div>
                <div class="empty-state-text">
                    Không có sinh viên đã được duyệt để ghi nhận kết quả.
                </div>
            </div>
        @endif

        <div class="action-bar">
            <a href="{{ route('nhanvien.' . $activityType . '.show', $activity) }}" class="btn-modern btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                Quay lại
            </a>
            @if($sinhViens->isNotEmpty())
                <button type="submit" class="btn-modern btn-save">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Lưu kết quả
                </button>
            @endif
        </div>
    </div>
</form>
@endsection 