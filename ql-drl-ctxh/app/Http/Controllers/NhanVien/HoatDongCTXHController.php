<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use App\Models\HoatDongCTXH;
use App\Models\QuyDinhDiemCtxh;
use App\Models\DangKyHoatDongCtxh;
use App\Models\DotDiaChiDo;
use App\Models\DiaDiemDiaChiDo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HoatDongCTXHController extends Controller
{
    /**
     * Hiển thị danh sách các Hoạt động CTXH.
     */
    public function index(Request $request)
    {
        // Tải kèm quan hệ và đếm số lượng đăng ký
        $query = HoatDongCTXH::withCount('dangKy')
            ->with('dotDiaChiDo', 'diaDiem', 'quydinh') // <-- Tải kèm
            ->orderBy('ThoiGianBatDau', 'desc');

        if ($request->has('search') && $request->search != '') {
            $query->where('TenHoatDong', 'like', '%' . $request->search . '%');
        }

        $hoatDongs = $query->paginate(10);

        return view('nhanvien.hoatdong_ctxh.index', compact('hoatDongs'));
    }

    /**
     * Hiển thị form tạo mới Hoạt động CTXH.
     */
    public function create()
    {
        $quyDinhDiems = QuyDinhDiemCtxh::orderBy('TenCongViec')->pluck('TenCongViec', 'MaDiem');
        // Không cần load $dots hay $diadiems nữa
        return view('nhanvien.hoatdong_ctxh.create', compact('quyDinhDiems'));
    }

    /**
     * === SỬA ===
     * Lưu HĐ CTXH THƯỜNG (KHÔNG CÓ "Địa chỉ đỏ")
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'TenHoatDong' => 'required|string|max:255',
            'LoaiHoatDong' => 'required|string|max:100|not_in:Địa chỉ đỏ', // Cấm tạo ĐCĐ ở đây
            'MaQuyDinhDiem' => 'required|exists:quydinhdiemctxh,MaDiem',
            'MoTa' => 'required|string',
            'ThoiGianBatDau' => 'required|date|after_or_equal:now',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'ThoiHanHuy' => 'required|date|before:ThoiGianBatDau',
            'DiaDiem' => 'required|string|max:255', // Ghi chú cụ thể
            'SoLuong' => 'required|integer|min:1',
        ], [
            'MaQuyDinhDiem.required' => 'Bạn phải chọn một quy định điểm.',
            'LoaiHoatDong.not_in' => 'Vui lòng dùng chức năng "Tạo Hàng Loạt" để thêm hoạt động Địa chỉ đỏ.'
        ]);

        // HĐ thường sẽ không có 2 trường này
        $validatedData['dot_id'] = null;
        $validatedData['diadiem_id'] = null;
        $validatedData['MaHoatDong'] = $this->generateUniqueMaHoatDong();

        try {
            HoatDongCTXH::create($validatedData);
            return redirect()->route('nhanvien.hoatdong_ctxh.index')
                ->with('success', 'Tạo hoạt động CTXH thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi tạo CTXH: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Tạo hoạt động thất bại.')->withInput();
        }
    }

    // ===================================================================
    // === THÊM MỚI: CÁC HÀM TẠO HÀNG LOẠT CHO "ĐỊA CHỈ ĐỎ" ===
    // ===================================================================

    /**
     * THÊM MỚI: Hiển thị form "Tạo Suất Hàng Loạt" (Batch Create) cho Địa Chỉ Đỏ.
     */
    public function createBatchDiaChiDo()
    {
        $quyDinhDiems = QuyDinhDiemCtxh::orderBy('TenCongViec')->pluck('TenCongViec', 'MaDiem');
        $dots = DotDiaChiDo::whereIn('TrangThai', ['SapDienRa', 'DangDienRa'])
            ->orderBy('NgayBatDau', 'desc')
            ->get();
        $diadiems = DiaDiemDiaChiDo::where('TrangThai', 'KhaDung')
            ->orderBy('TenDiaDiem')
            ->get();

        return view('nhanvien.hoatdong_ctxh.create_batch', compact('quyDinhDiems', 'dots', 'diadiems'));
    }

    /**
     * THÊM MỚI: Lưu các suất hàng loạt (Batch Store)
     */
    public function storeBatchDiaChiDo(Request $request)
    {
        $validatedData = $request->validate([
            'TenHoatDongGoc' => 'required|string|max:200',
            'dot_id' => 'required|exists:dot_dia_chi_do,id',
            'diadiem_id' => 'required|exists:diadiemdiachido,id',
            'MaQuyDinhDiem' => 'required|exists:quydinhdiemctxh,MaDiem',
            'SoLuongMoiNgay' => 'required|integer|min:1',
            'DiaDiemCuThe' => 'required|string|max:255',
            'NgayBatDauSuat' => 'required|date',
            'NgayKetThucSuat' => 'required|date|after_or_equal:NgayBatDauSuat',
            'GioBatDau' => 'required|date_format:H:i',
            'GioKetThuc' => 'required|date_format:H:i|after:GioBatDau',
        ], [
            // Thêm các thông báo lỗi nếu muốn
            'TenHoatDongGoc.required' => 'Tên gốc hoạt động là bắt buộc.',
            'dot_id.required' => 'Bạn phải chọn một đợt.',
            'diadiem_id.required' => 'Bạn phải chọn một địa điểm.',
            'SoLuongMoiNgay.required' => 'Số lượng mỗi ngày là bắt buộc.',
        ]);

        $dot = DotDiaChiDo::find($validatedData['dot_id']);
        $diaDiem = DiaDiemDiaChiDo::find($validatedData['diadiem_id']);

        $ngayBatDauDot = Carbon::parse($dot->NgayBatDau)->startOfDay();
        $ngayKetThucDot = Carbon::parse($dot->NgayKetThuc)->endOfDay();
        $ngayBatDauSuat = Carbon::parse($validatedData['NgayBatDauSuat']);
        $ngayKetThucSuat = Carbon::parse($validatedData['NgayKetThucSuat']);

        // Kiểm tra xem các ngày tạo suất có nằm trong Đợt không
        if ($ngayBatDauSuat->lt($ngayBatDauDot) || $ngayKetThucSuat->gt($ngayKetThucDot)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['NgayBatDauSuat' => 'Các ngày tạo suất phải nằm trong khoảng thời gian của đợt (Từ ' . $ngayBatDauDot->format('d/m/Y') . ' đến ' . $ngayKetThucDot->format('d/m/Y') . ').']);
        }

        // Tạo vòng lặp các ngày
        $period = CarbonPeriod::create($ngayBatDauSuat, $ngayKetThucSuat);
        $count = 0;

        try {
            foreach ($period as $date) {
                $thoiGianBatDau = $date->copy()->setTimeFromTimeString($validatedData['GioBatDau']);
                $thoiGianKetThuc = $date->copy()->setTimeFromTimeString($validatedData['GioKetThuc']);

                HoatDongCTXH::create([
                    'MaHoatDong' => $this->generateUniqueMaHoatDong($date),
                    'TenHoatDong' => $validatedData['TenHoatDongGoc'] . ' - Ngày ' . $date->format('d/m/Y'),
                    'MoTa' => 'Hoạt động thuộc đợt "' . $dot->TenDot . '" tại ' . $diaDiem->TenDiaDiem,
                    'ThoiGianBatDau' => $thoiGianBatDau,
                    'ThoiGianKetThuc' => $thoiGianKetThuc,
                    'ThoiHanHuy' => $thoiGianBatDau->copy()->subHours(12), // Hạn hủy: trước 12 tiếng
                    'DiaDiem' => $validatedData['DiaDiemCuThe'],
                    'SoLuong' => $validatedData['SoLuongMoiNgay'], // Số lượng cho ngày này
                    'LoaiHoatDong' => 'Địa chỉ đỏ', // Set cứng
                    'MaQuyDinhDiem' => $validatedData['MaQuyDinhDiem'],
                    'dot_id' => $validatedData['dot_id'],
                    'diadiem_id' => $validatedData['diadiem_id'],
                ]);
                $count++;
            }
        } catch (\Exception $e) {
            Log::error('Lỗi tạo hàng loạt CTXH: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi tạo suất. Lỗi: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('nhanvien.hoatdong_ctxh.index')
            ->with('success', "Tạo hàng loạt thành công $count suất hoạt động 'Địa chỉ đỏ'.");
    }



    /**
     * Hiển thị chi tiết một Hoạt động CTXH.
     */
    public function show(HoatDongCTXH $hoatdong_ctxh)
    {
        $hoatdong_ctxh->load('sinhVienDangKy', 'quydinh', 'dotDiaChiDo', 'diaDiem');
        return view('nhanvien.hoatdong_ctxh.show', compact('hoatdong_ctxh'));
    }

    /**
     * Hiển thị form chỉnh sửa Hoạt động CTXH.
     */
    public function edit(HoatDongCTXH $hoatdong_ctxh)
    {
        $quyDinhDiems = QuyDinhDiemCtxh::orderBy('TenCongViec')->pluck('TenCongViec', 'MaDiem');
        // Không cần load $dots, $diadiems vì chúng ta không cho sửa
        return view('nhanvien.hoatdong_ctxh.edit', compact('hoatdong_ctxh', 'quyDinhDiems'));
    }

    /**
     * Cập nhật Hoạt động CTXH trong database.
     */
    public function update(Request $request, HoatDongCTXH $hoatdong_ctxh)
    {
        $validatedData = $request->validate([
            'TenHoatDong' => 'required|string|max:255',
            // 'LoaiHoatDong' => '...', // Không cho phép sửa Loại Hoạt Động
            'MaQuyDinhDiem' => 'required|exists:quydinhdiemctxh,MaDiem',
            'MoTa' => 'nullable|string',
            'ThoiGianBatDau' => 'required|date',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'ThoiHanHuy' => 'required|date|before:ThoiGianBatDau',
            'DiaDiem' => 'required|string|max:255',
            'SoLuong' => 'required|integer|min:1',
        ]);

        // Nếu là "Địa chỉ đỏ", kiểm tra lại ngày có khớp với Đợt không
        if ($hoatdong_ctxh->LoaiHoatDong == 'Địa chỉ đỏ') {
            $dot = $hoatdong_ctxh->dotDiaChiDo; // Load từ quan hệ đã có

            $ngayBatDauDot = Carbon::parse($dot->NgayBatDau)->startOfDay();
            $ngayKetThucDot = Carbon::parse($dot->NgayKetThuc)->endOfDay();
            $ngayBatDauHD = Carbon::parse($validatedData['ThoiGianBatDau']);
            $ngayKetThucHD = Carbon::parse($validatedData['ThoiGianKetThuc']);

            if ($ngayBatDauHD->lt($ngayBatDauDot) || $ngayKetThucHD->gt($ngayKetThucDot)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['ThoiGianBatDau' => 'Thời gian HĐ phải nằm trong Đợt.']);
            }
        }

        try {
            $hoatdong_ctxh->update($validatedData);
            return redirect()->route('nhanvien.hoatdong_ctxh.index')
                ->with('success', 'Cập nhật hoạt động CTXH thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật CTXH: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Cập nhật thất bại.')->withInput();
        }
    }

    /**
     * Xóa Hoạt động CTXH khỏi database.
     */
    public function destroy(HoatDongCTXH $hoatdong_ctxh)
    {
        try {
            if ($hoatdong_ctxh->dangKy()->exists()) {
                return redirect()->route('nhanvien.hoatdong_ctxh.index')
                    ->with('error', 'Không thể xóa hoạt động "' . $hoatdong_ctxh->TenHoatDong . '" vì đã có sinh viên đăng ký.');
            }
            $hoatdong_ctxh->delete();
            return redirect()->route('nhanvien.hoatdong_ctxh.index')
                ->with('success', 'Xóa hoạt động CTXH thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi chung khi xóa CTXH: ' . $e->getMessage());
            return redirect()->route('nhanvien.hoatdong_ctxh.index')
                ->with('error', 'Đã xảy ra lỗi không mong muốn khi xóa.');
        }
    }

    private function generateUniqueMaHoatDong($date = null)
    {
        $prefix = 'CTXH-';
        $dateStr = ($date) ? $date->format('Ymd') : now()->format('Ymd');

        do {
            $randomStr = strtoupper(Str::random(4));
            $maHoatDong = $prefix . $dateStr . '-' . $randomStr;
        } while (HoatDongCTXH::where('MaHoatDong', $maHoatDong)->exists());

        return $maHoatDong;
    }

    // ===================================================================
    // === CÁC HÀM ĐIỂM DANH - TÁCH CHECK-IN VÀ CHECK-OUT ===
    // ===================================================================

    /**
     * Hiển thị form tạo mã QR check-in với thời gian hiệu lực tùy chỉnh.
     */
    public function createCheckInQr(HoatDongCTXH $hoatdong_ctxh)
    {
        return view('nhanvien.hoatdong_ctxh.create_checkin_qr', compact('hoatdong_ctxh'));
    }

    /**
     * Tạo token QR riêng cho check-in với thời gian hiệu lực do nhân viên chỉ định.
     * 
     * @param Request $request
     * @param HoatDongCTXH $hoatdong_ctxh
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateCheckInQr(Request $request, HoatDongCTXH $hoatdong_ctxh)
    {
        $validated = $request->validate([
            'CheckInOpenAt' => 'required|date|after_or_equal:now',
            'CheckInExpiresAt' => 'required|date|after:CheckInOpenAt',
        ], [
            'CheckInOpenAt.required' => 'Thời gian mở quét check-in là bắt buộc.',
            'CheckInOpenAt.after_or_equal' => 'Thời gian mở phải từ bây giờ trở đi.',
            'CheckInExpiresAt.required' => 'Thời gian hết hạn là bắt buộc.',
            'CheckInExpiresAt.after' => 'Thời gian hết hạn phải sau thời gian mở quét.',
        ]);

        try {
            // Kiểm tra hoạt động còn hiệu lực không
            if ($hoatdong_ctxh->ThoiGianKetThuc->isPast()) {
                return back()->with('error', 'Không thể phát mã QR cho hoạt động đã kết thúc.');
            }

            $hoatdong_ctxh->CheckInToken = Str::random(40);
            $hoatdong_ctxh->CheckInOpenAt = Carbon::parse($validated['CheckInOpenAt']);
            $hoatdong_ctxh->CheckInExpiresAt = Carbon::parse($validated['CheckInExpiresAt']);

            $hoatdong_ctxh->save();

            return back()->with('success', 'Đã tạo mã QR check-in thành công. Hiệu lực từ ' . $hoatdong_ctxh->CheckInOpenAt->format('H:i d/m/Y') . ' đến ' . $hoatdong_ctxh->CheckInExpiresAt->format('H:i d/m/Y'));
        } catch (\Throwable $e) {
            Log::error('Lỗi tạo QR check-in CTXH: ' . $e->getMessage());
            return back()->with('error', 'Không thể tạo mã QR check-in. Vui lòng thử lại.');
        }
    }

    /**
     * Hiển thị form tạo mã QR check-out với thời gian hiệu lực tùy chỉnh.
     */
    public function createCheckOutQr(HoatDongCTXH $hoatdong_ctxh)
    {
        return view('nhanvien.hoatdong_ctxh.create_checkout_qr', compact('hoatdong_ctxh'));
    }

    /**
     * Tạo token QR riêng cho check-out với thời gian hiệu lực do nhân viên chỉ định.
     * 
     * @param Request $request
     * @param HoatDongCTXH $hoatdong_ctxh
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateCheckOutQr(Request $request, HoatDongCTXH $hoatdong_ctxh)
    {
        $validated = $request->validate([
            'CheckOutOpenAt' => 'required|date|after_or_equal:now',
            'CheckOutExpiresAt' => 'required|date|after:CheckOutOpenAt',
        ], [
            'CheckOutOpenAt.required' => 'Thời gian mở quét check-out là bắt buộc.',
            'CheckOutOpenAt.after_or_equal' => 'Thời gian mở phải từ bây giờ trở đi.',
            'CheckOutExpiresAt.required' => 'Thời gian hết hạn là bắt buộc.',
            'CheckOutExpiresAt.after' => 'Thời gian hết hạn phải sau thời gian mở quét.',
        ]);

        try {
            // Kiểm tra hoạt động còn hiệu lực không
            if ($hoatdong_ctxh->ThoiGianKetThuc->isPast()) {
                return back()->with('error', 'Không thể phát mã QR cho hoạt động đã kết thúc.');
            }

            $hoatdong_ctxh->CheckOutToken = Str::random(40);
            $hoatdong_ctxh->CheckOutOpenAt = Carbon::parse($validated['CheckOutOpenAt']);
            $hoatdong_ctxh->CheckOutExpiresAt = Carbon::parse($validated['CheckOutExpiresAt']);

            $hoatdong_ctxh->save();

            return back()->with('success', 'Đã tạo mã QR check-out thành công. Hiệu lực từ ' . $hoatdong_ctxh->CheckOutOpenAt->format('H:i d/m/Y') . ' đến ' . $hoatdong_ctxh->CheckOutExpiresAt->format('H:i d/m/Y'));
        } catch (\Throwable $e) {
            Log::error('Lỗi tạo QR check-out CTXH: ' . $e->getMessage());
            return back()->with('error', 'Không thể tạo mã QR check-out. Vui lòng thử lại.');
        }
    }


    /**
     * Tổng kết điểm danh sau hoạt động kết thúc.
     * 
     * Sinh viên được đánh dấu:
     * - "Đã tham gia" nếu check-in AND check-out đều có
     * - "Vắng" nếu check-in AND check-out đều NULL
     * - "Chưa tổng kết" cho các trường hợp khác (chỉ check-in hoặc chỉ check-out)
     * 
     * @param Request $request
     * @param HoatDongCTXH $hoatdong_ctxh
     * @return \Illuminate\Http\RedirectResponse
     */
    public function finalizeAttendance(Request $request, HoatDongCTXH $hoatdong_ctxh)
    {
        try {
            $maHoatDong = $hoatdong_ctxh->MaHoatDong;

            // ✅ Vắng: Cả check-in và check-out đều NULL (không quét được)
            DangKyHoatDongCtxh::where('MaHoatDong', $maHoatDong)
                ->where('TrangThaiDangKy', 'Đã duyệt')
                ->whereNull('CheckInAt')
                ->whereNull('CheckOutAt')
                ->update(['TrangThaiThamGia' => 'Vắng']);

            // ✅ Đã tham gia: Cả check-in và check-out đều có
            DangKyHoatDongCtxh::where('MaHoatDong', $maHoatDong)
                ->where('TrangThaiDangKy', 'Đã duyệt')
                ->whereNotNull('CheckInAt')
                ->whereNotNull('CheckOutAt')
                ->update(['TrangThaiThamGia' => 'Đã tham gia']);

            // ℹ️ Chưa tổng kết: Có check-in hoặc check-out, nhưng không đầy đủ
            // (Để nhân viên review thủ công)
            DangKyHoatDongCtxh::where('MaHoatDong', $maHoatDong)
                ->where('TrangThaiDangKy', 'Đã duyệt')
                ->where(function ($query) {
                    $query->where(function ($q) {
                        // Chỉ có check-in, không có check-out
                        $q->whereNotNull('CheckInAt')->whereNull('CheckOutAt');
                    })
                    ->orWhere(function ($q) {
                        // Chỉ có check-out, không có check-in
                        $q->whereNull('CheckInAt')->whereNotNull('CheckOutAt');
                    });
                })
                ->update(['TrangThaiThamGia' => 'Chưa tổng kết']);

            return redirect()->back()->with('success', 'Đã tổng kết điểm danh thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi tổng kết điểm danh CTXH: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tổng kết. Vui lòng thử lại.');
        }
    }


    public function ghiNhanKetQua(HoatDongCTXH $hoatdong_ctxh)
    {
        // Tải sinh viên đã đăng ký kèm theo pivot data (thông tin check-in, trạng thái)
        $sinhViens = $hoatdong_ctxh->sinhVienDangKy()
            ->withPivot(['CheckInAt', 'CheckOutAt', 'TrangThaiDangKy', 'TrangThaiThamGia', 'MaDangKy'])
            ->get();
            
        return view('nhanvien.hoatdong_ctxh.ghi_nhan_ket_qua', compact('hoatdong_ctxh', 'sinhViens'));
    }

    /**
     * THÊM MỚI: Xử lý POST request để cập nhật kết quả thủ công.
     */
    public function updateKetQua(Request $request, HoatDongCTXH $hoatdong_ctxh)
    {
        // Dữ liệu sẽ chứa mảng các sinh viên cần cập nhật trạng thái tham gia
        $data = $request->validate([
            'results.*.MaDangKy' => 'required|exists:dangkyhoatdongctxh,MaDangKy',
            'results.*.TrangThaiThamGia' => 'required|in:Đã tham gia,Vắng,Chưa tổng kết',
        ]);
        
        try {
            foreach ($data['results'] as $result) {
                // Chỉ cập nhật TrangThaiThamGia
                DangKyHoatDongCtxh::where('MaDangKy', $result['MaDangKy'])->update([
                    'TrangThaiThamGia' => $result['TrangThaiThamGia'],
                ]);
            }

            return redirect()->route('nhanvien.hoatdong_ctxh.show', $hoatdong_ctxh)
                             ->with('success', 'Cập nhật kết quả sinh viên thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật kết quả CTXH thủ công: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Cập nhật kết quả thất bại. Vui lòng thử lại.');
        }
    }
}
