@extends('layouts.admin')

@section('title', 'Bảng điều khiển - Admin')
@section('page_title', 'Bảng điều khiển hệ thống')

@push('styles')
<style>
    /* Override container padding */
    .container-fluid.py-4 {
        padding-top: 0 !important;
        padding-bottom: 1rem !important;
    }

    /* User stats container - Flexbox */
    .stats-container {
        display: flex;
        gap: 1rem;
        margin-bottom: 3rem;
    }

    .stats-container > .stat-item {
        flex: 1;
        min-width: 0;
    }

    .stat-card {
        border-radius: 16px;
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #fff;
        height: 100%;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 12px;
    }
    
    .stat-icon.primary {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #2563eb;
    }
    
    .stat-icon.success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #059669;
    }
    
    .stat-icon.warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #d97706;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin: 8px 0;
    }
    
    .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }
    
    .chart-card {
        border-radius: 16px;
        border: none;
        background: #fff;
        height: 100%;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    }
    
    .chart-card .card-header {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 20px;
        border-radius: 16px 16px 0 0;
    }
    
    .chart-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    
    .year-filter {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .year-filter select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 6px 12px;
        font-size: 13px;
        min-width: 130px;
    }
    
    .year-filter label {
        font-size: 13px;
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .section-title i {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .stat-total {
        text-align: center;
        margin-bottom: 12px;
    }

    .stat-total .stat-label {
        font-size: 12px;
    }

    .stat-total .stat-value {
        font-size: 24px;
    }

    /* Charts container - Flexbox */
    .charts-container {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .charts-container > .chart-item {
        flex: 1;
        min-width: 0;
    }

    /* Responsive improvements */
    @media (max-width: 991px) {
        .chart-card .card-header {
            flex-direction: column !important;
            align-items: flex-start !important;
        }

        .year-filter {
            width: 100%;
            justify-content: space-between;
            margin-top: 12px;
        }

        .year-filter select {
            flex: 1;
        }

        .charts-container {
            flex-direction: column;
        }
    }

    @media (max-width: 768px) {
        .stats-container {
            gap: 0.75rem;
        }

        .stat-card .card-body {
            padding: 1.5rem 0.75rem !important;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 22px;
        }

        .stat-label {
            font-size: 11px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
    }

    @media (max-width: 576px) {
        .stats-container {
            flex-direction: column;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .stat-value {
            font-size: 20px;
        }

        .stat-label {
            font-size: 10px;
        }
    }
</style>
@endpush

@section('content')
<!-- Thống kê người dùng -->
<div class="section-title">
    <i class="fa-solid fa-users"></i>
    Thống kê người dùng
</div>

<div class="stats-container">
    <!-- Sinh viên -->
    <div class="stat-item">
        <div class="stat-card">
            <div class="card-body text-center p-3">
                <div class="stat-icon primary mx-auto">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div class="stat-label">Tổng số sinh viên</div>
                <div class="stat-value">{{ number_format($tongSinhVien) }}</div>
                <a href="{{ route('admin.sinhvien.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="fa-solid fa-arrow-right me-1"></i>Xem chi tiết
                </a>
            </div>
        </div>
    </div>

    <!-- Giảng viên -->
    <div class="stat-item">
        <div class="stat-card">
            <div class="card-body text-center p-3">
                <div class="stat-icon success mx-auto">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div class="stat-label">Tổng số giảng viên</div>
                <div class="stat-value">{{ number_format($tongGiangVien) }}</div>
                <a href="" class="btn btn-sm btn-outline-success mt-2">
                    <i class="fa-solid fa-arrow-right me-1"></i>Xem chi tiết
                </a>
            </div>
        </div>
    </div>

    <!-- Nhân viên -->
    <div class="stat-item">
        <div class="stat-card">
            <div class="card-body text-center p-3">
                <div class="stat-icon warning mx-auto">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div class="stat-label">Tổng số nhân viên CTSV</div>
                <div class="stat-value">{{ number_format($tongNhanVien) }}</div>
                <a href="" class="btn btn-sm btn-outline-warning mt-2">
                    <i class="fa-solid fa-arrow-right me-1"></i>Xem chi tiết
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Biểu đồ người dùng -->
<div class="chart-card mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="chart-title">
            <i class="fa-solid fa-chart-column me-2 text-primary"></i>
            Thống kê người dùng hệ thống
        </h5>
    </div>
    <div class="card-body p-4">
        <canvas id="chartUsers" height="80"></canvas>
    </div>
</div>

<!-- Thống kê hoạt động -->
<div class="section-title">
    <i class="fa-solid fa-calendar-days"></i>
    Thống kê hoạt động
</div>

<div class="charts-container">
    <!-- DRL -->
    <div class="chart-item">
        <div class="chart-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="chart-title mb-0">
                    <i class="fa-solid fa-clipboard-check me-2" style="color: #ef4444;"></i>
                    Hoạt động Điểm rèn luyện
                </h5>
                <div class="year-filter">
                    <label class="mb-0 text-secondary">Năm học:</label>
                    <select class="form-select form-select-sm" id="yearFilterDRL">
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                {{ $year - 1 }} - {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="stat-total">
                    <div class="stat-label">Tổng hoạt động DRL</div>
                    <div class="stat-value text-danger">{{ number_format($tongDRL) }}</div>
                </div>
                <canvas id="chartDRL" height="180"></canvas>
            </div>
        </div>
    </div>

    <!-- CTXH -->
    <div class="chart-item">
        <div class="chart-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="chart-title mb-0">
                    <i class="fa-solid fa-hand-holding-heart me-2" style="color: #06b6d4;"></i>
                    Hoạt động Cộng tác xã hội
                </h5>
                <div class="year-filter">
                    <label class="mb-0 text-secondary">Năm học:</label>
                    <select class="form-select form-select-sm" id="yearFilterCTXH">
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                {{ $year - 1 }} - {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="stat-total">
                    <div class="stat-label">Tổng hoạt động CTXH</div>
                    <div class="stat-value text-info">{{ number_format($tongCTXH) }}</div>
                </div>
                <canvas id="chartCTXH" height="180"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dữ liệu thực từ Controller
const drlMonthlyData = @json($drlTheoThang ?? array_fill(0, 12, 0));
const ctxhMonthlyData = @json($ctxhTheoThang ?? array_fill(0, 12, 0));

// Biểu đồ người dùng
const ctxUsers = document.getElementById('chartUsers');
new Chart(ctxUsers, {
    type: 'bar',
    data: {
        labels: ['Sinh viên', 'Giảng viên', 'Nhân viên CTSV'],
        datasets: [{
            label: 'Số lượng',
            data: [
                {{ $tongSinhVien ?? 0 }},
                {{ $tongGiangVien ?? 0 }},
                {{ $tongNhanVien ?? 0 }}
            ],
            backgroundColor: [
                'rgba(37, 99, 235, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)'
            ],
            borderColor: [
                'rgba(37, 99, 235, 1)',
                'rgba(16, 185, 129, 1)',
                'rgba(245, 158, 11, 1)'
            ],
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                borderRadius: 8,
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 13
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    font: {
                        size: 12
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 13,
                        weight: '500'
                    }
                },
                grid: {
                    display: false
                }
            }
        }
    }
});

// Dữ liệu mẫu theo tháng cho DRL
const drlDataByMonth = [120, 135, 150, 145, 160, 170, 155, 180, 165, 175, 190, 200];

// Biểu đồ DRL - Line Chart
const ctxDRL = document.getElementById('chartDRL');
const chartDRL = new Chart(ctxDRL, {
    type: 'line',
    data: {
        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        datasets: [{
            label: 'Số hoạt động DRL',
            data: drlDataByMonth,
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            borderColor: 'rgba(239, 68, 68, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: 'rgba(239, 68, 68, 1)',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 10,
                borderRadius: 8,
                titleFont: {
                    size: 13,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 12
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    font: {
                        size: 11
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 11
                    }
                },
                grid: {
                    display: false
                }
            }
        }
    }
});

// Dữ liệu mẫu theo tháng cho CTXH
const ctxhDataByMonth = [80, 90, 100, 95, 110, 120, 105, 130, 115, 125, 140, 150];

// Biểu đồ CTXH - Line Chart
const ctxCTXH = document.getElementById('chartCTXH');
const chartCTXH = new Chart(ctxCTXH, {
    type: 'line',
    data: {
        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        datasets: [{
            label: 'Số hoạt động CTXH',
            data: ctxhDataByMonth,
            backgroundColor: 'rgba(6, 182, 212, 0.1)',
            borderColor: 'rgba(6, 182, 212, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: 'rgba(6, 182, 212, 1)',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 10,
                borderRadius: 8,
                titleFont: {
                    size: 13,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 12
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    font: {
                        size: 11
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 11
                    }
                },
                grid: {
                    display: false
                }
            }
        }
    }
});

// Xử lý filter năm học cho DRL
document.getElementById('yearFilterDRL').addEventListener('change', function() {
    const year = this.value;
    console.log('Lọc DRL theo năm học:', year);
    
    // TODO: Gọi API để lấy dữ liệu theo năm
    // fetch(`/admin/api/drl-stats?year=${year}`)
    //     .then(response => response.json())
    //     .then(data => {
    //         chartDRL.data.datasets[0].data = data;
    //         chartDRL.update();
    //     });
    
    // Tạm thời cập nhật dữ liệu mẫu
    const randomData = Array.from({length: 12}, () => Math.floor(Math.random() * 100) + 100);
    chartDRL.data.datasets[0].data = randomData;
    chartDRL.update('active');
});

// Xử lý filter năm học cho CTXH
document.getElementById('yearFilterCTXH').addEventListener('change', function() {
    const year = this.value;
    console.log('Lọc CTXH theo năm học:', year);
    
    // TODO: Gọi API để lấy dữ liệu theo năm
    // fetch(`/admin/api/ctxh-stats?year=${year}`)
    //     .then(response => response.json())
    //     .then(data => {
    //         chartCTXH.data.datasets[0].data = data;
    //         chartCTXH.update();
    //     });
    
    // Tạm thời cập nhật dữ liệu mẫu
    const randomData = Array.from({length: 12}, () => Math.floor(Math.random() * 80) + 70);
    chartCTXH.data.datasets[0].data = randomData;
    chartCTXH.update('active');
});
</script>
@endpush