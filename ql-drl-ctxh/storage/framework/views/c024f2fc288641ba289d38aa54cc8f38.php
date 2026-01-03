<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="shortcut icon" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap&subset=vietnamese" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
</head>

<body>
    <main class="wapper-login">
        <header class="header">
            <div class="container text-center">
                <div class="logo-login-main">
                    <a href="<?php echo e(url('/')); ?>">
                        <img src="<?php echo e(asset('images/sv_header_login.png')); ?>" alt="Logo sinh viên">
                    </a>
                </div>
            </div>
        </header>

        <section class="main-content">
            <div class="container-fluid">
                <div class="row">
                    
                    <div class="col-lg-9 col-md-8 left-info">
                        <div class="news-container">
                            <!-- Tabs -->
                            <ul class="news-tabs">
                                <li class="active" data-tab="chung">THÔNG BÁO CHUNG</li>
                                <li data-tab="drl">ĐIỂM RÈN LUYỆN</li>
                                <li data-tab="ctxh">ĐIỂM CÔNG TÁC XÃ HỘI</li>
                            </ul>

                            <!-- Tab Content Wrapper -->
                            <div class="news-list-wrapper">
                                
                                <div class="news-list" id="tab-chung">
                                    <?php $__empty_1 = true; $__currentLoopData = $thongBaoChung ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="news-item">
                                        <div class="news-date">
                                            <span class="month"><?php echo e(optional($tb->ThoiGianBatDau)->isoFormat('MMM')); ?></span>
                                            <span class="day"><?php echo e(optional($tb->ThoiGianBatDau)->format('d')); ?></span>
                                        </div>
                                        <div class="news-content">
                                            <a href="#" class="news-title" title="<?php echo e($tb->TenHoatDong); ?>">
                                                [<?php echo e($tb->type); ?>] <?php echo e(Str::limit($tb->TenHoatDong, 80)); ?>

                                            </a>
                                            <span class="news-detail">Xem chi tiết</span>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-muted text-center py-3">Không có thông báo nào.</p>
                                    <?php endif; ?>
                                </div>

                                
                                <div class="news-list" id="tab-drl" style="display: none;">
                                    <?php $__empty_1 = true; $__currentLoopData = $thongBaoDrl ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="news-item">
                                        <div class="news-date">
                                            <span class="month"><?php echo e(optional($tb->ThoiGianBatDau)->isoFormat('MMM')); ?></span>
                                            <span class="day"><?php echo e(optional($tb->ThoiGianBatDau)->format('d')); ?></span>
                                        </div>
                                        <div class="news-content">
                                            <a href="#" class="news-title" title="<?php echo e($tb->TenHoatDong); ?>">
                                                <?php echo e(Str::limit($tb->TenHoatDong, 80)); ?>

                                            </a>
                                            <span class="news-detail">Xem chi tiết</span>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-muted text-center py-3">Không có thông báo điểm rèn luyện nào.</p>
                                    <?php endif; ?>
                                </div>

                                
                                <div class="news-list" id="tab-ctxh" style="display: none;">
                                    <?php $__empty_1 = true; $__currentLoopData = $thongBaoCtxh ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="news-item">
                                        <div class="news-date">
                                            <span class="month"><?php echo e(optional($tb->ThoiGianBatDau)->isoFormat('MMM')); ?></span>
                                            <span class="day"><?php echo e(optional($tb->ThoiGianBatDau)->format('d')); ?></span>
                                        </div>
                                        <div class="news-content">
                                            <a href="#" class="news-title" title="<?php echo e($tb->TenHoatDong); ?>">
                                                <?php echo e(Str::limit($tb->TenHoatDong, 80)); ?>

                                            </a>
                                            <span class="news-detail">Xem chi tiết</span>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-muted text-center py-3">Không có thông báo công tác xã hội nào.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Xem thêm -->
                            <div class="news-more">
                                <a href="#">XEM THÊM</a>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-lg-3 col-md-4">
                        <div class="bg-form authfy-login">
                            <div class="form-wrap h-100 w-100">
                                <div class="authfy-panel panel-login active">
                                    <form id="form-login" class="form-login" method="POST" action="<?php echo e(route('login.post')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-login">
                                            <div class="text-center">
                                                <img src="<?php echo e(asset('images/congthongtinsinhvien.png')); ?>" alt="Thông tin sinh viên" class="img-fluid" style="max-height:95px;">
                                            </div>
                                            <h4>ĐĂNG NHẬP HỆ THỐNG</h4>

                                            <div class="group">
                                                <input type="text" id="TenDangNhap" name="TenDangNhap" class="input form-control mb-2" placeholder="Nhập mã sinh viên" required value="<?php echo e(old('TenDangNhap')); ?>">
                                                <input type="password" id="password" name="password" class="input form-control mb-2" placeholder="Nhập mật khẩu" required>

                                                <div class="box-captcha">
                                                    <input type="text"
                                                        id="Captcha"
                                                        name="Captcha"
                                                        placeholder="Nhập mã"
                                                        class="form-control"
                                                        autocomplete="off"
                                                        required>
                                                    <a href="javascript:void(0)" class="captcharefresh" onclick="refreshCaptcha()"></a>
                                                    <img src="<?php echo e(route('captcha.image')); ?>"
                                                        id="newcaptcha"
                                                        alt="captcha"
                                                        style="cursor: pointer;"
                                                        onclick="refreshCaptcha()">
                                                </div>

                                                <?php $__errorArgs = ['Captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <small class="text-danger d-block mt-1"><?php echo e($message); ?></small>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


                                            </div>

                                            <?php if($errors->any()): ?>
                                            <div class="alert alert-danger text-center p-2" style="font-size: 0.85rem;">
                                                <?php echo e($errors->first()); ?>

                                            </div>
                                            <?php endif; ?>

                                            <input type="submit" class="button btn btn-primary w-100 mt-3" value="Đăng nhập">
                                        </div>
                                    </form>

                                    <div class="box-download-app text-center mt-4">
                                        <h6>Tải App Mobile sinh viên</h6>
                                        <div class="d-flex justify-content-center gap-3">
                                            <img src="<?php echo e(asset('images/img_qr_oneuni.png')); ?>" width="100">
                                            <div>
                                                <a href="https://apps.apple.com/us/app/oneuni/id1673685126" target="_blank" class="d-block mb-2">
                                                    <img src="<?php echo e(asset('images/store_appstore.svg')); ?>" width="150">
                                                </a>
                                                <a href="https://play.google.com/store/apps/details?id=vn.com.oneuni" target="_blank" class="d-block">
                                                    <img src="<?php echo e(asset('images/google_play.svg')); ?>" width="150">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <a href="https://oneuni.com.vn/" target="_blank" class="btn btn-info btn-sm text-white">Hướng dẫn sử dụng App OneUni</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Xử lý click vào tab
            $('.news-tabs li').on('click', function() {
                // Bỏ active khỏi tất cả li
                $('.news-tabs li').removeClass('active');

                // Thêm active cho li được click
                $(this).addClass('active');

                // Ẩn tất cả các news-list
                $('.news-list').hide();

                // Lấy data-tab của li được click
                var targetTab = $(this).data('tab');

                // Hiện news-list tương ứng
                $('#tab-' + targetTab).show();
            });
        });

        function refreshCaptcha() {
            // Lấy các phần tử cần thiết
            const refreshBtn = document.querySelector('.captcharefresh');
            const captchaImg = document.getElementById('newcaptcha');
            const captchaInput = document.getElementById('Captcha');
            
            // Disable nút
            if (refreshBtn) {
                refreshBtn.disabled = true;
                refreshBtn.style.opacity = '0.6';
            }
            
            // Fade out ảnh cũ
            captchaImg.style.opacity = '0.5';
            captchaImg.style.transition = 'opacity 0.3s ease';
            
            // Tạo URL mới với timestamp
            const timestamp = new Date().getTime();
            const newUrl = '<?php echo e(route("captcha.image")); ?>?t=' + timestamp;
            
            // Load ảnh mới
            const newImg = new Image();
            newImg.onload = function() {
                // Cập nhật src
                captchaImg.src = newUrl;
                captchaImg.style.opacity = '1';
                
                // Clear input Captcha
                captchaInput.value = '';
                captchaInput.focus();
                
                // Xóa tất cả error messages liên quan đến Captcha
                const groupDiv = captchaInput.closest('.group');
                if (groupDiv) {
                    const errors = groupDiv.querySelectorAll('.text-danger');
                    errors.forEach(err => {
                        // Chỉ xóa nếu là error của Captcha
                        if (err.textContent.includes('Captcha') || err.textContent.includes('mã')) {
                            err.remove();
                        }
                    });
                }
                
                // Re-enable nút
                if (refreshBtn) {
                    refreshBtn.disabled = false;
                    refreshBtn.style.opacity = '1';
                }
            };
            
            newImg.onerror = function() {
                // Restore ảnh cũ
                captchaImg.style.opacity = '1';
                if (refreshBtn) {
                    refreshBtn.disabled = false;
                    refreshBtn.style.opacity = '1';
                }
                console.error('Lỗi tải Captcha');
                alert('Không thể tải lại Captcha. Vui lòng thử lại!');
            };
            
            newImg.src = newUrl;
        }
    </script>
</body>

</html><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/auth/login.blade.php ENDPATH**/ ?>