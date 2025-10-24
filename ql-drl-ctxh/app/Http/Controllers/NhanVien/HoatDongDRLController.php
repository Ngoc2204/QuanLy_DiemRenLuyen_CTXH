<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use App\Models\HoatDongDRL;
use App\Models\QuyDinhDiemRL; 
use App\Models\HocKy; 
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Log;

class HoatDongDRLController extends Controller
{
    /**
     * Hiển thị danh sách các Hoạt động DRL.
     */
        public function index(Request $request)
    {
        // SỬA LẠI TÊN QUAN HỆ: 'dangKy' -> 'dangky', 'quyDinhDiem' -> 'quydinh'
        $query = HoatDongDRL::withCount('dangky') // Đếm số lượng đăng ký
                           ->with('hocKy', 'quydinh') // Lấy thêm thông tin học kỳ và quy định điểm
                           ->orderBy('ThoiGianBatDau', 'desc');

        // Tìm kiếm (ví dụ theo Tên hoạt động HOẶC Địa điểm)
        if ($request->has('search') && $request->search != '') {
             $searchTerm = '%' . $request->search . '%';
             $query->where(function ($q) use ($searchTerm) {
                 $q->where('TenHoatDong', 'like', $searchTerm)
                   ->orWhere('DiaDiem', 'like', $searchTerm); // Thêm tìm kiếm theo Địa điểm
             });
        }


        $hoatDongs = $query->paginate(10); // Phân trang 10 mục/trang

        // Tính toán chính xác hơn cho thẻ thống kê (tổng đăng ký và tổng số lượng)
        // Lấy tất cả MaHoatDong trên trang hiện tại
        $maHoatDongs = $hoatDongs->pluck('MaHoatDong');

        // Tính tổng đăng ký và số lượng dựa trên toàn bộ kết quả query (chưa phân trang)
        // Clone query để không ảnh hưởng đến $hoatDongs đã phân trang
        $statsQuery = clone $query;
        // Bỏ eager loading và order để count hiệu quả hơn
        $statsQuery->with([])->orderBy(null);
        // $tongSoLuongAll = $statsQuery->sum('SoLuong'); // Lấy tổng số lượng tối đa của tất cả hoạt động thỏa mãn tìm kiếm
        // $tongDangKyAll = \App\Models\DangKyHoatDongDrl::whereIn('MaHoatDong', $statsQuery->pluck('MaHoatDong'))->count(); // Đếm tổng đăng ký

        // Hoặc đơn giản là tính trên trang hiện tại nếu chỉ muốn hiển thị số liệu của trang đó
        $tongDangKyCurrentPage = $hoatDongs->sum('dangky_count');
        $tongSoLuongCurrentPage = $hoatDongs->sum('SoLuong');
        $tyLeLopDayCurrentPage = $tongSoLuongCurrentPage > 0 ? round(($tongDangKyCurrentPage / $tongSoLuongCurrentPage) * 100, 1) : 0;


        return view('nhanvien.hoatdong_drl.index', compact(
            'hoatDongs',
            'tongDangKyCurrentPage', // Truyền biến mới
            'tyLeLopDayCurrentPage' // Truyền biến mới
            // Có thể truyền thêm $tongSoLuongCurrentPage nếu cần
        ));
    }

    /**
     * Hiển thị form tạo mới Hoạt động DRL.
     */
    public function create()
    {
        // Lấy danh sách Quy định điểm DRL
        $quyDinhDiems = QuyDinhDiemRl::orderBy('TenCongViec')->pluck('TenCongViec', 'MaDiem');
        // Lấy danh sách Học Kỳ
        $hocKys = HocKy::orderBy('NgayBatDau', 'desc')->pluck('TenHocKy', 'MaHocKy');
        // Đổi tên view
        return view('nhanvien.hoatdong_drl.create', compact('quyDinhDiems', 'hocKys'));
    }

