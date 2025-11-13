<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use App\Models\HoatDongDRL; 
use App\Models\HoatDongCTXH; 
use App\Models\DangKyHoatDongDrl; // <-- THÊM
use App\Models\DangKyHoatDongCtxh; // <-- THÊM

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('vi');

        // Gửi dữ liệu đến layout 'layouts.sinhvien'
        View::composer('layouts.sinhvien', function ($view) {
            
            // Khởi tạo giá trị mặc định
            $displayNotifications = collect(); 
            $totalCount = 0;

            // Chỉ chạy logic nếu người dùng đã đăng nhập
            if (Auth::check()) {
                
                $user = Auth::user();
                
                // Kiểm tra vai trò SinhVien
                if (isset($user->VaiTro) && $user->VaiTro == 'SinhVien') {
                    
                    $mssv = $user->TenDangNhap;
                    $now = Carbon::now();
                    $nextSevenDays = $now->copy()->addDays(7);

                    // --- 1. TÍNH TOÁN UNREAD COUNT (Hoạt động đã duyệt và sắp diễn ra) ---
                    
                    $actionableCount = 0;

                    // Đếm DRL đã duyệt và sắp diễn ra trong 7 ngày
                    if (Schema::hasTable('dangkyhoatdongdrl') && Schema::hasTable('hoatdongdrl')) {
                        $actionableCount += DangKyHoatDongDrl::where('MSSV', $mssv)
                            ->where('TrangThaiDangKy', 'Đã duyệt')
                            ->whereHas('hoatdong', function ($query) use ($now, $nextSevenDays) {
                                 $query->whereBetween('ThoiGianBatDau', [$now, $nextSevenDays]);
                            })
                            ->count();
                    }

                    // Đếm CTXH đã duyệt và sắp diễn ra trong 7 ngày
                    if (Schema::hasTable('dangkyhoatdongctxh') && Schema::hasTable('hoatdongctxh')) {
                        $actionableCount += DangKyHoatDongCtxh::where('MSSV', $mssv)
                            ->where('TrangThaiDangKy', 'Đã duyệt')
                            ->whereHas('hoatdong', function ($query) use ($now, $nextSevenDays) {
                                 $query->whereBetween('ThoiGianBatDau', [$now, $nextSevenDays]);
                            })
                            ->count();
                    }
                    
                    $totalCount = $actionableCount;

                    // --- 2. Lấy 5 hoạt động SẮP DIỄN RA GẦN NHẤT (GENERAL NOTIFICATIONS) ---
                    
                    // Lấy các hoạt động DRL chung còn hạn
                    if (Schema::hasTable('hoatdongdrl')) {
                         $upcomingDRL = HoatDongDRL::where('ThoiGianBatDau', '>', $now)
                            ->orderBy('ThoiGianBatDau', 'asc')
                            ->take(5)
                            ->get();
                    } else {
                         $upcomingDRL = collect();
                    }
                    
                    // Lấy các hoạt động CTXH chung còn hạn
                    if (Schema::hasTable('hoatdongctxh')) {
                         $upcomingCTXH = HoatDongCTXH::where('ThoiGianBatDau', '>', $now)
                            ->orderBy('ThoiGianBatDau', 'asc')
                            ->take(5)
                            ->get();
                    } else {
                         $upcomingCTXH = collect();
                    }

                    // Gộp 2 danh sách, sắp xếp lại và lấy 5 cái gần nhất
                    $displayNotifications = $upcomingDRL->merge($upcomingCTXH)
                        ->sortBy('ThoiGianBatDau')
                        ->take(5);
                }
            }
            
            // 3. Truyền 2 biến này ra layout
            // $unreadCount (cho con số) và $notifications (cho danh sách dropdown)
            $view->with('unreadCount', $totalCount);
            $view->with('notifications', $displayNotifications);
        });
    }
}