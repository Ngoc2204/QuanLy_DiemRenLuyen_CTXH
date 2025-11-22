<?php

namespace App\Services;

use App\Models\SinhVien;
use App\Models\StudentInterest;
use App\Models\Interest;
use App\Models\StudentCluster;
use App\Models\ClusterStatistic;
use App\Models\DangKyHoatDongDRL;
use Illuminate\Support\Facades\DB;

class KMeansClusteringService
{
    protected $k = 4; // Số cụm
    protected $maxIterations = 100;
    protected $tolerance = 0.0001;
    
    public function __construct($k = 4)
    {
        $this->k = $k;
    }

    /**
     * Xây dựng Feature Vector cho mỗi sinh viên
     * 
     * Vector được thiết kế gồm 4 nhóm đặc trưng chính:
     * 1. Explicit Interests (10 chiều): Mức độ quan tâm của SV tới 10 loại hình sở thích
     * 2. Behavioral History (2 chiều):
     *    - Participation Rate: Tỷ lệ tham gia thực tế vs đăng ký
     *    - Activity Intensity: Cường độ tham gia tuyệt đối (chuẩn hóa theo ngưỡng 20 activities/năm)
     * 3. Performance (1 chiều): Điểm rèn luyện trung bình (chuẩn hóa về [0,1])
     * 4. Demographics & Context (N+1 chiều):
     *    - Faculty (N chiều): One-Hot Encoding cho mỗi khoa
     *    - Academic Year Level (1 chiều): Năm học hiện tại (1-4) được chuẩn hóa
     */
    public function buildFeatureVectors()
    {
        $students = SinhVien::all();
        $interests = Interest::orderBy('InterestID')->get();
        $facultyCodes = DB::table('khoa')->orderBy('MaKhoa')->pluck('MaKhoa')->toArray();
        $vectors = [];

        foreach ($students as $student) {
            $vector = [];
            
            // ===== NHÓM 1: EXPLICIT INTERESTS (10 chiều) =====
            // Phản ánh nhu cầu nội tại của sinh viên dựa trên khai báo sở thích
            foreach ($interests as $interest) {
                $studentInterest = StudentInterest::where('MSSV', $student->MSSV)
                    ->where('InterestID', $interest->InterestID)
                    ->first();
                // Chuẩn hóa mức độ quan tâm từ thang điểm 1-5 sang [0,1]
                $vector[] = $studentInterest ? ($studentInterest->InterestLevel / 5.0) : 0;
            }
            
            // ===== NHÓM 2: BEHAVIORAL HISTORY (2 chiều) =====
            
            // 2.1 Participation Rate: Tỷ lệ tham gia thực tế
            // Tính từ cả DRL và CTXH để có cái nhìn toàn diện
            $drlRegistered = DangKyHoatDongDRL::where('MSSV', $student->MSSV)->count();
            $drlAttended = DangKyHoatDongDRL::where('MSSV', $student->MSSV)
                ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
                ->count();
            
            $ctxhRegistered = \App\Models\DangKyHoatDongCTXH::where('MSSV', $student->MSSV)->count();
            $ctxhAttended = \App\Models\DangKyHoatDongCTXH::where('MSSV', $student->MSSV)
                ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
                ->count();
            
            $totalRegistered = $drlRegistered + $ctxhRegistered;
            $totalAttended = $drlAttended + $ctxhAttended;
            $participationRate = $totalRegistered > 0 ? ($totalAttended / $totalRegistered) : 0;
            $vector[] = $participationRate;
            
            // 2.2 Activity Intensity: Cường độ tham gia tuyệt đối
            // Chuẩn hóa theo ngưỡng benchmark = 20 hoạt động/năm
            $activityIntensity = min($totalAttended / 20.0, 1.0);
            $vector[] = $activityIntensity;
            
            // ===== NHÓM 3: PERFORMANCE (1 chiều) =====
            // Điểm rèn luyện trung bình, chuẩn hóa [0,1]
            $avgScore = \App\Models\DiemRenLuyen::where('MSSV', $student->MSSV)
                ->avg('TongDiem');
            $normalizedScore = $avgScore ? ($avgScore / 100.0) : 0.0;
            $vector[] = $normalizedScore;
            
            // ===== NHÓM 4: DEMOGRAPHICS & CONTEXT (N+1 chiều) =====
            
            // 4.1 Faculty (One-Hot Encoding): N chiều tương ứng với N khoa
            // Đảm bảo không sai lệch khoảng cách do gán số thứ tự
            foreach ($facultyCodes as $faculty) {
                $vector[] = ($student->MaKhoa === $faculty) ? 1.0 : 0.0;
            }
            
            // 4.2 Academic Year Level: Chuẩn hóa dựa trên năm học hiện tại
            // Lấy khóa từ mã lớp (ví dụ: "13DH..." => Khóa 13)
            $classCode = $student->MaLop; // Ví dụ: "13DH001"
            $cohort = intval(substr($classCode, 0, 2)); // Lấy 2 ký tự đầu
            
            // Tính năm thứ: năm hệ thống - năm nhập học + 1
            // Giả sử K1 bắt đầu năm 2010
            $yearOfEntry = 2010 + ($cohort - 1);
            $currentYear = date('Y');
            $academicYear = min($currentYear - $yearOfEntry + 1, 4); // Capped at 4
            
            // Chuẩn hóa: Năm 1 -> 0.25, Năm 2 -> 0.50, Năm 3 -> 0.75, Năm 4+ -> 1.00
            $yearNormalized = $this->encodeYear($academicYear);
            $vector[] = $yearNormalized;
            
            $vectors[$student->MSSV] = $vector;
        }
        
        return $vectors;
    }

