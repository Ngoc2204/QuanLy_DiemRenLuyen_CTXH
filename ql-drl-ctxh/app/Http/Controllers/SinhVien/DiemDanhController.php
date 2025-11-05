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
        // Lấy user đã đăng nhập (Giả sử là model Taikhoan)
        $user = Auth::user(); 
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để điểm danh.');
        }

        // SỬA LỖI LOGIC: Lấy MSSV từ 'TenDangNhap'
        $mssv = $user->TenDangNhap;
        
        if (!$mssv) {
             return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Không tìm thấy thông tin sinh viên. Vui lòng đăng nhập lại.']);
        }

        // 1. Tìm hoạt động dựa trên token
        $hoatDong = null;
        $type = null;
        $now = Carbon::now(); // Lấy thời gian hiện tại

        $hoatDongDrl = HoatDongDrl::where('CheckInToken', $token)->orWhere('CheckOutToken', $token)->first();
        if ($hoatDongDrl) {
            $hoatDong = $hoatDongDrl;
            $type = 'DRL';
        } else {
            $hoatDongCtxh = HoatDongCtxh::where('CheckInToken', $token)->orWhere('CheckOutToken', $token)->first();
            if ($hoatDongCtxh) {
                $hoatDong = $hoatDongCtxh;
                $type = 'CTXH';
            }
        }

        if (!$hoatDong) {
            return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Mã điểm danh không hợp lệ.']);
        }

        // 2. Kiểm tra token hết hạn (nếu có)
        // Sửa: Dùng ThoiGianKetThuc làm mốc
        if ($now->gt($hoatDong->ThoiGianKetThuc->addHours(1))) { // Cho phép trễ 1 giờ sau khi kết thúc
             return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Đã quá thời gian điểm danh cho hoạt động này.']);
        }

        // 3. Tìm bản ghi đăng ký của sinh viên
        $dangKy = null;
        if ($type == 'DRL') {
            $dangKy = DangKyHoatDongDrl::where('MaHoatDong', $hoatDong->MaHoatDong)
                                        ->where('MSSV', $mssv)
                                        ->first();
        } else {
            $dangKy = DangKyHoatDongCtxh::where('MaHoatDong', $hoatDong->MaHoatDong)
                                        ->where('MSSV', $mssv)
                                        ->first();
        }

        if (!$dangKy) {
            return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Bạn chưa đăng ký tham gia hoạt động này.']);
        }

        if ($dangKy->TrangThaiDangKy != 'Đã duyệt') {
            return view('sinhvien.scan_result', ['status' => 'error', 'message' => "Đăng ký của bạn đang ở trạng thái '{$dangKy->TrangThaiDangKy}'."]);
        }

        // 4. Xử lý Check-in / Check-out
        if ($hoatDong->CheckInToken == $token) {
            // Xử lý Check-in
            if ($dangKy->CheckInAt) {
                return view('sinhvien.scan_result', ['status' => 'warning', 'message' => 'Bạn đã check-in rồi.']);
            }
            // KIỂM TRA THỜI GIAN CHECK-IN
            if ($now->lt($hoatDong->ThoiGianBatDau->subMinutes(30))) { // Không cho check-in quá sớm (trước 30 phút)
                return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Chưa đến thời gian check-in. Vui lòng quay lại sau.']);
            }

            $dangKy->CheckInAt = $now;
            $dangKy->TrangThaiThamGia = 'Đang tham gia'; // Cập nhật
            $dangKy->save();
            return view('sinhvien.scan_result', ['status' => 'success', 'message' => 'Check-in thành công!']);

        } elseif ($hoatDong->CheckOutToken == $token) {
            // Xử lý Check-out
            if (!$dangKy->CheckInAt) {
                return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Bạn phải Check-in trước khi Check-out.']);
            }
            if ($dangKy->CheckOutAt) {
                return view('sinhvien.scan_result', ['status' => 'warning', 'message' => 'Bạn đã check-out rồi.']);
            }
            // KIỂM TRA THỜI GIAN CHECK-OUT
            if ($now->lt($hoatDong->ThoiGianKetThuc->subMinutes(15))) { // Không cho check-out quá sớm (trước 15 phút)
                return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Chưa đến thời gian check-out. Vui lòng quay lại sau.']);
            }

            $dangKy->CheckOutAt = $now;
            $dangKy->TrangThaiThamGia = 'Đã tham gia'; // Cập nhật
            $dangKy->save();
            return view('sinhvien.scan_result', ['status' => 'success', 'message' => 'Check-out thành công! Chúc mừng bạn đã hoàn thành.']);
        }
        
        return view('sinhvien.scan_result', ['status' => 'error', 'message' => 'Lỗi không xác định.']);
    }
}