<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityRecommendation;
use App\Models\HoatDongDRL;
use App\Models\HoatDongCTXH;
use Illuminate\Support\Facades\Auth;

class RecommendedActivitiesController extends Controller
{
    /**
     * Hiển thị danh sách hoạt động được đề xuất cho sinh viên
     */
    public function index()
    {
        $mssv = Auth::user()->TenDangNhap;

        // Lấy tất cả recommendations, sắp xếp theo priority (cao nhất trước) và score
        $recommendations = ActivityRecommendation::where('MSSV', $mssv)
            ->orderBy('priority', 'desc')
            ->orderBy('recommendation_score', 'desc')
            ->with(['hoatDongDRL', 'hoatDongCTXH'])
            ->paginate(10);

        // Đếm các loại đề xuất
        $countLowRL = ActivityRecommendation::where('MSSV', $mssv)
            ->where('recommendation_reason', 'like', '%low_rl_score%')
            ->count();

        $countIncompleteCTXH = ActivityRecommendation::where('MSSV', $mssv)
            ->where('recommendation_reason', 'like', '%incomplete_ctxh%')
            ->count();

        $countGraduatingSoon = ActivityRecommendation::where('MSSV', $mssv)
            ->where('recommendation_reason', 'like', '%graduating_soon%')
            ->count();

        return view('sinhvien.activities_recommended.index', compact(
            'recommendations',
            'countLowRL',
            'countIncompleteCTXH',
            'countGraduatingSoon'
        ));
    }

    /**
     * Xem chi tiết một đề xuất
     */
    public function show($id)
    {
        $mssv = Auth::user()->TenDangNhap;
        
        $recommendation = ActivityRecommendation::where('MSSV', $mssv)
            ->where('id', $id)
            ->with(['hoatDongDRL', 'hoatDongCTXH'])
            ->firstOrFail();

        // Cập nhật viewed_at
        $recommendation->update(['viewed_at' => now()]);

        return view('sinhvien.activities_recommended.show', compact('recommendation'));
    }

    /**
     * Lấy hoạt động thực tế từ DRL hoặc CTXH
     */
    public function getActivity($id, $type)
    {
        if ($type === 'drl') {
            $activity = HoatDongDRL::with('quydinh')->find($id);
        } else {
            $activity = HoatDongCTXH::with('quydinh')->find($id);
        }

        return response()->json($activity);
    }
}
