<?php

namespace App\Services;

use App\Models\StudentCluster;
use App\Models\SinhVien;
use App\Models\StudentInterest;
use App\Models\DangKyHoatDongDRL;
use App\Models\DiemRenLuyen;
use Illuminate\Support\Facades\DB;

class ClusterValidationService
{
    protected $kmeansService;
    
    public function __construct(KMeansClusteringService $kmeansService = null)
    {
        $this->kmeansService = $kmeansService ?? new KMeansClusteringService();
    }

    /**
     * Tiến hành toàn bộ validation và trả về báo cáo chi tiết
     */
    public function generateFullValidationReport()
    {
        $internalValidation = $this->validateInternalQuality();
        $externalValidation = $this->validateExternalRelevance();
        $stabilityValidation = $this->validateStability();
        $recommendationQuality = $this->validateRecommendationQuality();
        $businessMetrics = $this->calculateBusinessMetrics();
        
        $overallScore = $this->calculateOverallScore(
            $internalValidation,
            $externalValidation,
            $stabilityValidation,
            $recommendationQuality
        );
        
        return [
            'timestamp' => now(),
            'internal_validation' => $internalValidation,
            'external_validation' => $externalValidation,
            'stability_validation' => $stabilityValidation,
            'recommendation_quality' => $recommendationQuality,
            'business_metrics' => $businessMetrics,
            'overall_score' => round($overallScore, 2),
            'interpretation' => $this->interpretOverallScore($overallScore)
        ];
    }

    /**
     * 1. INTERNAL VALIDATION: Đánh giá chất lượng clustering từ góc độ toán học
     */
    public function validateInternalQuality()
    {
        $assignments = StudentCluster::pluck('ClusterID', 'MSSV')->toArray();
        
        if (empty($assignments)) {
            return [
                'status' => 'error',
                'message' => 'Không có dữ liệu clustering'
            ];
        }
        
        $vectors = $this->kmeansService->buildFeatureVectors();
        $normalized = $this->kmeansService->normalizeVectors($vectors);
        $centroids = $this->calculateCurrentCentroids($normalized, $assignments);
        
        $silhouetteScore = $this->calculateSilhouetteScore($normalized, $assignments);
        $daviesBouldinIndex = $this->calculateDaviesBouldinIndex($normalized, $centroids, $assignments);
        $calinskiHarabaszIndex = $this->calculateCalinskiHarabaszIndex($normalized, $centroids, $assignments);
        $clusterBalance = $this->calculateClusterBalance($assignments);
        
        return [
            'silhouette_score' => [
                'value' => round($silhouetteScore, 4),
                'min' => -1,
                'max' => 1,
                'interpretation' => $this->interpretSilhouetteScore($silhouetteScore),
                'weight' => 0.3
            ],
            'davies_bouldin_index' => [
                'value' => round($daviesBouldinIndex, 4),
                'min' => 0,
                'max' => 'inf',
                'interpretation' => $this->interpretDaviesBouldinIndex($daviesBouldinIndex),
                'weight' => 0.3
            ],
            'calinski_harabasz_index' => [
                'value' => round($calinskiHarabaszIndex, 4),
                'min' => 0,
                'max' => 'inf',
                'interpretation' => $this->interpretCalinskiHarabaszIndex($calinskiHarabaszIndex),
                'weight' => 0.3
            ],
            'cluster_balance' => [
                'value' => round($clusterBalance, 4),
                'min' => 0,
                'max' => 1,
                'interpretation' => $this->interpretClusterBalance($clusterBalance),
                'weight' => 0.1
            ],
            'overall_score' => 0
        ];
    }

