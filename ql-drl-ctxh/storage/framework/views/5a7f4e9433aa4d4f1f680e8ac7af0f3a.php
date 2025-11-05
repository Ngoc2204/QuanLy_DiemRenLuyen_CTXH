

<?php $__env->startSection('title', 'Bảng điều khiển'); ?>
<?php $__env->startSection('page_title', 'Bảng điều khiển Giảng viên'); ?>

<?php
$breadcrumbs = [
    ['url' => route('giangvien.home'), 'title' => 'Bảng điều khiển'],
];
?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper me-3">
                        <i class="fa-solid fa-chalkboard-teacher"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold">Chào mừng, <?php echo e(Auth::user()->TenDangNhap ?? 'Giảng viên'); ?>!</h4>
                        <p class="text-muted mb-0 small">Đây là trang tổng quan các lớp cố vấn và hoạt động bạn phụ trách.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-icon" style="--icon-bg: #e3f2fd; --icon-color: #1e88e5;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h6 class="stat-title">Số Lớp Cố Vấn</h6>
                <h3 class="stat-value"><?php echo e($soLopCoVan ?? 0); ?></h3>
                <span class="stat-sub">Lớp</span>
            </div>
        </div>
         <div class="col-lg-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-icon" style="--icon-bg: #e8f5e9; --icon-color: #388e3c;">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <h6 class="stat-title">Tổng Sĩ Số</h6>
                <h3 class="stat-value"><?php echo e($siSo ?? 0); ?></h3>
                <span class="stat-sub">Sinh viên (từ <?php echo e($soLopCoVan); ?> lớp)</span>
            </div>
        </div>
         <div class="col-lg-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-icon" style="--icon-bg: #fff8e1; --icon-color: #f59e0b;">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h6 class="stat-title">DRL TB (HK Hiện tại)</h6>
                <h3 class="stat-value"><?php echo e(number_format($avgDrl ?? 0, 1)); ?></h3>
                <span class="stat-sub">Điểm (Tất cả các lớp)</span>
            </div>
        </div>
         <div class="col-lg-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-icon" style="--icon-bg: #fbe9e7; --icon-color: #d84315;">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <h6 class="stat-title">CTXH TB (Tổng)</h6>
                <h3 class="stat-value"><?php echo e(number_format($avgCtxh ?? 0, 1)); ?></h3>
                <span class="stat-sub">Điểm (Tất cả các lớp)</span>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-12">
            <h5 class="fw-bold mb-3">Nghiệp vụ Lớp Cố vấn</h5>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="" class="quick-access-card card-drl">
                <div class="icon"><i class="fa-solid fa-clipboard-check"></i></div>
                <div class="title">Xem Điểm Rèn Luyện</div>
                <div class="desc">Tra cứu, theo dõi điểm DRL của các lớp</div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="" class="quick-access-card card-ctxh">
                <div class="icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
                <div class="title">Xem Điểm CTXH</div>
                <div class="desc">Tra cứu, theo dõi điểm CTXH của các lớp</div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="" class="quick-access-card card-bcs">
                <div class="icon"><i class="fa-solid fa-users-gear"></i></div>
                <div class="title">Ban Cán Sự Lớp</div>
                <div class="desc">Thiết lập ban cán sự cho từng lớp</div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="" class="quick-access-card card-report">
                <div class="icon"><i class="fa-solid fa-file-excel"></i></div>
                <div class="title">Xuất Báo Cáo</div>
                <div class="desc">Xuất file Excel điểm của các lớp</div>
            </a>
        </div>
    </div>

    
    
    
    <div class="row g-4 mb-4">
        <div class="col-12">
             <div class="card modern-card">
                <div class="card-header modern-card-header">
                    <i class="fa-solid fa-graduation-cap me-2"></i>Danh sách Lớp Cố Vấn (<?php echo e($soLopCoVan); ?>)
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table modern-table mb-0">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên Lớp</th>
                                    <th>Mã Lớp</th>
                                    <th>Khoa</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $lopCoVanList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $lop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <div class="student-name"><?php echo e($lop->TenLop); ?></div>
                                    </td>
                                    <td>
                                        <div class="student-code"><?php echo e($lop->MaLop); ?></div>
                                    </td>
                                    <td><?php echo e($lop->khoa->TenKhoa ?? 'N/A'); ?></td>
                                    <td class="text-center">
                                        
                                        <a href="" class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-eye me-1"></i> Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
                                            <h5 class="empty-title">Chưa được phân công</h5>
                                            <p class="empty-text">Bạn hiện chưa được phân công cố vấn cho lớp nào.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-4">
        <div class="col-12">
             <div class="card modern-card">
                <div class="card-header modern-card-header">
                    <i class="fa-solid fa-sliders me-2"></i>Hoạt động DRL bạn phụ trách
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table modern-table mb-0">
                            <thead>
                                <tr>
                                    <th>Hoạt động</th>
                                    <th class="text-center">Học kỳ</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Tổng số lượng</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $hoatDongsPhuTrach ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="student-name"><?php echo e($hd->TenHoatDong); ?></div>
                                        <div class="student-code"><?php echo e($hd->MaHoatDong); ?></div>
                                    </td>
                                    <td class="text-center"><?php echo e($hd->hocKy->TenHocKy ?? $hd->MaHocKy); ?></td>
                                    <td class="text-center">
                                        <?php if($hd->ThoiGianBatDau > now()): ?> <span class="badge-status badge-info">Sắp diễn ra</span>
                                        <?php elseif($hd->ThoiGianKetThuc < now()): ?> <span class="badge-status badge-secondary">Đã kết thúc</span>
                                        <?php else: ?> <span class="badge-status badge-success">Đang diễn ra</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center fw-bold"><?php echo e($hd->SoLuong); ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo e(route('giangvien.hoatdong.phanbo.edit', $hd->MaHoatDong)); ?>" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-sliders me-1"></i> Phân bổ
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
                                            <h5 class="empty-title">Không có hoạt động nào</h5>
                                            <p class="empty-text">Bạn hiện không được gán phụ trách hoạt động nào.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>

