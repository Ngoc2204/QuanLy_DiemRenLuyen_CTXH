<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuyDinhDiemRL;
use Illuminate\Support\Facades\DB; // <--- THÊM: Để kiểm tra lỗi DB khi xóa

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
        // <--- SỬA: Bỏ validation cho 'MaDiem'
        $validated = $request->validate([
            'TenCongViec' => 'required|string|max:255',
            'DiemNhan' => 'required|integer|min:0',
        ]);

        // <--- THÊM: Logic tự động tạo MaDiem
        try {
            // Lấy MaDiem cuối cùng (ví dụ: DRL09)
            $lastQuyDinh = QuyDinhDiemRL::orderBy('MaDiem', 'desc')->first();
            
            $nextIdNumber = 1; // Bắt đầu từ 1 nếu chưa có
            if ($lastQuyDinh) {
                // Tách lấy phần số từ MaDiem (ví dụ: 'DRL09' -> '09')
                $lastIdNumber = (int) substr($lastQuyDinh->MaDiem, 3); 
                $nextIdNumber = $lastIdNumber + 1; // Tăng lên 1 (ví dụ: 10)
            }

            // Định dạng lại MaDiem (ví dụ: 'DRL' + '10' = 'DRL10')
            // str_pad sẽ thêm '0' vào trước nếu số < 10 (ví dụ: 'DRL' + '03')
            $newMaDiem = 'DRL' . str_pad($nextIdNumber, 2, '0', STR_PAD_LEFT);

            // Gán MaDiem mới vào mảng dữ liệu
            $validated['MaDiem'] = $newMaDiem;

            QuyDinhDiemRL::create($validated);
            return redirect()->route('admin.quydinhdiemrl.index')->with('success', 'Thêm quy định điểm ('. $newMaDiem .') thành công!');

        } catch (\Exception $e) {
            // Xử lý nếu có lỗi (ví dụ: trùng lặp MaDiem do race condition)
             return redirect()->back()->with('error', 'Không thể tạo quy định: ' . $e->getMessage())->withInput();
        }
    }

    // <--- SỬA: Đổi $id thành $maDiem
    public function edit($maDiem)
    {
        // <--- SỬA: Dùng findOrFail với $maDiem
        // Lưu ý: Model QuyDinhDiemRL của bạn phải set protected $primaryKey = 'MaDiem';
        $quydinhdiem = QuyDinhDiemRL::findOrFail($maDiem); 
        return view('admin.quydinhdiemrl.edit', compact('quydinhdiem'));
    }

    // <--- SỬA: Đổi $id thành $maDiem
    public function update(Request $request, $maDiem)
    {
        // <--- SỬA: Dùng findOrFail với $maDiem
        $quydinhdiem = QuyDinhDiemRL::findOrFail($maDiem);

        $validated = $request->validate([
            'TenCongViec' => 'required|string|max:255',
            'DiemNhan' => 'required|integer|min:0',
            // Không validate MaDiem khi update
        ]);

        $quydinhdiem->update($validated);
        return redirect()->route('admin.quydinhdiemrl.index')->with('success', 'Cập nhật quy định điểm (' . $maDiem . ') thành công!');
    }

    // <--- SỬA: Đổi $id thành $maDiem
    public function destroy($maDiem)
    {
        try {
            // <--- SỬA: Dùng findOrFail với $maDiem
            $quydinhdiem = QuyDinhDiemRL::findOrFail($maDiem);

            // <--- THÊM: Kiểm tra an toàn trước khi xóa
            // (Bạn cần thêm quan hệ 'hoatDongs' vào Model, xem Bước 2)
            if ($quydinhdiem->hoatDongs()->count() > 0) {
                 return redirect()->route('admin.quydinhdiemrl.index')->with('error', 'Không thể xóa quy định (' . $maDiem . ') vì nó đang được sử dụng bởi ' . $quydinhdiem->hoatDongs()->count() . ' hoạt động.');
            }

            $quydinhdiem->delete();
            return redirect()->route('admin.quydinhdiemrl.index')->with('success', 'Xóa quy định điểm (' . $maDiem . ') thành công!');
        
        } catch (\Illuminate\Database\QueryException $e) {
            // Bắt lỗi nếu DB từ chối xóa vì lý do nào đó
             return redirect()->route('admin.quydinhdiemrl.index')->with('error', 'Không thể xóa quy định: Có lỗi ràng buộc cơ sở dữ liệu.');
        } catch (\Exception $e) {
             return redirect()->route('admin.quydinhdiemrl.index')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}