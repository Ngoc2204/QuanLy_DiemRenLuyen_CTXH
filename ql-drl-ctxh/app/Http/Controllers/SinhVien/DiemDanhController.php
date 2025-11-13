<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HoatDongDrl;
use App\Models\HoatDongCtxh;
use App\Models\DangKyHoatDongDrl;
use App\Models\DangKyHoatDongCtxh;
use Carbon\Carbon; // Thêm Carbon

class DiemDanhController extends Controller
{
    /**
     * Xử lý quét mã QR (hoặc nhấn nút).
     */
    public function handleScan(Request $request, $token)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để điểm danh.');
        $mssv = $user->TenDangNhap;
        if (!$mssv) return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Không tìm thấy thông tin sinh viên.']);

        $now = now();

        // Tìm hoạt động theo token
        $hoatDong = HoatDongDrl::where('CheckInToken', $token)->orWhere('CheckOutToken', $token)->first();
        $type = 'DRL';
        if (!$hoatDong) {
            $hoatDong = \App\Models\HoatDongCtxh::where('CheckInToken', $token)->orWhere('CheckOutToken', $token)->first();
            $type = $hoatDong ? 'CTXH' : null;
        }
        if (!$hoatDong) return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Mã điểm danh không hợp lệ.']);

        // Hết hạn token?
        if ($hoatDong->TokenExpiresAt && $now->gt($hoatDong->TokenExpiresAt)) {
            return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Mã điểm danh đã hết hạn.']);
        }

        // Lấy đăng ký
        $dangKy = $type === 'DRL'
            ? \App\Models\DangKyHoatDongDrl::where('MaHoatDong', $hoatDong->MaHoatDong)->where('MSSV', $mssv)->first()
            : \App\Models\DangKyHoatDongCtxh::where('MaHoatDong', $hoatDong->MaHoatDong)->where('MSSV', $mssv)->first();

        if (!$dangKy) {
            return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Bạn chưa đăng ký tham gia hoạt động này.']);
        }
        if ($dangKy->TrangThaiDangKy !== 'Đã duyệt') {
            return view('sinhvien.scan_result', ['status' => 'error', 'message' => "Đăng ký đang ở trạng thái '{$dangKy->TrangThaiDangKy}'."]);
        }

        // ---- CHỈ CẦN NHÂN VIÊN MỞ LÀ ĐƯỢC ----
        if ($hoatDong->CheckInToken === $token) {
            // Phải được mở check-in
            if (!$hoatDong->CheckInOpenAt || $now->lt($hoatDong->CheckInOpenAt)) {
                return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Chưa mở check-in. Vui lòng liên hệ Ban tổ chức.']);
            }

            if ($dangKy->CheckInAt) {
                return view('sinhvien.scan_result', ['status' => 'warning', 'message' => 'Bạn đã check-in rồi.']);
            }

            $dangKy->CheckInAt = $now;
            $dangKy->TrangThaiThamGia = 'Đang tham gia';
            $dangKy->save();

            return view('sinhvien.scan_result', ['status' => 'success', 'message' => 'Check-in thành công!']);
        }

        if ($hoatDong->CheckOutToken === $token) {
            // Phải được mở check-out
            if (!$hoatDong->CheckOutOpenAt || $now->lt($hoatDong->CheckOutOpenAt)) {
                return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Chưa mở check-out. Vui lòng liên hệ Ban tổ chức.']);
            }
            if (!$dangKy->CheckInAt) {
                return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Bạn phải Check-in trước khi Check-out.']);
            }
            if ($dangKy->CheckOutAt) {
                return view('sinhvien.scan_result', ['status' => 'warning', 'message' => 'Bạn đã check-out rồi.']);
            }

            $dangKy->CheckOutAt = $now;
            $dangKy->TrangThaiThamGia = 'Đã tham gia';
            $dangKy->save();

            return view('sinhvien.scan_result', ['status' => 'success', 'message' => 'Check-out thành công!']);
        }

        return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Lỗi không xác định.']);
    }
}
