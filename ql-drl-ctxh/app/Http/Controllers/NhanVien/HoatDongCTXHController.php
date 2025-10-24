<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use App\Models\HoatDongCtxh;
use App\Models\QuyDinhDiemCtxh; // Import Model Quy định điểm
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Hỗ trợ tạo Mã hoạt động

class HoatDongCTXHController extends Controller
{
    /**
     * Hiển thị danh sách các Hoạt động CTXH.
     */
    public function index(Request $request)
    {
        $query = HoatDongCtxh::withCount('dangKy')->orderBy('ThoiGianBatDau', 'desc'); // Lấy số lượng đăng ký

        // Tìm kiếm (ví dụ theo Tên hoạt động)
        if ($request->has('search') && $request->search != '') {
            $query->where('TenHoatDong', 'like', '%' . $request->search . '%');
        }

        $hoatDongs = $query->paginate(10); // Phân trang 10 mục/trang

        return view('nhanvien.hoatdong_ctxh.index', compact('hoatDongs'));
    }

    /**
     * Hiển thị form tạo mới Hoạt động CTXH.
     */
    public function create()
    {
        // Lấy danh sách Quy định điểm để hiển thị trong dropdown
        $quyDinhDiems = QuyDinhDiemCtxh::orderBy('TenCongViec')->pluck('TenCongViec', 'MaDiem');
        return view('nhanvien.hoatdong_ctxh.create', compact('quyDinhDiems'));
    }

    /**
     * Lưu Hoạt động CTXH mới vào database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'TenHoatDong' => 'required|string|max:255',
            'MoTa' => 'nullable|string',
            'ThoiGianBatDau' => 'required|date|after_or_equal:now',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'ThoiHanHuy' => 'nullable|date|before:ThoiGianBatDau',
            'DiaDiem' => 'nullable|string|max:255',
            'Diem' => 'required|integer|min:0',
            'SoLuong' => 'required|integer|min:1',
            'LoaiHoatDong' => 'required|string|max:100',
            'MaQuyDinhDiem' => 'required|exists:quydinhdiemctxh,MaDiem',
        ]);

        // Tạo Mã Hoạt động duy nhất (ví dụ: CTXH-YYYYMMDD-XXXX)
        $maHoatDong = 'CTXH-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        // Kiểm tra mã tồn tại (hiếm khi xảy ra)
        while (HoatDongCtxh::where('MaHoatDong', $maHoatDong)->exists()) {
             $maHoatDong = 'CTXH-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        }
        $validatedData['MaHoatDong'] = $maHoatDong;


        try {
             HoatDongCtxh::create($validatedData);
             return redirect()->route('nhanvien.hoatdong_ctxh.index')
                             ->with('success', 'Tạo hoạt động CTXH thành công.');
        } catch (\Exception $e) {
             return redirect()->back()
                             ->with('error', 'Tạo hoạt động thất bại. Lỗi: ' . $e->getMessage())
                             ->withInput();
        }

    }

    /**
     * Hiển thị chi tiết một Hoạt động CTXH.
     */
    public function show(HoatDongCtxh $hoatdong_ctxh) // Route Model Binding
    {
        // Tải danh sách sinh viên đã đăng ký
        $hoatdong_ctxh->load('sinhVienDangKy');
        return view('nhanvien.hoatdong_ctxh.show', compact('hoatdong_ctxh'));
    }

    /**
     * Hiển thị form chỉnh sửa Hoạt động CTXH.
     */
    public function edit(HoatDongCtxh $hoatdong_ctxh) // Route Model Binding
    {
        $quyDinhDiems = QuyDinhDiemCtxh::orderBy('TenCongViec')->pluck('TenCongViec', 'MaDiem');
        return view('nhanvien.hoatdong_ctxh.edit', compact('hoatdong_ctxh', 'quyDinhDiems'));
    }

    /**
     * Cập nhật Hoạt động CTXH trong database.
     */
    public function update(Request $request, HoatDongCtxh $hoatdong_ctxh) // Route Model Binding
    {
         $validatedData = $request->validate([
            'TenHoatDong' => 'required|string|max:255',
            'MoTa' => 'nullable|string',
            'ThoiGianBatDau' => 'required|date', // Có thể không cần after:now khi sửa
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'ThoiHanHuy' => 'nullable|date|before:ThoiGianBatDau',
            'DiaDiem' => 'nullable|string|max:255',
            'Diem' => 'required|integer|min:0',
            'SoLuong' => 'required|integer|min:1',
            'LoaiHoatDong' => 'required|string|max:100',
            'MaQuyDinhDiem' => 'required|exists:quydinhdiemctxh,MaDiem',
        ]);

         try {
             $hoatdong_ctxh->update($validatedData);
             return redirect()->route('nhanvien.hoatdong_ctxh.index')
                             ->with('success', 'Cập nhật hoạt động CTXH thành công.');
        } catch (\Exception $e) {
             return redirect()->back()
                             ->with('error', 'Cập nhật hoạt động thất bại. Lỗi: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Xóa Hoạt động CTXH khỏi database.
     */
    public function destroy(HoatDongCtxh $hoatdong_ctxh) // Route Model Binding
    {
        try {
            // ---- THÊM ĐOẠN KIỂM TRA NÀY ----
            // Kiểm tra xem có sinh viên nào đã đăng ký hoạt động này chưa
            if ($hoatdong_ctxh->dangKy()->exists()) { // Sử dụng exists() cho hiệu quả
                return redirect()->route('nhanvien.hoatdong_ctxh.index')
                                ->with('error', 'Không thể xóa hoạt động "' . $hoatdong_ctxh->TenHoatDong . '" vì đã có sinh viên đăng ký.');
            }
            // ---- KẾT THÚC ĐOẠN KIỂM TRA ----

            $hoatdong_ctxh->delete();
            return redirect()->route('nhanvien.hoatdong_ctxh.index')
                            ->with('success', 'Xóa hoạt động CTXH thành công.');

        } catch (\Illuminate\Database\QueryException $e) {
             // Bắt lỗi QueryException cụ thể hơn (bao gồm lỗi khóa ngoại)
             // Ghi log lỗi để debug nếu cần: Log::error($e->getMessage());
            return redirect()->route('nhanvien.hoatdong_ctxh.index')
                            ->with('error', 'Không thể xóa hoạt động này do có lỗi cơ sở dữ liệu hoặc ràng buộc dữ liệu.');
        }
         catch (\Exception $e) { // Bắt các lỗi chung khác
            // Ghi log lỗi để debug nếu cần: Log::error($e->getMessage());
            return redirect()->route('nhanvien.hoatdong_ctxh.index')
                            ->with('error', 'Đã xảy ra lỗi không mong muốn khi xóa.');
        }
    }
}
