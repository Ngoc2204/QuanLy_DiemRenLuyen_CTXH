<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileApiController extends Controller
{
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'HoTen' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'SoDienThoai' => 'nullable|string|max:15',
            'DiaChi' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;

            // Cập nhật thông tin user
            if ($request->has('HoTen')) {
                $user->HoTen = $request->HoTen;
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            $user->save();

            // Cập nhật thông tin sinh viên nếu là sinh viên
            if ($sinhVien && ($request->has('SoDienThoai') || $request->has('DiaChi'))) {
                if ($request->has('SoDienThoai')) {
                    $sinhVien->SoDienThoai = $request->SoDienThoai;
                }
                if ($request->has('DiaChi')) {
                    $sinhVien->DiaChi = $request->DiaChi;
                }
                $sinhVien->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'HoTen' => $user->HoTen,
                        'email' => $user->email,
                        'SoDienThoai' => $sinhVien->SoDienThoai ?? null,
                        'DiaChi' => $sinhVien->DiaChi ?? null,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật thông tin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|same:new_password',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
            'new_password_confirmation.same' => 'Mật khẩu xác nhận không khớp.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            
            // Get the TaiKhoan record based on authenticated user
            $taiKhoan = \App\Models\TaiKhoan::where('TenDangNhap', $user->TenDangNhap)->first();
            
            if (!$taiKhoan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin tài khoản'
                ], 404);
            }

            // Kiểm tra mật khẩu hiện tại (assuming plain text password in database)
            if ($taiKhoan->MatKhau !== $request->current_password) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không đúng'
                ], 400);
            }

            // Cập nhật mật khẩu mới (plain text as per current system)
            $taiKhoan->MatKhau = $request->new_password;
            $taiKhoan->save();

            return response()->json([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể đổi mật khẩu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProfile(Request $request)
    {
        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;

            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin sinh viên'
                ], 404);
            }

            // Lấy thông tin lớp và khoa
            $lop = $sinhVien->lop;
            $khoa = $lop ? $lop->khoa : null;

            // Lấy điểm rèn luyện và CTXH
            $diemRL = \App\Models\DiemRenLuyen::where('MSSV', $sinhVien->MSSV)
                ->orderBy('NgayCapNhat', 'desc')
                ->first();
            
            $diemCTXH = \App\Models\DiemCTXH::where('MSSV', $sinhVien->MSSV)->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'HoTen' => $sinhVien->HoTen,
                    'Email' => $sinhVien->Email,
                    'MSSV' => $sinhVien->MSSV,
                    'MaLop' => $sinhVien->MaLop,
                    'TenLop' => $lop ? $lop->TenLop : null,
                    'MaKhoa' => $khoa ? $khoa->MaKhoa : null,
                    'TenKhoa' => $khoa ? $khoa->TenKhoa : null,
                    'SDT' => $sinhVien->SDT,
                    'NgaySinh' => $sinhVien->NgaySinh,
                    'GioiTinh' => $sinhVien->GioiTinh,
                    'ThoiGianTotNghiepDuKien' => $sinhVien->ThoiGianTotNghiepDuKien,
                    'SoThich' => $sinhVien->SoThich,
                    'DiemRL' => $diemRL ? $diemRL->TongDiem : 0,
                    'XepLoaiRL' => $diemRL ? $diemRL->XepLoai : 'Chưa có',
                    'DiemCTXH' => $diemCTXH ? $diemCTXH->TongDiem : 0,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thông tin profile: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProfileComplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Email' => 'required|email|max:255',
            'SDT' => 'nullable|string|max:15',
            'ThoiGianTotNghiepDuKien' => 'nullable|date',
            'SoThich' => 'nullable|string|max:255',
        ], [
            'Email.required' => 'Vui lòng nhập email.',
            'Email.email' => 'Email không hợp lệ.',
            'ThoiGianTotNghiepDuKien.date' => 'Thời gian tốt nghiệp dự kiến không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;

            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin sinh viên'
                ], 404);
            }

            // Cập nhật thông tin sinh viên - chỉ các trường được phép chỉnh sửa
            $sinhVien->update([
                'Email' => $request->Email,
                'SDT' => $request->SDT,
                'ThoiGianTotNghiepDuKien' => $request->ThoiGianTotNghiepDuKien,
                'SoThich' => $request->SoThich,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công!',
                'data' => [
                    'Email' => $sinhVien->Email,
                    'SDT' => $sinhVien->SDT,
                    'ThoiGianTotNghiepDuKien' => $sinhVien->ThoiGianTotNghiepDuKien,
                    'SoThich' => $sinhVien->SoThich,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật thông tin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function submitFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'LoaiPhanHoi' => 'required|string|in:BaoLoi,GopY,HoTro,Khac',
            'TieuDe' => 'required|string|max:255',
            'NoiDung' => 'required|string|min:30',
        ], [
            'LoaiPhanHoi.required' => 'Vui lòng chọn loại phản hồi.',
            'LoaiPhanHoi.in' => 'Loại phản hồi không hợp lệ.',
            'TieuDe.required' => 'Vui lòng nhập tiêu đề.',
            'NoiDung.required' => 'Vui lòng nhập nội dung chi tiết.',
            'NoiDung.min' => 'Nội dung phản hồi cần ít nhất 30 ký tự.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;

            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin sinh viên'
                ], 404);
            }

            // Tạo mã phản hồi
            $maPhanHoi = time();

            // Ghép nội dung đầy đủ
            $noiDungDayDu = "[Phân loại: {$request->LoaiPhanHoi}]\n";
            $noiDungDayDu .= "TIÊU ĐỀ: {$request->TieuDe}\n\n";
            $noiDungDayDu .= "----------------------------------------\n";
            $noiDungDayDu .= $request->NoiDung;

            // Lưu vào database
            \App\Models\PhanHoiSinhVien::create([
                'MaPhanHoi' => $maPhanHoi,
                'MSSV' => $sinhVien->MSSV,
                'NoiDung' => $noiDungDayDu,
                'NgayGui' => now(),
                'TrangThai' => 'Chưa xử lý',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gửi phản hồi thành công! Cảm ơn bạn đã đóng góp ý kiến.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi phản hồi: ' . $e->getMessage()
            ], 500);
        }
    }
}