import 'package:flutter/material.dart';

class ActivityScheduleScreen extends StatefulWidget {
  const ActivityScheduleScreen({super.key});

  @override
  State<ActivityScheduleScreen> createState() => _ActivityScheduleScreenState();
}

class _ActivityScheduleScreenState extends State<ActivityScheduleScreen> {
  DateTime _selectedDate = DateTime.now();
  bool _isWeekView = false;

  // Dữ liệu hoạt động đã đăng ký
  final List<Map<String, dynamic>> _registeredActivities = [
    {
      'title': 'Cổ vũ văn nghệ',
      'date': DateTime.now(),
      'startTime': '13:00',
      'endTime': '17:00',
      'location': 'Hội trường C - 140 Lê Trọng Tấn',
      'points': '5',
      'category': 'Điểm rèn luyện',
      'categoryColor': Color(0xFFFFB74D),
      'status': 'Chưa bắt đầu',
    },
    {
      'title': 'Hiến máu nhân đạo',
      'date': DateTime.now().add(Duration(days: 1)),
      'startTime': '08:00',
      'endTime': '12:00',
      'location': 'Sân trường DHCN',
      'points': '10',
      'category': 'Điểm CTXH',
      'categoryColor': Color(0xFFE57373),
      'status': 'Chưa bắt đầu',
    },
    {
      'title': 'Đêm nhạc acoustic',
      'date': DateTime.now().add(Duration(days: 2)),
      'startTime': '19:00',
      'endTime': '21:00',
      'location': 'Sân khấu ngoài trời',
      'points': '5',
      'category': 'Điểm rèn luyện',
      'categoryColor': Color(0xFFFFB74D),
      'status': 'Chưa bắt đầu',
    },
    {
      'title': 'Tình nguyện dọn vệ sinh',
      'date': DateTime.now().add(Duration(days: 4)),
      'startTime': '06:00',
      'endTime': '10:00',
      'location': 'Công viên Thống Nhất',
      'points': '12',
      'category': 'Điểm CTXH',
      'categoryColor': Color(0xFFE57373),
      'status': 'Chưa bắt đầu',
    },
    {
      'title': 'Cuộc thi Marathon',
      'date': DateTime.now(),
      'startTime': '06:00',
      'endTime': '09:00',
      'location': 'Công viên Lê Văn Tám',
      'points': '8',
      'category': 'Điểm rèn luyện',
      'categoryColor': Color(0xFFFFB74D),
      'status': 'Chưa bắt đầu',
    },
  ];

  List<Map<String, dynamic>> get _filteredActivities {
    if (_isWeekView) {
      // Lấy hoạt động trong tuần
      final startOfWeek = _selectedDate.subtract(Duration(days: _selectedDate.weekday - 1));
      final endOfWeek = startOfWeek.add(Duration(days: 6));

      return _registeredActivities.where((activity) {
        final activityDate = activity['date'] as DateTime;
        return activityDate.isAfter(startOfWeek.subtract(Duration(days: 1))) &&
            activityDate.isBefore(endOfWeek.add(Duration(days: 1)));
      }).toList();
    } else {
      // Lấy hoạt động trong ngày
      return _registeredActivities.where((activity) {
        final activityDate = activity['date'] as DateTime;
        return activityDate.year == _selectedDate.year &&
            activityDate.month == _selectedDate.month &&
            activityDate.day == _selectedDate.day;
      }).toList();
    }
  }

