<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HoatDongDRL;
use App\Models\HoatDongCTXH;
use App\Models\DangKyHoatDongDRL;
use App\Models\DangKyHoatDongCTXH;
use App\Models\DiemDanhDRL;
use App\Models\DiemDanhCTXH;
use App\Models\SinhVien;
use App\Models\ThanhToan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivityApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            // Lấy hoạt động DRL và CTXH có sẵn (chưa hết hạn đăng ký)
            $drlActivities = HoatDongDRL::with(['quydinh'])
                ->where(function($query) {
                    $query->where('ThoiHanHuy', '>=', now())
                          ->orWhereNull('ThoiHanHuy');
                })
                ->where('ThoiGianBatDau', '>=', now())
                ->orderBy('ThoiGianBatDau', 'asc')
                ->get()
                ->map(function($activity) {
                    return [
                        'id' => $activity->MaHoatDong,
                        'ten' => $activity->TenHoatDong,
                        'mo_ta' => $activity->MoTa,
                        'ngay_to_chuc' => $activity->ThoiGianBatDau,
                        'thoi_gian_ket_thuc' => $activity->ThoiGianKetThuc,
                        'thoi_han_huy' => $activity->ThoiHanHuy,
                        'dia_diem' => $activity->DiaDiem,
                        'so_luong_toi_da' => $activity->SoLuong,
                        'diem_rl' => $activity->quydinh->DiemNhan ?? 0,
                        'type' => 'DRL'
                    ];
                });

            $ctxhActivities = HoatDongCTXH::with(['quydinh'])
                ->where(function($query) {
                    $query->where('ThoiHanHuy', '>=', now())
                          ->orWhereNull('ThoiHanHuy');
                })
                ->where('ThoiGianBatDau', '>=', now())
                ->orderBy('ThoiGianBatDau', 'asc')
                ->get()
                ->map(function($activity) {
                    return [
                        'id' => $activity->MaHoatDong,
                        'ten' => $activity->TenHoatDong,
                        'mo_ta' => $activity->MoTa,
                        'ngay_to_chuc' => $activity->ThoiGianBatDau,
                        'thoi_gian_ket_thuc' => $activity->ThoiGianKetThuc,
                        'thoi_han_huy' => $activity->ThoiHanHuy,
                        'dia_diem' => $activity->DiaDiem,
                        'so_luong_toi_da' => $activity->SoLuong,
                        'diem_ctxh' => $activity->quydinh->DiemNhan ?? 0,
                        'type' => 'CTXH'
                    ];
                });

            // Gộp cả 2 loại hoạt động lại
            $allActivities = $drlActivities->concat($ctxhActivities);
            
            return response()->json([
                'success' => true,
                'data' => $allActivities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách hoạt động: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAvailableDRLActivities(Request $request)
    {
        try {
            $activities = HoatDongDRL::with(['nhanVien', 'quydinh'])
                ->where('TrangThaiDangKy', 'Mo')
                ->where('NgayToChuc', '>=', now())
                ->orderBy('NgayToChuc', 'asc')
                ->get()
                ->map(function($activity) {
                    return [
                        'id' => $activity->MaHoatDong,
                        'ten' => $activity->TenHoatDong,
                        'mo_ta' => $activity->MoTa,
                        'ngay_to_chuc' => $activity->NgayToChuc,
                        'dia_diem' => $activity->DiaDiem,
                        'so_luong_toi_da' => $activity->SoLuongToiDa,
                        'diem_rl' => $activity->quydinh->DiemNhan ?? 0,
                        'nguoi_to_chuc' => $activity->nhanVien->user->HoTen ?? 'N/A'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy hoạt động DRL'
            ], 500);
        }
    }

    public function getAvailableCTXHActivities(Request $request)
    {
        try {
            $activities = HoatDongCTXH::with(['nhanVien', 'quydinh'])
                ->where('TrangThaiDangKy', 'Mo')
                ->where('NgayToChuc', '>=', now())
                ->orderBy('NgayToChuc', 'asc')
                ->get()
                ->map(function($activity) {
                    return [
                        'id' => $activity->MaHoatDong,
                        'ten' => $activity->TenHoatDong,
                        'mo_ta' => $activity->MoTa,
                        'ngay_to_chuc' => $activity->NgayToChuc,
                        'dia_diem' => $activity->DiaDiem,
                        'so_luong_toi_da' => $activity->SoLuongToiDa,
                        'diem_ctxh' => $activity->quydinh->DiemNhan ?? 0,
                        'nguoi_to_chuc' => $activity->nhanVien->user->HoTen ?? 'N/A'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy hoạt động CTXH'
            ], 500);
        }
    }

    public function registerDRL(Request $request, $id)
    {
        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;
            
            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ sinh viên mới có thể đăng ký hoạt động'
                ], 403);
            }

            // Kiểm tra hoạt động có tồn tại và còn trong thời hạn đăng ký không
            $hoatDong = HoatDongDRL::find($id);
            if (!$hoatDong) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hoạt động không tồn tại'
                ], 404);
            }

            // Kiểm tra deadline đăng ký (ThoiHanHuy)
            if ($hoatDong->ThoiHanHuy && now() > $hoatDong->ThoiHanHuy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hoạt động đã đóng đăng ký'
                ], 400);
            }

            // Kiểm tra hoạt động đã bắt đầu chưa
            if (now() > $hoatDong->ThoiGianBatDau) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hoạt động đã bắt đầu, không thể đăng ký'
                ], 400);
            }

            // Kiểm tra đã đăng ký chưa
            $existingRegistration = DangKyHoatDongDRL::where('MSSV', $sinhVien->MSSV)
                ->where('MaHoatDong', $id)
                ->first();

            if ($existingRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã đăng ký hoạt động này rồi'
                ], 400);
            }

            // Tạo đăng ký mới
            $dangKy = DangKyHoatDongDRL::create([
                'MSSV' => $sinhVien->MSSV,
                'MaHoatDong' => $id,
                'NgayDangKy' => now(),
                'TrangThaiDangKy' => 'Chờ duyệt'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký hoạt động thành công',
                'data' => [
                    'ma_dang_ky' => $dangKy->MaDangKy,
                    'trang_thai' => $dangKy->TrangThaiDangKy
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function registerCTXH(Request $request, $id)
    {
        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;
            
            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ sinh viên mới có thể đăng ký hoạt động'
                ], 403);
            }

            // Kiểm tra hoạt động có tồn tại và còn trong thời hạn đăng ký không
            $hoatDong = HoatDongCTXH::with('diaDiem')->find($id);
            if (!$hoatDong) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hoạt động không tồn tại'
                ], 404);
            }

            // Kiểm tra deadline đăng ký (ThoiHanHuy)
            if ($hoatDong->ThoiHanHuy && now() > $hoatDong->ThoiHanHuy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hoạt động đã đóng đăng ký'
                ], 400);
            }

            // Kiểm tra hoạt động đã bắt đầu chưa
            if (now() > $hoatDong->ThoiGianBatDau) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hoạt động đã bắt đầu, không thể đăng ký'
                ], 400);
            }

            // Kiểm tra đã đăng ký chưa
            $existingRegistration = DangKyHoatDongCTXH::where('MSSV', $sinhVien->MSSV)
                ->where('MaHoatDong', $id)
                ->first();

            if ($existingRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã đăng ký hoạt động này rồi'
                ], 400);
            }

            // Get fee if any
            $giaTien = $hoatDong->diaDiem?->GiaTien ?? 0;
            
            // Determine registration status based on fee
            $trangThaiDangKy = ($giaTien > 0) ? 'Chờ thanh toán' : 'Chờ duyệt';

            // Tạo đăng ký mới
            $dangKy = DangKyHoatDongCTXH::create([
                'MSSV' => $sinhVien->MSSV,
                'MaHoatDong' => $id,
                'NgayDangKy' => now(),
                'TrangThaiDangKy' => $trangThaiDangKy
            ]);

            // Create payment record if activity has fee
            $thanhToan = null;
            if ($giaTien > 0) {
                $thanhToan = ThanhToan::create([
                    'MSSV' => $sinhVien->MSSV,
                    'TongTien' => $giaTien,
                    'TrangThai' => 'Chờ thanh toán',
                ]);

                $dangKy->thanh_toan_id = $thanhToan->id;
                $dangKy->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký hoạt động thành công',
                'data' => [
                    'ma_dang_ky' => $dangKy->MaDangKy,
                    'trang_thai' => $dangKy->TrangThaiDangKy,
                    'can_pay' => $giaTien > 0,
                    'payment_id' => $thanhToan?->id,
                    'amount' => $giaTien
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMyRegistrations(Request $request)
    {
        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;
            $now = now();
            
            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ sinh viên mới có thể xem đăng ký'
                ], 403);
            }

            // Lấy đăng ký DRL
            $drlRegistrations = DangKyHoatDongDRL::with(['hoatdong', 'hoatdong.quydinh'])
                ->where('MSSV', $sinhVien->MSSV)
                ->get()
                ->map(function($reg) use ($now) {
                    $activity = $reg->hoatdong;
                    $canCancel = !$activity->ThoiHanHuy || $now->lt($activity->ThoiHanHuy);
                    
                    return [
                        'ma_dang_ky' => $reg->MaDangKy,
                        'hoatdong' => [
                            'ten' => $activity->TenHoatDong,
                            'ngay_to_chuc' => $activity->ThoiGianBatDau,
                            'dia_diem' => $activity->DiaDiem,
                            'thoi_han_huy' => $activity->ThoiHanHuy,
                            'diem_rl' => $activity->quydinh->DiemNhan ?? 0,
                        ],
                        'trang_thai_dang_ky' => $reg->TrangThaiDangKy,
                        'can_cancel' => $canCancel && in_array($reg->TrangThaiDangKy, ['Chờ duyệt', 'Đã duyệt']),
                        'type' => 'DRL'
                    ];
                });

            // Lấy đăng ký CTXH
            $ctxhRegistrations = DangKyHoatDongCTXH::with(['hoatdong', 'hoatdong.quydinh', 'hoatdong.diaDiem'])
                ->where('MSSV', $sinhVien->MSSV)
                ->get()
                ->map(function($reg) use ($now) {
                    $activity = $reg->hoatdong;
                    $canCancel = !$activity->ThoiHanHuy || $now->lt($activity->ThoiHanHuy);
                    
                    return [
                        'ma_dang_ky' => $reg->MaDangKy,
                        'hoatdong' => [
                            'ten' => $activity->TenHoatDong,
                            'ngay_to_chuc' => $activity->ThoiGianBatDau,
                            'dia_diem' => $activity->DiaDiem,
                            'thoi_han_huy' => $activity->ThoiHanHuy,
                            'diem_ctxh' => $activity->quydinh->DiemNhan ?? 0,
                            'dia_diem_detail' => [
                                'ten' => $activity->diaDiem?->TenDiaDiem,
                                'gia_tien' => $activity->diaDiem?->GiaTien ?? 0,
                            ]
                        ],
                        'trang_thai_dang_ky' => $reg->TrangThaiDangKy,
                        'can_cancel' => $canCancel && in_array($reg->TrangThaiDangKy, ['Chờ duyệt', 'Chờ thanh toán', 'Đã duyệt']),
                        'type' => 'CTXH'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'drl_registrations' => $drlRegistrations,
                    'ctxh_registrations' => $ctxhRegistrations
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách đăng ký'
            ], 500);
        }
    }

    public function getDashboardData(Request $request)
    {
        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;
            
            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ sinh viên mới có thể xem dashboard'
                ], 403);
            }

            // Lấy điểm DRL từ bảng diemrenluyen - theo actual database schema
            $diemDRL = DB::table('diemrenluyen')
                ->where('MSSV', $sinhVien->MSSV)
                ->where('MaHocKy', 'HK1_2526') // Học kỳ hiện tại
                ->first();
            
            $totalDRLScore = $diemDRL ? $diemDRL->TongDiem : 0;

            // Lấy điểm CTXH từ bảng diemctxh - theo actual database schema
            $diemCTXH = DB::table('diemctxh')
                ->where('MSSV', $sinhVien->MSSV)
                ->first();
            
            $totalCTXHScore = $diemCTXH ? $diemCTXH->TongDiem : 0;

            // Đếm số hoạt động đã hoàn thành (đã checkout) - theo actual database schema
            $completedDRLCount = DB::table('dangkyhoatdongdrl')
                ->where('MSSV', $sinhVien->MSSV)
                ->whereIn('TrangThaiDangKy', ['Đã duyệt'])
                ->whereNotNull('CheckOutAt')
                ->count();

            $completedCTXHCount = DB::table('dangkyhoatdongctxh')
                ->where('MSSV', $sinhVien->MSSV)
                ->whereIn('TrangThaiDangKy', ['Đã duyệt'])
                ->whereNotNull('CheckOutAt')
                ->count();

            $completedActivities = $completedDRLCount + $completedCTXHCount;

            // Đếm số hoạt động chờ duyệt - theo actual database schema
            $pendingDRLActivities = DB::table('dangkyhoatdongdrl')
                ->where('MSSV', $sinhVien->MSSV)
                ->where('TrangThaiDangKy', 'Chờ duyệt')
                ->count();
                
            $pendingCTXHActivities = DB::table('dangkyhoatdongctxh')
                ->where('MSSV', $sinhVien->MSSV)
                ->where('TrangThaiDangKy', 'Chờ duyệt')
                ->count();
                
            $pendingActivities = $pendingDRLActivities + $pendingCTXHActivities;

            return response()->json([
                'success' => true,
                'data' => [
                    'total_drl_score' => $totalDRLScore,
                    'total_ctxh_score' => $totalCTXHScore,
                    'completed_activities' => $completedActivities,
                    'pending_activities' => $pendingActivities,
                    'sinh_vien_info' => [
                        'mssv' => $sinhVien->MSSV,
                        'ho_ten' => $sinhVien->HoTen,
                        'lop' => $sinhVien->lop->TenLop ?? 'Chưa có lớp',
                        'khoa' => $sinhVien->lop->khoa->TenKhoa ?? 'Chưa có khoa',
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy dữ liệu dashboard: ' . $e->getMessage()
            ], 500);
        }
    }



    public function getDiemRenLuyenData(Request $request)
    {
        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;
            
            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ sinh viên mới có thể xem điểm rèn luyện'
                ], 403);
            }

            // Lấy danh sách học kỳ từ database
            $hocKyList = DB::table('hocky')
                ->orderBy('NgayBatDau', 'desc')
                ->select('MaHocKy', 'TenHocKy', 'NgayBatDau', 'NgayKetThuc')
                ->get();

            // Lấy điểm rèn luyện theo từng học kỳ
            $diemRenLuyenData = [];
            foreach ($hocKyList as $hocKy) {
                $diemRL = DB::table('diemrenluyen')
                    ->where('MSSV', $sinhVien->MSSV)
                    ->where('MaHocKy', $hocKy->MaHocKy)
                    ->first();
                
                if ($diemRL) {
                    // Lấy chi tiết điều chỉnh điểm (nếu có)
                    $dieuChinhList = DB::table('dieuchinhdrl as dc')
                        ->join('quydinhdiemrl as qd', 'dc.MaDiem', '=', 'qd.MaDiem')
                        ->join('nhanvien as nv', 'dc.MaNV', '=', 'nv.MaNV')
                        ->where('dc.MSSV', $sinhVien->MSSV)
                        ->where('dc.MaHocKy', $hocKy->MaHocKy)
                        ->select('qd.TenCongViec', 'qd.DiemNhan', 'nv.TenNV', 'dc.NgayCapNhat')
                        ->orderBy('dc.NgayCapNhat', 'desc')
                        ->get();

                    $diemRenLuyenData[] = [
                        'ma_hoc_ky' => $hocKy->MaHocKy,
                        'ten_hoc_ky' => $hocKy->TenHocKy,
                        'tong_diem' => $diemRL->TongDiem,
                        'xep_loai' => $diemRL->XepLoai,
                        'ngay_cap_nhat' => $diemRL->NgayCapNhat,
                        'chi_tiet_dieu_chinh' => $dieuChinhList->map(function($item) {
                            return [
                                'ten_cong_viec' => $item->TenCongViec,
                                'diem_nhan' => $item->DiemNhan,
                                'nguoi_cap_nhat' => $item->TenNV,
                                'ngay_cap_nhat' => $item->NgayCapNhat
                            ];
                        })->toArray()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'sinh_vien_info' => [
                        'mssv' => $sinhVien->MSSV,
                        'ho_ten' => $sinhVien->HoTen,
                        'ma_lop' => $sinhVien->lop->MaLop ?? 'Chưa có lớp',
                        'khoa' => $sinhVien->lop->khoa->TenKhoa ?? 'Chưa có khoa',
                    ],
                    'diem_ren_luyen' => $diemRenLuyenData,
                    'hoc_ky_list' => $hocKyList->map(function($hk) {
                        return [
                            'ma_hoc_ky' => $hk->MaHocKy,
                            'ten_hoc_ky' => $hk->TenHocKy
                        ];
                    })->toArray()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy dữ liệu điểm rèn luyện: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDiemCTXHData(Request $request)
    {
        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;
            
            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ sinh viên mới có thể xem điểm CTXH'
                ], 403);
            }

            // Lấy điểm CTXH từ database
            $diemCTXH = DB::table('diemctxh')
                ->where('MSSV', $sinhVien->MSSV)
                ->first();

            // Lấy chi tiết các hoạt động CTXH đã tham gia
            $hoatDongCTXH = DB::table('dangkyhoatdongctxh as dk')
                ->join('hoatdongctxh as hd', 'dk.MaHoatDong', '=', 'hd.MaHoatDong')
                ->join('quydinhdiemctxh as qd', 'hd.MaQuyDinhDiem', '=', 'qd.MaDiem')
                ->where('dk.MSSV', $sinhVien->MSSV)
                ->whereIn('dk.TrangThaiDangKy', ['Đã duyệt'])
                ->whereNotNull('dk.CheckOutAt') // Đã hoàn thành
                ->select(
                    'hd.TenHoatDong',
                    'hd.ThoiGianBatDau',
                    'hd.DiaDiem',
                    'qd.DiemNhan',
                    'dk.CheckInAt',
                    'dk.CheckOutAt',
                    'dk.NgayDangKy'
                )
                ->orderBy('hd.ThoiGianBatDau', 'desc')
                ->get();

            // Lấy các hoạt động đang chờ kết quả
            $hoatDongChoKetQua = DB::table('dangkyhoatdongctxh as dk')
                ->join('hoatdongctxh as hd', 'dk.MaHoatDong', '=', 'hd.MaHoatDong')
                ->join('quydinhdiemctxh as qd', 'hd.MaQuyDinhDiem', '=', 'qd.MaDiem')
                ->where('dk.MSSV', $sinhVien->MSSV)
                ->whereIn('dk.TrangThaiDangKy', ['Đã duyệt'])
                ->whereNull('dk.CheckOutAt') // Chưa hoàn thành
                ->select(
                    'hd.TenHoatDong',
                    'hd.ThoiGianBatDau',
                    'hd.DiaDiem',
                    'qd.DiemNhan',
                    'dk.TrangThaiDangKy',
                    'dk.NgayDangKy'
                )
                ->orderBy('hd.ThoiGianBatDau', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'sinh_vien_info' => [
                        'mssv' => $sinhVien->MSSV,
                        'ho_ten' => $sinhVien->HoTen,
                        'ma_lop' => $sinhVien->lop->MaLop ?? 'Chưa có lớp',
                        'khoa' => $sinhVien->lop->khoa->TenKhoa ?? 'Chưa có khoa',
                    ],
                    'tong_diem_ctxh' => $diemCTXH ? $diemCTXH->TongDiem : 0,
                    'hoat_dong_da_hoan_thanh' => $hoatDongCTXH->map(function($item) {
                        return [
                            'ten_hoat_dong' => $item->TenHoatDong,
                            'thoi_gian_bat_dau' => $item->ThoiGianBatDau,
                            'dia_diem' => $item->DiaDiem,
                            'diem_nhan' => $item->DiemNhan,
                            'check_in_at' => $item->CheckInAt,
                            'check_out_at' => $item->CheckOutAt,
                            'ngay_dang_ky' => $item->NgayDangKy
                        ];
                    })->toArray(),
                    'hoat_dong_cho_ket_qua' => $hoatDongChoKetQua->map(function($item) {
                        return [
                            'ten_hoat_dong' => $item->TenHoatDong,
                            'thoi_gian_bat_dau' => $item->ThoiGianBatDau,
                            'dia_diem' => $item->DiaDiem,
                            'diem_du_kien' => $item->DiemNhan,
                            'trang_thai' => $item->TrangThaiDangKy,
                            'ngay_dang_ky' => $item->NgayDangKy
                        ];
                    })->toArray()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy dữ liệu điểm CTXH: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getWeeklySchedule(Request $request)
    {
        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;
            
            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ sinh viên mới có thể xem lịch hoạt động'
                ], 403);
            }

            // Get extended date range: 7 days before to 7 days after for comprehensive schedule
            $startDate = now()->subDays(7)->startOfDay();
            $endDate = now()->addDays(7)->endOfDay();

            // Get DRL activities that student has registered and approved for
            $drlActivities = DangKyHoatDongDRL::where('MSSV', $sinhVien->MSSV)
                ->where('TrangThaiDangKy', 'Đã duyệt')
                ->with(['hoatdong', 'hoatdong.quydinh'])
                ->get()
                ->filter(function($registration) use ($startDate, $endDate) {
                    return $registration->hoatdong 
                        && $registration->hoatdong->ThoiGianBatDau >= $startDate 
                        && $registration->hoatdong->ThoiGianBatDau <= $endDate;
                })
                ->map(function($registration) {
                    $activity = $registration->hoatdong;
                    return [
                        'id' => $activity->MaHoatDong,
                        'ten' => $activity->TenHoatDong,
                        'mo_ta' => $activity->MoTa,
                        'ngay_to_chuc' => $activity->ThoiGianBatDau,
                        'thoi_gian_ket_thuc' => $activity->ThoiGianKetThuc,
                        'thoi_han_huy' => $activity->ThoiHanHuy,
                        'dia_diem' => $activity->DiaDiem,
                        'so_luong_toi_da' => $activity->SoLuong,
                        'diem_rl' => $activity->quydinh->DiemNhan ?? 0,
                        'type' => 'DRL',
                        'check_in_at' => $registration->CheckInAt,
                        'check_out_at' => $registration->CheckOutAt,
                        'trang_thai_tham_gia' => $registration->TrangThaiThamGia,
                    ];
                })
                ->sortBy('ngay_to_chuc')
                ->values();

            // Get CTXH activities that student has registered and approved for
            $ctxhActivities = DangKyHoatDongCTXH::where('MSSV', $sinhVien->MSSV)
                ->where('TrangThaiDangKy', 'Đã duyệt')
                ->with(['hoatdong', 'hoatdong.quydinh'])
                ->get()
                ->filter(function($registration) use ($startDate, $endDate) {
                    return $registration->hoatdong 
                        && $registration->hoatdong->ThoiGianBatDau >= $startDate 
                        && $registration->hoatdong->ThoiGianBatDau <= $endDate;
                })
                ->map(function($registration) {
                    $activity = $registration->hoatdong;
                    return [
                        'id' => $activity->MaHoatDong,
                        'ten' => $activity->TenHoatDong,
                        'mo_ta' => $activity->MoTa,
                        'ngay_to_chuc' => $activity->ThoiGianBatDau,
                        'thoi_gian_ket_thuc' => $activity->ThoiGianKetThuc,
                        'thoi_han_huy' => $activity->ThoiHanHuy,
                        'dia_diem' => $activity->DiaDiem,
                        'so_luong_toi_da' => $activity->SoLuong,
                        'diem_ctxh' => $activity->quydinh->DiemNhan ?? 0,
                        'type' => 'CTXH',
                        'check_in_at' => $registration->CheckInAt,
                        'check_out_at' => $registration->CheckOutAt,
                        'trang_thai_tham_gia' => $registration->TrangThaiThamGia,
                    ];
                })
                ->sortBy('ngay_to_chuc')
                ->values();

            // Merge activities and sort by date
            $allActivities = collect()
                ->merge($drlActivities)
                ->merge($ctxhActivities)
                ->sortBy('ngay_to_chuc')
                ->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'drl_activities' => $drlActivities,
                    'ctxh_activities' => $ctxhActivities,
                    'all_activities' => $allActivities,
                    'week_start' => $startDate->toDateString(),
                    'week_end' => $endDate->toDateString(),
                    'current_time' => now()->toDateTimeString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy lịch hoạt động: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDashboard(Request $request)
    {
        try {
            $user = $request->user();
            $sinhVien = $user->sinhVien;
            
            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ sinh viên mới có thể xem dashboard'
                ], 403);
            }

            // Count registered activities
            $drlRegisteredCount = DangKyHoatDongDRL::where('MSSV', $sinhVien->MSSV)->count();
            $ctxhRegisteredCount = DangKyHoatDongCTXH::where('MSSV', $sinhVien->MSSV)->count();

            // Count completed activities  
            $drlCompletedCount = DangKyHoatDongDRL::where('MSSV', $sinhVien->MSSV)
                ->where('TrangThaiDangKy', 'Đã duyệt')
                ->count();
            $ctxhCompletedCount = DangKyHoatDongCTXH::where('MSSV', $sinhVien->MSSV)
                ->where('TrangThaiDangKy', 'Đã duyệt')
                ->count();

            // Calculate total points
            $drlPoints = DangKyHoatDongDRL::join('hoatdongdrl', 'dangkyhoatdongdrl.MaHoatDong', '=', 'hoatdongdrl.MaHoatDong')
                ->join('quydinhdiemdrl', 'hoatdongdrl.MaQuyDinhDiem', '=', 'quydinhdiemdrl.MaQuyDinh')
                ->where('dangkyhoatdongdrl.MSSV', $sinhVien->MSSV)
                ->where('dangkyhoatdongdrl.TrangThaiDangKy', 'Đã duyệt')
                ->sum('quydinhdiemdrl.DiemRL');

            $ctxhPoints = DangKyHoatDongCTXH::join('hoatdongctxh', 'dangkyhoatdongctxh.MaHoatDong', '=', 'hoatdongctxh.MaHoatDong')
                ->join('quydinhdiem', 'hoatdongctxh.MaQuyDinhDiem', '=', 'quydinhdiem.MaQuyDinh')
                ->where('dangkyhoatdongctxh.MSSV', $sinhVien->MSSV)
                ->where('dangkyhoatdongctxh.TrangThaiDangKy', 'Đã duyệt')
                ->sum('quydinhdiem.DiemCTXH');

            return response()->json([
                'success' => true,
                'data' => [
                    'drl_registered' => $drlRegisteredCount,
                    'ctxh_registered' => $ctxhRegisteredCount,
                    'drl_completed' => $drlCompletedCount,
                    'ctxh_completed' => $ctxhCompletedCount,
                    'total_drl_points' => $drlPoints ?? 0,
                    'total_ctxh_points' => $ctxhPoints ?? 0
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy dữ liệu dashboard: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel DRL registration
     */
    public function cancelRegistrationDRL($maDangKy)
    {
        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không được xác thực'
                ], 401);
            }

            $dangKy = DangKyHoatDongDRL::find($maDangKy);
            if (!$dangKy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đăng ký'
                ], 404);
            }

            if ($dangKy->MaSV !== $user->MaSV) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền hủy đăng ký này'
                ], 403);
            }

            // Check if can cancel (status and time window)
            $hoatdong = $dangKy->hoatdong;
            if (!$hoatdong) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy hoạt động'
                ], 404);
            }

            $now = Carbon::now();
            $thoiHanHuy = Carbon::parse($hoatdong->ThoiHanHuy);

            // Check if within cancellation window
            if (!$now->lt($thoiHanHuy)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã quá hạn hủy đăng ký'
                ], 400);
            }

            // Check if status allows cancellation
            $allowedStatuses = ['Chờ duyệt', 'Đã duyệt'];
            if (!in_array($dangKy->TrangThaiDangKy, $allowedStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể hủy đăng ký với trạng thái này'
                ], 400);
            }

            // Delete the registration
            $dangKy->delete();

            return response()->json([
                'success' => true,
                'message' => 'Hủy đăng ký thành công'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hủy đăng ký: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel CTXH registration
     */
    public function cancelRegistrationCTXH($maDangKy)
    {
        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không được xác thực'
                ], 401);
            }

            $dangKy = DangKyHoatDongCTXH::find($maDangKy);
            if (!$dangKy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đăng ký'
                ], 404);
            }

            if ($dangKy->MaSV !== $user->MaSV) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền hủy đăng ký này'
                ], 403);
            }

            // Check if can cancel (status and time window)
            $hoatdong = $dangKy->hoatdong;
            if (!$hoatdong) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy hoạt động'
                ], 404);
            }

            $now = Carbon::now();
            $thoiHanHuy = Carbon::parse($hoatdong->ThoiHanHuy);

            // Check if within cancellation window
            if (!$now->lt($thoiHanHuy)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã quá hạn hủy đăng ký'
                ], 400);
            }

            // Check if status allows cancellation
            $allowedStatuses = ['Chờ duyệt', 'Chờ thanh toán', 'Đã duyệt'];
            if (!in_array($dangKy->TrangThaiDangKy, $allowedStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể hủy đăng ký với trạng thái này'
                ], 400);
            }

            // Delete the registration (payment handling if exists)
            // If there's associated payment, it should be handled by payment model relationships
            $dangKy->delete();

            return response()->json([
                'success' => true,
                'message' => 'Hủy đăng ký thành công'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hủy đăng ký: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRecommendations(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Get user MSSV from SinhVien table
            $sinhVien = SinhVien::where('Email', $user->email)->first();
            if (!$sinhVien) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // Get recommendations from database
            $recommendations = DB::table('activity_recommendations')
                ->where('MSSV', $sinhVien->MSSV)
                ->orderBy('recommendation_score', 'desc')
                ->limit(10)
                ->get();

            if ($recommendations->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // Format recommendations with activity details
            $formattedRecs = $recommendations->map(function($rec) {
                $activity = null;
                
                // Get activity details based on MaHoatDong and type
                if (strtoupper($rec->activity_type) === 'DRL') {
                    $activity = HoatDongDRL::with(['quydinh'])
                        ->where('MaHoatDong', $rec->MaHoatDong)
                        ->first();
                } else {
                    $activity = HoatDongCTXH::with(['quydinh'])
                        ->where('MaHoatDong', $rec->MaHoatDong)
                        ->first();
                }

                if (!$activity) {
                    return null;
                }

                return [
                    'id' => $rec->id,
                    'recommendation_score' => (int)$rec->recommendation_score,
                    'recommendation_reason' => $rec->recommendation_reason,
                    'activity' => [
                        'id' => $activity->MaHoatDong,
                        'ten' => $activity->TenHoatDong,
                        'mo_ta' => $activity->MoTa,
                        'ngay_to_chuc' => $activity->ThoiGianBatDau,
                        'dia_diem' => $activity->DiaDiem,
                        'so_luong_toi_da' => $activity->SoLuong,
                        'diem' => $activity->quydinh->DiemNhan ?? 0,
                        'type' => strtoupper($rec->activity_type)
                    ]
                ];
            })->filter();

            return response()->json([
                'success' => true,
                'data' => $formattedRecs->values()->all()
            ]);
        } catch (\Exception $e) {
            Log::error('Get recommendations error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy gợi ý hoạt động: ' . $e->getMessage()
            ], 500);
        }
    }
}