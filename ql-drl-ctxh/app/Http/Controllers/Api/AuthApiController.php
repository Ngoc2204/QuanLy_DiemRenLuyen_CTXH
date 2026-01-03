<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\TaiKhoan;
use Laravel\Sanctum\HasApiTokens;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Tìm tài khoản bằng TenDangNhap
        $taikhoan = TaiKhoan::where('TenDangNhap', $request->username)->first();

        if (!$taikhoan) {
            return response()->json([
                'success' => false,
                'message' => 'Thông tin đăng nhập không chính xác'
            ], 401);
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($request->password, $taikhoan->MatKhau)) {
            return response()->json([
                'success' => false,
                'message' => 'Thông tin đăng nhập không chính xác'
            ], 401);
        }

        // Đăng nhập thành công
        Auth::login($taikhoan);
        
        // Tạo token cho mobile app
        $token = $taikhoan->createToken('mobile-app-token')->plainTextToken;
        
        // Lấy thông tin bổ sung dựa trên vai trò
        $additionalInfo = [];
        $hoTen = $taikhoan->HoTen ?? '';
        
        if ($taikhoan->VaiTro === 'SinhVien') {
            $sinhVien = $taikhoan->sinhVien;
            if ($sinhVien) {
                $hoTen = $sinhVien->HoTen ?? $taikhoan->HoTen ?? '';
                $additionalInfo = [
                    'MaSV' => $sinhVien->MSSV,
                    'NgaySinh' => $sinhVien->NgaySinh,
                    'DiaChi' => $sinhVien->DiaChi,
                    'SoDienThoai' => $sinhVien->SDT,
                    'TenLop' => $sinhVien->lop->TenLop ?? null,
                    'MaLop' => $sinhVien->lop->MaLop ?? null,
                    'TenKhoa' => $sinhVien->lop->khoa->TenKhoa ?? null,
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'user' => array_merge([
                    'id' => $taikhoan->TenDangNhap,
                    'username' => $taikhoan->TenDangNhap,
                    'HoTen' => $hoTen,
                    'VaiTro' => $taikhoan->VaiTro,
                ], $additionalInfo),
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        try {
            // Xóa token hiện tại
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Đăng xuất thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng xuất'
            ], 500);
        }
    }

    public function user(Request $request)
    {
        try {
            $user = $request->user();
            
            // Lấy thông tin bổ sung dựa trên vai trò
            $additionalInfo = [];
            $hoTen = $user->HoTen ?? '';
            
            if ($user->VaiTro === 'SinhVien') {
                $sinhVien = $user->sinhVien;
                if ($sinhVien) {
                    $hoTen = $sinhVien->HoTen ?? $user->HoTen ?? '';
                    $additionalInfo = [
                        'MaSV' => $sinhVien->MSSV,
                        'NgaySinh' => $sinhVien->NgaySinh,
                        'DiaChi' => $sinhVien->DiaChi,
                        'SoDienThoai' => $sinhVien->SDT,
                        'TenLop' => $sinhVien->lop->TenLop ?? null,
                        'MaLop' => $sinhVien->lop->MaLop ?? null,
                        'TenKhoa' => $sinhVien->lop->khoa->TenKhoa ?? null,
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => array_merge([
                        'id' => $user->TenDangNhap,
                        'username' => $user->TenDangNhap,
                        'HoTen' => $hoTen,
                        'VaiTro' => $user->VaiTro,
                        'email' => $user->Email ?? '',
                    ], $additionalInfo)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thông tin người dùng'
            ], 500);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $user = $request->user();
            
            // Xóa token cũ
            $request->user()->currentAccessToken()->delete();
            
            // Tạo token mới
            $newToken = $user->createToken('mobile-app-token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'message' => 'Token đã được làm mới',
                'data' => [
                    'token' => $newToken,
                    'token_type' => 'Bearer'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể làm mới token'
            ], 500);
        }
    }
}