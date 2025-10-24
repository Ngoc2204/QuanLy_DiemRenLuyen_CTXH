<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SinhVien;
use App\Models\Lop;
use App\Models\Khoa;
use App\Models\TaiKhoan;
use Carbon\Carbon;

class SinhVienController extends Controller
{
    public function index(Request $request)
    {
        $query = SinhVien::query()
            ->join('lop', 'sinhvien.MaLop', '=', 'lop.MaLop')
            ->join('khoa', 'lop.MaKhoa', '=', 'khoa.MaKhoa')
            ->select('sinhvien.*', 'lop.TenLop', 'lop.MaKhoa', 'khoa.TenKhoa');

        // 🔎 Lọc từ khóa
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('sinhvien.MSSV', 'like', "%{$keyword}%")
                    ->orWhere('sinhvien.HoTen', 'like', "%{$keyword}%");
            });
        }

        // 🔎 Lọc theo khoa
        if ($request->filled('MaKhoa')) {
            $query->where('lop.MaKhoa', $request->MaKhoa);
        }

        // 🔎 Lọc theo lớp
        if ($request->filled('MaLop')) {
            $query->where('sinhvien.MaLop', $request->MaLop);
        }

        $sinhviens = $query->orderBy('sinhvien.MSSV')->paginate(15);

        // 📋 Dữ liệu phụ
        $khoas = Khoa::orderBy('TenKhoa')->get();
        $lops = Lop::orderBy('TenLop')->get();

        // 📊 Thống kê
        $totalStudents = SinhVien::count();
        $totalKhoas = Khoa::count();
        $totalLops = Lop::count();

        return view('admin.sinhvien.index', compact(
            'sinhviens',
            'khoas',
            'lops',
            'totalStudents',
            'totalKhoas',
            'totalLops'
        ));
    }

    public function create()
    {
        $khoas = Khoa::orderBy('TenKhoa')->get();
        $lops = Lop::orderBy('TenLop')->get();
        return view('admin.sinhvien.create', compact('khoas', 'lops'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'MSSV' => 'required|unique:sinhvien,MSSV|max:20',
            'HoTen' => 'required|string|max:100',
            'NgaySinh' => 'required|date',
            'GioiTinh' => 'required|in:Nam,Nữ,Khác',
            'Email' => 'nullable|email|max:100',
            'SDT' => 'nullable|string|max:15',
            'SoThich' => 'nullable|string|max:255',
            'ThoiGianTotNghiepDuKien' => 'nullable|date',
            'MaLop' => 'required|exists:lop,MaLop',
        ]);



        // 🔐 Mật khẩu mặc định là ngày sinh dạng ddmmyyyy
        $matkhau_macdinh = Carbon::parse($data['NgaySinh'])->format('dmY');

        // ✅ Tạo tài khoản tương ứng
        TaiKhoan::create([
            'TenDangNhap' => $data['MSSV'],
            'MatKhau' => $matkhau_macdinh,
            'VaiTro' => 'SinhVien',
        ]);

        // ✅ Thêm sinh viên vào bảng sinhvien
        $sinhvien = SinhVien::create($data);

        return redirect()
            ->route('admin.sinhvien.index')
            ->with('success', '🎉 Thêm sinh viên thành công! Tài khoản đã được tạo với mật khẩu mặc định là ngày sinh.');
    }

    public function show($MSSV)
    {
        $sinhvien = \App\Models\SinhVien::with(['lop.khoa'])
            ->where('MSSV', $MSSV)
            ->firstOrFail();

        return view('admin.sinhvien.show', compact('sinhvien'));
    }


    public function edit($MSSV)
    {
        $sinhvien = SinhVien::where('MSSV', $MSSV)->firstOrFail();
        $khoas = Khoa::orderBy('TenKhoa')->get();
        $lops = Lop::orderBy('TenLop')->get();

        return view('admin.sinhvien.edit', compact('sinhvien', 'khoas', 'lops'));
    }

    public function update(Request $request, $MSSV)
    {
        $sinhvien = SinhVien::where('MSSV', $MSSV)->firstOrFail();

        $data = $request->validate([
            'HoTen' => 'required|string|max:100',
            'NgaySinh' => 'required|date',
            'GioiTinh' => 'required|in:Nam,Nữ,Khác',
            'Email' => 'nullable|email|max:100',
            'SDT' => 'nullable|string|max:15',
            'SoThich' => 'nullable|string|max:255',
            'ThoiGianTotNghiepDuKien' => 'nullable|date',
            'MaLop' => 'required|exists:lop,MaLop',
        ]);

        $sinhvien->update($data);

        return redirect()
            ->route('admin.sinhvien.show', $MSSV)
            ->with('success', 'Cập nhật thông tin sinh viên thành công!');
    }
}
