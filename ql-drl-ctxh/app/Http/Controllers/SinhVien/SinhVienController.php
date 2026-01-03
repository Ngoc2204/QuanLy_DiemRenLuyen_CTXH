<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\SinhVien; 
use App\Models\BangDiemHocKy; 
use App\Models\DiemRenLuyen; 
use App\Models\DiemCTXH; 
use App\Models\StudentInterest; 

class SinhVienController extends Controller
{
    /**
     * Lấy MSSV của sinh viên đang đăng nhập.
     */
    private function getMssv()
    {
        // Giả định 'TenDangNhap' trong bảng 'taikhoan' chính là MSSV
        return Auth::user()->TenDangNhap; 
    }

    /**
     * 1. Hiển thị trang Thông tin sinh viên
     */
    public function showProfile()
    {
        $mssv = $this->getMssv();
        
        // Dùng 'with' để tải luôn thông tin Lớp và Khoa (tối ưu query)
        $student = SinhVien::with('lop.khoa')->find($mssv);

        if (!$student) {
            abort(404, 'Không tìm thấy thông tin sinh viên.');
        }

        return view('sinhvien.thongtin_sinhvien.profile_show', ['student' => $student]);
    }

    /**
     * 2. Hiển thị trang Chỉnh sửa thông tin
     */
    public function editProfile()
    {
        $mssv = $this->getMssv();
        $student = SinhVien::find($mssv);

        if (!$student) {
            abort(404, 'Không tìm thấy thông tin sinh viên.');
        }
        
        return view('sinhvien.thongtin_sinhvien.profile_edit', ['student' => $student]);
    }

    /**
     * 2. Cập nhật thông tin cá nhân
     */
    public function updateProfile(Request $request)
    {
        $mssv = $this->getMssv();
        $student = SinhVien::find($mssv);

        // DEBUG: Log request data
        \Illuminate\Support\Facades\Log::info('Profile Update Request', [
            'MSSV' => $mssv,
            'interests' => $request->input('interests'),
            'all_inputs' => $request->all(),
        ]);

        // Validate dữ liệu
        $validatedData = $request->validate([
            'Email' => 'required|email|max:100',
            'SDT' => 'nullable|string|max:15',
            'avatar' => 'nullable|image|max:2048',
            'interests' => 'nullable|array',
            'interests.*' => 'integer|exists:interests,InterestID',
        ]);

        // DEBUG: Log validated data
        \Illuminate\Support\Facades\Log::info('Validation Passed', [
            'validated_interests' => $validatedData['interests'],
            'interest_count' => count($validatedData['interests']),
        ]);

        // Update student info (Email, SDT)
        $student->Email = $validatedData['Email'];
        $student->SDT = $validatedData['SDT'] ?? null;
        $student->save();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $ext = $file->getClientOriginalExtension();
            $filename = Auth::user()->TenDangNhap . '.' . $ext;

            // remove any previous avatar for this user
            $existing = Storage::disk('public')->files('avatars');
            foreach ($existing as $f) {
                if (preg_match('/^avatars\\/' . preg_quote(Auth::user()->TenDangNhap, '/') . '\\./', $f)) {
                    Storage::disk('public')->delete($f);
                }
            }

            $file->storeAs('avatars', $filename, 'public');
            $user = Auth::user();
            $user->Avatar = 'avatars/' . $filename;
            $user->save();
        }

        // Save student interests to student_interests table
        if (!empty($validatedData['interests'])) {
            StudentInterest::where('MSSV', $mssv)->delete();
            
            foreach ($validatedData['interests'] as $interestId) {
                try {
                    DB::insert('INSERT INTO student_interests (MSSV, InterestID, InterestLevel) VALUES (?, ?, ?)', 
                        [$mssv, $interestId, 3]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Interest Save Error', [
                        'MSSV' => $mssv,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return redirect()->route('sinhvien.profile.show')->with('success', 'Cập nhật thông tin thành công!');
    }
}