import 'package:app_ql_sv/screens/register_activity_screen.dart';
import 'package:flutter/material.dart';
import '../widgets/custom_bottom_nav.dart';
import '../services/auth_service.dart';
import '../services/activity_service.dart';
import 'activity_schedule_screen.dart';
import 'contact_screen.dart';
import 'diem_ren_luyen_page.dart';
import 'diem_ctxh_page.dart';
import 'profile_screen.dart';
import 'login_screen.dart';
import 'my_registrations_screen.dart';
import 'recommendations_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _selectedIndex = 0;

  final List<Widget> _screens = [
    const HomeView(),
    const ActivityScheduleScreen(),
    const ContactScreen(),
    const StudentProfileScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: IndexedStack(index: _selectedIndex, children: _screens),
      bottomNavigationBar: CustomBottomNav(
        currentIndex: _selectedIndex,
        onTap: (index) => setState(() => _selectedIndex = index),
      ),
    );
  }
}

class HomeView extends StatefulWidget {
  const HomeView({super.key});

  @override
  State<HomeView> createState() => _HomeViewState();
}

class _HomeViewState extends State<HomeView> {
  Map<String, dynamic>? _userData;
  Map<String, dynamic>? _dashboardData;
  List<dynamic> _todayActivities = [];
  List<dynamic> _recommendations = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    try {
      // Load user data
      final userResult = await AuthService.getCurrentUser();
      if (userResult['success']) {
        setState(() {
          _userData = userResult['user'];
        });
      }

      // Load dashboard data
      final dashboardResult = await ActivityService.getDashboardData();
      if (dashboardResult['success']) {
        setState(() {
          _dashboardData = dashboardResult['data'];
        });
      }

      // Load recommendations
      final recsResult = await ActivityService.getRecommendations();
      print('[HomeScreen] Recommendations result: $recsResult');
      if (recsResult['success']) {
        final rawRecs = recsResult['data'] ?? [];
        print('[HomeScreen] Raw recommendations: $rawRecs');
        
        // Map và transform recommendations data
        final mappedRecs = List<Map<String, dynamic>>.from(
          (rawRecs as List).map((rec) {
            final activity = rec['activity'] as Map<String, dynamic>? ?? {};
            return {
              'id': rec['id'] ?? '',
              'recommendation_score': (rec['recommendation_score'] ?? 0).toDouble(),
              'recommendation_reason': rec['recommendation_reason'] ?? 'Hoạt động phù hợp',
              'activity_type': rec['activity_type'] ?? activity['type'] ?? 'DRL',
              'activity': {
                'id': activity['id'] ?? rec['MaHoatDong'] ?? '',
                'ten': activity['ten'] ?? rec['ten'] ?? 'N/A',
                'mo_ta': activity['mo_ta'] ?? '',
                'dia_diem': activity['dia_diem'] ?? 'Chưa xác định',
                'type': activity['type'] ?? rec['activity_type'] ?? 'DRL',
                'ngay_to_chuc': activity['ngay_to_chuc'] ?? '',
              }
            };
          }),
        );
        
        setState(() {
          _recommendations = mappedRecs;
          print('[HomeScreen] Loaded ${_recommendations.length} recommendations');
        });
      } else {
        print('[HomeScreen] Failed to load recommendations: ${recsResult['message']}');
        setState(() {
          _recommendations = [];
        });
      }

      // Load today's activities - use fallback if weekly schedule not available
      try {
        final weeklyResult = await ActivityService.getWeeklySchedule();
        print('Weekly result: $weeklyResult');

        if (weeklyResult['success']) {
          final today = DateTime.now();
          final todayStr =
              "${today.year}-${today.month.toString().padLeft(2, '0')}-${today.day.toString().padLeft(2, '0')}";

          final drlActivities =
              weeklyResult['data']['drl_activities'] as List? ?? [];
          final ctxhActivities =
              weeklyResult['data']['ctxh_activities'] as List? ?? [];

          print('DRL activities: $drlActivities');
          print('CTXH activities: $ctxhActivities');

          List<dynamic> todayActivities = [];

          for (var activity in drlActivities) {
            if (activity != null &&
                activity is Map<String, dynamic> &&
                activity['ngay_to_chuc']?.toString().startsWith(todayStr) ==
                    true) {
              todayActivities.add({...activity, 'type': 'DRL'});
            }
          }

          for (var activity in ctxhActivities) {
            if (activity != null &&
                activity is Map<String, dynamic> &&
                activity['ngay_to_chuc']?.toString().startsWith(todayStr) ==
                    true) {
              todayActivities.add({...activity, 'type': 'CTXH'});
            }
          }

          setState(() {
            _todayActivities = todayActivities;
          });
        } else {
          // Fallback: use empty list if weekly schedule fails
          print('Weekly schedule failed, using empty activities');
          setState(() {
            _todayActivities = [];
          });
        }
      } catch (weeklyError) {
        print('Error loading weekly schedule: $weeklyError');
        setState(() {
          _todayActivities = [];
        });
      }
    } catch (e) {
      print('Error loading data: $e');
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  Future<void> _logout() async {
    final result = await AuthService.logout();
    if (result['success']) {
      Navigator.pushAndRemoveUntil(
        context,
        MaterialPageRoute(builder: (context) => LoginScreen()),
        (route) => false,
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return Scaffold(
        backgroundColor: Colors.grey[100],
        body: Center(
          child: CircularProgressIndicator(
            valueColor: AlwaysStoppedAnimation<Color>(Color(0xFF2E5077)),
          ),
        ),
      );
    }

    return Scaffold(
      backgroundColor: Colors.grey[100],
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Header card style
              Container(
                margin: const EdgeInsets.all(16),
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: const Color(0xFF1E5A96),
                  borderRadius: BorderRadius.circular(24),
                  boxShadow: [
                    BoxShadow(
                      color: Color(0xFF2E5077).withOpacity(0.3),
                      blurRadius: 12,
                      offset: Offset(0, 6),
                    ),
                  ],
                ),
                child: Row(
                  children: [
                    Container(
                      padding: EdgeInsets.all(3),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        shape: BoxShape.circle,
                      ),
                      child: CircleAvatar(
                        radius: 28,
                        backgroundColor: Colors.grey[300],
                        child: Icon(
                          Icons.person,
                          color: Color(0xFF2E5077),
                          size: 32,
                        ),
                      ),
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Xin chào,',
                            style: TextStyle(
                              color: Colors.white.withOpacity(0.85),
                              fontSize: 13,
                            ),
                          ),
                          Text(
                            _userData?['HoTen'] ?? 'Người dùng',
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          SizedBox(height: 2),
                          Text(
                            'MSSV: ${_userData?['MaSV'] ?? 'N/A'}',
                            style: TextStyle(
                              color: Colors.white.withOpacity(0.85),
                              fontSize: 13,
                            ),
                          ),
                          Text(
                            '${_userData?['TenKhoa'] ?? 'N/A'}, ${_userData?['MaLop'] ?? 'N/A'}',
                            style: TextStyle(
                              color: Colors.white.withOpacity(0.85),
                              fontSize: 13,
                            ),
                          ),
                        ],
                      ),
                    ),
                    GestureDetector(
                      onTap: _logout,
                      child: Container(
                        padding: EdgeInsets.all(10),
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.15),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: Icon(
                          Icons.logout,
                          color: Colors.white,
                          size: 24,
                        ),
                      ),
                    ),
                  ],
                ),
              ),

              // Stats cards
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 16),
                child: Row(
                  children: [
                    Expanded(
                      child: _buildStatCard(
                        'Điểm rèn luyện',
                        '${_dashboardData?['total_drl_score'] ?? 0}',
                        Icons.stars,
                        Color(0xFFFFB74D),
                      ),
                    ),
                    SizedBox(width: 12),
                    Expanded(
                      child: _buildStatCard(
                        'Công tác xã hội',
                        '${_dashboardData?['total_ctxh_score'] ?? 0}',
                        Icons.favorite,
                        Color(0xFFE57373),
                      ),
                    ),
                  ],
                ),
              ),

              // Hoạt động hôm nay
              Padding(
                padding: const EdgeInsets.fromLTRB(16, 24, 16, 12),
                child: Row(
                  children: [
                    Icon(Icons.event_note, color: Color(0xFF2E5077), size: 20),
                    SizedBox(width: 6),
                    Text(
                      'Hoạt động hôm nay',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: Color(0xFF2E5077),
                      ),
                    ),
                  ],
                ),
              ),

              _todayActivities.isEmpty
                  ? Container(
                    margin: const EdgeInsets.symmetric(horizontal: 16),
                    padding: const EdgeInsets.all(20),
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
                    child: Center(
                      child: Text(
                        'Không có hoạt động nào diễn ra hôm nay',
                        style: TextStyle(color: Colors.grey[600], fontSize: 14),
                      ),
                    ),
                  )
                  : Column(
                    children:
                        _todayActivities
                            .where((activity) => activity != null)
                            .map(
                              (activity) => _buildActivityCard(
                                activity as Map<String, dynamic>,
                              ),
                            )
                            .toList(),
                  ),

              // Gợi ý hoạt động - Button
              Padding(
                padding: const EdgeInsets.fromLTRB(16, 24, 16, 12),
                child: SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const RecommendationsScreen(),
                        ),
                      );
                    },
                    icon: Icon(Icons.lightbulb_outline, size: 20),
                    label: Text(
                      'Hoạt động được gợi ý',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Color(0xFF2E5077),
                      foregroundColor: Colors.white,
                      padding: EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                      elevation: 2,
                    ),
                  ),
                ),
              ),

              // Phần chức năng
              Padding(
                padding: const EdgeInsets.fromLTRB(16, 24, 16, 12),
                child: Row(
                  children: [
                    Icon(
                      Icons.dashboard_customize,
                      color: Color(0xFF2E5077),
                      size: 20,
                    ),
                    SizedBox(width: 6),
                    Text(
                      'Danh mục',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: Color(0xFF2E5077),
                      ),
                    ),
                  ],
                ),
              ),

              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: GridView.count(
                  crossAxisCount: 4,
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  crossAxisSpacing: 12,
                  mainAxisSpacing: 16,
                  childAspectRatio: 0.9,
                  children: [
                    _buildMenuItem(
                      Icons.stars_rounded,
                      'Điểm\nrèn luyện',
                      Color(0xFFFFB74D),
                      () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => DiemRenLuyenScreen(),
                          ),
                        );
                      },
                    ),
                    _buildMenuItem(
                      Icons.favorite,
                      'Công tác\nxã hội',
                      Color(0xFFE57373),
                      () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => DiemCTXHScreen(),
                          ),
                        );
                      },
                    ),
                    _buildMenuItem(
                      Icons.assignment,
                      'Quản lý\nđăng ký',
                      Color(0xFF81C784),
                      () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => const MyRegistrationsScreen(),
                          ),
                        );
                      },
                    ),
                    _buildMenuItem(
                      Icons.add_circle,
                      'Đăng ký\nhoạt động',
                      Color(0xFF64B5F6),
                      () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => RegisterActivityScreen(),
                          ),
                        );
                      },
                    ),
                  ],
                ),
              ),
              SizedBox(height: 20),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildInfoChip(IconData icon, String text) {
    return Container(
      padding: EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: Color(0xFF4DA1A9).withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 14, color: Color(0xFF2E5077)),
          SizedBox(width: 4),
          Flexible(
            child: Text(
              text,
              style: TextStyle(
                fontSize: 12,
                color: Color(0xFF2E5077),
                fontWeight: FontWeight.w500,
              ),
              overflow: TextOverflow.ellipsis,
              maxLines: 1,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatCard(
    String title,
    String value,
    IconData icon,
    Color color,
  ) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        children: [
          Icon(icon, size: 24, color: color),
          SizedBox(height: 8),
          Text(
            value,
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: Color(0xFF2E5077),
            ),
          ),
          Text(title, style: TextStyle(fontSize: 12, color: Colors.grey[600])),
        ],
      ),
    );
  }

  Widget _buildActivityCard(Map<String, dynamic> activity) {
    final typeColor =
        activity['type'] == 'DRL' ? Color(0xFFFFB74D) : Color(0xFFE57373);

    return Container(
      margin: const EdgeInsets.fromLTRB(16, 0, 16, 12),
      padding: const EdgeInsets.all(16),
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
          Row(
            children: [
              Container(
                width: 4,
                height: 50,
                decoration: BoxDecoration(
                  color: typeColor,
                  borderRadius: BorderRadius.circular(2),
                ),
              ),
              SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: Text(
                            activity['ten'] ?? '',
                            style: TextStyle(
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                              color: Color(0xFF1A1A1A),
                            ),
                          ),
                        ),
                        Container(
                          padding: EdgeInsets.symmetric(
                            horizontal: 8,
                            vertical: 4,
                          ),
                          decoration: BoxDecoration(
                            color: typeColor.withOpacity(0.2),
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: Text(
                            activity['type'] ?? '',
                            style: TextStyle(
                              fontSize: 10,
                              fontWeight: FontWeight.bold,
                              color: typeColor,
                            ),
                          ),
                        ),
                      ],
                    ),
                    SizedBox(height: 4),
                    Text(
                      activity['dia_diem'] ?? '',
                      style: TextStyle(color: Colors.grey[600], fontSize: 13),
                    ),
                  ],
                ),
              ),
            ],
          ),
          SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: _buildInfoChip(
                  Icons.access_time,
                  _formatDateTime(activity['ngay_to_chuc']?.toString() ?? ''),
                ),
              ),
              SizedBox(width: 8),
              Expanded(
                child: _buildInfoChip(
                  Icons.star,
                  activity['type'] == 'DRL'
                      ? '${activity['diem_rl'] ?? 0} điểm rèn luyện'
                      : '${activity['diem_ctxh'] ?? 0} điểm CTXH',
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildMenuItem(
    IconData icon,
    String label,
    Color color, [
    VoidCallback? onTap,
  ]) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap:
              onTap ??
              () {
                debugPrint('Chức năng "$label" đang được phát triển');
              },
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: color.withOpacity(0.15),
                  shape: BoxShape.circle,
                ),
                child: Icon(icon, size: 26, color: color),
              ),
              const SizedBox(height: 6),
              Flexible(
                child: Text(
                  label,
                  textAlign: TextAlign.center,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: TextStyle(
                    fontSize: 11,
                    color: Colors.grey[700],
                    height: 1.1,
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  String _formatDateTime(String dateTimeString) {
    if (dateTimeString.isEmpty) return '';

    try {
      final dateTime = DateTime.parse(dateTimeString);
      final day = dateTime.day.toString().padLeft(2, '0');
      final month = dateTime.month.toString().padLeft(2, '0');
      final hour = dateTime.hour.toString().padLeft(2, '0');
      final minute = dateTime.minute.toString().padLeft(2, '0');

      return '$day/$month $hour:$minute';
    } catch (e) {
      return dateTimeString.length > 16
          ? '${dateTimeString.substring(0, 16)}...'
          : dateTimeString;
    }
  }
}