    /**
     * Chuẩn hóa vectors
     */
    public function normalizeVectors($vectors)
    {
        if (empty($vectors)) return [];
        
        $dimensions = count(reset($vectors));
        $normalized = [];
        
        // Tính min/max cho mỗi dimension
        $mins = array_fill(0, $dimensions, PHP_FLOAT_MAX);
        $maxs = array_fill(0, $dimensions, PHP_FLOAT_MIN);
        
        foreach ($vectors as $vector) {
            foreach ($vector as $i => $value) {
                $mins[$i] = min($mins[$i], $value);
                $maxs[$i] = max($maxs[$i], $value);
            }
        }
        
        // Normalize bằng Min-Max Scaling
        foreach ($vectors as $mssv => $vector) {
            $normalizedVector = [];
            foreach ($vector as $i => $value) {
                $range = $maxs[$i] - $mins[$i];
                $normalizedVector[$i] = $range > 0 ? ($value - $mins[$i]) / $range : 0;
            }
            $normalized[$mssv] = $normalizedVector;
        }
        
        return $normalized;
    }

    /**
     * Chạy K-Means clustering
     * 
     * LƯU Ý: Không normalize vectors lần nữa vì dữ liệu đã được chuẩn hóa [0,1] trong buildFeatureVectors().
     * Các features đã được thiết kế với scale chuẩn:
     * - Explicit Interests: [0, 1] (chia cho 5.0)
     * - Participation Rate: [0, 1] (là tỷ lệ)
     * - Activity Intensity: [0, 1] (min với 1.0)
     * - Performance: [0, 1] (chia cho 100)
     * - Faculty (One-Hot): [0, 1]
     * - Academic Year: [0, 1] (0.25/0.50/0.75/1.00)
     * 
     * Nếu normalize lại bằng Min-Max Scaling sẽ bị "Normalization Trap":
     * - Trọng số giữa các features bị triệt tiêu
     * - Dữ liệu bị thay đổi bất thường
     */
    public function cluster($vectors)
    {
        // $vectors = $this->normalizeVectors($vectors);  // ❌ REMOVED - Gây lỗi Normalization Trap
        
        if (empty($vectors)) {
            return ['assignments' => [], 'centroids' => [], 'iterations' => 0];
        }
        
        // Khởi tạo centroids ngẫu nhiên
        $mssves = array_keys($vectors);
        $initialIndices = array_rand($mssves, min($this->k, count($mssves)));
        
        $centroids = [];
        foreach ((array)$initialIndices as $idx) {
            $centroids[] = $vectors[$mssves[$idx]];
        }
        
        $assignments = [];
        $iteration = 0;
        
        while ($iteration < $this->maxIterations) {
            // Gán sinh viên vào cụm gần nhất
            $newAssignments = [];
            foreach ($vectors as $mssv => $vector) {
                $minDistance = PHP_FLOAT_MAX;
                $closestCluster = 0;
                
                foreach ($centroids as $clusterIdx => $centroid) {
                    $distance = $this->euclideanDistance($vector, $centroid);
                    if ($distance < $minDistance) {
                        $minDistance = $distance;
                        $closestCluster = $clusterIdx;
                    }
                }
                
                $newAssignments[$mssv] = $closestCluster;
            }
            
            // Kiểm tra hội tụ
            if ($assignments === $newAssignments) {
                break;
            }
            
            $assignments = $newAssignments;
            
            // Cập nhật centroids
            $newCentroids = [];
            for ($c = 0; $c < $this->k; $c++) {
                $clusterVectors = [];
                foreach ($assignments as $mssv => $cluster) {
                    if ($cluster === $c) {
                        $clusterVectors[] = $vectors[$mssv];
                    }
                }
                
                if (!empty($clusterVectors)) {
                    $newCentroids[$c] = $this->calculateCentroid($clusterVectors);
                } else {
                    // Nếu cụm trống, chọn ngẫu nhiên từ vectors
                    $randomMssv = $mssves[array_rand($mssves)];
                    $newCentroids[$c] = $vectors[$randomMssv];
                }
            }
            
            $centroids = $newCentroids;
            $iteration++;
        }
        
        return [
            'assignments' => $assignments,
            'centroids' => $centroids,
            'iterations' => $iteration
        ];
    }

