<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DiemCtxh; // Model đã sửa
use App\Models\DangKyHoatDongCtxh;
use App\Models\HoatDongCTXH;
use Carbon\Carbon;


class DiemCTXHController extends Controller
{
    /**
     * Hiển thị trang chi tiết điểm Công Tác Xã Hội (toàn khóa).
     */
    public function index()
    {
        $mssv = Auth::user()->TenDangNhap;
        $now = Carbon::now();

        // 1. Lấy hoặc tạo điểm CTXH (Khởi tạo 0 điểm)
        // Sử dụng MSSV làm khóa chính (như model đã sửa)
        $diemTongKet = DiemCtxh::firstOrCreate(
            ['MSSV' => $mssv],
            [
                'TongDiem' => 0, 
                'NgayCapNhat' => $now // Cung cấp giá trị cho cột bắt buộc
            ]
        );

        // 2. Lấy các hoạt động đã tham gia (Trạng thái "Đã tham gia")
        // Chúng ta giả định chỉ tính điểm cho các HĐ "Đã tham gia"
        $cacHoatDongDaThamGia = DangKyHoatDongCtxh::with('hoatdong.quydinh')
            ->where('MSSV', $mssv)
            ->where('TrangThaiThamGia', 'Đã tham gia')
            ->get();
        
        // 3. Tính điểm cộng và check "Địa chỉ đỏ"
        $diemCongThem = 0;
        // $diemTruDi = 0; // <-- KHÔNG CẦN NỮA
        $has_red_activity = false;

        foreach($cacHoatDongDaThamGia as $dangKy) {
            // Kiểm tra $dangKy->hoatdong và $dangKy->hoatdong->quydinh có tồn tại không
            if ($dangKy->hoatdong && $dangKy->hoatdong->quydinh) {
                
                $diem = $dangKy->hoatdong->quydinh->DiemNhan ?? 0;
                
                // SỬA LOGIC: Chỉ cộng nếu điểm > 0
                if ($diem > 0) {
                    $diemCongThem += $diem;
                }
                // else {
                //     $diemTruDi += $diem; 
                // } // <-- KHÔNG CẦN NỮA

                // Kiểm tra loại hoạt động (ví dụ: 'Địa chỉ đỏ')
                if (str_contains($dangKy->hoatdong->LoaiHoatDong, 'Địa chỉ đỏ')) {
                    $has_red_activity = true;
                }
            }
        }
        
        // 4. Tính tổng điểm mới (SỬA LẠI: chỉ là điểm cộng)
        $tongDiemMoi = $diemCongThem;

        // 5. Cập nhật CSDL nếu điểm có thay đổi
        if ($diemTongKet->TongDiem != $tongDiemMoi) {
            $diemTongKet->TongDiem = $tongDiemMoi;
            $diemTongKet->NgayCapNhat = $now;
            $diemTongKet->save();
        }

        // 6. Trả về view (SỬA LẠI: Bỏ $diemTruDi và $diemCongThem vì đã có $tongDiemMoi)
        return view('sinhvien.diem_cong_tac_xa_hoi.index', compact(
            'tongDiemMoi',
            'has_red_activity',
            'cacHoatDongDaThamGia'
        ));
    }
}

