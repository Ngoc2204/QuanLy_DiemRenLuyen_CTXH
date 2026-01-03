

<?php $__env->startSection('title', 'Thông tin sinh viên'); ?>

<?php $__env->startSection('content'); ?>

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
  }

  .stat-card.orange .stat-value {
    background: linear-gradient(135deg, #fd7e14, #f97316);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .stat-card.orange .stat-badge {
    background: rgba(253, 126, 20, 0.1);
    color: #fd7e14;
  }

  .profile-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 20px;
    padding: 2rem;
    color: #fff;
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
    background: #fff;
    border-radius: 16px;
    padding: 1.8rem;
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    border: 1px solid rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
    height: 100%;
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
    background: #fff;
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
    background: #fff;
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
    background: #fff;
    color: var(--primary);
    cursor: pointer;
    transition: var(--transition);
  }

  .btn-action:hover {
    background: var(--primary);
    color: #fff;
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
    background: #fff;
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
  }
</style>

<div class="container-fluid" style="max-width: 1400px; margin: 0 auto; padding: 2rem 1rem;">

  
  <div class="profile-header">
    <div class="profile-content">
      <img
        src="<?php echo e($student->taikhoan->Avatar ? asset('storage/' . $student->taikhoan->Avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($student->HoTen) . '&background=667eea&color=fff&size=150'); ?>"
        alt="Avatar"
        class="profile-avatar">
      <div class="profile-info flex-grow-1">
        <h2><?php echo e($student->HoTen); ?></h2>
        <div class="profile-meta">
          <div class="profile-meta-item">
            <i class="fas fa-id-card"></i>
            <span><strong>MSSV:</strong> <?php echo e($student->MSSV); ?></span>
          </div>
          <div class="profile-meta-item">
            <i class="fas fa-venus-mars"></i>
            <span><strong>Giới tính:</strong> <?php echo e($student->GioiTinh); ?></span>
          </div>
          <div class="profile-meta-item">
            <i class="fas fa-calendar"></i>
            <span><strong>Sinh:</strong>
              <?php echo e($student->NgaySinh ? date('d/m/Y', strtotime($student->NgaySinh)) : 'N/A'); ?>

            </span>
          </div>
          <div class="profile-meta-item">
            <i class="fas fa-graduation-cap"></i>
            <span><strong>Lớp:</strong> <?php echo e($student->lop->MaLop ?? 'N/A'); ?></span>
          </div>
          <div class="profile-meta-item">
            <i class="fas fa-book"></i>
            <span><strong>Ngành:</strong> <?php echo e($student->lop->khoa->TenKhoa ?? 'N/A'); ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <div class="stat-grid">
    <a href="<?php echo e(route('sinhvien.thongbao_hoatdong')); ?>" class="stat-card-link">
      <div class="stat-card">
        <div class="stat-label">
          <i class="fas fa-bell me-1"></i> Thông báo hoạt động
        </div>
        <div class="stat-value"><?php echo e($notifications_count); ?></div>
        <div class="stat-badge">Chưa đọc</div>
      </div>
    </a>

    <a href="<?php echo e(route('sinhvien.lich_tuan')); ?>" class="stat-card-link">
      <div class="stat-card orange">
        <div class="stat-label">
          <i class="fas fa-calendar me-1"></i> Hoạt động tuần này
        </div>
        <div class="stat-value"><?php echo e($weekly_activities); ?></div>
        <div class="stat-badge">Sắp diễn ra</div>
      </div>
    </a>

    <a href="<?php echo e(route('sinhvien.diem_ren_luyen')); ?>" class="stat-card-link">
      <div class="stat-card success">
        <div class="stat-label">
          <i class="fas fa-star me-1"></i> Điểm rèn luyện <?php echo e($currentHocKy->TenHocKy ?? ''); ?>

        </div>
        <div class="stat-value"><?php echo e($training_score); ?></div>
        <div class="stat-badge"><?php echo e($training_rank); ?></div>
      </div>
    </a>

    <a href="<?php echo e(route('sinhvien.diem_cong_tac_xa_hoi')); ?>" class="stat-card-link">
      <div class="stat-card secondary">
        <div class="stat-label">
          <i class="fas fa-heart me-1"></i> Điểm công tác xã hội
        </div>
        <div class="stat-value"><?php echo e($social_score); ?></div>
        <div class="stat-badge">
          <?php echo e($has_red_activity ? 'Đã có Địa chỉ đỏ' : 'Chưa có Địa chỉ đỏ'); ?>

        </div>
      </div>
    </a>
  </div>

  
  <div class="quick-actions">
    <div class="section-title">
      <i class="fas fa-lightning-bolt"></i> Chức năng nhanh
    </div>
    <div class="action-grid">
      <a href="<?php echo e(route('sinhvien.activities_recommended.index')); ?>" class="action-btn" title="Xem hoạt động được đề xuất dựa trên K-Means">
        <i class="fas fa-magic"></i>
        <span>Hoạt động gợi ý</span>
      </a>
      <a href="<?php echo e(route('sinhvien.quanly_dangky.index')); ?>" class="action-btn">
        <i class="fas fa-clipboard-list"></i>
        <span>Quản lý đăng ký</span>
      </a>
      <a href="<?php echo e(route('sinhvien.diem_ren_luyen')); ?>" class="action-btn">
        <i class="fas fa-star"></i>
        <span>Điểm rèn luyện</span>
      </a>
      <a href="<?php echo e(route('sinhvien.diem_cong_tac_xa_hoi')); ?>" class="action-btn">
        <i class="fas fa-heart"></i>
        <span>Công tác xã hội</span>
      </a>
    </div>
  </div>

  
  <?php if(isset($recommended_activities) && $recommended_activities->count() > 0): ?>
  <div class="table-section" style="margin-bottom: 2rem;">
    <div class="table-header">
      <h5>
        <i class="fas fa-magic me-2" style="color: #667eea;"></i>Hoạt Động Được Đề Xuất (K-Means)
      </h5>
      <a href="<?php echo e(route('sinhvien.activities_recommended.index')); ?>" class="btn btn-sm btn-outline-primary">
        <i class="fas fa-arrow-right me-1"></i>Xem tất cả
      </a>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Tên Hoạt Động</th>
            <th>Loại</th>
            <th>Match Score</th>
            <th>Ngày Bắt Đầu</th>
            <th>Địa Điểm</th>
            
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $recommended_activities->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
              $activity = $rec->activity_type === 'drl' ? $rec->hoatDongDRL : $rec->hoatDongCTXH;
            ?>
            <?php if($activity): ?>
            <tr>
              <td>
                <strong><?php echo e($activity->TenHoatDong); ?></strong>
              </td>
              <td>
                <?php if($rec->activity_type === 'drl'): ?>
                  <span class="badge bg-primary">Rèn Luyện</span>
                <?php else: ?>
                  <span class="badge bg-danger">CTXH</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="points-highlight"><?php echo e(round($rec->recommendation_score)); ?>/100</span>
              </td>
              <td>
                <?php echo e($activity->ThoiGianBatDau ? date('d/m/Y', strtotime($activity->ThoiGianBatDau)) : 'N/A'); ?>

              </td>
              <td>
                <small><?php echo e(substr($activity->DiaDiem, 0, 30)); ?><?php echo e(strlen($activity->DiaDiem) > 30 ? '...' : ''); ?></small>
              </td>
              
            </tr>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="6" class="text-center p-4 text-muted">
                <i class="fas fa-info-circle me-2"></i>Chưa có hoạt động được đề xuất. Hãy chạy K-Means clustering!
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  
  <div class="table-section">
    <div class="table-header">
      <h5>
        <i class="fas fa-list me-2"></i>Hoạt động điểm rèn luyện
      </h5>
      <select class="form-select form-select-sm">
        <option><?php echo e($currentHocKy->TenHocKy ?? 'Học kỳ hiện tại'); ?></option>
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
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="fw-bold"><?php echo e($index + 1); ?></td>
              <td><?php echo e($activity->hoatdong->TenHoatDong ?? 'N/A'); ?></td>
              <td>
                <?php echo e($activity->hoatdong->ThoiGianBatDau
                    ? date('d/m/Y', strtotime($activity->hoatdong->ThoiGianBatDau))
                    : 'N/A'); ?>

              </td>
              <td class="text-center">
                <span class="points-highlight">
                  +<?php echo e($activity->hoatdong->quydinh->DiemNhan ?? 0); ?>

                </span>
              </td>
              <td class="text-center">
                <?php if($activity->TrangThaiDangKy == 'Đã duyệt' || $activity->TrangThaiThamGia == 'Đã tham gia'): ?>
                  <span class="badge badge-success">
                    <i class="fas fa-check-circle me-1"></i>
                    <?php echo e($activity->TrangThaiThamGia == 'Đã tham gia' ? 'Đã tham gia' : $activity->TrangThaiDangKy); ?>

                  </span>
                <?php else: ?>
                  <span class="badge badge-warning">
                    <i class="fas fa-clock me-1"></i>
                    <?php echo e($activity->TrangThaiDangKy ?? $activity->TrangThaiThamGia); ?>

                  </span>
                <?php endif; ?>
              </td>
              
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="6" class="text-center p-4 text-muted">
                Chưa tham gia hoạt động rèn luyện nào trong học kỳ này.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  
  <div class="table-section">
    <div class="table-header">
      <h5>
        <i class="fas fa-heart me-2" style="color: #dc3545;"></i>Hoạt động công tác xã hội
      </h5>
      <span class="badge bg-success text-white">
        Tổng: <?php echo e($social_activities->count()); ?> hoạt động
      </span>
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
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $social_activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="fw-bold"><?php echo e($index + 1); ?></td>
              <td><?php echo e($activity->hoatdong->TenHoatDong ?? 'N/A'); ?></td>
              
              <td>
                <?php echo e($activity->hoatdong->ThoiGianBatDau
                    ? date('d/m/Y', strtotime($activity->hoatdong->ThoiGianBatDau))
                    : 'N/A'); ?>

              </td>
              <td class="text-center">
                <span class="points-highlight">
                  +<?php echo e($activity->hoatdong->quydinh->DiemNhan ?? 0); ?>

                </span>
              </td>
              <td class="text-center">
                <?php if($activity->TrangThaiDangKy == 'Đã duyệt'): ?>
                  <span class="badge badge-success">
                    <i class="fas fa-check-circle me-1"></i> <?php echo e($activity->TrangThaiDangKy); ?>

                  </span>
                <?php else: ?>
                  <span class="badge badge-warning">
                    <i class="fas fa-clock me-1"></i>
                    <?php echo e($activity->TrangThaiDangKy ?? $activity->TrangThaiThamGia); ?>

                  </span>
                <?php endif; ?>
              </td>
             
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="7" class="text-center p-4 text-muted">
                Chưa tham gia hoạt động công tác xã hội nào.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  
  <div id="statisticsSection" style="display: none; margin-bottom: 2rem;">
    <div class="charts-grid">
      <div class="chart-card">
        <h4 class="chart-title">
          <i class="fas fa-chart-bar me-2" style="color: var(--primary);"></i>
          Thống kê điểm rèn luyện
        </h4>
        <div style="height: 350px;">
          <canvas id="trainingChart"></canvas>
        </div>
      </div>

      <div class="chart-card">
        <h4 class="chart-title">
          <i class="fas fa-chart-pie me-2" style="color: var(--success);"></i>
          Thống kê điểm công tác xã hội
        </h4>
        <div style="height: 350px;">
          <canvas id="socialChart"></canvas>
        </div>
      </div>
    </div>
  </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
  const trainingChartData = <?php echo json_encode($training_chart_js ?? [], 15, 512) ?>;
  const socialChartData   = <?php echo json_encode($social_chart_js ?? [], 15, 512) ?>;

  const toggleButton = document.getElementById('toggleStatistics');
  const section      = document.getElementById('statisticsSection');
  let chartLoaded    = false;

  if (toggleButton && section) {
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
  }

  function renderCharts() {
    const trainingCtx = document.getElementById('trainingChart');

    if (trainingCtx && trainingChartData && trainingChartData.labels) {
      new Chart(trainingCtx, {
        type: 'bar',
        data: {
          labels: trainingChartData.labels,
          datasets: [
            {
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

    if (socialCtx && socialChartData && socialChartData.dat !== undefined) {
      new Chart(socialCtx, {
        type: 'doughnut',
        data: {
          labels: ['Đã đạt', 'Còn thiếu'],
          datasets: [
            {
              data: [socialChartData.dat, socialChartData.thieu],
              backgroundColor: ['#4caf50', '#f9c74f'],
              borderColor: '#fff',
              borderWidth: 2
            }
          ]
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
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/dashboard.blade.php ENDPATH**/ ?>