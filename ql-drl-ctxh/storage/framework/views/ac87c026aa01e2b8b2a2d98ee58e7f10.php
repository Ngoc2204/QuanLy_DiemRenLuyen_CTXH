

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Card hiển thị thông tin hoạt động -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800"><?php echo e($hoatdong_drl->TenHoatDong); ?></h2>
            
            <div class="space-y-3 text-gray-700">
                <p><strong>Mã hoạt động:</strong> <?php echo e($hoatdong_drl->MaHoatDong); ?></p>
                <p><strong>Thời gian bắt đầu:</strong> <?php echo e($hoatdong_drl->ThoiGianBatDau ? $hoatdong_drl->ThoiGianBatDau->format('H:i - d/m/Y') : 'N/A'); ?></p>
                <p><strong>Thời gian kết thúc:</strong> <?php echo e($hoatdong_drl->ThoiGianKetThuc ? $hoatdong_drl->ThoiGianKetThuc->format('H:i - d/m/Y') : 'N/A'); ?></p>
                <p><strong>Số lượng:</strong> <?php echo e($hoatdong_drl->SoLuong); ?> sinh viên</p>
            </div>

            <?php if($hoatdong_drl->CheckOutToken && $hoatdong_drl->CheckOutOpenAt && $hoatdong_drl->CheckOutExpiresAt): ?>
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded">
                    <p class="text-sm font-semibold text-blue-900">✓ Mã check-out đã phát</p>
                    <p class="text-xs text-blue-700 mt-2">
                        Hiệu lực: <?php echo e($hoatdong_drl->CheckOutOpenAt->format('H:i - d/m/Y')); ?> 
                        đến <?php echo e($hoatdong_drl->CheckOutExpiresAt->format('H:i - d/m/Y')); ?>

                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Form tạo mã check-out -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-6 text-gray-800">
                <i class="fas fa-qrcode text-orange-600 mr-2"></i>Tạo Mã QR Check-Out
            </h3>

            <form action="<?php echo e(route('nhanvien.hoatdong_drl.generate_checkout_qr', $hoatdong_drl)); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>

                <!-- Thời gian mở quét -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-clock text-blue-500 mr-1"></i>Thời gian bắt đầu quét (Mở từ)
                    </label>
                    <input type="datetime-local" 
                           name="CheckOutOpenAt" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="<?php echo e(old('CheckOutOpenAt', $hoatdong_drl->ThoiGianKetThuc ? $hoatdong_drl->ThoiGianKetThuc->format('Y-m-d\TH:i') : '')); ?>"
                           required>
                    <?php $__errorArgs = ['CheckOutOpenAt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Thời gian hết hạn -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-hourglass-end text-red-500 mr-1"></i>Thời gian hết hạn (Đóng lúc)
                    </label>
                    <input type="datetime-local" 
                           name="CheckOutExpiresAt" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="<?php echo e(old('CheckOutExpiresAt', $hoatdong_drl->ThoiGianKetThuc ? $hoatdong_drl->ThoiGianKetThuc->format('Y-m-d\TH:i') : '')); ?>"
                           required>
                    <?php $__errorArgs = ['CheckOutExpiresAt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Nút submit -->
                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    <i class="fas fa-check-circle mr-2"></i>Phát Mã QR Check-Out
                </button>
            </form>

            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Lưu ý:</strong> Thời gian mở và đóng có thể điều chỉnh linh hoạt. 
                    Sinh viên chỉ có thể quét khi nằm trong khoảng thời gian này.
                </p>
            </div>
        </div>

    </div>

    <!-- Nút quay lại -->
    <div class="mt-6">
        <a href="<?php echo e(route('nhanvien.hoatdong_drl.show', $hoatdong_drl)); ?>" class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Quay Lại
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.nhanvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/nhanvien/hoatdong_drl/create_checkout_qr.blade.php ENDPATH**/ ?>