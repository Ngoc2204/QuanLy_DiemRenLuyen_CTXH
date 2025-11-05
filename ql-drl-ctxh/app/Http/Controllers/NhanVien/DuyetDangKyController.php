<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use App\Models\DangKyHoatDongDrl;
use App\Models\DangKyHoatDongCtxh;
use App\Models\HoatDongDrl; // Đảm bảo đã use
use App\Models\HoatDongCtxh; // Đảm bảo đã use
use App\Models\SinhVien; // Đảm bảo đã use
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// Thêm các class để tự phân trang
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DuyetDangKyController extends Controller
{
    /**
     * Hiển thị danh sách các đăng ký đang chờ duyệt.
     */
    public function index(Request $request)
    {
        $trangThai = 'Chờ duyệt';
        $type = $request->input('type');
        $date = $request->input('date'); // <--- LẤY NGÀY TỪ REQUEST

        $drlRegistrations = collect();
        $ctxhRegistrations = collect();

        // 1. Tải DRL
        if (empty($type) || $type == 'DRL') {
            // <--- THÊM QUERY VỚI/KHÔNG CÓ NGÀY
            $drlQuery = DangKyHoatDongDrl::where('TrangThaiDangKy', $trangThai)
                            ->with(['sinhvien', 'hoatdong']);

            if ($date) {
                // Lọc theo ngày nếu có
                $drlQuery->whereDate('NgayDangKy', $date);
            }

            $drlRegistrations = $drlQuery->get()
                ->map(function ($item) {
                    $item->type = 'DRL'; 
                    $item->hoatDongRel = $item->hoatdong; 
                    unset($item->hoatdong); 
                    return $item;
                });
        }

        // 2. Tải CTXH
        if (empty($type) || $type == 'CTXH') {
            // <--- THÊM QUERY VỚI/KHÔNG CÓ NGÀY
             $ctxhQuery = DangKyHoatDongCtxh::where('TrangThaiDangKy', $trangThai)
                            ->with(['sinhvien', 'hoatdong']);

            if ($date) {
                // Lọc theo ngày nếu có
                $ctxhQuery->whereDate('NgayDangKy', $date);
            }
            
            $ctxhRegistrations = $ctxhQuery->get()
                ->map(function ($item) {
                    $item->type = 'CTXH'; 
                    $item->hoatDongRel = $item->hoatdong; 
                    unset($item->hoatdong); 
                    return $item;
                });
        }

        // 3. Gộp và sắp xếp
        $allRegistrations = $drlRegistrations->merge($ctxhRegistrations);
        $sortedRegistrations = $allRegistrations->sortByDesc('NgayDangKy');

        // 4. Tự tạo phân trang (Giữ nguyên)
        $perPage = 15;
        $currentPage = Paginator::resolveCurrentPage() ?: 1;
        $currentPageItems = $sortedRegistrations->slice(($currentPage - 1) * $perPage, $perPage);
        $dangKys = new LengthAwarePaginator(
            $currentPageItems,
            $sortedRegistrations->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()] 
        );
        $dangKys->appends($request->query());

        // 5. Trả về view
        // <--- TRUYỀN THÊM BIẾN 'date' SANG VIEW
        return view('nhanvien.duyet_dang_ky.index', compact('dangKys', 'date'));
    }

    /**
     * Cập nhật trạng thái đăng ký (Duyệt hoặc Từ chối).
     */
    public function update(Request $request, $duyet_dang_ky) // Sửa tham số ở đây
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        // Lấy type từ query string (?type=...)
        $type = $request->query('type');
        $id = $duyet_dang_ky; // $duyet_dang_ky chính là $id từ route

        $newStatus = ($request->action == 'approve') ? 'Đã duyệt' : 'Bị từ chối';

        try {
            if ($type == 'DRL') {
                $dangKy = DangKyHoatDongDrl::findOrFail($id);
            } elseif ($type == 'CTXH') {
                $dangKy = DangKyHoatDongCtxh::findOrFail($id);
            } else {
                return redirect()->back()->with('error', 'Loại đăng ký không hợp lệ.');
            }

            if ($dangKy->TrangThaiDangKy == 'Chờ duyệt') {
                $dangKy->TrangThaiDangKy = $newStatus;
                $dangKy->save();
                return redirect()->back()->with('success', "Đã {$newStatus} đăng ký thành công.");
            } else {
                return redirect()->back()->with('warning', "Đăng ký này không còn ở trạng thái 'Chờ duyệt'.");
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Không tìm thấy đăng ký.');
        } catch (\Exception $e) {
            Log::error("Lỗi duyệt đăng ký {$type} ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    public function batchApprove(Request $request)
    {
        $validated = $request->validate([
            'approve_date' => 'required|date',
            'approve_type' => 'nullable|in:DRL,CTXH', // Lấy type từ filter
        ]);

        $date = $validated['approve_date'];
        $type = $validated['approve_type'];
        $trangThai = 'Chờ duyệt';
        $approvedCount = 0;

        try {
            // Duyệt DRL
            if (empty($type) || $type == 'DRL') {
                $countDRL = DangKyHoatDongDrl::where('TrangThaiDangKy', $trangThai)
                                          ->whereDate('NgayDangKy', $date) // Lọc theo ngày
                                          ->update(['TrangThaiDangKy' => 'Đã duyệt']);
                $approvedCount += $countDRL;
            }

            // Duyệt CTXH
            if (empty($type) || $type == 'CTXH') {
                $countCTXH = DangKyHoatDongCtxh::where('TrangThaiDangKy', $trangThai)
                                           ->whereDate('NgayDangKy', $date) // Lọc theo ngày
                                           ->update(['TrangThaiDangKy' => 'Đã duyệt']);
                $approvedCount += $countCTXH;
            }

            if ($approvedCount > 0) {
                return redirect()->back()->with('success', "Đã duyệt thành công {$approvedCount} đăng ký cho ngày {$date}.");
            } else {
                return redirect()->back()->with('warning', "Không có đăng ký nào 'Chờ duyệt' cho ngày {$date} để cập nhật.");
            }

        } catch (\Exception $e) {
            Log::error("Lỗi duyệt hàng loạt ngày {$date}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra trong quá trình duyệt hàng loạt, vui lòng thử lại.');
        }
    }
}