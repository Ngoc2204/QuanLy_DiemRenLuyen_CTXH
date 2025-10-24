<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SinhVienController as AdminSinhVienController;
use App\Http\Controllers\NhanVien\DashboardController as NhanVienDashboardController;


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


Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Trang dashboard theo vai trò
Route::middleware('auth')->group(function () {
    
    Route::get('/giangvien', fn() => view('giangvien.dashboard'))->name('giangvien.home');
    Route::get('/sinhvien', fn() => view('sinhvien.dashboard'))->name('sinhvien.home');
});


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

    // Quản lý quy định điểm rèn luyện
    Route::resource('quydinhdiemrl', App\Http\Controllers\Admin\QuyDinhDiemRLController::class);

    // Quản lý quy định điểm CTXH
    Route::resource('quydinhdiemctxh', App\Http\Controllers\Admin\QuyDinhDiemCTXHController::class);

});

// Nhân Viên routes 
Route::middleware(['auth'])->prefix('nhanvien')->as('nhanvien.')->group(function () {
    Route::get('/', [NhanVienDashboardController::class, 'dashboard'])->name('home');

    // Quản lý Phan Hồi Sinh Viên (Thông Báo)
    Route::resource('thongbao', App\Http\Controllers\NhanVien\ThongBaoController::class);

    // Quản lý Hoạt Động CTXH
    Route::resource('hoatdong_ctxh', App\Http\Controllers\NhanVien\HoatDongCTXHController::class);

    // Quản lý Hoạt Động DRL
    Route::resource('hoatdong_drl', App\Http\Controllers\NhanVien\HoatDongDRLController::class);

});