    /**
     * 2. EXTERNAL VALIDATION: So sánh clustering với sự thật từ dữ liệu
     */
    public function validateExternalRelevance()
    {
        $assignments = StudentCluster::pluck('ClusterID', 'MSSV')->toArray();
        
        if (empty($assignments)) {
            return [
                'status' => 'error',
                'message' => 'Không có dữ liệu clustering'
            ];
        }

        $interestCohesion = $this->calculateInterestCohesion($assignments);
        $activityBehaviorCohesion = $this->calculateActivityBehaviorCohesion($assignments);
        $performanceCohesion = $this->calculatePerformanceCohesion($assignments);

        return [
            'interest_cohesion' => [
                'value' => round($interestCohesion, 4),
                'min' => 0,
                'max' => 1,
                'interpretation' => $this->interpretInterestCohesion($interestCohesion),
                'weight' => 0.35
            ],
            'activity_behavior_cohesion' => [
                'value' => round($activityBehaviorCohesion, 4),
                'min' => 0,
                'max' => 1,
                'interpretation' => $this->interpretActivityBehaviorCohesion($activityBehaviorCohesion),
                'weight' => 0.33
            ],
            'performance_cohesion' => [
                'value' => round($performanceCohesion, 4),
                'min' => 0,
                'max' => 1,
                'interpretation' => $this->interpretPerformanceCohesion($performanceCohesion),
                'weight' => 0.32
            ],
            'overall_score' => 0
        ];
    }

    /**
     * 3. STABILITY VALIDATION: Đánh giá độ ổn định của thuật toán
     */
    public function validateStability($numRuns = 3)
    {
        $stabilityScores = [];
        $vectors = $this->kmeansService->buildFeatureVectors();
        
        for ($i = 0; $i < $numRuns; $i++) {
            $result = $this->kmeansService->cluster($vectors);
            $stabilityScores[] = $result['assignments'];
        }
        
        $pairwiseARI = [];
        for ($i = 0; $i < count($stabilityScores) - 1; $i++) {
            for ($j = $i + 1; $j < count($stabilityScores); $j++) {
                $pairwiseARI[] = $this->calculateAdjustedRandIndex(
                    $stabilityScores[$i],
                    $stabilityScores[$j]
                );
            }
        }
        
        $avgARI = !empty($pairwiseARI) ? array_sum($pairwiseARI) / count($pairwiseARI) : 0;
        $consistencyRate = ($avgARI + 1) / 2;
        
        return [
            'num_runs' => $numRuns,
            'adjusted_rand_index' => [
                'value' => round($avgARI, 4),
                'min' => -1,
                'max' => 1,
                'interpretation' => $this->interpretAdjustedRandIndex($avgARI),
                'weight' => 0.5
            ],
            'consistency_rate' => [
                'value' => round($consistencyRate, 4),
                'min' => 0,
                'max' => 1,
                'interpretation' => $this->interpretConsistencyRate($consistencyRate),
                'weight' => 0.5
            ],
            'overall_score' => 0
        ];
    }

    /**
     * 4. RECOMMENDATION QUALITY: Đánh giá chất lượng đề xuất hoạt động
     */
    public function validateRecommendationQuality()
    {
        $totalStudents = SinhVien::count();
        $studentWithRecs = DB::table('activity_recommendations')
            ->distinct('MSSV')
            ->count('MSSV');
        $coverage = $totalStudents > 0 ? ($studentWithRecs / $totalStudents) : 0;
        
        $relevance = $this->calculateRecommendationRelevance();
        $ctr = $this->calculateClickThroughRate();
        
        return [
            'coverage' => [
                'value' => round($coverage, 4),
                'min' => 0,
                'max' => 1,
                'interpretation' => $this->interpretCoverage($coverage),
                'weight' => 0.25
            ],
            'relevance_score' => [
                'value' => round($relevance, 4),
                'min' => 0,
                'max' => 100,
                'interpretation' => $this->interpretRelevance($relevance),
                'weight' => 0.5
            ],
            'click_through_rate' => [
                'value' => round($ctr, 4),
                'min' => 0,
                'max' => 1,
                'interpretation' => $this->interpretCTR($ctr),
                'weight' => 0.25
            ],
            'overall_score' => 0
        ];
    }

