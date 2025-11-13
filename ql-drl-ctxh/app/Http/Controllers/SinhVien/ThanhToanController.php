<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThanhToan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ThanhToanController extends Controller
{
    /**
     * === ĐÃ SỬA LỖI 0đ ===
     * Hiển thị trang chọn phương thức thanh toán.
     */
    public function show($id)
    {
        $mssv = Auth::user()->TenDangNhap;

        $thanhToan = ThanhToan::with('dangKyHoatDong.hoatdong.diaDiem')
            ->where('id', $id)
            ->where('TrangThai', 'Chờ thanh toán')
            ->firstOrFail();

        // Kiểm tra chủ sở hữu qua relationship
        if (!$thanhToan->dangKyHoatDong || $thanhToan->dangKyHoatDong->MSSV != $mssv) {
            abort(403, 'Bạn không có quyền truy cập hóa đơn này.');
        }

        $hoatDong = $thanhToan->dangKyHoatDong->hoatdong ?? null;

        // === BẮT ĐẦU SỬA LỖI 0đ ===
        // Lấy giá tiền TỪ BẢNG GỐC (diaDiem),
        // phòng trường hợp TongTien trong CSDL bị sai (bị 0)
        $giaTienGoc = $hoatDong?->diaDiem?->GiaTien ?? $thanhToan->TongTien;

        // Nếu $thanhToan->TongTien đang bị 0, gán lại giá trị đúng
        // (chỉ gán tạm thời để hiển thị, không lưu CSDL)
        if ($giaTienGoc > 0) {
            $thanhToan->TongTien = $giaTienGoc;
        }
        // === KẾT THÚC SỬA ===

        return view('sinhvien.thanhtoan.show', compact('thanhToan', 'hoatDong'));
    }

    /**
     * Xử lý khi sinh viên chọn "Thanh toán Tiền mặt" hoặc "Online".
     * (Hàm này giữ nguyên)
     */
    public function chonPhuongThuc(Request $request, $id)
    {
        $mssv = Auth::user()->TenDangNhap;
        $phuongThuc = $request->input('phuong_thuc');

        if (!in_array($phuongThuc, ['TienMat', 'Online'])) {
            return back()->with('error', 'Phương thức thanh toán không hợp lệ.');
        }

        $thanhToan = ThanhToan::with('dangKyHoatDong')
            ->where('id', $id)
            ->where('TrangThai', 'Chờ thanh toán')
            ->firstOrFail();

        // Kiểm tra chủ sở hữu qua relationship
        if (!$thanhToan->dangKyHoatDong || $thanhToan->dangKyHoatDong->MSSV != $mssv) {
            abort(403);
        }

        // Cập nhật phương thức
        $thanhToan->update(['PhuongThuc' => $phuongThuc]);

        if ($phuongThuc == 'TienMat') {
            return redirect()->route('sinhvien.quanly_dangky.index')
                ->with('success', 'Đã ghi nhận yêu cầu. Vui lòng thanh toán tiền mặt tại Văn phòng Đoàn/Hội sinh viên.');
        }

        if ($phuongThuc == 'Online') {
            // Chuyển sinh viên đến trang hiển thị QR Code
            return redirect()->route('sinhvien.thanhtoan.qr', $thanhToan->id);
        }
    }

    /**
     * === ĐÃ SỬA LỖI 0đ ===
     * Hiển thị trang QR Code (VietQR)
     */
    public function showQr($id)
    {
        $mssv = Auth::user()->TenDangNhap;

        $thanhToan = ThanhToan::with('dangKyHoatDong.hoatdong.diaDiem')
            ->where('id', $id)
            ->where('TrangThai', 'Chờ thanh toán')
            ->where('PhuongThuc', 'Online')
            ->firstOrFail();

        if (!$thanhToan->dangKyHoatDong || $thanhToan->dangKyHoatDong->MSSV != $mssv) {
            abort(403, 'Bạn không có quyền truy cập hóa đơn này.');
        }

        // --- THAY thông tin tài khoản của bạn ---
        $bankBin         = '970436';             // BIN (ví dụ Vietcombank)
        $bankAccountNo   = '1031467947';         // Số tài khoản
        $bankAccountName = 'NGUYEN TAT NGOC';    // Không dấu (có thể để trống)
        // ----------------------------------------

        $donDangKy = $thanhToan->dangKyHoatDong;
        $maDangKy  = $donDangKy->MaDangKy;
        $hoatDong  = $donDangKy->hoatdong;

        // Lấy số tiền từ địa điểm gốc; fallback về TongTien
        $soTien = $hoatDong?->diaDiem?->GiaTien ?? $thanhToan->TongTien;
        $soTien = (int) $soTien;
        if ($soTien <= 0) {
            abort(400, 'Số tiền thanh toán không hợp lệ.');
        }

        // ===== TẠO addInfo theo yêu cầu =====
        $ma   = "DK{$maDangKy}";

        // Loại hoạt động (ví dụ: "Địa chỉ đỏ")
        $loaiHoatDongRaw = $hoatDong->LoaiHoatDong ?? '';
        $loaiHoatDong    = $this->_sanitizeVietQrString($loaiHoatDongRaw);

        // Địa điểm (ví dụ: "Địa đạo Củ Chi")
        $diaDiemRaw = $hoatDong->diaDiem->TenDiaDiem ?? '';
        $diaDiem    = $this->_sanitizeVietQrString($diaDiemRaw);

        // Ngày tổ chức (dd-mm) từ ThoiGianBatDau hoặc trường tương đương
        $ngay = '';
        if (!empty($hoatDong?->ThoiGianBatDau)) {
            $ngay = Carbon::parse($hoatDong->ThoiGianBatDau)->format('d-m');
        } elseif (!empty($hoatDong?->NgayToChuc)) {
            $ngay = Carbon::parse($hoatDong->NgayToChuc)->format('d-m');
        }

        // Ghép chuỗi: [Mã] [Loại HĐ] [Địa điểm] [Ngày]
        $noiDungChuyenKhoan = trim("{$ma} {$loaiHoatDong} {$diaDiem} {$ngay}");

        // Giới hạn chiều dài (VietQR khuyến nghị ngắn gọn)
        $noiDungChuyenKhoan = mb_substr($noiDungChuyenKhoan, 0, 70, 'UTF-8');

        // ===== Tạo QR (PNG trực tiếp) =====
        $qrUrl = sprintf(
            'https://img.vietqr.io/image/%s-%s-compact2.png?amount=%d&addInfo=%s&accountName=%s',
            $bankBin,
            $bankAccountNo,
            $soTien,                               // integer
            urlencode($noiDungChuyenKhoan),        // đã sanitize
            urlencode($bankAccountName)
        );

        // (Tùy chọn) chống cache khi F5
        // $qrUrl .= '&t=' . time();

        return view('sinhvien.thanhtoan.qr', compact('thanhToan', 'qrUrl', 'noiDungChuyenKhoan', 'soTien'));
    }


    /**
     * Chuẩn hóa addInfo cho VietQR: bỏ dấu, giữ a-z 0-9, khoảng trắng và một vài ký tự phổ biến.
     * Cần PHP intl extension để dùng Normalizer (nếu không có sẽ tự fallback).
     */
    private function _sanitizeVietQrString($str)
    {
        if ($str === null) return '';

        // 1) về chữ thường
        $str = mb_strtolower($str, 'UTF-8');

        // 2) chuẩn hóa unicode rồi bỏ toàn bộ dấu (combining marks)
        if (class_exists('\Normalizer')) {
            $str = \Normalizer::normalize($str, \Normalizer::FORM_D);
            // xóa dấu (mọi ký tự Mn = Mark, Nonspacing)
            $str = preg_replace('/\p{Mn}+/u', '', $str);
        } else {
            // Fallback thủ công nếu thiếu intl
            $from = [
                'á',
                'à',
                'ả',
                'ã',
                'ạ',
                'ă',
                'ắ',
                'ằ',
                'ẳ',
                'ẵ',
                'ặ',
                'â',
                'ấ',
                'ầ',
                'ẩ',
                'ẫ',
                'ậ',
                'é',
                'è',
                'ẻ',
                'ẽ',
                'ẹ',
                'ê',
                'ế',
                'ề',
                'ể',
                'ễ',
                'ệ',
                'í',
                'ì',
                'ỉ',
                'ĩ',
                'ị',
                'ó',
                'ò',
                'ỏ',
                'õ',
                'ọ',
                'ô',
                'ố',
                'ồ',
                'ổ',
                'ỗ',
                'ộ',
                'ơ',
                'ớ',
                'ờ',
                'ở',
                'ỡ',
                'ợ',
                'ú',
                'ù',
                'ủ',
                'ũ',
                'ụ',
                'ư',
                'ứ',
                'ừ',
                'ử',
                'ữ',
                'ự',
                'ý',
                'ỳ',
                'ỷ',
                'ỹ',
                'ỵ',
                'đ',
                'Đ'
            ];
            $to   = [
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'i',
                'i',
                'i',
                'i',
                'i',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'y',
                'y',
                'y',
                'y',
                'y',
                'd',
                'd'
            ];
            $str = str_replace($from, $to, $str);
        }

        // 3) thay riêng "đ/Đ" -> "d" (đề phòng trường hợp không thuộc tập Mn)
        $str = str_replace(['đ', 'Đ'], 'd', $str);

        // 4) chỉ giữ a-z, số, khoảng trắng và một vài ký tự hay dùng
        $str = preg_replace('/[^a-z0-9\s\-\.\,\/]/u', '', $str);

        // 5) gộp khoảng trắng
        $str = trim(preg_replace('/\s+/u', ' ', $str));

        return $str;
    }
}
