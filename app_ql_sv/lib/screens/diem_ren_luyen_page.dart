import 'package:flutter/material.dart';

class DiemRenLuyenScreen extends StatefulWidget {
  const DiemRenLuyenScreen({super.key});

  @override
  State<DiemRenLuyenScreen> createState() => _DiemRenLuyenScreenState();
}

class _DiemRenLuyenScreenState extends State<DiemRenLuyenScreen> {
  String _selectedSemester = 'HK1 2024-2025';

  final List<String> _semesters = [
    'HK1 2024-2025',
    'HK2 2023-2024',
    'HK1 2023-2024',
    'HK2 2022-2023',
  ];

  final Map<String, Map<String, dynamic>> _semesterScores = {
    'HK1 2024-2025': {
      'totalScore': 85,
      'classification': 'Xuất sắc',
      'details': [
        {'criteria': 'Ý thức học tập', 'maxScore': 25, 'achievedScore': 23},
        {'criteria': 'Tham gia hoạt động', 'maxScore': 25, 'achievedScore': 22},
        {'criteria': 'Ý thức tổ chức kỷ luật', 'maxScore': 25, 'achievedScore': 20},
        {'criteria': 'Phẩm chất công dân', 'maxScore': 25, 'achievedScore': 20},
      ],
    },
    'HK2 2023-2024': {
      'totalScore': 78,
      'classification': 'Tốt',
      'details': [
        {'criteria': 'Ý thức học tập', 'maxScore': 25, 'achievedScore': 20},
        {'criteria': 'Tham gia hoạt động', 'maxScore': 25, 'achievedScore': 19},
        {'criteria': 'Ý thức tổ chức kỷ luật', 'maxScore': 25, 'achievedScore': 19},
        {'criteria': 'Phẩm chất công dân', 'maxScore': 25, 'achievedScore': 20},
      ],
    },
    'HK1 2023-2024': {
      'totalScore': 82,
      'classification': 'Xuất sắc',
      'details': [
        {'criteria': 'Ý thức học tập', 'maxScore': 25, 'achievedScore': 21},
        {'criteria': 'Tham gia hoạt động', 'maxScore': 25, 'achievedScore': 21},
        {'criteria': 'Ý thức tổ chức kỷ luật', 'maxScore': 25, 'achievedScore': 20},
        {'criteria': 'Phẩm chất công dân', 'maxScore': 25, 'achievedScore': 20},
      ],
    },
    'HK2 2022-2023': {
      'totalScore': 75,
      'classification': 'Khá',
      'details': [
        {'criteria': 'Ý thức học tập', 'maxScore': 25, 'achievedScore': 19},
        {'criteria': 'Tham gia hoạt động', 'maxScore': 25, 'achievedScore': 18},
        {'criteria': 'Ý thức tổ chức kỷ luật', 'maxScore': 25, 'achievedScore': 19},
        {'criteria': 'Phẩm chất công dân', 'maxScore': 25, 'achievedScore': 19},
      ],
    },
  };

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
    final currentSemesterData = _semesterScores[_selectedSemester]!;

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
                          const Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Nguyễn Văn A',
                                  style: TextStyle(
                                    fontSize: 18,
                                    fontWeight: FontWeight.bold,
                                    color: Color(0xFF2E5077),
                                  ),
                                ),
                                SizedBox(height: 4),
                                Text(
                                  'MSSV: 2021001234',
                                  style: TextStyle(
                                    fontSize: 14,
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
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
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
                          value: _selectedSemester,
                          isExpanded: true,
                          icon: const Icon(Icons.arrow_drop_down, color: Color(0xFF1E5A96)),
                          style: const TextStyle(
                            color: Color(0xFF2E5077),
                            fontSize: 16,
                            fontWeight: FontWeight.w600,
                          ),
                          items: _semesters.map((String semester) {
                            return DropdownMenuItem<String>(
                              value: semester,
                              child: Row(
                                children: [
                                  const Icon(Icons.calendar_today, size: 18, color: Color(0xFF1E5A96)),
                                  const SizedBox(width: 12),
                                  Text(semester),
                                ],
                              ),
                            );
                          }).toList(),
                          onChanged: (String? newValue) {
                            setState(() {
                              _selectedSemester = newValue!;
                            });
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
                            _getClassificationColor(currentSemesterData['classification']),
                            _getClassificationColor(currentSemesterData['classification']).withOpacity(0.7),
                          ],
                          begin: Alignment.topLeft,
                          end: Alignment.bottomRight,
                        ),
                        borderRadius: BorderRadius.circular(20),
                        boxShadow: [
                          BoxShadow(
                            color: _getClassificationColor(currentSemesterData['classification']).withOpacity(0.3),
                            blurRadius: 15,
                            offset: const Offset(0, 5),
                          ),
                        ],
                      ),
                      child: Column(
                        children: [
                          Icon(
                            _getClassificationIcon(currentSemesterData['classification']),
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
                            '${currentSemesterData['totalScore']}',
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
                            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                            decoration: BoxDecoration(
                              color: Colors.white.withOpacity(0.25),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              'Xếp loại: ${currentSemesterData['classification']}',
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

                    // Danh sách chi tiết điểm
                    ...List.generate(
                      currentSemesterData['details'].length,
                          (index) {
                        final detail = currentSemesterData['details'][index];
                        final percentage = (detail['achievedScore'] / detail['maxScore'] * 100).toInt();

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
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Expanded(
                                    child: Text(
                                      detail['criteria'],
                                      style: const TextStyle(
                                        fontSize: 15,
                                        fontWeight: FontWeight.w600,
                                        color: Color(0xFF2E5077),
                                      ),
                                    ),
                                  ),
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                                    decoration: BoxDecoration(
                                      color: const Color(0xFF1E5A96).withOpacity(0.1),
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    child: Text(
                                      '${detail['achievedScore']}/${detail['maxScore']}',
                                      style: const TextStyle(
                                        fontSize: 16,
                                        fontWeight: FontWeight.bold,
                                        color: Color(0xFF1E5A96),
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 12),
                              Stack(
                                children: [
                                  Container(
                                    height: 10,
                                    decoration: BoxDecoration(
                                      color: Colors.grey[200],
                                      borderRadius: BorderRadius.circular(5),
                                    ),
                                  ),
                                  FractionallySizedBox(
                                    widthFactor: detail['achievedScore'] / detail['maxScore'],
                                    child: Container(
                                      height: 10,
                                      decoration: BoxDecoration(
                                        gradient: const LinearGradient(
                                          colors: [Color(0xFF1E5A96), Color(0xFF64B5F6)],
                                        ),
                                        borderRadius: BorderRadius.circular(5),
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 8),
                              Text(
                                '$percentage%',
                                style: TextStyle(
                                  fontSize: 12,
                                  color: Colors.grey[600],
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ],
                          ),
                        );
                      },
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
                          Icon(Icons.info_outline, color: Colors.blue[800], size: 24),
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