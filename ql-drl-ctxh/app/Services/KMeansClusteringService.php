<?php

namespace App\Services;

use App\Models\SinhVien;
use App\Models\StudentInterest;
use App\Models\Interest;
use App\Models\StudentCluster;
use App\Models\ClusterStatistic;
use App\Models\DangKyHoatDongDRL;
use App\Models\DangKyHoatDongCTXH;
use App\Models\HoatDongDRL;
use App\Models\HoatDongCTXH;
use Illuminate\Support\Facades\DB;

/**
 * Hệ thống Gợi ý Hoạt động DRL/CTXH sử dụng Thuật toán K-Means
 * 
 * Mô tả kỹ thuật:
 * - Phân nhóm sinh viên dựa trên tương đồng về đặc điểm nhân khẩu học và hành vi
 * - Áp dụng User-based Collaborative Filtering để gợi ý hoạt động
 * - Xử lý Cold Start problem thông qua chiến lược lai ghép (Hybrid Strategy)
 */
class KMeansClusteringService
{
    protected $k = 4; // Số cụm
    protected $maxIterations = 100;
    protected $tolerance = 0.0001;
    
    // Trọng số động - Giai đoạn Cold Start vs Refinement
    protected $weights_cold_start = [
        'faculty'    => 0.3,   // W1: Khoa
        'year'       => 0.3,   // W2: Năm học
        'interests'  => 0.4,   // W3: Sở thích
        'history'    => 0.0    // W4: Lịch sử (disabled ở Cold Start)
    ];
    
    protected $weights_refinement = [
        'faculty'    => 0.2,   // W1
        'year'       => 0.2,   // W2
        'interests'  => 0.3,   // W3
        'history'    => 0.3    // W4
    ];
    
    // Ngưỡng xác định chuyển từ Cold Start sang Refinement
    protected $activity_threshold = 5; // Sinh viên phải tham gia ≥5 hoạt động
    
    public function __construct($k = 4)
    {
        $this->k = $k;
    }

    /**
     * BƯỚC 1: XÂY DỰNG KHÔNG GIAN VECTOR ĐẶC TRƯNG
     * 
     * Vector sinh viên u: V_u = [W1·F_Khoa, W2·F_Nam, W3·F_SoThich, W4·F_LichSu]
     * 
     * Gồm 4 thành phần chính:
     * 1. F_Khoa (Vector Khoa): One-Hot Encoding - phân loại sinh viên theo đơn vị quản lý
     * 2. F_Nam (Vector Năm học): Min-Max Normalization - mức độ ưu tiên hoạt động theo khóa học
     * 3. F_SoThich (Vector Sở thích): Multi-Hot Encoding - nhu cầu nội tại của sinh viên
     * 4. F_LichSu (Vector Lịch sử): Frequency Distribution - hành vi thực tế đã tham gia
     * 
     * 📌 **PHẦN 2: Vector Năm học Chi Tiết**
     * 
     * Hệ thống ưu tiên sử dụng trường `NamNhapHoc` lưu trữ trực tiếp trong CSDL:
     * - Chính xác: Không phải tính toán gián tiếp
     * - Bắt buộc: Tất cả sinh viên phải có giá trị này
     * - Fallback: Nếu null, trích xuất từ Mã lớp (2 ký tự đầu)
     * 
     * Quy trình:
     * 1. Lấy giá trị NamNhapHoc từ database (ví dụ: 2021, 2022, 2023, 2024)
     * 2. Tính năm học hiện tại: $academicYear = (năm hiện tại) - (năm nhập học) + 1
     *    - Ví dụ: Sinh viên nhập năm 2022, hiện tại 2025 → Năm 4
     * 3. Chuẩn hóa Min-Max:
     *    - Năm 1 → 0.25 (Giai đoạn hòa nhập, khám phá)
     *    - Năm 2 → 0.50 (Giai đoạn phát triển kỹ năng)
     *    - Năm 3 → 0.75 (Giai đoạn chuyên sâu, chuyên môn)
     *    - Năm 4+ → 1.00 (Giai đoạn thực tập, tốt nghiệp)
     * 4. Ép giá trị về khoảng [0, 1] để đồng bộ với các thành phần khác
     */
    public function buildFeatureVectors()
    {
        $students = SinhVien::all();
        $facultyCodes = DB::table('khoa')->orderBy('MaKhoa')->pluck('MaKhoa')->toArray();
        $vectors = [];

        foreach ($students as $student) {
            $vector = [];
            
            // ===== PHẦN 1: VECTOR KHOA (One-Hot Encoding) =====
            // Ví dụ: [1, 0, 0, 0] nếu sinh viên thuộc khoa đầu tiên
            foreach ($facultyCodes as $faculty) {
                $vector[] = ($student->MaKhoa === $faculty) ? 1.0 : 0.0;
            }
            
            // ===== PHẦN 2: VECTOR NĂM HỌC (Min-Max Normalization) =====
            // Sử dụng trường NamNhapHoc từ database
            // Fallback: Trích xuất từ 2 ký tự đầu Mã lớp nếu NamNhapHoc null
            $yearOfEntry = $student->NamNhapHoc;
            
            if (!$yearOfEntry) {
                // Fallback: Trích xuất khóa từ 2 ký tự đầu Mã lớp (VD: "13DHTH06" -> 13)
                $classCode = $student->MaLop;
                $cohort = intval(substr($classCode, 0, 2));
                $yearOfEntry = 2010 + ($cohort - 1); // Giả sử K1 = năm 2010
            }
            
            $currentYear = date('Y');
            $academicYear = min($currentYear - $yearOfEntry + 1, 4); // Capped at 4 years
            
            // Chuẩn hóa: Năm 1->0.25, Năm 2->0.50, Năm 3->0.75, Năm 4+->1.0
            // Ánh xạ theo giai đoạn học tập
            $yearNormalized = $this->encodeYear($academicYear);
            $vector[] = min(1.0, max(0.0, $yearNormalized));
            
            // ===== PHẦN 3: VECTOR SỞ THÍCH (Multi-Hot Encoding) =====
            // Lấy 10 danh mục sở thích từ bảng interests
            $interests = \App\Models\Interest::orderBy('InterestID')->limit(10)->get();
            foreach ($interests as $interest) {
                $studentInterest = StudentInterest::where('MSSV', $student->MSSV)
                    ->where('InterestID', $interest->InterestID)
                    ->first();
                // Chuẩn hóa mức độ quan tâm: 1-5 -> [0,1]
                $vector[] = $studentInterest ? ($studentInterest->InterestLevel / 5.0) : 0;
            }
            
            // ===== PHẦN 4: VECTOR LỊCH SỬ (Frequency Distribution) =====
            // Tỷ lệ phân bố hoạt động sinh viên đã tham gia theo danh mục
            $historyVector = $this->calculateHistoryVector($student->MSSV, $interests);
            $vector = array_merge($vector, $historyVector);
            
            $vectors[$student->MSSV] = $vector;
        }
        
        return $vectors;
    }

