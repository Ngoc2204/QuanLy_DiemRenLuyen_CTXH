<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\SinhVien;
use App\Models\HoatDongDrl;
use App\Models\HoatDongCtxh;
use App\Models\DiemRenLuyen; // <-- Model quan trọng
use App\Models\DiemCtxh;
use App\Models\DangKyHoatDongDrl;
use App\Models\DangKyHoatDongCtxh;
use App\Models\HocKy; // <-- Model quan trọng

class DashboardController extends Controller
{
    public function dashboard()
    {
        // 1. Lấy MSSV từ user đang đăng nhập
        $mssv = Auth::user()->TenDangNhap;

        // 2. Lấy thông tin sinh viên và các quan hệ (lớp, khoa)
        $student = SinhVien::with(['lop.khoa'])->where('MSSV', $mssv)->firstOrFail();

        // 3. Lấy học kỳ hiện tại
        $today = Carbon::today();
        $currentHocKy = HocKy::where('NgayBatDau', '<=', $today)
            ->where('NgayKetThuc', '>=', $today)
            ->first();
        
        // Fallback: Nếu không trong học kỳ, lấy học kỳ gần nhất
        if (!$currentHocKy) {
            $currentHocKy = HocKy::orderBy('NgayKetThuc', 'desc')->first();
        }

        // 4. Lấy các thẻ thống kê
        $now = Carbon::now();

        // Đếm "Thông báo hoạt động" (còn hạn)
        $countDRL = HoatDongDrl::where('ThoiGianKetThuc', '>', $now)->count();
        $countCTXH = HoatDongCtxh::where('ThoiGianKetThuc', '>', $now)->count();
        $notifications_count = $countDRL + $countCTXH;

        // Đếm "Hoạt động tuần này" (ĐÃ ĐĂNG KÝ)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weekly_drl = DangKyHoatDongDrl::where('MSSV', $mssv)
            ->whereHas('hoatdong', function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('ThoiGianBatDau', [$startOfWeek, $endOfWeek]);
            })->count();

        $weekly_ctxh = DangKyHoatDongCtxh::where('MSSV', $mssv)
            ->whereHas('hoatdong', function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('ThoiGianBatDau', [$startOfWeek, $endOfWeek]);
            })->count();
            
        // --- ĐÃ SỬA LỖI LOGIC TẠI ĐÂY ---
        // (Sửa $weeklyDrlCount thành $weekly_drl)
        $weekly_activities = $weekly_drl + $weekly_ctxh; 
        // --- KẾT THÚC SỬA LOGIC ---


        // 5. Lấy điểm rèn luyện (cho học kỳ hiện tại)
        // --- LOGIC firstOrCreate (KHỞI TẠO 70Đ) ---
        
        if ($currentHocKy) {
            $training_score_data = DiemRenLuyen::firstOrCreate(
                [
                    // Điều kiện tìm:
                    'MSSV' => $mssv,
                    'MaHocKy' => $currentHocKy->MaHocKy
                ],
                [
                    // Dữ liệu tạo mới nếu không tìm thấy:
                    'TongDiem' => 70, // Điểm khởi đầu
                    'XepLoai' => 'Khá', // 70 điểm là Xếp loại Khá
                    'NgayCapNhat' => $now
                ]
            );

            // Gán giá trị (bây giờ $training_score_data chắc chắn tồn tại)
            $training_score = $training_score_data->TongDiem;
            $training_rank = $training_score_data->XepLoai;

        } else {
            // Trường hợp không tìm thấy học kỳ nào
            $training_score = 0;
            $training_rank = 'N/A';
        }
        // --- KẾT THÚC LOGIC 70Đ ---


        // 6. Lấy điểm CTXH (toàn khoá)
        $social_score_data = DiemCtxh::where('MSSV', $mssv)->first();
        $social_score = $social_score_data->TongDiem ?? 0;

        // 7. Kiểm tra đã có hoạt động địa chỉ đỏ chưa
        $has_red_activity = DangKyHoatDongCtxh::where('MSSV', $mssv)
            ->where('TrangThaiDangKy', 'Đã duyệt') // Hoặc 'Đã tham gia' tuỳ logic của bạn
            ->whereHas('hoatdong', function ($query) {
                $query->where('LoaiHoatDong', 'Địa chỉ đỏ');
            })
            ->exists();

        // 8. Lấy danh sách hoạt động ĐRL đã đăng ký (của học kỳ hiện tại)
        $activities = DangKyHoatDongDrl::with(['hoatdong.quydinh'])
            ->where('MSSV', $mssv)
            ->whereHas('hoatdong', function ($query) use ($currentHocKy) {
                if ($currentHocKy) {
                    $query->where('MaHocKy', $currentHocKy->MaHocKy);
                }
            })
            ->get();

        // 9. Lấy danh sách hoạt động CTXH đã đăng ký (toàn khoá)
        $social_activities = DangKyHoatDongCtxh::with(['hoatdong.quydinh'])
            ->where('MSSV', $mssv)
            ->get();

        // 10. Chuẩn bị dữ liệu cho Chart JS
        // Dữ liệu Biểu đồ ĐRL (Bar chart)
        $drl_scores = DiemRenLuyen::where('MSSV', $mssv)
            ->join('hocky', 'diemrenluyen.MaHocKy', '=', 'hocky.MaHocKy')
            ->join('namhoc', 'hocky.MaNamHoc', '=', 'namhoc.MaNamHoc')
            ->select('namhoc.TenNamHoc', 'hocky.TenHocKy', 'diemrenluyen.TongDiem')
            ->orderBy('hocky.NgayBatDau')
            ->get();

        $training_chart_js = ['labels' => [], 'hk1_data' => [], 'hk2_data' => []];
        $years = $drl_scores->groupBy('TenNamHoc');

        foreach ($years as $year_name => $scores) {
            $training_chart_js['labels'][] = $year_name;
            $training_chart_js['hk1_data'][] = $scores->firstWhere(fn($s) => str_contains($s->TenHocKy, 'Học kỳ 1'))->TongDiem ?? null;
            $training_chart_js['hk2_data'][] = $scores->firstWhere(fn($s) => str_contains($s->TenHocKy, 'Học kỳ 2'))->TongDiem ?? null;
        }

        // Dữ liệu Biểu đồ CTXH (Doughnut chart)
        $social_chart_js = [
            'dat' => $social_score,
            'thieu' => max(0, 170 - $social_score) // 170 là điểm tối đa
        ];

        // 11. Trả về view với tất cả dữ liệu
        return view('sinhvien.dashboard', compact(
            'student',
            'currentHocKy',
            'notifications_count',
            'weekly_activities',
            'training_score',
            'training_rank',
            'social_score',
            'has_red_activity',
            'activities',
            'social_activities',
            'training_chart_js',
            'social_chart_js'
        ));
    }
}