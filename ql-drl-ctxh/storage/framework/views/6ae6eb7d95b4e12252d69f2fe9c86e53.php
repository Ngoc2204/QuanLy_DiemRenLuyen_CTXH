
<?php $__env->startSection('title', 'Xác nhận Thanh toán'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .payment-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,.08);
        padding: 2.5rem;
        max-width: 600px;
        margin: 2rem auto;
    }
    .payment-header {
        text-align: center;
        border-bottom: 2px dashed #e0e0e0;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .payment-header .icon {
        font-size: 3rem;
        color: #667eea;
    }
    .payment-header h3 {
        color: #333;
        font-weight: 700;
        margin-top: 1rem;
    }
    .payment-amount {
        font-size: 2.5rem;
        font-weight: 800;
        color: #f5576c;
        margin: 0.5rem 0;
    }
    .payment-amount sup {
        font-size: 1.5rem;
        top: -1em;
    }
    .info-list { list-style: none; padding: 0; }
    .info-list li {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-list li:last-child { border-bottom: 0; }
    .info-list .label { font-weight: 600; color: #6c757d; }
    .info-list .value { font-weight: 500; color: #333; text-align: right; }
    
    .method-wrapper { margin-top: 2rem; }
    .method-btn {
        display: block;
        width: 100%;
        padding: 1.25rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .method-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,.1);
    }
    .btn-cash {
        background-color: #e7f8f0;
        color: #0d9255;
        border-color: #a7f3d0;
    }
    .btn-online {
        background-color: #eef2ff;
        color: #4f46e5;
        border-color: #c7d2fe;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="payment-card">
        <div class="payment-header">
            <i class="fas fa-file-invoice-dollar icon"></i>
            <h3>Xác nhận Thanh toán</h3>
            <p class="text-muted">Vui lòng thanh toán để hoàn tất đăng ký</p>
            
            
            <h4 class="payment-amount"><?php echo e(number_format($thanhToan->TongTien, 0, ',', '.')); ?><sup>đ</sup></h4>
        </div>

        <ul class="info-list">
            <li>
                <span class="label">Hoạt động:</span>
                
                <span class="value"><?php echo e($hoatDong->TenHoatDong ?? 'Hoạt động Địa chỉ đỏ'); ?></span>
            </li>
            <li>
                <span class="label">Địa điểm:</span>
                <span class="value"><?php echo e($hoatDong->diaDiem->TenDiaDiem ?? 'N/A'); ?></span>
            </li>
            <li>
                <span class="label">Mã hóa đơn:</span>
                <span class="value">HD-<?php echo e(str_pad($thanhToan->id, 6, '0', STR_PAD_LEFT)); ?></span>
            </li>
            <li>
                <span class="label">Mã Đăng Ký:</span>
                
                
                
                <span class="value">DK-<?php echo e(str_pad($thanhToan->dangKyHoatDong->MaDangKy, 6, '0', STR_PAD_LEFT)); ?></span>
            </li>
        </ul>

        <div class="method-wrapper">
            <h5 class="text-center mb-3 fw-600">Chọn phương thức thanh toán:</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <form action="<?php echo e(route('sinhvien.thanhtoan.chon_phuong_thuc', $thanhToan->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="phuong_thuc" value="TienMat">
                        <button type="submit" class="btn method-btn btn-cash">
                            <i class="fas fa-money-bill-wave me-2"></i> Thanh toán Tiền mặt
                        </button>
                    </form>
                </div>
                <div class="col-md-6">
                     <form action="<?php echo e(route('sinhvien.thanhtoan.chon_phuong_thuc', $thanhToan->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="phuong_thuc" value="Online">
                        <button type="submit" class="btn method-btn btn-online">
                            <i class="fas fa-credit-card me-2"></i> Thanh toán Online
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/thanhtoan/show.blade.php ENDPATH**/ ?>