    /**
     * Tính Vector Lịch sử (Frequency Distribution)
     * h_i = Số hoạt động thuộc danh mục i đã tham gia / Tổng số hoạt động đã tham gia
     */
    private function calculateHistoryVector($mssv, $interests)
    {
        // Lấy hoạt động DRL đã tham gia
        $drlParticipated = DangKyHoatDongDRL::where('MSSV', $mssv)
            ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
            ->pluck('MaHoatDong')
            ->toArray();
        
        // Lấy hoạt động CTXH đã tham gia
        $ctxhParticipated = DB::table('dangkyhoatdongctxh')
            ->where('MSSV', $mssv)
            ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
            ->pluck('MaHoatDong')
            ->toArray();
        
        $allActivities = array_merge($drlParticipated, $ctxhParticipated);
        $totalActivities = count($allActivities);
        
        // Nếu chưa tham gia hoạt động nào -> vector 0
        if ($totalActivities === 0) {
            return array_fill(0, 10, 0);
        }
        
        // Đếm hoạt động theo danh mục
        $historyCounts = array_fill(0, 10, 0);
        
        // Từ DRL activities
        foreach ($drlParticipated as $actId) {
            $activity = HoatDongDRL::find($actId);
            if ($activity && $activity->category_tags) {
                $tags = $this->parseInterestTags($activity->category_tags);
                foreach ($tags as $tag) {
                    if ($tag >= 1 && $tag <= 10) {
                        $historyCounts[$tag - 1]++;
                    }
                }
            }
        }
        
        // Từ CTXH activities
        foreach ($ctxhParticipated as $actId) {
            $activity = HoatDongCTXH::find($actId);
            if ($activity && $activity->category_tags) {
                $tags = $this->parseInterestTags($activity->category_tags);
                foreach ($tags as $tag) {
                    if ($tag >= 1 && $tag <= 10) {
                        $historyCounts[$tag - 1]++;
                    }
                }
            }
        }
        
        // Chuẩn hóa thành tỷ lệ
        $historyVector = array_map(function($count) use ($totalActivities) {
            return $count / $totalActivities;
        }, $historyCounts);
        
        return $historyVector;
    }

    /**
     * Parse category_tags string thành mảng InterestID
     * Hỗ trợ format: "1,2,3" hoặc "[1,2,3]"
     */
    private function parseInterestTags($tags)
    {
        $tags = trim($tags);
        
        // Loại bỏ dấu ngoặc nếu có
        if (str_starts_with($tags, '[') && str_ends_with($tags, ']')) {
            $tags = substr($tags, 1, -1);
        }
        
        // Split bằng comma
        $ids = array_map(function($id) {
            return intval(trim($id));
        }, explode(',', $tags));
        
        return array_filter($ids); // Loại bỏ 0 hoặc empty
    }

    /**
     * BƯỚC 2: TÍNH KHOẢNG CÁCH EUCLIDEAN CÓ TRỌNG SỐ
     * 
     * Formula: D_w(A, B) = √[Σ_k W_k·(A_k - B_k)²]
     * 
     * Với:
     * - W_k: Trọng số của thành phần k (phụ thuộc vào giai đoạn: Cold Start / Refinement)
     * - A_k, B_k: Giá trị thành phần k của vector A, B
     * 
     * ĐIỂM KHÁC BIỆT SO VỚI KHOẢNG CÁCH THÔNG THƯỜNG:
     * - Trọng số cao → thành phần đó ảnh hưởng nhiều hơn đến khoảng cách
     * - Trọng số thấp → thành phần đó ảnh hưởng ít hơn
     * - Thích ứng theo bối cảnh: Cold Start (ưu tiên khoa/năm), Refinement (ưu tiên lịch sử)
     */
    public function euclideanDistanceWeighted($vector1, $vector2, $weights = null)
    {
        if ($weights === null) {
            // Nếu không truyền weights, dùng mặc định (không phân biệt giai đoạn)
            $weights = array_fill(0, count($vector1), 1.0);
        }
        
        $sumSquaredDiff = 0;
        $dimensionCount = min(count($vector1), count($vector2), count($weights));
        
        for ($i = 0; $i < $dimensionCount; $i++) {
            $diff = $vector1[$i] - $vector2[$i];
            $sumSquaredDiff += $weights[$i] * ($diff * $diff);
        }
        
        return sqrt(max(0, $sumSquaredDiff)); // max(0, ...) để tránh sqrt số âm
    }

