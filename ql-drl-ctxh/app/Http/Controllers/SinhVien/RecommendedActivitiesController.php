<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityRecommendation;
use App\Models\StudentCluster;
use App\Models\HoatDongDRL;
use App\Models\HoatDongCTXH;
use Illuminate\Support\Facades\Auth;

class RecommendedActivitiesController extends Controller
{
    /**
     * Hiển thị danh sách hoạt động được đề xuất cho sinh viên
     * Dựa trên K-Means clustering
     */
    public function index()
    {
        $mssv = Auth::user()->TenDangNhap;

        // Lấy cluster của sinh viên
        $studentCluster = StudentCluster::where('MSSV', $mssv)->first();

        if (!$studentCluster) {
            return view('sinhvien.activities_recommended.index', [
                'recommendations' => collect(),
                'studentCluster' => null,
                'clusterName' => 'Chưa phân cụm',
                'clusterInfo' => []
            ]);
        }

        // Lấy recommendation từ K-Means
        $recommendations = ActivityRecommendation::where('MSSV', $mssv)
            ->orderBy('recommendation_score', 'desc')
            ->with(['hoatDongDRL', 'hoatDongCTXH'])
            ->paginate(10);

        return view('sinhvien.activities_recommended.index', [
            'recommendations' => $recommendations,
            'studentCluster' => $studentCluster,
            'clusterName' => $studentCluster->cluster_name,
            'clusterInfo' => [
                'interest_match' => 0.3,
                'popularity' => 0.25,
                'success_rate' => 0.2,
                'recency' => 0.15,
                'novelty' => 0.1
            ]
        ]);
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