    /**
     * Tính khoảng cách Euclidean
     */
    protected function euclideanDistance($vector1, $vector2)
    {
        $sum = 0;
        foreach ($vector1 as $i => $val1) {
            $val2 = $vector2[$i] ?? 0;
            $sum += pow($val1 - $val2, 2);
        }
        return sqrt($sum);
    }

    /**
     * Tính centroid (trung tâm cụm)
     */
    protected function calculateCentroid($vectors)
    {
        $dimensions = count($vectors[0]);
        $centroid = array_fill(0, $dimensions, 0);
        
        foreach ($vectors as $vector) {
            foreach ($vector as $i => $value) {
                $centroid[$i] += $value;
            }
        }
        
        foreach ($centroid as &$value) {
            $value /= count($vectors);
        }
        
        return $centroid;
    }

    /**
     * Lưu kết quả clustering vào database
     */
    public function saveClusterAssignments($assignments)
    {
        DB::table('student_clusters')->truncate();
        
        // Lưu assignments trước
        foreach ($assignments as $mssv => $clusterId) {
            StudentCluster::create([
                'MSSV' => $mssv,
                'ClusterID' => $clusterId,
                'ClusterName' => "Cluster $clusterId"
            ]);
        }
        
        // Tính toán chỉ số trung bình cho mỗi cụm
        $clusterStats = $this->getClusterStatisticsForLabeling();
        
        // Gán nhãn động dựa vào chỉ số
        $clusterNames = $this->assignClusterLabels($clusterStats);
        
        // Update cluster names
        foreach ($clusterNames as $clusterId => $clusterName) {
            StudentCluster::where('ClusterID', $clusterId)
                ->update(['ClusterName' => $clusterName]);
        }
    }

    /**
     * Tính chỉ số thống kê cho mỗi cụm (dùng để gán nhãn)
     */
    private function getClusterStatisticsForLabeling()
    {
        $clusterStats = [];
        
        // Lấy assignments hiện tại từ DB
        $assignments = StudentCluster::pluck('ClusterID', 'MSSV')->toArray();
        
        for ($clusterId = 0; $clusterId < $this->k; $clusterId++) {
            $students = collect($assignments)
                ->filter(fn($c) => $c == $clusterId)
                ->keys()
                ->toArray();
            
            if (empty($students)) {
                $clusterStats[$clusterId] = [
                    'count' => 0,
                    'avg_score' => 0,
                    'participation_rate' => 0,
                    'activities_per_student' => 0
                ];
                continue;
            }
            
            $scores = [];
            $participationRates = [];
            $activitiesCount = [];
            
            foreach ($students as $mssv) {
                // Điểm DRL trung bình
                $score = \App\Models\DiemRenLuyen::where('MSSV', $mssv)->avg('TongDiem');
                $scores[] = $score ?? 0;
                
                // Tỷ lệ tham gia
                $registered = DangKyHoatDongDRL::where('MSSV', $mssv)->count();
                $attended = DangKyHoatDongDRL::where('MSSV', $mssv)
                    ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
                    ->count();
                $participationRates[] = $registered > 0 ? ($attended / $registered) * 100 : 0;
                
                // Số hoạt động trung bình
                $activitiesCount[] = $registered;
            }
            
            $clusterStats[$clusterId] = [
                'count' => count($students),
                'avg_score' => !empty($scores) ? array_sum($scores) / count($scores) : 0,
                'participation_rate' => !empty($participationRates) ? array_sum($participationRates) / count($participationRates) : 0,
                'activities_per_student' => !empty($activitiesCount) ? array_sum($activitiesCount) / count($activitiesCount) : 0
            ];
        }
        
        return $clusterStats;
    }

