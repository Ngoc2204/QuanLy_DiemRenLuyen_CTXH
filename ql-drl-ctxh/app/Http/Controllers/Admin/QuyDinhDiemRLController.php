<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuyDinhDiemRL;

class QuyDinhDiemRLController extends Controller
{
    public function index()
    {
        $quydinhdiemrls = QuyDinhDiemRL::orderBy('MaDiem')->paginate(15);
        return view('admin.quydinhdiemrl.index', compact('quydinhdiemrls'));
    }

    public function create()
    {
        return view('admin.quydinhdiemrl.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaDiem' => 'required|unique:quydinhdiemrl,MaDiem|max:10',
            'TenCongViec' => 'required|string|max:255',
            'DiemNhan' => 'required|integer|min:0',
        ]);

        QuyDinhDiemRL::create($validated);
        return redirect()->route('admin.quydinhdiemrl.index')->with('success', 'Thêm quy định điểm thành công!');
    }

    public function edit($id)
    {
        $quydinhdiem = QuyDinhDiemRL::findOrFail($id);
        return view('admin.quydinhdiemrl.edit', compact('quydinhdiem'));
    }

    public function update(Request $request, $id)
    {
        $quydinhdiem = QuyDinhDiemRL::findOrFail($id);

        $validated = $request->validate([
            'TenCongViec' => 'required|string|max:255',
            'DiemNhan' => 'required|integer|min:0',
        ]);

        $quydinhdiem->update($validated);
        return redirect()->route('admin.quydinhdiemrl.index')->with('success', 'Cập nhật quy định điểm thành công!');
    }

    public function destroy($id)
    {
        QuyDinhDiemRL::findOrFail($id)->delete();
        return redirect()->route('admin.quydinhdiemrl.index')->with('success', 'Xóa quy định điểm thành công!');
    }
}
