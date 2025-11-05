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
use Illuminate\Support\Collection;


class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        // === BỘ LỌC ===
        $hocKys = HocKy::orderBy('NgayBatDau', 'desc')->get();
        $khoas = Khoa::orderBy('TenKhoa')->get();

        $selectedHocKy = $request->input('hoc_ky', $hocKys->first()->MaHocKy ?? null);
        $selectedKhoa = $request->input('khoa');
        $selectedType = $request->input('type'); // DRL, CTXH

        // === KHỞI TẠO BIẾN THỐNG KÊ ===
        $tongHoatDongDRL = 0;
        $tongHoatDongCTXH = 0;
        $tongSlotsDRL = 0;
        $tongSlotsCTXH = 0;
        $tongDangKyDRL = 0;
        $tongDangKyCTXH = 0;
        $tongThamGiaDRL = 0;
        $tongThamGiaCTXH = 0;
        $totalStudents = 0;
        $completedStudents = 0;
        $tyLeHoanThanhCTXH = 0;

        // === TÍNH TOÁN CÓ ĐIỀU KIỆN THEO 'TYPE' ===

        // 1. THỐNG KÊ DRL
        if (empty($selectedType) || $selectedType == 'DRL') {
            $hdDrlQuery = HoatDongDrl::query();
            if ($selectedHocKy) {
                $hdDrlQuery->where('MaHocKy', $selectedHocKy);
            }
            $tongHoatDongDRL = $hdDrlQuery->count();
            $tongSlotsDRL = $hdDrlQuery->sum('SoLuong');

            $drlDangKysQuery = DB::table('dangkyhoatdongdrl')
                ->join('hoatdongdrl', 'dangkyhoatdongdrl.MaHoatDong', '=', 'hoatdongdrl.MaHoatDong')
                ->where('hoatdongdrl.MaHocKy', $selectedHocKy);
            
            $tongDangKyDRL = $drlDangKysQuery->count();
            // Clone query để tránh lỗi, vì where() sẽ thay đổi query gốc
            $tongThamGiaDRL = (clone $drlDangKysQuery)->where('TrangThaiThamGia', 'Đã tham gia')->count();
        }

        // 2. THỐNG KÊ CTXH
        if (empty($selectedType) || $selectedType == 'CTXH') {
            $hdCtxhQuery = HoatDongCtxh::query(); // Không lọc theo học kỳ
            $tongHoatDongCTXH = $hdCtxhQuery->count();
            $tongSlotsCTXH = $hdCtxhQuery->sum('SoLuong');
            
            $ctxhDangKysQuery = DB::table('dangkyhoatdongctxh'); // Không lọc theo học kỳ
            
            $tongDangKyCTXH = $ctxhDangKysQuery->count();
            $tongThamGiaCTXH = (clone $ctxhDangKysQuery)->where('TrangThaiThamGia', 'Đã tham gia')->count();

            // Tính tỷ lệ hoàn thành CTXH
            if(!defined('TARGET_CTXH')) {
                define('TARGET_CTXH', 50);
            }
            $sinhVienQuery = SinhVien::query();
            if ($selectedKhoa) {
                $sinhVienQuery->whereHas('lop.khoa', function ($q) use ($selectedKhoa) {
                    $q->where('khoa.MaKhoa', $selectedKhoa);
                });
            }
            $totalStudents = $sinhVienQuery->count();
            $completedStudents = DiemCtxh::where('TongDiem', '>=', TARGET_CTXH)
                ->whereIn('MSSV', (clone $sinhVienQuery)->pluck('MSSV'))
                ->count();
            $tyLeHoanThanhCTXH = $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100) : 0;
        }

        // 3. TÍNH TỔNG HỢP
        $tongHoatDong = $tongHoatDongDRL + $tongHoatDongCTXH;
        $tongSlots = $tongSlotsDRL + $tongSlotsCTXH;
        $tongDangKy = $tongDangKyDRL + $tongDangKyCTXH;
        $tongThamGia = $tongThamGiaDRL + $tongThamGiaCTXH;
        $tyLeThamGia = $tongDangKy > 0 ? round(($tongThamGia / $tongDangKy) * 100) : 0;


        // === BẢNG: THỐNG KÊ HOẠT ĐỘNG ===
        // (Logic này đã đúng, giữ nguyên)
        $activities = collect();
        if (empty($selectedType) || $selectedType == 'DRL') {
            $drlActivities = HoatDongDrl::where('MaHocKy', $selectedHocKy)
                ->withCount(['dangky', 'dangky as thamgia_count' => function ($q) {
                    $q->where('TrangThaiThamGia', 'Đã tham gia');
                }])
                ->get()->map(function($item) { $item->type = 'DRL'; return $item; });
            $activities = $activities->merge($drlActivities);
        }
        if (empty($selectedType) || $selectedType == 'CTXH') {
             $ctxhActivities = HoatDongCtxh::withCount(['dangky', 'dangky as thamgia_count' => function ($q) {
                    $q->where('TrangThaiThamGia', 'Đã tham gia');
                }])
                ->get()->map(function($item) { $item->type = 'CTXH'; return $item; });
            $activities = $activities->merge($ctxhActivities);
        }
        
        $sortedActivities = $activities->sortByDesc('ThoiGianBatDau');
        $perPageHoatDong = 10;
        $currentPageHoatDong = Paginator::resolveCurrentPage('page_hd') ?: 1; 
        $currentPageItems = $sortedActivities->slice(($currentPageHoatDong - 1) * $perPageHoatDong, $perPageHoatDong);
        $thongKeHoatDong = new LengthAwarePaginator(
            $currentPageItems, $sortedActivities->count(), $perPageHoatDong, $currentPageHoatDong,
            ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page_hd'] 
        );
        $thongKeHoatDong->appends($request->query());


        // === BẢNG: THỐNG KÊ THEO LỚP ===
        // (Logic này đã đúng, giữ nguyên)
        $lopQuery = Lop::with('khoa')->with('sinhvien')->withCount('sinhvien');
        if ($selectedKhoa) {
            $lopQuery->where('MaKhoa', $selectedKhoa);
        }
        $thongKeLop = $lopQuery->paginate(10, ['*'], 'page_lop'); 
        $thongKeLop->getCollection()->transform(function($lop) use ($selectedHocKy) {
            if ($lop->sinhviens && $lop->sinhviens->count() > 0) {
                $mssvList = $lop->sinhviens->pluck('MSSV');
                $lop->avg_drl = DiemRenLuyen::whereIn('MSSV', $mssvList)
                                ->where('MaHocKy', $selectedHocKy)
                                ->avg('TongDiem');
                $lop->avg_ctxh = DiemCtxh::whereIn('MSSV', $mssvList)
                                ->avg('TongDiem');
            } else {
                $lop->avg_drl = 0; $lop->avg_ctxh = 0;
            }
            return $lop;
        });

        // === TRẢ VỀ VIEW ===
        return view('nhanvien.thongke.index', compact(
            'hocKys', 'khoas', 'selectedHocKy', 'selectedKhoa', 'selectedType',
            'tongHoatDong', 'tongHoatDongDRL', 'tongHoatDongCTXH',
            'tongSlots', 'tongDangKy', 'tongThamGia', 'tyLeThamGia',
            'totalStudents', 'completedStudents', 'tyLeHoanThanhCTXH',
            'thongKeHoatDong', 'thongKeLop'
        ));
    }
}