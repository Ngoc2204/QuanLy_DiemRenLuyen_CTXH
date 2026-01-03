<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\KMeansClusteringService;

class KMeansRecommendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clustering:kmeans-recommend
                            {--cluster-count=4 : Số cụm}
                            {--run-clustering : Chạy K-Means}
                            {--generate-recommendations : Tạo gợi ý}
                            {--force : Ghi đè dữ liệu cũ}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chạy K-Means clustering và tạo gợi ý hoạt động cho sinh viên';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clusterCount = (int)$this->option('cluster-count');
        $runClustering = $this->option('run-clustering');
        $generateRecommendations = $this->option('generate-recommendations');
        $force = $this->option('force');

        $this->info('🚀 Bắt đầu K-Means Clustering Service...');

        $service = new KMeansClusteringService($clusterCount);

        // Phase 1: Xây dựng Feature Vectors
        $this->info('📊 Phase 1: Xây dựng Feature Vectors...');
        $vectors = $service->buildFeatureVectors();
        $this->info("✅ Đã xây dựng " . count($vectors) . " vectors (mỗi vector có 30 chiều: 10 explicit + 10 implicit + 2 behavioral + 1 performance + N faculty + 1 year)");

        // Phase 2: Chạy K-Means
        if ($runClustering || !$runClustering) { // Mặc định chạy
            $this->info('🔄 Phase 2: Chạy K-Means Clustering (max 100 iterations)...');
            $result = $service->cluster($vectors);
            $assignments = $result['assignments'];
            $centroids = $result['centroids'];
            $iterations = $result['iterations'];
            
            $this->info("✅ Phân cụm thành công: " . $clusterCount . " cụm (sau $iterations iterations)");

            // Lưu kết quả
            $this->info('💾 Phase 3: Lưu kết quả vào database...');
            $service->saveClusterAssignments($assignments);
            $this->info('✅ Đã lưu cluster assignments');

            // Tính toán cluster statistics
            $this->info('📈 Phase 4: Tính toán Cluster Statistics...');
            $service->calculateClusterStatistics();
            $this->info('✅ Đã tính cluster statistics');

            // Tạo recommendations
            $this->info('🎯 Phase 5: Tạo gợi ý hoạt động...');
            $service->generateRecommendations();
            $this->info('✅ Đã tạo gợi ý hoạt động');

            // Display Quality Metrics
            $this->displayQualityMetrics($service, $vectors, $centroids, $assignments);
        }

        $this->info('✨ Hoàn tất! K-Means clustering đã xong.');
    }

    private function displayQualityMetrics($service, $vectors, $centroids, $assignments)
    {
        $this->newLine();
        $this->info('📊 ===== CLUSTERING QUALITY METRICS =====');
        $this->newLine();

        $metrics = $service->getClusteringMetrics($vectors, $centroids, $assignments);

        $this->line('Metric                      | Value');
        $this->line(str_repeat('─', 50));
        $this->line(sprintf('%-28s | %.4f', 'Inertia (WCSS)', $metrics['inertia']));
        $this->line(sprintf('%-28s | %.4f (-1 to 1)', 'Silhouette Score', $metrics['silhouette_score']));
        $this->line(sprintf('%-28s | %.4f', 'Davies-Bouldin Index', $metrics['davies_bouldin_index']));
        $this->line(sprintf('%-28s | %d', 'Number of Clusters', $metrics['num_clusters']));
        $this->line(sprintf('%-28s | %d', 'Number of Samples', $metrics['num_samples']));
        $this->newLine();

        // Display interpretation
        $this->line('📖 Giải thích:');
        $this->line('  • Inertia (WCSS): Tổng bình phương khoảng cách trong cluster');
        $this->line('    → Thấp hơn = Cluster tập trung hơn');
        $this->line('  • Silhouette Score: Độ tốt của clustering (-1 to 1)');
        $this->line('    → 1: Rất tốt | 0: Trung bình | -1: Rất tệ');
        $this->line('  • Davies-Bouldin Index: Tỷ lệ scatter/separation');
        $this->line('    → Thấp hơn = Clustering tốt hơn');
        $this->newLine();

        // Cluster distribution
        $this->line('👥 Phân bổ sinh viên theo cluster:');
        $distribution = $this->getClusterDistribution($assignments);
        foreach ($distribution as $cluster => $count) {
            $percentage = ($count / count($assignments)) * 100;
            $bar = str_repeat('█', intval($percentage / 5));
            $this->line(sprintf('  Cluster %d: %3d sinh viên (%5.1f%%) %s', 
                $cluster, $count, $percentage, $bar));
        }
        $this->newLine();
    }

    private function getClusterDistribution($assignments)
    {
        $distribution = [];
        foreach ($assignments as $assignment) {
            if (!isset($distribution[$assignment])) {
                $distribution[$assignment] = 0;
            }
            $distribution[$assignment]++;
        }
        ksort($distribution);
        return $distribution;
    }
}
