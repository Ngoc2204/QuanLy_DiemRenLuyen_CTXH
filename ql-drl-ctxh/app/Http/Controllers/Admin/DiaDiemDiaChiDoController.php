<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiaDiemDiaChiDo;
use Illuminate\Http\Request;

class DiaDiemDiaChiDoController extends Controller
{
    /**
     * Hiển thị danh sách các địa điểm.
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy tất cả địa điểm, sắp xếp theo tên
        $diaDiems = DiaDiemDiaChiDo::orderBy('TenDiaDiem')->paginate(10);
        return view('admin.diadiem.index', compact('diaDiems'));
    }

    /**
     * Hiển thị form tạo mới địa điểm.
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.diadiem.create');
    }

    /**
     * Lưu địa điểm mới vào CSDL.
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'TenDiaDiem' => 'required|string|max:255|unique:diadiemdiachido,TenDiaDiem',
            'DiaChi' => 'nullable|string',
            'GiaTien' => 'required|numeric|min:0',
            'TrangThai' => 'required|in:KhaDung,TamNgung',
        ], [
            'TenDiaDiem.required' => 'Tên địa điểm là bắt buộc.',
            'TenDiaDiem.unique' => 'Tên địa điểm này đã tồn tại.',
            'GiaTien.required' => 'Giá tiền là bắt buộc.',
            'GiaTien.numeric' => 'Giá tiền phải là một con số.',
        ]);

        // Tạo mới
        DiaDiemDiaChiDo::create($request->all());

        return redirect()->route('admin.diadiem.index')
                         ->with('success', 'Thêm địa điểm mới thành công!');
    }

    /**
     * Hiển thị thông tin (chúng ta dùng trang edit luôn).
     * Display the specified resource.
     */
    public function show(DiaDiemDiaChiDo $diadiem)
    {
        // Chuyển hướng đến trang edit
        return redirect()->route('admin.diadiem.edit', $diadiem->id);
    }

    /**
     * Hiển thị form chỉnh sửa địa điểm.
     * Show the form for editing the specified resource.
     */
    public function edit(DiaDiemDiaChiDo $diadiem)
    {
        // Route model binding sẽ tự động tìm $diaDiem từ ID
        return view('admin.diadiem.edit', compact('diadiem'));
    }

    /**
     * Cập nhật địa điểm trong CSDL.
     * Update the specified resource in storage.
     */
    public function update(Request $request, DiaDiemDiaChiDo $diadiem)
    {
         // Validate dữ liệu
        $request->validate([
            // Bỏ qua unique check nếu tên không thay đổi
            'TenDiaDiem' => 'required|string|max:255|unique:diadiemdiachido,TenDiaDiem,' . $diadiem->id,
            'DiaChi' => 'nullable|string',
            'GiaTien' => 'required|numeric|min:0',
            'TrangThai' => 'required|in:KhaDung,TamNgung',
        ], [
            'TenDiaDiem.required' => 'Tên địa điểm là bắt buộc.',
            'TenDiaDiem.unique' => 'Tên địa điểm này đã tồn tại.',
            'GiaTien.required' => 'Giá tiền là bắt buộc.',
            'GiaTien.numeric' => 'Giá tiền phải là một con số.',
        ]);

        // Cập nhật
        $diadiem->update($request->all());

        return redirect()->route('admin.diadiem.index')
                         ->with('success', 'Cập nhật địa điểm thành công!');
    }

    /**
     * Xóa địa điểm (nên dùng Hủy thay vì Xóa).
     * Chúng ta sẽ dùng phương thức 'DELETE' để xử lý.
     * Remove the specified resource from storage.
     */
    public function destroy(DiaDiemDiaChiDo $diadiem)
    {
        try {
            // Cập nhật: Cách an toàn hơn là set 'TamNgung'
            // $diaDiem->update(['TrangThai' => 'TamNgung']);
            // return redirect()->route('admin.diadiem.index')
            //                  ->with('success', 'Đã chuyển địa điểm sang "Tạm ngưng".');

            // Hoặc xóa hẳn nếu bạn muốn
            $diadiem->delete();
            return redirect()->route('admin.diadiem.index')
                             ->with('success', 'Đã xóa địa điểm thành công.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Bắt lỗi nếu địa điểm này đã được sử dụng (có khóa ngoại)
            if ($e->getCode() == "23000") { // Integrity constraint violation
                return redirect()->back()
                                 ->with('error', 'Không thể xóa địa điểm này vì đã có hoạt động liên kết.');
            }
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}