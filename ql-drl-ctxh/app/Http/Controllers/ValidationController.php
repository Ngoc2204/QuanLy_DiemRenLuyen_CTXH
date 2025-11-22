<?php

namespace App\Http\Controllers;

use App\Services\ClusterValidationService;
use Illuminate\Http\JsonResponse;

class ValidationController extends Controller
{
    protected $validationService;

    public function __construct()
    {
        $this->validationService = new ClusterValidationService();
    }

    /**
     * Chạy toàn bộ validation và trả về báo cáo
     * 
     * API Endpoint: GET /api/validation/report
     */
    public function getValidationReport(): JsonResponse
    {
        try {
            $report = $this->validationService->generateFullValidationReport();
            
            return response()->json([
                'success' => true,
                'data' => $report
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Chỉ kiểm tra chất lượng clustering nội bộ
     * 
     * API Endpoint: GET /api/validation/internal-quality
     */
    public function getInternalQuality(): JsonResponse
    {
        try {
            $quality = $this->validationService->validateInternalQuality();
            
            return response()->json([
                'success' => true,
                'data' => $quality
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Chỉ kiểm tra liên quan bên ngoài
     * 
     * API Endpoint: GET /api/validation/external-relevance
     */
    public function getExternalRelevance(): JsonResponse
    {
        try {
            $relevance = $this->validationService->validateExternalRelevance();
            
            return response()->json([
                'success' => true,
                'data' => $relevance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Chỉ kiểm tra độ ổn định
     * 
     * API Endpoint: GET /api/validation/stability
     */
    public function getStability(): JsonResponse
    {
        try {
            $stability = $this->validationService->validateStability(3);
            
            return response()->json([
                'success' => true,
                'data' => $stability
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Chỉ kiểm tra chất lượng gợi ý
     * 
     * API Endpoint: GET /api/validation/recommendation-quality
     */
    public function getRecommendationQuality(): JsonResponse
    {
        try {
            $quality = $this->validationService->validateRecommendationQuality();
            
            return response()->json([
                'success' => true,
                'data' => $quality
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Chỉ kiểm tra business metrics
     * 
     * API Endpoint: GET /api/validation/business-metrics
     */
    public function getBusinessMetrics(): JsonResponse
    {
        try {
            $metrics = $this->validationService->calculateBusinessMetrics();
            
            return response()->json([
                'success' => true,
                'data' => $metrics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
