<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\StudentInterest;

/**
 * Onboarding Controller - Xử lý bắt buộc chọn sở thích cho sinh viên mới
 * 
 * Theo kỹ thuật: "Data Guarantee" (Mô tả 2.3)
 * - Khi sinh viên đăng nhập lần đầu, nếu chưa có dữ liệu sở thích
 * - Bắt buộc chọn ≥1 sở thích trước khi truy cập các chức năng khác
 */
class OnboardingController extends Controller
{
    /**
     * Kiểm tra xem sinh viên đã chọn sở thích chưa
     * Return: true nếu đã chọn, false nếu chưa
     */
    public function hasSelectedInterests()
    {
        $mssv = Auth::user()->TenDangNhap;
        $count = StudentInterest::where('MSSV', $mssv)->count();
        return $count > 0;
    }

    /**
     * Hiển thị trang chọn sở thích (Onboarding)
     * URL: /sinhvien/onboarding/interests
     */
    public function showInterestSelection()
    {
        $mssv = Auth::user()->TenDangNhap;
        
        // Kiểm tra: nếu đã chọn sở thích rồi -> redirect đến dashboard
        if ($this->hasSelectedInterests()) {
            return redirect()->route('sinhvien.home')
                ->with('info', 'Bạn đã chọn sở thích rồi!');
        }

        // Lấy danh sách tất cả sở thích
        $allInterests = DB::table('interests')
            ->orderBy('InterestName')
            ->get();

        return view('sinhvien.onboarding.select_interests', [
            'interests' => $allInterests,
            'studentName' => Auth::user()->HoTen ?? 'Bạn'
        ]);
    }

    /**
     * Lưu sở thích được chọn
     * POST: /sinhvien/onboarding/interests/store
     */
    public function storeInterestSelection(Request $request)
    {
        $mssv = Auth::user()->TenDangNhap;

        // Validate: phải chọn ít nhất 1 sở thích
        $request->validate([
            'interests' => 'required|array|min:1',
            'interests.*' => 'integer|exists:interests,InterestID',
        ], [
            'interests.required' => 'Bạn phải chọn ít nhất 1 sở thích!',
            'interests.min' => 'Bạn phải chọn ít nhất 1 sở thích!',
            'interests.*.exists' => 'Sở thích không hợp lệ!',
        ]);

        // Kiểm tra: đã có sở thích chưa (tránh duplicate)
        if ($this->hasSelectedInterests()) {
            return redirect()->route('sinhvien.home')
                ->with('warning', 'Bạn đã chọn sở thích rồi!');
        }

        // Lưu sở thích vào database
        $selectedInterests = $request->input('interests');
        
        foreach ($selectedInterests as $interestId) {
            StudentInterest::create([
                'MSSV' => $mssv,
                'InterestID' => $interestId,
                'InterestLevel' => 3, // Default: Mức độ quan tâm trung bình
            ]);
        }

        // Redirect đến dashboard với thông báo thành công
        return redirect()->route('sinhvien.home')
            ->with('success', 'Chọn sở thích thành công! Hệ thống sẽ gợi ý hoạt động dựa trên sở thích của bạn.');
    }

    /**
     * Skip onboarding (tuỳ chọn)
     * POST: /sinhvien/onboarding/skip
     * 
     * Lưu ý: Theo requirement, nên BẮT BUỘC chọn sở thích
     * Hàm này chỉ dùng nếu muốn cho user skip (optional)
     */
    public function skipInterestSelection()
    {
        // Lưu flag vào session hoặc database
        session(['onboarding_skipped' => true]);
        
        return redirect()->route('sinhvien.home')
            ->with('info', 'Bạn sẽ không nhận được gợi ý hoạt động (chưa chọn sở thích). Bạn có thể chọn sau trong Cài đặt.');
    }
}