    /**
     * Lưu Hoạt động DRL mới vào database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'TenHoatDong' => 'required|string|max:255',
            'MoTa' => 'nullable|string',
            'ThoiGianBatDau' => 'required|date|after_or_equal:now',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'ThoiHanHuy' => 'nullable|date|before:ThoiGianBatDau',
            'DiaDiem' => 'nullable|string|max:255',
            'SoLuong' => 'required|integer|min:1',
            'LoaiHoatDong' => 'required|string|max:100',
            'MaHocKy' => 'required|exists:hocky,MaHocKy',
            'MaQuyDinhDiem' => 'required|exists:quydinhdiemrl,MaDiem',
        ]);

        // Tạo Mã Hoạt động duy nhất (ví dụ: DRL-YYYYMMDD-XXXX)
        $maHoatDong = 'DRL-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        while (HoatDongDrl::where('MaHoatDong', $maHoatDong)->exists()) {
             $maHoatDong = 'DRL-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        }
        $validatedData['MaHoatDong'] = $maHoatDong;

        try {
             HoatDongDrl::create($validatedData); // Đổi Model
             // Đổi route và thông báo
             return redirect()->route('nhanvien.hoatdong_drl.index')
                             ->with('success', 'Tạo hoạt động DRL thành công.');
        } catch (\Exception $e) {
             // Ghi log lỗi để debug
             Log::error('Lỗi khi tạo hoạt động DRL: ' . $e->getMessage());
             return redirect()->back()
                             ->with('error', 'Tạo hoạt động thất bại. Vui lòng thử lại.') // Thông báo chung chung hơn
                             ->withInput();
        }
    }

    /**
     * Hiển thị chi tiết một Hoạt động DRL.
     */
    // Đổi tên biến $hoatdong_ctxh thành $hoatdong_drl
    public function show(HoatDongDrl $hoatdong_drl)
    {
        // Tải các quan hệ cần thiết
        // SỬA LẠI TÊN QUAN HỆ: 'quyDinhDiem' -> 'quydinh'
        $hoatdong_drl->load('sinhVienDangKy', 'hocKy', 'quydinh');
        
        return view('nhanvien.hoatdong_drl.show', compact('hoatdong_drl'));
    }

    /**
     * Hiển thị form chỉnh sửa Hoạt động DRL.
     */
    // Đổi tên biến
    public function edit(HoatDongDrl $hoatdong_drl)
    {
        $quyDinhDiems = QuyDinhDiemRl::orderBy('TenCongViec')->pluck('TenCongViec', 'MaDiem');
        $hocKys = HocKy::orderBy('NgayBatDau', 'desc')->pluck('TenHocKy', 'MaHocKy');
        // Đổi tên view
        return view('nhanvien.hoatdong_drl.edit', compact('hoatdong_drl', 'quyDinhDiems', 'hocKys'));
    }

    /**
     * Cập nhật Hoạt động DRL trong database.
     */
    // Đổi tên biến
    public function update(Request $request, HoatDongDrl $hoatdong_drl)
    {
         $validatedData = $request->validate([
            'TenHoatDong' => 'required|string|max:255',
            'MoTa' => 'nullable|string',
            'ThoiGianBatDau' => 'required|date',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'ThoiHanHuy' => 'nullable|date|before:ThoiGianBatDau',
            'DiaDiem' => 'nullable|string|max:255',
            'SoLuong' => 'required|integer|min:1',
            'LoaiHoatDong' => 'required|string|max:100',
            'MaHocKy' => 'required|exists:hocky,MaHocKy', 
            'MaQuyDinhDiem' => 'required|exists:quydinhdiemrl,MaDiem',
        ]);

         try {
             $hoatdong_drl->update($validatedData); // Đổi biến
             // Đổi route và thông báo
             return redirect()->route('nhanvien.hoatdong_drl.index')
                             ->with('success', 'Cập nhật hoạt động DRL thành công.');
        } catch (\Exception $e) {
             Log::error('Lỗi khi cập nhật hoạt động DRL: ' . $e->getMessage());
             return redirect()->back()
                             ->with('error', 'Cập nhật hoạt động thất bại. Vui lòng thử lại.')
                             ->withInput();
        }
    }

    /**
     * Xóa Hoạt động DRL khỏi database.
     */
    // Đổi tên biến
    public function destroy(HoatDongDrl $hoatdong_drl)
    {
        try {
            // Kiểm tra xem có sinh viên nào đã đăng ký hoạt động này chưa
            // Đổi tên quan hệ thành 'dangKy' (định nghĩa trong Model HoatDongDrl)
            if ($hoatdong_drl->dangKy()->exists()) {
                // Đổi route và thông báo
                return redirect()->route('nhanvien.hoatdong_drl.index')
                                ->with('error', 'Không thể xóa hoạt động "' . $hoatdong_drl->TenHoatDong . '" vì đã có sinh viên đăng ký.');
            }

            $hoatdong_drl->delete(); // Đổi biến
            // Đổi route và thông báo
            return redirect()->route('nhanvien.hoatdong_drl.index')
                            ->with('success', 'Xóa hoạt động DRL thành công.');

        } catch (\Illuminate\Database\QueryException $e) {
             Log::error('Lỗi DB khi xóa hoạt động DRL: ' . $e->getMessage());
             // Đổi route
            return redirect()->route('nhanvien.hoatdong_drl.index')
                            ->with('error', 'Không thể xóa hoạt động này do có lỗi cơ sở dữ liệu hoặc ràng buộc dữ liệu.');
        }
         catch (\Exception $e) {
            Log::error('Lỗi chung khi xóa hoạt động DRL: ' . $e->getMessage());
             // Đổi route
            return redirect()->route('nhanvien.hoatdong_drl.index')
                            ->with('error', 'Đã xảy ra lỗi không mong muốn khi xóa.');
        }
    }
}
