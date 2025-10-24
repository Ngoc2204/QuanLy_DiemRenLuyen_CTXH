<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use App\Models\PhanHoiSinhVien; // Sử dụng Model PhanHoiSinhVien
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThongBaoController extends Controller // Giữ nguyên tên file Controller theo yêu cầu của bạn
{
    /**
     * Hiển thị danh sách Phản hồi của Sinh viên.
     */
    public function index(Request $request)
    {
        // Xây dựng query cơ bản, lấy kèm thông tin sinh viên
        $query = PhanHoiSinhVien::with('sinhvien')->orderBy('NgayGui', 'desc');

        // Lọc theo trạng thái nếu có yêu cầu
        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('TrangThai', $request->trang_thai);
        }

        // Lấy dữ liệu phân trang
        $phanHois = $query->paginate(15); // Hiển thị 15 phản hồi mỗi trang

        return view('nhanvien.thongbao.index', compact('phanHois'));
    }

    /**
     * Show the form for creating a new resource.
     * Nhân viên không tạo phản hồi, nên hàm này có thể bỏ trống hoặc redirect.
     */
    public function create()
    {
        // return view('nhanvien.thongbao.create'); // Bỏ qua nếu không cần form tạo
        return redirect()->route('nhanvien.thongbao.index')->with('info', 'Chức năng tạo phản hồi dành cho sinh viên.');
    }

    /**
     * Store a newly created resource in storage.
     * Nhân viên không tạo phản hồi, nên hàm này không cần thiết.
     */
    public function store(Request $request)
    {
        // Bỏ qua
        return redirect()->route('nhanvien.thongbao.index');
    }

    /**
     * Hiển thị chi tiết một Phản hồi.
     * Sử dụng Route Model Binding để tự động tìm PhanHoiSinhVien.
     */
    public function show(PhanHoiSinhVien $thongbao)
    {
        // Tải thông tin sinh viên liên quan nếu chưa có
        $thongbao->loadMissing('sinhvien'); 
        return view('nhanvien.thongbao.show', compact('thongbao'));
    }


    /**
     * Hiển thị form để chỉnh sửa/cập nhật trạng thái Phản hồi.
     * Sử dụng Route Model Binding.
     */
    public function edit(PhanHoiSinhVien $thongbao)
    {
         $thongbao->loadMissing('sinhvien');
        return view('nhanvien.thongbao.edit', compact('thongbao'));
    }

    /**
     * Cập nhật trạng thái của Phản hồi trong database.
     */
    public function update(Request $request, PhanHoiSinhVien $thongbao)
    {
        $request->validate([
            // Chỉ cần validate trạng thái
            'TrangThai' => 'required|in:Chưa xử lý,Đang xử lý,Đã phản hồi',
            // Thêm trường 'GhiChuPhanHoi' nếu bạn muốn nhân viên ghi chú lại
            // 'GhiChuPhanHoi' => 'nullable|string' 
        ]);

        // Cập nhật trạng thái
        $thongbao->TrangThai = $request->TrangThai;
        
        // Cập nhật ghi chú (nếu có)
        // if ($request->filled('GhiChuPhanHoi')) {
        //     $thongbao->GhiChuPhanHoi = $request->GhiChuPhanHoi; 
        // }

        $thongbao->save();

        return redirect()->route('nhanvien.thongbao.index')
                         ->with('success', 'Cập nhật trạng thái phản hồi thành công.');
    }

    /**
     * Xóa Phản hồi khỏi database (Cân nhắc kỹ có nên xóa hẳn không).
     */
    public function destroy(PhanHoiSinhVien $thongbao)
    {
        try {
            $thongbao->delete();
            return redirect()->route('nhanvien.thongbao.index')
                             ->with('success', 'Xóa phản hồi thành công.');
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có, ví dụ: khóa ngoại
            return redirect()->route('nhanvien.thongbao.index')
                             ->with('error', 'Không thể xóa phản hồi này. Lỗi: ' . $e->getMessage());
        }
    }
}