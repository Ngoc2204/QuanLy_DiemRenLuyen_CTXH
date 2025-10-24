<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập sinh viên</title>
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
                    <div class="col-lg-9 col-md-8 left-info">
                        <div class="news-container">
                            <!-- Tabs -->
                            <ul class="news-tabs">
                                <li class="active">THÔNG BÁO CHUNG</li>
                                <li>ĐIỂM RÈN LUYỆN</li>
                                <li>ĐIỂM CÔNG TÁC XÃ HỘI</li>

                            </ul>

                            <!-- Danh sách tin -->
                            <div class="news-list">
                                <div class="news-item">
                                    <div class="news-date">
                                        <span class="month">Tháng 10</span>
                                        <span class="day">06</span>
                                    </div>
                                    <div class="news-content">
                                        <a href="#" class="news-title">
                                            Thông báo cổ vũ văn nghệ cấp khoa Công Nghệ Thông Tin
                                        </a>
                                        <span class="news-detail">Xem chi tiết</span>
                                    </div>
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
                                    <form id="form-login" class="form-login" method="POST" action="{{ route('login.post') }}">
                                        @csrf
                                        <div class="form-login">
                                            <div class="text-center">
                                                <img src="{{ asset('images/congthongtinsinhvien.png') }}" alt="Thông tin sinh viên" class="img-fluid" style="max-height:95px;">
                                            </div>
                                            <h4>ĐĂNG NHẬP HỆ THỐNG</h4>

                                            <div class="group">
                                                <input type="text" id="TenDangNhap" name="TenDangNhap" class="input form-control mb-2" placeholder="Nhập mã sinh viên" required>
                                                <input type="password" id="password" name="password" class="input form-control mb-2" placeholder="Nhập mật khẩu" required>

                                                <div class="box-captcha">
                                                    <input type="text" id="Captcha" name="Captcha" placeholder="Nhập mã" class="form-control">
                                                    <a href="javascript:void(0)" class="captcharefresh"></a>
                                                    <img src="{{ asset('images/captcha-demo.jpg') }}" id="newcaptcha" alt="captcha">
                                                </div>
                                            </div>

                                            @if ($errors->any())
                                            <div class="alert alert-danger text-center">
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
                                                <a href="https://apps.apple.com/us/app/oneuni/id1673685126" target="_blank">
                                                    <img src="{{ asset('images/store_appstore.svg') }}" width="150">
                                                </a><br>
                                                <a href="https://play.google.com/store/apps/details?id=vn.com.oneuni" target="_blank">
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
</body>

</html>