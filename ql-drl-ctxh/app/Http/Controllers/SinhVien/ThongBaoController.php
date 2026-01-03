<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\DangKyHoatDongDrl;
use App\Models\DangKyHoatDongCtxh;
use App\Models\DiemRenLuyen;
use App\Models\HoatDongDRL; // Dùng cho nhắc nhở
use App\Models\HoatDongCTXH; // Dùng cho nhắc nhở

class ThongBaoController extends Controller
{
    /**
     * Hiển thị danh sách các thông báo cá nhân hóa của sinh viên.
     */
    public function index(Request $request)
    {
        $mssv = Auth::user()->TenDangNhap;
        $now = Carbon::now();
        $allThongBao = collect([]);

        // --- A. THÔNG BÁO DUYỆT ĐĂNG KÝ (DRL & CTXH) VÀ PHẢN HỒI ---
        
        // 1. Đăng ký Hoạt động Rèn Luyện (DRL)
        $dangKyDrl = DangKyHoatDongDrl::with('hoatdong')
            ->where('MSSV', $mssv)
            ->whereIn('TrangThaiDangKy', ['Đã duyệt', 'Bị từ chối'])
            ->orderBy('NgayDangKy', 'desc')
            ->get();
            
        foreach ($dangKyDrl as $dk) {
            $status = $dk->TrangThaiDangKy;
            $allThongBao->push((object)[
                'Loai' => 'DRL',
                'Ma' => 'DK-' . $dk->MaDangKy,
                'TieuDe' => ($status == 'Đã duyệt' ? 'Đăng ký thành công:' : 'Đăng ký bị từ chối:') . ' ' . optional($dk->hoatdong)->TenHoatDong,
                'NoiDung' => $status == 'Đã duyệt' ? 
                             'Đơn đăng ký hoạt động đã được chấp thuận. Bạn cần tham gia hoạt động để được cộng điểm.' :
                             'Đơn đăng ký bị từ chối. Lý do: ' . ($dk->PhanHoi ?? 'Không rõ lý do.'),
                'ThoiGian' => $dk->updated_at,
                'Action' => 'Xem chi tiết đăng ký',
                'TrangThai' => $status,
            ]);
        }

        // 2. Đăng ký Công tác Xã hội (CTXH)
        $dangKyCtxh = DangKyHoatDongCtxh::with('hoatdong')
            ->where('MSSV', $mssv)
            ->whereIn('TrangThaiDangKy', ['Đã duyệt', 'Bị từ chối'])
            ->orderBy('NgayDangKy', 'desc')
            ->get();

        foreach ($dangKyCtxh as $dk) {
            $status = $dk->TrangThaiDangKy;
            $allThongBao->push((object)[
                'Loai' => 'CTXH',
                'Ma' => 'DK-CTXH-' . $dk->MaDangKy,
                'TieuDe' => ($status == 'Đã duyệt' ? 'Đăng ký thành công:' : 'Đăng ký bị từ chối:') . ' ' . optional($dk->hoatdong)->TenHoatDong,
                'NoiDung' => $status == 'Đã duyệt' ? 
                             'Đơn đăng ký công tác xã hội đã được chấp thuận.' :
                             'Đơn đăng ký bị từ chối. Lý do: ' . ($dk->PhanHoi ?? 'Không rõ lý do.'),
                'ThoiGian' => $dk->updated_at,
                'Action' => 'Xem chi tiết đăng ký',
                'TrangThai' => $status,
            ]);
        }
        
        // --- B. THÔNG BÁO ĐIỀU CHỈNH ĐIỂM RÈN LUYỆN (GIỮ NGUYÊN) ---
        
        // Ta giả định điểm rèn luyện chỉ được cập nhật khi có thay đổi đáng kể
        $diemRenLuyen = DiemRenLuyen::where('MSSV', $mssv)
             // Lưu ý: Thêm with('hocky') để có thể dùng optional($dr->hocky)
             ->with('hocky') 
            ->whereDate('NgayCapNhat', '>=', $now->subDays(30)) // Lấy các cập nhật trong 30 ngày gần nhất
            ->orderBy('NgayCapNhat', 'desc')
            ->limit(5)
            ->get();
            
        foreach ($diemRenLuyen as $dr) {
            // Logic giả định: Nếu điểm khác 70 (điểm gốc), coi là đã điều chỉnh/chốt
            if ($dr->TongDiem != 70) {
                 $allThongBao->push((object)[
                    'Loai' => 'DIEM',
                    'Ma' => 'DRL-' . $dr->MaDiemRenLuyen,
                    'TieuDe' => 'Cập nhật Điểm Rèn Luyện Học kỳ: ' . optional($dr->hocky)->TenHocKy,
                    'NoiDung' => "Điểm rèn luyện của bạn đã được chốt/cập nhật là **{$dr->TongDiem} điểm**, Xếp loại **{$dr->XepLoai}**.",
                    'ThoiGian' => $dr->NgayCapNhat,
                    'Action' => 'Xem chi tiết DRL',
                    'TrangThai' => 'Đã chốt',
                ]);
            }
        }


        // --- C. THÔNG BÁO NHẮC NHỞ HOẠT ĐỘNG (GIỮ NGUYÊN) ---
        
        // 3. Nhắc nhở hoạt động DRL
        $nhacNhoDrl = DangKyHoatDongDrl::with('hoatdong')
            ->where('MSSV', $mssv)
            ->where('TrangThaiDangKy', 'Đã duyệt')
            ->whereHas('hoatdong', function ($query) use ($now) {
                // Hoạt động bắt đầu trong khoảng 1 ngày tới
                $tomorrow = $now->copy()->addDay()->endOfDay();
                $query->whereBetween('ThoiGianBatDau', [$now, $tomorrow]);
            })
            ->get();
            
        foreach ($nhacNhoDrl as $dk) {
            $allThongBao->push((object)[
                'Loai' => 'NHACNHO',
                'Ma' => 'NN-DRL-' . $dk->MaDangKy,
                'TieuDe' => 'Nhắc nhở: Hoạt động sắp diễn ra',
                'NoiDung' => 'Hoạt động **' . optional($dk->hoatdong)->TenHoatDong . '** sẽ diễn ra vào ' . Carbon::parse(optional($dk->hoatdong)->ThoiGianBatDau)->format('H:i, d/m/Y') . '. Hãy chuẩn bị tham gia!',
                'ThoiGian' => $now,
                'Action' => 'Xem chi tiết hoạt động',
                'TrangThai' => 'Sắp diễn ra',
            ]);
        }
        
        // 4. Nhắc nhở hoạt động CTXH (tương tự)
        $nhacNhoCtxh = DangKyHoatDongCtxh::with('hoatdong')
            ->where('MSSV', $mssv)
            ->where('TrangThaiDangKy', 'Đã duyệt')
            ->whereHas('hoatdong', function ($query) use ($now) {
                $tomorrow = $now->copy()->addDay()->endOfDay();
                $query->whereBetween('ThoiGianBatDau', [$now, $tomorrow]);
            })
            ->get();
            
        foreach ($nhacNhoCtxh as $dk) {
            $allThongBao->push((object)[
                'Loai' => 'NHACNHO',
                'Ma' => 'NN-CTXH-' . $dk->MaDangKy,
                'TieuDe' => 'Nhắc nhở: Hoạt động CTXH sắp diễn ra',
                'NoiDung' => 'Hoạt động CTXH **' . optional($dk->hoatdong)->TenHoatDong . '** sẽ diễn ra vào ' . Carbon::parse(optional($dk->hoatdong)->ThoiGianBatDau)->format('H:i, d/m/Y') . '. Hãy chuẩn bị tham gia!',
                'ThoiGian' => $now,
                'Action' => 'Xem chi tiết hoạt động',
                'TrangThai' => 'Sắp diễn ra',
            ]);
        }


        // --- D. KẾT HỢP, SẮP XẾP VÀ PHÂN TRANG (GIỮ NGUYÊN) ---
        
        $sortedThongBao = $allThongBao->sortByDesc('ThoiGian');

        // Phân trang thủ công
        $perPage = 5;
        $currentPage = $request->input('page', 1);
        $currentPageItems = $sortedThongBao->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $paginatedThongBao = new LengthAwarePaginator(
            $currentPageItems, 
            $sortedThongBao->count(), 
            $perPage, 
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 7. Trả về view
        return view('sinhvien.tintuc.index', [ // <-- Đổi tên view nếu cần
            'thongBaos' => $paginatedThongBao,
            'pageTitle' => 'Thông Báo & Lịch Sử Cá Nhân', // Dùng cho tiêu đề
        ]);
    }

    /**
     * Hiển thị chi tiết một thông báo
     */
    public function show($id)
    {
        $mssv = Auth::user()->TenDangNhap;
        $now = Carbon::now();
        $allThongBao = collect([]);

        // Tạo toàn bộ thông báo như trong index
        // 1. Đăng ký DRL
        $dangKyDrl = DangKyHoatDongDrl::with('hoatdong')
            ->where('MSSV', $mssv)
            ->whereIn('TrangThaiDangKy', ['Đã duyệt', 'Bị từ chối'])
            ->get();
            
        foreach ($dangKyDrl as $dk) {
            $status = $dk->TrangThaiDangKy;
            $allThongBao->push((object)[
                'id' => 'DK-' . $dk->MaDangKy,
                'Loai' => 'DRL',
                'Ma' => 'DK-' . $dk->MaDangKy,
                'TieuDe' => ($status == 'Đã duyệt' ? 'Đăng ký thành công:' : 'Đăng ký bị từ chối:') . ' ' . optional($dk->hoatdong)->TenHoatDong,
                'NoiDung' => $status == 'Đã duyệt' ? 
                             'Đơn đăng ký hoạt động đã được chấp thuận. Bạn cần tham gia hoạt động để được cộng điểm.' :
                             'Đơn đăng ký bị từ chối. Lý do: ' . ($dk->PhanHoi ?? 'Không rõ lý do.'),
                'ThoiGian' => $dk->updated_at,
                'Action' => 'Xem chi tiết đăng ký',
                'TrangThai' => $status,
                'data' => $dk
            ]);
        }

        // 2. Đăng ký CTXH
        $dangKyCtxh = DangKyHoatDongCtxh::with('hoatdong')
            ->where('MSSV', $mssv)
            ->whereIn('TrangThaiDangKy', ['Đã duyệt', 'Bị từ chối'])
            ->get();

        foreach ($dangKyCtxh as $dk) {
            $status = $dk->TrangThaiDangKy;
            $allThongBao->push((object)[
                'id' => 'DK-CTXH-' . $dk->MaDangKy,
                'Loai' => 'CTXH',
                'Ma' => 'DK-CTXH-' . $dk->MaDangKy,
                'TieuDe' => ($status == 'Đã duyệt' ? 'Đăng ký thành công:' : 'Đăng ký bị từ chối:') . ' ' . optional($dk->hoatdong)->TenHoatDong,
                'NoiDung' => $status == 'Đã duyệt' ? 
                             'Đơn đăng ký công tác xã hội đã được chấp thuận.' :
                             'Đơn đăng ký bị từ chối. Lý do: ' . ($dk->PhanHoi ?? 'Không rõ lý do.'),
                'ThoiGian' => $dk->updated_at,
                'Action' => 'Xem chi tiết đăng ký',
                'TrangThai' => $status,
                'data' => $dk
            ]);
        }

        // 3. Điểm RDL
        $diemRenLuyen = DiemRenLuyen::where('MSSV', $mssv)
            ->with('hocky') 
            ->whereDate('NgayCapNhat', '>=', $now->subDays(30))
            ->get();
            
        foreach ($diemRenLuyen as $dr) {
            if ($dr->TongDiem != 70) {
                 $allThongBao->push((object)[
                    'id' => 'DRL-' . $dr->MaDiemRenLuyen,
                    'Loai' => 'DIEM',
                    'Ma' => 'DRL-' . $dr->MaDiemRenLuyen,
                    'TieuDe' => 'Cập nhật Điểm Rèn Luyện Học kỳ: ' . optional($dr->hocky)->TenHocKy,
                    'NoiDung' => "Điểm rèn luyện của bạn đã được chốt/cập nhật là **{$dr->TongDiem} điểm**, Xếp loại **{$dr->XepLoai}**.",
                    'ThoiGian' => $dr->NgayCapNhat,
                    'Action' => 'Xem chi tiết DRL',
                    'TrangThai' => 'Đã chốt',
                    'data' => $dr
                ]);
            }
        }

        // Tìm thông báo theo id
        $thongBao = $allThongBao->firstWhere('id', $id);

        if (!$thongBao) {
            abort(404, 'Không tìm thấy thông báo');
        }

        return view('sinhvien.tintuc.show', compact('thongBao'));
    }
}
