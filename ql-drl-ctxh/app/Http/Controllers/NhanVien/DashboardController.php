<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Import tất cả các Models bạn đã cung cấp
use App\Models\SinhVien;
use App\Models\PhanHoiSinhVien;
use App\Models\HoatDongDrl;
use App\Models\HoatDongCtxh;
use App\Models\DangKyHoatDongDrl;
use App\Models\DangKyHoatDongCtxh;
use App\Models\DiemRenLuyen;
use App\Models\DiemCTXH;
use App\Models\KetQuaThamGiaCtxh;
use App\Models\KetQuaThamGiaDRL;

// Import các thư viện hỗ trợ
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang Bảng điều khiển (Dashboard) cho Nhân viên.
     * File view yêu cầu các biến:
     * - $tongSinhVien
     * - $tongHoatDongDRL
     * - $tongHoatDongCTXH
     * - $tongThongBao
     * - $thongBaoMoi (Collection/Array với TieuDe, created_at)
     * - $labelsDRL (Array)
     * - $dataDRL (Array)
     * - $labelsCTXH (Array)
     * - $dataCTXH (Array)
     */
    public function dashboard()
    {
        // --- 1. Truy vấn các thẻ KPI ---

        // KPI 1: Tổng sinh viên
        $tongSinhVien = SinhVien::count();

        // KPI 2: Tổng hoạt động DRL
        $tongHoatDongDRL = HoatDongDrl::count();

        // KPI 3: Tổng hoạt động CTXH
        $tongHoatDongCTXH = HoatDongCtxh::count();

        // KPI 4: Thông báo mới
        // Chúng ta sẽ sử dụng "Phản hồi chưa xử lý" cho mục "Thông báo"
        $tongThongBao = PhanHoiSinhVien::where('TrangThai', 'Chưa xử lý')->count();


        // --- 2. Truy vấn danh sách "Thông báo gần đây" ---
        // View yêu cầu biến $thongBaoMoi với 2 thuộc tính: TieuDe và created_at
        // Ta sẽ dùng AS để đổi tên cột cho khớp với view
        $thongBaoMoi = PhanHoiSinhVien::where('TrangThai', 'Chưa xử lý')
            ->orderBy('NgayGui', 'desc')
            ->take(5)
            ->select('NoiDung as TieuDe', 'NgayGui as created_at')
            ->get();


        // --- 3. Truy vấn dữ liệu Biểu đồ DRL ---
        // View yêu cầu: $labelsDRL, $dataDRL
        // CẬP NHẬT: Sửa lại logic theo yêu cầu: "giống như kết quả của CTXH"
        
        // Đếm số lượng sinh viên đã 'Hoàn thành' (Giả sử TrangThai='Hoàn thành')
        $hoanThanhDRL = KetQuaThamGiaDRL::where('TrangThai', 'Hoàn thành')->count();
        
        // Đếm tổng số lượt đăng ký DRL
        $tongDangKyDrl = DangKyHoatDongDrl::count();
        
        // Số chưa hoàn thành = Tổng - Đã hoàn thành
        $chuaHoanThanhDRL = $tongDangKyDrl - $hoanThanhDRL;

        // Cập nhật labels cho biểu đồ DRL (View sẽ dùng 2 nhãn này thay vì 5 nhãn mặc định)
        $labelsDRL = ['Hoàn thành', 'Chưa hoàn thành'];
        $dataDRL = [
            $hoanThanhDRL,
            $chuaHoanThanhDRL < 0 ? 0 : $chuaHoanThanhDRL // Đảm bảo không bị số âm
        ];


        // --- 4. Truy vấn dữ liệu Biểu đồ CTXH ---
        // View yêu cầu: $labelsCTXH, $dataCTXH ('Hoàn thành', 'Chưa hoàn thành')

        // Đếm số lượng sinh viên đã 'Hoàn thành' (Giả sử TrangThai='Hoàn thành')
        $hoanThanh = KetQuaThamGiaCtxh::where('TrangThai', 'Hoàn thành')->count();
        
        // Đếm tổng số lượt đăng ký CTXH
        $tongDangKyCtxh = DangKyHoatDongCtxh::count();
        
        // Số chưa hoàn thành = Tổng - Đã hoàn thành
        $chuaHoanThanh = $tongDangKyCtxh - $hoanThanh;

        // Các nhãn này được HARDCODE trong file dashboard.blade.php
        $labelsCTXH = ['Hoàn thành', 'Chưa hoàn thành'];
        $dataCTXH = [
            $hoanThanh,
            $chuaHoanThanh < 0 ? 0 : $chuaHoanThanh // Đảm bảo không bị số âm
        ];
        

        // --- 5. Trả về view với đầy đủ dữ liệu ---
        return view('nhanvien.dashboard', compact(
            'tongSinhVien',
            'tongHoatDongDRL',
            'tongHoatDongCTXH',
            'tongThongBao',
            'thongBaoMoi',
            'labelsDRL',
            'dataDRL',
            'labelsCTXH',
            'dataCTXH'
        ));
    }
}