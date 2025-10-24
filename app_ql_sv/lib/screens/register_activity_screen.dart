import 'package:flutter/material.dart';

class RegisterActivityScreen extends StatefulWidget {
  const RegisterActivityScreen({super.key});

  @override
  State<RegisterActivityScreen> createState() => _RegisterActivityScreenState();
}

class _RegisterActivityScreenState extends State<RegisterActivityScreen> {
  // Dữ liệu hoạt động sắp diễn ra
  final List<Map<String, dynamic>> _upcomingActivities = [
    {
      'title': 'Cổ vũ văn nghệ',
      'date': '20/10/2025',
      'startTime': '13:00',
      'endTime': '17:00',
      'location': 'Hội trường C - 140 Lê Trọng Tấn',
      'points': '5',
      'category': 'Điểm rèn luyện',
      'categoryColor': Color(0xFFFFB74D),
      'participants': '120',
      'maxParticipants': '200',
      'cancelDeadline': '19/10/2025 23:59',
      'description': 'Hoạt động cổ vũ văn nghệ chào mừng kỷ niệm 20/10. Sinh viên sẽ tham gia biểu diễn và cổ vũ các tiết mục văn nghệ.',
      'isRegistered': false,
    },
    {
      'title': 'Hiến máu nhân đạo',
      'date': '22/10/2025',
      'startTime': '08:00',
      'endTime': '12:00',
      'location': 'Sân trường DHCN',
      'points': '10',
      'category': 'Điểm CTXH',
      'categoryColor': Color(0xFFE57373),
      'participants': '150',
      'maxParticipants': '200',
      'cancelDeadline': '21/10/2025 23:59',
      'description': 'Chương trình hiến máu tình nguyện nhằm cứu giúp những bệnh nhân đang cần máu. Mỗi đơn vị máu có thể cứu được 3 sinh mạng.',
      'isRegistered': true,
    },
    {
      'title': 'Marathon vì môi trường',
      'date': '25/10/2025',
      'startTime': '06:00',
      'endTime': '09:00',
      'location': 'Công viên Lê Văn Tám',
      'points': '8',
      'category': 'Điểm rèn luyện',
      'categoryColor': Color(0xFFFFB74D),
      'participants': '80',
      'maxParticipants': '100',
      'cancelDeadline': '24/10/2025 23:59',
      'description': 'Cuộc thi chạy marathon 5km kết hợp nhặt rác dọc đường, nâng cao ý thức bảo vệ môi trường.',
      'isRegistered': false,
    },
    {
      'title': 'Tình nguyện dạy học miễn phí',
      'date': '28/10/2025',
      'startTime': '14:00',
      'endTime': '17:00',
      'location': 'Trường THCS Nguyễn Du',
      'points': '12',
      'category': 'Điểm CTXH',
      'categoryColor': Color(0xFFE57373),
      'participants': '30',
      'maxParticipants': '50',
      'cancelDeadline': '27/10/2025 23:59',
      'description': 'Dạy học và hỗ trợ học tập cho các em học sinh có hoàn cảnh khó khăn.',
      'isRegistered': false,
    },
  ];

