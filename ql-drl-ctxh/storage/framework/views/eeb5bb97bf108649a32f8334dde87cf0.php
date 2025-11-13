

<?php $__env->startSection('title', 'Báo cáo & Thống kê'); ?>
<?php $__env->startSection('page_title', 'Báo cáo & Thống kê'); ?>

<?php
$breadcrumbs = [
    ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
    ['url' => route('nhanvien.thongke.index'), 'title' => 'Thống kê'],
];
?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper me-3">
                        <i class="fa-solid fa-chart-area"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold">Báo cáo & Thống kê</h4>
                        <p class="text-muted mb-0 small">Tổng quan dữ liệu DRL và CTXH</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card modern-card mb-4">
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('nhanvien.thongke.index')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label for="hoc_ky" class="form-label fw-semibold">
                            <i class="fa-solid fa-calendar-days me-2 text-primary"></i>Chọn Học Kỳ
                        </label>
                        <select name="hoc_ky" id="hoc_ky" class="form-select modern-select">
                            <?php $__currentLoopData = $hocKys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hocKy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($hocKy->MaHocKy); ?>" <?php echo e($hocKy->MaHocKy == $selectedHocKy ? 'selected' : ''); ?>>
                                <?php echo e($hocKy->TenHocKy); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="khoa" class="form-label fw-semibold">
                            <i class="fa-solid fa-building-columns me-2 text-primary"></i>Lọc theo Khoa
                        </label>
                        <select name="khoa" id="khoa" class="form-select modern-select">
                            <option value="">Tất cả các Khoa</option>
                            <?php $__currentLoopData = $khoas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $khoa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($khoa->MaKhoa); ?>" <?php echo e($khoa->MaKhoa == $selectedKhoa ? 'selected' : ''); ?>>
                                <?php echo e($khoa->TenKhoa); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="type" class="form-label fw-semibold">
                            <i class="fa-solid fa-filter me-2 text-primary"></i>Loại Hoạt động
                        </label>
                        <select name="type" id="type" class="form-select modern-select">
                            <option value="">Tất cả (DRL & CTXH)</option>
                            <option value="DRL" <?php echo e($selectedType == 'DRL' ? 'selected' : ''); ?>>Chỉ DRL</option>
                            <option value="CTXH" <?php echo e($selectedType == 'CTXH' ? 'selected' : ''); ?>>Chỉ CTXH</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <button class="btn btn-primary w-100 modern-btn" type="submit">
                            <i class="fa-solid fa-filter me-2"></i>Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-icon" style="--icon-bg: #e0f7fa; --icon-color: #00796b;">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <h6 class="stat-title">Tổng Hoạt Động</h6>
                <h3 class="stat-value"><?php echo e(number_format($tongHoatDong)); ?></h3>
                <span class="stat-sub"><?php echo e($tongHoatDongDRL); ?> DRL & <?php echo e($tongHoatDongCTXH); ?> CTXH</span>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-icon" style="--icon-bg: #e3f2fd; --icon-color: #1e88e5;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h6 class="stat-title">Tổng Lượt Đăng Ký</h6>
                <h3 class="stat-value"><?php echo e(number_format($tongDangKy)); ?></h3>
                <span class="stat-sub">/ <?php echo e(number_format($tongSlots)); ?> vị trí</span>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-icon" style="--icon-bg: #e8f5e9; --icon-color: #388e3c;">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <h6 class="stat-title">Tổng Lượt Tham Gia</h6>
                <h3 class="stat-value"><?php echo e(number_format($tongThamGia)); ?></h3>
                <span class="stat-sub">Tỷ lệ: <?php echo e($tyLeThamGia); ?>%</span>
            </div>
        </div>
        <?php if(empty($selectedType) || $selectedType == 'CTXH'): ?>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-icon" style="--icon-bg: #fbe9e7; --icon-color: #d84315;">
                    <i class="fa-solid fa-flag-checkered"></i>
                </div>
                <h6 class="stat-title">Hoàn Thành CTXH</h6>
                <h3 class="stat-value"><?php echo e($tyLeHoanThanhCTXH); ?>%</h3>
                <span class="stat-sub"><?php echo e($completedStudents); ?>/<?php echo e($totalStudents); ?> SV (Mục tiêu: 50đ)</span>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="row g-4 mb-4">
        <?php if(empty($selectedType) || $selectedType == 'CTXH'): ?>
            
            <div class="col-lg-7">
                <div class="card modern-card chart-card h-100">
                    <div class="card-header modern-card-header">
                        <i class="fa-solid fa-chart-bar me-2"></i>Thống kê Hoạt động (Top 10)
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Top 10 hoạt động có nhiều sinh viên tham gia nhất.</p>
                        <div class="chart-placeholder p-3">
                            <div class="chart-icon">
                                <i class="fa-solid fa-chart-column"></i>
                            </div>
                            <p class="chart-text">Top 10 hoạt động theo số lượt tham gia</p>
                            <?php
                                $maxJoin = $topActivities->max('thamgia_count') ?? 1;
                            ?>
                            <div class="list-group w-100">
                                <?php $__empty_1 = true; $__currentLoopData = $topActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $width = $maxJoin > 0 ? round(($a->thamgia_count / $maxJoin) * 100) : 0;
                                    ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <div style="width:36px;" class="text-center fw-bold"><?php echo e($i+1); ?></div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <div class="fw-semibold"><?php echo e(Str::limit($a->TenHoatDong ?? $a['TenHoatDong'] ?? 'N/A', 60)); ?></div>
                                                <div class="text-muted"><?php echo e($a->thamgia_count ?? $a['thamgia_count'] ?? 0); ?> SV</div>
                                            </div>
                                            <div class="progress mt-1" style="height:8px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo e($width); ?>%;" aria-valuenow="<?php echo e($width); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-muted">Không có hoạt động để hiển thị.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card modern-card chart-card h-100">
                    <div class="card-header modern-card-header">
                        <i class="fa-solid fa-chart-pie me-2"></i>Tỷ lệ Hoàn thành CTXH
                        <?php if($selectedKhoa): ?>
                            <small class="d-block mt-1 opacity-75">
                                (Khoa: <?php echo e($khoas->firstWhere('MaKhoa', $selectedKhoa)->TenKhoa ?? $selectedKhoa); ?>)
                            </small>
                        <?php else: ?>
                            <small class="d-block mt-1 opacity-75">(Tất cả các Khoa)</small>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Tỷ lệ hoàn thành CTXH theo Khoa (mục tiêu: 50đ)</p>
                        <div class="list-group">
                            <?php if(isset($ctxhByKhoa) && $ctxhByKhoa->isNotEmpty()): ?>
                                <?php $__currentLoopData = $ctxhByKhoa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <div class="fw-semibold"><?php echo e($k['TenKhoa']); ?></div>
                                            <div class="text-muted"><?php echo e($k['completed']); ?>/<?php echo e($k['total']); ?> (<?php echo e($k['percent']); ?>%)</div>
                                        </div>
                                        <div class="progress" style="height:10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($k['percent']); ?>%;" aria-valuenow="<?php echo e($k['percent']); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="text-muted">Không có dữ liệu CTXH theo Khoa.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            
            <div class="col-12">
                <div class="card modern-card chart-card h-100">
                    <div class="card-header modern-card-header">
                        <i class="fa-solid fa-chart-bar me-2"></i>Thống kê Hoạt động DRL (Top 10)
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Top 10 hoạt động DRL có nhiều sinh viên tham gia nhất.</p>
                        <div class="chart-placeholder chart-wide">
                            <div class="chart-icon">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <p class="chart-text">Biểu đồ cột DRL đang được xây dựng</p>
                            <div class="chart-bars chart-bars-wide">
                                <div class="bar-item" style="height: 90%"></div>
                                <div class="bar-item" style="height: 75%"></div>
                                <div class="bar-item" style="height: 85%"></div>
                                <div class="bar-item" style="height: 65%"></div>
                                <div class="bar-item" style="height: 95%"></div>
                                <div class="bar-item" style="height: 80%"></div>
                                <div class="bar-item" style="height: 70%"></div>
                                <div class="bar-item" style="height: 88%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="card modern-card mb-4">
        <div class="card-header modern-card-header">
            <i class="fa-solid fa-list-check me-2"></i>Chi tiết Hoạt động (Học kỳ: <?php echo e($selectedHocKy); ?>)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th>Hoạt động</th>
                            <th class="text-center">Loại</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Vị trí (Slots)</th>
                            <th class="text-center">Đăng ký</th>
                            <th class="text-center">Tham gia</th>
                            <th class="text-center">Tỷ lệ Lấp đầy</th>
                            <th class="text-center">Tỷ lệ Tham gia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $thongKeHoatDong; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                        $tyLeLopDay = $hd->SoLuong > 0 ? round(($hd->dangky_count / $hd->SoLuong) * 100) : 0;
                        $tyLeThamGia = $hd->dangky_count > 0 ? round(($hd->thamgia_count / $hd->dangky_count) * 100) : 0;
                        ?>
                        <tr>
                            <td>
                                <div class="student-name"><?php echo e(Str::limit($hd->TenHoatDong, 45)); ?></div>
                                <div class="student-code"><?php echo e($hd->MaHoatDong); ?></div>
                            </td>
                            <td class="text-center">
                                <?php if($hd->type == 'DRL'): ?>
                                <span class="badge-modern badge-drl">DRL</span>
                                <?php else: ?>
                                <span class="badge-modern badge-ctxh">CTXH</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($hd->ThoiGianBatDau > now()): ?>
                                    <span class="badge-status badge-info">Sắp diễn ra</span>
                                <?php elseif($hd->ThoiGianKetThuc < now()): ?>
                                    <span class="badge-status badge-secondary">Đã kết thúc</span>
                                <?php else: ?>
                                    <span class="badge-status badge-success">Đang diễn ra</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center fw-bold"><?php echo e($hd->SoLuong); ?></td>
                            <td class="text-center fw-bold text-primary"><?php echo e($hd->dangky_count); ?></td>
                            <td class="text-center fw-bold text-success"><?php echo e($hd->thamgia_count); ?></td>
                            <td class="text-center">
                                <div class="progress-wrapper">
                                    <span class="progress-text"><?php echo e($tyLeLopDay); ?>%</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary" style="width: <?php echo e($tyLeLopDay); ?>%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="progress-wrapper">
                                    <span class="progress-text"><?php echo e($tyLeThamGia); ?>%</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: <?php echo e($tyLeThamGia); ?>%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
                                    <h5 class="empty-title">Không tìm thấy hoạt động</h5>
                                    <p class="empty-text">Không có hoạt động nào cho bộ lọc này</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($thongKeHoatDong->hasPages()): ?>
            <div class="pagination-wrapper">
                <?php echo e($thongKeHoatDong->appends(request()->query())->links(null, ['pageName' => 'page_hd'])); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card modern-card mb-4">
        <div class="card-header modern-card-header">
            <i class="fa-solid fa-graduation-cap me-2"></i>Thống kê theo Lớp (Khoa: <?php echo e($selectedKhoa ?? 'Tất cả'); ?>)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th>Lớp</th>
                            <th>Khoa</th>
                            <th class="text-center">Sĩ số</th>
                            <?php if(empty($selectedType) || $selectedType == 'DRL'): ?>
                            <th class="text-center">DRL TB (HK: <?php echo e($selectedHocKy); ?>)</th>
                            <?php endif; ?>
                            <?php if(empty($selectedType) || $selectedType == 'CTXH'): ?>
                            <th class="text-center">CTXH TB (Tổng)</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $thongKeLop; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="student-name"><?php echo e($lop->TenLop); ?></div>
                                <div class="student-code"><?php echo e($lop->MaLop); ?></div>
                            </td>
                            <td><?php echo e($lop->khoa->TenKhoa ?? 'N/A'); ?></td>
                            <td class="text-center fw-bold"><?php echo e($lop->sinhviens_count); ?></td>

                            <?php if(empty($selectedType) || $selectedType == 'DRL'): ?>
                            <td class="text-center">
                                <span class="score-badge score-drl"><?php echo e(number_format($lop->avg_drl, 1)); ?></span>
                            </td>
                            <?php endif; ?>

                            <?php if(empty($selectedType) || $selectedType == 'CTXH'): ?>
                            <td class="text-center">
                                <span class="score-badge score-ctxh"><?php echo e(number_format($lop->avg_ctxh, 1)); ?></span>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php
                        $colspan = 3;
                        if(empty($selectedType) || $selectedType == 'DRL') $colspan++;
                        if(empty($selectedType) || $selectedType == 'CTXH') $colspan++;
                        ?>
                        <tr>
                            <td colspan="<?php echo e($colspan); ?>" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
                                    <h5 class="empty-title">Không tìm thấy lớp</h5>
                                    <p class="empty-text">Không có lớp nào cho bộ lọc này</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($thongKeLop->hasPages()): ?>
            <div class="pagination-wrapper">
                <?php echo e($thongKeLop->appends(request()->query())->links(null, ['pageName' => 'page_lop'])); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    :root {
        --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        --card-shadow-hover: 0 8px 24px rgba(0, 0, 0, 0.12);
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    /* Page Header */
    .page-header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        box-shadow: var(--card-shadow);
    }

    .icon-wrapper {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    /* Modern Card */
    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .modern-card:hover {
        box-shadow: var(--card-shadow-hover);
    }

    /* Modern Select */
    .modern-select {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        transition: all 0.3s ease;
    }

    .modern-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Modern Button */
    .modern-btn {
        border-radius: 10px;
        padding: 0.65rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Stat Cards */
    .stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: 1px solid #f3f4f6;
    }

    .stat-card:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-4px);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background-color: var(--icon-bg, #e0f7fa);
        color: var(--icon-color, #00796b);
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.2;
        margin-bottom: 0.25rem;
    }

    .stat-sub {
        font-size: 0.875rem;
        color: #9ca3af;
        font-weight: 500;
    }

    /* Chart Cards */
    .chart-card {
        position: relative;
        overflow: hidden;
    }

    .chart-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }

    .modern-card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        font-weight: 700;
        color: #374151;
        padding: 1.25rem 1.5rem;
        border: none;
        font-size: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    /* Chart Placeholder */
    .chart-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 2rem;
        min-height: 300px;
    }

    .chart-wide {
        min-height: 350px;
    }

    .chart-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        animation: pulse 2s infinite;
    }

    .chart-icon i {
        font-size: 2.5rem;
        color: #667eea;
    }

    .chart-text {
        color: #9ca3af;
        font-weight: 600;
        margin-bottom: 2rem;
    }

    /* Animated Chart Bars */
    .chart-bars {
        display: flex;
        align-items: flex-end;
        gap: 0.5rem;
        height: 120px;
        margin-top: 1rem;
    }

    .chart-bars-wide {
        gap: 0.75rem;
        height: 150px;
    }

    .bar-item {
        width: 40px;
        background: var(--primary-gradient);
        border-radius: 8px 8px 0 0;
        opacity: 0.7;
        animation: barGrow 1.5s ease-out;
    }

    .chart-bars-wide .bar-item {
        width: 50px;
    }

    /* Pie Preview */
    .pie-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(
            #10b981 0deg calc(var(--percentage) * 3.6deg),
            #e5e7eb calc(var(--percentage) * 3.6deg) 360deg
        );
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        animation: pieRotate 2s ease-out;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes barGrow {
        from { height: 0; opacity: 0; }
        to { height: var(--height, 100%); opacity: 0.7; }
    }

    @keyframes pieRotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Modern Table */
    .modern-table {
        margin: 0;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    }

    .modern-table thead th {
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #374151;
        padding: 1rem 1.25rem;
        border: none;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .modern-table tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        color: #4b5563;
    }

    .student-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .student-code {
        font-size: 0.8rem;
        color: #9ca3af;
        font-family: 'Courier New', monospace;
    }

    /* Badges */
    .badge-modern {
        padding: 0.4rem 0.85rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-drl {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .badge-ctxh {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .badge-status {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
    }

    .badge-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-info {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .badge-secondary {
        background-color: #f3f4f6;
        color: #6b7280;
    }

    /* Progress Wrapper */
    .progress-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
    }

    .progress-text {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
    }

    .progress-sm {
        height: 6px;
        width: 80px;
        background-color: #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    /* Score Badge */
    .score-badge {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        display: inline-block;
        min-width: 60px;
    }

    .score-drl {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        box-shadow: 0 2px 6px rgba(30, 64, 175, 0.15);
    }

    .score-ctxh {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        box-shadow: 0 2px 6px rgba(6, 95, 70, 0.15);
    }

    /* Empty State */
    .empty-state {
        padding: 2rem;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon i {
        font-size: 2.5rem;
        color: #9ca3af;
    }

    .empty-title {
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #9ca3af;
        margin: 0;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        display: flex;
        justify-content: center;
        border-top: 1px solid #e5e7eb;
    }

    /* pagination default container (ul.pagination) and fallback anchors */
    .pagination, .pagination-wrapper .pagination-inline {
        display: inline-flex;
        gap: 0.5rem;
        padding: 0;
        margin: 0;
        list-style: none;
        align-items: center;
    }

    /* Generic page item styling covers both <li><a> and plain <a> outputs */
    .pagination li, .pagination a, .pagination span, .pagination-wrapper a.page-link, .pagination-wrapper .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        height: 44px;
        padding: 0 0.9rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        color: #374151;
        font-weight: 600;
        text-decoration: none;
        background: #fff;
        transition: all 0.12s ease;
        box-sizing: border-box;
    }

    .pagination a:hover, .pagination span:hover, .pagination li:hover a {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(102,126,234,0.12);
        border-color: rgba(102,126,234,0.2);
    }

    /* Active page */
    .pagination .active > a,
    .pagination li.active,
    .pagination .active {
        background: var(--primary-gradient);
        color: #fff !important;
        border-color: transparent;
        box-shadow: 0 8px 24px rgba(102,126,234,0.18);
    }

    /* Disabled previous/next */
    .pagination .disabled, .pagination li.disabled, .pagination span.disabled {
        opacity: 0.45;
        pointer-events: none;
        background: #fafafa;
        border-color: #f3f4f6;
    }

    .page-link:hover {
        background: #667eea;
        border-color: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    .page-item.active .page-link {
        background: var(--primary-gradient);
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Small helper for the 'Showing' text above pagination */
    .pagination-info {
        text-align: center;
        color: #6b7280;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    /* Responsive */
    @media (max-width: 991.98px) {
        .stat-value {
            font-size: 1.75rem;
        }

        .chart-placeholder {
            min-height: 250px;
            padding: 2rem 1rem;
        }

        .chart-icon {
            width: 60px;
            height: 60px;
        }

        .chart-icon i {
            font-size: 2rem;
        }

        .chart-bars {
            height: 100px;
        }

        .bar-item {
            width: 30px;
        }
    }

    @media (max-width: 767.98px) {
        .page-header-card {
            padding: 1.5rem;
        }

        .icon-wrapper {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .stat-card {
            padding: 1.25rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .modern-table {
            font-size: 0.875rem;
        }

        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.75rem;
        }

        .progress-sm {
            width: 60px;
        }
    }

    /* Smooth Transitions */
    * {
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    /* Print Styles */
    @media print {
        .modern-card {
            box-shadow: none;
            border: 1px solid #e5e7eb;
        }

        .page-header-card {
            background: #667eea;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .chart-placeholder {
            min-height: 200px;
        }

        .pagination-wrapper {
            display: none;
        }
    }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/thongke/index.blade.php ENDPATH**/ ?>