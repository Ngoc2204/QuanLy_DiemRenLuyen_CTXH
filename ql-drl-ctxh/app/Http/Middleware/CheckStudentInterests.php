<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentInterest;

class CheckStudentInterests
{
    /**
     * Handle an incoming request.
     * 
     * Nếu sinh viên chưa có sở thích trong bảng student_interests,
     * buộc redirect đến trang edit profile với thông báo warning
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Chỉ áp dụng cho sinh viên đã đăng nhập
        if (!Auth::check() || Auth::user()->VaiTro !== 'SinhVien') {
            return $next($request);
        }

        // Lấy MSSV từ user đăng nhập
        $mssv = Auth::user()->TenDangNhap;

        // Kiểm tra sinh viên có sở thích hay không
        $hasInterests = StudentInterest::where('MSSV', $mssv)->exists();

        // Nếu chưa có sở thích, redirect đến trang edit profile
        if (!$hasInterests) {
            // Tránh redirect vòng lặp
            $currentRoute = $request->route()->getName();
            if ($currentRoute !== 'sinhvien.profile.edit') {
                return redirect()->route('sinhvien.profile.edit')
                    ->with('warning', 'Bạn chưa chọn sở thích của mình. Vui lòng chọn sở thích để nhận được gợi ý hoạt động phù hợp!');
            }
        }

        return $next($request);
    }
}
