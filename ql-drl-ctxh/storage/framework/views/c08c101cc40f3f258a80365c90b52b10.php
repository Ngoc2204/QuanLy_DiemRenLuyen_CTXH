

<?php $__env->startSection('title', 'Sửa Hoạt động CTXH'); ?>
<?php $__env->startSection('page_title', 'Chỉnh sửa Hoạt động CTXH'); ?>

<?php
    // Breadcrumbs
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.hoatdong_ctxh.index'), 'title' => 'Hoạt động CTXH'],
        ['url' => '#', 'title' => 'Chỉnh sửa'],
    ];
?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-section { background: #f8f9fa; padding: 1.5rem; border-radius: 12px; border: 1px solid #e9ecef; }
    .section-header h6 { font-weight: 600; }
    .section-header hr { border-top: 2px solid currentColor; opacity: 0.2; }
    .form-label { font-weight: 600; color: #495057; margin-bottom: 0.5rem; display: flex; align-items: center; }
    .form-label i { width: 20px; text-align: center; }
    .form-control, .form-select { border-radius: 8px; border: 1px solid #dee2e6; padding: 0.625rem 0.875rem; transition: all 0.3s ease; }
    .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
    .form-control:disabled, .form-control[readonly] { background-color: #f8f9fa; border-color: #e9ecef; }
    .input-group-text { background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; font-weight: 500; }
    .btn { border-radius: 8px; padding: 0.625rem 1.25rem; font-weight: 500; transition: all 0.3s ease; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); }
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; }
    .btn-primary:hover { background: linear-gradient(135deg, #5568d3 0%, #653a8b 100%); }
    .alert { border-radius: 12px; }
    .card { border-radius: 12px; overflow: hidden; }
    .shadow-sm { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important; }
    .text-muted { color: #6c757d !important; }
    textarea.form-control { resize: vertical; min-height: 100px; }
    .form-control:invalid:not(:placeholder-shown) { border-color: #dc3545; }
    .form-control:valid:not(:placeholder-shown) { border-color: #28a745; }
    input[list]::-webkit-calendar-picker-indicator { opacity: 0.6; }
    .form-group { margin-bottom: 1rem; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-gradient py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="mb-0 text-white">
            <i class="fa-solid fa-pen-to-square me-2"></i>
            Chỉnh sửa Hoạt động: <?php echo e($hoatdong_ctxh->TenHoatDong); ?>

        </h5>
    </div>

    <div class="card-body p-4">
        
        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fa-solid fa-exclamation-circle me-2 mt-1"></i>
                    <div class="flex-grow-1">
                        <strong>Có lỗi xảy ra:</strong>
                        <ul class="mb-0 mt-2">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-exclamation-circle me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('nhanvien.hoatdong_ctxh.update', $hoatdong_ctxh->MaHoatDong)); ?>" method="POST" id="editForm" novalidate>
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-primary mb-0">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        Thông tin cơ bản
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="MaHoatDong" class="form-label">
                                <i class="fa-solid fa-hashtag me-1 text-muted"></i>
                                Mã Hoạt động
                            </label>
                            <input type="text" class="form-control" id="MaHoatDong" value="<?php echo e($hoatdong_ctxh->MaHoatDong); ?>" disabled readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="LoaiHoatDong" class="form-label">
                                <i class="fa-solid fa-tag me-1 text-muted"></i>
                                Phân loại
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="LoaiHoatDong" name="LoaiHoatDong" required>
                                <option value="Tình nguyện" <?php echo e(old('LoaiHoatDong', $hoatdong_ctxh->LoaiHoatDong) == 'Tình nguyện' ? 'selected' : ''); ?>>Tình nguyện</option>
                                <option value="Hội thảo" <?php echo e(old('LoaiHoatDong', $hoatdong_ctxh->LoaiHoatDong) == 'Hội thảo' ? 'selected' : ''); ?>>Hội thảo</option>
                                <option value="Tập huấn" <?php echo e(old('LoaiHoatDong', $hoatdong_ctxh->LoaiHoatDong) == 'Tập huấn' ? 'selected' : ''); ?>>Tập huấn</option>
                                <option value="Địa chỉ đỏ" <?php echo e(old('LoaiHoatDong', $hoatdong_ctxh->LoaiHoatDong) == 'Địa chỉ đỏ' ? 'selected' : ''); ?>>Địa chỉ đỏ</option>
                                <option value="Học thuật" <?php echo e(old('LoaiHoatDong', $hoatdong_ctxh->LoaiHoatDong) == 'Học thuật' ? 'selected' : ''); ?>>Học thuật</option>
                                <option value="Văn hóa - Văn nghệ" <?php echo e(old('LoaiHoatDong', $hoatdong_ctxh->LoaiHoatDong) == 'Văn hóa - Văn nghệ' ? 'selected' : ''); ?>>Văn hóa - Văn nghệ</option>
                                <option value="Thể dục - Thể thao" <?php echo e(old('LoaiHoatDong', $hoatdong_ctxh->LoaiHoatDong) == 'Thể dục - Thể thao' ? 'selected' : ''); ?>>Thể dục - Thể thao</option>
                                <option value="Khác" <?php echo e(old('LoaiHoatDong', $hoatdong_ctxh->LoaiHoatDong) == 'Khác' ? 'selected' : ''); ?>>Khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="TenHoatDong" class="form-label">
                                <i class="fa-solid fa-heading me-1 text-muted"></i>
                                Tên Hoạt động
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="TenHoatDong" name="TenHoatDong" value="<?php echo e(old('TenHoatDong', $hoatdong_ctxh->TenHoatDong)); ?>" required placeholder="Nhập tên hoạt động">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="MoTa" class="form-label">
                                <i class="fa-solid fa-align-left me-1 text-muted"></i>
                                Mô tả
                            </label>
                            <textarea class="form-control" id="MoTa" name="MoTa" rows="4" placeholder="Nhập mô tả chi tiết về hoạt động..."><?php echo e(old('MoTa', $hoatdong_ctxh->MoTa)); ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="DiaDiem" class="form-label">
                                <i class="fa-solid fa-location-dot me-1 text-muted"></i>
                                Địa điểm cụ thể (Ghi chú) <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="DiaDiem" name="DiaDiem" value="<?php echo e(old('DiaDiem', $hoatdong_ctxh->DiaDiem)); ?>" placeholder="Ví dụ: Sảnh A, Phòng B102..." required>
                        </div>
                    </div>

                    
                    <div class="row g-3" id="diaChiDoFields" style="display: none; width: 100%; margin-left: 0;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dot_id" class="form-label">
                                    <i class="fa-solid fa-calendar-week me-1 text-muted"></i>
                                    Thuộc Đợt
                                    <span class="text-danger" id="dot_id_star" style="display: none;">*</span>
                                </label>
                                <select class="form-select" id="dot_id" name="dot_id">
                                    <option value="">-- Chọn Đợt --</option>
                                    <?php $__empty_1 = true; $__currentLoopData = $dots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option value="<?php echo e($dot->id); ?>" <?php echo e(old('dot_id', $hoatdong_ctxh->dot_id) == $dot->id ? 'selected' : ''); ?>>
                                            <?php echo e($dot->TenDot); ?> (<?php echo e($dot->TrangThai); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <option value="" disabled>Không có đợt nào.</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="diadiem_id" class="form-label">
                                    <i class="fa-solid fa-map-location-dot me-1 text-muted"></i>
                                    Địa điểm tổ chức
                                    <span class="text-danger" id="diadiem_id_star" style="display: none;">*</span>
                                </label>
                                <select class="form-select" id="diadiem_id" name="diadiem_id">
                                    <option value="">-- Chọn Địa điểm --</option>
                                    <?php $__currentLoopData = $diadiems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diadiem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($diadiem->id); ?>" <?php echo e(old('diadiem_id', $hoatdong_ctxh->diadiem_id) == $diadiem->id ? 'selected' : ''); ?>>
                                            <?php echo e($diadiem->TenDiaDiem); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-success mb-0">
                        <i class="fa-solid fa-calendar-days me-2"></i>
                        Thời gian tổ chức
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ThoiGianBatDau" class="form-label">
                                <i class="fa-solid fa-calendar-plus me-1 text-success"></i>
                                Thời gian Bắt đầu
                                <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control" id="ThoiGianBatDau" name="ThoiGianBatDau" value="<?php echo e(old('ThoiGianBatDau', $hoatdong_ctxh->ThoiGianBatDau ? $hoatdong_ctxh->ThoiGianBatDau->format('Y-m-d\TH:i') : '')); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ThoiGianKetThuc" class="form-label">
                                <i class="fa-solid fa-calendar-xmark me-1 text-danger"></i>
                                Thời gian Kết thúc
                                <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control" id="ThoiGianKetThuc" name="ThoiGianKetThuc" value="<?php echo e(old('ThoiGianKetThuc', $hoatdong_ctxh->ThoiGianKetThuc ? $hoatdong_ctxh->ThoiGianKetThuc->format('Y-m-d\TH:i') : '')); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="ThoiHanHuy" class="form-label">
                                <i class="fa-solid fa-clock-rotate-left me-1 text-warning"></i>
                                Thời hạn Hủy đăng ký <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control" id="ThoiHanHuy" name="ThoiHanHuy" value="<?php echo e(old('ThoiHanHuy', $hoatdong_ctxh->ThoiHanHuy ? $hoatdong_ctxh->ThoiHanHuy->format('Y-m-d\TH:i') : '')); ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-warning mb-0">
                        <i class="fa-solid fa-chart-simple me-2"></i>
                        Điểm số & Số lượng
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="SoLuong" class="form-label">
                                <i class="fa-solid fa-users me-1 text-primary"></i>
                                Số lượng tối đa
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="SoLuong" name="SoLuong" value="<?php echo e(old('SoLuong', $hoatdong_ctxh->SoLuong)); ?>" min="1" required placeholder="0">
                                <span class="input-group-text">SV</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="current_registered" class="form-label">
                                <i class="fa-solid fa-user-check me-1 text-success"></i>
                                Đã đăng ký
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="current_registered" value="<?php echo e($hoatdong_ctxh->dangKy_count ?? $hoatdong_ctxh->dangKy()->count()); ?>" disabled readonly>
                                <span class="input-group-text">SV</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="MaQuyDinhDiem" class="form-label">
                                <i class="fa-solid fa-clipboard-list me-1 text-info"></i>
                                Quy định điểm liên quan
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="MaQuyDinhDiem" name="MaQuyDinhDiem" required>
                                <option value="">-- Chọn quy định điểm --</option>
                                <?php $__currentLoopData = $quyDinhDiems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $maDiem => $tenCongViec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($maDiem); ?>" <?php echo e(old('MaQuyDinhDiem', $hoatdong_ctxh->MaQuyDinhDiem) == $maDiem ? 'selected' : ''); ?>>
                                        <?php echo e($maDiem); ?> - <?php echo e($tenCongViec); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="alert alert-info border-0 shadow-sm mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-lightbulb fa-2x text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="alert-heading mb-2">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Lưu ý khi chỉnh sửa
                        </h6>
                        <ul class="mb-0 small">
                            <li>Nếu giảm số lượng tối đa, cần kiểm tra số lượng sinh viên đã đăng ký</li>
                            <li>Thay đổi thời gian có thể ảnh hưởng đến lịch đăng ký của sinh viên</li>
                            <li>Chọn 'Địa chỉ đỏ' sẽ yêu cầu chọn Đợt và Địa điểm tổ chức.</li>
                        </ul>
                    </div>
                </div>
            </div>

            
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="<?php echo e(route('nhanvien.hoatdong_ctxh.index')); ?>" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                </a>
                <div>
                    <button type="reset" class="btn btn-outline-warning me-2">
                        <i class="fa-solid fa-rotate-left me-2"></i>Đặt lại
                    </button>
                    <button type="submit" class="btn btn-primary shadow">
                        <i class="fa-solid fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle khối Địa chỉ đỏ
        const loaiHoatDongSelect = document.getElementById('LoaiHoatDong');
        const diaChiDoFields = document.getElementById('diaChiDoFields');
        const dotIdSelect = document.getElementById('dot_id');
        const diaDiemIdSelect = document.getElementById('diadiem_id');
        const dotIdStar = document.getElementById('dot_id_star');
        const diaDiemIdStar = document.getElementById('diadiem_id_star');

        function toggleDiaChiDoFields() {
            const isDiaChiDo = loaiHoatDongSelect.value === 'Địa chỉ đỏ';
            diaChiDoFields.style.display = isDiaChiDo ? 'flex' : 'none';
            [dotIdStar, diaDiemIdStar].forEach(el => el.style.display = isDiaChiDo ? 'inline' : 'none');
            [dotIdSelect, diaDiemIdSelect].forEach(el => isDiaChiDo ? el.setAttribute('required', 'required') : el.removeAttribute('required'));
        }

        // Validate số lượng tối đa không nhỏ hơn số đã đăng ký
        const form = document.getElementById('editForm');
        const soLuongInput = document.getElementById('SoLuong');
        const currentRegisteredInput = document.getElementById('current_registered');
        const currentRegistered = parseInt(currentRegisteredInput.value, 10) || 0;

        function validateSoLuong() {
            const newValue = parseInt(soLuongInput.value, 10);
            soLuongInput.setCustomValidity('');
            if (!isNaN(newValue) && newValue < currentRegistered) {
                soLuongInput.setCustomValidity(`Số lượng mới (${newValue}) không thể nhỏ hơn số lượng đã đăng ký (${currentRegistered}).`);
            }
        }

        // Validate thời gian
        const thoiGianBatDau = document.getElementById('ThoiGianBatDau');
        const thoiGianKetThuc = document.getElementById('ThoiGianKetThuc');
        const thoiHanHuy = document.getElementById('ThoiHanHuy');

        function validateDates() {
            const start = thoiGianBatDau.value ? new Date(thoiGianBatDau.value) : null;
            const end = thoiGianKetThuc.value ? new Date(thoiGianKetThuc.value) : null;
            const cancel = thoiHanHuy.value ? new Date(thoiHanHuy.value) : null;

            thoiGianKetThuc.setCustomValidity('');
            thoiHanHuy.setCustomValidity('');

            if (start && end && end <= start) {
                thoiGianKetThuc.setCustomValidity('Thời gian kết thúc phải sau thời gian bắt đầu.');
            }
            if (start && cancel && cancel >= start) {
                thoiHanHuy.setCustomValidity('Thời hạn hủy phải trước thời gian bắt đầu.');
            }
        }

        // Init
        toggleDiaChiDoFields();
        validateSoLuong();
        validateDates();

        // Listeners
        loaiHoatDongSelect.addEventListener('change', toggleDiaChiDoFields);
        soLuongInput.addEventListener('change', validateSoLuong);
        thoiGianBatDau.addEventListener('change', validateDates);
        thoiGianKetThuc.addEventListener('change', validateDates);
        thoiHanHuy.addEventListener('change', validateDates);

        // Submit
        form.addEventListener('submit', function (event) {
            validateSoLuong();
            validateDates();
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            form.classList.add('was-validated');
        }, false);

        // Reset
        const resetButton = form.querySelector('button[type="reset"]');
        if (resetButton) {
            resetButton.addEventListener('click', function () {
                form.classList.remove('was-validated');
                thoiGianKetThuc.setCustomValidity('');
                thoiHanHuy.setCustomValidity('');
                soLuongInput.setCustomValidity('');
                // Khôi phục giá trị Loại hoạt động ban đầu và toggle lại
                const originalLoai = <?php echo json_encode($hoatdong_ctxh->LoaiHoatDong, 15, 512) ?>;
                loaiHoatDongSelect.value = originalLoai;
                toggleDiaChiDoFields();
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/hoatdong_ctxh/edit.blade.php ENDPATH**/ ?>