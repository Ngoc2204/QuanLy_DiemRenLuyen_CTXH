<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HocKy;   // Giả sử bạn đã tạo Model HocKy
use App\Models\NamHoc; // Cần dùng Model NamHoc để lấy danh sách

class HocKyController extends Controller
{
    /**
     * Hiển thị danh sách các học kỳ.
     */
    public function index(Request $request)
    {
        // Eager load "namhoc" relationship (giả sử đã định nghĩa trong Model HocKy)
        $query = HocKy::with('namhoc'); 

        // Tìm kiếm theo từ khóa
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('MaHocKy', 'like', "%{$keyword}%")
                  ->orWhere('TenHocKy', 'like', "%{$keyword}%");
        }

        // Lọc theo năm học
        if ($request->filled('MaNamHoc')) {
            $query->where('MaNamHoc', $request->MaNamHoc);
        }

        // Sắp xếp theo ngày bắt đầu, mới nhất lên trước
        $hockys = $query->orderBy('NgayBatDau', 'desc')->paginate(10);
        
        // Lấy danh sách năm học để lọc
        $namhocs = NamHoc::orderBy('NgayBatDau', 'desc')->get();
        $total = HocKy::count();

        return view('admin.hocky.index', compact('hockys', 'namhocs', 'total'));
    }

    /**
     * Hiển thị form tạo học kỳ mới.
     */
    public function create()
    {
        // Cần lấy danh sách năm học để chọn
        $namhocs = NamHoc::orderBy('NgayBatDau', 'desc')->get();
        return view('admin.hocky.create', compact('namhocs'));
    }

    /**
     * Lưu học kỳ mới vào database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'MaHocKy' => 'required|string|max:50|unique:hocky,MaHocKy',
            'TenHocKy' => 'required|string|max:255',
            'MaNamHoc' => 'required|integer|exists:namhoc,MaNamHoc',
            'NgayBatDau' => 'required|date',
            'NgayKetThuc' => 'required|date|after_or_equal:NgayBatDau',
        ]);

        HocKy::create($data);

        return redirect()->route('admin.hocky.index')
            ->with('success', 'Thêm học kỳ mới thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa học kỳ.
     * $id ở đây là MaHocKy (string, ví dụ: "HK1_2324")
     */
    public function edit($id)
    {
        $hocky = HocKy::findOrFail($id);
        $namhocs = NamHoc::orderBy('NgayBatDau', 'desc')->get();
        return view('admin.hocky.edit', compact('hocky', 'namhocs'));
    }

    /**
     * Cập nhật thông tin học kỳ.
     */
    public function update(Request $request, $id)
    {
        $hocky = HocKy::findOrFail($id);

        $data = $request->validate([
            // Không cho sửa MaHocKy (là Primary Key)
            'TenHocKy' => 'required|string|max:255',
            'MaNamHoc' => 'required|integer|exists:namhoc,MaNamHoc',
            'NgayBatDau' => 'required|date',
            'NgayKetThuc' => 'required|date|after_or_equal:NgayBatDau',
        ]);

        $hocky->update($data);

        return redirect()->route('admin.hocky.index')
            ->with('success', 'Cập nhật học kỳ thành công!');
    }

    /**
     * Xóa học kỳ.
     */
    public function destroy($id)
    {
        $hocky = HocKy::findOrFail($id);

        // Quan trọng: Kiểm tra xem học kỳ này có dữ liệu liên quan không
        // (VD: bangdiemhocky, diemrenluyen, hoatdongdrl,...)
        // Đây là ví dụ kiểm tra 1 bảng, bạn cần bổ sung các bảng khác
        
        // Giả sử Model HocKy có các relationship:
        // public function diemrenluyens() { return $this->hasMany(DiemRenLuyen::class, 'MaHocKy'); }
        // public function hoatdongdrls() { return $this->hasMany(HoatDongDRL::class, 'MaHocKy'); }
        // ...
        
        $hocky->loadCount(['diemrenluyens', 'hoatdongdrl', 'chucvusinhviens', 'bangdiemhockys']);
        
        $count = $hocky->diemrenluyens_count + 
                 $hocky->hoatdongdrls_count + 
                 $hocky->chucvusinhviens_count + 
                 $hocky->bangdiemhockys_count;

        if ($count > 0) {
            return redirect()->route('admin.hocky.index')
                ->with('error', 'Không thể xóa học kỳ này. Đã có dữ liệu (điểm rèn luyện, hoạt động,...) liên quan.');
        }

        try {
            $hocky->delete();
            return redirect()->route('admin.hocky.index')
                ->with('success', 'Đã xóa học kỳ thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.hocky.index')
                ->with('error', 'Xóa thất bại! Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}
