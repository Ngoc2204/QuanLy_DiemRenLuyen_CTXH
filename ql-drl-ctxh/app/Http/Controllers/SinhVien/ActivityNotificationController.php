<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HoatDongDRL;
use App\Models\HoatDongCTXH;
use App\Models\DangKyHoatDongDrl;
use App\Models\DangKyHoatDongCtxh;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\ThanhToan;

class ActivityNotificationController extends Controller
{
    /**
     * === (HÀM NÀY ĐÃ ĐÚNG) ===
     * Hiển thị danh sách hoạt động DRL & CTXH còn hạn.
     * Hoạt động "Địa chỉ đỏ" sẽ được gom nhóm theo (Đợt + Địa điểm).
     */
    public function index()
    {
        $now  = now();
        $mssv = Auth::user()->TenDangNhap;
        $perPage = 5; // Số item mỗi trang

        // 1. Lấy Hoạt động DRL (với phân trang)
        $allDRL = HoatDongDRL::with(['quydinh', 'hocKy'])
            ->withCount('dangky')
            ->where('ThoiGianKetThuc', '>', $now)
            ->withExists(['dangky as is_registered' => function ($query) use ($mssv) {
                $query->where('MSSV', $mssv);
            }])
            ->orderBy('ThoiGianBatDau')
            ->get();

        // Phân trang thủ công
        $drlPage = request('page_drl', 1);
        $drlPage = max(1, $drlPage);
        $drlOffset = ($drlPage - 1) * $perPage;
        $drlItems = $allDRL->slice($drlOffset, $perPage);
        
        $activitiesDRL = new \Illuminate\Pagination\LengthAwarePaginator(
            $drlItems->values(),
            $allDRL->count(),
            $perPage,
            $drlPage,
            [
                'path' => route('sinhvien.thongbao_hoatdong'),
                'query' => request()->query(),
                'pageName' => 'page_drl',
            ]
        );

        // 2. Lấy TẤT CẢ Hoạt động CTXH
        $allActivitiesCTXH = HoatDongCTXH::with(['quydinh', 'dotDiaChiDo', 'diaDiem'])
            ->withCount('dangky') // Đếm số lượng của từng ngày
            ->where('ThoiGianKetThuc', '>', $now)
            ->withExists(['dangky as is_registered' => function ($query) use ($mssv) {
                $query->where('MSSV', $mssv);
            }])
            ->orderBy('ThoiGianBatDau') // Sắp xếp các ngày theo thứ tự
            ->get();

        // 3. Phân loại Hoạt động CTXH
        list($diaChiDoActivities, $normalActivities) = $allActivitiesCTXH->partition(function ($activity) {
            return $activity->LoaiHoatDong === 'Địa chỉ đỏ' && $activity->dot_id && $activity->diadiem_id;
        });

        // 4. Gom nhóm các hoạt động "Địa chỉ đỏ"
        $groupedActivities = $diaChiDoActivities->groupBy(function ($activity) {
            return $activity->dot_id . '_' . $activity->diadiem_id;
        });

        // 5. Phân trang hoạt động CTXH (ưu tiên Địa chỉ đỏ)
        $ctxhItemsPerPage = $perPage;
        $ctxhPage = request('page_ctxh', 1);
        $ctxhPage = max(1, $ctxhPage); // Đảm bảo trang >= 1

        // Tính toán offset
        $ctxhOffset = ($ctxhPage - 1) * $ctxhItemsPerPage;
        
        // Gộp 2 collection theo thứ tự ưu tiên
        $allCtxhItems = new \Illuminate\Support\Collection();
        foreach ($groupedActivities as $group) {
            $allCtxhItems->push(['type' => 'group', 'data' => $group]);
        }
        foreach ($normalActivities as $activity) {
            $allCtxhItems->push(['type' => 'normal', 'data' => $activity]);
        }

        // Lấy items cho trang hiện tại
        $ctxhItems = $allCtxhItems->slice($ctxhOffset, $ctxhItemsPerPage);
        
        // Tạo LengthAwarePaginator
        $paginatedCtxh = new \Illuminate\Pagination\LengthAwarePaginator(
            $ctxhItems->values(),
            $allCtxhItems->count(),
            $ctxhItemsPerPage,
            $ctxhPage,
            [
                'path' => route('sinhvien.thongbao_hoatdong'),
                'query' => request()->query(),
                'pageName' => 'page_ctxh',
            ]
        );

        // 6. Truyền biến sang View:
        return view('sinhvien.thongbao_hoatdong.index', compact(
            'activitiesDRL',      // Danh sách HĐ Rèn luyện (đã phân trang)
            'groupedActivities',  // Danh sách ĐÃ GOM NHÓM của Địa chỉ đỏ (dùng cho render)
            'normalActivities',   // Danh sách HĐ CTXH thường (dùng cho render)
            'paginatedCtxh'       // Hoạt động CTXH đã phân trang
        ));
    }

