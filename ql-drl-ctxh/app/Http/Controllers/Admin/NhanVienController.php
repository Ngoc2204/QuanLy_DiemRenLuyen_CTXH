<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NhanVien;
use App\Models\TaiKhoan;
use Carbon\Carbon;

class NhanVienController extends Controller
{
    public function index(Request $request)
    {
        $query = NhanVien::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('MaNV', 'like', "%{$keyword}%")
                  ->orWhere('TenNV', 'like', "%{$keyword}%");
        }

        $nhanviens = $query->orderBy('MaNV')->paginate(15);
        $totalNhanViens = \App\Models\NhanVien::count();

        return view('admin.nhanvien.index', compact('nhanviens', 'totalNhanViens'));
    }

    public function create()
    {
        return view('admin.nhanvien.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'MaNV' => 'required|unique:nhanvien,MaNV|max:20',
            'TenNV' => 'required|string|max:100',
            'Email' => 'nullable|email|max:100',
            'SDT' => 'nullable|string|max:15',
            'GioiTinh' => 'required|in:Nam,Nữ,Khác',
        ]);

        // Tạo tài khoản tự động
        TaiKhoan::create([
            'TenDangNhap' => $data['MaNV'],
            'MatKhau' => '123456', // Mật khẩu mặc định
            'VaiTro' => 'NhanVien',
        ]);

        // Thêm nhân viên
        $nhanvien = NhanVien::create($data);

        

        return redirect()->route('admin.nhanvien.index')
            ->with('success', 'Thêm nhân viên thành công!');
    }

    public function edit($MaNV)
    {
        $nhanvien = NhanVien::findOrFail($MaNV);
        return view('admin.nhanvien.edit', compact('nhanvien'));
    }

    public function update(Request $request, $MaNV)
    {
        $nhanvien = NhanVien::findOrFail($MaNV);

        $data = $request->validate([
            'TenNV' => 'required|string|max:100',
            'Email' => 'nullable|email|max:100',
            'SDT' => 'nullable|string|max:15',
            'GioiTinh' => 'required|in:Nam,Nữ,Khác',
        ]);

        $nhanvien->update($data);

        return redirect()->route('admin.nhanvien.index')
            ->with('success', 'Cập nhật thông tin nhân viên thành công!');
    }

    public function destroy($MaNV)
    {
        $nhanvien = NhanVien::findOrFail($MaNV);
        $nhanvien->delete();

        return redirect()->route('admin.nhanvien.index')
            ->with('success', 'Xóa nhân viên thành công!');
    }

    public function show($MaNV)
    {
        $nhanvien = NhanVien::findOrFail($MaNV);
        return view('admin.nhanvien.show', compact('nhanvien'));
    }

}
