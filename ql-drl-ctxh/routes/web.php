<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SinhVienController as AdminSinhVienController;
use App\Http\Controllers\NhanVien\DashboardController as NhanVienDashboardController;
use App\Http\Controllers\NhanVien\HoatDongCTXHController as NhanVienHoatDongCTXHController;
use App\Http\Controllers\NhanVien\HoatDongDRLController as NhanVienHoatDongDRLController;
use App\Http\Controllers\SinhVien\DiemDanhController as SinhVienDiemDanhController;
use App\Http\Controllers\GiangVien\DashboardController as GiangVienDashboardController;
use App\Http\Controllers\SinhVien\DashboardController as SinhVienDashboardController;
use App\Http\Controllers\SinhVien\ActivityNotificationController;
use App\Http\Controllers\SinhVien\WeeklyActivityController;
use App\Http\Controllers\SinhVien\SinhVienController;
use App\Http\Controllers\SinhVien\ThongBaoController;


Route::get('/', function () {
    if (Auth::check()) {
        // Nếu đã đăng nhập, kiểm tra vai trò và chuyển hướng trực tiếp
        $role = Auth::user()->VaiTro; // Lấy vai trò (Đảm bảo tên cột 'VaiTro' là chính xác)

        return match ($role) {
            'Admin'     => redirect()->route('admin.home'),
            'NhanVien'  => redirect()->route('nhanvien.home'),
            'GiangVien' => redirect()->route('giangvien.home'),
            'SinhVien'  => redirect()->route('sinhvien.home'),
            default     => redirect('/home'),
        };
    } else {
        return redirect()->route('login');
    }
});

// Routes cho captcha đơn giản
Route::get('/captcha-image', [AuthController::class, 'getCaptchaImage'])->name('captcha.image');
Route::get('/refresh-captcha', [AuthController::class, 'refreshCaptcha'])->name('captcha.refresh');




Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// =============================================================
// === ROUTE TẠO QR ĐỘNG CHO MODAL (CẦN MIDDLEWARE AUTH) ===
// =============================================================
Route::get('/qr-generator', function (Request $request) {
    // Sử dụng $request->validate() thay vì validate()
    $request->validate([
        'url' => 'required|url',
        'size' => 'nullable|integer|min:50|max:500'
    ]);

    $url = $request->input('url');
    $size = $request->input('size', 300); // Kích thước mặc định 300px

    // Tạo mã QR dưới dạng SVG
    $svg = QrCode::size($size)
        ->format('svg') // Đảm bảo trả về SVG
        ->margin(1)     // Thêm margin nhỏ
        ->generate($url);

    // Trả về SVG với đúng content type
    return response($svg)->header('Content-Type', 'image/svg+xml');
})->middleware('auth')->name('qr.generator'); // Đặt tên và yêu cầu đăng nhập




// Admin routes 
Route::middleware(['auth'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('home');

    // Quản lý sinh viên
    Route::resource('sinhvien', AdminSinhVienController::class);

    // Quản lý khoa
    Route::resource('khoa', App\Http\Controllers\Admin\KhoaController::class);

    // Quản lý lớp
    Route::resource('lop', App\Http\Controllers\Admin\LopController::class);

    // Quản lý nhân viên
    Route::resource('nhanvien', App\Http\Controllers\Admin\NhanVienController::class);

    // Quản lý giảng viên
    Route::resource('giangvien', App\Http\Controllers\Admin\GiangVienController::class);
    Route::get('giangvien/{MaGV}/assign-lop', [App\Http\Controllers\Admin\GiangVienController::class, 'assignLopForm'])
        ->name('giangvien.assign');

    Route::post('giangvien/{MaGV}/assign-lop', [App\Http\Controllers\Admin\GiangVienController::class, 'assignLop'])
        ->name('giangvien.assign.post');

    // Quản lý năm học
    Route::resource('namhoc', App\Http\Controllers\Admin\NamHocController::class);

    // Quản lý học kỳ
    Route::resource('hocky', App\Http\Controllers\Admin\HocKyController::class);

    // Quản lý quy định điểm rèn luyện
    Route::resource('quydinhdiemrl', App\Http\Controllers\Admin\QuyDinhDiemRLController::class);

    // Quản lý quy định điểm CTXH
    Route::resource('quydinhdiemctxh', App\Http\Controllers\Admin\QuyDinhDiemCTXHController::class);

     // Quản lý đợt địa chỉ đỏ
     Route::resource('dotdiachido', App\Http\Controllers\Admin\DotDiaChiDoController::class);

     // Quản lý địa điểm địa chỉ đỏ
     Route::resource('diadiem', App\Http\Controllers\Admin\DiaDiemDiaChiDoController::class);
});