    /**
     * === (HÀM NÀY ĐÃ ĐÚNG) ===
     * Đăng ký hoạt động DRL (Chuyển sang 'Chờ duyệt').
     */
    public function registerDRL(Request $request, $maHoatDong)
    {
        $mssv = Auth::user()->TenDangNhap;
        $now  = now();

        $activity = HoatDongDRL::withCount('dangky')->find($maHoatDong);

        if (!$activity) {
            return back()->with('error', 'Hoạt động không tồn tại.');
        }

        // 1. Kiểm tra hạn đăng ký
        if ($now->gt($activity->ThoiGianBatDau)) {
            return back()->with('error', 'Đã quá hạn đăng ký cho hoạt động này.');
        }

        // 2. Gọi hàm kiểm tra logic chung (đã đơn giản hóa)
        $validationError = $this->validateRegistration($activity, DangKyHoatDongDrl::class, $mssv);

        if ($validationError) {
            return back()->with('error', $validationError);
        }

        try {
            DangKyHoatDongDrl::create([
                'MSSV'              => $mssv,
                'MaHoatDong'        => $maHoatDong,
                'NgayDangKy'        => $now,
                'TrangThaiDangKy'   => 'Chờ duyệt', // <-- ĐÃ SỬA
            ]);

            return back()->with('success', 'Đăng ký hoạt động "' . $activity->TenHoatDong . '" thành công (chờ duyệt).');
        } catch (\Throwable $e) {
            Log::error('LỖI ĐĂNG KÝ DRL: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi hệ thống. Vui lòng thử lại.');
        }
    }

    /**
     * === SỬA (DỌN DẸP THEO MÔ HÌNH MỚI) ===
     * Đăng ký hoạt động CTXH (mặc định chờ duyệt).
     * (Hàm này giờ áp dụng CHUNG cho cả CTXH thường và Địa chỉ đỏ)
     */
    public function registerCTXH(Request $request, $maHoatDong)
    {
        $mssv = Auth::user()->TenDangNhap;
        $now  = now();

        // Load kèm 'diaDiem' để lấy giá tiền (CHO BƯỚC THANH TOÁN)
        $activity = HoatDongCTXH::withCount('dangky')->with('diaDiem')->find($maHoatDong);

        if (!$activity) {
            return back()->with('error', 'Hoạt động không tồn tại.');
        }

        // 1. Kiểm tra hạn đăng ký (chung)
        if ($now->gt($activity->ThoiGianBatDau)) {
            return back()->with('error', 'Đã quá hạn đăng ký cho hoạt động này.');
        }

        // 2. Gọi hàm validate chung (đã đơn giản hóa)
        $validationError = $this->validateRegistration($activity, DangKyHoatDongCtxh::class, $mssv);

        if ($validationError) {
            return back()->with('error', $validationError);
        }

        // === BẮT ĐẦU LOGIC THANH TOÁN ===

        // 3. Kiểm tra xem hoạt động này có phí không
        $giaTien = $activity->diaDiem?->GiaTien ?? 0;

        // Dùng DB Transaction để đảm bảo tạo Đăng ký và Hóa đơn cùng lúc
        try {
            DB::beginTransaction();

            $thanhToan = null;
            // Nếu có phí thì "Chờ thanh toán", nếu miễn phí thì "Chờ duyệt"
            $trangThaiDangKy = ($giaTien > 0) ? 'Chờ thanh toán' : 'Chờ duyệt';

            // 4. TẠO ĐƠN ĐĂNG KÝ TRƯỚC
            $dangKy = DangKyHoatDongCtxh::create([
                'MSSV'              => $mssv,
                'MaHoatDong'        => $maHoatDong,
                'NgayDangKy'        => $now,
                'TrangThaiDangKy'   => $trangThaiDangKy,
                // 'thanh_toan_id' sẽ được gán sau
            ]);

            // 5. NẾU CÓ PHÍ (GiaTien > 0), TẠO HÓA ĐƠN
            if ($giaTien > 0) {

                $thanhToan = ThanhToan::create([
                    'MSSV'      => $mssv,
                    'TongTien'  => $giaTien,
                    'TrangThai' => 'Chờ thanh toán',
                    'PhuongThuc' => null,
                ]);

                // Cập nhật lại đơn đăng ký với ID thanh toán
                $dangKy->thanh_toan_id = $thanhToan->id;
                $dangKy->save();
            }

            DB::commit(); // Hoàn tất giao dịch

            // 6. Chuyển hướng
            if ($thanhToan) {
                // (Giả sử bạn có route tên là 'sinhvien.thanhtoan.show')

                // <-- KÍCH HOẠT DÒNG NÀY
                return redirect()->route('sinhvien.thanhtoan.show', $thanhToan->id)
                    ->with('success', 'Đăng ký thành công! Vui lòng hoàn tất thanh toán.');

                // <-- BỎ DÒNG NÀY
                // return back()->with('success', 'Đăng ký thành công! Vui lòng thanh toán (ID: '.$thanhToan->id.').');
            }

            // Nếu miễn phí, quay lại như cũ
            return back()->with('success', 'Đăng ký hoạt động "' . $activity->TenHoatDong . '" thành công (chờ duyệt).');
        } catch (\Throwable $e) {
            DB::rollBack(); // Hoàn tác nếu có lỗi
            Log::error('LỖI ĐĂNG KÝ CTXH (THANH TOÁN): ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi hệ thống khi tạo đơn. Vui lòng thử lại.');
        }
    }

    /**
     * === (Hàm này giữ nguyên, không đổi) ===
     * Hàm private để xử lý logic validation chung.
     */
    private function validateRegistration(Model $activity, string $registrationModel, string $mssv): ?string
    {
        // 1. Kiểm tra đã đăng ký hoạt động này chưa
        $alreadyRegistered = $registrationModel::where('MSSV', $mssv)
            ->where('MaHoatDong', $activity->MaHoatDong)
            ->exists();

        if ($alreadyRegistered) {
            return 'Bạn đã đăng ký hoạt động này rồi.';
        }

        // 2. Kiểm tra số lượng (dùng 'dangky_count' đã load)
        if ($activity->dangky_count >= $activity->SoLuong) {
            return 'Hoạt động này đã đủ số lượng.';
        }

        return null; // Hợp lệ
    }
}