    /**
     * Gán nhãn cụm dựa vào chỉ số thống kê (Dynamic Labeling)
     */
    private function assignClusterLabels($clusterStats)
    {
        $clusterNames = [];
        
        // Sắp xếp clusters theo các chỉ số
        $byScore = collect($clusterStats)->sortByDesc('avg_score')->keys()->toArray();
        $byParticipation = collect($clusterStats)->sortByDesc('participation_rate')->keys()->toArray();
        $byActivity = collect($clusterStats)->sortByDesc('activities_per_student')->keys()->toArray();
        
        // Gán nhãn dựa vào thứ hạng
        for ($clusterId = 0; $clusterId < $this->k; $clusterId++) {
            $scoreRank = array_search($clusterId, $byScore) + 1;
            $participationRank = array_search($clusterId, $byParticipation) + 1;
            $activityRank = array_search($clusterId, $byActivity) + 1;
            
            $avgRank = ($scoreRank + $participationRank + $activityRank) / 3;
            
            // Gán nhãn dựa vào rank trung bình
            if ($avgRank <= 1.5) {
                $clusterNames[$clusterId] = 'Sinh viên tích cực, đa năng';
            } elseif ($avgRank <= 2.5) {
                $clusterNames[$clusterId] = 'Sinh viên hoạt động vừa phải';
            } elseif ($avgRank <= 3.5) {
                $clusterNames[$clusterId] = 'Sinh viên có định hướng chuyên sâu';
            } else {
                $clusterNames[$clusterId] = 'Sinh viên ít hoạt động';
            }
        }
        
        return $clusterNames;
    }

    /**
     * Tính toán cluster statistics
     */
    public function calculateClusterStatistics()
    {
        DB::table('cluster_statistics')->truncate();
        
        for ($clusterId = 0; $clusterId < $this->k; $clusterId++) {
            $students = StudentCluster::where('ClusterID', $clusterId)->pluck('MSSV')->toArray();
            
            if (empty($students)) continue;
            
            // Tính trung bình participation rate
            $participationRates = [];
            $scores = [];
            $interests = [];
            
            foreach ($students as $mssv) {
                $registered = DangKyHoatDongDRL::where('MSSV', $mssv)->count();
                $attended = DangKyHoatDongDRL::where('MSSV', $mssv)
                    ->where('TrangThaiThamGia', 'Có mặt')
                    ->count();
                $participationRates[] = $registered > 0 ? ($attended / $registered) * 100 : 0;
                
                $score = \App\Models\DiemRenLuyen::where('MSSV', $mssv)
                    ->avg('TongDiem');
                $scores[] = $score ?? 0;
                
                $studentInterests = StudentInterest::where('MSSV', $mssv)
                    ->pluck('InterestID')
                    ->toArray();
                $interests = array_merge($interests, $studentInterests);
            }
            
            $avgParticipation = !empty($participationRates) ? array_sum($participationRates) / count($participationRates) : 0;
            $avgScore = !empty($scores) ? array_sum($scores) / count($scores) : 0;
            $topInterests = array_count_values($interests);
            arsort($topInterests);
            $topInterests = array_slice(array_keys($topInterests), 0, 5);
            
            ClusterStatistic::create([
                'ClusterID' => $clusterId,
                'TotalStudents' => count($students),
                'AvgParticipationRate' => round($avgParticipation, 2),
                'AvgScore' => round($avgScore, 2),
                'TopInterests' => json_encode($topInterests)
            ]);
        }
    }

