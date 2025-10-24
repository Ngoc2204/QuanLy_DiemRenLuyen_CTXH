<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaiKhoan;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->VaiTro);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'TenDangNhap' => 'required',
            'password' => 'required',
        ]);

        $user = TaiKhoan::where('TenDangNhap', $data['TenDangNhap'])
                        ->where('MatKhau', $data['password'])
                        ->first();

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();
            return $this->redirectByRole($user->VaiTro);
        }

        return back()->withErrors(['TenDangNhap' => 'Sai tên đăng nhập hoặc mật khẩu.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'Đã đăng xuất.');
    }

    private function redirectByRole($role)
    {


        return match ($role) {
            'Admin'     => redirect()->route('admin.home'),
            'NhanVien'  => redirect()->route('nhanvien.home'),
            'GiangVien' => redirect()->route('giangvien.home'),
            'SinhVien'  => redirect()->route('sinhvien.home'),
            default     => redirect()->route('login'),
        };
    }
}
