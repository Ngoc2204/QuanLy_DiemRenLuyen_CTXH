<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DangKyHoatDongDrl;
use App\Models\DangKyHoatDongCtxh;
use App\Models\SinhVien;
use App\Models\HoatDongDRL;
use App\Models\HoatDongCTXH;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThongKeController extends Controller
{
    /**
     * Thống kê điểm DRL
     */
    public function drl()
    {
        // Tổng số sinh viên
        $tongSinhVien = SinhVien::count();
        
        // Lấy năm học hiện tại
        $currentYear = date('Y');
        
        // Thống kê hoạt động DRL theo tháng
        $hoatDongByMonth = HoatDongDRL::selectRaw('MONTH(ThoiGianBatDau) as thang, COUNT(*) as count')
            ->whereYear('ThoiGianBatDau', $currentYear)
            ->groupBy('thang')
            ->get();
        
        $drlByMonth = array_fill(1, 12, 0);
        foreach ($hoatDongByMonth as $item) {
            $drlByMonth[$item->thang] = $item->count;
        }
        
        // Thống kê số lần tham gia hoạt động DRL theo lớp
        $diemTrungBinhByLop = DB::table('dangkyhoatdongdrl')
            ->join('sinhvien', 'dangkyhoatdongdrl.MSSV', '=', 'sinhvien.MSSV')
            ->join('lop', 'sinhvien.MaLop', '=', 'lop.MaLop')
            ->selectRaw('lop.TenLop, COUNT(DISTINCT dangkyhoatdongdrl.MSSV) as sinhVien, COUNT(dangkyhoatdongdrl.MaDangKy) as soLanThamGia')
            ->groupBy('lop.MaLop', 'lop.TenLop')
            ->orderByDesc('soLanThamGia')
            ->get();
        
        // Sinh viên chưa có hoạt động DRL
        $sinhVienChuaCoHoatDong = SinhVien::leftJoin('dangkyhoatdongdrl', 'sinhvien.MSSV', '=', 'dangkyhoatdongdrl.MSSV')
            ->whereNull('dangkyhoatdongdrl.MaDangKy')
            ->distinct()
            ->count('sinhvien.MSSV');
        
        // Số hoạt động DRL
        $tongHoatDong = HoatDongDRL::count();
        
        // Thống kê theo khoảng lần tham gia (0-3, 3-6, 6-10, 10+)
        $diemRanges = [
            '0-3' => 0,
            '3-6' => 0,
            '6-10' => 0,
            '10+' => 0,
        ];
        
        $participationCounts = DB::table('dangkyhoatdongdrl')
            ->selectRaw('MSSV, COUNT(*) as count')
            ->groupBy('MSSV')
            ->get();
        
        foreach ($participationCounts as $item) {
            if ($item->count < 3) {
                $diemRanges['0-3']++;
            } elseif ($item->count < 6) {
                $diemRanges['3-6']++;
            } elseif ($item->count < 10) {
                $diemRanges['6-10']++;
            } else {
                $diemRanges['10+']++;
            }
        }
        
        // Tổng lần tham gia
        $tongDiem = DangKyHoatDongDrl::count();
        
        return view('admin.thongke.drl', compact(
            'tongSinhVien',
            'sinhVienChuaCoHoatDong',
            'tongHoatDong',
            'tongDiem',
            'diemRanges',
            'drlByMonth',
            'diemTrungBinhByLop'
        ));
    }
    
    /**
     * Thống kê điểm CTXH
     */
    public function ctxh()
    {
        // Tổng số sinh viên
        $tongSinhVien = SinhVien::count();
        
        // Lấy năm học hiện tại
        $currentYear = date('Y');
        
        // Thống kê hoạt động CTXH theo tháng
        $hoatDongByMonth = HoatDongCTXH::selectRaw('MONTH(ThoiGianBatDau) as thang, COUNT(*) as count')
            ->whereYear('ThoiGianBatDau', $currentYear)
            ->groupBy('thang')
            ->get();
        
        $ctxhByMonth = array_fill(1, 12, 0);
        foreach ($hoatDongByMonth as $item) {
            $ctxhByMonth[$item->thang] = $item->count;
        }
        
        // Thống kê số lần tham gia hoạt động CTXH theo lớp
        $diemTrungBinhByLop = DB::table('dangkyhoatdongctxh')
            ->join('sinhvien', 'dangkyhoatdongctxh.MSSV', '=', 'sinhvien.MSSV')
            ->join('lop', 'sinhvien.MaLop', '=', 'lop.MaLop')
            ->selectRaw('lop.TenLop, COUNT(DISTINCT dangkyhoatdongctxh.MSSV) as sinhVien, COUNT(dangkyhoatdongctxh.MaDangKy) as soLanThamGia')
            ->groupBy('lop.MaLop', 'lop.TenLop')
            ->orderByDesc('soLanThamGia')
            ->get();
        
        // Sinh viên chưa có hoạt động CTXH
        $sinhVienChuaCoHoatDong = SinhVien::leftJoin('dangkyhoatdongctxh', 'sinhvien.MSSV', '=', 'dangkyhoatdongctxh.MSSV')
            ->whereNull('dangkyhoatdongctxh.MaDangKy')
            ->distinct()
            ->count('sinhvien.MSSV');
        
        // Số hoạt động CTXH
        $tongHoatDong = HoatDongCTXH::count();
        
        // Thống kê theo khoảng lần tham gia (0-3, 3-6, 6-10, 10+)
        $diemRanges = [
            '0-3' => 0,
            '3-6' => 0,
            '6-10' => 0,
            '10+' => 0,
        ];
        
        $participationCounts = DB::table('dangkyhoatdongctxh')
            ->selectRaw('MSSV, COUNT(*) as count')
            ->groupBy('MSSV')
            ->get();
        
        foreach ($participationCounts as $item) {
            if ($item->count < 3) {
                $diemRanges['0-3']++;
            } elseif ($item->count < 6) {
                $diemRanges['3-6']++;
            } elseif ($item->count < 10) {
                $diemRanges['6-10']++;
            } else {
                $diemRanges['10+']++;
            }
        }
        
        // Tổng lần tham gia
        $tongDiem = DangKyHoatDongCtxh::count();
        
        return view('admin.thongke.ctxh', compact(
            'tongSinhVien',
            'sinhVienChuaCoHoatDong',
            'tongHoatDong',
            'tongDiem',
            'diemRanges',
            'ctxhByMonth',
            'diemTrungBinhByLop'
        ));
    }
}
