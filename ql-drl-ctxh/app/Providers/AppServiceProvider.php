<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Import View
use Illuminate\Support\Facades\Auth; // Import Auth
use Illuminate\Support\Facades\Schema; // Import Schema
use Illuminate\Support\Carbon;
use App\Models\HoatDongDRL; // <-- THAY ĐỔI: Import DRL
use App\Models\HoatDongCTXH; // <-- THAY ĐỔI: Import CTXH

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
            $displayNotifications = collect(); // Collection rỗng
            $totalCount = 0;

            // Chỉ chạy logic nếu người dùng đã đăng nhập
            if (Auth::check()) {
                
                $user = Auth::user();
                
                // Kiểm tra vai trò SinhVien
                if (isset($user->VaiTro) && $user->VaiTro == 'SinhVien') {
                    
                    // --- LOGIC ĐÃ SỬA ---
                    $now = Carbon::now();

                    // 1. Đếm số hoạt động còn hạn (Giống hệt DashboardController)
                    $countDRL = 0;
                    $countCTXH = 0;

                    if (Schema::hasTable('hoatdongdrl')) {
                         $countDRL = HoatDongDRL::where('ThoiGianKetThuc', '>', $now)->count();
                    }
                    if (Schema::hasTable('hoatdongctxh')) {
                        $countCTXH = HoatDongCTXH::where('ThoiGianKetThuc', '>', $now)->count();
                    }
                    
                    $totalCount = $countDRL + $countCTXH;

                    // 2. Lấy 5 hoạt động SẮP DIỄN RA GẦN NHẤT để hiển thị trong dropdown
                    $upcomingDRL = HoatDongDRL::where('ThoiGianBatDau', '>', $now)
                                             ->orderBy('ThoiGianBatDau', 'asc')
                                             ->take(5)
                                             ->get();
                                             
                    $upcomingCTXH = HoatDongCTXH::where('ThoiGianBatDau', '>', $now)
                                              ->orderBy('ThoiGianBatDau', 'asc')
                                              ->take(5)
                                              ->get();

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
