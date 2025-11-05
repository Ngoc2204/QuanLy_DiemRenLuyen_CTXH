<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\HoatDongDRL;
use App\Models\HoatDongCTXH;
use Carbon\Carbon;
use App\Models\DangKyHoatDongDrl;  // <-- THÊM DÒNG NÀY
use App\Models\DangKyHoatDongCtxh; // <-- THÊM DÒNG NÀY
use Illuminate\Support\Facades\Auth; // <-- THÊM DÒNG NÀY

class ActivityNotificationController extends Controller
{
    /**
     * Hiển thị danh sách các hoạt động (DRL và CTXH) còn hạn.
     */
    public function index()
    {
        $now = Carbon::now();
        $mssv = Auth::user()->TenDangNhap; // Lấy MSSV của sinh viên

        // 1. Lấy danh sách ID các hoạt động đã đăng ký
        $registeredDrlIds = DangKyHoatDongDrl::where('MSSV', $mssv)
            ->pluck('MaHoatDong')
            ->all();
            
        $registeredCtxhIds = DangKyHoatDongCtxh::where('MSSV', $mssv)
            ->pluck('MaHoatDong')
            ->all();

        // 2. Lấy danh sách hoạt động
        $activitiesDRL = HoatDongDRL::with('quydinh', 'dangky') // 'dangky' để đếm số lượng
                                    ->where('ThoiGianKetThuc', '>', $now)
                                    ->orderBy('ThoiGianBatDau', 'asc')
                                    ->get();

        $activitiesCTXH = HoatDongCTXH::with('quydinh', 'dangky')
                                      ->where('ThoiGianKetThuc', '>', $now)
                                      ->orderBy('ThoiGianBatDau', 'asc')
                                      ->get();

        // 3. Trả về view với đầy đủ dữ liệu
        return view('sinhvien.thongbao_hoatdong.index', compact(
            'activitiesDRL', 
            'activitiesCTXH',
            'registeredDrlIds',  // <-- Truyền biến này
            'registeredCtxhIds'  // <-- Truyền biến này
        ));
    }

    public function registerDRL(Request $request, $maHoatDong)
    {
        $mssv = Auth::user()->TenDangNhap;
        $now = Carbon::now();

        // 1. Tìm hoạt động và đếm số lượng đã đăng ký
        $activity = HoatDongDRL::withCount('dangky')->find($maHoatDong);
        if (!$activity) {
            return redirect()->back()->with('error', 'Hoạt động không tồn tại.');
        }

        // 2. Kiểm tra hạn đăng ký (ví dụ: không cho đăng ký sau khi HĐ bắt đầu)
        if ($now->gt($activity->ThoiGianBatDau)) {
            return redirect()->back()->with('error', 'Đã quá hạn đăng ký cho hoạt động này.');
        }

        // 3. Kiểm tra đăng ký trùng lặp
        $isRegistered = DangKyHoatDongDrl::where('MSSV', $mssv)
                                         ->where('MaHoatDong', $maHoatDong)
                                         ->exists();
        if ($isRegistered) {
            return redirect()->back()->with('error', 'Bạn đã đăng ký hoạt động này rồi.');
        }

        // 4. Kiểm tra số lượng
        if ($activity->dangky_count >= $activity->SoLuong) {
            return redirect()->back()->with('error', 'Hoạt động đã đủ số lượng.');
        }

        // 5. Tạo đăng ký mới
        try {
            DangKyHoatDongDrl::create([
                'MSSV' => $mssv,
                'MaHoatDong' => $maHoatDong,
                'NgayDangKy' => $now,
                'TrangThaiDangKy' => 'Chờ duyệt', // Bạn có thể đổi thành 'Đã đăng ký' nếu muốn
            ]);

            return redirect()->back()->with('success', 'Đăng ký hoạt động "' . $activity->TenHoatDong . '" thành công.');

        } catch (\Exception $e) {
            // Nên Log lỗi lại: Log::error('Lỗi đăng ký DRL: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi hệ thống. Vui lòng thử lại.');
        }
    }

    /**
     * THÊM HÀM NÀY: Xử lý đăng ký hoạt động CTXH
     */
    public function registerCTXH(Request $request, $maHoatDong)
    {
        $mssv = Auth::user()->TenDangNhap;
        $now = Carbon::now();

        // 1. Tìm hoạt động
        $activity = HoatDongCTXH::withCount('dangky')->find($maHoatDong);
        if (!$activity) {
            return redirect()->back()->with('error', 'Hoạt động không tồn tại.');
        }

        // 2. Kiểm tra hạn đăng ký
        if ($now->gt($activity->ThoiGianBatDau)) {
            return redirect()->back()->with('error', 'Đã quá hạn đăng ký cho hoạt động này.');
        }

        // 3. Kiểm tra đăng ký trùng lặp
        $isRegistered = DangKyHoatDongCtxh::where('MSSV', $mssv)
                                         ->where('MaHoatDong', $maHoatDong)
                                         ->exists();
        if ($isRegistered) {
            return redirect()->back()->with('error', 'Bạn đã đăng ký hoạt động này rồi.');
        }

        // 4. Kiểm tra số lượng
        if ($activity->dangky_count >= $activity->SoLuong) {
            return redirect()->back()->with('error', 'Hoạt động đã đủ số lượng.');
        }

        // 5. Tạo đăng ký mới
        try {
            DangKyHoatDongCtxh::create([
                'MSSV' => $mssv,
                'MaHoatDong' => $maHoatDong,
                'NgayDangKy' => $now,
                'TrangThaiDangKy' => 'Chờ duyệt',
            ]);

            return redirect()->back()->with('success', 'Đăng ký hoạt động "' . $activity->TenHoatDong . '" thành công.');

        } catch (\Exception $e) {
            // Nên Log lỗi lại: Log::error('Lỗi đăng ký CTXH: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi hệ thống. Vui lòng thử lại.');
        }
    }
}