    /**
     * 5. BUSINESS METRICS: Chỉ số kinh doanh
     */
    public function calculateBusinessMetrics()
    {
        return [
            'cluster_size_distribution' => $this->analyzeClusterSizeDistribution(),
            'recommendation_acceptance_rate' => $this->calculateAcceptanceRate(),
            'student_diversity_per_cluster' => $this->calculateStudentDiversityPerCluster(),
            'cold_start_handling' => $this->evaluateColdStartHandling(),
            'recommendation_freshness' => $this->evaluateRecommendationFreshness()
        ];
    }

    // ============================================================
    // CÁC HÀM HỖ TRỢ
    // ============================================================

    protected function calculateSilhouetteScore($vectors, $assignments)
    {
        $scores = [];
        $mssves = array_keys($vectors);
        
        foreach ($mssves as $i => $mssv) {
            $vector = $vectors[$mssv];
            $currentCluster = $assignments[$mssv];
            
            $intraDistances = [];
            foreach ($mssves as $j => $otherMssv) {
                if ($i !== $j && $assignments[$otherMssv] === $currentCluster) {
                    $intraDistances[] = $this->euclideanDistance($vector, $vectors[$otherMssv]);
                }
            }
            $a = !empty($intraDistances) ? array_sum($intraDistances) / count($intraDistances) : 0;
            
            $clusterDistances = [];
            $clusters = array_unique($assignments);
            
            foreach ($clusters as $cluster) {
                if ($cluster === $currentCluster) continue;
                
                $distances = [];
                foreach ($mssves as $j => $otherMssv) {
                    if ($assignments[$otherMssv] === $cluster) {
                        $distances[] = $this->euclideanDistance($vector, $vectors[$otherMssv]);
                    }
                }
                
                if (!empty($distances)) {
                    $clusterDistances[$cluster] = array_sum($distances) / count($distances);
                }
            }
            
            $b = !empty($clusterDistances) ? min($clusterDistances) : 0;
            
            if (max($a, $b) === 0) {
                $scores[] = 0;
            } else {
                $scores[] = ($b - $a) / max($a, $b);
            }
        }
        
        return !empty($scores) ? array_sum($scores) / count($scores) : 0;
    }

    protected function calculateDaviesBouldinIndex($vectors, $centroids, $assignments)
    {
        $clusterMembers = [];
        $clusterScatter = [];
        
        foreach ($vectors as $mssv => $vector) {
            $clusterId = $assignments[$mssv];
            if (!isset($clusterMembers[$clusterId])) {
                $clusterMembers[$clusterId] = [];
            }
            $clusterMembers[$clusterId][] = $vector;
        }
        
        foreach ($clusterMembers as $clusterId => $members) {
            $distances = [];
            foreach ($members as $vector) {
                $distances[] = $this->euclideanDistance($vector, $centroids[$clusterId]);
            }
            $clusterScatter[$clusterId] = !empty($distances) ? array_sum($distances) / count($distances) : 0;
        }
        
        $dbIndex = 0;
        $validClusters = count($clusterMembers);
        
        if ($validClusters < 2) return 0;
        
        foreach ($clusterMembers as $i => $members1) {
            $maxRatio = 0;
            foreach ($clusterMembers as $j => $members2) {
                if ($i === $j) continue;
                
                $distance = $this->euclideanDistance($centroids[$i], $centroids[$j]);
                if ($distance > 0) {
                    $ratio = ($clusterScatter[$i] + $clusterScatter[$j]) / $distance;
                    $maxRatio = max($maxRatio, $ratio);
                }
            }
            $dbIndex += $maxRatio;
        }
        
        return $validClusters > 0 ? $dbIndex / $validClusters : 0;
    }

