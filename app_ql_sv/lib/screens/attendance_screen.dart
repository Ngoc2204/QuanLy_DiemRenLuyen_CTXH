import 'package:flutter/material.dart';

class AttendanceScreen extends StatefulWidget {
  const AttendanceScreen({super.key});

  @override
  State<AttendanceScreen> createState() => _AttendanceScreenState();
}

class _AttendanceScreenState extends State<AttendanceScreen> {
  // Dữ liệu lớp học
  final List<Map<String, dynamic>> _allClasses = [
    {
      'name': 'Cổ vũ văn nghệ 20/10',
      'session': '13:00 - 18:00',
      'type': 'Điểm rèn luyện',
      'location': 'HTC - 140 Lê Trọng Tấn',
      'status': 'Chưa điểm danh',
      'attended': false,
      'date': DateTime.now(),
    },
    {
      'name': 'Hiến máu nhân đạo',
      'session': '7:30 - 9:00',
      'type': 'CTXH',
      'location': 'Hồ cá koi',
      'status': 'Đã điểm danh',
      'attended': true,
      'date': DateTime.now(),
    },

  ];

  List<Map<String, dynamic>> get _classes {
    final today = DateTime.now();
    return _allClasses.where((classItem) {
      final classDate = classItem['date'] as DateTime;
      return classDate.year == today.year &&
          classDate.month == today.month &&
          classDate.day == today.day;
    }).toList();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      body: SafeArea(
        child: Column(
          children: [
            // Header với gradient
            Container(
              width: double.infinity,
              decoration: BoxDecoration(
                color: Color(0xFF1E5A96),

                borderRadius: BorderRadius.only(
                  bottomLeft: Radius.circular(30),
                  bottomRight: Radius.circular(30),
                ),
              ),
              child: Padding(
                padding: const EdgeInsets.fromLTRB(20, 20, 20, 30),
                child: Column(
                  children: [
                    Text(
                      'Điểm danh',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    SizedBox(height: 16),
                    // Thống kê nhanh
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                      children: [
                        _buildStatItem(
                          icon: Icons.event_available,
                          label: 'Hôm nay',
                          value: '${_classes.length}',
                          color: Colors.white,
                        ),
                        Container(
                          height: 40,
                          width: 1,
                          color: Colors.white.withOpacity(0.3),
                        ),
                        _buildStatItem(
                          icon: Icons.check_circle,
                          label: 'Đã điểm danh',
                          value: '${_classes.where((c) => c['attended']).length}',
                          color: Colors.white,
                        ),
                        Container(
                          height: 40,
                          width: 1,
                          color: Colors.white.withOpacity(0.3),
                        ),
                        _buildStatItem(
                          icon: Icons.pending_actions,
                          label: 'Chưa điểm danh',
                          value: '${_classes.where((c) => !c['attended']).length}',
                          color: Colors.white,
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),

            // Danh sách lớp học
            Expanded(
              child: _classes.isEmpty
                  ? Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      Icons.event_busy,
                      size: 80,
                      color: Colors.grey[400],
                    ),
                    SizedBox(height: 16),
                    Text(
                      'Không có lớp học hôm nay',
                      style: TextStyle(
                        fontSize: 16,
                        color: Colors.grey[600],
                      ),
                    ),
                  ],
                ),
              )
                  : ListView.builder(
                padding: EdgeInsets.all(16),
                itemCount: _classes.length,
                itemBuilder: (context, index) {
                  return _buildClassCard(_classes[index], index);
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildStatItem({
    required IconData icon,
    required String label,
    required String value,
    required Color color,
  }) {
    return Column(
      children: [
        Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, color: color, size: 20),
            SizedBox(width: 4),
            Text(
              value,
              style: TextStyle(
                color: color,
                fontSize: 22,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
        SizedBox(height: 4),
        Text(
          label,
          style: TextStyle(
            color: color.withOpacity(0.9),
            fontSize: 12,
          ),
        ),
      ],
    );
  }

  Widget _buildClassCard(Map<String, dynamic> classItem, int index) {
    return Container(
      margin: EdgeInsets.only(bottom: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.06),
            blurRadius: 10,
            offset: Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header card với màu
          Container(
            width: double.infinity,
            padding: EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: classItem['attended']
                  ? Color(0xFF81C784).withOpacity(0.1)
                  : Color(0xFFFF8A65).withOpacity(0.1),
              borderRadius: BorderRadius.only(
                topLeft: Radius.circular(20),
                topRight: Radius.circular(20),
              ),
            ),
            child: Row(
              children: [
                Container(
                  padding: EdgeInsets.all(10),
                  decoration: BoxDecoration(
                    color: classItem['attended']
                        ? Color(0xFF81C784)
                        : Color(0xFFFF8A65),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Icon(
                    classItem['attended']
                        ? Icons.check_circle
                        : Icons.schedule,
                    color: Colors.white,
                    size: 24,
                  ),
                ),
                SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        classItem['name'],
                        style: TextStyle(
                          fontSize: 17,
                          fontWeight: FontWeight.bold,
                          color: Colors.grey[800],
                        ),
                      ),
                      SizedBox(height: 4),
                      Container(
                        padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: classItem['attended']
                              ? Color(0xFF81C784)
                              : Color(0xFFFF8A65),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          classItem['status'],
                          style: TextStyle(
                            fontSize: 12,
                            color: Colors.white,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // Thông tin chi tiết
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              children: [
                _buildInfoRow(
                  icon: Icons.access_time,
                  label: 'Thời gian',
                  value: classItem['session'],
                  color: Color(0xFF64B5F6),
                ),
                SizedBox(height: 12),
                _buildInfoRow(
                  icon: Icons.class_,
                  label: 'Loại hoạt động',
                  value: classItem['type'],
                  color: Color(0xFF9575CD),
                ),
                SizedBox(height: 12),
                _buildInfoRow(
                  icon: Icons.location_on,
                  label: 'Địa điểm',
                  value: classItem['location'],
                  color: Color(0xFFFF8A65),
                ),

                // Nút điểm danh
                if (!classItem['attended']) ...[
                  SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    height: 48,
                    child: ElevatedButton(
                      onPressed: () {
                        setState(() {
                          classItem['attended'] = true;
                          classItem['status'] = 'Đã điểm danh';
                        });
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(
                            content: Row(
                              children: [
                                Icon(Icons.check_circle, color: Colors.white),
                                SizedBox(width: 8),
                                Text('Điểm danh thành công!'),
                              ],
                            ),
                            backgroundColor: Color(0xFF81C784),
                            duration: Duration(seconds: 2),
                            behavior: SnackBarBehavior.floating,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                          ),
                        );
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Color(0xFF2E5077),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        elevation: 0,
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.touch_app, size: 20),
                          SizedBox(width: 8),
                          Text(
                            'Điểm danh ngay',
                            style: TextStyle(
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ],
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInfoRow({
    required IconData icon,
    required String label,
    required String value,
    required Color color,
  }) {
    return Row(
      children: [
        Container(
          padding: EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: color.withOpacity(0.1),
            borderRadius: BorderRadius.circular(10),
          ),
          child: Icon(icon, color: color, size: 20),
        ),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                label,
                style: TextStyle(
                  fontSize: 12,
                  color: Colors.grey[600],
                ),
              ),
              SizedBox(height: 2),
              Text(
                value,
                style: TextStyle(
                  fontSize: 15,
                  fontWeight: FontWeight.w600,
                  color: Colors.grey[800],
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}