    /**
     * Chọn trọng số phù hợp dựa trên giai đoạn của sinh viên
     * 
     * COLD START PHASE:
     * - Sinh viên mới (< 5 hoạt động): Dựa vào khoa, năm học, sở thích
     * - W = [30% Khoa, 30% Năm, 40% Sở thích, 0% Lịch sử]
     * 
     * REFINEMENT PHASE:
     * - Sinh viên có kinh nghiệm (≥ 5 hoạt động): Dựa thêm vào hành vi quá khứ
     * - W = [20% Khoa, 20% Năm, 30% Sở thích, 30% Lịch sử]
     */
    private function getApplicableWeights($mssv)
    {
        // Đếm hoạt động đã tham gia
        $activityCount = DangKyHoatDongDRL::where('MSSV', $mssv)
            ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
            ->count()
            + DB::table('dangkyhoatdongctxh')
                ->where('MSSV', $mssv)
                ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
                ->count();
        
        // Nếu < threshold -> Cold Start, ngược lại -> Refinement
        if ($activityCount < $this->activity_threshold) {
            return $this->weights_cold_start;
        } else {
            return $this->weights_refinement;
        }
    }

    /**
     * Tạo mảng trọng số hoàn chỉnh từ cấu hình trọng số theo thành phần
     * 
     * Ví dụ input:
     *   ['faculty' => 0.3, 'year' => 0.3, 'interests' => 0.4, 'history' => 0.0]
     * 
     * Sẽ trở thành mảng full chiều dài vector:
     *   [0.3, 0.3, 0.3, 0.3, 0.3, 0.3, ...] (Khoa: N chiều)
     *   [0.3] (Năm học: 1 chiều)
     *   [0.4, 0.4, ..., 0.4] (Sở thích: 10 chiều)
     *   [0.0, 0.0, ..., 0.0] (Lịch sử: 10 chiều)
     */
    private function expandWeights($componentWeights, $facultyCount = 6)
    {
        $expandedWeights = [];
        
        // Faculty component: N chiều (ứng với N khoa)
        for ($i = 0; $i < $facultyCount; $i++) {
            $expandedWeights[] = $componentWeights['faculty'];
        }
        
        // Year component: 1 chiều
        $expandedWeights[] = $componentWeights['year'];
        
        // Interests component: 10 chiều
        for ($i = 0; $i < 10; $i++) {
            $expandedWeights[] = $componentWeights['interests'];
        }
        
        // History component: 10 chiều
        for ($i = 0; $i < 10; $i++) {
            $expandedWeights[] = $componentWeights['history'];
        }
        
        return $expandedWeights;
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
     * Chạy K-Means clustering với Trọng số Động
     * 
     * THUẬT TOÁN:
     * 1. Khởi tạo centroids ngẫu nhiên từ vectors
     * 2. Lặp cho tới khi hội tụ:
     *    a. Gán mỗi sinh viên vào cụm gần nhất (dùng weighted distance)
     *    b. Cập nhật centroids bằng trung bình vector trong mỗi cụm
     *    c. Kiểm tra hội tụ (assignments không thay đổi)
     * 3. Trả về assignments, centroids, số lần lặp
     * 
     * ĐIỂM KHÁC BIỆT VỚI K-MEANS TIÊU CHUẨN:
     * - Sử dụng Weighted Euclidean Distance thay vì Standard Euclidean
     * - Trọng số được lựa chọn động dựa trên giai đoạn: Cold Start vs Refinement
     */
    public function cluster($vectors)
    {
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
        
        // Số khoa để mở rộng trọng số
        $facultyCount = DB::table('khoa')->count();
        
        while ($iteration < $this->maxIterations) {
            // Gán sinh viên vào cụm gần nhất
            $newAssignments = [];
            foreach ($vectors as $mssv => $vector) {
                $minDistance = PHP_FLOAT_MAX;
                $closestCluster = 0;
                
                // Lấy trọng số phù hợp cho sinh viên này
                $componentWeights = $this->getApplicableWeights($mssv);
                $expandedWeights = $this->expandWeights($componentWeights, $facultyCount);
                
                // Tìm cụm gần nhất dùng weighted distance
                foreach ($centroids as $clusterIdx => $centroid) {
                    $distance = $this->euclideanDistanceWeighted($vector, $centroid, $expandedWeights);
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
            $distance = $this->euclideanDistanceWeighted($vector, $centroid);
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
                    $intraClusterDistances[] = $this->euclideanDistanceWeighted($vector, $otherVector);
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
                        $distances[] = $this->euclideanDistanceWeighted($vector, $otherVector);
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
                $distances[] = $this->euclideanDistanceWeighted($vector, $centroids[$clusterId]);
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
                
                $distance = $this->euclideanDistanceWeighted($centroids[$i], $centroids[$j]);
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
     * 
     * Chiến lược:
     * 1. Ưu tiên: Hoạt động phổ biến trong cluster + match interest
     * 2. Fallback: Hoạt động được tagging match interest (nếu cluster member ít hoạt động)
     * 3. Last resort: Hoạt động phổ biến toàn hệ thống
     */
    /**
     * BƯỚC 3: WORKFLOW GỢI Ý HOẠT ĐỘNG (HYBRID STRATEGY)
     * 
     * QUI TRÌNH:
     * 1. Xác định giai đoạn: Cold Start (< 5 hoạt động) hay Refinement (≥ 5 hoạt động)
     * 2. Tùy theo giai đoạn, áp dụng chiến lược khác:
     *
     * COLD START STRATEGY (Sinh viên mới):
     * - Sử dụng: Collaborative Filtering trên cluster
     * - Cơ chế: Gợi ý hoạt động phổ biến mà các bạn trong cluster tham gia
     * - Lợi ích: Giúp sinh viên nhanh chóng intergrate vào nhóm, tìm hoạt động "trend"
     *
     * REFINEMENT STRATEGY (Sinh viên kinh nghiệm):
     * - Sử dụng: User-based Collaborative Filtering + Content-based
     * - Cơ chế: Gợi ý dựa trên:
     *   a. Popularity trong cluster (các bạn cùng nhóm thích)
     *   b. Lịch sử tham gia (hoạt động tương tự hành vi quá khứ)
     *   c. Sở thích khai báo
     * - Lợi ích: Giới thiệu hoạt động mới nhưng vẫn phù hợp với hành vi đã chứng minh
     */
    public function generateRecommendations()
    {
        DB::table('activity_recommendations')->truncate();
        
        // Lấy tất cả sinh viên đã được phân cụm
        $clusterAssignments = \App\Models\StudentCluster::all();
        
        foreach ($clusterAssignments as $assignment) {
            $mssv = $assignment->MSSV;
            $clusterId = $assignment->ClusterID;
            
            // Xác định giai đoạn của sinh viên
            $activityCount = DangKyHoatDongDRL::where('MSSV', $mssv)
                ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
                ->count()
                + DB::table('dangkyhoatdongctxh')
                    ->where('MSSV', $mssv)
                    ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
                    ->count();
            
            $isInColdStart = ($activityCount < $this->activity_threshold);
            
            // Lấy các thành viên khác trong cùng cluster
            $clusterMembers = \App\Models\StudentCluster::where('ClusterID', $clusterId)
                ->where('MSSV', '!=', $mssv)
                ->pluck('MSSV')
                ->toArray();
            
            if ($isInColdStart) {
                // ===== COLD START: Collaborative Filtering =====
                // Gợi ý dựa trên "đám đông" - hoạt động phổ biến trong cluster
                
                // Gợi ý DRL
                $drlRecommendCount = 0;
                if (!empty($clusterMembers)) {
                    $drlRecommendCount = $this->recommendPopularActivitiesDRL($mssv, $clusterMembers, $clusterId);
                }
                
                // Nếu không đủ gợi ý, fallback dùng content-based (quan tâm đến sở thích)
                if ($drlRecommendCount < 5) {
                    $this->recommendContentBasedActivitiesDRL($mssv, $clusterMembers, 5 - $drlRecommendCount);
                }
                
                // Gợi ý CTXH
                $ctxhRecommendCount = 0;
                if (!empty($clusterMembers)) {
                    $ctxhRecommendCount = $this->recommendPopularActivitiesCTXH($mssv, $clusterMembers, $clusterId);
                }
                
                // Fallback cho CTXH
                if ($ctxhRecommendCount < 5) {
                    $this->recommendContentBasedActivitiesCTXH($mssv, $clusterMembers, 5 - $ctxhRecommendCount);
                }
            } else {
                // ===== REFINEMENT: Hybrid Collaborative + Content-based =====
                // Gợi ý dựa trên: Popularity + History + Interest Match
                
                // Gợi ý DRL
                $drlRecommendCount = 0;
                if (!empty($clusterMembers)) {
                    $drlRecommendCount = $this->recommendPopularActivitiesDRL($mssv, $clusterMembers, $clusterId);
                }
                
                // Fallback với activity-based recommendation (dựa trên lịch sử)
                if ($drlRecommendCount < 5) {
                    $this->recommendActivityBasedActivitiesDRL($mssv, 5 - $drlRecommendCount);
                }
                
                // Gợi ý CTXH
                $ctxhRecommendCount = 0;
                if (!empty($clusterMembers)) {
                    $ctxhRecommendCount = $this->recommendPopularActivitiesCTXH($mssv, $clusterMembers, $clusterId);
                }
                
                // Fallback cho CTXH
                if ($ctxhRecommendCount < 5) {
                    $this->recommendActivityBasedActivitiesCTXH($mssv, 5 - $ctxhRecommendCount);
                }
            }
        }
    }

    /**
     * Gợi ý hoạt động DRL dựa trên popularity trong cluster
     * (Collaborative Filtering - Phù hợp với cả Cold Start và Refinement)
     * 
     * FIX: Chỉ gợi ý hoạt động nếu có ≥2 người trong cluster tham gia (không quá generic)
     * Nếu cluster member ít tham gia → đừng khuyến cáo những hoạt động có <2 người
     */
    private function recommendPopularActivitiesDRL($mssv, $clusterMembers, $clusterId)
    {
        // Lấy các hoạt động đã được tham gia bởi những người trong cùng cluster
        $populateActivities = DB::table('dangkyhoatdongdrl as dk')
            ->join('hoatdongdrl as hd', 'dk.MaHoatDong', '=', 'hd.MaHoatDong')
            ->select('dk.MaHoatDong', 'hd.category_tags', DB::raw('COUNT(*) as popularity'))
            ->whereIn('dk.MSSV', $clusterMembers)
            ->where('dk.TrangThaiThamGia', 'Đã tham gia') // Chỉ lấy những hoạt động đã tham gia
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
        if ($populateActivities->count() > 0) {
            foreach ($populateActivities as $activity) {
                if ($recommendCount >= 5) break;
                if (in_array($activity->MaHoatDong, $studentRegistered)) continue;
                
                // FIX: Skip activities with < 2 cluster members participated (too generic)
                // Only recommend if there's meaningful participation (≥2 people)
                if ($activity->popularity < 2) {
                    continue;
                }
                
                // Lấy thông tin activity
                $activityInfo = \App\Models\HoatDongDRL::find($activity->MaHoatDong);
                if (!$activityInfo) continue;
                
                // Tính popularity score (normalized)
                $maxPopularity = $populateActivities->max('popularity');
                $popularityScore = ($activity->popularity / $maxPopularity) * 100;
                
                // Lấy sở thích của sinh viên
                $studentInterests = StudentInterest::where('MSSV', $mssv)
                    ->pluck('InterestID')
                    ->toArray();
                
                // Tính interest match score
                $interestMatchScore = $this->calculateActivityInterestMatch(
                    $activity->category_tags,
                    $studentInterests
                );
                
                // Tính match score dựa trên:
                // - Interest Match (50%): Hoạt động có liên quan sở thích
                // - Popularity trong cluster (35%): Phổ biến trong nhóm
                // - Tính mới của hoạt động (15%)
                $recencyBonus = $this->getRecencyBonus($activityInfo->ThoiGianBatDau);
                $matchScore = (0.5 * $interestMatchScore) + (0.35 * $popularityScore) + (0.15 * $recencyBonus);
                
                // Tạo reason message
                $reasonParts = [];
                $reasonParts[] = sprintf('Được %d thành viên khác tham gia', intval($activity->popularity));
                if ($interestMatchScore > 0) {
                    $reasonParts[] = sprintf('Match sở thích %.0f%%', $interestMatchScore);
                }
                $reason = implode('. ', $reasonParts);
                
                \App\Models\ActivityRecommendation::create([
                    'MSSV' => $mssv,
                    'MaHoatDong' => $activity->MaHoatDong,
                    'activity_type' => 'drl',
                    'recommendation_score' => round(min(100, max(50, $matchScore)), 2),
                    'recommendation_reason' => $reason,
                    'viewed_at' => null
                ]);
                
                $recommendCount++;
            }
        }
        
        return $recommendCount;
    }

    /**
     * Content-Based Recommendation cho DRL (Cold Start)
     * Dùng khi cluster member ít tham gia hoạt động
     * Gợi ý dựa trên: Interest match + Recency
     */
    private function recommendContentBasedActivitiesDRL($mssv, $clusterMembers, $remainingSlots)
    {
        if ($remainingSlots <= 0) return;
        
        // Lấy hoạt động chưa đăng ký
        $studentRegistered = DangKyHoatDongDRL::where('MSSV', $mssv)
            ->pluck('MaHoatDong')
            ->toArray();
        
        // Lấy sở thích của sinh viên
        $studentInterests = StudentInterest::where('MSSV', $mssv)
            ->pluck('InterestID')
            ->toArray();
        
        if (empty($studentInterests)) return;
        
        // Lấy hoạt động được tagging (category_tags không NULL)
        $allActivities = DB::table('hoatdongdrl')
            ->select('MaHoatDong', 'TenHoatDong', 'category_tags', 'ThoiGianBatDau')
            ->whereNotNull('category_tags')
            ->whereNotIn('MaHoatDong', $studentRegistered)
            ->orderByDesc('ThoiGianBatDau')
            ->limit(50)
            ->get();
        
        $recommendCount = 0;
        foreach ($allActivities as $activity) {
            if ($recommendCount >= $remainingSlots) break;
            
            // Tính interest match
            $interestMatchScore = $this->calculateActivityInterestMatch(
                $activity->category_tags,
                $studentInterests
            );
            
            // Chỉ gợi ý nếu có match hoặc hoạt động mới
            if ($interestMatchScore > 0 || $this->getRecencyBonus($activity->ThoiGianBatDau) > 50) {
                $recencyBonus = $this->getRecencyBonus($activity->ThoiGianBatDau);
                $matchScore = (0.7 * $interestMatchScore) + (0.3 * $recencyBonus);
                
                // Chỉ tạo gợi ý nếu score >= 50
                if ($matchScore >= 50) {
                    $reason = 'Hoạt động phù hợp với sở thích của bạn';
                    if ($interestMatchScore > 0) {
                        $reason .= sprintf(' (Match %.0f%%)', $interestMatchScore);
                    }
                    
                    \App\Models\ActivityRecommendation::create([
                        'MSSV' => $mssv,
                        'MaHoatDong' => $activity->MaHoatDong,
                        'activity_type' => 'drl',
                        'recommendation_score' => round(min(100, max(50, $matchScore)), 2),
                        'recommendation_reason' => $reason,
                        'viewed_at' => null
                    ]);
                    
                    $recommendCount++;
                }
            }
        }
    }

    /**
     * Gợi ý hoạt động CTXH dựa trên popularity trong cluster
     * 
     * FIX: Chỉ gợi ý hoạt động nếu có ≥2 người trong cluster tham gia (không quá generic)
     * Nếu cluster member ít tham gia → đừng khuyến cáo những hoạt động có <2 người
     */
    private function recommendPopularActivitiesCTXH($mssv, $clusterMembers, $clusterId)
    {
        // Lấy các hoạt động đã được tham gia bởi những người trong cùng cluster
        $populateActivities = DB::table('dangkyhoatdongctxh as dk')
            ->join('hoatdongctxh as hc', 'dk.MaHoatDong', '=', 'hc.MaHoatDong')
            ->select('dk.MaHoatDong', 'hc.category_tags', DB::raw('COUNT(*) as popularity'))
            ->whereIn('dk.MSSV', $clusterMembers)
            ->where('dk.TrangThaiThamGia', 'Đã tham gia') // Chỉ lấy những hoạt động đã tham gia
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
        if ($populateActivities->count() > 0) {
            foreach ($populateActivities as $activity) {
                if ($recommendCount >= 5) break;
                if (in_array($activity->MaHoatDong, $studentRegistered)) continue;
                
                // FIX: Skip activities with < 2 cluster members participated (too generic)
                // Only recommend if there's meaningful participation (≥2 people)
                if ($activity->popularity < 2) {
                    continue;
                }
                
                // Lấy thông tin activity
                $activityInfo = \App\Models\HoatDongCTXH::find($activity->MaHoatDong);
                if (!$activityInfo) continue;
                
                // Tính popularity score (normalized)
                $maxPopularity = $populateActivities->max('popularity');
                $popularityScore = ($activity->popularity / $maxPopularity) * 100;
                
                // Lấy sở thích của sinh viên
                $studentInterests = StudentInterest::where('MSSV', $mssv)
                    ->pluck('InterestID')
                    ->toArray();
                
                // Tính interest match score
                $interestMatchScore = $this->calculateActivityInterestMatch(
                    $activity->category_tags,
                    $studentInterests
                );
                
                // Tính match score dựa trên:
                // - Interest Match (50%): Hoạt động có liên quan sở thích
                // - Popularity trong cluster (35%): Phổ biến trong nhóm
                // - Tính mới của hoạt động (15%)
                $recencyBonus = $this->getRecencyBonus($activityInfo->ThoiGianBatDau);
                $matchScore = (0.5 * $interestMatchScore) + (0.35 * $popularityScore) + (0.15 * $recencyBonus);
                
                // Tạo reason message
                $reasonParts = [];
                $reasonParts[] = sprintf('Được %d thành viên khác tham gia', intval($activity->popularity));
                if ($interestMatchScore > 0) {
                    $reasonParts[] = sprintf('Match sở thích %.0f%%', $interestMatchScore);
                }
                $reason = implode('. ', $reasonParts);
                
                \App\Models\ActivityRecommendation::create([
                    'MSSV' => $mssv,
                    'MaHoatDong' => $activity->MaHoatDong,
                    'activity_type' => 'ctxh',
                    'recommendation_score' => round(min(100, max(50, $matchScore)), 2),
                    'recommendation_reason' => $reason,
                    'viewed_at' => null
                ]);
                
                $recommendCount++;
            }
        }
        
        return $recommendCount;
    }

    /**
     * Content-Based Recommendation cho CTXH
     * Dùng khi cluster member ít tham gia hoạt động
     * Gợi ý dựa trên: Interest match + Recency
     */
    private function recommendContentBasedActivitiesCTXH($mssv, $clusterMembers, $remainingSlots)
    {
        if ($remainingSlots <= 0) return;
        
        // Lấy hoạt động chưa đăng ký
        $studentRegistered = DB::table('dangkyhoatdongctxh')
            ->where('MSSV', $mssv)
            ->pluck('MaHoatDong')
            ->toArray();
        
        // Lấy sở thích của sinh viên
        $studentInterests = StudentInterest::where('MSSV', $mssv)
            ->pluck('InterestID')
            ->toArray();
        
        if (empty($studentInterests)) return;
        
        // Lấy hoạt động được tagging (category_tags không NULL)
        $allActivities = DB::table('hoatdongctxh')
            ->select('MaHoatDong', 'TenHoatDong', 'category_tags', 'ThoiGianBatDau')
            ->whereNotNull('category_tags')
            ->whereNotIn('MaHoatDong', $studentRegistered)
            ->orderByDesc('ThoiGianBatDau')
            ->limit(50)
            ->get();
        
        $recommendCount = 0;
        foreach ($allActivities as $activity) {
            if ($recommendCount >= $remainingSlots) break;
            
            // Tính interest match
            $interestMatchScore = $this->calculateActivityInterestMatch(
                $activity->category_tags,
                $studentInterests
            );
            
            // Chỉ gợi ý nếu có match hoặc hoạt động mới
            if ($interestMatchScore > 0 || $this->getRecencyBonus($activity->ThoiGianBatDau) > 50) {
                $recencyBonus = $this->getRecencyBonus($activity->ThoiGianBatDau);
                $matchScore = (0.7 * $interestMatchScore) + (0.3 * $recencyBonus);
                
                // Chỉ tạo gợi ý nếu score >= 50
                if ($matchScore >= 50) {
                    $reason = 'Hoạt động phù hợp với sở thích của bạn';
                    if ($interestMatchScore > 0) {
                        $reason .= sprintf(' (Match %.0f%%)', $interestMatchScore);
                    }
                    
                    \App\Models\ActivityRecommendation::create([
                        'MSSV' => $mssv,
                        'MaHoatDong' => $activity->MaHoatDong,
                        'activity_type' => 'ctxh',
                        'recommendation_score' => round(min(100, max(50, $matchScore)), 2),
                        'recommendation_reason' => $reason,
                        'viewed_at' => null
                    ]);
                    
                    $recommendCount++;
                }
            }
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

    /**
     * Tính Implicit Interests dựa trên hoạt động thực tế sinh viên tham gia
     * 
     * Phương pháp:
     * 1. Lấy tất cả hoạt động mà sinh viên đã tham gia (Có mặt/Đã tham gia)
     * 2. Trích xuất category_tags từ mỗi hoạt động
     * 3. Đếm số lần mỗi InterestID xuất hiện trong hoạt động
     * 4. Chuẩn hóa thành [0, 1] scale dựa trên max count
     */
    private function calculateImplicitInterests($mssv, $interests)
    {
        // Lấy tất cả hoạt động DRL mà sinh viên đã tham gia
        $drlActivities = DB::table('dangkyhoatdongdrl as dk')
            ->join('hoatdongdrl as hd', 'dk.MaHoatDong', '=', 'hd.MaHoatDong')
            ->select('hd.category_tags')
            ->where('dk.MSSV', $mssv)
            ->whereIn('dk.TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
            ->pluck('category_tags')
            ->toArray();
        
        // Lấy tất cả hoạt động CTXH mà sinh viên đã tham gia
        $ctxhActivities = DB::table('dangkyhoatdongctxh as dk')
            ->join('hoatdongctxh as hc', 'dk.MaHoatDong', '=', 'hc.MaHoatDong')
            ->select('hc.category_tags')
            ->where('dk.MSSV', $mssv)
            ->whereIn('dk.TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
            ->pluck('category_tags')
            ->toArray();
        
        $allActivityTags = array_merge($drlActivities, $ctxhActivities);
        
        // Đếm số lần mỗi InterestID xuất hiện
        $interestCounts = array_fill(0, 10, 0); // Khởi tạo cho 10 loại sở thích
        
        foreach ($allActivityTags as $tags) {
            if (!$tags) continue;
            
            // Parse category_tags (comma-separated interest IDs hoặc JSON)
            $interestIds = $this->parseInterestTags($tags);
            
            foreach ($interestIds as $interestId) {
                if ($interestId >= 1 && $interestId <= 10) {
                    $interestCounts[$interestId - 1]++;
                }
            }
        }
        
        // Chuẩn hóa: Chia cho số hoạt động tối đa (để scale [0, 1])
        // Benchmark: Nếu sinh viên tham gia 20 hoạt động, max count ~ 20
        // Chuẩn hóa: count / max(count, 20) để giới hạn [0, 1]
        $maxCount = max($interestCounts) ?: 1;
        $maxBenchmark = max($maxCount, 20); // Benchmark là 20 hoạt động
        
        $normalizedInterests = array_map(function($count) use ($maxBenchmark) {
            return $count / $maxBenchmark;
        }, $interestCounts);
        
        return $normalizedInterests;
    }


    /**
     * ACTIVITY-BASED RECOMMENDATION cho DRL (Refinement Phase)
     * 
     * Sử dụng cho sinh viên kinh nghiệm (≥ 5 hoạt động)
     * Gợi ý dựa trên: Tương đồng hoạt động + Lịch sử hành vi
     * 
     * LÝ THUYẾT:
     * - Tìm hoạt động tương tự với những hoạt động sinh viên đã tham gia
     * - "Tương tự" được định nghĩa bằng category_tags overlap
     * - Ưu tiên hoạt động được tham gia bởi nhiều bạn cùng khóa/khoa
     */
    private function recommendActivityBasedActivitiesDRL($mssv, $remainingSlots)
    {
        if ($remainingSlots <= 0) return;
        
        // Lấy hoạt động DRL mà sinh viên đã tham gia
        $participatedActivities = DangKyHoatDongDRL::where('MSSV', $mssv)
            ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
            ->pluck('MaHoatDong')
            ->toArray();
        
        if (empty($participatedActivities)) {
            // Fallback: Content-based nếu chưa tham gia hoạt động nào
            $this->recommendContentBasedActivitiesDRL($mssv, [], $remainingSlots);
            return;
        }
        
        // Lấy hoạt động chưa đăng ký
        $studentRegistered = DangKyHoatDongDRL::where('MSSV', $mssv)
            ->pluck('MaHoatDong')
            ->toArray();
        
        // Lấy category tags của hoạt động đã tham gia
        $participatedTags = DB::table('hoatdongdrl')
            ->whereIn('MaHoatDong', $participatedActivities)
            ->pluck('category_tags')
            ->toArray();
        
        // Merge tất cả tags từ hoạt động đã tham gia
        $studentTagSet = [];
        foreach ($participatedTags as $tags) {
            $parsed = $this->parseInterestTags($tags);
            foreach ($parsed as $tag) {
                if (!isset($studentTagSet[$tag])) {
                    $studentTagSet[$tag] = 0;
                }
                $studentTagSet[$tag]++;
            }
        }
        
        // Lấy hoạt động chưa tham gia nhưng có category_tags
        $candidateActivities = DB::table('hoatdongdrl')
            ->select('MaHoatDong', 'TenHoatDong', 'category_tags', 'ThoiGianBatDau')
            ->whereNotNull('category_tags')
            ->whereNotIn('MaHoatDong', $studentRegistered)
            ->orderByDesc('ThoiGianBatDau')
            ->limit(100)
            ->get();
        
        // Tính similarity score cho mỗi hoạt động
        $activityScores = [];
        foreach ($candidateActivities as $activity) {
            $activityTags = $this->parseInterestTags($activity->category_tags);
            
            // Tính Jaccard similarity: |A ∩ B| / |A ∪ B|
            $intersection = count(array_intersect(array_keys($studentTagSet), $activityTags));
            $union = count(array_unique(array_merge(array_keys($studentTagSet), $activityTags)));
            $jaccardSimilarity = $union > 0 ? ($intersection / $union) : 0;
            
            // Tính popularity: Số sinh viên cùng khoa/năm tham gia
            $studentInfo = SinhVien::find($mssv);
            $similarStudents = SinhVien::where('MaKhoa', $studentInfo->MaKhoa)
                ->where('MaLop', 'like', $studentInfo->MaLop[0] . '%')
                ->pluck('MSSV')
                ->toArray();
            
            $popularityInCluster = DangKyHoatDongDRL::whereIn('MSSV', $similarStudents)
                ->where('MaHoatDong', $activity->MaHoatDong)
                ->where('TrangThaiThamGia', 'Đã tham gia')
                ->count();
            
            $maxPopularity = max(1, count($similarStudents));
            $popularityScore = ($popularityInCluster / $maxPopularity) * 100;
            
            // Tính final score
            $recencyBonus = $this->getRecencyBonus($activity->ThoiGianBatDau);
            $finalScore = (0.5 * $jaccardSimilarity * 100) + (0.35 * $popularityScore) + (0.15 * $recencyBonus);
            
            if ($finalScore >= 40) { // Threshold
                $activityScores[$activity->MaHoatDong] = [
                    'activity' => $activity,
                    'score' => $finalScore,
                    'similarity' => $jaccardSimilarity
                ];
            }
        }
        
        // Sort theo score
        usort($activityScores, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        // Tạo gợi ý
        $recommendCount = 0;
        foreach ($activityScores as $item) {
            if ($recommendCount >= $remainingSlots) break;
            
            $reason = sprintf('Tương tự với hoạt động bạn tham gia (%.0f%% match)', $item['similarity'] * 100);
            
            \App\Models\ActivityRecommendation::create([
                'MSSV' => $mssv,
                'MaHoatDong' => $item['activity']->MaHoatDong,
                'activity_type' => 'drl',
                'recommendation_score' => round(min(100, max(50, $item['score'])), 2),
                'recommendation_reason' => $reason,
                'viewed_at' => null
            ]);
            
            $recommendCount++;
        }
    }

    /**
     * ACTIVITY-BASED RECOMMENDATION cho CTXH (Refinement Phase)
     * 
     * Sử dụng cho sinh viên kinh nghiệm (≥ 5 hoạt động)
     * Gợi ý dựa trên: Tương đồng hoạt động + Lịch sử hành vi
     */
    private function recommendActivityBasedActivitiesCTXH($mssv, $remainingSlots)
    {
        if ($remainingSlots <= 0) return;
        
        // Lấy hoạt động CTXH mà sinh viên đã tham gia
        $participatedActivities = DB::table('dangkyhoatdongctxh')
            ->where('MSSV', $mssv)
            ->whereIn('TrangThaiThamGia', ['Có mặt', 'Đã tham gia'])
            ->pluck('MaHoatDong')
            ->toArray();
        
        if (empty($participatedActivities)) {
            // Fallback: Content-based nếu chưa tham gia hoạt động nào
            $this->recommendContentBasedActivitiesCTXH($mssv, [], $remainingSlots);
            return;
        }
        
        // Lấy hoạt động chưa đăng ký
        $studentRegistered = DB::table('dangkyhoatdongctxh')
            ->where('MSSV', $mssv)
            ->pluck('MaHoatDong')
            ->toArray();
        
        // Lấy category tags của hoạt động đã tham gia
        $participatedTags = DB::table('hoatdongctxh')
            ->whereIn('MaHoatDong', $participatedActivities)
            ->pluck('category_tags')
            ->toArray();
        
        // Merge tất cả tags từ hoạt động đã tham gia
        $studentTagSet = [];
        foreach ($participatedTags as $tags) {
            $parsed = $this->parseInterestTags($tags);
            foreach ($parsed as $tag) {
                if (!isset($studentTagSet[$tag])) {
                    $studentTagSet[$tag] = 0;
                }
                $studentTagSet[$tag]++;
            }
        }
        
        // Lấy hoạt động chưa tham gia nhưng có category_tags
        $candidateActivities = DB::table('hoatdongctxh')
            ->select('MaHoatDong', 'TenHoatDong', 'category_tags', 'ThoiGianBatDau')
            ->whereNotNull('category_tags')
            ->whereNotIn('MaHoatDong', $studentRegistered)
            ->orderByDesc('ThoiGianBatDau')
            ->limit(100)
            ->get();
        
        // Tính similarity score cho mỗi hoạt động
        $activityScores = [];
        foreach ($candidateActivities as $activity) {
            $activityTags = $this->parseInterestTags($activity->category_tags);
            
            // Tính Jaccard similarity: |A ∩ B| / |A ∪ B|
            $intersection = count(array_intersect(array_keys($studentTagSet), $activityTags));
            $union = count(array_unique(array_merge(array_keys($studentTagSet), $activityTags)));
            $jaccardSimilarity = $union > 0 ? ($intersection / $union) : 0;
            
            // Tính popularity: Số sinh viên cùng khoa/năm tham gia
            $studentInfo = SinhVien::find($mssv);
            $similarStudents = SinhVien::where('MaKhoa', $studentInfo->MaKhoa)
                ->where('MaLop', 'like', $studentInfo->MaLop[0] . '%')
                ->pluck('MSSV')
                ->toArray();
            
            $popularityInCluster = DB::table('dangkyhoatdongctxh')
                ->whereIn('MSSV', $similarStudents)
                ->where('MaHoatDong', $activity->MaHoatDong)
                ->where('TrangThaiThamGia', 'Đã tham gia')
                ->count();
            
            $maxPopularity = max(1, count($similarStudents));
            $popularityScore = ($popularityInCluster / $maxPopularity) * 100;
            
            // Tính final score
            $recencyBonus = $this->getRecencyBonus($activity->ThoiGianBatDau);
            $finalScore = (0.5 * $jaccardSimilarity * 100) + (0.35 * $popularityScore) + (0.15 * $recencyBonus);
            
            if ($finalScore >= 40) { // Threshold
                $activityScores[$activity->MaHoatDong] = [
                    'activity' => $activity,
                    'score' => $finalScore,
                    'similarity' => $jaccardSimilarity
                ];
            }
        }
        
        // Sort theo score
        usort($activityScores, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        // Tạo gợi ý
        $recommendCount = 0;
        foreach ($activityScores as $item) {
            if ($recommendCount >= $remainingSlots) break;
            
            $reason = sprintf('Tương tự với hoạt động bạn tham gia (%.0f%% match)', $item['similarity'] * 100);
            
            \App\Models\ActivityRecommendation::create([
                'MSSV' => $mssv,
                'MaHoatDong' => $item['activity']->MaHoatDong,
                'activity_type' => 'ctxh',
                'recommendation_score' => round(min(100, max(50, $item['score'])), 2),
                'recommendation_reason' => $reason,
                'viewed_at' => null
            ]);
            
            $recommendCount++;
        }
    }
}

