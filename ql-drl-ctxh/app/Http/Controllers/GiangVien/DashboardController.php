<?php

namespace App\Http\Controllers\GiangVien; // Đảm bảo đúng namespace

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GiangVien;
use App\Models\CoVanHT;
use App\Models\Lop;
use App\Models\SinhVien;
use App\Models\DiemRenLuyen;
use App\Models\DiemCtxh;
use App\Models\HoatDongDrl;
use App\Models\HocKy; // Giả sử bạn có model HocKy

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // 1. Lấy thông tin Giảng viên (và Mã GV)
        $user = Auth::user(); // Giả sử user đã login là tài khoản
        $maGV = $user->TenDangNhap; // Giả sử TenDangNhap là MaGV

        // 2. Lấy Lớp Cố vấn
        $maLops = CoVanHT::where('MaGiangVien', $maGV)->pluck('MaLop');
        // Giả sử 1 Giảng viên chỉ cố vấn 1 lớp
        $lopCoVanList = Lop::with('khoa')->whereIn('MaLop', $maLops)->get();
        $soLopCoVan = $lopCoVanList->count();
        
        $siSo = 0;
        $avgDrl = 0;
        $avgCtxh = 0;

        if ($soLopCoVan > 0) {
            // 3. Lấy *tất cả* sinh viên từ *tất cả* các lớp
            $allMSSV = SinhVien::whereIn('MaLop', $maLops)->pluck('MSSV');
            $siSo = $allMSSV->count();
            
            if($siSo > 0) {
                 // 4. Lấy Học kỳ hiện tại (Logic ví dụ)
                $hocKyHienTai = HocKy::where('NgayBatDau', '<=', now())
                                    ->where('NgayKetThuc', '>=', now())
                                    ->first();
                
                // 5. Tính DRL Trung bình (của tất cả SV)
                if($hocKyHienTai) {
                    $avgDrl = DiemRenLuyen::whereIn('MSSV', $allMSSV)
                                        ->where('MaHocKy', $hocKyHienTai->MaHocKy)
                                        ->avg('TongDiem');
                }

                // 6. Tính CTXH Trung bình (của tất cả SV)
                $avgCtxh = DiemCtxh::whereIn('MSSV', $allMSSV)->avg('TongDiem');
            }
        }
        
        // 7. Lấy các Hoạt động DRL Giảng viên phụ trách
        $hoatDongsPhuTrach = HoatDongDrl::where('MaGV', $maGV)
                                    ->with('hocKy')
                                    ->orderBy('ThoiGianBatDau', 'desc')
                                    ->get();

        // 8. Trả về View
        return view('giangvien.dashboard', [
            'lopCoVanList' => $lopCoVanList, // Gửi danh sách các lớp
            'soLopCoVan' => $soLopCoVan,   // Gửi số lượng lớp
            'siSo' => $siSo,
            'avgDrl' => $avgDrl,
            'avgCtxh' => $avgCtxh,
            'hoatDongsPhuTrach' => $hoatDongsPhuTrach
        ]);
    }
}