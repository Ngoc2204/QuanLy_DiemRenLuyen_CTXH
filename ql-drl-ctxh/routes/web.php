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
use App\Http\Controllers\SinhVien\ThanhToanController as SinhVienThanhToanController;
use App\Http\Controllers\NhanVien\QuanLyThanhToanController as NhanVienThanhToanController;
use App\Http\Controllers\SinhVien\RecommendedActivitiesController;


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

     // Thống kê
     Route::get('thongke/drl', [App\Http\Controllers\Admin\ThongKeController::class, 'drl'])->name('thongke.drl');
     Route::get('thongke/ctxh', [App\Http\Controllers\Admin\ThongKeController::class, 'ctxh'])->name('thongke.ctxh');
});

// Nhân Viên routes 
Route::middleware(['auth'])->prefix('nhanvien')->as('nhanvien.')->group(function () {
    Route::get('/', [NhanVienDashboardController::class, 'dashboard'])->name('home');

    // Quản lý Phan Hồi Sinh Viên (Thông Báo)
    Route::resource('thongbao', App\Http\Controllers\NhanVien\ThongBaoController::class);

    // Quản lý Hoạt Động CTXH
    Route::resource('hoatdong_ctxh', NhanVienHoatDongCTXHController::class);
    
    // Routes điểm danh - TÁCH RIÊNG CHECK-IN VÀ CHECK-OUT
    Route::get('hoatdong_ctxh/{hoatdong_ctxh}/create-checkin-qr', [NhanVienHoatDongCTXHController::class, 'createCheckInQr'])
        ->name('hoatdong_ctxh.create_checkin_qr');
    Route::post('hoatdong_ctxh/{hoatdong_ctxh}/generate-checkin-qr', [NhanVienHoatDongCTXHController::class, 'generateCheckInQr'])
        ->name('hoatdong_ctxh.generate_checkin_qr');
    
    Route::get('hoatdong_ctxh/{hoatdong_ctxh}/create-checkout-qr', [NhanVienHoatDongCTXHController::class, 'createCheckOutQr'])
        ->name('hoatdong_ctxh.create_checkout_qr');
    Route::post('hoatdong_ctxh/{hoatdong_ctxh}/generate-checkout-qr', [NhanVienHoatDongCTXHController::class, 'generateCheckOutQr'])
        ->name('hoatdong_ctxh.generate_checkout_qr');
    
    // Tổng kết điểm danh
    Route::post('hoatdong_ctxh/{hoatdong_ctxh}/finalize', [NhanVienHoatDongCTXHController::class, 'finalizeAttendance'])
        ->name('hoatdong_ctxh.finalize');

     Route::get('/create-batch-diachido', [NhanVienHoatDongCTXHController::class, 'createBatchDiaChiDo'])->name('create_batch');
     Route::post('/store-batch-diachido', [NhanVienHoatDongCTXHController::class, 'storeBatchDiaChiDo'])->name('store_batch');

     Route::get('hoatdong_ctxh/{hoatdong_ctxh}/ghi-nhan-ket-qua', [NhanVienHoatDongCTXHController::class, 'ghiNhanKetQua'])
        ->name('hoatdong_ctxh.ghi_nhan_ket_qua');
    Route::post('hoatdong_ctxh/{hoatdong_ctxh}/update-ket-qua', [NhanVienHoatDongCTXHController::class, 'updateKetQua'])
        ->name('hoatdong_ctxh.update_ket_qua');

    // Quản lý Hoạt Động DRL
    Route::resource('hoatdong_drl', NhanVienHoatDongDRLController::class);
    // Bổ sung route điểm danh cho DRL - Check-In
    Route::get('hoatdong_drl/{hoatdong_drl}/create-checkin-qr', [NhanVienHoatDongDRLController::class, 'createCheckInQr'])
        ->name('hoatdong_drl.create_checkin_qr');
    Route::post('hoatdong_drl/{hoatdong_drl}/generate-checkin-qr', [NhanVienHoatDongDRLController::class, 'generateCheckInQr'])
        ->name('hoatdong_drl.generate_checkin_qr');
    // Bổ sung route điểm danh cho DRL - Check-Out
    Route::get('hoatdong_drl/{hoatdong_drl}/create-checkout-qr', [NhanVienHoatDongDRLController::class, 'createCheckOutQr'])
        ->name('hoatdong_drl.create_checkout_qr');
    Route::post('hoatdong_drl/{hoatdong_drl}/generate-checkout-qr', [NhanVienHoatDongDRLController::class, 'generateCheckOutQr'])
        ->name('hoatdong_drl.generate_checkout_qr');
    Route::post('hoatdong_drl/{hoatdong_drl}/finalize', [NhanVienHoatDongDRLController::class, 'finalizeAttendance'])
        ->name('hoatdong_drl.finalize');

     Route::get('hoatdong_drl/{hoatdong_drl}/ghi-nhan-ket-qua', [NhanVienHoatDongDRLController::class, 'ghiNhanKetQua'])
        ->name('hoatdong_drl.ghi_nhan_ket_qua');
    Route::post('hoatdong_drl/{hoatdong_drl}/update-ket-qua', [NhanVienHoatDongDRLController::class, 'updateKetQua'])
        ->name('hoatdong_drl.update_ket_qua');

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

    // Thông tin cá nhân (Nhân viên)
    Route::get('thong-tin-ca-nhan', [App\Http\Controllers\NhanVien\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('thong-tin-ca-nhan', [App\Http\Controllers\NhanVien\ProfileController::class, 'update'])
        ->name('profile.update');
    Route::post('thong-tin-ca-nhan/doi-mat-khau', [App\Http\Controllers\NhanVien\ProfileController::class, 'updatePassword'])
        ->name('profile.update_password');

     // Quản lý thanh toán
     Route::prefix('quan-ly-thanh-toan')->name('thanhtoan.')->group(function () {
        Route::get('/', [NhanVienThanhToanController::class, 'index'])->name('index');
        Route::put('/xac-nhan/{id}', [NhanVienThanhToanController::class, 'xacNhanThanhToan'])->name('xacnhan');
    });
     
     // Điều chỉnh điểm rèn luyện
     Route::resource('dieuchinh_drl', App\Http\Controllers\NhanVien\DieuChinhDRLController::class);
});


Route::middleware(['auth'])->prefix('sinhvien')->as('sinhvien.')->group(function () {

    Route::get('/', [SinhVienDashboardController::class, 'dashboard'])->name('home');
    
    // === ONBOARDING ROUTES - Mandatory Interest Selection ===
    Route::get('/onboarding/interests', [App\Http\Controllers\SinhVien\OnboardingController::class, 'showInterestSelection'])->name('onboarding.interests');
    Route::post('/onboarding/interests/store', [App\Http\Controllers\SinhVien\OnboardingController::class, 'storeInterestSelection'])->name('onboarding.interests.store');
    Route::post('/onboarding/skip', [App\Http\Controllers\SinhVien\OnboardingController::class, 'skipInterestSelection'])->name('onboarding.skip');
    
    // ...
    // Route để xử lý quét QR
    Route::get('scan/{token}', [SinhVienDiemDanhController::class, 'handleScan'])
        ->where('token', '.*') // Cho phép token có ký tự đặc biệt (vd: base64)
        ->name('scan');

    // Route trang quét QR bằng camera (sinh viên mở camera để quét QR do nhân viên tạo)
    Route::get('scan-camera', function () {
        return view('sinhvien.scan.camera');
    })->name('scan.camera');

    // Route thông báo hoạt động
    Route::get('/thong-bao-hoat-dong', [ActivityNotificationController::class, 'index'])
        ->name('thongbao_hoatdong');

    Route::post('/dang-ky-drl/{maHoatDong}', [ActivityNotificationController::class, 'registerDRL'])
     ->name('dangky.drl');
     
    Route::post('/dang-ky-ctxh/{maHoatDong}', [ActivityNotificationController::class, 'registerCTXH'])
     ->name('dangky.ctxh');

    // Additional routes for timeline view compatibility
    Route::post('/drl/register/{maHoatDong}', [ActivityNotificationController::class, 'registerDRL'])
     ->name('drl.register');
     
    Route::post('/ctxh/register/{maHoatDong}', [ActivityNotificationController::class, 'registerCTXH'])
     ->name('ctxh.register');

    Route::get('/lich-hoat-dong', [WeeklyActivityController::class, 'index'])
         ->name('lich_tuan');  

    // 1. Thông tin sinh viên (Trang cá nhân)
    Route::get('/thong-tin', [SinhVienController::class, 'showProfile'])->name('profile.show');
    
    // 2. Chỉnh sửa thông tin
    Route::get('/chinh-sua', [SinhVienController::class, 'editProfile'])->name('profile.edit');
    Route::put('/chinh-sua', [SinhVienController::class, 'updateProfile'])->name('profile.update');

    // Thông báo
    Route::get('/tin-tuc', [ThongBaoController::class, 'index'])->name('tintuc.index');
    Route::get('/tin-tuc/{id}', [ThongBaoController::class, 'show'])->name('tintuc.show');

    // Hoạt động được đề xuất (Recommendation)
    Route::get('/de-xuat-hoat-dong', [RecommendedActivitiesController::class, 'index'])
         ->name('activities_recommended.index');
    Route::get('/de-xuat-hoat-dong/{id}', [RecommendedActivitiesController::class, 'show'])
         ->name('activities_recommended.show');
    Route::get('/api/activity/{id}/{type}', [RecommendedActivitiesController::class, 'getActivity'])
         ->name('api.get_activity');

    // Đổi mật khẩu
    Route::get('/doi-mat-khau', [App\Http\Controllers\SinhVien\PasswordController::class, 'showChangePasswordForm'])
         ->name('thongtin_sinhvien.password_edit');
    Route::post('/doi-mat-khau', [App\Http\Controllers\SinhVien\PasswordController::class, 'updatePassword'])
         ->name('thongtin_sinhvien.password_update');

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

     // Quản lý thanh toán
     Route::prefix('thanh-toan')->name('thanhtoan.')->group(function () {
        Route::get('/{id}', [SinhVienThanhToanController::class, 'show'])->name('show');
        Route::post('/{id}/chon-phuong-thuc', [SinhVienThanhToanController::class, 'chonPhuongThuc'])->name('chon_phuong_thuc');
          Route::get('/{id}/qr', [SinhVienThanhToanController::class, 'showQr'])->name('qr');
     });
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

    // Thông tin cá nhân (Giảng viên)
    Route::get('thong-tin-ca-nhan', [App\Http\Controllers\GiangVien\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('thong-tin-ca-nhan', [App\Http\Controllers\GiangVien\ProfileController::class, 'update'])
        ->name('profile.update');
    Route::post('thong-tin-ca-nhan/doi-mat-khau', [App\Http\Controllers\GiangVien\ProfileController::class, 'updatePassword'])
        ->name('profile.update_password');
});