<style>
/* Page Header */
.page-header-card {
    background: var(--dark);
    padding: 2rem;
    border-radius: 16px;
    color: white;
    box-shadow: var(--shadow-lg);
    background: linear-gradient(180deg, var(--dark) 0%, var(--darker) 100%);
}
.icon-wrapper {
    width: 56px; height: 56px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(10px);
}
.icon-wrapper i { font-size: 1.75rem; color: var(--primary-light); }
/* Modern Card */
.modern-card {
    border: none;
    border-radius: 16px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
}
.modern-card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    font-weight: 700;
    color: #374151;
    padding: 1.25rem 1.5rem;
    border: none;
    font-size: 1rem;
}
.modern-table { font-size: 0.9375rem; }
.modern-table thead { background: #f9fafb; }
.modern-table thead th {
    font-weight: 600; color: #4b5563;
    padding: 1rem 1.25rem; border: none;
    font-size: 0.8rem; letter-spacing: 0.05em; text-transform: uppercase;
}
.modern-table tbody td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
}
.student-name { font-weight: 600; color: #1f2937; }
.student-code { font-size: 0.8125rem; color: #6b7280; }
/* Stat Card */
.stat-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
}
.stat-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-4px); }
.stat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    background-color: var(--icon-bg, #e0f7fa);
    color: var(--icon-color, #00796b);
    margin-bottom: 1rem;
}
.stat-title {
    font-size: 0.9rem; font-weight: 600; color: #6b7280;
    margin-bottom: 0.25rem; text-transform: uppercase;
}
.stat-value { font-size: 2.25rem; font-weight: 700; color: #1f2937; line-height: 1.2; }
.stat-sub { font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; }
/* Quick Access Card */
.quick-access-card {
    display: block;
    background: #fff;
    border-radius: 16px;
    padding: 1.5rem;
    text-decoration: none;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    height: 100%;
}
.quick-access-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-5px);
}
.quick-access-card .icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}
.quick-access-card .title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
}
.quick-access-card .desc {
    font-size: 0.875rem;
    color: var(--secondary);
}
/* Card colors */
.quick-access-card.card-drl .icon { color: var(--primary); }
.quick-access-card.card-ctxh .icon { color: var(--success); }
.quick-access-card.card-bcs .icon { color: var(--warning); }
.quick-access-card.card-report .icon { color: #1e293b; }
/* Badge Status (trong bảng) */
.badge-status {
    padding: 0.35rem 0.75rem; border-radius: 20px;
    font-weight: 600; font-size: 0.8rem;
    display: inline-flex; align-items: center;
}
.badge-success { background-color: #e7f8f0; color: #0d9255; }
.badge-info { background-color: #e0f7fa; color: #00796b; }
.badge-secondary { background-color: #f3f4f6; color: #6b7280; }
/* Empty State */
.empty-state { padding: 3rem 1rem; text-align: center; }
.empty-icon { font-size: 4rem; color: #e5e7eb; margin-bottom: 1.5rem; }
.empty-title { color: #6b7280; font-weight: 600; margin-bottom: 0.5rem; }
.empty-text { color: #9ca3af; font-size: 0.9375rem; margin: 0; }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.giangvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/giangvien/dashboard.blade.php ENDPATH**/ ?>