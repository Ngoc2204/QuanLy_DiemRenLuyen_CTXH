@extends('layouts.sinhvien')

@section('title', 'Thông tin sinh viên')

@section('content')

<style>
  :root {
    --primary: #667eea;
    --secondary: #764ba2;
    --accent: #f5576c;
    --success: #28a745;
    --warning: #ffc107;
    --info: #17a2b8;
    --light-bg: #f8f9ff;
    --card-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .stat-card.orange::before {
    background: #fd7e14;
    /* Màu cam */
  }

  .stat-card.orange .stat-value {
    background: linear-gradient(135deg, #fd7e14, #f97316);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .stat-card.orange .stat-badge {
    background: rgba(253, 126, 20, 0.1);
    /* Nền cam nhạt */
    color: #fd7e14;
    /* Chữ cam đậm */
  }



  .profile-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: var(--card-shadow);
    position: relative;
    overflow: hidden;
  }

  .profile-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
  }

  .profile-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 2rem;
  }

  .profile-avatar {
    width: 140px;
    height: 140px;
    border-radius: 20px;
    border: 4px solid rgba(255, 255, 255, 0.3);
    object-fit: cover;
    flex-shrink: 0;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
  }

  .profile-info h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
  }

  .profile-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    font-size: 0.95rem;
  }

  .profile-meta-item {
    display: flex;
    gap: 0.5rem;
    align-items: center;
  }

  .profile-meta-item i {
    opacity: 0.8;
    width: 20px;
  }

  .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
  }

  .stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.8rem;
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    border: 1px solid rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
    height: 100%;
    /* chiếm toàn bộ chiều cao cột */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
  }

  .stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary);
  }

  .stat-card.secondary::before {
    background: var(--accent);
  }

  .stat-card.success::before {
    background: var(--success);
  }

  .stat-label {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #718096;
    font-weight: 600;
    margin-bottom: 0.8rem;
  }

  .stat-value {
    font-size: 2.8rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.8rem;
  }

  .stat-card.secondary .stat-value {
    background: linear-gradient(135deg, var(--accent), #ff6b9d);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .stat-card.success .stat-value {
    background: linear-gradient(135deg, var(--success), #48bb78);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .stat-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    background: rgba(102, 126, 234, 0.1);
    color: var(--primary);
  }

  .stat-card.secondary .stat-badge {
    background: rgba(245, 87, 108, 0.1);
    color: var(--accent);
  }

  .stat-card.success .stat-badge {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
  }

  .stat-card-link {
    text-decoration: none;
    color: inherit;
    /* Kế thừa màu chữ từ cha */
  }

  .stat-card-link:hover {
    text-decoration: none;
    color: inherit;
  }

  .quick-actions {
    margin-bottom: 2rem;
  }

  .section-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1.2rem;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .section-title i {
    color: var(--primary);
  }

  .action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 1rem;
  }

  .action-btn {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.3rem 0.8rem;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.8rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  }

  .action-btn:hover {
    border-color: var(--primary);
    background: rgba(102, 126, 234, 0.05);
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
  }

  .action-btn i {
    font-size: 1.8rem;
    color: var(--primary);
  }

  .action-btn span {
    font-size: 0.85rem;
    font-weight: 600;
    color: #2d3748;
  }

  .table-section {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
  }

  .table-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0e7ff 100%);
  }

  .table-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 1.1rem;
    color: #2d3748;
  }

  .table-header .form-select {
    width: 270px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
  }

  .table {
    margin: 0;
    font-size: 0.95rem;
  }

  .table thead th {
    background: #f8f9fa;
    border-bottom: 2px solid #e2e8f0;
    font-weight: 700;
    color: #4a5568;
    padding: 1rem;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
  }

  .table tbody td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: middle;
  }

  .table tbody tr:hover {
    background: rgba(102, 126, 234, 0.02);
  }

  .badge {
    padding: 0.45em 0.75em;
    font-size: 0.85em;
    font-weight: 600;
    border-radius: 20px;
  }

  .badge-success {
    background: rgba(40, 167, 69, 0.15);
    color: var(--success);
  }

  .badge-warning {
    background: rgba(255, 193, 7, 0.15);
    color: #ff9800;
  }

  .badge-danger {
    background: rgba(220, 53, 69, 0.15);
    color: #dc3545;
  }

  .badge-info {
    background: rgba(23, 162, 184, 0.15);
    color: #17a2b8;
  }

  .points-highlight {
    color: var(--primary);
    font-weight: 700;
    font-size: 1.05rem;
  }

  .btn-action {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #e2e8f0;
    background: white;
    color: var(--primary);
    cursor: pointer;
    transition: var(--transition);
  }

  .btn-action:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
  }

  .table-footer {
    background: #f8f9fa;
    padding: 1rem;
    font-weight: 600;
    border-top: 2px solid #e2e8f0;
    text-align: right;
    color: var(--primary);
  }

  .charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
  }

  .chart-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(0, 0, 0, 0.05);
  }

  .chart-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #2d3748;
  }

  @media (max-width: 768px) {
    .profile-content {
      flex-direction: column;
      text-align: center;
    }

    .profile-avatar {
      width: 100px;
      height: 100px;
    }

    .profile-info h2 {
      font-size: 1.5rem;
    }

    .stat-value {
      font-size: 2rem;
    }

    .action-grid {
      grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
    }

    .charts-grid {
      grid-template-columns: 1fr;
    }

    .stat-grid {
      grid-template-columns: 1fr;
    }

    .action-grid {
      grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
    }

    .action-grid {
      grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
    }
  }
