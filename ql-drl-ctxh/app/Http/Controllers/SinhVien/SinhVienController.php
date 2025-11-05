<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SinhVien; 
use App\Models\BangDiemHocKy; 
use App\Models\DiemRenLuyen; 
use App\Models\DiemCTXH; 

class SinhVienController extends Controller
{
    /**
     * Lấy MSSV của sinh viên đang đăng nhập.
     */
    private function getMssv()
    {
        // Giả định 'TenDangNhap' trong bảng 'taikhoan' chính là MSSV
        return Auth::user()->TenDangNhap; 
    }

    /**
     * 1. Hiển thị trang Thông tin sinh viên
     */
    public function showProfile()
    {
        $mssv = $this->getMssv();
        
        // Dùng 'with' để tải luôn thông tin Lớp và Khoa (tối ưu query)
        $student = SinhVien::with('lop.khoa')->find($mssv);

        if (!$student) {
            abort(404, 'Không tìm thấy thông tin sinh viên.');
        }

        return view('sinhvien.thongtin_sinhvien.profile_show', ['student' => $student]);
    }

    /**
     * 2. Hiển thị trang Chỉnh sửa thông tin
     */
    public function editProfile()
    {
        $mssv = $this->getMssv();
        $student = SinhVien::find($mssv);

        if (!$student) {
            abort(404, 'Không tìm thấy thông tin sinh viên.');
        }
        
        return view('sinhvien.thongtin_sinhvien.profile_edit', ['student' => $student]);
    }

    /**
     * 2. Cập nhật thông tin cá nhân
     */
    public function updateProfile(Request $request)
    {
        $mssv = $this->getMssv();
        $student = SinhVien::find($mssv);

        // Validate dữ liệu
        $validatedData = $request->validate([
            'Email' => 'required|email|max:100',
            'SDT' => 'nullable|string|max:15',
            'SoThich' => 'nullable|string|max:255',
        ]);

        $student->update($validatedData);

        return redirect()->route('sinhvien.profile.show')->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * 3. Hiển thị trang Thông tin học tập (Chỉ bảng điểm)
     */
    public function showAcademics()
    {
        $mssv = $this->getMssv();
        
        // Lấy bảng điểm học kỳ
        $diemHocKy = BangDiemHocKy::where('MSSV', $mssv)
                                 ->orderBy('MaHocKy', 'desc')
                                 ->get();
        
        // Trả về view với đường dẫn chính xác và chỉ truyền $diemHocKy
        return view('sinhvien.thongtin_sinhvien.academics_show', [
            'diemHocKy' => $diemHocKy,
        ]);
    }
    
    /**
     * 4. Hiển thị trang Đề xuất tốt nghiệp
     */
    public function showGraduation()
    {
        $mssv = $this->getMssv();
        $student = SinhVien::with('lop.khoa')->find($mssv); // Dùng with để lấy luôn thông tin lớp, khoa
        
        return view('sinhvien.thongtin_sinhvien.graduation_show', ['student' => $student]);
    }

    /**
     * 4. Cập nhật thời gian tốt nghiệp dự kiến (Hàm mới)
     */
    public function updateGraduation(Request $request)
    {
        $mssv = $this->getMssv();
        $student = SinhVien::find($mssv);

        // Validate dữ liệu
        // Cho phép 'nullable' (để trống) hoặc phải là định dạng date
        $validatedData = $request->validate([
            'ThoiGianTotNghiepDuKien' => 'nullable|date',
        ]);

        // Cập nhật CSDL
        $student->update([
            'ThoiGianTotNghiepDuKien' => $request->input('ThoiGianTotNghiepDuKien')
        ]);

        // Quay lại trang với thông báo
        return redirect()->route('sinhvien.graduation.show')
                         ->with('success', 'Đã cập nhật thời gian tốt nghiệp dự kiến!');
    }
}