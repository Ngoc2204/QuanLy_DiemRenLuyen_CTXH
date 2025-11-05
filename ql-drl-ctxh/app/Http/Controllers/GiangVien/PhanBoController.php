<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HoatDongDrl;
use App\Models\Khoa;
use App\Models\PhanBoSoLuong;
use Illuminate\Support\Facades\DB;

class PhanBoController extends Controller
{
    /**
     * Hiển thị danh sách các hoạt động GV phụ trách
     */
    public function index(Request $request)
    {
        $maGV = Auth::user()->TenDangNhap;

        $hoatDongs = HoatDongDrl::where('MaGV', $maGV)
            ->with('hocKy')
            // Đếm số lượng đã đăng ký
            ->withCount('dangky')
            // Tính tổng số lượng đã phân bổ
            ->withSum('phanBos as DaPhanBo', 'SoLuongPhanBo')
            ->orderBy('ThoiGianBatDau', 'desc')
            ->paginate(15);

        return view('giangvien.phanbo.index', compact('hoatDongs'));
    }

    /**
     * Hiển thị form để phân bổ
     */
    public function edit(HoatDongDrl $hoatdong_drl)
    {
        // Kiểm tra đúng GV phụ trách
        if ($hoatdong_drl->MaGV != Auth::user()->TenDangNhap) {
            abort(403, 'Bạn không có quyền phân bổ cho hoạt động này.');
        }

        // Lấy tất cả các khoa
        $khoas = Khoa::orderBy('TenKhoa')->get();

        // Lấy các phân bổ đã lưu
        $existingAllocations = PhanBoSoLuong::where('MaHoatDong', $hoatdong_drl->MaHoatDong)
            ->pluck('SoLuongPhanBo', 'MaKhoa'); // -> ['CNTT' => 80, 'CK' => 20]

        return view('giangvien.phanbo.edit', compact('hoatdong_drl', 'khoas', 'existingAllocations'));
    }

    /**
     * Lưu dữ liệu phân bổ
     */
    public function update(Request $request, HoatDongDrl $hoatdong_drl)
    {
        // Kiểm tra đúng GV phụ trách
        if ($hoatdong_drl->MaGV != Auth::user()->TenDangNhap) {
            abort(403, 'Bạn không có quyền phân bổ cho hoạt động này.');
        }

        // $request->so_luong_khoa là một mảng ['CNTT' => '80', 'CK' => '20', ...]
        $allocations = $request->input('so_luong_khoa', []);
        
        // --- VALIDATION ---
        $totalAllocated = 0;
        foreach ($allocations as $maKhoa => $soLuong) {
            if (!is_numeric($soLuong) || (int)$soLuong < 0) {
                 return redirect()->back()->with('error', 'Số lượng phân bổ cho Khoa ' . $maKhoa . ' phải là một số >= 0.');
            }
            $totalAllocated += (int)$soLuong;
        }

        // So sánh tổng phân bổ với tổng số lượng của hoạt động
        if ($totalAllocated != $hoatdong_drl->SoLuong) {
             return redirect()->back()->with('error', "Tổng số lượng đã phân bổ ({$totalAllocated}) không khớp với tổng số lượng của hoạt động ({$hoatdong_drl->SoLuong}). Vui lòng điều chỉnh lại.");
        }

        // --- LƯU VÀO DATABASE ---
        try {
            DB::beginTransaction();
            
            // Xóa hết phân bổ cũ
            PhanBoSoLuong::where('MaHoatDong', $hoatdong_drl->MaHoatDong)->delete();
            
            // Thêm phân bổ mới
            foreach ($allocations as $maKhoa => $soLuong) {
                if ((int)$soLuong > 0) {
                    PhanBoSoLuong::create([
                        'MaHoatDong' => $hoatdong_drl->MaHoatDong,
                        'MaKhoa' => $maKhoa,
                        'SoLuongPhanBo' => (int)$soLuong,
                    ]);
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi CSDL: ' . $e->getMessage());
        }

        return redirect()->route('giangvien.hoatdong.phanbo.index')->with('success', 'Phân bổ số lượng cho hoạt động ' . $hoatdong_drl->MaHoatDong . ' thành công!');
    }
}