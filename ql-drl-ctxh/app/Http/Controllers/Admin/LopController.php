<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lop;
use App\Models\Khoa;

class LopController extends Controller
{
    public function index(Request $request)
    {
        $query = Lop::with('khoa');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('MaLop', 'like', "%{$keyword}%")
                  ->orWhere('TenLop', 'like', "%{$keyword}%");
        }

        if ($request->filled('MaKhoa')) {
            $query->where('MaKhoa', $request->MaKhoa);
        }

        $lops = $query->orderBy('TenLop')->paginate(10);
        $khoas = Khoa::orderBy('TenKhoa')->get();
        $total = Lop::count();

        return view('admin.lop.index', compact('lops', 'khoas', 'total'));
    }

    public function create()
    {
        $khoas = Khoa::orderBy('TenKhoa')->get();
        return view('admin.lop.create', compact('khoas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'MaLop' => 'required|unique:lop,MaLop|max:20',
            'TenLop' => 'required|string|max:100',
            'MaKhoa' => 'required|exists:khoa,MaKhoa',
        ]);

        Lop::create($data);

        return redirect()->route('admin.lop.index')
            ->with('success', 'Thêm lớp mới thành công!');
    }

    public function edit($id)
    {
        $lop = Lop::findOrFail($id);
        $khoas = Khoa::orderBy('TenKhoa')->get();
        return view('admin.lop.edit', compact('lop', 'khoas'));
    }

    public function update(Request $request, $id)
    {
        $lop = Lop::findOrFail($id);

        $data = $request->validate([
            'TenLop' => 'required|string|max:100',
            'MaKhoa' => 'required|exists:khoa,MaKhoa',
        ]);

        $lop->update($data);

        return redirect()->route('admin.lop.index')
            ->with('success', 'Cập nhật lớp thành công!');
    }

    public function destroy($id)
    {
        $lop = Lop::findOrFail($id);
        $lop->delete();

        return redirect()->route('admin.lop.index')
            ->with('success', 'Đã xóa lớp thành công!');
    }
}
