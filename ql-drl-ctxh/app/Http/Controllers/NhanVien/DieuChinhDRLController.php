<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Nhóm Models
use App\Models\DieuChinhDRL;
use App\Models\DiemRenLuyen;
use App\Models\SinhVien;
use App\Models\HocKy;
use App\Models\QuyDinhDiemRL;
use App\Models\NhanVien; 
// Nhóm Facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DieuChinhDRLController extends Controller
{
    /**
     * Hiển thị trang quản lý (Form thêm và Lịch sử)
     */
    public function index(Request $request)
    {
        $hocKys = HocKy::orderBy('NgayBatDau', 'desc')->get();
        $quyDinhs = QuyDinhDiemRL::orderBy('MaDiem')->get();

        $query = DieuChinhDRL::with('sinhvien', 'hocky', 'nhanvien', 'quydinh');

        if ($request->filled('search_mssv')) {
            $query->where('MSSV', 'like', '%' . $request->search_mssv . '%');
        }

        $dieuChinhs = $query->orderBy('NgayCapNhat', 'desc')->paginate(15);

        // Chú ý: View name phải là 'nhanvien.drl_dieuchinh.index'
        return view('nhanvien.dieuchinh_drl.index', compact('hocKys', 'quyDinhs', 'dieuChinhs'));
    }

    /**
     * Lưu điều chỉnh mới (Cộng/Trừ điểm)
     */
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'MSSV' => 'required|string|max:50',
            'MaHocKy' => 'required|string|exists:hocky,MaHocKy',
            'MaDiem' => 'required|string|exists:quydinhdiemrl,MaDiem', 
        ]);

        $mssv = $request->MSSV;
        $maHocKy = $request->MaHocKy;
        $maDiem = $request->MaDiem;

        // 2. Kiểm tra Sinh viên có tồn tại
        if (!SinhVien::where('MSSV', $mssv)->exists()) {
            return back()->with('error', 'MSSV không tồn tại trong hệ thống.')->withInput();
        }

        // === 3. FIX LỖI KHÓA NGOẠI (MaNV): Đảm bảo MaNV tồn tại ===
        $loginId = Auth::user()->TenDangNhap; // Lấy ID đăng nhập (ví dụ: NV001)

        $nv = NhanVien::where('MaNV', $loginId)->first();

        if (!$nv) {
             // Nếu tài khoản đang Auth không có trong bảng NhanVien
             Log::error("LỖI KHÓA NGOẠI: Tài khoản {$loginId} không có hồ sơ trong bảng NhanVien.");
             return back()->with('error', "Lỗi: Tài khoản Nhân viên ({$loginId}) chưa được tạo hồ sơ MaNV đầy đủ. Vui lòng liên hệ Admin.")->withInput();
        }
        $maNV = $nv->MaNV; 
        // ==========================================================


        // 4. Lấy điểm từ Quy định
        $quyDinh = QuyDinhDiemRL::find($maDiem);
        $soDiemDieuChinh = (int) $quyDinh->DiemNhan;
        $noiDung = $quyDinh->TenCongViec;

        // 5. Transaction
        try {
            DB::beginTransaction();

            // Tạo entry mới trong bảng lịch sử
            DieuChinhDRL::create([
                'MSSV' => $mssv,
                'MaHocKy' => $maHocKy,
                'MaNV' => $maNV, // <-- Đảm bảo MaNV hợp lệ
                'MaDiem' => $maDiem,
                'NgayCapNhat' => now(),
            ]);

            // Cập nhật bảng TỔNG KẾT (DiemRenLuyen)
            $diemRL = DiemRenLuyen::firstOrNew(
                ['MSSV' => $mssv, 'MaHocKy' => $maHocKy]
            );

            $diemRL->TongDiem = ($diemRL->TongDiem ?? 0) + $soDiemDieuChinh;
            $diemRL->XepLoai = $this->_getXepLoaiDRL($diemRL->TongDiem);
            $diemRL->NgayCapNhat = now();
            $diemRL->save(); // Dòng này đã được FIX nhờ sửa Model DiemRenLuyen trước đó

            DB::commit();

            return redirect()->route('dieuchinh_drl.index')->with('success', "Đã áp dụng '{$noiDung}' ({$soDiemDieuChinh}đ) cho SV {$mssv} (HK: {$maHocKy}).");

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('LỖI CẬP NHẬT ĐRL THỦ CÔNG (FINAL CATCH): ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi hệ thống khi lưu. Vui lòng kiểm tra Log chi tiết.');
        }
    }

    /**
     * Hàm helper để tính Xếp loại từ Tổng điểm
     */
    private function _getXepLoaiDRL($diem)
    {
        if ($diem >= 90) return 'Xuất Sắc';
        if ($diem >= 80) return 'Giỏi';
        if ($diem >= 65) return 'Khá';
        if ($diem >= 50) return 'Trung Bình';
        if ($diem >= 35) return 'Yếu';
        return 'Kém';
    }
}