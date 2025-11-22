<?php

namespace App\Console\Commands;

use App\Services\ClusterValidationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RunClusterValidation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clustering:validate {--full : Chạy validation đầy đủ} {--internal : Chỉ validation nội bộ} {--external : Chỉ validation ngoài} {--stability : Chỉ validation ổn định} {--recommendations : Chỉ validation gợi ý} {--save : Lưu kết quả vào database}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Đánh giá chất lượng và độ tin cậy của thuật toán K-Means Clustering';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');
        $this->line('╔═══════════════════════════════════════════════════════════╗');
        $this->line('║  VALIDATION THUẬT TOÁN K-MEANS CLUSTERING                  ║');
        $this->line('╚═══════════════════════════════════════════════════════════╝');
        $this->line('');

        $service = new ClusterValidationService();
        
        // Mặc định chạy validation đầy đủ
        $full = $this->option('full') || (!$this->option('internal') && !$this->option('external') && 
                !$this->option('stability') && !$this->option('recommendations'));

        if ($full) {
            $this->info('Đang chạy validation ĐẦY ĐỦ...');
            $report = $service->generateFullValidationReport();
            $this->displayFullReport($report);
            
            if ($this->option('save')) {
                $this->saveReport($report);
            }
        } else {
            if ($this->option('internal')) {
                $this->info('Validation: INTERNAL QUALITY');
                $result = $service->validateInternalQuality();
                $this->displayInternalQuality($result);
            }
            
            if ($this->option('external')) {
                $this->info('Validation: EXTERNAL RELEVANCE');
                $result = $service->validateExternalRelevance();
                $this->displayExternalRelevance($result);
            }
            
            if ($this->option('stability')) {
                $this->info('Validation: STABILITY');
                $result = $service->validateStability();
                $this->displayStability($result);
            }
            
            if ($this->option('recommendations')) {
                $this->info('Validation: RECOMMENDATION QUALITY');
                $result = $service->validateRecommendationQuality();
                $this->displayRecommendationQuality($result);
            }
        }

        $this->line('');
        $this->info('✓ Validation hoàn thành!');
    }

    /**
     * Hiển thị báo cáo đầy đủ
     */
    protected function displayFullReport($report)
    {
        $this->line('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->line('OVERALL SCORE: ' . $report['overall_score'] . ' / 1.0');
        $this->line('INTERPRETATION: ' . $report['interpretation']);
        $this->info('═══════════════════════════════════════════════════════════');
        $this->line('');

        $this->displayInternalQuality($report['internal_validation']);
        $this->line('');

        $this->displayExternalRelevance($report['external_validation']);
        $this->line('');

        $this->displayStability($report['stability_validation']);
        $this->line('');

        $this->displayRecommendationQuality($report['recommendation_quality']);
        $this->line('');

        $this->displayBusinessMetrics($report['business_metrics']);
        $this->line('');

        $this->displayRecommendations($report['overall_score']);
    }

    /**
     * Hiển thị Internal Quality
     */
    protected function displayInternalQuality($result)
    {
        $this->comment('┌─ INTERNAL QUALITY (Chất lượng nội bộ)');
        
        if (isset($result['status']) && $result['status'] === 'error') {
            $this->error('  ✗ ' . $result['message']);
            return;
        }

        $metrics = [
            'Silhouette Score' => $result['silhouette_score'] ?? null,
            'Davies-Bouldin Index' => $result['davies_bouldin_index'] ?? null,
            'Calinski-Harabasz Index' => $result['calinski_harabasz_index'] ?? null,
            'Cluster Balance' => $result['cluster_balance'] ?? null,
        ];

        foreach ($metrics as $name => $metric) {
            if ($metric) {
                $this->line('  │');
                $this->line("  ├─ {$name}");
                $this->line("  │  Value: " . $metric['value']);
                $this->line("  │  Interpretation: " . $metric['interpretation']);
                $this->line("  │  Weight: " . ($metric['weight'] * 100) . '%');
            }
        }
        
        $this->line('  │');
        $this->line('  └─ Overall Internal Score: ' . $this->getScore($result));
    }

    /**
     * Hiển thị External Relevance
     */
    protected function displayExternalRelevance($result)
    {
        $this->comment('┌─ EXTERNAL RELEVANCE (Liên quan bên ngoài)');
        
        if (isset($result['status']) && $result['status'] === 'error') {
            $this->error('  ✗ ' . $result['message']);
            return;
        }

        $metrics = [
            'Interest Cohesion' => $result['interest_cohesion'] ?? null,
            'Activity Behavior Cohesion' => $result['activity_behavior_cohesion'] ?? null,
            'Performance Cohesion' => $result['performance_cohesion'] ?? null,
        ];

        foreach ($metrics as $name => $metric) {
            if ($metric) {
                $this->line('  │');
                $this->line("  ├─ {$name}");
                $this->line("  │  Value: " . $metric['value']);
                $this->line("  │  Interpretation: " . $metric['interpretation']);
                $this->line("  │  Weight: " . ($metric['weight'] * 100) . '%');
            }
        }
        
        $this->line('  │');
        $this->line('  └─ Overall External Score: ' . $this->getScore($result));
    }

    /**
     * Hiển thị Stability
     */
    protected function displayStability($result)
    {
        $this->comment('┌─ STABILITY VALIDATION (Độ ổn định)');
        
        $this->line('  │');
        $this->line("  ├─ Number of Runs: " . $result['num_runs']);
        $this->line('  │');
        $this->line("  ├─ Adjusted Rand Index: " . $result['adjusted_rand_index']['value']);
        $this->line("  │  " . $result['adjusted_rand_index']['interpretation']);
        $this->line('  │');
        $this->line("  └─ Consistency Rate: " . $result['consistency_rate']['value']);
        $this->line("     " . $result['consistency_rate']['interpretation']);
    }

    /**
     * Hiển thị Recommendation Quality
     */
    protected function displayRecommendationQuality($result)
    {
        $this->comment('┌─ RECOMMENDATION QUALITY (Chất lượng gợi ý)');
        
        $this->line('  │');
        $this->line("  ├─ Coverage: " . ($result['coverage']['value'] * 100) . '%');
        $this->line("  │  " . $result['coverage']['interpretation']);
        $this->line('  │');
        $this->line("  ├─ Relevance Score: " . $result['relevance_score']['value']);
        $this->line("  │  " . $result['relevance_score']['interpretation']);
        $this->line('  │');
        $this->line("  └─ Click-Through Rate: " . ($result['click_through_rate']['value'] * 100) . '%');
        $this->line("     " . $result['click_through_rate']['interpretation']);
    }

    /**
     * Hiển thị Business Metrics
     */
    protected function displayBusinessMetrics($metrics)
    {
        $this->comment('┌─ BUSINESS METRICS (Chỉ số kinh doanh)');
        
        if (isset($metrics['cluster_size_distribution']) && !isset($metrics['cluster_size_distribution']['status'])) {
            $this->line('  │');
            $this->line('  ├─ Cluster Size Distribution');
            $this->line("  │  Min Size: " . $metrics['cluster_size_distribution']['min_size']);
            $this->line("  │  Max Size: " . $metrics['cluster_size_distribution']['max_size']);
            $this->line("  │  Avg Size: " . round($metrics['cluster_size_distribution']['avg_size'], 2));
            $this->line("  │  Balance: " . $metrics['cluster_size_distribution']['balance_score']);
        }

        if (isset($metrics['recommendation_acceptance_rate']) && isset($metrics['recommendation_acceptance_rate']['acceptance_rate'])) {
            $this->line('  │');
            $this->line('  ├─ Recommendation Acceptance Rate');
            $this->line("  │  " . ($metrics['recommendation_acceptance_rate']['acceptance_rate'] * 100) . '%');
        }

        if (isset($metrics['cold_start_handling']) && isset($metrics['cold_start_handling']['coverage_rate'])) {
            $this->line('  │');
            $this->line('  ├─ Cold Start Handling');
            $this->line("  │  Coverage: " . ($metrics['cold_start_handling']['coverage_rate'] * 100) . '%');
            $this->line("  │  " . $metrics['cold_start_handling']['interpretation']);
        }

        if (isset($metrics['recommendation_freshness']) && isset($metrics['recommendation_freshness']['freshness_rate'])) {
            $this->line('  │');
            $this->line('  └─ Recommendation Freshness');
            $this->line("     " . ($metrics['recommendation_freshness']['freshness_rate'] * 100) . '%');
            $this->line("     " . $metrics['recommendation_freshness']['interpretation']);
        }
    }

    /**
     * Hiển thị gợi ý cải thiện
     */
    protected function displayRecommendations($overallScore)
    {
        $this->line('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->line('RECOMMENDATIONS');
        $this->info('═══════════════════════════════════════════════════════════');
        
        if ($overallScore >= 0.8) {
            $this->line('✓ Thuật toán hoạt động XUẤT SẮC!');
            $this->line('  - Có thể triển khai vào sản phẩm');
            $this->line('  - Tiếp tục theo dõi metrics');
        } elseif ($overallScore >= 0.6) {
            $this->line('✓ Thuật toán hoạt động TỐT');
            $this->line('  - Có thể sử dụng với lưới ý');
            $this->line('  - Kiểm tra các metrics yếu');
        } elseif ($overallScore >= 0.4) {
            $this->line('⚠ Thuật toán có thể CẢI THIỆN');
            $this->line('  - Nên điều chỉnh feature engineering');
            $this->line('  - Thử tăng/giảm số cluster');
        } else {
            $this->line('✗ Thuật toán CẦN XEM XÉT LẠI');
            $this->line('  - Kiểm tra dữ liệu input');
            $this->line('  - Xem xét lại design của feature vector');
        }
    }

    /**
     * Lấy overall score từ metrics
     */
    protected function getScore($metrics)
    {
        $totalWeight = 0;
        $weightedSum = 0;

        foreach ($metrics as $key => $metric) {
            if ($key === 'overall_score' || !is_array($metric) || !isset($metric['weight'])) {
                continue;
            }

            $weight = $metric['weight'];
            $totalWeight += $weight;
            $value = $metric['value'];

            // Normalize
            if (is_numeric($value)) {
                $normalized = min(1, max(0, $value > 1 ? $value / 100 : $value));
                $weightedSum += $normalized * $weight;
            }
        }

        return $totalWeight > 0 ? number_format($weightedSum / $totalWeight, 4) : 0;
    }

    /**
     * Lưu report vào database
     */
    protected function saveReport($report)
    {
        try {
            DB::table('validation_reports')->insert([
                'validation_date' => $report['timestamp'],
                'silhouette_score' => $report['internal_validation']['silhouette_score']['value'] ?? null,
                'davies_bouldin_index' => $report['internal_validation']['davies_bouldin_index']['value'] ?? null,
                'calinski_harabasz_index' => $report['internal_validation']['calinski_harabasz_index']['value'] ?? null,
                'cluster_balance' => $report['internal_validation']['cluster_balance']['value'] ?? null,
                'interest_cohesion' => $report['external_validation']['interest_cohesion']['value'] ?? null,
                'activity_behavior_cohesion' => $report['external_validation']['activity_behavior_cohesion']['value'] ?? null,
                'performance_cohesion' => $report['external_validation']['performance_cohesion']['value'] ?? null,
                'adjusted_rand_index' => $report['stability_validation']['adjusted_rand_index']['value'] ?? null,
                'consistency_rate' => $report['stability_validation']['consistency_rate']['value'] ?? null,
                'coverage' => $report['recommendation_quality']['coverage']['value'] ?? null,
                'relevance_score' => $report['recommendation_quality']['relevance_score']['value'] ?? null,
                'click_through_rate' => $report['recommendation_quality']['click_through_rate']['value'] ?? null,
                'overall_score' => $report['overall_score'],
                'interpretation' => $report['interpretation'],
                'business_metrics' => json_encode($report['business_metrics']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info('✓ Kết quả đã lưu vào database');
        } catch (\Exception $e) {
            $this->error('✗ Lỗi khi lưu: ' . $e->getMessage());
        }
    }
}