  void _showActivityDetail(Map<String, dynamic> activity) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => _buildActivityDetailSheet(activity),
    );
  }

  void _handleRegister(Map<String, dynamic> activity) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
        title: Row(
          children: [
            Icon(Icons.help_outline, color: Color(0xFF2E5077)),
            SizedBox(width: 8),
            Text('Xác nhận đăng ký'),
          ],
        ),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Bạn có chắc chắn muốn đăng ký hoạt động:'),
            SizedBox(height: 8),
            Text(
              activity['title'],
              style: TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 16,
              ),
            ),
            SizedBox(height: 12),
            Container(
              padding: EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.orange[50],
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                children: [
                  Icon(Icons.info_outline, color: Colors.orange[700], size: 20),
                  SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      'Hạn hủy: ${activity['cancelDeadline']}',
                      style: TextStyle(
                        fontSize: 13,
                        color: Colors.orange[700],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text('Hủy', style: TextStyle(color: Colors.grey)),
          ),
          ElevatedButton(
            onPressed: () {
              setState(() {
                activity['isRegistered'] = true;
                int currentParticipants = int.parse(activity['participants']);
                activity['participants'] = (currentParticipants + 1).toString();
              });
              Navigator.pop(context);
              Navigator.pop(context); // Đóng bottom sheet
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Row(
                    children: [
                      Icon(Icons.check_circle, color: Colors.white),
                      SizedBox(width: 8),
                      Text('Đăng ký hoạt động thành công!'),
                    ],
                  ),
                  backgroundColor: Color(0xFF81C784),
                  behavior: SnackBarBehavior.floating,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
              );
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: Color(0xFF2E5077),
            ),
            child: Text('Xác nhận', style: TextStyle(color: Colors.white)),
          ),
        ],
      ),
    );
  }

  void _handleCancelRegistration(Map<String, dynamic> activity) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
        title: Row(
          children: [
            Icon(Icons.warning_amber, color: Colors.red),
            SizedBox(width: 8),
            Text('Xác nhận hủy đăng ký'),
          ],
        ),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Bạn có chắc chắn muốn hủy đăng ký hoạt động:'),
            SizedBox(height: 8),
            Text(
              activity['title'],
              style: TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 16,
              ),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text('Quay lại', style: TextStyle(color: Colors.grey)),
          ),
          ElevatedButton(
            onPressed: () {
              setState(() {
                activity['isRegistered'] = false;
                int currentParticipants = int.parse(activity['participants']);
                activity['participants'] = (currentParticipants - 1).toString();
              });
              Navigator.pop(context);
              Navigator.pop(context); // Đóng bottom sheet
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Row(
                    children: [
                      Icon(Icons.info, color: Colors.white),
                      SizedBox(width: 8),
                      Text('Đã hủy đăng ký hoạt động'),
                    ],
                  ),
                  backgroundColor: Colors.orange,
                  behavior: SnackBarBehavior.floating,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
              );
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.red,
            ),
            child: Text('Hủy đăng ký', style: TextStyle(color: Colors.white)),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
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
                          'Đăng ký hoạt động',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 20,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 2),
                        Text(
                          'Chọn hoạt động và đăng ký ngay',
                          style: TextStyle(
                            color: Colors.white.withOpacity(0.85),
                            fontSize: 13,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(
                    width: 34,
                  ),
                ],
              ),
            ),




            // Danh sách hoạt động
            Expanded(
              child: ListView.builder(
                padding: EdgeInsets.all(16),
                itemCount: _upcomingActivities.length,
                itemBuilder: (context, index) {
                  return _buildActivityCard(_upcomingActivities[index]);
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildActivityCard(Map<String, dynamic> activity) {
    final isRegistered = activity['isRegistered'] as bool;
    final progress = int.parse(activity['participants']) / int.parse(activity['maxParticipants']);

    return Container(
      margin: EdgeInsets.only(bottom: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: isRegistered ? Border.all(color: Color(0xFF81C784), width: 2) : null,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.06),
            blurRadius: 10,
            offset: Offset(0, 2),
          ),
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(20),
          onTap: () => _showActivityDetail(activity),
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
                        activity['category'] == 'Điểm rèn luyện'
                            ? Icons.stars
                            : Icons.volunteer_activism,
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
                    if (isRegistered)
                      Container(
                        padding: EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                        decoration: BoxDecoration(
                          color: Color(0xFF81C784),
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Row(
                          children: [
                            Icon(Icons.check_circle, color: Colors.white, size: 14),
                            SizedBox(width: 4),
                            Text(
                              'Đã đăng ký',
                              style: TextStyle(
                                color: Colors.white,
                                fontSize: 12,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ],
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
                    _buildInfoRow(
                      icon: Icons.calendar_today,
                      value: activity['date'],
                      color: Color(0xFF64B5F6),
                    ),
                    SizedBox(height: 10),
                    _buildInfoRow(
                      icon: Icons.access_time,
                      value: '${activity['startTime']} - ${activity['endTime']}',
                      color: Color(0xFF9575CD),
                    ),
                    SizedBox(height: 10),
                    _buildInfoRow(
                      icon: Icons.location_on,
                      value: activity['location'],
                      color: Color(0xFFFF8A65),
                    ),
                    SizedBox(height: 16),

                    // Số lượng đăng ký
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Row(
                              children: [
                                Icon(Icons.people, size: 18, color: Colors.grey[600]),
                                SizedBox(width: 6),
                                Text(
                                  '${activity['participants']}/${activity['maxParticipants']} người',
                                  style: TextStyle(
                                    fontSize: 13,
                                    fontWeight: FontWeight.w600,
                                    color: Colors.grey[700],
                                  ),
                                ),
                              ],
                            ),
                            Text(
                              '+${activity['points']} điểm',
                              style: TextStyle(
                                fontSize: 13,
                                fontWeight: FontWeight.bold,
                                color: Color(0xFFFFB74D),
                              ),
                            ),
                          ],
                        ),
                        SizedBox(height: 8),
                        ClipRRect(
                          borderRadius: BorderRadius.circular(4),
                          child: LinearProgressIndicator(
                            value: progress,
                            backgroundColor: Colors.grey[200],
                            valueColor: AlwaysStoppedAnimation<Color>(
                              progress > 0.8 ? Colors.red : Color(0xFF81C784),
                            ),
                            minHeight: 6,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildInfoRow({
    required IconData icon,
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
          child: Icon(icon, color: color, size: 18),
        ),
        SizedBox(width: 12),
        Expanded(
          child: Text(
            value,
            style: TextStyle(
              fontSize: 14,
              color: Colors.grey[700],
              fontWeight: FontWeight.w500,
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildActivityDetailSheet(Map<String, dynamic> activity) {
    final isRegistered = activity['isRegistered'] as bool;

    return DraggableScrollableSheet(
      initialChildSize: 0.85,
      minChildSize: 0.5,
      maxChildSize: 0.95,
      builder: (context, scrollController) {
        return Container(
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.only(
              topLeft: Radius.circular(25),
              topRight: Radius.circular(25),
            ),
          ),
          child: Column(
            children: [
              // Handle bar
              Container(
                margin: EdgeInsets.symmetric(vertical: 12),
                width: 40,
                height: 4,
                decoration: BoxDecoration(
                  color: Colors.grey[300],
                  borderRadius: BorderRadius.circular(2),
                ),
              ),

              Expanded(
                child: SingleChildScrollView(
                  controller: scrollController,
                  padding: EdgeInsets.all(20),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Category badge
                      Container(
                        padding: EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: activity['categoryColor'],
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(
                          activity['category'],
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 12,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                      SizedBox(height: 12),

                      // Title
                      Text(
                        activity['title'],
                        style: TextStyle(
                          fontSize: 24,
                          fontWeight: FontWeight.bold,
                          color: Colors.grey[800],
                        ),
                      ),
                      SizedBox(height: 20),

                      // Thông tin chi tiết
                      _buildDetailRow(
                        Icons.calendar_today,
                        'Ngày diễn ra',
                        activity['date'],
                        Color(0xFF64B5F6),
                      ),
                      SizedBox(height: 16),
                      _buildDetailRow(
                        Icons.access_time,
                        'Thời gian',
                        '${activity['startTime']} - ${activity['endTime']}',
                        Color(0xFF9575CD),
                      ),
                      SizedBox(height: 16),
                      _buildDetailRow(
                        Icons.location_on,
                        'Địa điểm',
                        activity['location'],
                        Color(0xFFFF8A65),
                      ),
                      SizedBox(height: 16),
                      _buildDetailRow(
                        Icons.star,
                        'Điểm thưởng',
                        '+${activity['points']} điểm',
                        Color(0xFFFFB74D),
                      ),
                      SizedBox(height: 16),
                      _buildDetailRow(
                        Icons.people,
                        'Số lượng',
                        '${activity['participants']}/${activity['maxParticipants']} người',
                        Color(0xFF81C784),
                      ),
                      SizedBox(height: 16),
                      _buildDetailRow(
                        Icons.event_busy,
                        'Hạn hủy đăng ký',
                        activity['cancelDeadline'],
                        Colors.orange,
                      ),
                      SizedBox(height: 24),

                      // Mô tả
                      Text(
                        'Mô tả hoạt động',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                          color: Colors.grey[800],
                        ),
                      ),
                      SizedBox(height: 12),
                      Container(
                        padding: EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: Colors.grey[100],
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: Text(
                          activity['description'],
                          style: TextStyle(
                            fontSize: 14,
                            color: Colors.grey[700],
                            height: 1.5,
                          ),
                        ),
                      ),
                      SizedBox(height: 80), // Space for button
                    ],
                  ),
                ),
              ),

              // Action button
              Container(
                padding: EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.05),
                      blurRadius: 10,
                      offset: Offset(0, -2),
                    ),
                  ],
                ),
                child: SafeArea(
                  child: SizedBox(
                    width: double.infinity,
                    height: 50,
                    child: ElevatedButton(
                      onPressed: isRegistered
                          ? () => _handleCancelRegistration(activity)
                          : () => _handleRegister(activity),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: isRegistered ? Colors.red : Color(0xFF2E5077),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        elevation: 0,
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(isRegistered ? Icons.cancel : Icons.check_circle, size: 20),
                          SizedBox(width: 8),
                          Text(
                            isRegistered ? 'Hủy đăng ký' : 'Đăng ký ngay',
                            style: TextStyle(
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildDetailRow(IconData icon, String label, String value, Color color) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Container(
          padding: EdgeInsets.all(10),
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