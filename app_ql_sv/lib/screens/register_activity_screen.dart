import 'package:flutter/material.dart';
import '../services/api_service.dart';

class RegisterActivityScreen extends StatefulWidget {
  const RegisterActivityScreen({super.key});

  @override
  State<RegisterActivityScreen> createState() => _RegisterActivityScreenState();
}

class _RegisterActivityScreenState extends State<RegisterActivityScreen> {
  List<Map<String, dynamic>> _availableActivities = [];
  List<Map<String, dynamic>> _userRegistrations = [];
  bool _isLoading = true;
  String _selectedFilter = 'Tất cả'; // Tất cả, DRL, CTXH

  @override
  void initState() {
    super.initState();
    _loadActivities();
  }

  Future<void> _loadActivities() async {
    try {
      if (!mounted) return;
      setState(() => _isLoading = true);

      // Check if user is logged in
      final token = await ApiService.getToken();
      print('User token: ${token != null ? 'Available' : 'Missing'}');

      // Load all available activities (both DRL and CTXH)
      final activitiesResponse = await ApiService.get(
        '/activities',
        needAuth: true,
      );
      // Load user registrations - Skip for now
      // final registrationsResponse = await ApiService.get(
      //   '/my-registrations',
      //   needAuth: true,
      // );

      List<Map<String, dynamic>> allActivities = [];

      print('Activities API Response: ${activitiesResponse.statusCode}');
      print('Activities API Body: ${activitiesResponse.body}');

      if (activitiesResponse.statusCode == 200) {
        final activitiesData = ApiService.parseResponse(activitiesResponse);
        print('Activities Data: $activitiesData');
        if (activitiesData['success'] == true) {
          final activities = activitiesData['data'] as List;
          print('Found ${activities.length} activities');
          allActivities =
              activities.map((activity) {
                return {
                  'id': activity['id'],
                  'title': activity['ten'],
                  'description': activity['mo_ta'] ?? 'Không có mô tả',
                  'date': _formatDate(activity['ngay_to_chuc']),
                  'startTime': _formatTime(activity['ngay_to_chuc']),
                  'endTime': _formatEndTime(activity['ngay_to_chuc']),
                  'location': activity['dia_diem'] ?? 'Chưa xác định',
                  'points':
                      activity['type'] == 'DRL'
                          ? activity['diem_rl'].toString()
                          : activity['diem_ctxh'].toString(),
                  'category':
                      activity['type'] == 'DRL'
                          ? 'Điểm rèn luyện'
                          : 'Công tác xã hội',
                  'categoryColor':
                      activity['type'] == 'DRL'
                          ? Color(0xFFFFB74D)
                          : Color(0xFFE57373),
                  'type': activity['type'],
                  'maxParticipants':
                      activity['so_luong_toi_da']?.toString() ?? '0',
                  'participants': '0', // Will be calculated
                  'cancelDeadline': _formatRegistrationDeadline(
                    activity['thoi_han_huy'],
                  ),
                  'isRegistered':
                      false, // Will be updated based on registrations
                };
              }).toList();
        }
      } else {
        print(
          'API Error: ${activitiesResponse.statusCode} - ${activitiesResponse.body}',
        );
      }

      // Load user registrations to check which activities are already registered - Skip for now
      Set<String> registeredActivityIds = {};
      // if (registrationsResponse.statusCode == 200) {
      //   final registrationsData = ApiService.parseResponse(
      //     registrationsResponse,
      //   );
      //   if (registrationsData['success'] == true) {
      //     final registrations = registrationsData['data'];
      //
      //     if (registrations['drl_registrations'] != null) {
      //       for (var reg in registrations['drl_registrations']) {
      //         registeredActivityIds.add(reg['ma_hoat_dong']);
      //       }
      //     }
      //
      //     if (registrations['ctxh_registrations'] != null) {
      //       for (var reg in registrations['ctxh_registrations']) {
      //         registeredActivityIds.add(reg['ma_hoat_dong']);
      //       }
      //     }
      //   }
      // }

      // Update registration status - All false for now
      for (var activity in allActivities) {
        activity['isRegistered'] =
            false; // registeredActivityIds.contains(activity['id']);
      }

      if (!mounted) return;
      setState(() {
        _availableActivities = allActivities;
      });
      print('Updated UI with ${allActivities.length} activities');
    } catch (e) {
      _showErrorMessage('Có lỗi xảy ra khi tải dữ liệu: $e');
    } finally {
      if (mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  String _formatDate(String? dateTime) {
    if (dateTime == null) return 'Chưa xác định';
    try {
      final date = DateTime.parse(dateTime);
      return '${date.day.toString().padLeft(2, '0')}/${date.month.toString().padLeft(2, '0')}/${date.year}';
    } catch (e) {
      return 'Chưa xác định';
    }
  }

  String _formatTime(String? dateTime) {
    if (dateTime == null) return '00:00';
    try {
      final date = DateTime.parse(dateTime);
      return '${date.hour.toString().padLeft(2, '0')}:${date.minute.toString().padLeft(2, '0')}';
    } catch (e) {
      return '00:00';
    }
  }

  String _formatEndTime(String? dateTime) {
    if (dateTime == null) return '00:00';
    try {
      final date = DateTime.parse(dateTime);
      final endTime = date.add(Duration(hours: 3)); // Assume 3 hours duration
      return '${endTime.hour.toString().padLeft(2, '0')}:${endTime.minute.toString().padLeft(2, '0')}';
    } catch (e) {
      return '00:00';
    }
  }

  String _formatRegistrationDeadline(String? dateTime) {
    if (dateTime == null) return 'Không có hạn chót';
    try {
      final date = DateTime.parse(dateTime);
      return '${date.day.toString().padLeft(2, '0')}/${date.month.toString().padLeft(2, '0')}/${date.year} ${date.hour.toString().padLeft(2, '0')}:${date.minute.toString().padLeft(2, '0')}';
    } catch (e) {
      return 'Chưa xác định';
    }
  }

  List<Map<String, dynamic>> get _filteredActivities {
    if (_selectedFilter == 'Tất cả') {
      return _availableActivities;
    } else if (_selectedFilter == 'DRL') {
      return _availableActivities
          .where((activity) => activity['type'] == 'DRL')
          .toList();
    } else if (_selectedFilter == 'CTXH') {
      return _availableActivities
          .where((activity) => activity['type'] == 'CTXH')
          .toList();
    }
    return _availableActivities;
  }

  void _showErrorMessage(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: Colors.red,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      ),
    );
  }

  void _showSuccessMessage(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Row(
          children: [
            Icon(Icons.check_circle, color: Colors.white),
            SizedBox(width: 8),
            Expanded(child: Text(message)),
          ],
        ),
        backgroundColor: Color(0xFF81C784),
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      ),
    );
  }

  Widget _buildFilterButton(String filter) {
    final isSelected = _selectedFilter == filter;
    return InkWell(
      onTap: () {
        setState(() {
          _selectedFilter = filter;
        });
      },
      child: Container(
        padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        decoration: BoxDecoration(
          color: isSelected ? Color(0xFF1E5A96) : Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(
            color: isSelected ? Color(0xFF1E5A96) : Colors.grey[300]!,
          ),
        ),
        child: Text(
          filter,
          style: TextStyle(
            color: isSelected ? Colors.white : Colors.grey[700],
            fontWeight: isSelected ? FontWeight.w600 : FontWeight.normal,
            fontSize: 14,
          ),
        ),
      ),
    );
  }

  void _showActivityDetail(Map<String, dynamic> activity) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => _buildActivityDetailSheet(activity),
    );
  }

  void _handleRegister(Map<String, dynamic> activity) {
    final diaDiem = activity['dia_diem_detail'] as Map<String, dynamic>? ?? {};
    final giaTien = diaDiem['gia_tien'] as int?;
    final isCtxhWithFee =
        activity['type'] == 'CTXH' && giaTien != null && giaTien > 0;

    showDialog(
      context: context,
      builder:
          (context) => AlertDialog(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(15),
            ),
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
                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
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
                      Icon(
                        Icons.info_outline,
                        color: Colors.orange[700],
                        size: 20,
                      ),
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
                if (isCtxhWithFee)
                  Padding(
                    padding: const EdgeInsets.only(top: 12),
                    child: Container(
                      padding: EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.red[50],
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(color: Colors.red[300]!),
                      ),
                      child: Row(
                        children: [
                          Icon(
                            Icons.warning_amber,
                            color: Colors.red[700],
                            size: 20,
                          ),
                          SizedBox(width: 8),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Yêu cầu thanh toán',
                                  style: TextStyle(
                                    fontSize: 12,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.red[700],
                                  ),
                                ),
                                SizedBox(height: 4),
                                Text(
                                  '${giaTien.toString().replaceAllMapped(RegExp(r'\B(?=(\d{3})+(?!\d))'), (Match m) => ',')}đ',
                                  style: TextStyle(
                                    fontSize: 14,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.red[900],
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
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
                onPressed: () => _confirmRegister(activity),
                style: ElevatedButton.styleFrom(
                  backgroundColor: isCtxhWithFee ? Colors.orange : Color(0xFF2E5077),
                ),
                child: Text(
                  isCtxhWithFee ? 'Đăng ký & Thanh toán' : 'Xác nhận',
                  style: TextStyle(color: Colors.white),
                ),
              ),
            ],
          ),
    );
  }

  Future<void> _confirmRegister(Map<String, dynamic> activity) async {
    Navigator.pop(context); // Close dialog

    try {
      String endpoint;
      if (activity['type'] == 'DRL') {
        endpoint = '/activities/drl/${activity['id']}/register';
      } else {
        endpoint = '/activities/ctxh/${activity['id']}/register';
      }

      print('Registering for activity: ${activity['id']} via $endpoint');

      final response = await ApiService.post(
        endpoint,
        body: {},
        needAuth: true,
      );

      print('Registration response: ${response.statusCode} - ${response.body}');

      if (response.statusCode == 200) {
        final data = ApiService.parseResponse(response);

        if (data['success'] == true) {
          setState(() {
            activity['isRegistered'] = true;
          });

          // Check if payment is required (from API response)
          final responseData = data['data'] as Map<String, dynamic>? ?? {};
          final canPay = responseData['can_pay'] ?? false;
          final paymentAmount = responseData['amount'] ?? 0;

          if (canPay && paymentAmount > 0) {
            // Show payment required dialog with payment amount from API
            Navigator.pop(context); // Close bottom sheet
            _showPaymentRequiredDialog(activity, paymentAmount);
          } else {
            Navigator.pop(context); // Close bottom sheet
            _showSuccessMessage('Đăng ký hoạt động thành công!');
            _loadActivities();
          }
        } else {
          _showErrorMessage(data['message'] ?? 'Có lỗi xảy ra khi đăng ký');
        }
      } else {
        final errorData = ApiService.parseResponse(response);
        _showErrorMessage(
          errorData['message'] ?? 'Không thể đăng ký hoạt động',
        );
      }
    } catch (e) {
      _showErrorMessage('Có lỗi xảy ra: $e');
    }
  }

  void _handleCancelRegistration(Map<String, dynamic> activity) {
    showDialog(
      context: context,
      builder:
          (context) => AlertDialog(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(15),
            ),
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
                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                ),
              ],
            ),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(context),
                child: Text('Quay lại', style: TextStyle(color: Colors.grey)),
              ),
              ElevatedButton(
                onPressed: () => _confirmCancelRegistration(activity),
                style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
                child: Text(
                  'Hủy đăng ký',
                  style: TextStyle(color: Colors.white),
                ),
              ),
            ],
          ),
    );
  }

  Future<void> _confirmCancelRegistration(Map<String, dynamic> activity) async {
    Navigator.pop(context); // Close dialog

    try {
      String endpoint;
      if (activity['type'] == 'DRL') {
        endpoint = '/activities/drl/${activity['id']}/unregister';
      } else {
        endpoint = '/activities/ctxh/${activity['id']}/unregister';
      }

      final response = await ApiService.delete(endpoint, needAuth: true);

      if (response.statusCode == 200) {
        final data = ApiService.parseResponse(response);

        if (data['success'] == true) {
          setState(() {
            activity['isRegistered'] = false;
          });

          Navigator.pop(context); // Close bottom sheet
          _showSuccessMessage('Đã hủy đăng ký hoạt động thành công!');

          // Refresh activities to get updated data
          _loadActivities();
        } else {
          _showErrorMessage(data['message'] ?? 'Có lỗi xảy ra khi hủy đăng ký');
        }
      } else {
        final errorData = ApiService.parseResponse(response);
        _showErrorMessage(
          errorData['message'] ?? 'Không thể hủy đăng ký hoạt động',
        );
      }
    } catch (e) {
      _showErrorMessage('Có lỗi xảy ra: $e');
    }
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
                  const SizedBox(width: 34),
                ],
              ),
            ),

            // Filter buttons
            Container(
              padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              child: Row(
                children: [
                  _buildFilterButton('Tất cả'),
                  SizedBox(width: 8),
                  _buildFilterButton('DRL'),
                  SizedBox(width: 8),
                  _buildFilterButton('CTXH'),
                ],
              ),
            ),

            // Danh sách hoạt động
            Expanded(
              child:
                  _isLoading
                      ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            CircularProgressIndicator(
                              valueColor: AlwaysStoppedAnimation<Color>(
                                Color(0xFF1E5A96),
                              ),
                            ),
                            SizedBox(height: 16),
                            Text(
                              'Đang tải hoạt động...',
                              style: TextStyle(
                                color: Colors.grey[600],
                                fontSize: 14,
                              ),
                            ),
                          ],
                        ),
                      )
                      : _filteredActivities.isEmpty
                      ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(
                              Icons.event_busy,
                              size: 64,
                              color: Colors.grey[400],
                            ),
                            SizedBox(height: 16),
                            Text(
                              'Không có hoạt động nào',
                              style: TextStyle(
                                fontSize: 16,
                                color: Colors.grey[600],
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                            SizedBox(height: 8),
                            Text(
                              'Hiện tại chưa có hoạt động nào để đăng ký',
                              style: TextStyle(
                                fontSize: 14,
                                color: Colors.grey[500],
                              ),
                            ),
                          ],
                        ),
                      )
                      : RefreshIndicator(
                        onRefresh: _loadActivities,
                        child: ListView.builder(
                          padding: EdgeInsets.all(16),
                          itemCount: _filteredActivities.length,
                          itemBuilder: (context, index) {
                            return _buildActivityCard(
                              _filteredActivities[index],
                            );
                          },
                        ),
                      ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildActivityCard(Map<String, dynamic> activity) {
    final isRegistered = activity['isRegistered'] as bool;
    final progress =
        int.parse(activity['participants']) /
        int.parse(activity['maxParticipants']);

    return Container(
      margin: EdgeInsets.only(bottom: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border:
            isRegistered
                ? Border.all(color: Color(0xFF81C784), width: 2)
                : null,
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
                            padding: EdgeInsets.symmetric(
                              horizontal: 8,
                              vertical: 4,
                            ),
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
                        padding: EdgeInsets.symmetric(
                          horizontal: 10,
                          vertical: 6,
                        ),
                        decoration: BoxDecoration(
                          color: Color(0xFF81C784),
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Row(
                          children: [
                            Icon(
                              Icons.check_circle,
                              color: Colors.white,
                              size: 14,
                            ),
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
                      value:
                          '${activity['startTime']} - ${activity['endTime']}',
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
                                Icon(
                                  Icons.people,
                                  size: 18,
                                  color: Colors.grey[600],
                                ),
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
                        padding: EdgeInsets.symmetric(
                          horizontal: 12,
                          vertical: 6,
                        ),
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
                      onPressed:
                          isRegistered
                              ? () => _handleCancelRegistration(activity)
                              : () => _handleRegister(activity),
                      style: ElevatedButton.styleFrom(
                        backgroundColor:
                            isRegistered ? Colors.red : Color(0xFF2E5077),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        elevation: 0,
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(
                            isRegistered ? Icons.cancel : Icons.check_circle,
                            size: 20,
                          ),
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

  void _showPaymentRequiredDialog(
      Map<String, dynamic> activity, dynamic giaTienRaw) {
    // Handle amount - can be int or string
    int giaTien = 0;
    if (giaTienRaw != null) {
      if (giaTienRaw is int) {
        giaTien = giaTienRaw;
      } else if (giaTienRaw is String) {
        giaTien = int.tryParse(giaTienRaw) ?? 0;
      } else {
        giaTien = int.tryParse(giaTienRaw.toString()) ?? 0;
      }
    }

    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => AlertDialog(
        title: Row(
          children: [
            Icon(Icons.warning_amber, color: Colors.orange, size: 28),
            SizedBox(width: 12),
            Text('Yêu cầu thanh toán'),
          ],
        ),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Bạn đã đăng ký hoạt động thành công!',
              style: TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.w500,
              ),
            ),
            SizedBox(height: 16),
            Container(
              padding: EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.orange[50],
                borderRadius: BorderRadius.circular(8),
                border: Border.all(color: Colors.orange[300]!),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Số tiền cần thanh toán:',
                    style: TextStyle(
                      fontSize: 12,
                      color: Colors.grey[700],
                    ),
                  ),
                  SizedBox(height: 8),
                  Text(
                    '${giaTien.toString().replaceAllMapped(RegExp(r'\B(?=(\d{3})+(?!\d))'), (Match m) => ',')}đ',
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      color: Colors.orange[900],
                    ),
                  ),
                ],
              ),
            ),
            SizedBox(height: 12),
            Container(
              padding: EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.blue[50],
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                children: [
                  Icon(Icons.info_outline, color: Colors.blue[700], size: 20),
                  SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      'Vui lòng thanh toán để xác nhận đăng ký',
                      style: TextStyle(
                        fontSize: 12,
                        color: Colors.blue[700],
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
            onPressed: () {
              Navigator.pop(context);
              _showSuccessMessage(
                'Đăng ký thành công! Vui lòng thanh toán để hoàn tất',
              );
              _loadActivities();
            },
            child: Text('Xong'),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context);
              _showSuccessMessage(
                'Đăng ký thành công! Vui lòng thanh toán để hoàn tất',
              );
              _loadActivities();
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.orange,
            ),
            child: Text('Đã hiểu'),
          ),
        ],
      ),
    );
  }

  Widget _buildDetailRow(
    IconData icon,
    String label,
    String value,
    Color color,
  ) {
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
                style: TextStyle(fontSize: 12, color: Colors.grey[600]),
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
