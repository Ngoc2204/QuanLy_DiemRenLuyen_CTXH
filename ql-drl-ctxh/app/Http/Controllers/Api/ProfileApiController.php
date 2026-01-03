<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

            // Kiểm tra mật khẩu hiện tại
            if (!Hash::check($request->current_password, $taiKhoan->MatKhau)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không đúng'
                ], 400);
            }

            // Cập nhật mật khẩu mới (hash an toàn)
            $taiKhoan->MatKhau = Hash::make($request->new_password);
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

            // Lấy sở thích từ bảng student_interests
            $studentInterests = DB::table('student_interests')
                ->join('interests', 'student_interests.InterestID', '=', 'interests.InterestID')
                ->where('student_interests.MSSV', $sinhVien->MSSV)
                ->select('interests.InterestID', 'interests.InterestName', 'interests.Icon', 'student_interests.InterestLevel')
                ->get();

            // Format sở thích thành danh sách tên phục vụ mobile
            $soThichList = $studentInterests->pluck('InterestName')->toArray();
            $soThichString = implode(', ', $soThichList);

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
                    'SoThich' => $soThichString,
                    'StudentInterests' => $studentInterests,
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
            'SoThich' => 'nullable|string',
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

            // Cập nhật thông tin sinh viên cơ bản
            $sinhVien->update([
                'Email' => $request->Email,
                'SDT' => $request->SDT,
                'ThoiGianTotNghiepDuKien' => $request->ThoiGianTotNghiepDuKien,
            ]);

            // Cập nhật sở thích từ danh sách tên sở thích
            if ($request->has('SoThich') && !empty($request->SoThich)) {
                // Parse danh sách sở thích từ chuỗi
                $interestNames = array_filter(array_map('trim', explode(',', $request->SoThich)));
                
                // Lấy ID của các sở thích từ tên
                $interests = DB::table('interests')
                    ->whereIn('InterestName', $interestNames)
                    ->get(['InterestID', 'InterestName']);
                
                // Xóa sở thích cũ
                DB::table('student_interests')
                    ->where('MSSV', $sinhVien->MSSV)
                    ->delete();
                
                // Thêm sở thích mới
                foreach ($interests as $interest) {
                    DB::table('student_interests')->insert([
                        'MSSV' => $sinhVien->MSSV,
                        'InterestID' => $interest->InterestID,
                        'InterestLevel' => 3, // Mức độ mặc định
                        'UpdatedAt' => now(),
                    ]);
                }
            } else {
                // Xóa tất cả sở thích nếu không gửi dữ liệu
                DB::table('student_interests')
                    ->where('MSSV', $sinhVien->MSSV)
                    ->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công!',
                'data' => [
                    'Email' => $sinhVien->Email,
                    'SDT' => $sinhVien->SDT,
                    'ThoiGianTotNghiepDuKien' => $sinhVien->ThoiGianTotNghiepDuKien,
                    'SoThich' => $request->SoThich,
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

    public function getInterests(Request $request)
    {
        try {
            // Lấy toàn bộ danh sách sở thích
            $interests = DB::table('interests')
                ->select('InterestID', 'InterestName', 'Icon', 'Description')
                ->orderBy('InterestID')
                ->get();

            // Nếu user đã login, lấy thêm thông tin sở thích đã chọn
            $selectedInterestIds = [];
            if ($request->user()) {
                $user = $request->user();
                $sinhVien = $user->sinhVien;
                
                if ($sinhVien) {
                    $selectedInterestIds = DB::table('student_interests')
                        ->where('MSSV', $sinhVien->MSSV)
                        ->pluck('InterestID')
                        ->toArray();
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'interests' => $interests,
                    'selected' => $selectedInterestIds,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách sở thích: ' . $e->getMessage()
            ], 500);
        }
    }
}