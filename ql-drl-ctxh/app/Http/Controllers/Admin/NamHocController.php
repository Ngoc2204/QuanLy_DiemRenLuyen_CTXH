<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NamHoc; // Giả sử bạn đã tạo Model NamHoc
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class NamHocController extends Controller
{
    /**
     * Hiển thị danh sách các năm học.
     */
    public function index(Request $request)
    {
        $query = NamHoc::query();

        // Tìm kiếm theo từ khóa
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('TenNamHoc', 'like', "%{$keyword}%");
        }

        // Sắp xếp theo ngày bắt đầu, mới nhất lên trước
        $namhocs = $query->orderBy('NgayBatDau', 'desc')->paginate(10);
        $total = NamHoc::count();

        return view('admin.namhoc.index', compact('namhocs', 'total'));
    }

    /**
     * Hiển thị form tạo năm học mới.
     */
    public function create()
    {
        return view('admin.namhoc.create');
    }

    /**
     * Lưu năm học mới vào database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'TenNamHoc' => 'required|string|max:255|unique:namhoc,TenNamHoc',
            'NgayBatDau' => 'required|date',
            'NgayKetThuc' => 'required|date|after_or_equal:NgayBatDau',
        ]);

        NamHoc::create($data);

        return redirect()->route('admin.namhoc.index')
            ->with('success', 'Thêm năm học mới thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa năm học.
     * $id ở đây là MaNamHoc (int, auto-increment)
     */
    public function edit($id)
    {
        $namhoc = NamHoc::findOrFail($id);
        return view('admin.namhoc.edit', compact('namhoc'));
    }

    /**
     * Cập nhật thông tin năm học.
     */
    public function update(Request $request, $id)
    {
        $namhoc = NamHoc::findOrFail($id);

        $data = $request->validate([
            'TenNamHoc' => [
                'required',
                'string',
                'max:255',
                Rule::unique('namhoc')->ignore($id, 'MaNamHoc') // Bỏ qua chính nó khi check unique
            ],
            'NgayBatDau' => 'required|date',
            'NgayKetThuc' => 'required|date|after_or_equal:NgayBatDau',
        ]);

        $namhoc->update($data);

        return redirect()->route('admin.namhoc.index')
            ->with('success', 'Cập nhật năm học thành công!');
    }

    /**
     * Xóa năm học.
     */
    public function destroy($id)
    {
        // Quan trọng: Kiểm tra xem năm học này có học kỳ nào đang sử dụng không
        // Giả sử Model NamHoc có relationship: public function hockys() { return $this->hasMany(HocKy::class, 'MaNamHoc'); }
        $namhoc = NamHoc::withCount('hockys')->findOrFail($id);

        if ($namhoc->hockys_count > 0) {
            return redirect()->route('admin.namhoc.index')
                ->with('error', 'Không thể xóa năm học này. Vẫn còn ' . $namhoc->hockys_count . ' học kỳ liên quan.');
        }

        try {
            $namhoc->delete();
            return redirect()->route('admin.namhoc.index')
                ->with('success', 'Đã xóa năm học thành công!');
        } catch (\Exception $e) {
            // Bắt các lỗi ràng buộc khóa ngoại khác nếu có
            return redirect()->route('admin.namhoc.index')
                ->with('error', 'Xóa thất bại! Đã xảy ra lỗi.');
        }
    }
}
