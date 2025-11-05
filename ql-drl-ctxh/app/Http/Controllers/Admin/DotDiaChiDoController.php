<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DotDiaChiDo;
use Illuminate\Http\Request;

class DotDiaChiDoController extends Controller
{
    /**
     * Hiển thị danh sách các đợt.
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy các đợt, sắp xếp mới nhất lên đầu
        $dots = DotDiaChiDo::orderBy('NgayBatDau', 'desc')->paginate(10);
        return view('admin.dotdiachido.index', compact('dots'));
    }

    /**
     * Hiển thị form tạo đợt mới.
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dotdiachido.create');
    }

    /**
     * Lưu đợt mới vào CSDL.
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'TenDot' => 'required|string|max:255|unique:dot_dia_chi_do,TenDot',
            'NgayBatDau' => 'required|date',
            'NgayKetThuc' => 'required|date|after_or_equal:NgayBatDau',
            'TrangThai' => 'required|in:SapDienRa,DangDienRa,DaKetThuc',
        ], [
            'TenDot.required' => 'Tên đợt là bắt buộc.',
            'TenDot.unique' => 'Tên đợt này đã tồn tại.',
            'NgayBatDau.required' => 'Ngày bắt đầu là bắt buộc.',
            'NgayKetThuc.required' => 'Ngày kết thúc là bắt buộc.',
            'NgayKetThuc.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ]);

        // Tạo mới
        DotDiaChiDo::create($request->all());

        return redirect()->route('admin.dotdiachido.index')
                         ->with('success', 'Tạo đợt mới thành công!');
    }

    /**
     * Hiển thị thông tin (chúng ta dùng trang edit).
     * Display the specified resource.
     */
    public function show(DotDiaChiDo $dot)
    {
        // Chuyển hướng đến trang edit
        return redirect()->route('admin.dotdiachido.edit', $dot->id);
    }

    /**
     * Hiển thị form chỉnh sửa đợt.
     * Show the form for editing the specified resource.
     */
    public function edit(DotDiaChiDo $dotdiachido)
    {
        // Route model binding sẽ tự động tìm $dot từ ID
        return view('admin.dotdiachido.edit', compact('dotdiachido'));
    }

    /**
     * Cập nhật đợt trong CSDL.
     * Update the specified resource in storage.
     */
    public function update(Request $request, DotDiaChiDo $dotdiachido)
    {
        // Validate dữ liệu
        $request->validate([
            'TenDot' => 'required|string|max:255|unique:dot_dia_chi_do,TenDot,' . $dotdiachido->id,
            'NgayBatDau' => 'required|date',
            'NgayKetThuc' => 'required|date|after_or_equal:NgayBatDau',
            'TrangThai' => 'required|in:SapDienRa,DangDienRa,DaKetThuc',
        ], [
            'TenDot.required' => 'Tên đợt là bắt buộc.',
            'TenDot.unique' => 'Tên đợt này đã tồn tại.',
            'NgayBatDau.required' => 'Ngày bắt đầu là bắt buộc.',
            'NgayKetThuc.required' => 'Ngày kết thúc là bắt buộc.',
            'NgayKetThuc.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ]);

        // Cập nhật
        $dotdiachido->update($request->all());

        return redirect()->route('admin.dotdiachido.index')
                         ->with('success', 'Cập nhật đợt thành công!');
    }

    /**
     * Xóa đợt.
     * Remove the specified resource from storage.
     */
    public function destroy(DotDiaChiDo $dotdiachido)
    {
         try {
            // Xóa đợt
            $dotdiachido->delete();
            return redirect()->route('admin.dotdiachido.index')
                             ->with('success', 'Đã xóa đợt thành công.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Bắt lỗi nếu đợt này đã được sử dụng (có khóa ngoại từ 'hoatdongctxh')
            if ($e->getCode() == "23000") { // Integrity constraint violation
                return redirect()->back()
                                 ->with('error', 'Không thể xóa đợt này vì đã có suất hoạt động liên kết.');
            }
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}