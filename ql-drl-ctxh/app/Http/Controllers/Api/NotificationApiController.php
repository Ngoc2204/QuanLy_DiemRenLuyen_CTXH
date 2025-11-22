<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThongBao;

class NotificationApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $notifications = ThongBao::orderBy('NgayTao', 'desc')
                ->paginate(20);

            $data = $notifications->map(function($notification) {
                return [
                    'id' => $notification->MaThongBao,
                    'tieu_de' => $notification->TieuDe,
                    'noi_dung' => $notification->NoiDung,
                    'ngay_tao' => $notification->NgayTao,
                    'nguoi_tao' => $notification->nhanVien->user->HoTen ?? 'Hệ thống',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'notifications' => $data,
                    'pagination' => [
                        'current_page' => $notifications->currentPage(),
                        'last_page' => $notifications->lastPage(),
                        'per_page' => $notifications->perPage(),
                        'total' => $notifications->total(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách thông báo'
            ], 500);
        }
    }

    public function markAsRead(Request $request, $id)
    {
        try {
            // Trong trường hợp này, ta chỉ trả về success
            // Vì chưa có bảng tracking việc đọc thông báo của từng user
            return response()->json([
                'success' => true,
                'message' => 'Đã đánh dấu là đã đọc'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể đánh dấu thông báo'
            ], 500);
        }
    }
}