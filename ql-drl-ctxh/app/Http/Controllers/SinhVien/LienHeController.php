<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PhanHoiSinhVien;
use Carbon\Carbon;

class LienHeController extends Controller
{
    /**
     * Hiển thị form phản hồi của sinh viên
     */
    public function create()
    {
        // Lấy tài khoản đang đăng nhập
        $taiKhoan = Auth::user();

        // Kiểm tra nếu chưa đăng nhập hoặc chưa có thông tin sinh viên
        if (!$taiKhoan || !$taiKhoan->sinhvien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin sinh viên.');
        }

        $sinhvien = $taiKhoan->sinhvien;

        return view('sinhvien.lienhe.create', compact('sinhvien'));
    }

    /**
     * Lưu phản hồi mới vào database
     */
    public function store(Request $request)
    {
        // 1️⃣ Validate dữ liệu
        $request->validate([
            'LoaiPhanHoi' => 'required|string',
            'TieuDe'      => 'required|string|max:255',
            'NoiDung'     => 'required|string|min:30',
        ], [
            'LoaiPhanHoi.required' => 'Vui lòng chọn loại phản hồi.',
            'TieuDe.required'      => 'Vui lòng nhập tiêu đề.',
            'NoiDung.required'     => 'Vui lòng nhập nội dung chi tiết.',
            'NoiDung.min'          => 'Nội dung phản hồi cần ít nhất 30 ký tự.'
        ]);

        // 2️⃣ Lấy thông tin sinh viên
        $taiKhoan = Auth::user();
        $sinhvien = $taiKhoan->sinhvien;

        if (!$sinhvien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin sinh viên.');
        }

        // 3️⃣ Tạo mã phản hồi (nếu cột là INT, dùng timestamp)
        $maPhanHoi = time();

        // 4️⃣ Ghép nội dung đầy đủ
        $noiDungDayDu  = "[Phân loại: {$request->LoaiPhanHoi}]\n";
        $noiDungDayDu .= "TIÊU ĐỀ: {$request->TieuDe}\n\n";
        $noiDungDayDu .= "----------------------------------------\n";
        $noiDungDayDu .= $request->NoiDung;

        // 5️⃣ Lưu vào database
        PhanHoiSinhVien::create([
            'MaPhanHoi' => $maPhanHoi,
            'MSSV'      => $sinhvien->MSSV,
            'NoiDung'   => $noiDungDayDu,
            'NgayGui'   => Carbon::now(),
            'TrangThai' => 'Chưa xử lý', // ✅ khớp với giá trị bên nhân viên
        ]);

        // 6️⃣ Redirect kèm thông báo
        return redirect()->route('sinhvien.lienhe.create')
                         ->with('success', 'Gửi phản hồi thành công! Cảm ơn bạn đã đóng góp ý kiến.');
    }
}
