<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Khoa;

class KhoaController extends Controller
{
    public function index(Request $request)
    {
        $query = Khoa::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('MaKhoa', 'like', "%{$keyword}%")
                  ->orWhere('TenKhoa', 'like', "%{$keyword}%");
        }

        $khoas = $query->orderBy('TenKhoa')->paginate(10);
        $total = Khoa::count();

        return view('admin.khoa.index', compact('khoas', 'total'));
    }

    public function create()
    {
        return view('admin.khoa.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'MaKhoa' => 'required|unique:khoa,MaKhoa|max:20',
            'TenKhoa' => 'required|string|max:100',
        ]);

        Khoa::create($data);

        return redirect()->route('admin.khoa.index')
            ->with('success', 'Thêm khoa mới thành công!');
    }

    public function edit($id)
    {
        $khoa = Khoa::findOrFail($id);
        return view('admin.khoa.edit', compact('khoa'));
    }

    public function update(Request $request, $id)
    {
        $khoa = Khoa::findOrFail($id);

        $data = $request->validate([
            'TenKhoa' => 'required|string|max:100',
        ]);

        $khoa->update($data);

        return redirect()->route('admin.khoa.index')
            ->with('success', 'Cập nhật khoa thành công!');
    }

    public function destroy($id)
    {
        $khoa = Khoa::findOrFail($id);
        $khoa->delete();

        return redirect()->route('admin.khoa.index')
            ->with('success', 'Đã xóa khoa thành công!');
    }
}
