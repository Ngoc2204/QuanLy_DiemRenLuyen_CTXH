<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DangKyHoatDongDrl;
use App\Models\DangKyHoatDongCtxh;
use Carbon\Carbon;

class DangKyController extends Controller
{
    /**
     * Hiển thị trang quản lý các hoạt động đã đăng ký
     */
    public function index()
    {
        $mssv = Auth::user()->TenDangNhap;
        $now = Carbon::now();

        // Lấy tất cả DRL đã đăng ký (kèm theo thông tin hoạt động)
        $drlRegistrations = DangKyHoatDongDrl::with('hoatdong.quydinh')
            ->where('MSSV', $mssv)
            ->get()
            ->sortByDesc('hoatdong.ThoiGianBatDau'); // Sắp xếp theo ngày bắt đầu

        // Lấy tất cả CTXH đã đăng ký (kèm theo thông tin hoạt động)
        $ctxhRegistrations = DangKyHoatDongCtxh::with('hoatdong.quydinh')
            ->where('MSSV', $mssv)
            ->get()
            ->sortByDesc('hoatdong.ThoiGianBatDau'); // Sắp xếp

        return view('sinhvien.quanly_dangky.index', [
            'drlRegistrations' => $drlRegistrations,
            'ctxhRegistrations' => $ctxhRegistrations,
            'currentTime' => $now // Truyền thời gian hiện tại sang View
        ]);
    }

    /**
     * Xử lý Hủy đăng ký hoạt động DRL
     */
    public function huyDrl($maDangKy)
    {
        $mssv = Auth::user()->TenDangNhap;
        $now = Carbon::now();

        // 1. Tìm đăng ký
        $registration = DangKyHoatDongDrl::with('hoatdong')->find($maDangKy);

        // 2. Kiểm tra
        if (!$registration) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn đăng ký.');
        }
        
        // 3. Kiểm tra xem có đúng là của sinh viên này không
        if ($registration->MSSV != $mssv) {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy đăng ký này.');
        }

        // 4. Kiểm tra thời hạn hủy
        $activity = $registration->hoatdong;
        if ($activity->ThoiHanHuy && $now->gt($activity->ThoiHanHuy)) {
            return redirect()->back()->with('error', 'Đã quá thời hạn hủy cho hoạt động này.');
        }

        // 5. Xóa đăng ký
        $registration->delete();

        return redirect()->back()->with('success', 'Đã hủy đăng ký hoạt động "' . $activity->TenHoatDong . '" thành công.');
    }

    /**
     * Xử lý Hủy đăng ký hoạt động CTXH
     */
    public function huyCtxh($maDangKy)
    {
        $mssv = Auth::user()->TenDangNhap;
        $now = Carbon::now();

        $registration = DangKyHoatDongCtxh::with('hoatdong')->find($maDangKy);

        if (!$registration) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn đăng ký.');
        }
        
        if ($registration->MSSV != $mssv) {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy đăng ký này.');
        }

        $activity = $registration->hoatdong;
        if ($activity->ThoiHanHuy && $now->gt($activity->ThoiHanHuy)) {
            return redirect()->back()->with('error', 'Đã quá thời hạn hủy cho hoạt động này.');
        }

        $registration->delete();

        return redirect()->back()->with('success', 'Đã hủy đăng ký hoạt động "' . $activity->TenHoatDong . '" thành công.');
    }
}