// Nhân Viên routes 
Route::middleware(['auth'])->prefix('nhanvien')->as('nhanvien.')->group(function () {
    Route::get('/', [NhanVienDashboardController::class, 'dashboard'])->name('home');

    // Quản lý Phan Hồi Sinh Viên (Thông Báo)
    Route::resource('thongbao', App\Http\Controllers\NhanVien\ThongBaoController::class);

    // Quản lý Hoạt Động CTXH
    Route::resource('hoatdong_ctxh', NhanVienHoatDongCTXHController::class);
    // Bổ sung route điểm danh cho CTXH
    Route::post('hoatdong_ctxh/{hoatdong_ctxh}/generate-qr', [NhanVienHoatDongCTXHController::class, 'generateQrTokens'])
        ->name('hoatdong_ctxh.generate_qr');
    Route::post('hoatdong_ctxh/{hoatdong_ctxh}/finalize', [NhanVienHoatDongCTXHController::class, 'finalizeAttendance'])
        ->name('hoatdong_ctxh.finalize');


    // Quản lý Hoạt Động DRL
    Route::resource('hoatdong_drl', NhanVienHoatDongDRLController::class);
    // Bổ sung route điểm danh cho DRL
    Route::post('hoatdong_drl/{hoatdong_drl}/generate-qr', [NhanVienHoatDongDRLController::class, 'generateQrTokens'])
        ->name('hoatdong_drl.generate_qr');
    Route::post('hoatdong_drl/{hoatdong_drl}/finalize', [NhanVienHoatDongDRLController::class, 'finalizeAttendance'])
        ->name('hoatdong_drl.finalize');

    // 1. Route để hiển thị trang danh sách (hàm index)
    Route::get('duyet-dang-ky', [App\Http\Controllers\NhanVien\DuyetDangKyController::class, 'index'])
         ->name('duyet_dang_ky.index');
         
    // 2. Route để duyệt/từ chối 1 sinh viên (hàm update)
    Route::put('duyet-dang-ky/{duyet_dang_ky}', [App\Http\Controllers\NhanVien\DuyetDangKyController::class, 'update'])
         ->name('duyet_dang_ky.update');
         
    // 3. Route MỚI để duyệt hàng loạt theo ngày (hàm batchApprove)
    Route::post('duyet-dang-ky/batch-approve', [App\Http\Controllers\NhanVien\DuyetDangKyController::class, 'batchApprove'])
         ->name('duyet_dang_ky.batch_approve');

    // Thống kê điểm rèn luyện và CTXH
    Route::get('thong-ke', [App\Http\Controllers\NhanVien\ThongKeController::class, 'index'])
         ->name('thongke.index');

});