</style>

<div class="container-fluid" style="max-width: 1400px; margin: 0 auto; padding: 2rem 1rem;">

  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-content">
      <img src="https://ui-avatars.com/api/?name={{ urlencode($student->HoTen) }}&background=667eea&color=fff&size=150"
        alt="Avatar" class="profile-avatar">
      <div class="profile-info flex-grow-1">
        <h2>{{ $student->HoTen }}</h2>
        <div class="profile-meta">
          <div class="profile-meta-item">
            <i class="fas fa-id-card"></i>
            <span><strong>MSSV:</strong> {{ $student->MSSV }}</span>
          </div>
          <div class="profile-meta-item">
            <i class="fas fa-venus-mars"></i>
            <span><strong>Giới tính:</strong> {{ $student->GioiTinh }}</span>
          </div>
          <div class="profile-meta-item">
            <i class="fas fa-calendar"></i>
            <span><strong>Sinh:</strong> {{ $student->NgaySinh ? date('d/m/Y', strtotime($student->NgaySinh)) : 'N/A' }}</span>
          </div>
          <div class="profile-meta-item">
            <i class="fas fa-graduation-cap"></i>
            <span><strong>Lớp:</strong> {{ $student->lop->TenLop ?? 'N/A' }}</span>
          </div>
          <div class="profile-meta-item">
            <i class="fas fa-book"></i>
            <span><strong>Ngành:</strong> {{ $student->lop->khoa->TenKhoa ?? 'N/A' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="stat-grid">
    <a href="{{ route('sinhvien.thongbao_hoatdong') }}" class="stat-card-link">
      <div class="stat-card">
        <div class="stat-label"><i class="fas fa-bell me-1"></i> Thông báo hoạt động</div>
        <div class="stat-value">{{ $notifications_count }}</div>
        <div class="stat-badge">Chưa đọc</div>
      </div>
    </a>
    <a href="{{ route('sinhvien.lich_tuan') }}" class="stat-card-link">
      <div class="stat-card orange">
        <div class="stat-label"><i class="fas fa-calendar me-1"></i> Hoạt động tuần này</div>
        <div class="stat-value">{{ $weekly_activities }}</div>
        <div class="stat-badge">Sắp diễn ra</div>
      </div>
    </a>
    <a href="{{ route('sinhvien.diem_ren_luyen') }}" class="stat-card-link">
      <div class="stat-card success">
        <div class="stat-label"><i class="fas fa-star me-1"></i> Điểm rèn luyện {{ $currentHocKy->TenHocKy ?? '' }}</div>
        <div class="stat-value">{{ $training_score }}</div>
        <div class="stat-badge">{{ $training_rank }}</div>
      </div>
    </a>
    <a href="{{ route('sinhvien.diem_cong_tac_xa_hoi') }}" class="stat-card-link">
      <div class="stat-card secondary">
        <div class="stat-label"><i class="fas fa-heart me-1"></i> Điểm công tác xã hội</div>
        <div class="stat-value">{{ $social_score }}</div>
        <div class="stat-badge">{{ $has_red_activity ? 'Đã có Địa chỉ đỏ' : 'Chưa có Địa chỉ đỏ' }}</div>
      </div>
    </a>
  </div>

  <!-- Quick Actions -->
  <div class="quick-actions">
    <div class="section-title">
      <i class="fas fa-lightning-bolt"></i> Chức năng nhanh
    </div>
    <div class="action-grid">
      <a href="{{ route('sinhvien.quanly_dangky.index') }}" class="action-btn">
        <i class="fas fa-clipboard-list"></i>
        <span>Quản lý đăng ký</span>
      </a>
      <a href="#" class="action-btn">
        <i class="fas fa-history"></i>
        <span>Lịch sử hoạt động</span>
      </a>
      <a href="{{ route('sinhvien.diem_ren_luyen') }}" class="action-btn">
        <i class="fas fa-star"></i>
        <span>Điểm rèn luyện</span>
      </a>
      <a href="{{ route('sinhvien.diem_cong_tac_xa_hoi') }}" class="action-btn">
        <i class="fas fa-heart"></i>
        <span>Công tác xã hội</span>
      </a>
      <a href="#" class="action-btn">
        <i class="fas fa-credit-card"></i>
        <span>Thanh toán</span>
      </a>

    </div>
  </div>

  <!-- Training Activities Table -->
  <div class="table-section">
    <div class="table-header">
      <h5><i class="fas fa-list me-2"></i>Hoạt động điểm rèn luyện</h5>
      <select class="form-select form-select-sm">
        <option>{{ $currentHocKy->TenHocKy ?? 'Học kỳ hiện tại' }}</option>
      </select>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>STT</th>
            <th>Tên hoạt động</th>
            <th>Ngày tham gia</th>
            <th class="text-center">Điểm</th>
            <th class="text-center">Trạng thái</th>
            <th class="text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($activities as $index => $activity)
          <tr>
            <td class="fw-bold">{{ $index + 1 }}</td>
            <td>{{ $activity->hoatdong->TenHoatDong ?? 'N/A' }}</td>
            <td>{{ $activity->hoatdong->ThoiGianBatDau ? date('d/m/Y', strtotime($activity->hoatdong->ThoiGianBatDau)) : 'N/A' }}</td>
            <td class="text-center"><span class="points-highlight">+{{ $activity->hoatdong->quydinhdiem->DiemNhan ?? 0 }}</span></td>
            <td class="text-center">
              @if ($activity->TrangThaiDangKy == 'Đã duyệt' || $activity->TrangThaiThamGia == 'Đã tham gia')
              <span class="badge badge-success">
                <i class="fas fa-check-circle me-1"></i> {{ $activity->TrangThaiDangKy }}
              </span>
              @else
              <span class="badge badge-warning">
                <i class="fas fa-clock me-1"></i> {{ $activity->TrangThaiDangKy ?? $activity->TrangThaiThamGia }}
              </span>
              @endif
            </td>
            <td class="text-center">
              <a href="#" class="btn-action" title="Xem chi tiết">
                <i class="fas fa-eye"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center p-4 text-muted">Chưa tham gia hoạt động rèn luyện nào trong học kỳ này.</td>
          </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr class="table-footer">
            <td colspan="3">Tổng cộng:</td>
            <td class="text-center">+{{ $activities->sum(fn($a) => $a->hoatdong->quydinhdiem->DiemNhan ?? 0) }} điểm</td>
            <td colspan="2"></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <!-- Social Activities Table -->
  <div class="table-section">
    <div class="table-header">
      <h5><i class="fas fa-heart me-2" style="color: #dc3545;"></i>Hoạt động công tác xã hội</h5>
      <span class="badge bg-success text-white">Tổng: {{ $social_activities->count() }} hoạt động</span>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>STT</th>
            <th>Tên hoạt động</th>
            <th>Loại hoạt động</th>
            <th>Ngày tham gia</th>
            <th class="text-center">Điểm</th>
            <th class="text-center">Trạng thái</th>
            <th class="text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($social_activities as $index => $activity)
          <tr>
            <td class="fw-bold">{{ $index + 1 }}</td>
            <td>{{ $activity->hoatdong->TenHoatDong ?? 'N/A' }}</td>
            <td>
              @if($activity->hoatdong->LoaiHoatDong == 'Địa chỉ đỏ')
              <span class="badge badge-danger">{{ $activity->hoatdong->LoaiHoatDong }}</span>
              @elseif($activity->hoatdong->LoaiHoatDong == 'Môi trường')
              <span class="badge badge-info">{{ $activity->hoatdong->LoaiHoatDong }}</span>
              @else
              <span class="badge" style="background: rgba(108,117,125,0.15); color: #6c757d;">{{ $activity->hoatdong->LoaiHoatDong ?? 'Khác' }}</span>
              @endif
            </td>
            <td>{{ $activity->hoatdong->ThoiGianBatDau ? date('d/m/Y', strtotime($activity->hoatdong->ThoiGianBatDau)) : 'N/A' }}</td>
            <td class="text-center"><span class="points-highlight">+{{ $activity->hoatdong->quydinhdiem->DiemNhan ?? 0 }}</span></td>
            <td class="text-center">
              @if ($activity->TrangThaiDangKy == 'Đã duyệt')
              <span class="badge badge-success">
                <i class="fas fa-check-circle me-1"></i> {{ $activity->TrangThaiDangKy }}
              </span>
              @else
              <span class="badge badge-warning">
                <i class="fas fa-clock me-1"></i> {{ $activity->TrangThaiDangKy ?? $activity->TrangThaiThamGia }}
              </span>
              @endif
            </td>
            <td class="text-center">
              <a href="#" class="btn-action" title="Xem chi tiết">
                <i class="fas fa-eye"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center p-4 text-muted">Chưa tham gia hoạt động công tác xã hội nào.</td>
          </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr class="table-footer">
            <td colspan="4">Tổng cộng:</td>
            <td class="text-center">+{{ $social_activities->sum(fn($a) => $a->hoatdong->quydinhdiem->DiemNhan ?? 0) }} điểm
              @if($social_score < 170)
                (Còn thiếu {{ 170 - $social_score }} điểm)
                @endif
                </td>
            <td colspan="2"></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <!-- Statistics Section -->
  <div id="statisticsSection" style="display: none; margin-bottom: 2rem;">
    <div class="charts-grid">
      <div class="chart-card">
        <h4 class="chart-title">
          <i class="fas fa-chart-bar me-2" style="color: var(--primary);"></i> Thống kê điểm rèn luyện
        </h4>
        <div style="height: 350px;">
          <canvas id="trainingChart"></canvas>
        </div>
      </div>

      <div class="chart-card">
        <h4 class="chart-title">
          <i class="fas fa-chart-pie me-2" style="color: var(--success);"></i> Thống kê điểm công tác xã hội
        </h4>
        <div style="height: 350px;">
          <canvas id="socialChart"></canvas>
        </div>
      </div>
    </div>
  </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
  const trainingChartData = @json($training_chart_js ?? []);
  const socialChartData = @json($social_chart_js ?? []);

  const toggleButton = document.getElementById('toggleStatistics');
  const section = document.getElementById('statisticsSection');
  let chartLoaded = false;

  toggleButton.addEventListener('click', () => {
    if (section.style.display === 'none') {
      section.style.display = 'block';
      if (!chartLoaded) {
        renderCharts();
        chartLoaded = true;
      }
      window.scrollTo({
        top: section.offsetTop - 50,
        behavior: 'smooth'
      });
    } else {
      section.style.display = 'none';
    }
  });

  function renderCharts() {
    const ctx = document.getElementById('trainingChart');
    if (ctx && trainingChartData.labels) {
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: trainingChartData.labels,
          datasets: [{
              label: 'Học kỳ 1',
              data: trainingChartData.hk1_data,
              backgroundColor: 'rgba(102, 126, 234, 0.8)',
              borderColor: 'rgba(102, 126, 234, 1)',
              borderWidth: 2,
              borderRadius: 6
            },
            {
              label: 'Học kỳ 2',
              data: trainingChartData.hk2_data,
              backgroundColor: 'rgba(255, 193, 7, 0.8)',
              borderColor: 'rgba(255, 193, 7, 1)',
              borderWidth: 2,
              borderRadius: 6
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
              ticks: {
                stepSize: 10
              }
            }
          },
          plugins: {
            legend: {
              display: true,
              position: 'top'
            },
            tooltip: {
              backgroundColor: 'rgba(0,0,0,0.8)',
              callbacks: {
                label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y} điểm`
              }
            }
          }
        }
      });
    }

    const socialCtx = document.getElementById('socialChart');
    if (socialCtx && socialChartData.dat) {
      new Chart(socialCtx, {
        type: 'doughnut',
        data: {
          labels: ['Đã đạt', 'Còn thiếu'],
          datasets: [{
            data: [socialChartData.dat, socialChartData.thieu],
            backgroundColor: ['#4caf50', '#f9c74f'],
            borderColor: '#fff',
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '70%',
          plugins: {
            legend: {
              position: 'bottom'
            },
            tooltip: {
              callbacks: {
                label: ctx => `${ctx.label}: ${ctx.parsed} điểm`
              }
            }
          }
        }
      });
    }
  }
</script>
@endpush

@endsection