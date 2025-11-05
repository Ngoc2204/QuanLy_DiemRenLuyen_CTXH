<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// BẠN NÊN SỬ DỤNG HASH, NHƯNG HỆ THỐNG CỦA BẠN ĐANG DÙNG PLAIN TEXT
// use Illuminate\Support\Facades\Hash; 

class PasswordController extends Controller
{
    /**
     * Hiển thị form đổi mật khẩu
     */
    public function showChangePasswordForm()
    {
        return view('sinhvien.thongtin_sinhvien.password_edit');
    }

    /**
     * Xử lý cập nhật mật khẩu
     */
    public function updatePassword(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // confirmed bắt buộc phải có trường new_password_confirmation
        ], [
            'current_password.required' => 'Bạn phải nhập mật khẩu hiện tại.',
            'new_password.required' => 'Bạn phải nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = Auth::user(); // Đây là model TaiKhoan

        // 2. Kiểm tra mật khẩu cũ
        // !! CẢNH BÁO BẢO MẬT: Đang so sánh plain-text.
        if ($request->current_password !== $user->MatKhau) {
            // Nếu dùng HASH (đúng), bạn nên dùng:
            // if (!Hash::check($request->current_password, $user->MatKhau)) {
            
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        // 3. Cập nhật mật khẩu mới
        // !! CẢNH BÁO BẢO MẬT: Đang lưu plain-text.
        $user->MatKhau = $request->new_password;
        
        // Nếu dùng HASH (đúng), bạn nên dùng:
        // $user->MatKhau = Hash::make($request->new_password);
        
        $user->save();

        // 4. Trả về thông báo thành công
        return redirect()->route('sinhvien.thongtin_sinhvien.password_edit')->with('success', 'Đổi mật khẩu thành công!');
    }
}
