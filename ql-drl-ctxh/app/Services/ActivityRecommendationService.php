<?php

namespace App\Services;

use App\Models\SinhVien;
use App\Models\HoatDongDRL;
use App\Models\HoatDongCTXH;
use App\Models\DiemRenLuyen;
use App\Models\DiemCTXH;
use App\Models\ActivityRecommendation;
use App\Models\DangKyHoatDongDRL;
use App\Models\DangKyHoatDongCTXH;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ActivityRecommendationService
{
    /**
     * Thuật toán clustering - tính toán điểm đề xuất cho sinh viên
     * 
     * Tiêu chí ưu tiên:
     * 1. Sinh viên có điểm RL < 60 → Ưu tiên cao (Priority: 10)
     * 2. Sinh viên sắp tốt nghiệp (< 3 tháng) nhưng CTXH chưa đạt 170 → Ưu tiên cao (Priority: 9)
     * 3. Hoạt động phù hợp sở thích → Ưu tiên trung bình (Priority: 5)
     * 4. Địa chỉ đỏ cho sinh viên CTXH chưa đủ → Ưu tiên trung bình (Priority: 6)
     */
    public function generateRecommendations()
    {
        try {
            // Lấy tất cả sinh viên còn hoạt động
            $allStudents = SinhVien::whereHas('lop')->get();

            foreach ($allStudents as $student) {
                $this->generateRecommendationForStudent($student);
            }

            Log::info('✓ Recommendation clustering hoàn tất.');
        } catch (\Exception $e) {
            Log::error('Lỗi clustering: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Tính toán đề xuất cho 1 sinh viên
     */
    private function generateRecommendationForStudent(SinhVien $student)
    {
        $mssv = $student->MSSV;
        $now = Carbon::now();

        // Xóa recommendations cũ
        ActivityRecommendation::where('MSSV', $mssv)->delete();

        // Lấy điểm RL và CTXH
        $diemRL = DiemRenLuyen::where('MSSV', $mssv)->first()?->DiemRL ?? 0;
        $diemCTXH = DiemCTXH::where('MSSV', $mssv)->first()?->DiemCTXH ?? 0;

        // Lấy thời gian tốt nghiệp
        $thoiGianTotNghiep = $student->ThoiGianTotNghiepDuKien 
            ? Carbon::parse($student->ThoiGianTotNghiepDuKien)
            : null;

        // Kiểm tra sắp tốt nghiệp (< 3 tháng)
        $isGraduatingSoon = $thoiGianTotNghiep && $now->diffInMonths($thoiGianTotNghiep) < 3;

        // Sở thích của sinh viên
        $preferences = $this->parsePreferences($student->SoThich);

        // Lấy hoạt động đã đăng ký
        $registeredDRL = DangKyHoatDongDRL::where('MSSV', $mssv)
            ->pluck('MaHoatDong')->toArray();
        $registeredCTXH = DangKyHoatDongCTXH::where('MSSV', $mssv)
            ->pluck('MaHoatDong')->toArray();
        $registeredActivities = array_merge($registeredDRL, $registeredCTXH);

        // ========== HOẠT ĐỘNG DRL ==========
        if ($diemRL < 60) {
            $drlActivities = HoatDongDRL::where('ThoiGianKetThuc', '>', $now)
                ->whereNotIn('MaHoatDong', $registeredActivities)
                ->get();

            foreach ($drlActivities as $activity) {
                $score = 0;
                $reason = [];

                // Điểm cơ bản vì điểm RL thấp
                $score += 70; // Base score
                $reason[] = 'low_rl_score';

                // Điểm bonus nếu phù hợp sở thích
                if ($activity->LoaiHoatDong && $this->isPreferenceMatch($activity->LoaiHoatDong, $preferences)) {
                    $score += 15;
                    $reason[] = 'preference_match';
                }

                // Lưu đề xuất
                ActivityRecommendation::create([
                    'MSSV' => $mssv,
                    'MaHoatDong' => $activity->MaHoatDong,
                    'activity_type' => 'drl',
                    'recommendation_score' => min($score, 100),
                    'recommendation_reason' => implode(',', $reason),
                    'priority' => 10, // Ưu tiên cao nhất
                ]);
            }
        }

        // ========== HOẠT ĐỘNG CTXH ==========
        // Kiểm tra: CTXH < 170 OR sắp tốt nghiệp nhưng CTXH < 170
        if ($diemCTXH < 170 || ($isGraduatingSoon && $diemCTXH < 170)) {
            // Lấy hoạt động CTXH thường
            $ctxhActivities = HoatDongCTXH::where('ThoiGianKetThuc', '>', $now)
                ->where(function ($query) {
                    $query->whereNull('dot_id')
                        ->orWhere('dot_id', '=', '');
                })
                ->whereNotIn('MaHoatDong', $registeredActivities)
                ->get();

            foreach ($ctxhActivities as $activity) {
                $score = 50; // Base score
                $reason = [];

                if ($diemCTXH < 170) {
                    $score += 30;
                    $reason[] = 'incomplete_ctxh';
                }

                if ($isGraduatingSoon) {
                    $score += 20;
                    $reason[] = 'graduating_soon';
                }

                if ($activity->LoaiHoatDong && $this->isPreferenceMatch($activity->LoaiHoatDong, $preferences)) {
                    $score += 15;
                    $reason[] = 'preference_match';
                }

                ActivityRecommendation::create([
                    'MSSV' => $mssv,
                    'MaHoatDong' => $activity->MaHoatDong,
                    'activity_type' => 'ctxh',
                    'recommendation_score' => min($score, 100),
                    'recommendation_reason' => implode(',', $reason),
                    'priority' => ($isGraduatingSoon && $diemCTXH < 170) ? 9 : 6,
                ]);
            }

            // ========== ĐỊA CHỈ ĐỎ (CTXH) ==========
            // Ưu tiên cho sinh viên cần hoàn thành CTXH
            $diaChiDoActivities = HoatDongCTXH::where('ThoiGianKetThuc', '>', $now)
                ->where('LoaiHoatDong', '=', 'Địa chỉ đỏ')
                ->whereNotIn('MaHoatDong', $registeredActivities)
                ->get();

            foreach ($diaChiDoActivities as $activity) {
                $score = 60; // Base score cao cho địa chỉ đỏ
                $reason = ['red_address'];

                if ($diemCTXH < 170) {
                    $score += 25;
                    $reason[] = 'incomplete_ctxh';
                }

                if ($isGraduatingSoon) {
                    $score += 15;
                    $reason[] = 'graduating_soon';
                }

                ActivityRecommendation::create([
                    'MSSV' => $mssv,
                    'MaHoatDong' => $activity->MaHoatDong,
                    'activity_type' => 'ctxh',
                    'recommendation_score' => min($score, 100),
                    'recommendation_reason' => implode(',', $reason),
                    'priority' => ($isGraduatingSoon && $diemCTXH < 170) ? 9 : 7,
                ]);
            }
        }

        Log::info("✓ Recommendations for {$mssv}: " . 
            ActivityRecommendation::where('MSSV', $mssv)->count() . ' activities');
    }

    /**
     * Parse sở thích từ chuỗi (VD: "Tình nguyện,Thể thao")
     */
    private function parsePreferences(?string $preferencesStr): array
    {
        if (!$preferencesStr) {
            return [];
        }

        return array_map('trim', explode(',', strtolower($preferencesStr)));
    }

    /**
     * Kiểm tra hoạt động có phù hợp sở thích không
     */
    private function isPreferenceMatch(string $activityCategory, array $preferences): bool
    {
        if (empty($preferences)) {
            return false;
        }

        $categoryLower = strtolower($activityCategory);
        
        foreach ($preferences as $pref) {
            if (strpos($categoryLower, $pref) !== false || strpos($pref, $categoryLower) !== false) {
                return true;
            }
        }

        return false;
    }
}
