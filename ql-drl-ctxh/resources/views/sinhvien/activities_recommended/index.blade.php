@extends('layouts.sinhvien')
@section('title', 'Hoạt Động Được Đề Xuất')

@push('styles')
<style>
    :root {
        --primary: #667eea;
        --primary-dark: #5568c7;
        --secondary: #764ba2;
        --accent: #f5576c;
        --accent-dark: #d84a5f;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --card-shadow: 0 10px 40px rgba(0, 0, 0, .08);
        --card-hover-shadow: 0 20px 50px rgba(0, 0, 0, .12);
        --transition: all .4s cubic-bezier(.4, 0, .2, 1)
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        min-height: 100vh
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 2.5rem 0;
        margin-bottom: 2rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, .3);
        position: relative;
        overflow: hidden
    }

    .page-header h3 {
        color: #fff;
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0, 0, 0, .2);
    }

    .page-header .subtitle {
        color: rgba(255, 255, 255, .9);
        font-size: 1rem;
        margin-top: .5rem;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        border-left: 5px solid var(--primary);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }

    .stat-card.warning {
        border-left-color: var(--warning);
    }

    .stat-card.danger {
        border-left-color: var(--danger);
    }

    .stat-card.info {
        border-left-color: var(--info);
    }

    .stat-card h5 {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card .number {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
    }

    .recommendation-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
        transition: var(--transition);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .recommendation-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
    }

    .recommendation-card.ctxh::before {
        background: linear-gradient(90deg, var(--accent), #ff6b9d);
    }

    .recommendation-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
        border-color: rgba(102, 126, 234, .2);
    }

    .recommendation-card.ctxh:hover {
        border-color: rgba(245, 87, 108, .2);
    }

    .rec-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .rec-title {
        flex: 1;
    }

    .rec-title h5 {
        font-size: 1.2rem;
        font-weight: 800;
        margin: 0 0 0.5rem 0;
        color: #1e293b;
    }

    .rec-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .rec-badge.drl {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: #fff;
    }

    .rec-badge.ctxh {
        background: linear-gradient(135deg, var(--accent) 0%, #ff6b9d 100%);
        color: #fff;
    }

    .rec-score {
        text-align: center;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 800;
        font-size: 1.5rem;
    }

    .rec-reasons {
        margin: 1rem 0;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .reason-tag {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    .reason-tag.priority {
        background: linear-gradient(135deg, var(--warning), #fbbf24);
        color: #fff;
        border: none;
    }

    .rec-meta {
        font-size: 0.9rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .rec-meta i {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background: #f1f5f9;
        color: var(--primary);
        font-size: 0.75rem;
    }

    .rec-action {
        margin-top: 1rem;
        display: flex;
        gap: 0.75rem;
    }

    .btn-view {
        flex: 1;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-view.drl {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: #fff;
    }

    .btn-view.drl:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, .4);
    }

    .btn-view.ctxh {
        background: linear-gradient(135deg, var(--accent) 0%, #ff6b9d 100%);
        color: #fff;
    }

    .btn-view.ctxh:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(245, 87, 108, .4);
    }

    .empty-state {
        background: #fff;
        border-radius: 15px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: var(--card-shadow);
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
        margin: 0;
    }

    @media (max-width: 768px) {
        .page-header h3 {
            font-size: 1.5rem;
        }

        .rec-header {
            flex-direction: column;
        }

        .rec-action {
            flex-direction: column;
        }

        .btn-view {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid" style="max-width:1200px;margin:0 auto;padding:2rem 1rem;">

    <div class="page-header">
        <div class="container">
            <h3><i class="fas fa-magic me-3"></i>Hoạt Động Được Đề Xuất</h3>
            <p class="subtitle">Những hoạt động phù hợp với bạn được hệ thống thông minh gợi ý</p>
        </div>
    </div>

    {{-- Thống kê --}}
    <div class="stats-container">
        <div class="stat-card danger">
            <h5><i class="fas fa-exclamation-circle me-2"></i>Điểm RL Thấp</h5>
            <p class="number">{{ $countLowRL }}</p>
            <small class="text-muted">hoạt động đề xuất</small>
        </div>
        <div class="stat-card warning">
            <h5><i class="fas fa-heart me-2"></i>CTXH Chưa Đủ</h5>
            <p class="number">{{ $countIncompleteCTXH }}</p>
            <small class="text-muted">hoạt động đề xuất</small>
        </div>
        <div class="stat-card info">
            <h5><i class="fas fa-graduation-cap me-2"></i>Sắp Tốt Nghiệp</h5>
            <p class="number">{{ $countGraduatingSoon }}</p>
            <small class="text-muted">hoạt động đề xuất</small>
        </div>
    </div>

    {{-- Danh sách đề xuất --}}
    @forelse($recommendations as $rec)
        @php
            $activity = $rec->activity_type === 'drl' ? $rec->hoatDongDRL : $rec->hoatDongCTXH;
            $reasons = array_map('trim', explode(',', $rec->recommendation_reason ?? ''));
        @endphp

        @if($activity)
        <div class="recommendation-card {{ strtolower($rec->activity_type) }}-card">
            <div class="rec-header">
                <div class="rec-title">
                    <h5>{{ $activity->TenHoatDong }}</h5>
                    <span class="rec-badge {{ strtolower($rec->activity_type) }}">
                        {{ $rec->activity_type === 'drl' ? 'Rèn Luyện' : 'CTXH' }}
                    </span>
                </div>
                <div class="rec-score">
                    {{ round($rec->recommendation_score) }}/100
                </div>
            </div>

            {{-- Lý do đề xuất --}}
            <div class="rec-reasons">
                @foreach($reasons as $reason)
                    @php
                        $reasonLabel = match($reason) {
                            'low_rl_score' => 'Điểm RL Thấp',
                            'incomplete_ctxh' => 'CTXH Chưa Đủ',
                            'graduating_soon' => 'Sắp Tốt Nghiệp',
                            'preference_match' => 'Phù Hợp Sở Thích',
                            'red_address' => 'Địa Chỉ Đỏ',
                            default => $reason
                        };
                    @endphp
                    <span class="reason-tag {{ in_array($reason, ['low_rl_score', 'graduating_soon']) ? 'priority' : '' }}">
                        {{ $reasonLabel }}
                    </span>
                @endforeach
                <span class="reason-tag priority">Priority {{ $rec->priority }}</span>
            </div>

            {{-- Mô tả chi tiết --}}
            @if($activity->MoTa)
            <div style="margin: 1rem 0; padding: 1rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid var(--primary);">
                <strong style="color: var(--primary); display: block; margin-bottom: 0.5rem;">📋 Mô tả chi tiết:</strong>
                <p style="color: #555; margin: 0; line-height: 1.6;">{{ $activity->MoTa }}</p>
            </div>
            @endif

            {{-- Loại hoạt động --}}
            @if($activity->LoaiHoatDong)
            <div style="margin: 0.75rem 0; padding: 0.75rem; background: #f1f5f9; border-radius: 8px;">
                <strong style="color: #64748b;">📂 Loại hoạt động:</strong>
                <span style="margin-left: 0.5rem; color: #475569;">{{ $activity->LoaiHoatDong }}</span>
            </div>
            @endif

            {{-- Thông tin hoạt động --}}
            <div class="row" style="margin-top: 1rem; font-size: 0.9rem;">
                <div class="col-md-6">
                    <div class="rec-meta">
                        <i class="fas fa-calendar-alt"></i>
                        <span><strong>Bắt đầu:</strong> {{ optional($activity->ThoiGianBatDau)->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="rec-meta">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><strong>Địa điểm:</strong> {{ $activity->DiaDiem }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="rec-meta">
                        <i class="fas fa-trophy"></i>
                        <span><strong>Điểm:</strong> +{{ $activity->quydinh->DiemNhan ?? 0 }}</span>
                    </div>
                    <div class="rec-meta">
                        <i class="fas fa-users"></i>
                        <span><strong>Chỗ trống:</strong> {{ $activity->SoLuong }} chỗ</span>
                    </div>
                </div>
            </div>

            {{-- Nút action --}}
            <div class="rec-action">
                @if($rec->activity_type === 'drl')
                    <form action="{{ route('sinhvien.dangky.drl', $activity->MaHoatDong) }}" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" class="btn-view drl" style="width: 100%;">
                            <i class="fas fa-user-plus me-2"></i>Đăng Ký Ngay
                        </button>
                    </form>
                @else
                    <form action="{{ route('sinhvien.dangky.ctxh', $activity->MaHoatDong) }}" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" class="btn-view ctxh" style="width: 100%;">
                            <i class="fas fa-user-plus me-2"></i>Đăng Ký Ngay
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endif
    @empty
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h5>Chưa có đề xuất nào</h5>
            <p>Hiện tại hệ thống chưa có đề xuất hoạt động phù hợp cho bạn. Vui lòng quay lại sau!</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($recommendations->count() > 0)
    <div style="margin-top: 2rem;">
        {{ $recommendations->links() }}
    </div>
    @endif

</div>
@endsection
