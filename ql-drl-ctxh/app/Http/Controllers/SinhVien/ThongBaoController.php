<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <-- Thêm Request
use App\Models\HoatDongDRL;
use App\Models\HoatDongCTXH;
use Illuminate\Pagination\LengthAwarePaginator;

class ThongBaoController extends Controller
{
    /**
     * Hiển thị danh sách TẤT CẢ các hoạt động DRL và CTXH
     */
    public function index(Request $request) // <-- Thêm Request
    {
        // 1. Lấy bộ lọc từ URL, mặc định là 'all'
        $filter = $request->input('type', 'all');

        $allActivities = collect([]);

        // 2. Lấy dữ liệu DRL nếu bộ lọc là 'all' hoặc 'drl'
        if ($filter == 'all' || $filter == 'drl') {
            $activitiesDRL = HoatDongDRL::with('quydinh')->get();
            foreach ($activitiesDRL as $activity) {
                $activity->Loai = 'DRL';
                $allActivities->push($activity);
            }
        }

        // 3. Lấy dữ liệu CTXH nếu bộ lọc là 'all' hoặc 'ctxh'
        if ($filter == 'all' || $filter == 'ctxh') {
            $activitiesCTXH = HoatDongCTXH::with('quydinh')->get();
            foreach ($activitiesCTXH as $activity) {
                $activity->Loai = 'CTXH';
                $allActivities->push($activity);
            }
        }

        // 4. Sắp xếp collection tổng theo ngày bắt đầu (mới nhất lên đầu)
        $sortedActivities = $allActivities->sortByDesc('ThoiGianBatDau');

        // 5. Phân trang thủ công
        $perPage = 5;
        $currentPage = $request->input('page', 1);
        $currentPageItems = $sortedActivities->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $paginatedActivities = new LengthAwarePaginator(
            $currentPageItems, 
            $sortedActivities->count(), 
            $perPage, 
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 6. THÊM BỘ LỌC VÀO LINK PHÂN TRANG
        $paginatedActivities->appends(['type' => $filter]);

        // 7. Trả về view
        return view('sinhvien.tintuc.index', [
            'thongBaos' => $paginatedActivities,
            'currentFilter' => $filter // <-- Truyền bộ lọc hiện tại sang view
        ]);
    }
}
