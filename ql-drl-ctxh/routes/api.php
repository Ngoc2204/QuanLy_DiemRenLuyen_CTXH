<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ActivityApiController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\ScoreApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes không cần xác thực
Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::post('/refresh', [AuthApiController::class, 'refresh']);
    
    // Test activities without auth
    Route::get('/activities-test', [ActivityApiController::class, 'index']);
});

// Routes cần xác thực
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // User profile
    Route::get('/user', [AuthApiController::class, 'user']);
    Route::put('/user/profile', [ProfileApiController::class, 'updateProfile']);
    Route::post('/user/change-password', [ProfileApiController::class, 'changePassword']);
    
    // Activities
    Route::get('/activities', [ActivityApiController::class, 'index']);
    Route::get('/activities/{id}', [ActivityApiController::class, 'show']);
    Route::get('/activities/drl/available', [ActivityApiController::class, 'getAvailableDRLActivities']);
    Route::get('/activities/ctxh/available', [ActivityApiController::class, 'getAvailableCTXHActivities']);
    Route::post('/activities/drl/{id}/register', [ActivityApiController::class, 'registerDRL']);
    Route::post('/activities/ctxh/{id}/register', [ActivityApiController::class, 'registerCTXH']);
    Route::delete('/activities/drl/{id}/unregister', [ActivityApiController::class, 'unregisterDRL']);
    Route::delete('/activities/ctxh/{id}/unregister', [ActivityApiController::class, 'unregisterCTXH']);
    
    // Registered activities
    Route::get('/my-registrations', [ActivityApiController::class, 'getMyRegistrations']);
    Route::delete('/my-registrations/cancel-drl/{id}', [ActivityApiController::class, 'cancelRegistrationDRL']);
    Route::delete('/my-registrations/cancel-ctxh/{id}', [ActivityApiController::class, 'cancelRegistrationCTXH']);
    
    // Attendance
    Route::post('/attendance/scan', [AttendanceApiController::class, 'scanQR']);
    Route::get('/attendance/history', [AttendanceApiController::class, 'getAttendanceHistory']);
    
    // Scores
    Route::get('/scores/drl', [ActivityApiController::class, 'getDRLScores']);
    
    // Notifications
    Route::get('/notifications', [NotificationApiController::class, 'index']);
    Route::put('/notifications/{id}/mark-read', [NotificationApiController::class, 'markAsRead']);
    
    // Dashboard data
    Route::get('/dashboard', [ActivityApiController::class, 'getDashboardData']);
    
    // Weekly schedule
    Route::get('/schedule/weekly', [ActivityApiController::class, 'getWeeklySchedule']);
    
    // Recommendations
    Route::get('/recommendations', [ActivityApiController::class, 'getRecommendations']);
    
    // Diem ren luyen data
    Route::get('/diem-ren-luyen', [ActivityApiController::class, 'getDiemRenLuyenData']);
    
    // Diem CTXH data
    Route::get('/diem-ctxh', [ActivityApiController::class, 'getDiemCTXHData']);
    
    // Recommended activities
    Route::get('/activities/recommended', [ActivityApiController::class, 'getRecommendedActivities']);
    
    // Contact/Feedback
    Route::get('/profile', [ProfileApiController::class, 'getProfile']);
    Route::get('/interests', [ProfileApiController::class, 'getInterests']);
    Route::put('/profile/update', [ProfileApiController::class, 'updateProfileComplete']);
    Route::post('/lienhe', [ProfileApiController::class, 'submitFeedback']);
    
    // Payments
    Route::get('/payments/pending', [PaymentApiController::class, 'getPendingPayments']);
    Route::get('/payments/{id}', [PaymentApiController::class, 'getPaymentDetails']);
    Route::post('/payments/{id}/confirm-method', [PaymentApiController::class, 'confirmPaymentMethod']);
    Route::get('/registrations/{id}/payment', [PaymentApiController::class, 'getPaymentByRegistration']);
    
    // Scores - Student view
    Route::get('/scores/pending', [ScoreApiController::class, 'getPendingScores']);
    Route::get('/scores/summary', [ScoreApiController::class, 'getScoreSummary']);
    
    // Scores - Admin record attendance
    Route::post('/admin/record-attendance', [ScoreApiController::class, 'recordAttendance']);
});

// Test route
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!',
        'timestamp' => now(),
        'version' => 'v1.0.0'
    ]);
});

// Test activities route
Route::get('/test-activities', function () {
    try {
        $drlCount = \App\Models\HoatDongDRL::where('ThoiGianBatDau', '>=', now())->count();
        $ctxhCount = \App\Models\HoatDongCTXH::where('ThoiGianBatDau', '>=', now())->count();
        
        $drlActivities = \App\Models\HoatDongDRL::with(['quydinh'])
            ->where('ThoiGianBatDau', '>=', now())
            ->orderBy('ThoiGianBatDau', 'asc')
            ->take(2)
            ->get()
            ->map(function($activity) {
                return [
                    'id' => $activity->MaHoatDong,
                    'ten' => $activity->TenHoatDong,
                    'mo_ta' => $activity->MoTa,
                    'ngay_to_chuc' => $activity->ThoiGianBatDau,
                    'dia_diem' => $activity->DiaDiem,
                    'so_luong_toi_da' => $activity->SoLuong,
                    'diem_rl' => $activity->quydinh->DiemNhan ?? 0,
                    'type' => 'DRL'
                ];
            });

        return response()->json([
            'success' => true,
            'counts' => [
                'drl' => $drlCount,
                'ctxh' => $ctxhCount
            ],
            'sample_drl' => $drlActivities
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});