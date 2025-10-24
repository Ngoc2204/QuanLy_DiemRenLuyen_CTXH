<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuyDinhDiemCTXH;

class QuyDinhDiemCTXHController extends Controller
{
    public function index(Request $request)
    {
        $quydinhctxhs = QuyDinhDiemCTXH::orderBy('MaDiem')->paginate(15);
        return view('admin.quydinhdiemctxh.index', compact('quydinhctxhs'));
    }

    public function create()
    {
        return view('admin.quydinhdiemctxh.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaDiem' => 'required|unique:quydinhdiemctxh,MaDiem|max:10',
            // Corrected from TenHoatDong to TenCongViec to match the model
            'TenCongViec' => 'required|string|max:255', 
            'DiemNhan' => 'required|integer|min:0',
        ]);

        // Ensure the validated data keys match the fillable fields
        // If the form input name is different, you might need to map it here.
        // Assuming form input name matches 'TenCongViec' now.
        QuyDinhDiemCTXH::create($validated);
        return redirect()->route('admin.quydinhdiemctxh.index')->with('success', 'Thêm quy định CTXH thành công!');
    }

    public function edit($id)
    {
        $quydinhctxh = QuyDinhDiemCTXH::findOrFail($id);
        return view('admin.quydinhdiemctxh.edit', compact('quydinhctxh'));
    }

    public function update(Request $request, $id)
    {
        $quydinhctxh = QuyDinhDiemCTXH::findOrFail($id);

        $validated = $request->validate([
            // Corrected from TenHoatDong to TenCongViec to match the model
            'TenCongViec' => 'required|string|max:255',
            'DiemNhan' => 'required|integer|min:0',
        ]);

        $quydinhctxh->update($validated);
        return redirect()->route('admin.quydinhdiemctxh.index')->with('success', 'Cập nhật quy định CTXH thành công!');
    }

    public function destroy($id)
    {
        QuyDinhDiemCTXH::findOrFail($id)->delete();
        return redirect()->route('admin.quydinhdiemctxh.index')->with('success', 'Xóa quy định CTXH thành công!');
    }
}
