<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DangKyHoatDongDRL;
use App\Models\DangKyHoatDongCTXH;
use App\Models\KetQuaThamGiaDRL;
use App\Models\KetQuaThamGiaCTXH;

class ScoreApiController extends Controller
{
    /**
     * Get pending scores for authenticated user
     * Returns activities with "Vắng" status that haven't been scored yet
     */
    public function getPendingScores()
    {
        $user = auth()->guard('sanctum')->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Không được xác thực'
            ], 401);
        }

        $userMSSV = $this->getUserMSSV($user);

        // Get DRL with absent status
        $drlAbsent = DangKyHoatDongDRL::where('MSSV', $userMSSV)
            ->where('TrangThaiThamGia', 'Vắng')
            ->where('TrangThaiDangKy', 'Đã duyệt')
            ->with(['hoatdong' => function($q) {
                $q->with('quydinh');
            }])
            ->get()
            ->map(function($reg) {
                return [
                    'id' => $reg->MaDangKy,
                    'type' => 'DRL',
                    'activity' => $reg->hoatdong->TenHoatDong ?? 'N/A',
                    'date' => $reg->hoatdong->ThoiGianBatDau,
                    'status' => $reg->TrangThaiThamGia,
                    'base_score' => $reg->hoatdong->quydinh->DiemNhan ?? 0,
                    'deduction' => -0.5,
                ];
            });

        // Get CTXH with absent status
        $ctxhAbsent = DangKyHoatDongCTXH::where('MSSV', $userMSSV)
            ->where('TrangThaiThamGia', 'Vắng')
            ->where('TrangThaiDangKy', 'Đã duyệt')
            ->with(['hoatdong' => function($q) {
                $q->with('quydinh');
            }])
            ->get()
            ->map(function($reg) {
                return [
                    'id' => $reg->MaDangKy,
                    'type' => 'CTXH',
                    'activity' => $reg->hoatdong->TenHoatDong ?? 'N/A',
                    'date' => $reg->hoatdong->ThoiGianBatDau,
                    'status' => $reg->TrangThaiThamGia,
                    'base_score' => $reg->hoatdong->quydinh->DiemNhan ?? 0,
                    'deduction' => -1,
                ];
            });

        $pendingScores = array_merge($drlAbsent->toArray(), $ctxhAbsent->toArray());

        return response()->json([
            'success' => true,
            'data' => [
                'pending_scores' => $pendingScores,
                'total_drl_absent' => count($drlAbsent),
                'total_ctxh_absent' => count($ctxhAbsent),
            ]
        ]);
    }

    /**
     * Get score summary for authenticated user
     */
    public function getScoreSummary()
    {
        $user = auth()->guard('sanctum')->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Không được xác thực'
            ], 401);
        }

        $userMSSV = $this->getUserMSSV($user);

        // Get DRL scores
        $drlScores = KetQuaThamGiaDRL::where('MSSV', $userMSSV)
            ->get();

        $drlTotal = $drlScores->sum('Diem');
        $drlCount = $drlScores->count();
        $drlAbsent = $drlScores->where('TrangThai', 'Vắng')->count();

        // Get CTXH scores
        $ctxhScores = KetQuaThamGiaCTXH::where('MSSV', $userMSSV)
            ->get();

        $ctxhTotal = $ctxhScores->sum('Diem');
        $ctxhCount = $ctxhScores->count();
        $ctxhAbsent = $ctxhScores->where('TrangThai', 'Vắng')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'drl' => [
                    'total_score' => $drlTotal,
                    'total_events' => $drlCount,
                    'absent_count' => $drlAbsent,
                    'participated' => $drlCount - $drlAbsent,
                ],
                'ctxh' => [
                    'total_score' => $ctxhTotal,
                    'total_events' => $ctxhCount,
                    'absent_count' => $ctxhAbsent,
                    'participated' => $ctxhCount - $ctxhAbsent,
                ],
                'grand_total' => $drlTotal + $ctxhTotal,
            ]
        ]);
    }

    /**
     * Admin: Record attendance and calculate score
     * POST /api/v1/admin/record-attendance
     */
    public function recordAttendance(Request $request)
    {
        $user = auth()->guard('sanctum')->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Không được xác thực'
            ], 401);
        }

        $validated = $request->validate([
            'registrations' => 'required|array',
            'registrations.*.registration_id' => 'required|integer',
            'registrations.*.type' => 'required|in:DRL,CTXH',
            'registrations.*.status' => 'required|in:Đã tham gia,Vắng,Hủy',
        ]);

        $processed = 0;
        $errors = [];

        foreach ($validated['registrations'] as $item) {
            try {
                $this->processAttendance($item);
                $processed++;
            } catch (\Exception $e) {
                $errors[] = [
                    'registration_id' => $item['registration_id'],
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Xử lý $processed bản ghi thành công",
            'data' => [
                'processed' => $processed,
                'failed' => count($errors),
                'errors' => $errors,
            ]
        ]);
    }

    /**
     * Process single attendance record
     */
    private function processAttendance($item)
    {
        $type = $item['type'];
        $regId = $item['registration_id'];
        $status = $item['status'];

        if ($type === 'DRL') {
            $registration = DangKyHoatDongDRL::with('hoatdong.quydinh')
                ->findOrFail($regId);
            
            $score = $this->calculateDRLScore($registration, $status);
            
            // Update or create score record
            KetQuaThamGiaDRL::updateOrCreate(
                [
                    'MSSV' => $registration->MSSV,
                    'MaHoatDong' => $registration->MaHoatDong,
                ],
                [
                    'Diem' => $score,
                    'TrangThai' => $status,
                ]
            );
        } else {
            $registration = DangKyHoatDongCTXH::with('hoatdong.quydinh')
                ->findOrFail($regId);
            
            $score = $this->calculateCTXHScore($registration, $status);
            
            // Update or create score record
            KetQuaThamGiaCTXH::updateOrCreate(
                [
                    'MSSV' => $registration->MSSV,
                    'MaHoatDong' => $registration->MaHoatDong,
                ],
                [
                    'Diem' => $score,
                    'TrangThai' => $status,
                ]
            );
        }

        // Update registration status
        $registration->TrangThaiThamGia = $status;
        $registration->save();
    }

    /**
     * Calculate DRL score
     */
    private function calculateDRLScore($registration, $status)
    {
        $baseScore = $registration->hoatdong->quydinh->DiemNhan ?? 0;

        switch ($status) {
            case 'Đã tham gia':
                return $baseScore;
            case 'Vắng':
                return -0.5;
            case 'Hủy':
                return 0;
            default:
                return 0;
        }
    }

    /**
     * Calculate CTXH score
     */
    private function calculateCTXHScore($registration, $status)
    {
        $baseScore = $registration->hoatdong->quydinh->DiemNhan ?? 0;

        switch ($status) {
            case 'Đã tham gia':
                return $baseScore;
            case 'Vắng':
                return -1;
            case 'Hủy':
                return 0;
            default:
                return 0;
        }
    }

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
}