Route::middleware(['auth'])->prefix('sinhvien')->as('sinhvien.')->group(function () {

    Route::get('/', [SinhVienDashboardController::class, 'dashboard'])->name('home');
    // ...
    // Route để xử lý quét QR
    Route::get('/scan/{token}', [SinhVienDiemDanhController::class, 'handleScan'])
        ->name('scan'); // Tên là 'scan', kết hợp với prefix 'sinhvien.' thành 'sinhvien.scan' 
    // ...

    // Route thông báo hoạt động
    Route::get('/thong-bao-hoat-dong', [ActivityNotificationController::class, 'index'])
        ->name('thongbao_hoatdong');

    Route::post('/dang-ky-drl/{maHoatDong}', [ActivityNotificationController::class, 'registerDRL'])
     ->name('dangky.drl');
     
    Route::post('/dang-ky-ctxh/{maHoatDong}', [ActivityNotificationController::class, 'registerCTXH'])
     ->name('dangky.ctxh');

    Route::get('/lich-hoat-dong', [WeeklyActivityController::class, 'index'])
         ->name('lich_tuan');  

    // 1. Thông tin sinh viên (Trang cá nhân)
    Route::get('/thong-tin', [SinhVienController::class, 'showProfile'])->name('profile.show');
    
    // 2. Chỉnh sửa thông tin
    Route::get('/chinh-sua', [SinhVienController::class, 'editProfile'])->name('profile.edit');
    Route::put('/chinh-sua', [SinhVienController::class, 'updateProfile'])->name('profile.update');
    
    // 3. Thông tin học tập
    Route::get('/hoc-tap', [SinhVienController::class, 'showAcademics'])->name('academics.show');
    
    // 4. Đề xuất tốt nghiệp (Giả định đây là một trang thông tin)
    // Route GET (Hiển thị trang)
    Route::get('/tot-nghiep', [SinhVienController::class, 'showGraduation'])->name('graduation.show');
    // Route POST (Cập nhật ngày)
    Route::post('/tot-nghiep', [SinhVienController::class, 'updateGraduation'])->name('graduation.update');

    // Thông báo
    Route::get('/tin-tuc', [ThongBaoController::class, 'index'])->name('news.index');

    // Đổi mật khẩu
    Route::get('/doi-mat-khau', [App\Http\Controllers\SinhVien\PasswordController::class, 'showChangePasswordForm'])
         ->name('thongtin_sinhvien.password_edit');
    Route::post('/doi-mat-khau', [App\Http\Controllers\SinhVien\PasswordController::class, 'updatePassword'])
         ->name('thongtin_sinhvien.password_edit');

    // Quản lý đăng ký hoạt động
    Route::get('/quan-ly-dang-ky', [App\Http\Controllers\SinhVien\DangKyController::class, 'index'])
         ->name('quanly_dangky.index');
    Route::delete('/quan-ly-dang-ky/huy-drl/{maDangKy}', [App\Http\Controllers\SinhVien\DangKyController::class, 'huyDrl'])
         ->name('quanly_dangky.huy_drl');
    Route::delete('/quan-ly-dang-ky/huy-ctxh/{maDangKy}', [App\Http\Controllers\SinhVien\DangKyController::class, 'huyCtxh'])
         ->name('quanly_dangky.huy_ctxh');

    // Điểm rèn luyện
    Route::get('/diem-ren-luyen', [App\Http\Controllers\SinhVien\DiemRenLuyenController::class, 'index'])
         ->name('diem_ren_luyen');
    
    // Điểm công tác xã hội
    Route::get('/diem-cong-tac-xa-hoi', [App\Http\Controllers\SinhVien\DiemCTXHController::class, 'index'])
         ->name('diem_cong_tac_xa_hoi');
    
    // Liên hệ - Phản hồi
    Route::get('/lien-he', [App\Http\Controllers\SinhVien\LienHeController::class, 'create'])
         ->name('lienhe.create');
    Route::post('/lien-he', [App\Http\Controllers\SinhVien\LienHeController::class, 'store'])
         ->name('lienhe.store');
});


// --- GIẢNG VIÊN ROUTES ---
Route::middleware(['auth'])->prefix('giangvien')->as('giangvien.')->group(function (){
    
    Route::get('/', [GiangVienDashboardController::class, 'dashboard'])->name('home');

    Route::get('/lop/diem-ren-luyen', [App\Http\Controllers\GiangVien\LopController::class, 'xemDiemDRL'])
         ->name('lop.diem_drl');
         
    Route::get('/lop/diem-ctxh', [App\Http\Controllers\GiangVien\LopController::class, 'xemDiemCTXH'])
         ->name('lop.diem_ctxh');

    Route::get('/lop/ban-can-su', [App\Http\Controllers\GiangVien\LopController::class, 'indexBanCanSu'])
         ->name('lop.bancansu');

    Route::post('/lop/ban-can-su/update', [App\Http\Controllers\GiangVien\LopController::class, 'updateBanCanSu'])
         ->name('lop.bancansu.update');

    Route::get('/lop/bao-cao', [App\Http\Controllers\GiangVien\LopController::class, 'indexBaoCao'])
         ->name('lop.baocao');
         
    Route::get('/lop/bao-cao/export', [App\Http\Controllers\GiangVien\LopController::class, 'exportBaoCao'])
         ->name('lop.baocao.export');


    Route::get('/hoatdong/phan-bo', [App\Http\Controllers\GiangVien\PhanBoController::class, 'index'])
         ->name('hoatdong.phanbo.index');
         
    Route::get('/hoatdong/phan-bo/{hoatdong_drl}', [App\Http\Controllers\GiangVien\PhanBoController::class, 'edit'])
         ->name('hoatdong.phanbo.edit');
         
    Route::post('/hoatdong/phan-bo/{hoatdong_drl}', [App\Http\Controllers\GiangVien\PhanBoController::class, 'update'])
         ->name('hoatdong.phanbo.update');
});
