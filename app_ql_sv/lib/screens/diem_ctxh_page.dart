import 'package:flutter/material.dart';
import '../services/activity_service.dart';

class DiemCTXHScreen extends StatefulWidget {
  const DiemCTXHScreen({super.key});

  @override
  State<DiemCTXHScreen> createState() => _DiemCTXHScreenState();
}

class _DiemCTXHScreenState extends State<DiemCTXHScreen> {
  bool _isLoading = true;
  Map<String, dynamic>? _sinhVienInfo;
  Map<String, dynamic>? _ctxhData;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _loadCTXHData();
  }

  Future<void> _loadCTXHData() async {
    try {
      final result = await ActivityService.getDiemCTXHData();
      if (result['success']) {
        final data = result['data'];
        setState(() {
          _sinhVienInfo = data['sinh_vien_info'];
          _ctxhData = data;
          _isLoading = false;
        });
      } else {
        setState(() {
          _errorMessage = result['message'] ?? 'Có lỗi xảy ra';
          _isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Lỗi kết nối: $e';
        _isLoading = false;
      });
    }
  }

  // --- ĐÃ LOẠI BỎ CÁC HÀM XẾP LOẠI (getClassification) ---

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return Scaffold(
        backgroundColor: Colors.grey[100],
        body: const Center(
          child: CircularProgressIndicator(
            valueColor: AlwaysStoppedAnimation<Color>(Color(0xFF2E5077)),
          ),
        ),
      );
    }

    if (_errorMessage != null) {
      return Scaffold(
        backgroundColor: Colors.grey[100],
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(Icons.error_outline, size: 48, color: Colors.red[300]),
              const SizedBox(height: 16),
              Text(
                _errorMessage!,
                style: const TextStyle(fontSize: 16, color: Colors.red),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 16),
              ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF1E5A96),
                  foregroundColor: Colors.white,
                ),
                onPressed: () {
                  setState(() {
                    _isLoading = true;
                    _errorMessage = null;
                  });
                  _loadCTXHData();
                },
                child: const Text('Thử lại'),
              ),
            ],
          ),
        ),
      );
    }

    // Dữ liệu cho thẻ
    final totalScore = _ctxhData?['tong_diem_ctxh'] ?? 0;
    // Giả sử API trả về `max_score` là điểm mục tiêu, nếu không mặc định là 100
    final maxScore = _ctxhData?['max_score'] ?? 170;
    final hasRedActivity = _ctxhData?['has_red_activity'] ?? false;

    return Scaffold(
      backgroundColor: Colors.grey[100],
      body: SafeArea(
        child: Column(
          children: [
            // Header tùy chỉnh (Giống DRL)
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(16, 18, 16, 20),
              decoration: BoxDecoration(
                color: const Color(0xFF1E5A96),
                borderRadius: const BorderRadius.only(
                  bottomLeft: Radius.circular(25),
                  bottomRight: Radius.circular(25),
                ),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.1),
                    blurRadius: 6,
                    offset: const Offset(0, 3),
                  ),
                ],
              ),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  Padding(
                    padding: const EdgeInsets.only(left: 0, right: 8),
                    child: InkWell(
                      borderRadius: BorderRadius.circular(20),
                      onTap: () => Navigator.pop(context),
                      child: const Padding(
                        padding: EdgeInsets.all(6),
                        child: Icon(
                          Icons.arrow_back_ios_new,
                          color: Colors.white,
                          size: 22,
                        ),
                      ),
                    ),
                  ),
                  const Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(
                          'Điểm CTXH',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 20,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        SizedBox(height: 2),
                        Text(
                          'Tổng hợp điểm công tác xã hội',
                          style: TextStyle(color: Colors.white70, fontSize: 13),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(width: 34), // Để căn giữa tiêu đề
                ],
              ),
            ),

            // Content
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildStudentInfoCard(),
                    const SizedBox(height: 20),
                    // Thẻ điểm (Đã bỏ Xếp loại)
                    _buildScoreCard(totalScore, maxScore),
                    const SizedBox(height: 20),
                    // THẺ MỚI: Điều kiện Địa chỉ đỏ
                    _buildRedAddressCard(hasRedActivity),
                    const SizedBox(height: 24),
                    const Text(
                      'Chi tiết hoạt động đã tham gia',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: Color(0xFF2E5077),
                      ),
                    ),
                    const SizedBox(height: 12),
                    _buildActivitiesCard(),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildStudentInfoCard() {
    // Giữ nguyên style DRL
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.06),
            blurRadius: 10,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: const Color(0xFF1E5A96).withOpacity(0.1),
              borderRadius: BorderRadius.circular(15),
            ),
            child: const Icon(Icons.person, size: 40, color: Color(0xFF1E5A96)),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  _sinhVienInfo?['ho_ten'] ?? 'Chưa có tên',
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF2E5077),
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  'MSSV: ${_sinhVienInfo?['mssv'] ?? 'N/A'}',
                  style: const TextStyle(fontSize: 14, color: Colors.grey),
                ),
                Text(
                  '${_sinhVienInfo?['khoa'] ?? 'N/A'} - ${_sinhVienInfo?['ma_lop'] ?? 'N/A'}',
                  style: const TextStyle(fontSize: 12, color: Colors.grey),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildScoreCard(int totalScore, int maxScore) {
    // SỬA LẠI: Dùng màu đỏ/tím cố định từ web và bỏ logic xếp loại
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [
            Color(0xFFf5576c), // Màu đỏ từ web
            Color(0xFF764ba2), // Màu tím từ web
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFFf5576c).withOpacity(0.3),
            blurRadius: 15,
            offset: const Offset(0, 5),
          ),
        ],
      ),
      child: Column(
        children: [
          const Icon(
            Icons.volunteer_activism, // Icon cho hoạt động tình nguyện/CTXH
            color: Colors.white,
            size: 40,
          ),
          const SizedBox(height: 12),
          const Text(
            'TỔNG ĐIỂM CTXH TÍCH LŨY',
            style: TextStyle(
              color: Colors.white70,
              fontSize: 13,
              fontWeight: FontWeight.w600,
              letterSpacing: 1.2,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            '$totalScore',
            style: const TextStyle(
              color: Colors.white,
              fontSize: 56,
              fontWeight: FontWeight.bold,
            ),
          ),
          Text(
            '/ $maxScore điểm mục tiêu', // Giữ lại điểm mục tiêu
            style: const TextStyle(color: Colors.white70, fontSize: 16),
          ),
          // Đã BỎ thẻ "Xếp loại"
        ],
      ),
    );
  }

  // WIDGET MỚI: Hiển thị điều kiện Địa chỉ đỏ
  Widget _buildRedAddressCard(bool hasRedActivity) {
    final color =
        hasRedActivity ? const Color(0xFF4CAF50) : const Color(0xFFF44336);
    final icon = hasRedActivity ? Icons.check_circle : Icons.warning;
    final text = hasRedActivity ? 'Đã đạt' : 'Chưa đạt';

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.06),
            blurRadius: 10,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: color.withOpacity(0.1),
              borderRadius: BorderRadius.circular(15),
            ),
            child: Icon(icon, size: 32, color: color),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Điều kiện bắt buộc (Địa chỉ đỏ)',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF2E5077),
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  text,
                  style: TextStyle(
                    fontSize: 14,
                    color: color,
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildActivitiesCard() {
    final completedActivities =
        _ctxhData?['hoat_dong_da_hoan_thanh'] as List? ?? [];
    final pendingActivities =
        _ctxhData?['hoat_dong_cho_ket_qua'] as List? ?? [];

    return Container(
      width: double.infinity,
      // Bỏ padding 16 ở đây để các item con có thể full-width
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.06),
            blurRadius: 10,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header
          Container(
            padding: const EdgeInsets.all(16),
            child: Row(
              children: [
                Icon(Icons.assignment, color: Colors.blue[600], size: 20),
                const SizedBox(width: 8),
                const Text(
                  'Hoạt động CTXH',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                    color: Color(0xFF1E3A8A),
                  ),
                ),
              ],
            ),
          ),

          // Completed Activities
          if (completedActivities.isNotEmpty) ...[
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Text(
                'Hoạt động đã hoàn thành (${completedActivities.length})',
                style: TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                  color: Colors.green[700],
                ),
              ),
            ),
            const SizedBox(height: 8),
            ...completedActivities
                .map(
                  (activity) => _buildActivityItem(activity, isCompleted: true),
                )
                .toList(),
            const SizedBox(height: 16),
          ],

          // Pending Activities
          if (pendingActivities.isNotEmpty) ...[
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Text(
                'Hoạt động chờ kết quả (${pendingActivities.length})',
                style: TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                  color: Colors.orange[700],
                ),
              ),
            ),
            const SizedBox(height: 8),
            ...pendingActivities
                .map(
                  (activity) =>
                      _buildActivityItem(activity, isCompleted: false),
                )
                .toList(),
            const SizedBox(height: 16),
          ],

          // No activities message
          if (completedActivities.isEmpty && pendingActivities.isEmpty)
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  Icon(
                    Icons.assignment_outlined,
                    size: 48,
                    color: Colors.grey[400],
                  ),
                  const SizedBox(height: 12),
                  Text(
                    'Chưa có hoạt động CTXH nào',
                    style: TextStyle(
                      fontSize: 16,
                      color: Colors.grey[500],
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Hãy tham gia các hoạt động CTXH để tích lũy điểm',
                    style: TextStyle(fontSize: 14, color: Colors.grey[400]),
                    textAlign: TextAlign.center,
                  ),
                ],
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildActivityItem(
    Map<String, dynamic> activity, {
    bool? isCompleted,
  }) {
    final completed = isCompleted ?? (activity['check_out_at'] != null);
    final diemNhan = activity['diem_nhan'] ?? activity['diem_du_kien'] ?? 0;
    final tenHoatDong = activity['ten_hoat_dong'] ?? 'Không có tên';
    final diaDiem = activity['dia_diem'] ?? 'Chưa có địa điểm';
    final thoiGian =
        activity['thoi_gian_bat_dau'] ?? activity['ngay_dang_ky'] ?? '';

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.grey[50],
        borderRadius: BorderRadius.circular(12),
        border: Border(
          left: BorderSide(
            color: completed ? Colors.green : Colors.orange,
            width: 4,
          ),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Expanded(
                child: Text(
                  tenHoatDong,
                  style: const TextStyle(
                    fontWeight: FontWeight.w600,
                    fontSize: 15,
                    color: Color(0xFF2E5077),
                  ),
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 4,
                ),
                decoration: BoxDecoration(
                  color: completed ? Colors.green[100] : Colors.orange[100],
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  '+$diemNhan điểm',
                  style: TextStyle(
                    fontSize: 12,
                    color: completed ? Colors.green[700] : Colors.orange[700],
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          if (diaDiem.isNotEmpty && diaDiem != 'Chưa có địa điểm') ...[
            Row(
              children: [
                Icon(Icons.location_on, size: 14, color: Colors.grey[600]),
                const SizedBox(width: 4),
                Expanded(
                  child: Text(
                    diaDiem,
                    style: TextStyle(fontSize: 13, color: Colors.grey[600]),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 4),
          ],
          if (thoiGian.isNotEmpty) ...[
            Row(
              children: [
                Icon(Icons.access_time, size: 14, color: Colors.grey[600]),
                const SizedBox(width: 4),
                Text(
                  _formatDateTime(thoiGian),
                  style: TextStyle(fontSize: 13, color: Colors.grey[600]),
                ),
              ],
            ),
            const SizedBox(height: 4),
          ],
          Row(
            children: [
              Icon(
                completed ? Icons.check_circle : Icons.schedule,
                size: 16,
                color: completed ? Colors.green : Colors.orange,
              ),
              const SizedBox(width: 4),
              Text(
                completed ? 'Đã hoàn thành' : 'Chờ kết quả',
                style: TextStyle(
                  fontSize: 12,
                  color: completed ? Colors.green[700] : Colors.orange[700],
                  fontWeight: FontWeight.w500,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  String _formatDateTime(String dateTimeStr) {
    try {
      final dateTime = DateTime.parse(dateTimeStr);
      return '${dateTime.day}/${dateTime.month}/${dateTime.year} ${dateTime.hour.toString().padLeft(2, '0')}:${dateTime.minute.toString().padLeft(2, '0')}';
    } catch (e) {
      return dateTimeStr;
    }
  }
}