    protected function calculateCalinskiHarabaszIndex($vectors, $centroids, $assignments)
    {
        $mssves = array_keys($vectors);
        $k = count($centroids);
        $n = count($vectors);
        
        if ($n <= $k) return 0;
        
        $globalCentroid = array_fill(0, count(reset($vectors)), 0);
        foreach ($vectors as $vector) {
            foreach ($vector as $i => $val) {
                $globalCentroid[$i] += $val;
            }
        }
        foreach ($globalCentroid as &$val) {
            $val /= $n;
        }
        
        $bcf = 0;
        $clusterSizes = [];
        foreach ($assignments as $cluster) {
            $clusterSizes[$cluster] = ($clusterSizes[$cluster] ?? 0) + 1;
        }
        
        foreach ($centroids as $i => $centroid) {
            $distance = $this->euclideanDistance($centroid, $globalCentroid);
            $bcf += $clusterSizes[$i] * pow($distance, 2);
        }
        
        $wcf = 0;
        foreach ($vectors as $mssv => $vector) {
            $clusterId = $assignments[$mssv];
            $distance = $this->euclideanDistance($vector, $centroids[$clusterId]);
            $wcf += pow($distance, 2);
        }
        
        if ($wcf === 0) return 0;
        
        return ($bcf / ($k - 1)) / ($wcf / ($n - $k));
    }

