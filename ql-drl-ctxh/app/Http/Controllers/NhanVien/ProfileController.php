<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show edit form for authenticated staff user.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $nhanvien = $user->nhanvien;

        return view('nhanvien.profile.edit', compact('user', 'nhanvien'));
    }

    /**
     * Update profile data for authenticated staff user.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $nhanvien = $user->nhanvien;

        $data = $request->validate([
            'TenNV' => 'required|string|max:255',
            'Email' => 'nullable|email|max:255',
            'SDT' => 'nullable|string|max:20',
            'GioiTinh' => 'nullable|in:Nam,Nữ,Khác',
            'avatar' => 'nullable|image|max:2048'
        ]);

        if ($nhanvien) {
            $nhanvien->TenNV = $data['TenNV'];
            $nhanvien->Email = $data['Email'] ?? $nhanvien->Email;
            $nhanvien->SDT = $data['SDT'] ?? $nhanvien->SDT;
            $nhanvien->GioiTinh = $data['GioiTinh'] ?? $nhanvien->GioiTinh;
            $nhanvien->save();
        }

        // Handle avatar upload (store in storage/app/public/avatars/{TenDangNhap}.{ext})
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $ext = $file->getClientOriginalExtension();
            $filename = $user->TenDangNhap . '.' . $ext;

            // Delete previous avatar file if stored in DB
            if (!empty($user->Avatar) && Storage::disk('public')->exists($user->Avatar)) {
                Storage::disk('public')->delete($user->Avatar);
            } else {
                // Fallback: remove any files matching the username in avatars folder
                $existing = Storage::disk('public')->files('avatars');
                foreach ($existing as $f) {
                    if (preg_match('/^avatars\/' . preg_quote($user->TenDangNhap, '/') . '\./', $f)) {
                        Storage::disk('public')->delete($f);
                    }
                }
            }

            // Store image to storage (resize will be done via web compression in future)
            $file->storeAs('avatars', $filename, 'public');
            
            $user->Avatar = 'avatars/' . $filename;
            $user->save();
        }

        return redirect()->route('nhanvien.profile.edit')
            ->with('success', 'Cập nhật thông tin cá nhân thành công.');
    }

    /**
     * Update password for authenticated staff user.
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Verify current password
        if (!Hash::check($data['current_password'], $user->MatKhau)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        // Update password
        $user->MatKhau = Hash::make($data['new_password']);
        $user->save();

        return redirect()->route('nhanvien.profile.edit')
            ->with('success', 'Đổi mật khẩu thành công.');
    }
}
