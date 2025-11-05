@extends('layouts.sinhvien')
@section('title', 'Tổng điểm Công Tác Xã Hội')

@push('styles')
<style>
    :root {
        --primary-red: #f5576c;
        --secondary-purple: #764ba2;
        --success: #10b981;
        --danger: #ef4444;
    }

    .page-wrapper {
        background: linear-gradient(135deg, #fef2f2 0%, #fce7f3 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Header Card */
    .header-card {
        background: linear-gradient(135deg, #f5576c 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(245, 87, 108, 0.3);
        position: relative;
        overflow: hidden;
    }

    .header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .header-card h3 {
        color: white;
        font-weight: 700;
        margin: 0;
        font-size: 1.8rem;
        position: relative;
        z-index: 1;
    }

    .header-card .subtitle {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1rem;
        margin-top: 0.5rem;
        position: relative;
        z-index: 1;
    }

    /* Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, rgba(245, 87, 108, 0.1) 0%, transparent 100%);
        border-radius: 0 16px 0 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        border-color: var(--primary-red);
    }

    .stat-card .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .stat-card .icon-wrapper.bg-primary {
        background: linear-gradient(135deg, #f5576c 0%, #764ba2 100%);
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
    }

    .stat-card .icon-wrapper.bg-success {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .stat-card .icon-wrapper.bg-danger {
        background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .stat-card .stat-content {
        flex: 1;
        position: relative;
        z-index: 1;
    }

    .stat-card .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-card .stat-value.text-primary {
        background: linear-gradient(135deg, #f5576c 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-card .stat-value.text-success {
        color: var(--success);
    }

    .stat-card .stat-value.text-danger {
        color: var(--danger);
    }

    .stat-card .stat-description {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        border: none;
    }

    .table-card .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }

    .table-card h4 {
        color: #212529;
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .table-custom {
        margin: 0;
    }

    .table-custom thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        color: #495057;
        padding: 1rem;
    }

    .table-custom tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
    }

    .table-custom tbody tr {
        transition: all 0.2s ease;
    }

    .table-custom tbody tr:hover {
        background: #fff5f7;
        transform: scale(1.01);
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    .activity-name {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }

    .activity-type {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .activity-description {
        color: #6c757d;
        font-size: 0.85rem;
    }

    .badge-points {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .badge-points i {
        margin-right: 0.25rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    .empty-state p {
        color: #94a3b8;
        margin: 0;
        font-size: 1rem;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.5rem;
        border-radius: 10px;    
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-badge.success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .status-badge.danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    .status-badge i {
        font-size: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: 1fr;
        }
        
        .header-card {
            padding: 1.5rem;
        }

        .header-card h3 {
            font-size: 1.5rem;
        }

        .stat-card {
            flex-direction: column;
            text-align: center;
        }

        .stat-card .icon-wrapper {
            margin: 0 auto;
        }
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <div class="container-fluid" style="max-width: 1400px; margin: 0 auto; padding: 0 1rem;">

        <!-- Header -->
        <div class="header-card">
            <h3><i class="fas fa-hand-holding-heart me-2"></i>Điểm Công Tác Xã Hội</h3>
            <p class="subtitle">Tổng kết điểm công tác xã hội của bạn trong toàn khóa học</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <!-- Tổng Điểm CTXH -->
            <div class="stat-card">
                <div class="icon-wrapper bg-primary">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Tổng Điểm CTXH</div>
                    <div class="stat-value text-primary">{{ $tongDiemMoi }}</div>
                    <div class="stat-description">Tích lũy toàn khóa</div>
                </div>
            </div>

            <!-- Điều Kiện Bắt Buộc -->
            <div class="stat-card">
                @if($has_red_activity)
                    <div class="icon-wrapper bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Điều Kiện Bắt Buộc (>1 Tham Gia Địa Chỉ Đỏ)</div>
                        <div class="stat-value text-success">Đã đạt</div>
                        <span class="status-badge success">
                            <i class="fas fa-check"></i>
                            Hoàn thành yêu cầu
                        </span>
                    </div>
                @else
                    <div class="icon-wrapper bg-danger">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Điều Kiện Bắt Buộc (>1 Tham Gia Địa Chỉ Đỏ)</div>
                        <div class="stat-value text-danger">Chưa đạt</div>
                        <span class="status-badge danger">
                            <i class="fas fa-times"></i>
                            Cần hoàn thành
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activities Table -->
        <div class="table-card">
            <div class="card-header">
                <h4>
                    <i class="fas fa-list-check me-2" style="color: #f5576c;"></i>
                    Chi tiết các hoạt động CTXH đã tham gia
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Tên Hoạt Động</th>
                                <th style="width: 20%;">Loại</th>
                                <th class="text-center" style="width: 20%;">Ngày tham gia</th>
                                <th class="text-center" style="width: 20%;">Điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cacHoatDongDaThamGia as $dangKy)
                                @php
                                    $activity = $dangKy->hoatdong;
                                    $quyDinh = $activity ? $activity->quydinh : null;
                                    $diem = $quyDinh ? $quyDinh->DiemNhan : 0;
                                @endphp
                                
                                @if($activity && $quyDinh && $diem > 0)
                                <tr>
                                    <td>
                                        <div class="activity-name">{{ $activity->TenHoatDong }}</div>
                                        <div class="activity-description">{{ $quyDinh->TenCongViec }}</div>
                                    </td>
                                    <td>
                                        <span class="activity-type">{{ $activity->LoaiHoatDong }}</span>
                                    </td>
                                    <td class="text-center">
                                        {{ $activity->ThoiGianKetThuc ? \Carbon\Carbon::parse($activity->ThoiGianKetThuc)->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-points">
                                            <i class="fas fa-plus"></i>{{ $diem }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox fa-4x"></i>
                                            <p>Bạn chưa tham gia hoạt động công tác xã hội nào</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection