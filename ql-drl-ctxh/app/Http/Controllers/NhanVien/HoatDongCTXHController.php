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
use Carbon\Carbon;

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
        // Lấy danh sách Quy định điểm
        $quyDinhDiems = QuyDinhDiemCtxh::orderBy('TenCongViec')->pluck('TenCongViec', 'MaDiem');
        
        // Lấy các đợt Sắp/Đang diễn ra
        $dots = DotDiaChiDo::whereIn('TrangThai', ['SapDienRa', 'DangDienRa'])
                            ->orderBy('NgayBatDau', 'desc')
                            ->get();
                            
        // Lấy tất cả địa điểm (bạn có thể lọc theo đợt nếu muốn)
        $diadiems = DiaDiemDiaChiDo::orderBy('TenDiaDiem')->get(); 

        return view('nhanvien.hoatdong_ctxh.create', compact('quyDinhDiems', 'dots', 'diadiems'));
    }

    /**
     * Lưu Hoạt động CTXH mới vào database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'TenHoatDong' => 'required|string|max:255',
            'LoaiHoatDong' => 'required|string|max:100', // <-- Chuyển lên trước để validate
            
            // --- SỬA VALIDATE ---
            // Chỉ bắt buộc khi LoaiHoatDong là 'Địa chỉ đỏ'
            'dot_id' => 'required_if:LoaiHoatDong,Địa chỉ đỏ|nullable|exists:dot_dia_chi_do,id', 
            'diadiem_id' => 'required_if:LoaiHoatDong,Địa chỉ đỏ|nullable|exists:diadiemdiachido,id', 
            // --- KẾT THÚC SỬA ---

            'MaQuyDinhDiem' => 'required|exists:quydinhdiemctxh,MaDiem',
            'MoTa' => 'nullable|string',
            'ThoiGianBatDau' => 'required|date|after_or_equal:now',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'ThoiHanHuy' => 'required|date|before:ThoiGianBatDau',
            'DiaDiem' => 'required|string|max:255', // Ghi chú cụ thể (luôn bắt buộc)
            'SoLuong' => 'required|integer|min:1',
        ], [
            'dot_id.required_if' => 'Bạn phải chọn một đợt cho hoạt động Địa chỉ đỏ.',
            'diadiem_id.required_if' => 'Bạn phải chọn một địa điểm cho hoạt động Địa chỉ đỏ.',
            'MaQuyDinhDiem.required' => 'Bạn phải chọn một quy định điểm.',
        ]);

        // --- SỬA LOGIC KIỂM TRA NGÀY ---
        // Chỉ kiểm tra ngày nếu đây là hoạt động 'Địa chỉ đỏ'
        if ($validatedData['LoaiHoatDong'] == 'Địa chỉ đỏ') {
            if (empty($validatedData['dot_id'])) {
                 return redirect()->back()->withInput()->withErrors(['dot_id' => 'Lỗi: Bạn phải chọn Đợt cho hoạt động Địa chỉ đỏ.']);
            }

            $dot = DotDiaChiDo::find($validatedData['dot_id']);
            $ngayBatDauDot = Carbon::parse($dot->NgayBatDau)->startOfDay();
            $ngayKetThucDot = Carbon::parse($dot->NgayKetThuc)->endOfDay();
            $ngayBatDauHD = Carbon::parse($validatedData['ThoiGianBatDau']);
            $ngayKetThucHD = Carbon::parse($validatedData['ThoiGianKetThuc']);

            if ($ngayBatDauHD->lt($ngayBatDauDot) || $ngayKetThucHD->gt($ngayKetThucDot)) {
                return redirect()->back()
                            ->withInput()
                            ->withErrors(['ThoiGianBatDau' => 'Thời gian hoạt động phải nằm trong khoảng thời gian của đợt (Từ ' . $ngayBatDauDot->format('d/m/Y') . ' đến ' . $ngayKetThucDot->format('d/m/Y') . ').']);
            }
        } else {
            // Nếu không phải 'Địa chỉ đỏ', đảm bảo 2 trường này là NULL
            $validatedData['dot_id'] = null;
            $validatedData['diadiem_id'] = null;
        }
        // --- KẾT THÚC SỬA ---


        // Tạo Mã Hoạt động duy nhất
        $maHoatDong = 'CTXH-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        while (HoatDongCTXH::where('MaHoatDong', $maHoatDong)->exists()) {
            $maHoatDong = 'CTXH-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        }
        $validatedData['MaHoatDong'] = $maHoatDong;


        try {
            HoatDongCTXH::create($validatedData); // <-- Đã sửa (X hoa)
            return redirect()->route('nhanvien.hoatdong_ctxh.index')
                             ->with('success', 'Tạo hoạt động CTXH thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi tạo CTXH: ' . $e->getMessage()); // <-- Ghi Log lỗi
            return redirect()->back()
                             ->with('error', 'Tạo hoạt động thất bại. Lỗi: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Hiển thị chi tiết một Hoạt động CTXH.
     */
    public function show(HoatDongCTXH $hoatdong_ctxh) // <-- Đã sửa (X hoa)
    {
        $hoatdong_ctxh->load('sinhVienDangKy', 'quydinh', 'dotDiaChiDo', 'diaDiem'); // Tải thêm
        return view('nhanvien.hoatdong_ctxh.show', compact('hoatdong_ctxh'));
    }

    /**
     * Hiển thị form chỉnh sửa Hoạt động CTXH.
     */
    public function edit(HoatDongCTXH $hoatdong_ctxh) // <-- Đã sửa (X hoa)
    {
        $quyDinhDiems = QuyDinhDiemCtxh::orderBy('TenCongViec')->pluck('TenCongViec', 'MaDiem');
        $dots = DotDiaChiDo::orderBy('NgayBatDau', 'desc')->get();
        $diadiems = DiaDiemDiaChiDo::orderBy('TenDiaDiem')->get(); 

        return view('nhanvien.hoatdong_ctxh.edit', compact('hoatdong_ctxh', 'quyDinhDiems', 'dots', 'diadiems'));
    }

    /**
     * Cập nhật Hoạt động CTXH trong database.
     */
    public function update(Request $request, HoatDongCTXH $hoatdong_ctxh) // <-- Đã sửa (X hoa)
    {
        $validatedData = $request->validate([
            'TenHoatDong' => 'required|string|max:255',
            'LoaiHoatDong' => 'required|string|max:100', // <-- Chuyển lên trước

            // --- SỬA VALIDATE ---
            'dot_id' => 'required_if:LoaiHoatDong,Địa chỉ đỏ|nullable|exists:dot_dia_chi_do,id', 
            'diadiem_id' => 'required_if:LoaiHoatDong,Địa chỉ đỏ|nullable|exists:diadiemdiachido,id', 
            // --- KẾT THÚC SỬA ---

            'MaQuyDinhDiem' => 'required|exists:quydinhdiemctxh,MaDiem',
            'MoTa' => 'nullable|string',
            'ThoiGianBatDau' => 'required|date',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'ThoiHanHuy' => 'required|date|before:ThoiGianBatDau',
            'DiaDiem' => 'required|string|max:255',
            'SoLuong' => 'required|integer|min:1',
        ], [
            'dot_id.required_if' => 'Bạn phải chọn một đợt cho hoạt động Địa chỉ đỏ.',
            'diadiem_id.required_if' => 'Bạn phải chọn một địa điểm cho hoạt động Địa chỉ đỏ.',
        ]);
        
        // --- SỬA LOGIC KIỂM TRA NGÀY ---
        if ($validatedData['LoaiHoatDong'] == 'Địa chỉ đỏ') {
            if (empty($validatedData['dot_id'])) {
                 return redirect()->back()->withInput()->withErrors(['dot_id' => 'Lỗi: Bạn phải chọn Đợt cho hoạt động Địa chỉ đỏ.']);
            }
            
            $dot = DotDiaChiDo::find($validatedData['dot_id']);
            $ngayBatDauDot = Carbon::parse($dot->NgayBatDau)->startOfDay();
            $ngayKetThucDot = Carbon::parse($dot->NgayKetThuc)->endOfDay();
            $ngayBatDauHD = Carbon::parse($validatedData['ThoiGianBatDau']);
            $ngayKetThucHD = Carbon::parse($validatedData['ThoiGianKetThuc']);

            if ($ngayBatDauHD->lt($ngayBatDauDot) || $ngayKetThucHD->gt($ngayKetThucDot)) {
                return redirect()->back()
                            ->withInput()
                            ->withErrors(['ThoiGianBatDau' => 'Thời gian hoạt động phải nằm trong khoảng thời gian của đợt (Từ ' . $ngayBatDauDot->format('d/m/Y') . ' đến ' . $ngayKetThucDot->format('d/m/Y') . ').']);
            }
        } else {
            $validatedData['dot_id'] = null;
            $validatedData['diadiem_id'] = null;
        }
        // --- KẾT THÚC SỬA ---

        try {
            $hoatdong_ctxh->update($validatedData);
            return redirect()->route('nhanvien.hoatdong_ctxh.index')
                             ->with('success', 'Cập nhật hoạt động CTXH thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật CTXH: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Cập nhật hoạt động thất bại. Lỗi: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Xóa Hoạt động CTXH khỏi database.
     */
    public function destroy(HoatDongCTXH $hoatdong_ctxh) // <-- Đã sửa (X hoa)
    {
        try {
            if ($hoatdong_ctxh->dangKy()->exists()) { 
                return redirect()->route('nhanvien.hoatdong_ctxh.index')
                                 ->with('error', 'Không thể xóa hoạt động "' . $hoatdong_ctxh->TenHoatDong . '" vì đã có sinh viên đăng ký.');
            }
            
            $hoatdong_ctxh->delete();
            return redirect()->route('nhanvien.hoatdong_ctxh.index')
                             ->with('success', 'Xóa hoạt động CTXH thành công.');

        } catch (\Illuminate\Database\QueryException $e) {
             Log::error('Lỗi DB khi xóa CTXH: ' . $e->getMessage());
             return redirect()->route('nhanvien.hoatdong_ctxh.index')
                              ->with('error', 'Không thể xóa hoạt động này do có lỗi cơ sở dữ liệu hoặc ràng buộc dữ liệu.');
        }
         catch (\Exception $e) { 
             Log::error('Lỗi chung khi xóa CTXH: ' . $e->getMessage());
             return redirect()->route('nhanvien.hoatdong_ctxh.index')
                              ->with('error', 'Đã xảy ra lỗi không mong muốn khi xóa.');
         }
    }

    // ===================================================================
    // === CÁC HÀM ĐIỂM DANH (Không đổi) ===
    // ===================================================================

    public function generateQrTokens(Request $request, HoatDongCTXH $hoatdong_ctxh) // <-- Đã sửa (X hoa)
    {
        try {
            $hoatdong_ctxh->CheckInToken = Str::random(40);
            $hoatdong_ctxh->CheckOutToken = Str::random(40);
            $hoatdong_ctxh->TokenExpiresAt = $hoatdong_ctxh->ThoiGianKetThuc->addHours(2);
            $hoatdong_ctxh->save();

            return redirect()->back()->with('success', 'Đã tạo mã QR điểm danh thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi tạo QR CTXH: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Không thể tạo mã QR. Vui lòng thử lại.');
        }
    }

    public function finalizeAttendance(Request $request, HoatDongCTXH $hoatdong_ctxh) // <-- Đã sửa (X hoa)
    {
        try {
            $maHoatDong = $hoatdong_ctxh->MaHoatDong;

            // Vắng
            DangKyHoatDongCtxh::where('MaHoatDong', $maHoatDong)
                ->where('TrangThaiDangKy', 'Đã duyệt')
                ->where(function ($query) {
                    $query->whereNull('CheckInAt')
                          ->orWhereNull('CheckOutAt');
                })
                ->update(['TrangThaiThamGia' => 'Vắng']);
                
            // Đã tham gia
            DangKyHoatDongCtxh::where('MaHoatDong', $maHoatDong)
                ->where('TrangThaiDangKy', 'Đã duyệt')
                ->whereNotNull('CheckInAt')
                ->whereNotNull('CheckOutAt')
                ->update(['TrangThaiThamGia' => 'Đã tham gia']);

            return redirect()->back()->with('success', 'Đã tổng kết điểm danh thành công!');

        } catch (\Exception $e) {
            Log::error('Lỗi tổng kết điểm danh CTXH: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tổng kết. Vui lòng thử lại.');
        }
    }
}