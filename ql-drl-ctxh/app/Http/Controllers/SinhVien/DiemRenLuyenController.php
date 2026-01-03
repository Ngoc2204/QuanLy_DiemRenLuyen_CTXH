<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HocKy;
use App\Models\DiemRenLuyen;
use App\Models\DangKyHoatDongDrl;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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

        // 2. Xác định học kỳ đang xem (Giữ nguyên)
        $selectedHocKyMa = $request->input('hocky');
        $currentHocKy = null;

        if ($selectedHocKyMa) {
            $currentHocKy = HocKy::find($selectedHocKyMa);
        } else {
            $currentHocKy = HocKy::where('NgayBatDau', '<=', $now)
                                 ->where('NgayKetThuc', '>=', $now)
                                 ->first() 
                                 ?? $allHocKys->first();
        }
        
        // Khởi tạo các biến
        $diemRenLuyen = null;
        // Danh sách tất cả các mục ảnh hưởng đến điểm (Điểm cơ bản, hoạt động, điều chỉnh)
        $cacMucAnhHuongDenDiem = collect(); 
        $tongDiemMoi = $diemGoc; 
        $xepLoaiMoi = $this->getXepLoai($diemGoc);

        // 3. Chỉ tính toán nếu có một học kỳ hợp lệ
        if ($currentHocKy) {
            
            // 4. Lấy (hoặc tạo) điểm DRL gốc
            $diemRenLuyen = DiemRenLuyen::firstOrCreate(
                [
                    'MSSV' => $mssv,
                    'MaHocKy' => $currentHocKy->MaHocKy
                ],
                [
                    'TongDiem' => $diemGoc,
                    'XepLoai' => 'Khá',
                    'NgayCapNhat' => $now
                ]
            );

            $tongDiemDaLuu = $diemRenLuyen->TongDiem;
            
            // 5. Lấy TẤT CẢ đăng ký trong học kỳ này (tất cả trạng thái)
            $cacDangKyTrongKy = DangKyHoatDongDrl::with('hoatdong.quydinh')
                ->where('MSSV', $mssv)
                // Bỏ filter TrangThaiDangKy để lấy tất cả (Chờ duyệt, Đã duyệt, Từ chối)
                ->whereHas('hoatdong', function ($query) use ($currentHocKy) {
                    $query->where('MaHocKy', $currentHocKy->MaHocKy);
                })
                ->get();

            // Tính điểm tự động để phát hiện điều chỉnh thủ công
            $diemCongTuHoatDong = 0;
            foreach ($cacDangKyTrongKy as $dangKy) {
                // Chỉ tính điểm nếu: (Đã duyệt OR Đã xác nhận) AND Đã tham gia
                $trangThaiHopLe = in_array($dangKy->TrangThaiDangKy, ['Đã duyệt', 'Đã xác nhận']);
                if ($trangThaiHopLe && 
                    $dangKy->TrangThaiThamGia === 'Đã tham gia' && 
                    $dangKy->hoatdong && 
                    $dangKy->hoatdong->quydinh) {
                    $diemCongTuHoatDong += $dangKy->hoatdong->quydinh->DiemNhan;
                }
            }
            $tongDiemTuDong = max(0, min(100, $diemGoc + $diemCongTuHoatDong));
            
            // 8. CẬP NHẬT ĐIỂM VÀ TÍNH ĐIỂM ĐIỀU CHỈNH
            $diemDieuChinhThuCong = 0;
            
            if ($tongDiemDaLuu != $tongDiemTuDong) {
                // Có can thiệp thủ công -> Giữ nguyên điểm đã lưu
                $tongDiemMoi = $tongDiemDaLuu;
                $diemDieuChinhThuCong = $tongDiemDaLuu - $tongDiemTuDong;
            } else {
                // Không có can thiệp thủ công -> Cập nhật điểm mới
                $diemRenLuyen->TongDiem = $tongDiemTuDong;
                $diemRenLuyen->XepLoai = $this->getXepLoai($tongDiemTuDong);
                $diemRenLuyen->NgayCapNhat = $now;
                $diemRenLuyen->save();
                
                $tongDiemMoi = $tongDiemTuDong;
            }

            $xepLoaiMoi = $this->getXepLoai($tongDiemMoi);


            // 9. CHUẨN BỊ DỮ LIỆU CHO BẢNG CHI TIẾT
            
            // 9.1. Thêm Dòng Điểm Cơ Bản (70)
            $cacMucAnhHuongDenDiem->push((object)[
                'loai' => 'goc',
                'ten' => 'Điểm rèn luyện Cơ bản (Quy định)',
                'ma' => 'BASE-SCORE',
                'diem' => $diemGoc,
                'ngay' => $diemRenLuyen->NgayCapNhat->format('d/m/Y'),
                'trang_thai' => 'Ghi nhận',
            ]);

            // 9.2. Thêm Dòng Điều Chỉnh Thủ Công (nếu có)
            if ($diemDieuChinhThuCong != 0) {
                $cacMucAnhHuongDenDiem->push((object)[
                    'loai' => 'dieu_chinh',
                    'ten' => 'Điều chỉnh thủ công từ Phòng CTSV',
                    'ma' => 'ADJ-' . $currentHocKy->MaHocKy,
                    'diem' => $diemDieuChinhThuCong,
                    'ngay' => $diemRenLuyen->NgayCapNhat->format('d/m/Y'),
                    'trang_thai' => 'Điều chỉnh',
                ]);
            }

            // 9.3. Thêm các Hoạt Động (có/không điểm)
            foreach ($cacDangKyTrongKy as $dangKy) {
                $diemNhan = optional(optional($dangKy->hoatdong)->quydinh)->DiemNhan ?? 0;
                
                // Xác định trạng thái hiển thị
                $trangThaiHienThi = 'Chưa đăng ký';
                if ($dangKy->TrangThaiDangKy === 'Chờ duyệt') {
                    $trangThaiHienThi = 'Chờ duyệt';
                } elseif ($dangKy->TrangThaiDangKy === 'Từ chối') {
                    $trangThaiHienThi = 'Từ chối';
                } elseif ($dangKy->TrangThaiDangKy === 'Đã duyệt') {
                    $trangThaiHienThi = $dangKy->TrangThaiThamGia; // 'Đã tham gia' hoặc 'Chưa tham gia'
                }
                
                $cacMucAnhHuongDenDiem->push((object)[
                    'loai' => 'hoat_dong',
                    'ten' => $dangKy->hoatdong->TenHoatDong ?? 'Không rõ tên',
                    'ma' => $dangKy->hoatdong->MaHoatDong ?? 'N/A',
                    // Chỉ tính điểm nếu Đã duyệt AND Đã tham gia
                    'diem' => ($dangKy->TrangThaiDangKy === 'Đã duyệt' && $dangKy->TrangThaiThamGia === 'Đã tham gia') ? $diemNhan : 0,
                    'ngay' => $dangKy->CheckOutAt ? \Carbon\Carbon::parse($dangKy->CheckOutAt)->format('d/m/Y') : (\Carbon\Carbon::parse($dangKy->NgayDangKy)->format('d/m/Y') ?? 'N/A'),
                    'trang_thai' => $trangThaiHienThi,
                ]);
            }
        }

        // 10. Trả về view
        return view('sinhvien.diem_ren_luyen.index', [
            'allHocKys' => $allHocKys,
            'currentHocKy' => $currentHocKy,
            'tongDiemMoi' => $tongDiemMoi,
            'xepLoaiMoi' => $xepLoaiMoi,
            'cacMucAnhHuongDenDiem' => $cacMucAnhHuongDenDiem->reverse()->values(), // Đảo ngược để Điểm cơ bản ở cuối
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