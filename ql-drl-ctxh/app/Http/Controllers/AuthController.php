<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\TaiKhoan;

// --- THÊM CÁC MODEL VÀ THƯ VIỆN CẦN THIẾT ---
use App\Models\HoatDongDrl;
use App\Models\HoatDongCtxh;
use Illuminate\Support\Collection;
// --- KẾT THÚC THÊM ---

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->VaiTro);
        }

        // Tạo captcha mới khi hiển thị form
        $this->generateSimpleCaptcha();

        // --- THÊM LOGIC LẤY THÔNG BÁO ---
        $hoatDongsDrl = HoatDongDrl::orderBy('ThoiGianBatDau', 'desc')
                                    ->take(5)
                                    ->get();

        $hoatDongsCtxh = HoatDongCtxh::orderBy('ThoiGianBatDau', 'desc')
                                     ->take(5)
                                     ->get();

        $allActivities = collect($hoatDongsDrl)->map(function ($item) {
                            $item->type = 'DRL';
                            return $item;
                        })
                        ->merge(collect($hoatDongsCtxh)->map(function ($item) {
                            $item->type = 'CTXH';
                            return $item;
                        }))
                        ->sortByDesc('ThoiGianBatDau')
                        ->take(5);

        return view('auth.login', [
            'thongBaoChung' => $allActivities,
            'thongBaoDrl' => $hoatDongsDrl,
            'thongBaoCtxh' => $hoatDongsCtxh,
        ]);
    }

    public function login(Request $request)
    {
        // VALIDATE bao gồm cả captcha
        $data = $request->validate([
            'TenDangNhap' => 'required',
            'password' => 'required',
            'Captcha' => 'required',
        ], [
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'Captcha.required' => 'Vui lòng nhập mã captcha',
        ]);

        // Kiểm tra captcha
        if (strtolower($request->Captcha) !== strtolower(Session::get('captcha_code'))) {
            return back()
                ->withErrors(['Captcha' => 'Mã captcha không chính xác'])
                ->withInput();
        }

        // Xóa captcha sau khi kiểm tra (bảo mật)
        Session::forget('captcha_code');

        $user = TaiKhoan::where('TenDangNhap', $data['TenDangNhap'])->first();

        if ($user && Hash::check($data['password'], $user->MatKhau)) {
            Auth::login($user);
            $request->session()->regenerate();
            return $this->redirectByRole($user->VaiTro);
        }

        // Tạo captcha mới nếu đăng nhập thất bại
        $this->generateSimpleCaptcha();

        return back()
            ->withErrors(['TenDangNhap' => 'Sai tên đăng nhập hoặc mật khẩu.'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Đã đăng xuất.');
    }

    // --- CAPTCHA ĐƠN GIẢN KHÔNG CẦN GD ---
    
    /**
     * Tạo mã captcha đơn giản và lưu vào session
     */
    private function generateSimpleCaptcha()
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // Bỏ 0, O, 1, I để dễ đọc
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        Session::put('captcha_code', $code);
    }

    /**
     * API để refresh captcha (trả về mã mới)
     */
    public function refreshCaptcha()
    {
        $this->generateSimpleCaptcha();
        return response()->json([
            'success' => true,
            'captcha' => Session::get('captcha_code')
        ]);
    }

    /**
     * Tạo ảnh captcha dạng SVG (không cần GD)
     */
    public function getCaptchaImage()
    {
        $code = Session::get('captcha_code', 'ERROR');
        
        // Tạo SVG captcha
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg width="120" height="40" xmlns="http://www.w3.org/2000/svg">
            <rect width="120" height="40" fill="#f0f0f0"/>
            <line x1="0" y1="20" x2="120" y2="20" stroke="#ddd" stroke-width="1"/>
            <line x1="60" y1="0" x2="60" y2="40" stroke="#ddd" stroke-width="1"/>
            <text x="10" y="28" font-family="Arial" font-size="24" font-weight="bold" fill="#333" transform="rotate(-5 60 20)">
                ' . $code . '
            </text>
        </svg>';
        
        return response($svg)->header('Content-Type', 'image/svg+xml');
    }
    // --- KẾT THÚC CAPTCHA ---

    private function redirectByRole($role)
    {
        return match ($role) {
            'Admin'     => redirect()->intended(route('admin.home')),
            'NhanVien'  => redirect()->intended(route('nhanvien.home')),
            'GiangVien' => redirect()->intended(route('giangvien.home')),
            'SinhVien'  => redirect()->intended(route('sinhvien.home')),
            default     => redirect()->intended('/'),
        };
    }
}