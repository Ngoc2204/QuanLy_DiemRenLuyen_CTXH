<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DangKyHoatDongDrl;
use App\Models\DangKyHoatDongCtxh;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Thêm DB
use Illuminate\Support\Facades\Log; // Thêm Log

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

        // SỬA: Thêm 'thanhToan' vào 'with()'
        // Tải kèm Hóa đơn (nếu có) để lấy ID của hóa đơn
        $ctxhRegistrations = DangKyHoatDongCtxh::with('hoatdong.quydinh', 'thanhToan')
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
     * SỬA: Thêm logic xóa Hóa đơn khi hủy
     */
    public function huyCtxh($maDangKy)
    {
        $mssv = Auth::user()->TenDangNhap;
        $now = Carbon::now();

        // SỬA: Tải kèm 'thanhToan' để xóa
        $registration = DangKyHoatDongCtxh::with('hoatdong', 'thanhToan')->find($maDangKy);

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

        // Nếu có hóa đơn đã được thanh toán, không cho hủy từ phía sinh viên
        $thanhToanCheck = $registration->thanhToan;
        if ($thanhToanCheck && (($thanhToanCheck->TrangThai ?? '') == 'DaThanhToan')) {
            return redirect()->back()->with('error', 'Đã thanh toán cho đăng ký này, không thể hủy. Vui lòng liên hệ nhân viên để xử lý.');
        }

        // SỬA: Dùng Transaction để đảm bảo xóa cả Đơn ĐK và Hóa đơn
        try {
            DB::beginTransaction();
            
            // 1. Lấy hóa đơn liên kết (nếu có)
            $thanhToan = $registration->thanhToan;
            
            // 2. Xóa Đơn Đăng Ký
            $registration->delete();
            
            // 3. Nếu có hóa đơn -> Xóa luôn hóa đơn
            // (Lưu ý: Cần đảm bảo `thanh_toan_id` trong `dangkyhoatdongctxh`
            // được set là ON DELETE SET NULL hoặc CASCADE. 
            // Nếu là SET NULL, hóa đơn sẽ mồ côi, ta nên xóa thủ công)
            if ($thanhToan) {
                // Xóa hóa đơn
                $thanhToan->delete();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Đã hủy đăng ký hoạt động "' . $activity->TenHoatDong . '" thành công.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Lỗi hủy CTXH: " . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi hệ thống khi hủy. Vui lòng thử lại.');
        }
    }
}