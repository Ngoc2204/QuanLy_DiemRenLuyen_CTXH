<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HocKy;
use App\Models\Khoa;
use App\Models\Lop;
use App\Models\HoatDongDrl;
use App\Models\HoatDongCtxh;
use App\Models\DiemRenLuyen;
use App\Models\DiemCtxh;
use App\Models\SinhVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        $hocKys = HocKy::orderBy('NgayBatDau', 'desc')->get();
        $khoas = Khoa::orderBy('TenKhoa')->get();
        $selectedHocKy = $request->input('hoc_ky', $hocKys->first()->MaHocKy ?? null);
        $selectedKhoa = $request->input('khoa');
        $selectedType = $request->input('type');

        // Lọc sinh viên theo khoa (nếu có)
        $sinhVienQuery = SinhVien::query();
        if ($selectedKhoa) {
            $sinhVienQuery->whereHas('lop.khoa', function ($q) use ($selectedKhoa) {
                $q->where('MaKhoa', $selectedKhoa);
            });
        }
        $mssvList = $sinhVienQuery->pluck('MSSV')->toArray();
        $hasMssvFilter = !empty($mssvList) || $selectedKhoa;


        // Initialize statistics variables
        $tongHoatDongDRL = 0;
        $tongHoatDongCTXH = 0;
        $tongSlotsDRL = 0;
        $tongSlotsCTXH = 0;
        $tongDangKyDRL = 0;
        $tongDangKyCTXH = 0;
        $tongThamGiaDRL = 0;
        $tongThamGiaCTXH = 0;
        $trangThaiDRL = [];
        $trangThaiCTXH = [];
        $thamGiaDRL = 0;
        $vangDRL = 0;
        $chuaTongKetDRL = 0;
        $thamGiaCTXH = 0;
        $vangCTXH = 0;
        $chuaTongKetCTXH = 0;
        $totalStudents = 0;
        $completedStudents = 0;
        $tyLeHoanThanhCTXH = 0;

        // DRL Statistics
        if (empty($selectedType) || $selectedType == 'DRL') {
            $hdDrlQuery = HoatDongDrl::query();
            if ($selectedHocKy) {
                $hdDrlQuery->where('MaHocKy', $selectedHocKy);
            }
            $tongHoatDongDRL = $hdDrlQuery->count();
            $tongSlotsDRL = $hdDrlQuery->sum('SoLuong');

            $drlDangKysQuery = DB::table('dangkyhoatdongdrl')
                ->join('hoatdongdrl', 'dangkyhoatdongdrl.MaHoatDong', '=', 'hoatdongdrl.MaHoatDong');

            if ($selectedHocKy) {
                $drlDangKysQuery->where('hoatdongdrl.MaHocKy', $selectedHocKy);
            }
            // LỌC THEO MSSV NẾU CÓ CHỌN KHOA
            if ($selectedKhoa) {
                $drlDangKysQuery->whereIn('dangkyhoatdongdrl.MSSV', $mssvList);
            }


            $tongDangKyDRL = $drlDangKysQuery->count();

            $trangThaiDRL = [
                'Chờ duyệt' => (clone $drlDangKysQuery)->where('TrangThaiDangKy', 'Chờ duyệt')->count(),
                'Đã duyệt' => (clone $drlDangKysQuery)->where('TrangThaiDangKy', 'Đã duyệt')->count(),
                'Chờ thanh toán' => (clone $drlDangKysQuery)->where('TrangThaiDangKy', 'Chờ thanh toán')->count(),
                'Đã hủy' => (clone $drlDangKysQuery)->where('TrangThaiDangKy', 'Đã hủy')->count(),
            ];

            $thamGiaDRL = (clone $drlDangKysQuery)->where('TrangThaiThamGia', 'Đã tham gia')->count();
            $vangDRL = (clone $drlDangKysQuery)->where('TrangThaiThamGia', 'Vắng')->count();
            $chuaTongKetDRL = (clone $drlDangKysQuery)->where('TrangThaiThamGia', 'Chưa tổng kết')->count();
            $tongThamGiaDRL = $thamGiaDRL;
        }

        // CTXH Statistics
        if (empty($selectedType) || $selectedType == 'CTXH') {
            $hdCtxhQuery = HoatDongCtxh::query();
            $tongHoatDongCTXH = $hdCtxhQuery->count();
            $tongSlotsCTXH = $hdCtxhQuery->sum('SoLuong');

            $ctxhDangKysQuery = DB::table('dangkyhoatdongctxh')
                ->join('hoatdongctxh', 'dangkyhoatdongctxh.MaHoatDong', '=', 'hoatdongctxh.MaHoatDong');
                
            // LỌC THEO MSSV NẾU CÓ CHỌN KHOA
            if ($selectedKhoa) {
                $ctxhDangKysQuery->whereIn('dangkyhoatdongctxh.MSSV', $mssvList);
            }


            $tongDangKyCTXH = $ctxhDangKysQuery->count();

            $trangThaiCTXH = [
                'Chờ duyệt' => (clone $ctxhDangKysQuery)->where('TrangThaiDangKy', 'Chờ duyệt')->count(),
                'Đã duyệt' => (clone $ctxhDangKysQuery)->where('TrangThaiDangKy', 'Đã duyệt')->count(),
                'Chờ thanh toán' => (clone $ctxhDangKysQuery)->where('TrangThaiDangKy', 'Chờ thanh toán')->count(),
                'Đã hủy' => (clone $ctxhDangKysQuery)->where('TrangThaiDangKy', 'Đã hủy')->count(),
            ];

            $thamGiaCTXH = (clone $ctxhDangKysQuery)->where('TrangThaiThamGia', 'Đã tham gia')->count();
            $vangCTXH = (clone $ctxhDangKysQuery)->where('TrangThaiThamGia', 'Vắng')->count();
            $chuaTongKetCTXH = (clone $ctxhDangKysQuery)->where('TrangThaiThamGia', 'Chưa tổng kết')->count();
            $tongThamGiaCTXH = $thamGiaCTXH;

            // CTXH Completion Rate
            $targetCtxh = 50;
            // $sinhVienQuery đã được định nghĩa ở trên
            $totalStudents = $sinhVienQuery->count(); 
            
            if ($hasMssvFilter) {
                 $completedStudents = DiemCtxh::where('TongDiem', '>=', $targetCtxh)
                    ->whereIn('MSSV', $mssvList) // CHỈ LỌC NHỮNG SV CỦA KHOA ĐÃ HOÀN THÀNH
                    ->count();
            } else {
                 $completedStudents = DiemCtxh::where('TongDiem', '>=', $targetCtxh)->count();
            }
            
            $tyLeHoanThanhCTXH = $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100) : 0;
        }

        // Overall Summary
        $tongHoatDong = $tongHoatDongDRL + $tongHoatDongCTXH;
        $tongSlots = $tongSlotsDRL + $tongSlotsCTXH;
        $tongDangKy = $tongDangKyDRL + $tongDangKyCTXH;
        $tongThamGia = $tongThamGiaDRL + $tongThamGiaCTXH;
        $tyLeThamGia = $tongDangKy > 0 ? round(($tongThamGia / $tongDangKy) * 100) : 0;

        // Activity List with Pagination
        $activities = collect();
        if (empty($selectedType) || $selectedType == 'DRL') {
            $drlActivities = HoatDongDrl::where('MaHocKy', $selectedHocKy)
                ->withCount(['dangky', 'dangky as thamgia_count' => function ($q) use ($mssvList, $selectedKhoa) {
                    $q->where('TrangThaiThamGia', 'Đã tham gia');
                    // Áp dụng lọc MSSV vào count
                    if ($selectedKhoa) {
                         $q->whereIn('MSSV', $mssvList);
                    }
                }])
                ->get()
                ->map(function($item) {
                    $item->type = 'DRL';
                    return $item;
                });
            $activities = $activities->merge($drlActivities);
        }

        if (empty($selectedType) || $selectedType == 'CTXH') {
            $ctxhActivities = HoatDongCtxh::withCount(['dangky', 'dangky as thamgia_count' => function ($q) use ($mssvList, $selectedKhoa) {
                $q->where('TrangThaiThamGia', 'Đã tham gia');
                // Áp dụng lọc MSSV vào count
                if ($selectedKhoa) {
                    $q->whereIn('MSSV', $mssvList);
                }
            }])
                ->get()
                ->map(function($item) {
                    $item->type = 'CTXH';
                    return $item;
                });
            $activities = $activities->merge($ctxhActivities);
        }

        $sortedActivities = $activities->sortByDesc('ThoiGianBatDau');
        $perPageHoatDong = 10;
        $currentPageHoatDong = Paginator::resolveCurrentPage('page_hd') ?: 1;
        $currentPageItems = $sortedActivities->slice(($currentPageHoatDong - 1) * $perPageHoatDong, $perPageHoatDong);
        
        // SỬA LỖI: Truyền query parameters dưới dạng mảng vào constructor options
        $thongKeHoatDong = new LengthAwarePaginator(
            $currentPageItems,
            $sortedActivities->count(),
            $perPageHoatDong,
            $currentPageHoatDong,
            [
                'path' => Paginator::resolveCurrentPath(), 
                'pageName' => 'page_hd',
                // Truyền query() an toàn
                'query' => $request->query(), 
            ]
        );

        // Class Statistics with Pagination
        $lopQuery = Lop::with('khoa')->with('sinhvien')->withCount('sinhvien');
        if ($selectedKhoa) {
            $lopQuery->where('MaKhoa', $selectedKhoa);
        }
        $thongKeLop = $lopQuery->paginate(10, ['*'], 'page_lop');
        $thongKeLop->appends($request->query()); // Thêm appends cho paginator thứ 2
        
        $thongKeLop->getCollection()->transform(function($lop) use ($selectedHocKy) {
            if ($lop->sinhviens && $lop->sinhviens->count() > 0) {
                $mssvListLop = $lop->sinhviens->pluck('MSSV');
                $lop->avg_drl = DiemRenLuyen::whereIn('MSSV', $mssvListLop)
                    ->where('MaHocKy', $selectedHocKy)
                    ->avg('TongDiem');
                $lop->avg_ctxh = DiemCtxh::whereIn('MSSV', $mssvListLop)->avg('TongDiem');
            } else {
                $lop->avg_drl = 0;
                $lop->avg_ctxh = 0;
            }
            return $lop;
        });

            // --- Top 10 activities by participation ---
            $topActivities = $activities->sortByDesc('thamgia_count')->take(10)->values();

            // --- CTXH completion by Khoa ---
            $ctxhByKhoa = collect();
            if (empty($selectedType) || $selectedType == 'CTXH') {
                $khoaQuery = Khoa::orderBy('TenKhoa');
                if ($selectedKhoa) $khoaQuery->where('MaKhoa', $selectedKhoa);
                $khoasForCalc = $khoaQuery->get();
                foreach ($khoasForCalc as $khoa) {
                    // total students in khoa
                    $svQuery = SinhVien::whereHas('lop', function ($q) use ($khoa) {
                        $q->where('MaKhoa', $khoa->MaKhoa);
                    });
                    $total = $svQuery->count();
                    $mssvList = $svQuery->pluck('MSSV');
                    $completed = 0;
                    if ($mssvList->isNotEmpty()) {
                        $completed = DiemCtxh::whereIn('MSSV', $mssvList)->where('TongDiem', '>=', 50)->count();
                    }
                    $percent = $total > 0 ? round(($completed / $total) * 100) : 0;
                    $ctxhByKhoa->push([
                        'MaKhoa' => $khoa->MaKhoa,
                        'TenKhoa' => $khoa->TenKhoa,
                        'total' => $total,
                        'completed' => $completed,
                        'percent' => $percent,
                    ]);
                }
            }

        return view('nhanvien.thongke.index', compact(
            'hocKys', 'khoas', 'selectedHocKy', 'selectedKhoa', 'selectedType',
            'tongHoatDong', 'tongHoatDongDRL', 'tongHoatDongCTXH',
            'tongSlots', 'tongDangKy', 'tongThamGia', 'tyLeThamGia',
            'totalStudents', 'completedStudents', 'tyLeHoanThanhCTXH',
            'thongKeHoatDong', 'thongKeLop',
            'trangThaiDRL', 'trangThaiCTXH',
            'thamGiaDRL', 'vangDRL', 'chuaTongKetDRL',
            'thamGiaCTXH', 'vangCTXH', 'chuaTongKetCTXH',
            'topActivities', 'ctxhByKhoa'
        ));
    }
}