  String _formatDate(DateTime date) {
    final weekdays = ['', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
    final weekday = weekdays[date.weekday];
    final day = date.day.toString().padLeft(2, '0');
    final month = date.month.toString().padLeft(2, '0');
    final year = date.year;
    return '$weekday, $day/$month/$year';
  }

  int _getWeekNumber(DateTime date) {
    final firstDayOfYear = DateTime(date.year, 1, 1);
    final daysSinceFirstDay = date.difference(firstDayOfYear).inDays;
    return (daysSinceFirstDay / 7).ceil() + 1;
  }

  IconData _getCategoryIcon(String category) {
    switch (category) {
      case 'Văn hóa':
        return Icons.music_note;
      case 'Cộng đồng':
        return Icons.volunteer_activism;
      case 'Học thuật':
        return Icons.school;
      default:
        return Icons.event;
    }
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
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          'Lịch hoạt động',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 24,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        // Toggle view button
                        Container(
                          padding: EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                          decoration: BoxDecoration(
                            color: Colors.white.withOpacity(0.2),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: Row(
                            children: [
                              Icon(
                                _isWeekView ? Icons.calendar_view_week : Icons.calendar_today,
                                color: Colors.white,
                                size: 18,
                              ),
                              SizedBox(width: 6),
                              Text(
                                _isWeekView ? 'Tuần' : 'Ngày',
                                style: TextStyle(
                                  color: Colors.white,
                                  fontWeight: FontWeight.w600,
                                  fontSize: 14,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                    SizedBox(height: 20),

                    // Date selector
                    Row(
                      children: [
                        IconButton(
                          icon: Icon(Icons.chevron_left, color: Colors.white),
                          onPressed: () {
                            setState(() {
                              _selectedDate = _selectedDate.subtract(
                                  Duration(days: _isWeekView ? 7 : 1)
                              );
                            });
                          },
                        ),
                        Expanded(
                          child: GestureDetector(
                            onTap: () {
                              setState(() {
                                _isWeekView = !_isWeekView;
                              });
                            },
                            child: Container(
                              padding: EdgeInsets.symmetric(vertical: 12),
                              decoration: BoxDecoration(
                                color: Colors.white.withOpacity(0.2),
                                borderRadius: BorderRadius.circular(12),
                              ),
                              child: Text(
                                _isWeekView
                                    ? 'Tuần ${_getWeekNumber(_selectedDate)}'
                                    : _formatDate(_selectedDate),
                                textAlign: TextAlign.center,
                                style: TextStyle(
                                  color: Colors.white,
                                  fontWeight: FontWeight.bold,
                                  fontSize: 16,
                                ),
                              ),
                            ),
                          ),
                        ),
                        IconButton(
                          icon: Icon(Icons.chevron_right, color: Colors.white),
                          onPressed: () {
                            setState(() {
                              _selectedDate = _selectedDate.add(
                                  Duration(days: _isWeekView ? 7 : 1)
                              );
                            });
                          },
                        ),
                      ],
                    ),
                    SizedBox(height: 16),

                    // Stats
                    Container(
                      padding: EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.white.withOpacity(0.15),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceAround,
                        children: [
                          _buildStatItem(
                            icon: Icons.event_available,
                            label: 'Hoạt động',
                            value: '${_filteredActivities.length}',
                          ),
                          Container(
                            height: 30,
                            width: 1,
                            color: Colors.white.withOpacity(0.3),
                          ),
                          _buildStatItem(
                            icon: Icons.star,
                            label: 'Tổng điểm',
                            value: '${_filteredActivities.fold(0, (sum, item) => sum + int.parse(item['points']))}',
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),

            // Danh sách hoạt động
            Expanded(
              child: _filteredActivities.isEmpty
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
                      _isWeekView
                          ? 'Không có hoạt động trong tuần này'
                          : 'Không có hoạt động hôm nay',
                      style: TextStyle(
                        fontSize: 16,
                        color: Colors.grey[600],
                      ),
                    ),
                    SizedBox(height: 8),
                    TextButton(
                      onPressed: () {
                        setState(() {
                          _selectedDate = DateTime.now();
                        });
                      },
                      child: Text('Về hôm nay'),
                    ),
                  ],
                ),
              )
                  : ListView.builder(
                padding: EdgeInsets.all(16),
                itemCount: _filteredActivities.length,
                itemBuilder: (context, index) {
                  return _buildActivityCard(_filteredActivities[index]);
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
  }) {
    return Row(
      children: [
        Icon(icon, color: Colors.white, size: 20),
        SizedBox(width: 8),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              value,
              style: TextStyle(
                color: Colors.white,
                fontSize: 20,
                fontWeight: FontWeight.bold,
              ),
            ),
            Text(
              label,
              style: TextStyle(
                color: Colors.white.withOpacity(0.9),
                fontSize: 12,
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildActivityCard(Map<String, dynamic> activity) {
    final activityDate = activity['date'] as DateTime;
    final isToday = activityDate.year == DateTime.now().year &&
        activityDate.month == DateTime.now().month &&
        activityDate.day == DateTime.now().day;

    return Container(
      margin: EdgeInsets.only(bottom: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: isToday ? Border.all(color: Color(0xFF4DA1A9), width: 2) : null,
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
          // Header
          Container(
            padding: EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: activity['categoryColor'].withOpacity(0.1),
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
                    color: activity['categoryColor'],
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Icon(
                    _getCategoryIcon(activity['category']),
                    color: Colors.white,
                    size: 20,
                  ),
                ),
                SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        activity['title'],
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
                          color: activity['categoryColor'],
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          activity['category'],
                          style: TextStyle(
                            fontSize: 11,
                            color: Colors.white,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                if (isToday)
                  Container(
                    padding: EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                    decoration: BoxDecoration(
                      color: Color(0xFF4DA1A9),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(
                      'Hôm nay',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
              ],
            ),
          ),

          // Nội dung
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              children: [
                // Ngày (nếu xem theo tuần)
                if (_isWeekView) ...[
                  Row(
                    children: [
                      Container(
                        padding: EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          color: Color(0xFF64B5F6).withOpacity(0.1),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: Icon(Icons.calendar_today, color: Color(0xFF64B5F6), size: 18),
                      ),
                      SizedBox(width: 12),
                      Text(
                        _formatDate(activityDate),
                        style: TextStyle(
                          fontSize: 14,
                          color: Colors.grey[700],
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ],
                  ),
                  SizedBox(height: 12),
                ],

                // Thời gian
                Row(
                  children: [
                    Container(
                      padding: EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: Color(0xFF9575CD).withOpacity(0.1),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: Icon(Icons.access_time, color: Color(0xFF9575CD), size: 18),
                    ),
                    SizedBox(width: 12),
                    Text(
                      '${activity['startTime']} - ${activity['endTime']}',
                      style: TextStyle(
                        fontSize: 14,
                        color: Colors.grey[700],
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ],
                ),
                SizedBox(height: 12),

                // Địa điểm
                Row(
                  children: [
                    Container(
                      padding: EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: Color(0xFFFF8A65).withOpacity(0.1),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: Icon(Icons.location_on, color: Color(0xFFFF8A65), size: 18),
                    ),
                    SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        activity['location'],
                        style: TextStyle(
                          fontSize: 14,
                          color: Colors.grey[700],
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ),
                  ],
                ),
                SizedBox(height: 12),

                // Điểm thưởng
                Row(
                  children: [
                    Container(
                      padding: EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: Color(0xFFFFB74D).withOpacity(0.1),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: Icon(Icons.star, color: Color(0xFFFFB74D), size: 18),
                    ),
                    SizedBox(width: 12),
                    Text(
                      '+${activity['points']} điểm',
                      style: TextStyle(
                        fontSize: 14,
                        color: Color(0xFFFFB74D),
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}