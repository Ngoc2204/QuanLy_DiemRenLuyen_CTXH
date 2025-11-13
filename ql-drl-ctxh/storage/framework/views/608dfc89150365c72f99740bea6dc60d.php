

<?php $__env->startSection('title', 'Tạo Hàng Loạt Hoạt động ĐCĐ'); ?>
<?php $__env->startSection('page_title', 'Tạo Suất Hàng Loạt (Địa chỉ đỏ)'); ?>

<?php
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.hoatdong_ctxh.index'), 'title' => 'Hoạt động CTXH'],
        ['url' => '#', 'title' => 'Tạo Hàng Loạt ĐCĐ'],
    ];
?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-section{background:#f8f9fa;padding:1.5rem;border-radius:12px;border:1px solid #e9ecef}
    .form-label{font-weight:600;color:#495057}
    .form-label i{width:20px;text-align:center}
    .form-control,.form-select{border-radius:8px}
    .btn{border-radius:8px;font-weight:500}
    .btn-primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border:none}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm border-0">
    <div class="card-header py-3" style="background:linear-gradient(135deg,#f5576c 0%,#764ba2 100%)">
        <h5 class="mb-0 text-white">
            <i class="fa-solid fa-map-location-dot me-2"></i>
            Tạo Hàng Loạt Suất Hoạt Động (Địa Chỉ Đỏ)
        </h5>
    </div>

    <div class="card-body p-4">
        
        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Có lỗi xảy ra:</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('nhanvien.store_batch')); ?>" method="POST" novalidate>
            <?php echo csrf_field(); ?>

            
            <div class="form-section mb-4">
                <h6 class="text-primary mb-3">1. Thông tin chung (áp dụng cho mọi suất)</h6>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="TenHoatDongGoc" class="form-label">
                            Tên Hoạt động Gốc (sẽ tự thêm ngày) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="TenHoatDongGoc"
                            name="TenHoatDongGoc"
                            class="form-control <?php $__errorArgs = ['TenHoatDongGoc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('TenHoatDongGoc')); ?>"
                            placeholder="Ví dụ: Tham quan Dinh Độc Lập"
                            required
                        >
                        <?php $__errorArgs = ['TenHoatDongGoc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="dot_id" class="form-label">Thuộc Đợt <span class="text-danger">*</span></label>
                        <select
                            id="dot_id"
                            name="dot_id"
                            class="form-select <?php $__errorArgs = ['dot_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                            aria-required="true"
                        >
                            <option value="">-- Chọn Đợt --</option>
                            <?php $__currentLoopData = $dots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dot->id); ?>" <?php echo e(old('dot_id') == $dot->id ? 'selected' : ''); ?>>
                                    <?php echo e($dot->TenDot); ?>

                                    [<?php echo e(\Carbon\Carbon::parse($dot->NgayBatDau)->format('d/m')); ?>

                                     → <?php echo e(\Carbon\Carbon::parse($dot->NgayKetThuc)->format('d/m/Y')); ?>]
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['dot_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="diadiem_id" class="form-label">Địa điểm tổ chức <span class="text-danger">*</span></label>
                        <select
                            id="diadiem_id"
                            name="diadiem_id"
                            class="form-select <?php $__errorArgs = ['diadiem_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                            <option value="">-- Chọn Địa điểm --</option>
                            <?php $__currentLoopData = $diadiems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diadiem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($diadiem->id); ?>" <?php echo e(old('diadiem_id') == $diadiem->id ? 'selected' : ''); ?>>
                                    <?php echo e($diadiem->TenDiaDiem); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['diadiem_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="MaQuyDinhDiem" class="form-label">Quy định điểm <span class="text-danger">*</span></label>
                        <select
                            id="MaQuyDinhDiem"
                            name="MaQuyDinhDiem"
                            class="form-select <?php $__errorArgs = ['MaQuyDinhDiem'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                            <option value="">-- Chọn quy định điểm --</option>
                            <?php $__currentLoopData = $quyDinhDiems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $maDiem => $tenCongViec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($maDiem); ?>" <?php echo e(old('MaQuyDinhDiem') == $maDiem ? 'selected' : ''); ?>>
                                    <?php echo e($maDiem); ?> - <?php echo e($tenCongViec); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['MaQuyDinhDiem'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="DiaDiemCuThe" class="form-label">Địa điểm cụ thể (ghi chú) <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            id="DiaDiemCuThe"
                            name="DiaDiemCuThe"
                            class="form-control <?php $__errorArgs = ['DiaDiemCuThe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('DiaDiemCuThe', 'Tập trung tại cổng chính')); ?>"
                            required
                        >
                        <?php $__errorArgs = ['DiaDiemCuThe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            
            <div class="form-section mb-4">
                <h6 class="text-success mb-3">2. Thông tin suất hàng loạt (tạo lặp lại theo ngày)</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="NgayBatDauSuat" class="form-label">Tạo suất từ ngày <span class="text-danger">*</span></label>
                        <input
                            type="date"
                            id="NgayBatDauSuat"
                            name="NgayBatDauSuat"
                            class="form-control <?php $__errorArgs = ['NgayBatDauSuat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('NgayBatDauSuat')); ?>"
                            required
                        >
                        <?php $__errorArgs = ['NgayBatDauSuat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="NgayKetThucSuat" class="form-label">Đến hết ngày <span class="text-danger">*</span></label>
                        <input
                            type="date"
                            id="NgayKetThucSuat"
                            name="NgayKetThucSuat"
                            class="form-control <?php $__errorArgs = ['NgayKetThucSuat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('NgayKetThucSuat')); ?>"
                            required
                        >
                        <?php $__errorArgs = ['NgayKetThucSuat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4">
                        <label for="GioBatDau" class="form-label">Giờ bắt đầu (hàng ngày) <span class="text-danger">*</span></label>
                        <input
                            type="time"
                            id="GioBatDau"
                            name="GioBatDau"
                            class="form-control <?php $__errorArgs = ['GioBatDau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('GioBatDau')); ?>"
                            required
                        >
                        <?php $__errorArgs = ['GioBatDau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4">
                        <label for="GioKetThuc" class="form-label">Giờ kết thúc (hàng ngày) <span class="text-danger">*</span></label>
                        <input
                            type="time"
                            id="GioKetThuc"
                            name="GioKetThuc"
                            class="form-control <?php $__errorArgs = ['GioKetThuc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('GioKetThuc')); ?>"
                            required
                        >
                        <?php $__errorArgs = ['GioKetThuc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4">
                        <label for="SoLuongMoiNgay" class="form-label">Số lượng (mỗi ngày) <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            id="SoLuongMoiNgay"
                            name="SoLuongMoiNgay"
                            class="form-control <?php $__errorArgs = ['SoLuongMoiNgay'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('SoLuongMoiNgay')); ?>"
                            min="1"
                            required
                            placeholder="0"
                        >
                        <?php $__errorArgs = ['SoLuongMoiNgay'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="<?php echo e(route('nhanvien.hoatdong_ctxh.index')); ?>" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                </a>
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fa-solid fa-layer-group me-2"></i>Tạo Hàng Loạt
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/hoatdong_ctxh/create_batch.blade.php ENDPATH**/ ?>