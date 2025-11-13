<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThanhToan;
use App\Models\DangKyHoatDongCtxh; // Cần model này để cập nhật
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuanLyThanhToanController extends Controller
{
    /**
     * Hiển thị danh sách các hóa đơn chờ thanh toán.
     */
    public function index(Request $request)
    {
        // Tải hóa đơn KÈM THEO thông tin của đơn đăng ký (Tên SV, Tên HĐ)
        // Sửa: Thêm `dangKyHoatDong` vào with() để đảm bảo nó luôn được tải
        $query = ThanhToan::with('dangKyHoatDong.hoatdong', 'dangKyHoatDong.sinhvien')
                         ->where('TrangThai', 'Chờ thanh toán');

        // Lọc theo MSSV
        if ($request->filled('search_mssv')) {
            $query->whereHas('dangKyHoatDong', function($q) use ($request) {
                $q->where('MSSV', 'like', '%' . $request->search_mssv . '%');
            });
        }

        // Lọc theo Phương thức
        if ($request->filled('phuong_thuc')) {
            $query->where('PhuongThuc', $request->phuong_thuc);
        }

        $thanhToans = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('nhanvien.thanhtoan.index', compact('thanhToans'));
    }

    /**
     * Xác nhận đã thu tiền (Tiền mặt hoặc Online)
     */
    public function xacNhanThanhToan(Request $request, $id)
    {
        // SỬA: Tải 'dangKyHoatDong' cùng lúc để sử dụng
        $thanhToan = ThanhToan::with('dangKyHoatDong')
                            ->where('id', $id)
                            ->where('TrangThai', 'Chờ thanh toán')
                            ->firstOrFail();

        try {
            DB::beginTransaction();

            $maGiaoDichInput = $request->input('ma_giao_dich');
            $finalMaGiaoDich = $maGiaoDichInput; 
            if (empty($maGiaoDichInput)) {
                // Tự gán mã nếu nhân viên không nhập
                $finalMaGiaoDich = $thanhToan->PhuongThuc == 'TienMat' ? 'CASH_CONFIRMED' : 'ONLINE_CONFIRMED';
            }

            // 1. Cập nhật hóa đơn
            $thanhToan->update([
                'TrangThai' => 'Đã thanh toán',
                'NgayThanhToan' => now(),
                'MaGiaoDich' => $finalMaGiaoDich, 
            ]);

            // 2. Cập nhật đơn đăng ký liên quan
            
            // SỬA: Lấy đơn đăng ký từ relationship đã tải
            $donDangKy = $thanhToan->dangKyHoatDong;

            if ($donDangKy) {
                // Cập nhật trạng thái của đơn đăng ký
                $donDangKy->update([
                    'TrangThaiDangKy' => 'Đã duyệt' // Thanh toán xong = tự động duyệt
                ]);
                
                DB::commit();
                
                // SỬA: Lấy MaDangKy từ $donDangKy
                return redirect()->back()->with('success', 'Xác nhận thanh toán cho Mã ĐK ' . $donDangKy->MaDangKy . ' thành công!');
            } else {
                // Trường hợp hiếm: Hóa đơn tồn tại nhưng không lk với đơn ĐK nào
                DB::rollBack();
                Log::warning('Hóa đơn ' . $thanhToan->id . ' không có đơn đăng ký liên kết.');
                return redirect()->back()->with('error', 'Lỗi: Hóa đơn này không có đơn đăng ký liên kết.');
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('LỖI XÁC NHẬN THANH TOÁN: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xác nhận.');
        }
    }
}