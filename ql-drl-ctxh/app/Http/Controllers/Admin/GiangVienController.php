<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\GiangVien;
use App\Models\Lop;

class GiangVienController extends Controller
{
    /**
     * Danh sách giảng viên
     */
    public function index(Request $request)
    {
        $query = GiangVien::with('lopPhuTrach');

        // 🔍 Tìm kiếm theo mã hoặc tên giảng viên
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('MaGV', 'like', "%{$keyword}%")
                    ->orWhere('TenGV', 'like', "%{$keyword}%");
            });
        }

        // 🔍 Lọc theo lớp cố vấn
        if ($request->filled('MaLop')) {
            $query->whereHas('lopPhuTrach', function ($q) use ($request) {
                $q->where('lop.MaLop', $request->MaLop);
            });
        }

        $giangviens = $query->paginate(15);
        $lops = Lop::orderBy('TenLop')->get();

        // 📊 Thống kê
        $totalGiangVien = GiangVien::count();
        $totalLopCoVan = DB::table('covanht')->distinct('MaLop')->count('MaLop');

        return view('admin.giangvien.index', compact(
            'giangviens',
            'lops',
            'totalGiangVien',
            'totalLopCoVan'
        ));
    }

    /**
     * Hiển thị form thêm mới
     */
    public function create()
    {
        return view('admin.giangvien.create');
    }

    /**
     * Lưu giảng viên mới
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'MaGV' => 'required|unique:giangvien,MaGV|max:20',
            'TenGV' => 'required|string|max:100',
            'Email' => 'nullable|email',
            'SDT' => 'nullable|string|max:15',
            'GioiTinh' => 'required|in:Nam,Nữ,Khác',
        ]);

        DB::transaction(function () use ($data) {
            // Tạo tài khoản đăng nhập tương ứng với mật khẩu được hash
            \App\Models\TaiKhoan::create([
                'TenDangNhap' => $data['MaGV'],
                'MatKhau' => Hash::make('123456'),
                'VaiTro' => 'GiangVien',
            ]);

            // Thêm giảng viên
            GiangVien::create($data);
        });

        return redirect()->route('admin.giangvien.index')
            ->with('success', 'Thêm giảng viên thành công! Mật khẩu mặc định: 123456');
    }

    /**
     * Hiển thị chi tiết giảng viên
     */
    public function show($MaGV)
    {
        $giangvien = GiangVien::with('lopPhuTrach')->findOrFail($MaGV);
        return view('admin.giangvien.show', compact('giangvien'));
    }

    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($MaGV)
    {
        $giangvien = GiangVien::findOrFail($MaGV);
        return view('admin.giangvien.edit', compact('giangvien'));
    }

    /**
     * Cập nhật thông tin giảng viên
     */
    public function update(Request $request, $MaGV)
    {
        $data = $request->validate([
            'TenGV' => 'required|string|max:100',
            'Email' => 'nullable|email',
            'SDT' => 'nullable|string|max:15',
            'GioiTinh' => 'required|in:Nam,Nữ,Khác',
        ]);

        $giangvien = GiangVien::findOrFail($MaGV);
        $giangvien->update($data);

        return redirect()->route('admin.giangvien.index')
            ->with('success', 'Cập nhật thông tin giảng viên thành công!');
    }

    /**
     * Xóa giảng viên
     */
    public function destroy($MaGV)
    {
        DB::transaction(function () use ($MaGV) {
            $giangvien = GiangVien::findOrFail($MaGV);

            // Xóa lớp cố vấn trước
            $giangvien->lopPhuTrach()->detach();

            // Xóa tài khoản
            \App\Models\TaiKhoan::where('TenDangNhap', $MaGV)->delete();

            // Xóa giảng viên
            $giangvien->delete();
        });

        return redirect()->route('admin.giangvien.index')
            ->with('success', 'Đã xóa giảng viên và tài khoản liên quan!');
    }

    /**
     * Form gán lớp cố vấn
     */
    public function assignLopForm($MaGV)
    {
        $giangvien = GiangVien::with('lopPhuTrach')->findOrFail($MaGV);
        
        // Lấy ID của các lớp đã có cố vấn (trừ lớp của giảng viên hiện tại)
        $lopsWithCoVan = DB::table('covanht')
            ->where('MaGiangVien', '!=', $MaGV)
            ->pluck('MaLop')
            ->toArray();
        
        // Lấy các lớp chưa có cố vấn hoặc đã gán cho giảng viên hiện tại
        $lops = Lop::whereNotIn('MaLop', $lopsWithCoVan)
            ->orderBy('TenLop')
            ->get();
        
        $lopPhuTrach = $giangvien->lopPhuTrach->pluck('MaLop')->toArray();

        return view('admin.giangvien.assign', compact('giangvien', 'lops', 'lopPhuTrach'));
    }

    /**
     * Cập nhật lớp cố vấn
     */
    public function assignLop(Request $request, $MaGV)
    {
        $giangvien = GiangVien::findOrFail($MaGV);
        $giangvien->lopPhuTrach()->sync($request->lop ?? []);

        return redirect()->route('admin.giangvien.index')
            ->with('success', 'Cập nhật lớp cố vấn thành công!');
    }
}
