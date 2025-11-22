import 'package:flutter/material.dart';
import '../services/activity_service.dart';

class DiemRenLuyenScreen extends StatefulWidget {
  const DiemRenLuyenScreen({super.key});

  @override
  State<DiemRenLuyenScreen> createState() => _DiemRenLuyenScreenState();
}

class _DiemRenLuyenScreenState extends State<DiemRenLuyenScreen> {
  String _selectedSemester = '';
  bool _isLoading = true;
  Map<String, dynamic>? _sinhVienInfo;
  List<dynamic> _hocKyList = [];
  List<dynamic> _diemRenLuyenData = [];
  Map<String, dynamic>? _currentSemesterData;

  @override
  void initState() {
    super.initState();
    _loadDiemRenLuyenData();
  }

  Future<void> _loadDiemRenLuyenData() async {
    try {
      final result = await ActivityService.getDiemRenLuyenData();
      if (result['success']) {
        final data = result['data'];
        setState(() {
          _sinhVienInfo = data['sinh_vien_info'];
          _hocKyList = data['hoc_ky_list'];
          _diemRenLuyenData = data['diem_ren_luyen'];

          // Set học kỳ mặc định là học kỳ đầu tiên (mới nhất)
          if (_hocKyList.isNotEmpty) {
            _selectedSemester = _hocKyList[0]['ma_hoc_ky'];
            _updateCurrentSemesterData();
          }

          _isLoading = false;
        });
      } else {
        setState(() {
          _isLoading = false;
        });
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(result['message'] ?? 'Có lỗi xảy ra')),
        );
      }
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Không thể kết nối đến server')),
      );
    }
  }

  void _updateCurrentSemesterData() {
    _currentSemesterData = _diemRenLuyenData.firstWhere(
      (item) => item['ma_hoc_ky'] == _selectedSemester,
      orElse: () => null,
    );
    setState(() {});
  }

  Color _getClassificationColor(String classification) {
    switch (classification) {
      case 'Xuất sắc':
        return const Color(0xFF81C784);
      case 'Tốt':
        return const Color(0xFF64B5F6);
      case 'Khá':
        return const Color(0xFFFFB74D);
      default:
        return Colors.grey;
    }
  }

  IconData _getClassificationIcon(String classification) {
    switch (classification) {
      case 'Xuất sắc':
        return Icons.emoji_events;
      case 'Tốt':
        return Icons.thumb_up;
      case 'Khá':
        return Icons.star;
      default:
        return Icons.info;
    }
  }

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

    if (_currentSemesterData == null && _diemRenLuyenData.isEmpty) {
      return Scaffold(
        backgroundColor: Colors.grey[100],
        body: SafeArea(
          child: Column(
            children: [
              // Header (giữ nguyên)
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
                            'Điểm rèn luyện',
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 20,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          SizedBox(height: 2),
                          Text(
                            'Xem điểm rèn luyện theo học kỳ',
                            style: TextStyle(
                              color: Colors.white70,
                              fontSize: 13,
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 34),
                  ],
                ),
              ),
              const Expanded(
                child: Center(
                  child: Text(
                    'Chưa có dữ liệu điểm rèn luyện',
                    style: TextStyle(fontSize: 16, color: Colors.grey),
                  ),
                ),
              ),
            ],
          ),
        ),
      );
    }

    return Scaffold(
      backgroundColor: Colors.grey[100],
      body: SafeArea(
        child: Column(
          children: [
            // Header
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
                  // Nút back
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

                  // Tiêu đề
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Text(
                          'Điểm rèn luyện',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 20,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 2),
                        Text(
                          'Xem điểm rèn luyện theo học kỳ',
                          style: TextStyle(
                            color: Colors.white.withOpacity(0.85),
                            fontSize: 13,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(width: 34),
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
                    // Thông tin sinh viên
                    Container(
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
                            child: const Icon(
                              Icons.person,
                              size: 40,
                              color: Color(0xFF1E5A96),
                            ),
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
                                  style: const TextStyle(
                                    fontSize: 14,
                                    color: Colors.grey,
                                  ),
                                ),
                                Text(
                                  '${_sinhVienInfo?['khoa'] ?? 'N/A'} - ${_sinhVienInfo?['ma_lop'] ?? 'N/A'}',
                                  style: const TextStyle(
                                    fontSize: 12,
                                    color: Colors.grey,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),

                    // Dropdown chọn học kỳ
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 16,
                        vertical: 4,
                      ),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(15),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withOpacity(0.06),
                            blurRadius: 10,
                            offset: const Offset(0, 2),
                          ),
                        ],
                      ),
                      child: DropdownButtonHideUnderline(
                        child: DropdownButton<String>(
                          value:
                              _selectedSemester.isEmpty
                                  ? null
                                  : _selectedSemester,
                          isExpanded: true,
                          hint: const Text('Chọn học kỳ'),
                          icon: const Icon(
                            Icons.arrow_drop_down,
                            color: Color(0xFF1E5A96),
                          ),
                          style: const TextStyle(
                            color: Color(0xFF2E5077),
                            fontSize: 16,
                            fontWeight: FontWeight.w600,
                          ),
                          items:
                              _hocKyList.map<DropdownMenuItem<String>>((hocKy) {
                                return DropdownMenuItem<String>(
                                  value: hocKy['ma_hoc_ky'],
                                  child: Row(
                                    children: [
                                      const Icon(
                                        Icons.calendar_today,
                                        size: 18,
                                        color: Color(0xFF1E5A96),
                                      ),
                                      const SizedBox(width: 12),
                                      Text(hocKy['ten_hoc_ky']),
                                    ],
                                  ),
                                );
                              }).toList(),
                          onChanged: (String? newValue) {
                            if (newValue != null) {
                              setState(() {
                                _selectedSemester = newValue;
                                _updateCurrentSemesterData();
                              });
                            }
                          },
                        ),
                      ),
                    ),
                    const SizedBox(height: 20),

                    // Card tổng điểm
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(24),
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          colors: [
                            _getClassificationColor(
                              _currentSemesterData?['xep_loai'] ?? 'Khá',
                            ),
                            _getClassificationColor(
                              _currentSemesterData?['xep_loai'] ?? 'Khá',
                            ).withOpacity(0.7),
                          ],
                          begin: Alignment.topLeft,
                          end: Alignment.bottomRight,
                        ),
                        borderRadius: BorderRadius.circular(20),
                        boxShadow: [
                          BoxShadow(
                            color: _getClassificationColor(
                              _currentSemesterData?['xep_loai'] ?? 'Khá',
                            ).withOpacity(0.3),
                            blurRadius: 15,
                            offset: const Offset(0, 5),
                          ),
                        ],
                      ),
                      child: Column(
                        children: [
                          Icon(
                            _getClassificationIcon(
                              _currentSemesterData?['xep_loai'] ?? 'Khá',
                            ),
                            color: Colors.white,
                            size: 40,
                          ),
                          const SizedBox(height: 12),
                          const Text(
                            'TỔNG ĐIỂM RÈN LUYỆN',
                            style: TextStyle(
                              color: Colors.white70,
                              fontSize: 13,
                              fontWeight: FontWeight.w600,
                              letterSpacing: 1.2,
                            ),
                          ),
                          const SizedBox(height: 8),
                          Text(
                            '${_currentSemesterData?['tong_diem'] ?? 0}',
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 56,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const Text(
                            '/ 100 điểm',
                            style: TextStyle(
                              color: Colors.white70,
                              fontSize: 16,
                            ),
                          ),
                          const SizedBox(height: 16),
                          Container(
                            padding: const EdgeInsets.symmetric(
                              horizontal: 20,
                              vertical: 10,
                            ),
                            decoration: BoxDecoration(
                              color: Colors.white.withOpacity(0.25),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              'Xếp loại: ${_currentSemesterData?['xep_loai'] ?? 'Chưa có'}',
                              style: const TextStyle(
                                color: Colors.white,
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 24),

                    // Tiêu đề chi tiết
                    const Text(
                      'Chi tiết điểm theo tiêu chí',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: Color(0xFF2E5077),
                      ),
                    ),
                    const SizedBox(height: 12),

                    // Tiêu đề chi tiết
                    const Text(
                      'Chi tiết điều chỉnh điểm',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: Color(0xFF2E5077),
                      ),
                    ),
                    const SizedBox(height: 12),

                    // Hiển thị chi tiết điều chỉnh
                    if (_currentSemesterData != null &&
                        _currentSemesterData!['chi_tiet_dieu_chinh'] != null)
                      ...List.generate(
                        _currentSemesterData!['chi_tiet_dieu_chinh'].length,
                        (index) {
                          final detail =
                              _currentSemesterData!['chi_tiet_dieu_chinh'][index];
                          return Container(
                            margin: const EdgeInsets.only(bottom: 12),
                            padding: const EdgeInsets.all(16),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(15),
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.black.withOpacity(0.06),
                                  blurRadius: 8,
                                  offset: const Offset(0, 2),
                                ),
                              ],
                            ),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Expanded(
                                      child: Text(
                                        detail['ten_cong_viec'] ??
                                            'Chưa có tên',
                                        style: const TextStyle(
                                          fontSize: 15,
                                          fontWeight: FontWeight.w600,
                                          color: Color(0xFF2E5077),
                                        ),
                                      ),
                                    ),
                                    Container(
                                      padding: const EdgeInsets.symmetric(
                                        horizontal: 12,
                                        vertical: 6,
                                      ),
                                      decoration: BoxDecoration(
                                        color:
                                            (detail['diem_nhan'] ?? 0) >= 0
                                                ? const Color(
                                                  0xFF81C784,
                                                ).withOpacity(0.1)
                                                : Colors.red.withOpacity(0.1),
                                        borderRadius: BorderRadius.circular(8),
                                      ),
                                      child: Text(
                                        '${detail['diem_nhan'] ?? 0} điểm',
                                        style: TextStyle(
                                          fontSize: 16,
                                          fontWeight: FontWeight.bold,
                                          color:
                                              (detail['diem_nhan'] ?? 0) >= 0
                                                  ? const Color(0xFF81C784)
                                                  : Colors.red,
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 8),
                                Text(
                                  'Người cập nhật: ${detail['nguoi_cap_nhat'] ?? 'N/A'}',
                                  style: TextStyle(
                                    fontSize: 12,
                                    color: Colors.grey[600],
                                  ),
                                ),
                                Text(
                                  'Ngày: ${detail['ngay_cap_nhat'] ?? 'N/A'}',
                                  style: TextStyle(
                                    fontSize: 12,
                                    color: Colors.grey[600],
                                  ),
                                ),
                              ],
                            ),
                          );
                        },
                      ),

                    if (_currentSemesterData == null ||
                        _currentSemesterData!['chi_tiet_dieu_chinh'] == null ||
                        _currentSemesterData!['chi_tiet_dieu_chinh'].isEmpty)
                      Container(
                        padding: const EdgeInsets.all(20),
                        decoration: BoxDecoration(
                          color: Colors.grey[100],
                          borderRadius: BorderRadius.circular(15),
                        ),
                        child: const Center(
                          child: Text(
                            'Chưa có điều chỉnh điểm nào',
                            style: TextStyle(color: Colors.grey, fontSize: 14),
                          ),
                        ),
                      ),

                    const SizedBox(height: 16),

                    // Ghi chú
                    Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: Colors.blue[50],
                        borderRadius: BorderRadius.circular(15),
                        border: Border.all(color: Colors.blue[200]!),
                      ),
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Icon(
                            Icons.info_outline,
                            color: Colors.blue[800],
                            size: 24,
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Lưu ý',
                                  style: TextStyle(
                                    color: Colors.blue[900],
                                    fontSize: 14,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  'Điểm rèn luyện được đánh giá dựa trên 4 tiêu chí chính. Tổng điểm tối đa là 100 điểm.',
                                  style: TextStyle(
                                    color: Colors.blue[800],
                                    fontSize: 13,
                                    height: 1.4,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
