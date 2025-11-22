<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThanhToan;
use App\Models\DangKyHoatDongCTXH;
use Carbon\Carbon;

class PaymentApiController extends Controller
{
    /**
     * Get user MSSV from authenticated user
     */
    private function getUserMSSV($user)
    {
        if (!$user) {
            return null;
        }
        
        // TaiKhoan.TenDangNhap is the MSSV!
        if (isset($user->TenDangNhap) && $user->TenDangNhap) {
            return $user->TenDangNhap;
        }
        
        // Fallback to sinhvien relationship
        if (method_exists($user, 'sinhvien')) {
            try {
                $sinhVien = $user->sinhvien;
                if ($sinhVien && isset($sinhVien->MSSV)) {
                    return $sinhVien->MSSV;
                }
            } catch (\Exception $e) {
                // Relationship might not be loaded
            }
        }
        
        return null;
    }

    /**
     * Get pending payments for current user
     */
    public function getPendingPayments()
    {
        try {
            $user = auth()->guard('sanctum')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không được xác thực'
                ], 401);
            }

            // Get pending payments for this student
            $userMSSV = $this->getUserMSSV($user);
            $payments = ThanhToan::where('MSSV', $userMSSV)
                ->where('TrangThai', 'Chờ thanh toán')
                ->with(['dangKyHoatDong.hoatdong'])
                ->get()
                ->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'mssv' => $payment->MSSV,
                        'amount' => $payment->TongTien,
                        'status' => $payment->TrangThai,
                        'method' => $payment->PhuongThuc,
                        'registration' => [
                            'ma_dang_ky' => $payment->dangKyHoatDong?->MaDangKy,
                            'hoat_dong' => [
                                'ten' => $payment->dangKyHoatDong?->hoatdong?->TenHoatDong,
                                'ngay_to_chuc' => $payment->dangKyHoatDong?->hoatdong?->ThoiGianBatDau,
                                'dia_diem' => $payment->dangKyHoatDong?->hoatdong?->DiaDiem,
                            ]
                        ],
                        'created_at' => $payment->created_at,
                        'updated_at' => $payment->updated_at
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $payments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi lấy thông tin thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment details by ID
     */
    public function getPaymentDetails($paymentId)
    {
        try {
            $user = auth()->guard('sanctum')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không được xác thực'
                ], 401);
            }

            $payment = ThanhToan::find($paymentId);
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy hóa đơn'
                ], 404);
            }

            // Check ownership
            if ($payment->MSSV !== $this->getUserMSSV($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập hóa đơn này'
                ], 403);
            }

            $payment->load(['dangKyHoatDong.hoatdong']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $payment->id,
                    'mssv' => $payment->MSSV,
                    'amount' => $payment->TongTien,
                    'status' => $payment->TrangThai,
                    'method' => $payment->PhuongThuc,
                    'registration' => [
                        'ma_dang_ky' => $payment->dangKyHoatDong?->MaDangKy,
                        'hoat_dong' => [
                            'ten' => $payment->dangKyHoatDong?->hoatdong?->TenHoatDong,
                            'ngay_to_chuc' => $payment->dangKyHoatDong?->hoatdong?->ThoiGianBatDau,
                            'dia_diem' => $payment->dangKyHoatDong?->hoatdong?->DiaDiem,
                            'mo_ta' => $payment->dangKyHoatDong?->hoatdong?->MoTa,
                        ]
                    ],
                    'created_at' => $payment->created_at,
                    'updated_at' => $payment->updated_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi lấy chi tiết thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm payment method (Tiền mặt or Online)
     */
    public function confirmPaymentMethod(Request $request, $paymentId)
    {
        try {
            $user = auth()->guard('sanctum')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không được xác thực'
                ], 401);
            }

            $validated = $request->validate([
                'method' => 'required|in:Tiền mặt,Online'
            ]);

            $payment = ThanhToan::find($paymentId);
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy hóa đơn'
                ], 404);
            }

            // Check ownership
            if ($payment->MSSV !== $this->getUserMSSV($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập hóa đơn này'
                ], 403);
            }

            // Check if payment is still pending
            if ($payment->TrangThai !== 'Chờ thanh toán') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hóa đơn đã được xử lý hoặc không hợp lệ'
                ], 400);
            }

            // Update payment method
            $payment->PhuongThuc = $validated['method'];
            $payment->save();

            // For cash payments, mark as completed
            if ($validated['method'] === 'Tiền mặt') {
                $payment->TrangThai = 'Đã thanh toán';
                $payment->save();

                // Update registration status to approved
                if ($payment->dangKyHoatDong) {
                    $payment->dangKyHoatDong->TrangThaiDangKy = 'Đã duyệt';
                    $payment->dangKyHoatDong->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Thanh toán tiền mặt đã được xác nhận. Vui lòng thanh toán tại cơ quan có thẩm quyền.'
                ]);
            } else {
                // For online payments, generate VietQR info
                $payment->TrangThai = 'Chờ xác nhận';
                $payment->save();

                // Get payment details for VietQR generation
                $donDangKy = $payment->dangKyHoatDong;
                $maDangKy  = $donDangKy->MaDangKy;
                $hoatDong  = $donDangKy->hoatdong;
                
                $soTien = $hoatDong?->diaDiem?->GiaTien ?? $payment->TongTien;
                $soTien = (int) $soTien;
                
                // Bank info
                $bankBin = '970436';
                $bankAccountNo = '1031467947';
                $bankAccountName = 'NGUYEN TAT NGOC';
                
                // Generate transfer content (noi dung chuyen khoan)
                $ma = "DK{$maDangKy}";
                $loaiHoatDong = $hoatDong->LoaiHoatDong ?? '';
                $diaDiem = $hoatDong->diaDiem->TenDiaDiem ?? '';
                $ngay = '';
                if (!empty($hoatDong?->ThoiGianBatDau)) {
                    $ngay = \Carbon\Carbon::parse($hoatDong->ThoiGianBatDau)->format('d-m');
                }
                
                $noiDungChuyenKhoan = trim("{$ma} {$loaiHoatDong} {$diaDiem} {$ngay}");
                $noiDungChuyenKhoan = mb_substr($noiDungChuyenKhoan, 0, 70, 'UTF-8');
                
                // Generate QR URL
                $qrUrl = sprintf(
                    'https://img.vietqr.io/image/%s-%s-compact2.png?amount=%d&addInfo=%s&accountName=%s',
                    $bankBin,
                    $bankAccountNo,
                    $soTien,
                    urlencode($noiDungChuyenKhoan),
                    urlencode($bankAccountName)
                );
                
                return response()->json([
                    'success' => true,
                    'message' => 'Phương thức thanh toán online đã được chọn. Vui lòng quét mã QR để thanh toán.',
                    'data' => [
                        'payment_id' => $payment->id,
                        'amount' => $soTien,
                        'method' => $payment->PhuongThuc,
                        'bank_info' => [
                            'bin' => $bankBin,
                            'account_no' => $bankAccountNo,
                            'account_name' => $bankAccountName,
                        ],
                        'transfer_content' => $noiDungChuyenKhoan,
                        'qr_url' => $qrUrl
                    ]
                ]);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác nhận thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment status by registration ID (MaDangKy)
     */
    public function getPaymentByRegistration($registrationId)
    {
        try {
            $user = auth()->guard('sanctum')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không được xác thực'
                ], 401);
            }

            // Find registration by MaDangKy
            $registration = DangKyHoatDongCTXH::where('MaDangKy', $registrationId)->first();
            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đăng ký'
                ], 404);
            }

            // Get user MSSV - use helper method
            $userMSSV = $this->getUserMSSV($user);

            // Check ownership
            if (!$userMSSV || $registration->MSSV !== $userMSSV) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập đăng ký này'
                ], 403);
            }

            // Find payment by thanh_toan_id from registration
            $payment = null;
            if ($registration->thanh_toan_id) {
                $payment = ThanhToan::find($registration->thanh_toan_id);
            }

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thanh toán'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $payment->id,
                    'amount' => $payment->TongTien,
                    'status' => $payment->TrangThai,
                    'method' => $payment->PhuongThuc,
                    'registration_status' => $registration->TrangThaiDangKy
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi lấy thông tin thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }
}
