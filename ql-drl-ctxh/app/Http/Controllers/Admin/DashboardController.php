<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Models\HoatDongCTXH;
use App\Models\HoatDongDRL;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $tongTaiKhoan = TaiKhoan::count();
        $tongSinhVien = TaiKhoan::where('VaiTro', 'SinhVien')->count();
        $tongGiangVien = TaiKhoan::where('VaiTro', 'GiangVien')->count();
        $tongNhanVien = TaiKhoan::where('VaiTro', 'NhanVien')->count();
        $tongCTXH = HoatDongCTXH::count();
        $tongDRL = HoatDongDRL::count();

        // Lấy dữ liệu thực theo tháng trong năm hiện tại
        $currentYear = date('Y');
        
        // DRL theo tháng
        $drlTheoThang = array_fill(1, 12, 0);
        $drlData = HoatDongDRL::selectRaw('MONTH(ThoiGianBatDau) as thang, COUNT(*) as count')
            ->whereYear('ThoiGianBatDau', $currentYear)
            ->groupBy('thang')
            ->get();
        
        foreach ($drlData as $item) {
            $drlTheoThang[$item->thang] = $item->count;
        }
        
        // CTXH theo tháng
        $ctxhTheoThang = array_fill(1, 12, 0);
        $ctxhData = HoatDongCTXH::selectRaw('MONTH(ThoiGianBatDau) as thang, COUNT(*) as count')
            ->whereYear('ThoiGianBatDau', $currentYear)
            ->groupBy('thang')
            ->get();
        
        foreach ($ctxhData as $item) {
            $ctxhTheoThang[$item->thang] = $item->count;
        }

        return view('admin.dashboard', compact(
            'tongTaiKhoan', 'tongSinhVien', 'tongGiangVien', 'tongNhanVien', 'tongCTXH', 'tongDRL',
            'drlTheoThang', 'ctxhTheoThang'
        ));
    }
}
