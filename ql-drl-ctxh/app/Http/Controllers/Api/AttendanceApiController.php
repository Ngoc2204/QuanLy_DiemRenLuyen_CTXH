<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// KHÔNG CẦN DiemDanhDRL hay DiemDanhCTXH
use App\Models\DangKyHoatDongDRL;
use App\Models\DangKyHoatDongCTXH;
use App\Models\HoatDongDRL;
use App\Models\HoatDongCTXH;
use App\Models\DiemRenLuyen;
use App\Models\DiemCtxh;
use Carbon\Carbon;

class AttendanceApiController extends Controller
{
    public function scanQR(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_data' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu QR không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $taikhoan = $request->user(); // This is TaiKhoan model
            $sinhVien = $taikhoan->sinhvien; // Relationship to SinhVien
            
            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ sinh viên mới có thể điểm danh'
                ], 403);
            }

            $qrData = $request->qr_data;
            
            // Trim whitespace and decode if needed
            $qrData = trim($qrData);
            
            // Extract token from URL if it's a full URL
            // Format: http://127.0.0.1:8000/sinhvien/scan/TOKEN or http://192.168.x.x:8000/sinhvien/scan/TOKEN
            if (preg_match('/\/sinhvien\/scan\/([a-zA-Z0-9]+)$/', $qrData, $matches)) {
                $qrData = $matches[1];
            }
            
            // Log the QR data for debugging
            Log::info('QR Scanned', [
                'mssv' => $sinhVien->MSSV,
                'qr_data' => $qrData,
                'qr_data_raw' => $request->qr_data,
                'qr_length' => strlen($qrData),
                'qr_bytes' => bin2hex(substr($qrData, 0, 20)), // First 20 bytes in hex
            ]);
            
            // Try parsing as colon-separated format first
            $parts = explode(':', $qrData);
            
            if (count($parts) >= 4) {
                // Format: DRL:activity_id:checkin:timestamp or CTXH:activity_id:checkout:timestamp
                $activityType = $parts[0]; // 'DRL' or 'CTXH'
                $activityId = $parts[1];
                $action = $parts[2]; // 'checkin' or 'checkout'
                $timestamp = $parts[3];

                $qrTime = Carbon::createFromTimestamp($timestamp);
                if ($qrTime->diffInMinutes(Carbon::now()) > 30) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã QR đã hết hạn'
                    ], 400);
                }

                if ($activityType === 'DRL') {
                    return $this->handleDRLAttendance($sinhVien, $activityId, $action);
                } elseif ($activityType === 'CTXH') {
                    return $this->handleCTXHAttendance($sinhVien, $activityId, $action);
                }
            } else {
                // Try as token format: just a random string (CheckInToken or CheckOutToken)
                return $this->handleTokenAttendance($sinhVien, $qrData);
            }

            return response()->json([
                'success' => false,
                'message' => 'Loại hoạt động không hợp lệ'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi điểm danh: ' . $e->getMessage()
            ], 500);
        }
    }

    private function handleDRLAttendance($sinhVien, $activityId, $action)
    {
        // 1. Tìm bản ghi ĐĂNG KÝ
        $dangKy = DangKyHoatDongDRL::where('MSSV', $sinhVien->MSSV)
            ->where('MaHoatDong', $activityId)
            ->whereIn('TrangThaiDangKy', ['DaDuyet', 'Đã duyệt'])
            ->first();

        if (!$dangKy) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa đăng ký hoạt động này hoặc chưa được duyệt'
            ], 400);
        }

        $now = Carbon::now();

        if ($action === 'checkin') {
            // 2. Kiểm tra cột CheckInAt của chính bản ghi $dangKy
            if ($dangKy->CheckInAt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã check-in rồi'
                ], 400);
            }

            // 3. Cập nhật chính bản ghi $dangKy
            $dangKy->update([
                'CheckInAt' => $now,
                'TrangThaiThamGia' => 'Đang tham gia' // Cập nhật trạng thái tham gia
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-in thành công',
                'data' => [
                    'action' => 'checkin',
                    'time' => $now->format('H:i:s d/m/Y'),
                    'activity' => $dangKy->hoatdong->TenHoatDong ?? 'Không rõ'
                ]
            ]);

        } elseif ($action === 'checkout') {
            if (!$dangKy->CheckInAt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chưa check-in'
                ], 400);
            }

            if ($dangKy->CheckOutAt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã check-out rồi'
                ], 400);
            }

            // 3. Cập nhật chính bản ghi $dangKy
            $dangKy->update([
                'CheckOutAt' => $now,
                'TrangThaiThamGia' => 'Đã tham gia' // Cập nhật trạng thái tham gia
            ]);

            // 4. Cập nhật bảng điểm DRL
            $hoatDong = $dangKy->hoatdong;
            $quyDinh = $hoatDong->quydinh;
            
            if ($quyDinh) {
                $diemRenLuyen = DiemRenLuyen::updateOrCreate(
                    ['MSSV' => $sinhVien->MSSV],
                    ['TongDiem' => DB::raw('TongDiem + ' . $quyDinh->DiemNhan)]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Check-out thành công',
                'data' => [
                    'action' => 'checkout',
                    'time' => $now->format('H:i:s d/m/Y'),
                    'activity' => $dangKy->hoatdong->TenHoatDong ?? 'Không rõ'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Hành động không hợp lệ'
        ], 400);
    }

    private function handleCTXHAttendance($sinhVien, $activityId, $action)
    {
        // 1. Tìm bản ghi ĐĂNG KÝ
        $dangKy = DangKyHoatDongCTXH::where('MSSV', $sinhVien->MSSV)
            ->where('MaHoatDong', $activityId)
            ->whereIn('TrangThaiDangKy', ['DaDuyet', 'Đã duyệt'])
            ->first();

        if (!$dangKy) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa đăng ký hoạt động này hoặc chưa được duyệt'
            ], 400);
        }

        $now = Carbon::now();

        if ($action === 'checkin') {
            // 2. Kiểm tra cột CheckInAt của chính bản ghi $dangKy
            if ($dangKy->CheckInAt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã check-in rồi'
                ], 400);
            }

            // 3. Cập nhật chính bản ghi $dangKy
            $dangKy->update([
                'CheckInAt' => $now,
                'TrangThaiThamGia' => 'Đang tham gia'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-in thành công',
                'data' => [
                    'action' => 'checkin',
                    'time' => $now->format('H:i:s d/m/Y'),
                    'activity' => $dangKy->hoatdong->TenHoatDong ?? 'Không rõ'
                ]
            ]);

        } elseif ($action === 'checkout') {
            if (!$dangKy->CheckInAt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chưa check-in'
                ], 400);
            }

            if ($dangKy->CheckOutAt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã check-out rồi'
                ], 400);
            }

            // 3. Cập nhật chính bản ghi $dangKy
            $dangKy->update([
                'CheckOutAt' => $now,
                'TrangThaiThamGia' => 'Đã tham gia'
            ]);

            // 4. Cập nhật bảng điểm CTXH
            $hoatDong = $dangKy->hoatdong;
            $quyDinh = $hoatDong->quydinh;
            
            if ($quyDinh) {
                $diemCtxh = DiemCtxh::updateOrCreate(
                    ['MSSV' => $sinhVien->MSSV],
                    ['TongDiem' => DB::raw('TongDiem + ' . $quyDinh->DiemNhan)]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Check-out thành công',
                'data' => [
                    'action' => 'checkout',
                    'time' => $now->format('H:i:s d/m/Y'),
                    'activity' => $dangKy->hoatdong->TenHoatDong ?? 'Không rõ'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Hành động không hợp lệ'
        ], 400);
    }

    private function handleTokenAttendance($sinhVien, $token)
    {
        // Trim token
        $token = trim($token);
        
        // Log token search with available tokens
        $allTokens = [];
        
        // Get all DRL tokens for comparison
        $drlActivities = HoatDongDRL::whereNotNull('CheckInToken')
            ->orWhereNotNull('CheckOutToken')
            ->select('MaHoatDong', 'CheckInToken', 'CheckOutToken')
            ->limit(10)
            ->get();
        
        foreach ($drlActivities as $activity) {
            $allTokens[] = [
                'activity' => $activity->MaHoatDong,
                'checkin_token' => $activity->CheckInToken,
                'token_match' => $activity->CheckInToken === $token ? 'MATCH' : 'no match',
            ];
        }
        
        Log::info('Searching for token', [
            'token_search' => $token,
            'token_length' => strlen($token),
            'token_hex' => bin2hex($token),
            'mssv' => $sinhVien->MSSV,
            'available_tokens' => $allTokens,
        ]);
        
        // Tìm hoạt động DRL hoặc CTXH có token này
        $hoatDongDRL = HoatDongDRL::where('CheckInToken', $token)
            ->orWhere('CheckOutToken', $token)
            ->first();

        if ($hoatDongDRL) {
            Log::info('Token found in DRL activity', ['activity_id' => $hoatDongDRL->MaHoatDong]);
            $isCheckin = $hoatDongDRL->CheckInToken === $token;
            $action = $isCheckin ? 'checkin' : 'checkout';
            return $this->handleDRLAttendance($sinhVien, $hoatDongDRL->MaHoatDong, $action);
        }

        $hoatDongCTXH = HoatDongCTXH::where('CheckInToken', $token)
            ->orWhere('CheckOutToken', $token)
            ->first();

        if ($hoatDongCTXH) {
            Log::info('Token found in CTXH activity', ['activity_id' => $hoatDongCTXH->MaHoatDong]);
            $isCheckin = $hoatDongCTXH->CheckInToken === $token;
            $action = $isCheckin ? 'checkin' : 'checkout';
            return $this->handleCTXHAttendance($sinhVien, $hoatDongCTXH->MaHoatDong, $action);
        }

        Log::warning('Token not found in any activity', ['token' => $token]);

        return response()->json([
            'success' => false,
            'message' => 'Mã QR không hợp lệ hoặc không tìm thấy'
        ], 400);
    }

    public function getAttendanceHistory(Request $request)
    {
        // Hàm này sẽ đọc từ bảng đăng ký, nên logic của nó vẫn đúng
        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;
            
            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ sinh viên mới có thể xem lịch sử điểm danh'
                ], 403);
            }

            // Lấy lịch sử điểm danh DRL (những bản ghi đã checkin)
            $drlHistory = DangKyHoatDongDRL::with(['hoatdong'])
                ->where('MSSV', $sinhVien->MSSV)
                ->whereNotNull('CheckInAt') // Chỉ lấy các bản ghi đã check-in
                ->get()
                ->map(function($record) {
                    return [
                        'id' => $record->MaDangKy, // Dùng MaDangKy làm ID
                        'ten_hoat_dong' => $record->hoatdong->TenHoatDong ?? 'Không rõ',
                        'ngay_to_chuc' => $record->hoatdong->NgayToChuc ?? null,
                        'check_in' => $record->CheckInAt,
                        'check_out' => $record->CheckOutAt,
                        'trang_thai' => $record->TrangThaiThamGia, // Dùng cột TrangThaiThamGia
                        'type' => 'DRL'
                    ];
                });

            // Lấy lịch sử điểm danh CTXH (những bản ghi đã checkin)
            $ctxhHistory = DangKyHoatDongCTXH::with(['hoatdong'])
                ->where('MSSV', $sinhVien->MSSV)
                ->whereNotNull('CheckInAt') // Chỉ lấy các bản ghi đã check-in
                ->get()
                ->map(function($record) {
                    return [
                        'id' => $record->MaDangKy, // Dùng MaDangKy làm ID
                        'ten_hoat_dong' => $record->hoatdong->TenHoatDong ?? 'Không rõ',
                        'ngay_to_chuc' => $record->hoatdong->NgayToChuc ?? null,
                        'check_in' => $record->CheckInAt,
                        'check_out' => $record->CheckOutAt,
                        'trang_thai' => $record->TrangThaiThamGia, // Dùng cột TrangThaiThamGia
                        'type' => 'CTXH'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'drl_history' => $drlHistory,
                    'ctxh_history' => $ctxhHistory
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy lịch sử điểm danh: ' . $e->getMessage()
            ], 500);
        }
    }
}