    protected function calculateAdjustedRandIndex($assignment1, $assignment2)
    {
        $n = count($assignment1);
        $pairs = 0;
        $pairsInSameClusters1 = 0;
        $pairsInSameClusters2 = 0;
        
        $keys1 = array_keys($assignment1);
        $keys2 = array_keys($assignment2);
        
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $same1 = $assignment1[$keys1[$i]] === $assignment1[$keys1[$j]];
                $same2 = $assignment2[$keys2[$i]] === $assignment2[$keys2[$j]];
                
                if ($same1 && $same2) $pairs++;
                if ($same1) $pairsInSameClusters1++;
                if ($same2) $pairsInSameClusters2++;
            }
        }
        
        $totalPairs = ($n * ($n - 1)) / 2;
        $expectedPairs = ($pairsInSameClusters1 * $pairsInSameClusters2) / $totalPairs;
        $maxPairs = ($pairsInSameClusters1 + $pairsInSameClusters2) / 2;
        
        if ($maxPairs === $expectedPairs) {
            return $pairs === $maxPairs ? 1 : 0;
        }
        
        return ($pairs - $expectedPairs) / ($maxPairs - $expectedPairs);
    }

    protected function calculateInterestCohesion($assignments)
    {
        $clusterCohesion = [];
        
        $clusterMembers = [];
        foreach ($assignments as $mssv => $cluster) {
            if (!isset($clusterMembers[$cluster])) {
                $clusterMembers[$cluster] = [];
            }
            $clusterMembers[$cluster][] = $mssv;
        }
        
        foreach ($clusterMembers as $cluster => $mssves) {
            if (count($mssves) < 2) {
                $clusterCohesion[$cluster] = 1;
                continue;
            }
            
            $similarities = [];
            for ($i = 0; $i < count($mssves) - 1; $i++) {
                for ($j = $i + 1; $j < count($mssves); $j++) {
                    $interests1 = StudentInterest::where('MSSV', $mssves[$i])
                        ->pluck('InterestID')
                        ->toArray();
                    $interests2 = StudentInterest::where('MSSV', $mssves[$j])
                        ->pluck('InterestID')
                        ->toArray();
                    
                    $intersection = count(array_intersect($interests1, $interests2));
                    $union = count(array_unique(array_merge($interests1, $interests2)));
                    
                    $similarity = $union > 0 ? $intersection / $union : 0;
                    $similarities[] = $similarity;
                }
            }
            
            $clusterCohesion[$cluster] = !empty($similarities) ? array_sum($similarities) / count($similarities) : 0;
        }
        
        return !empty($clusterCohesion) ? array_sum($clusterCohesion) / count($clusterCohesion) : 0;
    }

    protected function calculateActivityBehaviorCohesion($assignments)
    {
        $clusterCohesion = [];
        
        $clusterMembers = [];
        foreach ($assignments as $mssv => $cluster) {
            if (!isset($clusterMembers[$cluster])) {
                $clusterMembers[$cluster] = [];
            }
            $clusterMembers[$cluster][] = $mssv;
        }
        
        foreach ($clusterMembers as $cluster => $mssves) {
            if (count($mssves) < 2) {
                $clusterCohesion[$cluster] = 1;
                continue;
            }
            
            $participationRates = [];
            foreach ($mssves as $mssv) {
                $registered = DangKyHoatDongDRL::where('MSSV', $mssv)->count();
                $attended = DangKyHoatDongDRL::where('MSSV', $mssv)
                    ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
                    ->count();
                $participationRates[] = $registered > 0 ? $attended / $registered : 0;
            }
            
            $mean = array_sum($participationRates) / count($participationRates);
            $variance = 0;
            foreach ($participationRates as $rate) {
                $variance += pow($rate - $mean, 2);
            }
            $variance /= count($participationRates);
            
            $clusterCohesion[$cluster] = 1 - min(1, $variance);
        }
        
        return !empty($clusterCohesion) ? array_sum($clusterCohesion) / count($clusterCohesion) : 0;
    }

    protected function calculatePerformanceCohesion($assignments)
    {
        $clusterCohesion = [];
        
        $clusterMembers = [];
        foreach ($assignments as $mssv => $cluster) {
            if (!isset($clusterMembers[$cluster])) {
                $clusterMembers[$cluster] = [];
            }
            $clusterMembers[$cluster][] = $mssv;
        }
        
        foreach ($clusterMembers as $cluster => $mssves) {
            if (count($mssves) < 2) {
                $clusterCohesion[$cluster] = 1;
                continue;
            }
            
            $scores = [];
            foreach ($mssves as $mssv) {
                $avgScore = DiemRenLuyen::where('MSSV', $mssv)->avg('TongDiem');
                $scores[] = $avgScore ?? 0;
            }
            
            $mean = array_sum($scores) / count($scores);
            $variance = 0;
            foreach ($scores as $score) {
                $variance += pow($score - $mean, 2);
            }
            $variance /= count($scores);
            
            $clusterCohesion[$cluster] = 1 - min(1, $variance / 10000);
        }
        
        return !empty($clusterCohesion) ? array_sum($clusterCohesion) / count($clusterCohesion) : 0;
    }

    protected function calculateClusterBalance($assignments)
    {
        $clusterSizes = array_count_values($assignments);
        
        if (empty($clusterSizes)) return 0;
        
        $totalStudents = array_sum($clusterSizes);
        $expectedSize = $totalStudents / count($clusterSizes);
        
        $variance = 0;
        foreach ($clusterSizes as $size) {
            $variance += pow($size - $expectedSize, 2);
        }
        $variance /= count($clusterSizes);
        
        $stdDev = sqrt($variance);
        return 1 - min(1, $stdDev / $expectedSize);
    }

    protected function calculateRecommendationRelevance()
    {
        $recommendations = DB::table('activity_recommendations')->get();
        
        if ($recommendations->isEmpty()) return 0;
        
        $relevanceScores = [];
        
        foreach ($recommendations as $rec) {
            $studentInterests = StudentInterest::where('MSSV', $rec->MSSV)
                ->pluck('InterestID')
                ->toArray();
            
            $activity = null;
            if ($rec->activity_type === 'drl') {
                $activity = \App\Models\HoatDongDRL::find($rec->MaHoatDong);
            } else {
                $activity = \App\Models\HoatDongCTXH::find($rec->MaHoatDong);
            }
            
            if (!$activity || !$activity->category_tags) {
                $relevanceScores[] = 0;
                continue;
            }
            
            $activityInterests = array_map('intval', array_filter(
                array_map('trim', explode(',', $activity->category_tags))
            ));
            
            $intersection = count(array_intersect($activityInterests, $studentInterests));
            $match = !empty($activityInterests) ? $intersection / count($activityInterests) : 0;
            
            $relevanceScores[] = $match * 100;
        }
        
        return !empty($relevanceScores) ? array_sum($relevanceScores) / count($relevanceScores) : 0;
    }

    protected function calculateClickThroughRate()
    {
        $totalRecs = DB::table('activity_recommendations')->count();
        if ($totalRecs === 0) return 0;
        
        $viewedRecs = DB::table('activity_recommendations')
            ->whereNotNull('viewed_at')
            ->count();
        
        return $viewedRecs / $totalRecs;
    }

    protected function analyzeClusterSizeDistribution()
    {
        $assignments = StudentCluster::pluck('ClusterID')->toArray();
        if (empty($assignments)) {
            return ['status' => 'No data'];
        }
        
        $sizes = array_count_values($assignments);
        
        return [
            'cluster_sizes' => $sizes,
            'min_size' => min($sizes) ?? 0,
            'max_size' => max($sizes) ?? 0,
            'avg_size' => array_sum($sizes) / count($sizes) ?? 0,
            'balance_score' => $this->calculateClusterBalance(
                StudentCluster::pluck('ClusterID', 'MSSV')->toArray()
            )
        ];
    }

    protected function calculateAcceptanceRate()
    {
        $totalRecs = DB::table('activity_recommendations')->count();
        if ($totalRecs === 0) return ['acceptance_rate' => 0];
        
        $acceptedRecs = DB::table('activity_recommendations')
            ->whereNotNull('viewed_at')
            ->count();
        
        $rate = $acceptedRecs / $totalRecs;
        return [
            'total_recommendations' => $totalRecs,
            'accepted_count' => $acceptedRecs,
            'acceptance_rate' => round($rate, 4),
            'interpretation' => $this->interpretAcceptanceRate($rate)
        ];
    }

    protected function calculateStudentDiversityPerCluster()
    {
        $clusterStudents = StudentCluster::with('student')
            ->get()
            ->groupBy('ClusterID');
        
        $diversityScores = [];
        
        foreach ($clusterStudents as $clusterId => $students) {
            $faculties = $students->pluck('student.MaKhoa')->unique();
            $totalFaculties = DB::table('khoa')->count();
            
            $diversity = $totalFaculties > 0 ? count($faculties) / $totalFaculties : 0;
            
            $diversityScores[$clusterId] = [
                'faculties' => count($faculties),
                'diversity_score' => round($diversity, 4),
                'student_count' => count($students)
            ];
        }
        
        return $diversityScores;
    }

    protected function evaluateColdStartHandling()
    {
        $newStudents = DB::table('sinhvien as sv')
            ->leftJoin('dangkyhoatdongdrl as dk', 'sv.MSSV', '=', 'dk.MSSV')
            ->select('sv.MSSV', DB::raw('COUNT(dk.MSSV) as activity_count'))
            ->groupBy('sv.MSSV')
            ->having('activity_count', '<', 3)
            ->get();
        
        if ($newStudents->isEmpty()) {
            return ['status' => 'N/A', 'message' => 'No new students'];
        }
        
        $newStudentMssves = $newStudents->pluck('MSSV')->toArray();
        
        $recommendedNewStudents = DB::table('activity_recommendations')
            ->whereIn('MSSV', $newStudentMssves)
            ->distinct('MSSV')
            ->count('MSSV');
        
        $coverageRate = count($newStudentMssves) > 0 
            ? $recommendedNewStudents / count($newStudentMssves) 
            : 0;
        
        return [
            'new_students_count' => count($newStudentMssves),
            'recommended_new_students' => $recommendedNewStudents,
            'coverage_rate' => round($coverageRate, 4),
            'interpretation' => $this->interpretColdStartHandling($coverageRate)
        ];
    }

    protected function evaluateRecommendationFreshness()
    {
        $recommendations = DB::table('activity_recommendations')->get();
        
        if ($recommendations->isEmpty()) {
            return ['status' => 'N/A'];
        }
        
        $freshCount = 0;
        foreach ($recommendations as $rec) {
            $activity = null;
            if ($rec->activity_type === 'drl') {
                $activity = \App\Models\HoatDongDRL::find($rec->MaHoatDong);
            } else {
                $activity = \App\Models\HoatDongCTXH::find($rec->MaHoatDong);
            }
            
            if (!$activity || !$activity->ThoiGianBatDau) continue;
            
            $startTime = \Carbon\Carbon::parse($activity->ThoiGianBatDau);
            $daysUntilActivity = $startTime->diffInDays(now());
            
            if ($daysUntilActivity >= 0 && $daysUntilActivity <= 30) {
                $freshCount++;
            }
        }
        
        $freshnessRate = count($recommendations) > 0 ? $freshCount / count($recommendations) : 0;
        
        return [
            'total_recommendations' => count($recommendations),
            'fresh_recommendations' => $freshCount,
            'freshness_rate' => round($freshnessRate, 4),
            'interpretation' => $this->interpretFreshness($freshnessRate)
        ];
    }

    protected function calculateCurrentCentroids($vectors, $assignments)
    {
        $clusterMembers = [];
        
        foreach ($vectors as $mssv => $vector) {
            $clusterId = $assignments[$mssv];
            if (!isset($clusterMembers[$clusterId])) {
                $clusterMembers[$clusterId] = [];
            }
            $clusterMembers[$clusterId][] = $vector;
        }
        
        $centroids = [];
        foreach ($clusterMembers as $clusterId => $members) {
            $centroid = array_fill(0, count($members[0]), 0);
            foreach ($members as $vector) {
                foreach ($vector as $i => $val) {
                    $centroid[$i] += $val;
                }
            }
            foreach ($centroid as &$val) {
                $val /= count($members);
            }
            $centroids[$clusterId] = $centroid;
        }
        
        return $centroids;
    }

    protected function euclideanDistance($vector1, $vector2)
    {
        $sum = 0;
        foreach ($vector1 as $i => $val1) {
            $val2 = $vector2[$i] ?? 0;
            $sum += pow($val1 - $val2, 2);
        }
        return sqrt($sum);
    }

    protected function calculateOverallScore($internal, $external, $stability, $recommendation)
    {
        $internalScore = $this->calculateWeightedScore($internal);
        $externalScore = $this->calculateWeightedScore($external);
        $stabilityScore = $this->calculateWeightedScore($stability);
        $recommendationScore = $this->calculateWeightedScore($recommendation);
        
        return (0.3 * $internalScore) + (0.3 * $externalScore) + (0.2 * $stabilityScore) + (0.2 * $recommendationScore);
    }

    protected function calculateWeightedScore($metrics)
    {
        if (isset($metrics['status']) && $metrics['status'] === 'error') {
            return 0;
        }
        
        $totalWeight = 0;
        $weightedSum = 0;
        
        foreach ($metrics as $key => $metric) {
            if ($key === 'overall_score' || !is_array($metric) || !isset($metric['weight'])) {
                continue;
            }
            
            $weight = $metric['weight'];
            $totalWeight += $weight;
            
            $normalizedValue = $this->normalizeMetricValue($metric['value'], $key);
            $weightedSum += $normalizedValue * $weight;
        }
        
        return $totalWeight > 0 ? $weightedSum / $totalWeight : 0;
    }

    protected function normalizeMetricValue($value, $metricType)
    {
        if ($metricType === 'davies_bouldin_index') {
            return max(0, 1 - ($value / 2));
        } elseif ($metricType === 'calinski_harabasz_index') {
            return min(1, $value / 200);
        } else {
            if ($value > 1) {
                return min(1, $value / 100);
            }
            return min(1, max(0, $value));
        }
    }

    protected function interpretOverallScore($score)
    {
        if ($score >= 0.8) return 'Xuất sắc - Thuật toán hoạt động rất tốt';
        if ($score >= 0.6) return 'Tốt - Thuật toán có hiệu năng tốt';
        if ($score >= 0.4) return 'Trung bình - Cần cải thiện một số khía cạnh';
        if ($score >= 0.2) return 'Yếu - Cần kiểm tra lại thiết kế';
        return 'Rất yếu - Cần xem xét lại toàn bộ phương pháp';
    }

    // ============================================================
    // CÁC HÀM GIẢI THÍCH
    // ============================================================

    protected function interpretSilhouetteScore($score)
    {
        if ($score >= 0.7) return 'Xuất sắc';
        if ($score >= 0.5) return 'Tốt';
        if ($score >= 0.3) return 'Trung bình';
        if ($score >= 0.0) return 'Yếu';
        return 'Rất yếu';
    }

    protected function interpretDaviesBouldinIndex($score)
    {
        if ($score < 0.5) return 'Xuất sắc';
        if ($score < 1.0) return 'Tốt';
        if ($score < 1.5) return 'Trung bình';
        if ($score < 2.0) return 'Yếu';
        return 'Rất yếu';
    }

    protected function interpretCalinskiHarabaszIndex($score)
    {
        if ($score > 200) return 'Xuất sắc';
        if ($score > 100) return 'Tốt';
        if ($score > 50) return 'Trung bình';
        if ($score > 10) return 'Yếu';
        return 'Rất yếu';
    }

    protected function interpretClusterBalance($score)
    {
        if ($score >= 0.8) return 'Xuất sắc';
        if ($score >= 0.6) return 'Tốt';
        if ($score >= 0.4) return 'Trung bình';
        if ($score >= 0.2) return 'Yếu';
        return 'Rất yếu';
    }

    protected function interpretInterestCohesion($score)
    {
        if ($score >= 0.7) return 'Xuất sắc';
        if ($score >= 0.5) return 'Tốt';
        if ($score >= 0.3) return 'Trung bình';
        return 'Yếu';
    }

    protected function interpretActivityBehaviorCohesion($score)
    {
        if ($score >= 0.7) return 'Xuất sắc';
        if ($score >= 0.5) return 'Tốt';
        if ($score >= 0.3) return 'Trung bình';
        return 'Yếu';
    }

    protected function interpretPerformanceCohesion($score)
    {
        if ($score >= 0.7) return 'Xuất sắc';
        if ($score >= 0.5) return 'Tốt';
        if ($score >= 0.3) return 'Trung bình';
        return 'Yếu';
    }

    protected function interpretAdjustedRandIndex($score)
    {
        if ($score >= 0.8) return 'Xuất sắc';
        if ($score >= 0.6) return 'Tốt';
        if ($score >= 0.4) return 'Trung bình';
        if ($score >= 0.0) return 'Yếu';
        return 'Rất yếu';
    }

    protected function interpretConsistencyRate($score)
    {
        if ($score >= 0.9) return 'Xuất sắc';
        if ($score >= 0.7) return 'Tốt';
        if ($score >= 0.5) return 'Trung bình';
        return 'Yếu';
    }

    protected function interpretCoverage($score)
    {
        if ($score >= 0.9) return 'Xuất sắc';
        if ($score >= 0.7) return 'Tốt';
        if ($score >= 0.5) return 'Trung bình';
        return 'Yếu';
    }

    protected function interpretRelevance($score)
    {
        if ($score >= 80) return 'Xuất sắc';
        if ($score >= 60) return 'Tốt';
        if ($score >= 40) return 'Trung bình';
        return 'Yếu';
    }

    protected function interpretCTR($score)
    {
        if ($score >= 0.5) return 'Xuất sắc';
        if ($score >= 0.3) return 'Tốt';
        if ($score >= 0.1) return 'Trung bình';
        return 'Yếu';
    }

    protected function interpretAcceptanceRate($score)
    {
        if ($score >= 0.5) return 'Xuất sắc';
        if ($score >= 0.3) return 'Tốt';
        if ($score >= 0.1) return 'Trung bình';
        return 'Yếu';
    }

    protected function interpretColdStartHandling($score)
    {
        if ($score >= 0.9) return 'Xuất sắc';
        if ($score >= 0.7) return 'Tốt';
        if ($score >= 0.5) return 'Trung bình';
        return 'Yếu';
    }

    protected function interpretFreshness($score)
    {
        if ($score >= 0.8) return 'Xuất sắc';
        if ($score >= 0.6) return 'Tốt';
        if ($score >= 0.4) return 'Trung bình';
        return 'Yếu';
    }
}
