@extends('layouts.admin')

@section('title', 'Thống kê điểm CTXH')
@section('page_title', 'Thống kê Cộng Tác Xã Hội')

@push('styles')
<style>
    :root {
        --primary: #06b6d4;
        --primary-dark: #0891b2;
        --primary-light: #67e8f9;
        --success: #10b981;
        --dark: #1e293b;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray-200);
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(8, 145, 178, 0.05));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: var(--primary);
        margin: 0 auto 12px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--dark);
        margin: 8px 0;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }

    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray-200);
        margin-bottom: 24px;
    }

    .chart-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 20px;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom thead {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(8, 145, 178, 0.05));
    }

    .table-custom th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: var(--dark);
        border-bottom: 2px solid var(--gray-200);
    }

    .table-custom td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--gray-200);
    }

    .table-custom tbody tr:hover {
        background: var(--gray-50);
    }

    .badge-rank {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
    }

    .badge-1 {
        background: #fef08a;
        color: #92400e;
    }

    .badge-2 {
        background: #dbeafe;
        color: #075985;
    }

    .badge-3 {
        background: #f3e8ff;
        color: #6b21a8;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-label">Tổng sinh viên</div>
            <div class="stat-value">{{ number_format($tongSinhVien) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-user-minus"></i>
            </div>
            <div class="stat-label">Chưa có hoạt động</div>
            <div class="stat-value">{{ number_format($sinhVienChuaCoHoatDong) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-hand-holding-heart"></i>
            </div>
            <div class="stat-label">Tổng hoạt động</div>
            <div class="stat-value">{{ number_format($tongHoatDong) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-star"></i>
            </div>
            <div class="stat-label">Tổng lần tham gia</div>
            <div class="stat-value">{{ number_format($tongDiem) }}</div>
        </div>
    </div>

    <!-- Biểu đồ phân bố tham gia -->
    <div class="chart-card">
        <div class="chart-title">
            <i class="fa-solid fa-chart-pie me-2" style="color: var(--primary);"></i>
            Phân bố sinh viên theo lần tham gia hoạt động
        </div>
        <canvas id="chartDiemRanges" style="max-height: 300px;"></canvas>
    </div>

    <!-- Biểu đồ hoạt động theo tháng -->
    <div class="chart-card">
        <div class="chart-title">
            <i class="fa-solid fa-chart-line me-2" style="color: var(--primary);"></i>
            Hoạt động CTXH theo tháng
        </div>
        <canvas id="chartHoatDongByMonth" style="max-height: 300px;"></canvas>
    </div>

    <!-- Bảng thống kê theo lớp -->
    <div class="chart-card">
        <div class="chart-title">
            <i class="fa-solid fa-table me-2" style="color: var(--primary);"></i>
            Thống kê điểm trung bình theo lớp
        </div>
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Xếp hạng</th>
                    <th>Tên lớp</th>
                    <th>Số sinh viên</th>
                    <th>Lần tham gia</th>
                </tr>
            </thead>
            <tbody>
                @forelse($diemTrungBinhByLop as $key => $lop)
                <tr>
                    <td>
                        @if($key == 0)
                            <span class="badge-rank badge-1">🥇 1</span>
                        @elseif($key == 1)
                            <span class="badge-rank badge-2">🥈 2</span>
                        @elseif($key == 2)
                            <span class="badge-rank badge-3">🥉 3</span>
                        @else
                            <span class="badge-rank" style="background: var(--gray-100); color: var(--dark);">{{ $key + 1 }}</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $lop->TenLop }}</strong>
                    </td>
                    <td>{{ number_format($lop->sinhVien) }}</td>
                    <td>
                        <strong style="color: var(--primary);">{{ number_format($lop->soLanThamGia) }}</strong>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 32px;">
                        <i class="fa-solid fa-inbox" style="font-size: 32px; color: #cbd5e1; margin-bottom: 12px;"></i>
                        <p style="color: #64748b;">Chưa có dữ liệu thống kê</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ phân bố điểm
    const diemRanges = {!! json_encode($diemRanges) !!};
    const ctx1 = document.getElementById('chartDiemRanges').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['0-3 lần', '3-6 lần', '6-10 lần', '10+ lần'],
            datasets: [{
                label: 'Số sinh viên',
                data: [
                    diemRanges['0-3'],
                    diemRanges['3-6'],
                    diemRanges['6-10'],
                    diemRanges['10+']
                ],
                backgroundColor: [
                    'rgba(6, 182, 212, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(168, 85, 247, 0.8)',
                    'rgba(59, 130, 246, 0.8)'
                ],
                borderColor: [
                    'rgba(6, 182, 212, 1)',
                    'rgba(34, 197, 94, 1)',
                    'rgba(168, 85, 247, 1)',
                    'rgba(59, 130, 246, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '500'
                        }
                    }
                }
            }
        }
    });

    // Biểu đồ hoạt động theo tháng
    const ctxhByMonth = {!! json_encode(array_values($ctxhByMonth)) !!};
    const ctx2 = document.getElementById('chartHoatDongByMonth').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            datasets: [{
                label: 'Số hoạt động',
                data: ctxhByMonth,
                backgroundColor: 'rgba(6, 182, 212, 0.8)',
                borderColor: 'rgba(6, 182, 212, 1)',
                borderWidth: 2,
                borderRadius: 8,
                hoverBackgroundColor: 'rgba(8, 145, 178, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        padding: 15,
                        font: {
                            size: 13,
                            weight: '500'
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
