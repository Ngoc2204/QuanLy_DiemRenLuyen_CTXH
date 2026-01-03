 
<?php $__env->startSection('title', 'Điều chỉnh Điểm Rèn Luyện'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-card {
        background: #fdfdfd;
        border: 1px solid #e9ecef;
        border-radius: 12px;
    }
    .history-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
    }
    .form-control, .form-select { border-radius: 8px; }
    .table-vcenter { vertical-align: middle !important; }
    .diem-am { color: #dc3545; font-weight: 700; }
    .diem-duong { color: #198754; font-weight: 700; }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Chỉnh Select2 cho giống Bootstrap 5 */
    .select2-container { width: 100% !important; }
    .select2-container .select2-selection--single {
        height: 38px;
        border-radius: 8px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
        color: #212529;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-dropdown { border-radius: 8px; border: 1px solid #ced4da; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <h3 class="mb-4">Điều chỉnh Điểm Rèn Luyện Thủ công</h3>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <!-- <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?> -->
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Vui lòng kiểm tra lại:</strong>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    
    <div class="card form-card p-4 mb-4">
        <h5 class="mb-3">Áp dụng Khen thưởng / Vi phạm</h5>
        <form action="<?php echo e(route('nhanvien.dieuchinh_drl.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="MSSV" class="form-label fw-bold">MSSV (*)</label>
                    <input type="text" class="form-control" id="MSSV" name="MSSV" value="<?php echo e(old('MSSV')); ?>" placeholder="200122..." required>
                </div>
                <div class="col-md-3">
                    <label for="MaHocKy" class="form-label fw-bold">Học kỳ (*)</label>
                    <select class="form-select" id="MaHocKy" name="MaHocKy" required>
                        <option value="">-- Chọn học kỳ --</option>
                        <?php $__currentLoopData = $hocKys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($hk->MaHocKy); ?>" <?php echo e(old('MaHocKy') == $hk->MaHocKy ? 'selected' : ''); ?>>
                                <?php echo e($hk->TenHocKy); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    
                    <label for="MaDiem" class="form-label fw-bold">Quy định áp dụng (*)</label>
                    <select class="form-select" id="MaDiem" name="MaDiem" required>
                        <option value="">-- Chọn quy định... --</option>
                        <?php $__currentLoopData = $quyDinhs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($qd->MaDiem); ?>" <?php echo e(old('MaDiem') == $qd->MaDiem ? 'selected' : ''); ?>>
                                [<?php echo e($qd->MaDiem); ?>] <?php echo e($qd->TenCongViec); ?> (<?php echo e($qd->DiemNhan); ?>đ)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i>Thêm Điều chỉnh</button>
            </div>
        </form>
    </div>

    
    <div class="card history-card">
        <div class="card-header">
            <h5 class="mb-0">Lịch sử Điều chỉnh</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-vcenter mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sinh viên</th>
                            <th>Học kỳ</th>
                            <th>Nội dung áp dụng</th> 
                            <th class="text-center">Số điểm</th> 
                            <th>Người cập nhật</th>
                            <th>Ngày Cập nhật</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $dieuChinhs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($item->sinhvien->HoTen ?? 'N/A'); ?></strong>
                                    <div><?php echo e($item->MSSV); ?></div>
                                </td>
                                <td><?php echo e($item->hocky->TenHocKy ?? $item->MaHocKy); ?></td>
                                
                                <td>
                                    <?php if($item->quydinh): ?>
                                        [<?php echo e($item->quydinh->MaDiem); ?>] <?php echo e($item->quydinh->TenCongViec); ?>

                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($item->quydinh): ?>
                                        <?php $diem = $item->quydinh->DiemNhan; ?>
                                        <?php if($diem > 0): ?>
                                            <span class="diem-duong">+<?php echo e($diem); ?></span>
                                        <?php else: ?>
                                            <span class="diem-am"><?php echo e($diem); ?></span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($item->nhanvien->TenNV ?? $item->MaNV); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($item->NgayCapNhat)->format('d/m/Y H:i')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center p-5">
                                    <p class="text-muted mb-0">Chưa có lịch sử điều chỉnh nào.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($dieuChinhs->hasPages()): ?>
                <div class="card-footer border-top-0">
                    <?php echo e($dieuChinhs->appends(request()->query())->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?> 

<?php $__env->startPush('scripts'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Áp dụng Select2 cho dropdown Quy định
        $('#MaDiem').select2({
            placeholder: "-- Chọn quy định...",
            allowClear: true,
            theme: "default"
        });
        
        // (Tùy chọn) Áp dụng cho Học kỳ
        $('#MaHocKy').select2({
            placeholder: "-- Chọn học kỳ --",
            allowClear: false, // Bắt buộc chọn
            theme: "default"
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/dieuchinh_drl/index.blade.php ENDPATH**/ ?>