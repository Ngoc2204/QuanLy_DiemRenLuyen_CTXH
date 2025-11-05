<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap&subset=vietnamese" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <main class="wapper-login">
        <header class="header">
            <div class="container text-center">
                <div class="logo-login-main">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/sv_header_login.png') }}" alt="Logo sinh viên">
                    </a>
                </div>
            </div>
        </header>

        <section class="main-content">
            <div class="container-fluid">
                <div class="row">
                    {{-- Cột Tin tức --}}
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
                                {{-- Tab Thông báo chung --}}
                                <div class="news-list" id="tab-chung">
                                    @forelse ($thongBaoChung ?? [] as $tb)
                                    <div class="news-item">
                                        <div class="news-date">
                                            <span class="month">{{ optional($tb->ThoiGianBatDau)->isoFormat('MMM') }}</span>
                                            <span class="day">{{ optional($tb->ThoiGianBatDau)->format('d') }}</span>
                                        </div>
                                        <div class="news-content">
                                            <a href="#" class="news-title" title="{{ $tb->TenHoatDong }}">
                                                [{{ $tb->type }}] {{ Str::limit($tb->TenHoatDong, 80) }}
                                            </a>
                                            <span class="news-detail">Xem chi tiết</span>
                                        </div>
                                    </div>
                                    @empty
                                    <p class="text-muted text-center py-3">Không có thông báo nào.</p>
                                    @endforelse
                                </div>

                                {{-- Tab Điểm rèn luyện --}}
                                <div class="news-list" id="tab-drl" style="display: none;">
                                    @forelse ($thongBaoDrl ?? [] as $tb)
                                    <div class="news-item">
                                        <div class="news-date">
                                            <span class="month">{{ optional($tb->ThoiGianBatDau)->isoFormat('MMM') }}</span>
                                            <span class="day">{{ optional($tb->ThoiGianBatDau)->format('d') }}</span>
                                        </div>
                                        <div class="news-content">
                                            <a href="#" class="news-title" title="{{ $tb->TenHoatDong }}">
                                                {{ Str::limit($tb->TenHoatDong, 80) }}
                                            </a>
                                            <span class="news-detail">Xem chi tiết</span>
                                        </div>
                                    </div>
                                    @empty
                                    <p class="text-muted text-center py-3">Không có thông báo điểm rèn luyện nào.</p>
                                    @endforelse
                                </div>

                                {{-- Tab Điểm CTXH --}}
                                <div class="news-list" id="tab-ctxh" style="display: none;">
                                    @forelse ($thongBaoCtxh ?? [] as $tb)
                                    <div class="news-item">
                                        <div class="news-date">
                                            <span class="month">{{ optional($tb->ThoiGianBatDau)->isoFormat('MMM') }}</span>
                                            <span class="day">{{ optional($tb->ThoiGianBatDau)->format('d') }}</span>
                                        </div>
                                        <div class="news-content">
                                            <a href="#" class="news-title" title="{{ $tb->TenHoatDong }}">
                                                {{ Str::limit($tb->TenHoatDong, 80) }}
                                            </a>
                                            <span class="news-detail">Xem chi tiết</span>
                                        </div>
                                    </div>
                                    @empty
                                    <p class="text-muted text-center py-3">Không có thông báo công tác xã hội nào.</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Xem thêm -->
                            <div class="news-more">
                                <a href="#">XEM THÊM</a>
                            </div>
                        </div>
                    </div>

                    {{-- Cột Form Đăng nhập --}}
                    <div class="col-lg-3 col-md-4">
                        <div class="bg-form authfy-login">
                            <div class="form-wrap h-100 w-100">
                                <div class="authfy-panel panel-login active">
                                    <form id="form-login" class="form-login" method="POST" action="{{ route('login.post') }}">
                                        @csrf
                                        <div class="form-login">
                                            <div class="text-center">
                                                <img src="{{ asset('images/congthongtinsinhvien.png') }}" alt="Thông tin sinh viên" class="img-fluid" style="max-height:95px;">
                                            </div>
                                            <h4>ĐĂNG NHẬP HỆ THỐNG</h4>

                                            <div class="group">
                                                <input type="text" id="TenDangNhap" name="TenDangNhap" class="input form-control mb-2" placeholder="Nhập mã sinh viên" required value="{{ old('TenDangNhap') }}">
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
                                                    <img src="{{ route('captcha.image') }}"
                                                        id="newcaptcha"
                                                        alt="captcha"
                                                        style="cursor: pointer;"
                                                        onclick="refreshCaptcha()">
                                                </div>

                                                @error('Captcha')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                @enderror


                                            </div>

                                            @if ($errors->any())
                                            <div class="alert alert-danger text-center p-2" style="font-size: 0.85rem;">
                                                {{ $errors->first() }}
                                            </div>
                                            @endif

                                            <input type="submit" class="button btn btn-primary w-100 mt-3" value="Đăng nhập">
                                        </div>
                                    </form>

                                    <div class="box-download-app text-center mt-4">
                                        <h6>Tải App Mobile sinh viên</h6>
                                        <div class="d-flex justify-content-center gap-3">
                                            <img src="{{ asset('images/img_qr_oneuni.png') }}" width="100">
                                            <div>
                                                <a href="https://apps.apple.com/us/app/oneuni/id1673685126" target="_blank" class="d-block mb-2">
                                                    <img src="{{ asset('images/store_appstore.svg') }}" width="150">
                                                </a>
                                                <a href="https://play.google.com/store/apps/details?id=vn.com.oneuni" target="_blank" class="d-block">
                                                    <img src="{{ asset('images/google_play.svg') }}" width="150">
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
            // Reload ảnh captcha với timestamp để tránh cache
            document.getElementById('newcaptcha').src = '{{ route("captcha.image") }}?' + Date.now();
        }
    </script>
</body>

</html>