<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DangKyHoatDongDRL;
use App\Models\DangKyHoatDongCTXH;
use App\Models\KetQuaThamGiaDRL;
use App\Models\KetQuaThamGiaCTXH;
use App\Models\DiemRenLuyen;
use App\Models\DiemCTXH;
use Carbon\Carbon;

class ProcessAttendanceScores extends Command
{
    protected $signature = 'attendance:process-scores {--force : Bỏ qua xác nhận} {--semester= : Mã học kỳ cụ thể}';
    protected $description = 'Xử lý trừ điểm cho sinh viên vắng hoạt động';

    public function handle()
    {
        $this->info('🔄 Bắt đầu xử lý điểm tham gia hoạt động...');
        $this->newLine();

        try {
            $semesterId = $this->option('semester');
            
            // Process DRL attendance
            $drlProcessed = $this->processDRLAttendance($semesterId);
            $this->info("✅ Xử lý DRL: $drlProcessed bản ghi");

            // Process CTXH attendance
            $ctxhProcessed = $this->processCTXHAttendance($semesterId);
            $this->info("✅ Xử lý CTXH: $ctxhProcessed bản ghi");

            $total = $drlProcessed + $ctxhProcessed;
            $this->newLine();
            $this->info("✅ Tổng cộng: $total bản ghi được xử lý");
            
        } catch (\Exception $e) {
            $this->error('❌ Lỗi: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Process DRL attendance and scores
     */
    private function processDRLAttendance($semesterId = null)
    {
        $query = DangKyHoatDongDRL::with([
            'sinhvien.diemrenluyen',
            'hoatdong' => function($q) {
                $q->with('quydinh', 'hocky');
            }
        ])
        ->whereNotNull('TrangThaiThamGia')
        ->where('TrangThaiDangKy', 'Đã duyệt'); // Only approved registrations

        if ($semesterId) {
            $query->whereHas('hoatdong.hocky', function($q) use ($semesterId) {
                $q->where('MaHocKy', $semesterId);
            });
        }

        $registrations = $query->get();
        $processed = 0;

        foreach ($registrations as $registration) {
            try {
                $score = $this->calculateDRLScore($registration);
                
                // Check if result exists
                $result = KetQuaThamGiaDRL::where('MSSV', $registration->MSSV)
                    ->where('MaHoatDong', $registration->MaHoatDong)
                    ->first();

                if ($result) {
                    // Update existing
                    $result->Diem = $score;
                    $result->TrangThai = $registration->TrangThaiThamGia;
                    $result->save();
                } else {
                    // Create new
                    KetQuaThamGiaDRL::create([
                        'MSSV' => $registration->MSSV,
                        'MaHoatDong' => $registration->MaHoatDong,
                        'Diem' => $score,
                        'TrangThai' => $registration->TrangThaiThamGia,
                    ]);
                }

                // Update DiemRenLuyen if available
                if ($registration->sinhvien && $registration->sinhvien->diemrenluyen) {
                    $this->updateDiemRenLuyen(
                        $registration->sinhvien->diemrenluyen,
                        $registration->hoatdong->hocky
                    );
                }

                $processed++;
            } catch (\Exception $e) {
                $this->warn("⚠️ Lỗi xử lý DRL {$registration->MaDangKy}: {$e->getMessage()}");
            }
        }

        return $processed;
    }

    /**
     * Process CTXH attendance and scores
     */
    private function processCTXHAttendance($semesterId = null)
    {
        $query = DangKyHoatDongCTXH::with([
            'sinhvien.diemctxh',
            'hoatdong' => function($q) {
                $q->with('quydinh', 'hocky');
            }
        ])
        ->whereNotNull('TrangThaiThamGia')
        ->where('TrangThaiDangKy', 'Đã duyệt'); // Only approved registrations

        if ($semesterId) {
            $query->whereHas('hoatdong.hocky', function($q) use ($semesterId) {
                $q->where('MaHocKy', $semesterId);
            });
        }

        $registrations = $query->get();
        $processed = 0;

        foreach ($registrations as $registration) {
            try {
                $score = $this->calculateCTXHScore($registration);
                
                // Check if result exists
                $result = KetQuaThamGiaCTXH::where('MSSV', $registration->MSSV)
                    ->where('MaHoatDong', $registration->MaHoatDong)
                    ->first();

                if ($result) {
                    // Update existing
                    $result->Diem = $score;
                    $result->TrangThai = $registration->TrangThaiThamGia;
                    $result->save();
                } else {
                    // Create new
                    KetQuaThamGiaCTXH::create([
                        'MSSV' => $registration->MSSV,
                        'MaHoatDong' => $registration->MaHoatDong,
                        'Diem' => $score,
                        'TrangThai' => $registration->TrangThaiThamGia,
                    ]);
                }

                // Update DiemCTXH if available
                if ($registration->sinhvien && $registration->sinhvien->diemctxh) {
                    $this->updateDiemCTXH(
                        $registration->sinhvien->diemctxh,
                        $registration->hoatdong->hocky
                    );
                }

                $processed++;
            } catch (\Exception $e) {
                $this->warn("⚠️ Lỗi xử lý CTXH {$registration->MaDangKy}: {$e->getMessage()}");
            }
        }

        return $processed;
    }

    /**
     * Calculate DRL score based on attendance status
     */
    private function calculateDRLScore($registration)
    {
        $status = trim($registration->TrangThaiThamGia);
        $baseScore = $registration->hoatdong->quydinh->DiemNhan ?? 0;

        switch ($status) {
            case 'Đã tham gia':
                // Full score if participated
                return $baseScore;
            
            case 'Vắng':
                // Deduct 0.5 points per event if absent
                return -0.5;
            
            case 'Hủy':
            case 'Bỏ qua':
                // No score if cancelled
                return 0;
            
            default:
                return 0;
        }
    }

    /**
     * Calculate CTXH score based on attendance status
     */
    private function calculateCTXHScore($registration)
    {
        $status = trim($registration->TrangThaiThamGia);
        $baseScore = $registration->hoatdong->quydinh->DiemNhan ?? 0;

        switch ($status) {
            case 'Đã tham gia':
                // Full score if participated
                return $baseScore;
            
            case 'Vắng':
                // Deduct 1 point per CTXH event if absent
                return -1;
            
            case 'Hủy':
            case 'Bỏ qua':
                // No score if cancelled
                return 0;
            
            default:
                return 0;
        }
    }

    /**
     * Update DiemRenLuyen after scoring
     */
    private function updateDiemRenLuyen($diemRL, $hocky)
    {
        if (!$diemRL || !$hocky) {
            return;
        }

        // Get all DRL results for this student and semester
        $drlResults = KetQuaThamGiaDRL::where('MSSV', $diemRL->MSSV)
            ->whereHas('hoatdong', function($q) use ($hocky) {
                $q->where('MaHocKy', $hocky->MaHocKy);
            })
            ->get();

        $totalScore = 0;
        foreach ($drlResults as $result) {
            $totalScore += $result->Diem;
        }

        // Update total score
        $diemRL->TongDiem = max(0, $diemRL->TongDiem + $totalScore);
        
        // Determine rank
        if ($diemRL->TongDiem >= 85) {
            $diemRL->XepLoai = 'Xuất sắc';
        } elseif ($diemRL->TongDiem >= 70) {
            $diemRL->XepLoai = 'Tốt';
        } elseif ($diemRL->TongDiem >= 50) {
            $diemRL->XepLoai = 'Khá';
        } else {
            $diemRL->XepLoai = 'Trung bình';
        }

        $diemRL->save();
    }

    /**
     * Update DiemCTXH after scoring
     */
    private function updateDiemCTXH($diemCTXH, $hocky)
    {
        if (!$diemCTXH || !$hocky) {
            return;
        }

        // Get all CTXH results for this student and semester
        $ctxhResults = KetQuaThamGiaCTXH::where('MSSV', $diemCTXH->MSSV)
            ->whereHas('hoatdong', function($q) use ($hocky) {
                $q->where('MaHocKy', $hocky->MaHocKy);
            })
            ->get();

        $totalScore = 0;
        foreach ($ctxhResults as $result) {
            $totalScore += $result->Diem;
        }

        // Update total score
        $diemCTXH->TongDiem = max(0, $diemCTXH->TongDiem + $totalScore);
        
        // Determine rank
        if ($diemCTXH->TongDiem >= 85) {
            $diemCTXH->XepLoai = 'Xuất sắc';
        } elseif ($diemCTXH->TongDiem >= 70) {
            $diemCTXH->XepLoai = 'Tốt';
        } elseif ($diemCTXH->TongDiem >= 50) {
            $diemCTXH->XepLoai = 'Khá';
        } else {
            $diemCTXH->XepLoai = 'Trung bình';
        }

        $diemCTXH->save();
    }
}