    /**
     * Encode năm học thành giá trị chuẩn hóa [0,1]
     * 
     * Ánh xạ:
     * - Năm 1 (Tân sinh viên): 0.25 (Giai đoạn hòa nhập)
     * - Năm 2: 0.50 (Giai đoạn phát triển kỹ năng)
     * - Năm 3: 0.75 (Giai đoạn chuyên sâu)
     * - Năm 4+: 1.00 (Giai đoạn thực tập/tốt nghiệp)
     */
    protected function encodeYear($yearLevel)
    {
        return match($yearLevel) {
            1 => 0.25,
            2 => 0.50,
            3 => 0.75,
            4 => 1.00,
            default => 0.50 // Default cho các trường hợp đặc biệt
        };
    }
    public function calculateInertia($vectors, $centroids, $assignments)
    {
        $inertia = 0;
        
        foreach ($vectors as $mssv => $vector) {
            if (!isset($assignments[$mssv])) continue;
            
            $clusterId = $assignments[$mssv];
            $centroid = $centroids[$clusterId];
            $distance = $this->euclideanDistance($vector, $centroid);
            $inertia += pow($distance, 2);
        }
        
        return $inertia;
    }

    /**
     * Tính Silhouette Score (-1 to 1, cao hơn = tốt hơn)
     */
    public function calculateSilhouetteScore($vectors, $assignments)
    {
        $scores = [];
        $vectorArray = array_values($vectors);
        $assignmentArray = array_values($assignments);
        
        foreach ($vectorArray as $idx => $vector) {
            $currentCluster = $assignmentArray[$idx];
            
            // a: avg distance to points in same cluster
            $intraClusterDistances = [];
            foreach ($vectorArray as $j => $otherVector) {
                if ($assignmentArray[$j] == $currentCluster && $idx != $j) {
                    $intraClusterDistances[] = $this->euclideanDistance($vector, $otherVector);
                }
            }
            $a = !empty($intraClusterDistances) ? array_sum($intraClusterDistances) / count($intraClusterDistances) : 0;
            
            // b: avg distance to nearest cluster
            $clusterDistances = [];
            for ($c = 0; $c < $this->k; $c++) {
                if ($c == $currentCluster) continue;
                
                $distances = [];
                foreach ($vectorArray as $j => $otherVector) {
                    if ($assignmentArray[$j] == $c) {
                        $distances[] = $this->euclideanDistance($vector, $otherVector);
                    }
                }
                
                if (!empty($distances)) {
                    $clusterDistances[$c] = array_sum($distances) / count($distances);
                }
            }
            
            $b = !empty($clusterDistances) ? min($clusterDistances) : 0;
            
            // Silhouette score for this point
            if (max($a, $b) == 0) {
                $scores[] = 0;
            } else {
                $scores[] = ($b - $a) / max($a, $b);
            }
        }
        
        return !empty($scores) ? array_sum($scores) / count($scores) : 0;
    }

