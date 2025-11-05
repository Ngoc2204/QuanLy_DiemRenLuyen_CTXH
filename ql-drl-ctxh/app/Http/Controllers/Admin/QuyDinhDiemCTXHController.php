<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuyDinhDiemCTXH;
use Illuminate\Support\Facades\DB;

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
        // <--- SỬA: Bỏ validation cho 'MaDiem'
        $validated = $request->validate([
            'TenCongViec' => 'required|string|max:255',
            'DiemNhan' => 'required|integer|min:0',
        ]);


        try {

            $lastQuyDinh = QuyDinhDiemCTXH::orderBy('MaDiem', 'desc')->first();

            $nextIdNumber = 1; // Bắt đầu từ 1 nếu chưa có
            if ($lastQuyDinh) {

                $lastIdNumber = (int) substr($lastQuyDinh->MaDiem, 4); // 'CTXH' là 4 ký tự
                $nextIdNumber = $lastIdNumber + 1; // Tăng lên 1 (ví dụ: 2)
            }


            $newMaDiem = 'CTXH' . str_pad($nextIdNumber, 2, '0', STR_PAD_LEFT);


            $validated['MaDiem'] = $newMaDiem;

            QuyDinhDiemCTXH::create($validated);
            return redirect()->route('admin.quydinhdiemctxh.index')->with('success', 'Thêm quy định CTXH (' . $newMaDiem . ') thành công!');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Không thể tạo quy định: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($maDiem)
    {
        $quydinhctxh = QuyDinhDiemCTXH::findOrFail($maDiem);
        return view('admin.quydinhdiemctxh.edit', compact('quydinhctxh'));
    }

    public function update(Request $request, $maDiem)
    {
        $quydinhctxh = QuyDinhDiemCTXH::findOrFail($maDiem);

        $validated = $request->validate([
            // Corrected from TenHoatDong to TenCongViec to match the model
            'TenCongViec' => 'required|string|max:255',
            'DiemNhan' => 'required|integer|min:0',
        ]);

        $quydinhctxh->update($validated);
        return redirect()->route('admin.quydinhdiemctxh.index')->with('success', 'Cập nhật quy định CTXH thành công!');
    }

    public function destroy($maDiem)
    {
        try {

            $quydinhctxh = QuyDinhDiemCTXH::findOrFail($maDiem);

            if ($quydinhctxh->hoatDongs()->count() > 0) {
                return redirect()->route('admin.quydinhdiemctxh.index')->with('error', 'Không thể xóa quy định (' . $maDiem . ') vì nó đang được sử dụng bởi ' . $quydinhctxh->hoatDongs()->count() . ' hoạt động.');
            }

            $quydinhctxh->delete();
            return redirect()->route('admin.quydinhdiemctxh.index')->with('success', 'Xóa quy định CTXH (' . $maDiem . ') thành công!');
        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()->route('admin.quydinhdiemctxh.index')->with('error', 'Không thể xóa quy định: Có lỗi ràng buộc cơ sở dữ liệu.');
        } catch (\Exception $e) {
            return redirect()->route('admin.quydinhdiemctxh.index')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}
