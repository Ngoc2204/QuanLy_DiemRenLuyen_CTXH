

<?php $__env->startSection('title', 'Chi tiết Hoạt động DRL'); ?>
<?php $__env->startSection('page_title', 'Chi tiết Hoạt động DRL'); ?> 

<?php
// Breadcrumbs
$breadcrumbs = [
['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
['url' => route('nhanvien.hoatdong_drl.index'), 'title' => 'Hoạt động DRL'],
['url' => '#', 'title' => 'Chi tiết'],
];

// Thêm logic tính toán trạng thái
$now = now();
$dangDienRa = $hoatdong_drl->ThoiGianBatDau <= $now && $hoatdong_drl->ThoiGianKetThuc >= $now;
    $daKetThuc = $hoatdong_drl->ThoiGianKetThuc < $now;
        $chuaBatDau=$hoatdong_drl->ThoiGianBatDau > $now;
        $tyLeDangKy = $hoatdong_drl->SoLuong > 0 ? round(($hoatdong_drl->sinhVienDangKy->count() / $hoatdong_drl->SoLuong) * 100) : 0;
        ?>

        <?php $__env->startSection('content'); ?>
        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-gradient py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 ">
                            <i class="fa-solid fa-star me-2"></i> 
                            <?php echo e($hoatdong_drl->TenHoatDong); ?>

                        </h5>
                        <small class="">Mã: <?php echo e($hoatdong_drl->MaHoatDong); ?></small>
                    </div>
                    <div>
                        <?php if($dangDienRa): ?>
                        <span class="badge bg-success px-3 py-2">
                            <i class="fa-solid fa-circle-play me-1"></i>Đang diễn ra
                        </span>
                        <?php elseif($daKetThuc): ?>
                        <span class="badge bg-secondary px-3 py-2">
                            <i class="fa-solid fa-circle-check me-1"></i>Đã kết thúc
                        </span>
                        <?php else: ?>
                        <span class="badge bg-info px-3 py-2">
                            <i class="fa-solid fa-clock me-1"></i>Sắp diễn ra
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row g-4">
            
            <div class="col-lg-8">
                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-dark">
                            <i class="fa-solid fa-circle-info me-2 text-primary"></i>
                            Thông tin chi tiết
                        </h6>
                    </div>
                    <div class="card-body">
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fa-solid fa-tag me-2 text-muted"></i>
                                        Loại Hoạt động
                                    </label>
                                    <p class="info-value">
                                        <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                            <?php echo e($hoatdong_drl->LoaiHoatDong); ?>

                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fa-solid fa-align-left me-2 text-muted"></i>
                                        Mô tả
                                    </label>
                                    <p class="info-value text-muted">
                                        <?php echo e($hoatdong_drl->MoTa ?: 'Không có mô tả.'); ?>

                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fa-solid fa-calendar-plus me-2 text-success"></i>
                                        Thời gian Bắt đầu
                                    </label>
                                    <p class="info-value">
                                        <?php echo e($hoatdong_drl->ThoiGianBatDau->format('d/m/Y H:i')); ?>

                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fa-solid fa-calendar-xmark me-2 text-danger"></i>
                                        Thời gian Kết thúc
                                    </label>
                                    <p class="info-value">
                                        <?php echo e($hoatdong_drl->ThoiGianKetThuc->format('d/m/Y H:i')); ?>

                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i>
                                        Thời hạn Hủy đăng ký
                                    </label>
                                    <p class="info-value">
                                        <?php echo e($hoatdong_drl->ThoiHanHuy ? $hoatdong_drl->ThoiHanHuy->format('d/m/Y H:i') : 'Không cho phép'); ?>

                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fa-solid fa-location-dot me-2 text-danger"></i>
                                        Địa điểm
                                    </label>
                                    <p class="info-value">
                                        <?php echo e($hoatdong_drl->DiaDiem ?: 'Không xác định'); ?>

                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fa-solid fa-user-tie me-2 text-muted"></i>
                                        Giảng viên phụ trách
                                    </label>
                                    <p class="info-value">
                                        <?php echo e($hoatdong_drl->giangVienPhuTrach->TenGV ?? 'Chưa gán'); ?>

                                    </p>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fa-solid fa-school me-2 text-muted"></i>
                                        Học kỳ
                                    </label>
                                    <p class="info-value">
                                        <?php echo e($hoatdong_drl->hocKy->TenHocKy ?? 'N/A'); ?>

                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fa-solid fa-clipboard-list me-2 text-info"></i>
                                        Quy định điểm
                                    </label>
                                    <p class="info-value">
                                        <?php if($hoatdong_drl->quydinh): ?>
                                        <span class="badge bg-info-subtle text-info px-3 py-2">
                                            <?php echo e($hoatdong_drl->quydinh->MaDiem); ?> -
                                            <?php echo e($hoatdong_drl->quydinh->TenCongViec); ?>

                                            (<?php echo e($hoatdong_drl->quydinh->DiemNhan ?? 'N/A'); ?> điểm) 
                                        </span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                            Không rõ
                                        </span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-dark">
                            <i class="fa-solid fa-users me-2 text-primary"></i>
                            Danh sách Sinh viên Đăng ký
                            <span class="badge bg-primary ms-2"><?php echo e($hoatdong_drl->sinhVienDangKy->count()); ?></span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <?php if($hoatdong_drl->sinhVienDangKy->count() > 0): ?>
                        <div class="table-responsive" style="max-height: 400px;">
                            <table class="table table-hover mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">#</th>
                                        <th>Sinh viên</th>
                                        <th class="text-center">Trạng thái ĐK</th>
                                        
                                        <th class="text-center">Check-in</th>
                                        <th class="text-center">Check-out</th>
                                        <th class="text-center">Kết quả</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $hoatdong_drl->sinhVienDangKy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $pivot = $sv->pivot; ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($index + 1); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle-small">
                                                    <span><?php echo e(strtoupper(substr($sv->HoTen, 0, 1))); ?></span>
                                                </div>
                                                <div class="ms-3">
                                                    <div class="student-name"><?php echo e($sv->HoTen); ?></div>
                                                    <div class="student-code"><?php echo e($sv->MSSV); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if($pivot->TrangThaiDangKy == 'Chờ duyệt'): ?>
                                            <span class="badge-status badge-warning">
                                                <i class="fa-solid fa-hourglass-half me-1"></i><?php echo e($pivot->TrangThaiDangKy); ?>

                                            </span>
                                            <?php elseif($pivot->TrangThaiDangKy == 'Đã duyệt'): ?>
                                            <span class="badge-status badge-success">
                                                <i class="fa-solid fa-check-circle me-1"></i><?php echo e($pivot->TrangThaiDangKy); ?>

                                            </span>
                                            <?php elseif($pivot->TrangThaiDangKy == 'Đã hủy'): ?>
                                            <span class="badge-status badge-secondary">
                                                <i class="fa-solid fa-ban me-1"></i><?php echo e($pivot->TrangThaiDangKy); ?>

                                            </span>
                                            <?php else: ?>
                                            <span class="badge-status badge-danger">
                                                <i class="fa-solid fa-exclamation-circle me-1"></i><?php echo e($pivot->TrangThaiDangKy ?: 'Không rõ'); ?>

                                            </span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="text-center">
                                            <?php if($pivot->CheckInAt): ?>
                                            <span class="badge-check check-in">
                                                <i class="fa-solid fa-check me-1"></i>
                                                <?php echo e(\Carbon\Carbon::parse($pivot->CheckInAt)->format('H:i:s')); ?>

                                            </span>
                                            <?php else: ?>
                                            <span class="badge-check check-null">Chưa có</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($pivot->CheckOutAt): ?>
                                            <span class="badge-check check-out">
                                                <i class="fa-solid fa-check me-1"></i>
                                                <?php echo e(\Carbon\Carbon::parse($pivot->CheckOutAt)->format('H:i:s')); ?>

                                            </span>
                                            <?php else: ?>
                                            <span class="badge-check check-null">Chưa có</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($pivot->TrangThaiThamGia == 'Đã tham gia'): ?>
                                            <span class="badge-status badge-success">Đã tham gia</span>
                                            <?php elseif($pivot->TrangThaiThamGia == 'Vắng'): ?>
                                            <span class="badge-status badge-danger">Vắng</span>
                                            <?php else: ?>
                                            <span class="badge-status badge-secondary">Chưa tổng kết</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-user-slash fa-3x mb-3 d-block opacity-25"></i>
                            <p class="mb-0">Chưa có sinh viên nào đăng ký hoạt động này.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-lg-4">

                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-dark">
                            <i class="fa-solid fa-qrcode me-2 text-primary"></i>
                            Điểm danh QR Code
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <?php if($hoatdong_drl->CheckInToken || $hoatdong_drl->CheckOutToken): ?>
                        <div class="row g-3">
                            <?php if($hoatdong_drl->CheckInToken): ?>
                            <div class="col-6">
                                <h6 class="fw-bold small text-uppercase mb-2">Check-In</h6>
                                <div class="qr-code-wrapper-small">
                                    <?php echo QrCode::size(100)->generate(route('sinhvien.scan', ['token' => $hoatdong_drl->CheckInToken])); ?>

                                </div>
                                <button class="btn btn-sm btn-outline-primary mt-2 w-100"
                                    onclick="showQRModal('checkin', '<?php echo e(route('sinhvien.scan', ['token' => $hoatdong_drl->CheckInToken])); ?>')">
                                    <i class="fa-solid fa-expand me-1"></i>Phóng to
                                </button>
                                <small class="text-muted d-block mt-1">Quét khi bắt đầu</small>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($hoatdong_drl->CheckOutToken): ?>
                            <div class="col-6">
                                <h6 class="fw-bold small text-uppercase mb-2">Check-Out</h6>
                                <div class="qr-code-wrapper-small">
                                    <?php echo QrCode::size(100)->generate(route('sinhvien.scan', ['token' => $hoatdong_drl->CheckOutToken])); ?>

                                </div>
                                <button class="btn btn-sm btn-outline-primary mt-2 w-100"
                                    onclick="showQRModal('checkout', '<?php echo e(route('sinhvien.scan', ['token' => $hoatdong_drl->CheckOutToken])); ?>')">
                                    <i class="fa-solid fa-expand me-1"></i>Phóng to
                                </button>
                                <small class="text-muted d-block mt-1">Quét khi kết thúc</small>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-3 text-muted">
                            <i class="fa-solid fa-camera fa-3x mb-3 d-block opacity-25"></i>
                            <p class="mb-0">Chưa tạo mã QR.</p>
                            <small>Nhấn nút "Phát Mã QR Check-In" hoặc "Phát Mã QR Check-Out" ở bên dưới.</small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title" id="qrModalTitle">QR Code</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center py-5">
                                <div id="qrModalContent" class="qr-modal-wrapper"></div>
                                <p class="text-muted mt-3 mb-0" id="qrModalDescription"></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-dark">
                            <i class="fa-solid fa-chart-pie me-2 text-primary"></i>
                            Thống kê
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="stat-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <small class="text-muted d-block">Điểm Rèn Luyện</small>
                                    <h4 class="mb-0 text-warning"><?php echo e($hoatdong_drl->quydinh->DiemNhan ?? 'N/A'); ?> <small>điểm</small></h4>
                                </div>
                            </div>
                        </div>

                        <div class="stat-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <small class="text-muted d-block">Số lượng</small>
                                    <h4 class="mb-0 text-primary">
                                        <?php echo e($hoatdong_drl->sinhVienDangKy->count()); ?>/<?php echo e($hoatdong_drl->SoLuong); ?>

                                    </h4>
                                </div>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar <?php echo e($tyLeDangKy >= 80 ? 'bg-danger' : ($tyLeDangKy >= 50 ? 'bg-warning' : 'bg-success')); ?>"
                                    role="progressbar"
                                    style="width: <?php echo e($tyLeDangKy); ?>%;"
                                    aria-valuenow="<?php echo e($tyLeDangKy); ?>"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted mt-1 d-block">Đã lấp đầy <?php echo e($tyLeDangKy); ?>%</small>
                        </div>

                        <div class="stat-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success bg-opacity-10 text-success">
                                    <i class="fa-solid fa-check-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted d-block">Đã duyệt</small>
                                    <h5 class="mb-0 text-success">
                                        <?php echo e($hoatdong_drl->sinhVienDangKy->where('pivot.TrangThaiDangKy', 'Đã duyệt')->count()); ?>

                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="stat-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="fa-solid fa-hourglass-half"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted d-block">Chờ duyệt</small>
                                    <h5 class="mb-0 text-warning">
                                        <?php echo e($hoatdong_drl->sinhVienDangKy->where('pivot.TrangThaiDangKy', 'Chờ duyệt')->count()); ?>

                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                                    <i class="fa-solid fa-ban"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted d-block">Đã hủy</small>
                                    <h5 class="mb-0 text-secondary">
                                        <?php echo e($hoatdong_drl->sinhVienDangKy->where('pivot.TrangThaiDangKy', 'Đã hủy')->count()); ?>

                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-dark">
                            <i class="fa-solid fa-cogs me-2 text-primary"></i>
                            Quản lý & Tác vụ
                        </h6>
                    </div>
                    <div class="card-body d-grid gap-2">
                        <a href="<?php echo e(route('nhanvien.hoatdong_drl.ghi_nhan_ket_qua', $hoatdong_drl)); ?>"
                            class="btn btn-info w-100 mb-2">
                            <i class="fa-solid fa-marker me-2"></i> Ghi nhận/Điều chỉnh Kết quả
                        </a>

                        
                        <a href="<?php echo e(route('nhanvien.hoatdong_drl.create_checkin_qr', $hoatdong_drl)); ?>" class="btn btn-success w-100">
                            <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Phát Mã QR Check-In
                        </a>

                        
                        <a href="<?php echo e(route('nhanvien.hoatdong_drl.create_checkout_qr', $hoatdong_drl)); ?>" class="btn btn-warning text-white w-100">
                            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Phát Mã QR Check-Out
                        </a>

                        <form action="<?php echo e(route('nhanvien.hoatdong_drl.finalize', $hoatdong_drl)); ?>" method="POST"
                            onsubmit="return confirm('Bạn có chắc muốn tổng kết điểm danh? Hành động này sẽ ghi nhận trạng thái (Đã tham gia/Vắng) cho tất cả sinh viên đã được duyệt.')">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-primary w-100" <?php echo e($daKetThuc ? '' : 'disabled'); ?>

                                title="<?php echo e($daKetThuc ? 'Tổng kết điểm danh' : 'Chỉ có thể tổng kết sau khi hoạt động đã kết thúc'); ?>">
                                <i class="fa-solid fa-clipboard-check me-2"></i> Tổng kết Điểm danh
                            </button>
                        </form>

                        <hr class="my-2">

                        <a href="<?php echo e(route('nhanvien.hoatdong_drl.edit', $hoatdong_drl->MaHoatDong)); ?>"
                            class="btn btn-outline-warning">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Chỉnh sửa
                        </a>
                        <a href="<?php echo e(route('nhanvien.hoatdong_drl.index')); ?>"
                            class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-2"></i>Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <style>
            /* Info List */
            .info-item {
                padding: 1rem 0;
                border-bottom: 1px solid #f0f0f0;
            }

            .info-item:last-child {
                border-bottom: none;
            }

            .info-label {
                font-weight: 600;
                color: #6c757d;
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
                display: block;
            }

            .info-value {
                margin: 0;
                color: #212529;
                font-size: 0.95rem;
            }

            /* Stat Card */
            .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
            }

            .stat-item {
                padding-bottom: 1rem;
                border-bottom: 1px solid #f0f0f0;
            }

            .stat-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .avatar-xs {
                width: 32px;
                height: 32px;
            }

            .card {
                border-radius: 12px;
                overflow: hidden;
            }

            .badge {
                padding: 0.5em 0.75em;
                font-weight: 500;
                border-radius: 6px;
            }

            .progress {
                height: 12px;
                border-radius: 10px;
                background-color: #e9ecef;
            }

            .progress-bar {
                border-radius: 10px;
            }

            .table> :not(caption)>*>* {
                padding: 0.75rem;
            }

            .sticky-top {
                position: sticky;
                top: 0;
                z-index: 10;
            }

            .btn {
                border-radius: 8px;
                padding: 0.5rem 1rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .shadow-sm {
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
            }

            .bg-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            /* === BỔ SUNG THÊM STYLE CHO TÍNH NĂNG MỚI === */
            /* QR Code - Small Version */
            .qr-code-wrapper-small {
                border: 3px solid #f3f4f6;
                border-radius: 10px;
                padding: 8px;
                display: inline-block;
                background: #fff;
                margin-bottom: 0.5rem;
            }

            /* QR Code - Modal Full Size */
            .qr-modal-wrapper {
                display: inline-block;
                padding: 20px;
                background: #fff;
                border: 5px solid #f3f4f6;
                border-radius: 16px;
            }

            .qr-modal-wrapper svg {
                display: block;
            }

            /* Avatar Circle Small (cho bảng) */
            .avatar-circle-small {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 700;
                font-size: 1rem;
                flex-shrink: 0;
            }

            .student-name {
                font-weight: 600;
                color: #1f2937;
                font-size: 0.9375rem;
            }

            .student-code {
                font-size: 0.8125rem;
                color: #6b7280;
            }

            /* Badge Status (trong bảng) */
            .badge-status {
                padding: 0.35rem 0.75rem;
                border-radius: 20px;
                font-weight: 600;
                font-size: 0.8rem;
                display: inline-flex;
                align-items: center;
            }

            .badge-success {
                background-color: #e7f8f0;
                color: #0d9255;
            }

            .badge-warning {
                background-color: #fff8e1;
                color: #f59e0b;
            }

            .badge-danger {
                background-color: #fef2f2;
                color: #ef4444;
            }

            .badge-secondary {
                background-color: #f3f4f6;
                color: #6b7280;
            }

            /* Badge Check (trong bảng) */
            .badge-check {
                padding: 0.35rem 0.75rem;
                border-radius: 8px;
                font-size: 0.8125rem;
                font-weight: 500;
            }

            .badge-check.check-in,
            .badge-check.check-out {
                background-color: #e7f8f0;
                color: #0d9255;
            }

            .badge-check.check-null {
                background-color: #f3f4f6;
                color: #9ca3af;
            }
        </style>

        <script>
            // Hàm hiển thị QR Modal
            function showQRModal(type, url) {
                const modal = new bootstrap.Modal(document.getElementById('qrModal'));
                const modalTitle = document.getElementById('qrModalTitle');
                const modalContent = document.getElementById('qrModalContent');
                const modalDescription = document.getElementById('qrModalDescription');

                // Set title
                if (type === 'checkin') {
                    modalTitle.innerHTML = '<i class="fa-solid fa-arrow-right-to-bracket me-2 text-success"></i>QR Code Check-In';
                    modalDescription.textContent = 'Sinh viên quét mã này khi bắt đầu hoạt động';
                } else {
                    modalTitle.innerHTML = '<i class="fa-solid fa-arrow-right-from-bracket me-2 text-danger"></i>QR Code Check-Out';
                    modalDescription.textContent = 'Sinh viên quét mã này khi kết thúc hoạt động';
                }

                // Generate large QR code using fetch to get the SVG
                fetch(`/qr-generator?url=${encodeURIComponent(url)}&size=300`)
                    .then(response => response.text())
                    .then(svg => {
                        modalContent.innerHTML = `<div style="display: inline-block;">${svg}</div>`;
                    })
                    .catch(error => {
                        // Fallback: If fetch fails, try to generate with PHP
                        modalContent.innerHTML = `
                <div style="display: inline-block;">
                    <?php echo QrCode::size(300)->generate('${url}'); ?>

                </div>
            `;
                    });

                // Show modal
                modal.show();
            }
        </script>
        <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/hoatdong_drl/show.blade.php ENDPATH**/ ?>