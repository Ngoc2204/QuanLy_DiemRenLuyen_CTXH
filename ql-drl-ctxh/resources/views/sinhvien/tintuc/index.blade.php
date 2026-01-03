@extends('layouts.sinhvien')
@section('title', $pageTitle ?? 'Thông Báo & Lịch Sử Cá Nhân')

@push('styles')
<style>
    /* Style cơ bản */
    :root {
        --primary: #667eea;
        --secondary: #764ba2;
        --accent: #f5576c;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
    }
    
    .thongbao-item {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
        padding: 0;
        overflow: hidden;
        border-left: 5px solid #667eea;
        transition: all 0.3s ease;
    }
    
    .thongbao-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    /* Phân biệt màu cho các loại thông báo */
    .thongbao-item.DRL { border-left-color: var(--primary); }
    .thongbao-item.CTXH { border-left-color: var(--accent); }
    .thongbao-item.DIEM { border-left-color: var(--success); }
    .thongbao-item.NHACNHO { border-left-color: var(--warning); }

    .thongbao-header {
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
    }

    .thongbao-header .badge {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.5em 1em;
    }

    .thongbao-item h5 {
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
        padding: 1rem 1.5rem 0 1.5rem;
    }
    .thongbao-item .thongbao-date {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 1rem;
        padding: 0 1.5rem;
        display: block;
    }
    .thongbao-item .thongbao-content {
        color: #555;
        line-height: 1.6;
        padding: 0 1.5rem 1.5rem 1.5rem;
    }

    /* Styles cho bộ lọc (custom-tabs) */
    .custom-tabs {
        background: white;
        border-radius: 50px;
        padding: 0.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        display: inline-flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }

    .custom-tabs .nav-link {
        border: 0;
        border-radius: 50px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .custom-tabs .nav-link.active {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .custom-tabs .nav-link#ctxh-tab.active {
        background: linear-gradient(135deg, var(--accent) 0%, #ff6b9d 100%);
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
    }

    /* Styles cho phân trang */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .pagination .page-item {
        list-style: none;
    }

    .pagination .page-link {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        height: 44px;
        padding: 0.5rem 0.75rem;
        margin: 0;
        font-weight: 600;
        line-height: 1.25;
        color: #667eea;
        background-color: #fff;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        cursor: pointer;
    }

    .pagination .page-link:hover {
        color: #667eea;
        background-color: rgba(102, 126, 234, 0.08);
        border-color: #667eea;
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .pagination .page-item.disabled .page-link {
        color: #cbd5e1;
        pointer-events: none;
        background-color: #f3f4f6;
        border-color: #e5e7eb;
        opacity: 0.5;
    }

    .pagination .page-link:focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    /* Thêm info text */
    .pagination-info {
        text-align: center;
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 1rem;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="container-fluid" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    
    <div class="page-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2.5rem 0; margin-bottom: 2rem; border-radius: 20px;">
        <div class="container">
            <h3 style="color: white; font-weight: 800; margin: 0; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
                <i class="fa fa-bell me-3"></i>{{ $pageTitle ?? 'Thông Báo Cá Nhân' }}
            </h3>
            <p style="color: rgba(255, 255, 255, 0.9); font-size: 1rem; margin-top: 0.5rem; margin-bottom: 0;">
                Xem lịch sử duyệt đơn, điều chỉnh điểm và nhắc nhở hoạt động của bạn.
            </p>
        </div>
    </div>

    @if($thongBaos->isEmpty())
        <div class="empty-state" style="background: white; border-radius: 20px; padding: 4rem 2rem; text-align: center;">
            <i class="fas fa-inbox" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
            <h5>Hộp thư trống</h5>
            <p style="color: #94a3b8; margin: 0;">Không có thông báo nào khớp với bộ lọc của bạn.</p>
        </div>
    @else
        @foreach($thongBaos as $thongBao)
            @php
                // Xác định màu sắc/icon dựa trên loại thông báo
                $typeClass = $thongBao->Loai; // DRL, CTXH, DIEM, NHACNHO
                $badgeType = 'bg-primary';
                $icon = 'fas fa-info-circle';
                $label = 'Thông báo';
                
                if ($thongBao->Loai == 'CTXH') {
                    $badgeType = 'bg-danger';
                    $label = 'CTXH';
                } elseif ($thongBao->Loai == 'DIEM') {
                    $badgeType = 'bg-success';
                    $icon = 'fas fa-medal';
                    $label = 'Điểm RL';
                } elseif ($thongBao->Loai == 'NHACNHO') {
                    $badgeType = 'bg-warning text-dark';
                    $icon = 'fas fa-clock';
                    $label = 'Nhắc nhở';
                }
                
                // Trạng thái cụ thể
                $statusColor = 'text-primary';
                $statusIcon = 'fas fa-check-circle';

                if (str_contains($thongBao->TrangThai, 'từ chối') || str_contains($thongBao->TrangThai, 'Bị từ chối')) {
                    $statusColor = 'text-danger';
                    $statusIcon = 'fas fa-times-circle';
                } elseif (str_contains($thongBao->TrangThai, 'Sắp diễn ra') || str_contains($thongBao->TrangThai, 'chốt')) {
                    $statusColor = 'text-success';
                    $statusIcon = 'fas fa-check-circle';
                }
            @endphp
            
            <div class="thongbao-item {{ $typeClass }}">
                
                <div class="thongbao-header">
                    <span class="badge {{ $badgeType }}">{{ $label }}</span>
                    
                    <span style="font-weight: 700;" class="{{ $statusColor }}">
                        <i class="{{ $statusIcon }} me-1"></i>
                        {{ $thongBao->TrangThai }}
                    </span>
                </div>

                <h5><i class="{{ $icon }} me-2" style="color: var(--{{ strtolower(substr($badgeType, 3)) }});"></i>{{ $thongBao->TieuDe }}</h5>
                
                <p class="thongbao-date">
                    <i class="fa fa-calendar-alt me-2"></i> 
                    Thời gian: {{ \Carbon\Carbon::parse($thongBao->ThoiGian)->format('H:i, d/m/Y') }}
                </p>

                <div class="thongbao-content">
                    <p>{!! nl2br(e($thongBao->NoiDung)) !!}</p>
                </div>
            </div>
        @endforeach



        {{-- Hiển thị link phân trang --}}
        <div class="d-flex justify-content-center">
            {{ $thongBaos->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>
@endsection