    /**
     * Tính Davies-Bouldin Index (càng thấp càng tốt)
     */
    public function calculateDaviesBouldinIndex($vectors, $centroids, $assignments)
    {
        $clusterMembers = [];
        $clusterScatter = [];
        
        // Group vectors by cluster
        foreach ($vectors as $mssv => $vector) {
            if (!isset($assignments[$mssv])) continue;
            
            $clusterId = $assignments[$mssv];
            if (!isset($clusterMembers[$clusterId])) {
                $clusterMembers[$clusterId] = [];
            }
            $clusterMembers[$clusterId][] = $vector;
        }
        
        // Calculate scatter for each cluster (avg distance to centroid)
        foreach ($clusterMembers as $clusterId => $members) {
            $distances = [];
            foreach ($members as $vector) {
                $distances[] = $this->euclideanDistance($vector, $centroids[$clusterId]);
            }
            $clusterScatter[$clusterId] = !empty($distances) ? array_sum($distances) / count($distances) : 0;
        }
        
        // Calculate Davies-Bouldin Index
        $dbIndex = 0;
        $validClusters = count($clusterMembers);
        
        if ($validClusters < 2) return 0;
        
        foreach ($clusterMembers as $i => $members1) {
            $maxRatio = 0;
            foreach ($clusterMembers as $j => $members2) {
                if ($i == $j) continue;
                
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

    /**
     * Lấy cluster quality metrics
     */
    public function getClusteringMetrics($vectors, $centroids, $assignments)
    {
        return [
            'inertia' => round($this->calculateInertia($vectors, $centroids, $assignments), 4),
            'silhouette_score' => round($this->calculateSilhouetteScore($vectors, $assignments), 4),
            'davies_bouldin_index' => round($this->calculateDaviesBouldinIndex($vectors, $centroids, $assignments), 4),
            'num_clusters' => $this->k,
            'num_samples' => count($assignments),
        ];
    }

    /**
     * Tạo recommendations cho sinh viên dựa trên cluster
     */
    public function generateRecommendations()
    {
        DB::table('activity_recommendations')->truncate();
        
        // Lấy tất cả sinh viên đã được phân cụm
        $clusterAssignments = \App\Models\StudentCluster::all();
        
        foreach ($clusterAssignments as $assignment) {
            $mssv = $assignment->MSSV;
            $clusterId = $assignment->ClusterID;
            
            // Lấy các thành viên khác trong cùng cluster
            $clusterMembers = \App\Models\StudentCluster::where('ClusterID', $clusterId)
                ->where('MSSV', '!=', $mssv)
                ->pluck('MSSV')
                ->toArray();
            
            if (!empty($clusterMembers)) {
                // Gợi ý DRL dựa trên hoạt động phổ biến trong cluster
                $this->recommendPopularActivitiesDRL($mssv, $clusterMembers, $clusterId);
                
                // Gợi ý CTXH dựa trên hoạt động phổ biến trong cluster
                $this->recommendPopularActivitiesCTXH($mssv, $clusterMembers, $clusterId);
            }
        }
    }

    /**
     * Gợi ý hoạt động DRL dựa trên popularity trong cluster
     */
    private function recommendPopularActivitiesDRL($mssv, $clusterMembers, $clusterId)
    {
        // Lấy sở thích của sinh viên
        $studentInterests = StudentInterest::where('MSSV', $mssv)
            ->pluck('InterestID')
            ->toArray();
        
        // Lấy các hoạt động đã được tham gia bởi những người trong cùng cluster
        $populateActivities = DB::table('dangkyhoatdongdrl as dk')
            ->join('hoatdongdrl as hd', 'dk.MaHoatDong', '=', 'hd.MaHoatDong')
            ->select('dk.MaHoatDong', 'hd.category_tags', DB::raw('COUNT(*) as popularity'))
            ->whereIn('dk.MSSV', $clusterMembers)
            ->groupBy('dk.MaHoatDong', 'hd.category_tags')
            ->orderByDesc('popularity')
            ->limit(30)
            ->get();
        
        // Lấy hoạt động mà sinh viên chưa đăng ký
        $studentRegistered = DangKyHoatDongDRL::where('MSSV', $mssv)
            ->pluck('MaHoatDong')
            ->toArray();
        
        // Tạo gợi ý cho các hoạt động phù hợp
        $recommendCount = 0;
        foreach ($populateActivities as $activity) {
            if ($recommendCount >= 5) break;
            if (in_array($activity->MaHoatDong, $studentRegistered)) continue;
            
            // Tính interest match score
            $interestMatchScore = $this->calculateActivityInterestMatch(
                $activity->category_tags,
                $studentInterests
            );
            
            // Nếu không match sở thích, bỏ qua
            if ($interestMatchScore < 40) continue;
            
            // Lấy thông tin activity
            $activityInfo = \App\Models\HoatDongDRL::find($activity->MaHoatDong);
            if (!$activityInfo) continue;
            
            // Tính popularity score (normalized)
            $maxPopularity = $populateActivities->max('popularity');
            $popularityScore = ($activity->popularity / $maxPopularity) * 100;
            
            // Tính match score dựa trên:
            // - Popularity trong cluster (40%)
            // - Tính mới của hoạt động (10%)
            // - Interest match (50%)
            $recencyBonus = $this->getRecencyBonus($activityInfo->ThoiGianBatDau);
            $matchScore = (0.4 * $popularityScore) + (0.1 * $recencyBonus) + (0.5 * $interestMatchScore);
            
            \App\Models\ActivityRecommendation::create([
                'MSSV' => $mssv,
                'MaHoatDong' => $activity->MaHoatDong,
                'activity_type' => 'drl',
                'recommendation_score' => round(min(100, max(50, $matchScore)), 2),
                'recommendation_reason' => sprintf(
                    'Được %d thành viên khác trong cluster tham gia. Phù hợp với sở thích của bạn',
                    intval($activity->popularity)
                ),
                'viewed_at' => null
            ]);
            
            $recommendCount++;
        }
    }

    /**
     * Gợi ý hoạt động CTXH dựa trên popularity trong cluster
     */
    private function recommendPopularActivitiesCTXH($mssv, $clusterMembers, $clusterId)
    {
        // Lấy sở thích của sinh viên
        $studentInterests = StudentInterest::where('MSSV', $mssv)
            ->pluck('InterestID')
            ->toArray();
        
        // Lấy các hoạt động đã được tham gia bởi những người trong cùng cluster
        $populateActivities = DB::table('dangkyhoatdongctxh as dk')
            ->join('hoatdongctxh as hc', 'dk.MaHoatDong', '=', 'hc.MaHoatDong')
            ->select('dk.MaHoatDong', 'hc.category_tags', DB::raw('COUNT(*) as popularity'))
            ->whereIn('dk.MSSV', $clusterMembers)
            ->groupBy('dk.MaHoatDong', 'hc.category_tags')
            ->orderByDesc('popularity')
            ->limit(30)
            ->get();
        
        // Lấy hoạt động mà sinh viên chưa đăng ký
        $studentRegistered = DB::table('dangkyhoatdongctxh')
            ->where('MSSV', $mssv)
            ->pluck('MaHoatDong')
            ->toArray();
        
        // Tạo gợi ý cho các hoạt động phù hợp
        $recommendCount = 0;
        foreach ($populateActivities as $activity) {
            if ($recommendCount >= 5) break;
            if (in_array($activity->MaHoatDong, $studentRegistered)) continue;
            
            // Tính interest match score
            $interestMatchScore = $this->calculateActivityInterestMatch(
                $activity->category_tags,
                $studentInterests
            );
            
            // Nếu không match sở thích, bỏ qua
            if ($interestMatchScore < 40) continue;
            
            // Lấy thông tin activity
            $activityInfo = \App\Models\HoatDongCTXH::find($activity->MaHoatDong);
            if (!$activityInfo) continue;
            
            // Tính popularity score (normalized)
            $maxPopularity = $populateActivities->max('popularity');
            $popularityScore = ($activity->popularity / $maxPopularity) * 100;
            
            // Tính match score dựa trên:
            // - Popularity trong cluster (40%)
            // - Tính mới của hoạt động (10%)
            // - Interest match (50%)
            $recencyBonus = $this->getRecencyBonus($activityInfo->ThoiGianBatDau);
            $matchScore = (0.4 * $popularityScore) + (0.1 * $recencyBonus) + (0.5 * $interestMatchScore);
            
            \App\Models\ActivityRecommendation::create([
                'MSSV' => $mssv,
                'MaHoatDong' => $activity->MaHoatDong,
                'activity_type' => 'ctxh',
                'recommendation_score' => round(min(100, max(50, $matchScore)), 2),
                'recommendation_reason' => sprintf(
                    'Được %d thành viên khác trong cluster tham gia. Phù hợp với sở thích của bạn',
                    intval($activity->popularity)
                ),
                'viewed_at' => null
            ]);
            
            $recommendCount++;
        }
    }

    /**
     * Tính bonus cho các hoạt động mới (recency)
     */
    private function getRecencyBonus($activityStartTime)
    {
        if (!$activityStartTime) return 0;
        
        $now = \Carbon\Carbon::now();
        $start = \Carbon\Carbon::parse($activityStartTime);
        $daysUntilActivity = $start->diffInDays($now, false); // âm nếu trong quá khứ
        
        // Các hoạt động trong 30 ngày tới được ưu tiên
        if ($daysUntilActivity > 0 && $daysUntilActivity <= 30) {
            return ((30 - $daysUntilActivity) / 30) * 100;
        }
        
        return 0;
    }

    /**
     * Tính match score dựa trên category tags của activity và interests của sinh viên
     */
    private function calculateActivityInterestMatch($categoryTags, $studentInterests)
    {
        if (!$categoryTags || empty($studentInterests)) {
            return 50; // Default score
        }
        
        // Parse category tags (comma-separated interest IDs)
        $activityInterests = array_map('intval', array_filter(array_map('trim', explode(',', $categoryTags))));
        
        if (empty($activityInterests)) {
            return 50;
        }
        
        // Tính số sở thích trùng lặp
        $matches = array_intersect($activityInterests, $studentInterests);
        
        if (empty($matches)) {
            return 0; // Không có sở thích nào trùng
        }
        
        // Tính match score dựa trên tỷ lệ trùng lặp
        $matchRatio = count($matches) / count($activityInterests);
        return $matchRatio * 100;
    }
}
