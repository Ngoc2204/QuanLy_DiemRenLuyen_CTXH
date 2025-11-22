import 'package:flutter/material.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:fluttertoast/fluttertoast.dart';
import '../services/activity_service.dart';

class ActivityScheduleScreen extends StatefulWidget {
  const ActivityScheduleScreen({super.key});

  @override
  State<ActivityScheduleScreen> createState() => _ActivityScheduleScreenState();
}

class _ActivityScheduleScreenState extends State<ActivityScheduleScreen> {
  DateTime _selectedDate = DateTime.now();
  bool _isWeekView = false;
  bool _isLoading = true;
  String? _errorMessage;

  // Dữ liệu hoạt động từ API
  List<Map<String, dynamic>> _drlActivities = [];
  List<Map<String, dynamic>> _ctxhActivities = [];
  String _weekStart = '';
  String _weekEnd = '';

  @override
  void initState() {
    super.initState();
    _loadWeeklySchedule();
  }

  Future<void> _loadWeeklySchedule() async {
    try {
      setState(() {
        _isLoading = true;
        _errorMessage = null;
      });

      final result = await ActivityService.getWeeklySchedule();
      if (result['success']) {
        final data = result['data'];
        
        // Debug logging - show full response
        print('=== Weekly Schedule Response ===');
        print('Response Keys: ${data.keys.toList()}');
        print('DRL Activities count: ${data['drl_activities']?.length ?? 0}');
        print('CTXH Activities count: ${data['ctxh_activities']?.length ?? 0}');
        print('All Activities count: ${data['all_activities']?.length ?? 0}');
        
        print('\n--- DRL Activities ---');
        if (data['drl_activities'] != null && (data['drl_activities'] as List).isNotEmpty) {
          for (var activity in data['drl_activities']) {
            print('DRL: ${activity['ten']} (${activity['ngay_to_chuc']}) - Points: ${activity['diem_rl']}');
          }
        } else {
          print('DRL: Empty or null');
        }
        
        print('\n--- CTXH Activities ---');
        if (data['ctxh_activities'] != null && (data['ctxh_activities'] as List).isNotEmpty) {
          for (var activity in data['ctxh_activities']) {
            print('CTXH: ${activity['ten']} (${activity['ngay_to_chuc']}) - Points: ${activity['diem_ctxh']}');
          }
        } else {
          print('CTXH: Empty or null');
        }
        
        setState(() {
          // Use individual arrays if available
          if (data['drl_activities'] != null) {
            _drlActivities = List<Map<String, dynamic>>.from(data['drl_activities']);
            print('✓ Loaded DRL: ${_drlActivities.length}');
          } else {
            _drlActivities = [];
          }
          
          if (data['ctxh_activities'] != null) {
            _ctxhActivities = List<Map<String, dynamic>>.from(data['ctxh_activities']);
            print('✓ Loaded CTXH: ${_ctxhActivities.length}');
          } else {
            _ctxhActivities = [];
          }
          
          // Fallback to merged array if needed
          if (_drlActivities.isEmpty && _ctxhActivities.isEmpty && data['all_activities'] != null) {
            print('Using fallback: splitting all_activities');
            final allActs = List<Map<String, dynamic>>.from(data['all_activities']);
            _drlActivities = allActs.where((a) => a['type'] == 'DRL').toList();
            _ctxhActivities = allActs.where((a) => a['type'] == 'CTXH').toList();
          }
          
          _weekStart = data['week_start'] ?? '';
          _weekEnd = data['week_end'] ?? '';
          _isLoading = false;
        });
      } else {
        setState(() {
          _errorMessage = result['message'] ?? 'Không thể tải lịch hoạt động';
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

  // Lấy hoạt động theo ngày được chọn
  List<Map<String, dynamic>> get _activitiesForSelectedDate {
    List<Map<String, dynamic>> activities = [];

    // Add DRL activities for selected date
    for (var activity in _drlActivities) {
      final activityDate = DateTime.tryParse(activity['ngay_to_chuc'] ?? '');
      if (activityDate != null &&
          activityDate.year == _selectedDate.year &&
          activityDate.month == _selectedDate.month &&
          activityDate.day == _selectedDate.day) {
        activities.add({...activity, 'type': 'DRL'});
      }
    }

    // Add CTXH activities for selected date
    for (var activity in _ctxhActivities) {
      final activityDate = DateTime.tryParse(activity['ngay_to_chuc'] ?? '');
      if (activityDate != null &&
          activityDate.year == _selectedDate.year &&
          activityDate.month == _selectedDate.month &&
          activityDate.day == _selectedDate.day) {
        activities.add({...activity, 'type': 'CTXH'});
      }
    }

    print('_activitiesForSelectedDate: ${_selectedDate.toString().split(' ')[0]}, Total: ${activities.length}, DRL: ${activities.where((a) => a['type'] == 'DRL').length}, CTXH: ${activities.where((a) => a['type'] == 'CTXH').length}');
    return activities;
  }

  // Lấy hoạt động theo tuần được chọn
  List<Map<String, dynamic>> get _activitiesForSelectedWeek {
    List<Map<String, dynamic>> activities = [];
    final startOfWeek = _selectedDate.subtract(
      Duration(days: _selectedDate.weekday - 1),
    );
    final endOfWeek = startOfWeek.add(const Duration(days: 6));

    // Add DRL activities for selected week
    for (var activity in _drlActivities) {
      final activityDate = DateTime.tryParse(activity['ngay_to_chuc'] ?? '');
      if (activityDate != null &&
          activityDate.isAfter(startOfWeek.subtract(const Duration(days: 1))) &&
          activityDate.isBefore(endOfWeek.add(const Duration(days: 1)))) {
        activities.add({...activity, 'type': 'DRL'});
      }
    }

    // Add CTXH activities for selected week
    for (var activity in _ctxhActivities) {
      final activityDate = DateTime.tryParse(activity['ngay_to_chuc'] ?? '');
      if (activityDate != null &&
          activityDate.isAfter(startOfWeek.subtract(const Duration(days: 1))) &&
          activityDate.isBefore(endOfWeek.add(const Duration(days: 1)))) {
        activities.add({...activity, 'type': 'CTXH'});
      }
    }

    print('_activitiesForSelectedWeek: Week of ${startOfWeek.toString().split(' ')[0]}, Total: ${activities.length}, DRL: ${activities.where((a) => a['type'] == 'DRL').length}, CTXH: ${activities.where((a) => a['type'] == 'CTXH').length}');
    return activities;
  }

  String _formatDate(DateTime date) {
    return '${date.day}/${date.month}/${date.year}';
  }

  String _formatTime(String? dateTimeString) {
    if (dateTimeString == null) return 'N/A';
    final dateTime = DateTime.tryParse(dateTimeString);
    if (dateTime == null) return 'N/A';
    return '${dateTime.hour.toString().padLeft(2, '0')}:${dateTime.minute.toString().padLeft(2, '0')} - ${_formatDate(dateTime)}';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F5F5),
      appBar: AppBar(
        backgroundColor: const Color(0xFF1E3A8A),
        centerTitle: true,
        title: const Text(
          'Lịch hoạt động',
          style: TextStyle(
            color: Colors.white,
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
        ),
        automaticallyImplyLeading: false,
        actions: [
          IconButton(
            icon: Icon(
              _isWeekView ? Icons.calendar_today : Icons.view_week,
              color: Colors.white,
            ),
            onPressed: () {
              setState(() {
                _isWeekView = !_isWeekView;
              });
            },
          ),
          IconButton(
            icon: const Icon(Icons.refresh, color: Colors.white),
            onPressed: _loadWeeklySchedule,
          ),
        ],
      ),
      body:
          _isLoading
              ? const Center(child: CircularProgressIndicator())
              : _errorMessage != null
              ? _buildErrorState()
              : Column(
                children: [
                  _buildCalendarHeader(),
                  Expanded(
                    child: _isWeekView ? _buildWeekView() : _buildDayView(),
                  ),
                ],
              ),
    );
  }

  Widget _buildErrorState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.error_outline, size: 64, color: Colors.red[300]),
          const SizedBox(height: 16),
          Text(
            _errorMessage!,
            style: const TextStyle(fontSize: 16, color: Colors.red),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 16),
          ElevatedButton(
            onPressed: _loadWeeklySchedule,
            child: const Text('Thử lại'),
          ),
        ],
      ),
    );
  }

  Widget _buildCalendarHeader() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.1),
            spreadRadius: 1,
            blurRadius: 3,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              IconButton(
                icon: const Icon(Icons.chevron_left),
                onPressed: () {
                  setState(() {
                    _selectedDate =
                        _isWeekView
                            ? _selectedDate.subtract(const Duration(days: 7))
                            : _selectedDate.subtract(const Duration(days: 1));
                  });
                },
              ),
              Text(
                _isWeekView
                    ? 'Tuần ${_getWeekNumber(_selectedDate)} - ${_selectedDate.month}/${_selectedDate.year}'
                    : _formatDate(_selectedDate),
                style: const TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              IconButton(
                icon: const Icon(Icons.chevron_right),
                onPressed: () {
                  setState(() {
                    _selectedDate =
                        _isWeekView
                            ? _selectedDate.add(const Duration(days: 7))
                            : _selectedDate.add(const Duration(days: 1));
                  });
                },
              ),
            ],
          ),
          if (_weekStart.isNotEmpty && _weekEnd.isNotEmpty)
            Text(
              'Dữ liệu từ $_weekStart đến $_weekEnd',
              style: TextStyle(fontSize: 12, color: Colors.grey[600]),
            ),
        ],
      ),
    );
  }

  int _getWeekNumber(DateTime date) {
    final firstDayOfYear = DateTime(date.year, 1, 1);
    final daysSinceFirstDay = date.difference(firstDayOfYear).inDays;
    return (daysSinceFirstDay / 7).ceil() + 1;
  }

  Widget _buildDayView() {
    final activities = _activitiesForSelectedDate;

    if (activities.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.event_busy, size: 64, color: Colors.grey[400]),
            const SizedBox(height: 16),
            Text(
              'Không có hoạt động nào\ntrong ngày ${_formatDate(_selectedDate)}',
              style: TextStyle(fontSize: 16, color: Colors.grey[600]),
              textAlign: TextAlign.center,
            ),
          ],
        ),
      );
    }

    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: activities.length,
      itemBuilder: (context, index) {
        return _buildActivityCard(activities[index]);
      },
    );
  }

  Widget _buildWeekView() {
    final activities = _activitiesForSelectedWeek;

    if (activities.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.event_busy, size: 64, color: Colors.grey[400]),
            const SizedBox(height: 16),
            Text(
              'Không có hoạt động nào\ntrong tuần này',
              style: TextStyle(fontSize: 16, color: Colors.grey[600]),
              textAlign: TextAlign.center,
            ),
          ],
        ),
      );
    }

    // Nhóm hoạt động theo ngày
    Map<String, List<Map<String, dynamic>>> groupedActivities = {};
    for (var activity in activities) {
      final dateTime = DateTime.tryParse(activity['ngay_to_chuc'] ?? '');
      if (dateTime != null) {
        final date = _formatDate(dateTime);
        if (groupedActivities[date] == null) {
          groupedActivities[date] = [];
        }
        groupedActivities[date]!.add(activity);
      }
    }

    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: groupedActivities.keys.length,
      itemBuilder: (context, index) {
        final date = groupedActivities.keys.elementAt(index);
        final dayActivities = groupedActivities[date]!;

        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 12),
              margin: const EdgeInsets.only(bottom: 8),
              decoration: BoxDecoration(
                color: const Color(0xFF1E3A8A),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Text(
                date,
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
            ...dayActivities.map((activity) => _buildActivityCard(activity)),
            const SizedBox(height: 16),
          ],
        );
      },
    );
  }

  Widget _buildActivityCard(Map<String, dynamic> activity) {
    // Determine if this is DRL or CTXH activity
    final isDRL = activity['type'] == 'DRL';
    final categoryColor =
        isDRL ? const Color(0xFFFFB74D) : const Color(0xFFE57373);
    final categoryText = isDRL ? 'DRL' : 'CTXH';
    final points = isDRL ? activity['diem_rl'] : activity['diem_ctxh'];

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.1),
            spreadRadius: 1,
            blurRadius: 3,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: categoryColor.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Icon(
                        isDRL ? Icons.stars : Icons.favorite,
                        color: categoryColor,
                        size: 20,
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            activity['ten'] ?? 'Hoạt động',
                            style: const TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: Color(0xFF2E5077),
                            ),
                          ),
                          const SizedBox(height: 4),
                          Row(
                            children: [
                              Container(
                                padding: const EdgeInsets.symmetric(
                                  horizontal: 8,
                                  vertical: 2,
                                ),
                                decoration: BoxDecoration(
                                  color: categoryColor,
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                child: Text(
                                  categoryText,
                                  style: const TextStyle(
                                    color: Colors.white,
                                    fontSize: 12,
                                    fontWeight: FontWeight.w500,
                                  ),
                                ),
                              ),
                              const SizedBox(width: 8),
                              Container(
                                padding: const EdgeInsets.symmetric(
                                  horizontal: 8,
                                  vertical: 2,
                                ),
                                decoration: BoxDecoration(
                                  color: Colors.green[100],
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                child: Text(
                                  '+$points điểm',
                                  style: TextStyle(
                                    color: Colors.green[700],
                                    fontSize: 12,
                                    fontWeight: FontWeight.w600,
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 8,
                        vertical: 4,
                      ),
                      decoration: BoxDecoration(
                        color: Colors.green.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: Colors.green, width: 1),
                      ),
                      child: const Text(
                        'Đã duyệt',
                        style: TextStyle(
                          color: Colors.green,
                          fontSize: 12,
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Icon(Icons.location_on, size: 16, color: Colors.grey[600]),
                    const SizedBox(width: 4),
                    Expanded(
                      child: Text(
                        activity['dia_diem'] ?? 'Chưa xác định',
                        style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 4),
                Row(
                  children: [
                    Icon(Icons.access_time, size: 16, color: Colors.grey[600]),
                    const SizedBox(width: 4),
                    Text(
                      _formatTime(activity['ngay_to_chuc']),
                      style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                // Attendance button
                Row(
                  children: [
                    Expanded(
                      child: ElevatedButton.icon(
                        onPressed: () => _showAttendanceOptions(activity),
                        icon: const Icon(Icons.qr_code_scanner, size: 18),
                        label: const Text('Điểm danh'),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF1E3A8A),
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 8),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
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

  // Attendance methods
  void _showAttendanceOptions(Map<String, dynamic> activity) {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder:
          (context) => Container(
            padding: const EdgeInsets.all(20),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  'Điểm danh: ${activity['ten']}',
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 20),
                Row(
                  children: [
                    Expanded(
                      child: ElevatedButton.icon(
                        onPressed: () {
                          Navigator.pop(context);
                          _scanQRCode(activity, 'checkin');
                        },
                        icon: const Icon(Icons.login),
                        label: const Text('Check-in'),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF4CAF50),
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 12),
                        ),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: ElevatedButton.icon(
                        onPressed: () {
                          Navigator.pop(context);
                          _scanQRCode(activity, 'checkout');
                        },
                        icon: const Icon(Icons.logout),
                        label: const Text('Check-out'),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFFFF5722),
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 12),
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 10),
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Hủy'),
                ),
              ],
            ),
          ),
    );
  }

  Future<void> _scanQRCode(Map<String, dynamic> activity, String type) async {
    // Check camera permission
    final permission = await Permission.camera.request();
    if (permission != PermissionStatus.granted) {
      if (mounted) {
        Fluttertoast.showToast(
          msg: "Cần cấp quyền camera để quét mã QR",
          toastLength: Toast.LENGTH_LONG,
        );
      }
      return;
    }

    if (mounted) {
      final result = await Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => QRScannerScreen(activity: activity, type: type),
        ),
      );

      if (result != null) {
        _handleQRResult(result, activity, type);
      }
    }
  }

  void _handleQRResult(
    String qrData,
    Map<String, dynamic> activity,
    String type,
  ) async {
    print('[DEBUG] QR scanned: $qrData');
    print('[DEBUG] Activity: $activity');
    print('[DEBUG] Type: $type');
    
    // Show loading dialog
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => Dialog(
        child: Padding(
          padding: const EdgeInsets.all(20.0),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              CircularProgressIndicator(),
              SizedBox(height: 16),
              Text('Đang xử lý điểm danh...'),
            ],
          ),
        ),
      ),
    );

    try {
      // Gọi API điểm danh
      final result = await ActivityService.scanQRCode(qrData);

      print('[DEBUG] API Response: $result');

      if (!mounted) return;

      // Đóng loading dialog
      if (Navigator.canPop(context)) {
        Navigator.pop(context);
      }

      if (result['success']) {
        // Điểm danh thành công
        Fluttertoast.showToast(
          msg: result['message'] ?? '${type == 'checkin' ? 'Check-in' : 'Check-out'} thành công',
          toastLength: Toast.LENGTH_SHORT,
          backgroundColor: Colors.green,
        );
        
        // Reload the activity list to show updated check-in status
        if (mounted) {
          _loadWeeklySchedule();
        }
      } else {
        // Điểm danh thất bại
        Fluttertoast.showToast(
          msg: result['message'] ?? 'Điểm danh thất bại',
          toastLength: Toast.LENGTH_SHORT,
          backgroundColor: Colors.red,
        );
      }
    } catch (e) {
      print('[DEBUG] Exception: $e');
      if (!mounted) return;

      // Đóng loading dialog
      if (Navigator.canPop(context)) {
        Navigator.pop(context);
      }
      
      Fluttertoast.showToast(
        msg: 'Lỗi: ${e.toString()}',
        toastLength: Toast.LENGTH_SHORT,
        backgroundColor: Colors.red,
      );
    }
  }
}

class QRScannerScreen extends StatefulWidget {
  final Map<String, dynamic> activity;
  final String type;

  const QRScannerScreen({
    super.key,
    required this.activity,
    required this.type,
  });

  @override
  State<QRScannerScreen> createState() => _QRScannerScreenState();
}

class _QRScannerScreenState extends State<QRScannerScreen> {
  MobileScannerController? _controller;
  bool _isFlashOn = false;

  @override
  void initState() {
    super.initState();
    _controller = MobileScannerController();
  }

  @override
  void dispose() {
    _controller?.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        backgroundColor: Colors.black,
        title: Text(
          'Quét mã QR ${widget.type == 'checkin' ? 'check-in' : 'check-out'}',
          style: const TextStyle(color: Colors.white),
        ),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.white),
          onPressed: () => Navigator.pop(context),
        ),
        actions: [
          IconButton(
            icon: Icon(
              _isFlashOn ? Icons.flash_on : Icons.flash_off,
              color: Colors.white,
            ),
            onPressed: () {
              setState(() {
                _isFlashOn = !_isFlashOn;
              });
              _controller?.toggleTorch();
            },
          ),
        ],
      ),
      body: Stack(
        children: [
          if (_controller != null)
            MobileScanner(
              controller: _controller,
              onDetect: (BarcodeCapture barcodeCapture) {
                final barcode = barcodeCapture.barcodes.first;
                if (barcode.rawValue != null) {
                  Navigator.pop(context, barcode.rawValue);
                }
              },
            ),
          // Custom overlay
          CustomPaint(
            painter: QRScannerOverlayShape(),
            size: MediaQuery.of(context).size,
          ),
          // Info text
          Positioned(
            bottom: 100,
            left: 20,
            right: 20,
            child: Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.black.withOpacity(0.7),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Text(
                'Đưa mã QR vào trong khung để ${widget.type == 'checkin' ? 'check-in' : 'check-out'}\n${widget.activity['ten']}',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 16,
                  fontWeight: FontWeight.w500,
                ),
                textAlign: TextAlign.center,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class QRScannerOverlayShape extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final paint =
        Paint()
          ..color = const Color(0xFF1E3A8A)
          ..strokeWidth = 4
          ..style = PaintingStyle.stroke;

    final centerX = size.width / 2;
    final centerY = size.height / 2;
    final scanAreaSize = size.width * 0.7;
    final halfSize = scanAreaSize / 2;

    // Draw corner borders
    const cornerLength = 30.0;

    // Top-left corner
    canvas.drawLine(
      Offset(centerX - halfSize, centerY - halfSize),
      Offset(centerX - halfSize + cornerLength, centerY - halfSize),
      paint,
    );
    canvas.drawLine(
      Offset(centerX - halfSize, centerY - halfSize),
      Offset(centerX - halfSize, centerY - halfSize + cornerLength),
      paint,
    );

    // Top-right corner
    canvas.drawLine(
      Offset(centerX + halfSize, centerY - halfSize),
      Offset(centerX + halfSize - cornerLength, centerY - halfSize),
      paint,
    );
    canvas.drawLine(
      Offset(centerX + halfSize, centerY - halfSize),
      Offset(centerX + halfSize, centerY - halfSize + cornerLength),
      paint,
    );

    // Bottom-left corner
    canvas.drawLine(
      Offset(centerX - halfSize, centerY + halfSize),
      Offset(centerX - halfSize + cornerLength, centerY + halfSize),
      paint,
    );
    canvas.drawLine(
      Offset(centerX - halfSize, centerY + halfSize),
      Offset(centerX - halfSize, centerY + halfSize - cornerLength),
      paint,
    );

    // Bottom-right corner
    canvas.drawLine(
      Offset(centerX + halfSize, centerY + halfSize),
      Offset(centerX + halfSize - cornerLength, centerY + halfSize),
      paint,
    );
    canvas.drawLine(
      Offset(centerX + halfSize, centerY + halfSize),
      Offset(centerX + halfSize, centerY + halfSize - cornerLength),
      paint,
    );
  }

  @override
  bool shouldRepaint(CustomPainter oldDelegate) => false;
}
