<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Models\HoatDongCTXH;
use App\Models\HoatDongDRL;

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

        return view('admin.dashboard', compact(
            'tongTaiKhoan', 'tongSinhVien', 'tongGiangVien', 'tongNhanVien', 'tongCTXH', 'tongDRL'
        ));
    }
}
