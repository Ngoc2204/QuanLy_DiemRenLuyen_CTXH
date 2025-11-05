<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HocKy;
use App\Models\DiemRenLuyen;
use App\Models\DangKyHoatDongDrl;
use Carbon\Carbon;

class DiemRenLuyenController extends Controller
{
    /**
     * Hiển thị trang điểm rèn luyện chi tiết theo kỳ.
     */
    public function index(Request $request)
    {
        $mssv = Auth::user()->TenDangNhap;
        $now = Carbon::now();
        $diemGoc = 70; // Điểm khởi tạo cố định

        // 1. Lấy tất cả học kỳ để làm bộ lọc
        $allHocKys = HocKy::orderBy('NgayBatDau', 'desc')->get();

        // 2. Xác định học kỳ đang xem
        $selectedHocKyMa = $request->input('hocky');
        $currentHocKy = null;

        if ($selectedHocKyMa) {
            $currentHocKy = HocKy::find($selectedHocKyMa);
        } else {
            // Nếu không chọn, tìm học kỳ hiện tại
            $currentHocKy = HocKy::where('NgayBatDau', '<=', $now)
                                 ->where('NgayKetThuc', '>=', $now)
                                 ->first() 
                                 ?? $allHocKys->first(); // Lấy cái gần nhất nếu không có
        }
        
        // Khởi tạo các biến
        $diemCongThem = 0;
        $diemTruDi = 0; // <-- BIẾN MỚI
        $diemRenLuyen = null;
        $cacHoatDongDaThamGia = collect();
        $tongDiemMoi = $diemGoc; 
        $xepLoaiMoi = $this->getXepLoai($diemGoc);

        // 3. Chỉ tính toán nếu có một học kỳ hợp lệ
        if ($currentHocKy) {
            
            // 4. Lấy (hoặc tạo) điểm DRL gốc cho học kỳ này (Logic 70 điểm)
            $diemRenLuyen = DiemRenLuyen::firstOrCreate(
                [
                    'MSSV' => $mssv,
                    'MaHocKy' => $currentHocKy->MaHocKy
                ],
                [
                    'TongDiem' => $diemGoc,
                    'XepLoai' => 'Khá', // 70 điểm là Khá
                    'NgayCapNhat' => $now
                ]
            );

            // 5. Lấy tất cả hoạt động DRL đã tham gia trong học kỳ này
            $cacHoatDongDaThamGia = DangKyHoatDongDrl::with('hoatdong.quydinh')
                ->where('MSSV', $mssv)
                // Chỉ lấy hoạt động đã được duyệt VÀ có trạng thái "Đã tham gia"
                ->where('TrangThaiDangKy', 'Đã duyệt') 
                ->where('TrangThaiThamGia', 'Đã tham gia')
                ->whereHas('hoatdong', function ($query) use ($currentHocKy) {
                    $query->where('MaHocKy', $currentHocKy->MaHocKy);
                })
                ->get();

            // 6. TÍNH TOÁN LẠI: Phân loại điểm cộng và điểm trừ
            $diemCongThem = 0;
            $diemTruDi = 0; 
            foreach ($cacHoatDongDaThamGia as $dangKy) {
                // Đảm bảo có thông tin quy định điểm
                if ($dangKy->hoatdong && $dangKy->hoatdong->quydinh) {
                    $diem = $dangKy->hoatdong->quydinh->DiemNhan;
                    
                    if ($diem > 0) {
                        $diemCongThem += $diem;
                    } elseif ($diem < 0) {
                        $diemTruDi += $diem; // $diemTruDi sẽ là số âm (vd: -10)
                    }
                }
            }

            // 7. Tính tổng điểm mới (Không vượt quá 100 và không thấp hơn 0)
            $tongDiemMoi = max(0, min(100, $diemGoc + $diemCongThem + $diemTruDi));

            // 8. Cập nhật điểm và xếp loại trong CSDL
            $xepLoaiMoi = $this->getXepLoai($tongDiemMoi);
            
            // Chỉ cập nhật nếu có thay đổi
            if ($diemRenLuyen->TongDiem != $tongDiemMoi || $diemRenLuyen->XepLoai != $xepLoaiMoi) {
                $diemRenLuyen->TongDiem = $tongDiemMoi;
                $diemRenLuyen->XepLoai = $xepLoaiMoi;
                $diemRenLuyen->NgayCapNhat = $now;
                $diemRenLuyen->save();
            }
        }

        // 9. Trả về view
        return view('sinhvien.diem_ren_luyen.index', [
            'allHocKys' => $allHocKys,
            'currentHocKy' => $currentHocKy,
            'diemRenLuyen' => $diemRenLuyen, // Dòng điểm gốc từ CSDL
            'cacHoatDongDaThamGia' => $cacHoatDongDaThamGia,
            'diemGoc' => $diemGoc,
            'diemCongThem' => $diemCongThem,
            'diemTruDi' => $diemTruDi, // <-- TRUYỀN BIẾN MỚI
            'tongDiemMoi' => $tongDiemMoi,
            'xepLoaiMoi' => $xepLoaiMoi
        ]);
    }

    /**
     * Hàm helper để lấy xếp loại dựa trên điểm
     */
    private function getXepLoai($diem)
    {
        if ($diem >= 90) return 'Xuất Sắc';
        if ($diem >= 80) return 'Giỏi';
        if ($diem >= 70) return 'Khá';
        if ($diem >= 60) return 'Trung Bình';
        if ($diem >= 50) return 'Yếu';
        return 'Kém';
    }
}