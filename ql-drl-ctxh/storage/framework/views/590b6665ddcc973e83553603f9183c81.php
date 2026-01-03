


<?php $__env->startSection('title', 'Quản lý Hoạt động DRL'); ?>
<?php $__env->startSection('page_title', 'Danh sách Hoạt động DRL'); ?>

<?php
// Đổi route breadcrumb
$breadcrumbs = [
['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
['url' => route('nhanvien.hoatdong_drl.index'), 'title' => 'Hoạt động DRL'],
];

// Lấy số liệu từ Controller
$tongHoatDong = $hoatDongs->total();
$tongDangKy = $tongDangKyCurrentPage ?? 0;
$tyLeLopDay = $tyLeLopDayCurrentPage ?? 0;

?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm border-0">
    
    <div class="card-header bg-gradient  d-flex justify-content-between align-items-center py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="mb-0">
            <i class="fa-solid fa-clipboard-check me-2"></i> Danh sách Hoạt động Rèn luyện
        </h5>
        <a href="<?php echo e(route('nhanvien.hoatdong_drl.create')); ?>" class="btn btn-light btn-sm shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> Thêm mới
        </a>
    </div>
    <div class="card-body p-4">

        
        <form method="GET" action="<?php echo e(route('nhanvien.hoatdong_drl.index')); ?>" class="mb-4">
            <div class="row g-3">
                <div class="col-md-10">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Tìm kiếm theo tên hoạt động, địa điểm..." value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-fill shadow-sm" type="submit"><i class="fa-solid fa-search me-1"></i> Tìm</button>
                        <a href="<?php echo e(route('nhanvien.hoatdong_drl.index')); ?>" class="btn btn-outline-secondary shadow-sm" title="Làm mới"><i class="fa-solid fa-rotate-right"></i></a>
                    </div>
                </div>
            </div>
        </form>

        
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0"><i class="fa-solid fa-clipboard-list fa-2x text-primary"></i></div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Tổng hoạt động DRL</h6>
                                <h4 class="mb-0 text-primary"><?php echo e(number_format($tongHoatDong)); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0"><i class="fa-solid fa-users fa-2x text-success"></i></div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Tổng đăng ký DRL</h6>
                                <h4 class="mb-0 text-success"><?php echo e(number_format($tongDangKy)); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0"><i class="fa-solid fa-chart-line fa-2x text-info"></i></div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Tỷ lệ lấp đầy DRL</h6>
                                <h4 class="mb-0 text-info"><?php echo e($tyLeLopDay); ?>%</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center" style="width: 5%;">#</th>
                        <th scope="col">Thông tin hoạt động</th>
                        <th scope="col" class="text-center" style="width: 15%;">Học Kỳ</th>
                        <th scope="col" class="text-center" style="width: 15%;">Thời gian</th>
                        <th scope="col" class="text-center" style="width: 8%;">Điểm RL</th>
                        <th scope="col" class="text-center" style="width: 12%;">Số lượng</th>
                        <th scope="col" class="text-center" style="width: 10%;">Trạng thái</th>
                        <th scope="col" class="text-center" style="width: 15%;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $hoatDongs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $hd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    

                    <?php
                    $now = now();
                    $dangDienRa = $hd->ThoiGianBatDau <= $now && $hd->ThoiGianKetThuc >= $now;
                        $daKetThuc = $hd->ThoiGianKetThuc < $now;
                            $chuaBatDau=$hd->ThoiGianBatDau > $now;
                            $tyLe = $hd->SoLuong > 0 ? round(($hd->dangky_count / $hd->SoLuong) * 100) : 0;
                            ?>
                            <tr>
                                <td class="text-center fw-bold"><?php echo e($hoatDongs->firstItem() + $index); ?></td>
                                <td>
                                    
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="fa-solid fa-clipboard-check text-primary"></i>
                                        </div>
                                        <div>
                                            <a href="<?php echo e(route('nhanvien.hoatdong_drl.show', $hd->MaHoatDong)); ?>" class="text-decoration-none fw-semibold text-dark">
                                                <?php echo e($hd->TenHoatDong); ?>

                                            </a>
                                            <div class="small text-muted mt-1"><i class="fa-solid fa-location-dot me-1"></i> <?php echo e($hd->DiaDiem ?? 'Chưa xác định'); ?></div>


                                            <div class="small text-primary mt-1">
                                                <i class="fa-solid fa-user-tie me-1"></i>
                                                <?php echo e($hd->giangVienPhuTrach->TenGV ?? 'Chưa gán'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center small"><?php echo e($hd->hocKy->TenHocKy ?? $hd->MaHocKy); ?></td>
                                <td class="text-center">
                                    
                                    <div class="small">
                                        <div class="text-success mb-1"><i class="fa-solid fa-play me-1"></i> <?php echo e($hd->ThoiGianBatDau->format('d/m/Y H:i')); ?></div>
                                        <div class="text-danger"><i class="fa-solid fa-stop me-1"></i> <?php echo e($hd->ThoiGianKetThuc->format('d/m/Y H:i')); ?></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    
                                    <span class="badge bg-warning text-dark"><?php echo e($hd->quydinh->DiemNhan ?? 'N/A'); ?></span>
                                </td>
                                <td class="text-center">
                                    
                                    <div class="mb-1"><span class="badge bg-primary"><?php echo e($hd->dangky_count); ?>/<?php echo e($hd->SoLuong); ?></span></div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar <?php echo e($tyLe >= 80 ? 'bg-danger' : ($tyLe >= 50 ? 'bg-warning' : 'bg-success')); ?>" role="progressbar" style="width: <?php echo e($tyLe); ?>%;" aria-valuenow="<?php echo e($tyLe); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted"><?php echo e($tyLe); ?>%</small>
                                </td>
                                <td class="text-center">
                                    
                                    <?php if($dangDienRa): ?> <span class="badge bg-success"><i class="fa-solid fa-circle-play me-1"></i>Đang diễn ra</span>
                                    <?php elseif($daKetThuc): ?> <span class="badge bg-secondary"><i class="fa-solid fa-circle-check me-1"></i>Đã kết thúc</span>
                                    <?php else: ?> <span class="badge bg-info"><i class="fa-solid fa-clock me-1"></i>Sắp diễn ra</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('nhanvien.hoatdong_drl.show', $hd->MaHoatDong)); ?>" class="btn btn-sm btn-info text-white" title="Xem chi tiết"><i class="fa-solid fa-eye"></i></a>
                                        <a href="<?php echo e(route('nhanvien.hoatdong_drl.edit', $hd->MaHoatDong)); ?>" class="btn btn-sm btn-warning text-white" title="Chỉnh sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($hd->MaHoatDong); ?>" title="Xóa"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo e($hd->MaHoatDong); ?>" tabindex="-1">
                                        
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Xác nhận xóa</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-start p-4">
                                                    <p class="mb-3">Bạn có chắc chắn muốn xóa hoạt động <strong class="text-danger"><?php echo e($hd->TenHoatDong); ?></strong> không?</p>
                                                    <div class="alert alert-warning mb-0"><i class="fa-solid fa-exclamation-triangle me-2"></i><strong>Cảnh báo:</strong> Hành động này không thể hoàn tác!</div>
                                                </div>
                                                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>Hủy</button>
                                                    <form action="<?php echo e(route('nhanvien.hoatdong_drl.destroy', $hd->MaHoatDong)); ?>" method="POST" style="display: inline;"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash-can me-1"></i>Xóa</button></form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted"><i class="fa-solid fa-inbox fa-3x mb-3 d-block"></i>
                                        <h5>Không có hoạt động nào</h5>
                                        <p class="mb-0">Hãy thêm hoạt động mới để bắt đầu</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($hoatDongs->hasPages()): ?>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">Hiển thị <?php echo e($hoatDongs->firstItem()); ?> - <?php echo e($hoatDongs->lastItem()); ?> / <?php echo e($hoatDongs->total()); ?></div>
            <div><?php echo e($hoatDongs->appends(request()->query())->links('pagination.custom')); ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Giữ nguyên CSS */
    .avatar-sm {
        width: 40px;
        height: 40px;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header {
        border: none;
    }

    .btn {
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .table> :not(caption)>*>* {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }

    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }

    .progress-bar {
        border-radius: 10px;
    }

    .modal-content {
        border-radius: 12px;
    }

    .btn-group .btn {
        border-radius: 0;
    }

    .btn-group .btn:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .btn-group .btn:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }

    .sticky-top {
        top: 0;
        z-index: 10;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/hoatdong_drl/index.blade.php ENDPATH**/ ?>