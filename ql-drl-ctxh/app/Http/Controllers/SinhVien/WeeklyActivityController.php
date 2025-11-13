<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DangKyHoatDongDrl;
use App\Models\DangKyHoatDongCtxh;

class WeeklyActivityController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy MSSV của sinh viên đang đăng nhập
        $mssv = Auth::user()->TenDangNhap;

        // 2. Xử lý ngày: Lấy ngày từ query ?date=... hoặc dùng ngày hôm nay
        try {
            // Carbon::setLocale('vi'); // Nên đặt ở AppServiceProvider
            $targetDate = Carbon::parse($request->input('date', 'today'));
        } catch (\Exception $e) {
            $targetDate = Carbon::today();
        }

        // 3. Tính toán các mốc thời gian của tuần
        $startOfWeek = $targetDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $targetDate->copy()->endOfWeek(Carbon::SUNDAY);

        // 4. Lấy dữ liệu đăng ký
        // Lấy các HĐRL đã đăng ký VÀ hoạt động đó diễn ra trong tuần
        $drlRegistrations = DangKyHoatDongDrl::where('MSSV', $mssv)
            ->whereHas('hoatdong', function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('ThoiGianBatDau', [$startOfWeek, $endOfWeek]);
            })
            ->with('hoatdong') // Tải sẵn thông tin hoạt động
            ->get();

        // Lấy các HĐCTXH đã đăng ký VÀ hoạt động đó diễn ra trong tuần
        $ctxhRegistrations = DangKyHoatDongCtxh::where('MSSV', $mssv)
            ->whereHas('hoatdong', function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('ThoiGianBatDau', [$startOfWeek, $endOfWeek]);
            })
            ->with('hoatdong') // Tải sẵn thông tin hoạt động
            ->get();

        // 5. Gộp và sắp xếp tất cả hoạt động
        $allActivities = collect([]);
        foreach ($drlRegistrations as $reg) {
            $allActivities->push([
                'type' => 'DRL',
                'hoatdong' => $reg->hoatdong,
                'trang_thai' => $reg->TrangThaiDangKy,
                'trang_thai_tham_gia' => $reg->TrangThaiThamGia,
                'check_in_at' => $reg->CheckInAt, // Thời gian Check In
                'check_out_at' => $reg->CheckOutAt, // Thời gian Check Out
            ]);
        }
        foreach ($ctxhRegistrations as $reg) {
            $allActivities->push([
                'type' => 'CTXH',
                'hoatdong' => $reg->hoatdong,
                'trang_thai' => $reg->TrangThaiDangKy,
                'trang_thai_tham_gia' => $reg->TrangThaiThamGia,
                'check_in_at' => $reg->CheckInAt, // Thời gian Check In
                'check_out_at' => $reg->CheckOutAt, // Thời gian Check Out
            ]);
        }

        // Sắp xếp theo thời gian bắt đầu
        $sortedActivities = $allActivities->sortBy(function ($item) {
            return $item['hoatdong']->ThoiGianBatDau;
        });

        // 6. Nhóm các hoạt động lại theo ngày (Thứ 2, Thứ 3...)
        $groupedActivities = $sortedActivities->groupBy(function ($item) {
            // 'w' trả về 1 (Thứ 2) đến 7 (Chủ nhật)
            return $item['hoatdong']->ThoiGianBatDau->format('w');
        });

        // 7. Tạo mảng 7 ngày cho View
        $daysOfWeek = [];
        $currentDay = $startOfWeek->copy();
        for ($i = 0; $i < 7; $i++) {
            $dayKey = $currentDay->format('w'); // 1-7
            $daysOfWeek[] = [
                'date' => $currentDay->copy(),
                'activities' => $groupedActivities->get($dayKey, collect()) // Lấy HĐ cho ngày này
            ];
            $currentDay->addDay();
        }

        // 8. Tạo link cho tuần trước/sau
        $prevWeek = $targetDate->copy()->subWeek();
        $nextWeek = $targetDate->copy()->addWeek();

        // 9. Trả về View
        return view('sinhvien.lich_tuan.index', compact(
            'daysOfWeek',
            'startOfWeek',
            'endOfWeek',
            'prevWeek',
            'nextWeek'
        ));
    }
}
