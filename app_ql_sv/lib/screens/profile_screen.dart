import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../services/api_service.dart';
import 'login_screen.dart';
import 'change_password_screen.dart';

class StudentProfileScreen extends StatefulWidget {
  const StudentProfileScreen({super.key});

  @override
  State<StudentProfileScreen> createState() => _StudentProfileScreenState();
}

class _StudentProfileScreenState extends State<StudentProfileScreen> {
  static const _storage = FlutterSecureStorage();
  Map<String, dynamic>? profileData;
  List<Map<String, dynamic>> availableInterests = [];
  bool isLoading = true;
  bool isEditing = false;

  // Form controllers
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _emailController;
  late TextEditingController _sdtController;
  List<String> selectedInterests = [];
  

  @override
  void initState() {
    super.initState();
    _initializeControllers();
    _loadProfile();
  }

  void _initializeControllers() {
    _emailController = TextEditingController();
    _sdtController = TextEditingController();
  }

  @override
  void dispose() {
    _emailController.dispose();
    _sdtController.dispose();
    super.dispose();
  }

  Future<void> _loadProfile() async {
    try {
      setState(() => isLoading = true);

      final response = await ApiService.get('/profile', needAuth: true);

      if (response.statusCode == 200) {
        final data = ApiService.parseResponse(response);
        if (data['success'] == true) {
          setState(() {
            profileData = data['data'];
            _populateControllers();
          });
        } else {
          _showErrorMessage(
            data['message'] ?? 'Không thể tải thông tin profile',
          );
        }
      } else {
        _showErrorMessage('Lỗi kết nối server');
      }
    } catch (e) {
      _showErrorMessage('Có lỗi xảy ra: $e');
    } finally {
      setState(() => isLoading = false);
    }
  }

  Future<void> _loadInterests() async {
    try {
      final response = await ApiService.get('/interests', needAuth: true);

      if (response.statusCode == 200) {
        final data = ApiService.parseResponse(response);
        if (data['success'] == true) {
          setState(() {
            availableInterests = List<Map<String, dynamic>>.from(
              (data['data']['interests'] as List).map(
                (interest) => {
                  'InterestID': interest['InterestID'],
                  'InterestName': interest['InterestName'],
                  'Icon': interest['Icon'] ?? 'fas fa-heart',
                },
              ),
            );
          });
        }
      }
    } catch (e) {
      print('Error loading interests: $e');
    }
  }

  void _populateControllers() {
    if (profileData != null) {
      _emailController.text = profileData!['Email'] ?? '';
      _sdtController.text = profileData!['SDT'] ?? '';
      
      // Parse sở thích từ chuỗi
      if (profileData!['SoThich'] != null && profileData!['SoThich'].isNotEmpty) {
        selectedInterests = (profileData!['SoThich'] as String)
            .split(',')
            .map((s) => s.trim())
            .toList();
      } else {
        selectedInterests = [];
      }
    }
  }

  Future<void> _updateProfile() async {
    if (!_formKey.currentState!.validate()) return;

    try {
      final requestData = {
        'Email': _emailController.text.trim(),
        'SDT': _sdtController.text.trim(),
        'SoThich': selectedInterests.join(', '),
      };

      final response = await ApiService.put(
        '/profile/update',
        body: requestData,
        needAuth: true,
      );
      final data = ApiService.parseResponse(response);

      if (response.statusCode == 200 && data['success'] == true) {
        _showSuccessMessage(
          data['message'] ?? 'Cập nhật thông tin thành công!',
        );
        await _loadProfile(); // Reload data
        setState(() => isEditing = false);
      } else {
        _showErrorMessage(data['message'] ?? 'Không thể cập nhật thông tin');
      }
    } catch (e) {
      _showErrorMessage('Có lỗi xảy ra: $e');
    }
  }

  Future<void> _logout(BuildContext context) async {
    await _storage.delete(key: 'auth_token');
    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (context) => LoginScreen()),
      (route) => false,
    );
  }

  void _showLogoutDialog(BuildContext context) {
    showDialog(
      context: context,
      builder:
          (context) => AlertDialog(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(15),
            ),
            title: Text(
              'Đăng xuất',
              style: TextStyle(fontWeight: FontWeight.bold),
            ),
            content: Text('Bạn có chắc chắn muốn đăng xuất?'),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(context),
                child: Text('Hủy', style: TextStyle(color: Colors.grey)),
              ),
              TextButton(
                onPressed: () {
                  Navigator.pop(context);
                  _logout(context);
                },
                child: Text(
                  'Đăng xuất',
                  style: TextStyle(
                    color: Colors.red,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
    );
  }

  void _showErrorMessage(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: Colors.red,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }

  void _showSuccessMessage(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: Colors.green,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      body: SafeArea(
        child:
            isLoading
                ? Center(child: CircularProgressIndicator())
                : RefreshIndicator(
                  onRefresh: _loadProfile,
                  child: SingleChildScrollView(
                    physics: AlwaysScrollableScrollPhysics(),
                    child: Column(
                      children: [
                        _buildHeader(),
                        _buildStats(),
                        if (isEditing)
                          _buildEditForm()
                        else
                          _buildProfileInfo(),
                        _buildActionButtons(),
                        SizedBox(height: 20),
                      ],
                    ),
                  ),
                ),
      ),
    );
  }

  Widget _buildHeader() {
    return Container(
      width: double.infinity,
      decoration: const BoxDecoration(
        color: Color(0xFF1E5A96),
        borderRadius: BorderRadius.only(
          bottomLeft: Radius.circular(30),
          bottomRight: Radius.circular(30),
        ),
      ),
      child: Column(
        children: [
          // App bar
          Padding(
            padding: const EdgeInsets.all(16),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                if (isEditing)
                  IconButton(
                    icon: Icon(Icons.close, color: Colors.white),
                    onPressed: () {
                      setState(() {
                        isEditing = false;
                        _populateControllers(); // Reset form
                      });
                    },
                  )
                else
                  SizedBox(width: 48),
                Text(
                  isEditing ? 'Chỉnh sửa thông tin' : 'Thông tin cá nhân',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                if (!isEditing)
                  IconButton(
                    icon: Icon(Icons.edit, color: Colors.white),
                    onPressed: () {
                      setState(() => isEditing = true);
                      _loadInterests(); // Tải toàn bộ danh sách sở thích
                    },
                  )
                else
                  SizedBox(width: 48),
              ],
            ),
          ),

          // Avatar và thông tin cơ bản
          Padding(
            padding: const EdgeInsets.only(bottom: 30),
            child: Column(
              children: [
                Container(
                  padding: EdgeInsets.all(4),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    shape: BoxShape.circle,
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black26,
                        blurRadius: 10,
                        offset: Offset(0, 4),
                      ),
                    ],
                  ),
                  child: CircleAvatar(
                    radius: 50,
                    backgroundColor: Colors.grey[200],
                    child: Icon(
                      Icons.person,
                      size: 50,
                      color: Color(0xFF2E5077),
                    ),
                  ),
                ),
                SizedBox(height: 16),
                Text(
                  profileData?['HoTen'] ?? 'Đang tải...',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                SizedBox(height: 4),
                Text(
                  'MSSV: ${profileData?['MSSV'] ?? ''}',
                  style: TextStyle(
                    color: Colors.white.withOpacity(0.9),
                    fontSize: 16,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStats() {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Row(
        children: [
          Expanded(
            child: _buildStatCard(
              icon: Icons.star,
              label: 'Điểm rèn luyện',
              value: '${profileData?['DiemRL'] ?? 0}',
              color: Color(0xFFFFD93D),
            ),
          ),
          SizedBox(width: 12),
          Expanded(
            child: _buildStatCard(
              icon: Icons.favorite,
              label: 'Điểm CTXH',
              value: '${profileData?['DiemCTXH'] ?? 0}',
              color: Color(0xFFE57373),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildEditForm() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Form(
        key: _formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Chỉnh sửa thông tin',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Colors.grey[800],
              ),
            ),
            SizedBox(height: 12),

            _buildEditCard([
              _buildTextFormField(
                controller: _emailController,
                label: 'Email',
                icon: Icons.email,
                keyboardType: TextInputType.emailAddress,
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'Vui lòng nhập email';
                  }
                  if (!RegExp(
                    r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$',
                  ).hasMatch(value)) {
                    return 'Email không hợp lệ';
                  }
                  return null;
                },
              ),
              SizedBox(height: 16),
              _buildTextFormField(
                controller: _sdtController,
                label: 'Số điện thoại',
                icon: Icons.phone,
                keyboardType: TextInputType.phone,
              ),
              SizedBox(height: 16),
              _buildInterestsSelector(),
            ]),

            SizedBox(height: 16),

            // Save/Cancel buttons
            Row(
              children: [
                Expanded(
                  child: ElevatedButton(
                    onPressed: _updateProfile,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Color(0xFF1E5A96),
                      foregroundColor: Colors.white,
                      padding: EdgeInsets.symmetric(vertical: 16),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    child: Text(
                      'Lưu thay đổi',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
                SizedBox(width: 12),
                Expanded(
                  child: OutlinedButton(
                    onPressed: () {
                      setState(() {
                        isEditing = false;
                        _populateControllers();
                      });
                    },
                    style: OutlinedButton.styleFrom(
                      foregroundColor: Colors.grey[600],
                      padding: EdgeInsets.symmetric(vertical: 16),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    child: Text('Hủy', style: TextStyle(fontSize: 16)),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildProfileInfo() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Thông tin chi tiết',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: Colors.grey[800],
            ),
          ),
          SizedBox(height: 12),

          _buildInfoCard(
            children: [
              _buildInfoRow(
                Icons.school,
                'Khoa',
                profileData?['TenKhoa'] ?? 'Chưa có thông tin',
              ),
              Divider(height: 24),
              _buildInfoRow(
                Icons.class_,
                'Lớp',
                profileData?['MaLop'] ?? 'Chưa có thông tin',
              ),
              Divider(height: 24),
              _buildInfoRow(
                Icons.email,
                'Email',
                profileData?['Email'] ?? 'Chưa có thông tin',
              ),
              Divider(height: 24),
              _buildInfoRow(
                Icons.phone,
                'Số điện thoại',
                profileData?['SDT'] ?? 'Chưa có thông tin',
              ),
            ],
          ),

          SizedBox(height: 16),

          _buildInfoCard(
            children: [
              _buildInfoRow(
                Icons.cake,
                'Ngày sinh',
                _formatDate(profileData?['NgaySinh']),
              ),
              Divider(height: 24),
              _buildInfoRow(
                Icons.person_outline,
                'Giới tính',
                profileData?['GioiTinh'] ?? 'Chưa có thông tin',
              ),
              Divider(height: 24),
              _buildInfoRow(
                Icons.school_outlined,
                'Dự kiến tốt nghiệp',
                _formatDate(profileData?['ThoiGianTotNghiepDuKien']),
              ),
              Divider(height: 24),
              _buildInfoRow(
                Icons.interests,
                'Sở thích',
                profileData?['SoThich'] ?? 'Chưa có thông tin',
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildActionButtons() {
    if (isEditing) return SizedBox.shrink();

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Column(
        children: [
          SizedBox(height: 16),
          _buildActionButton(
            icon: Icons.lock_outline,
            label: 'Đổi mật khẩu',
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => ChangePasswordScreen()),
              );
            },
          ),
          SizedBox(height: 12),
          _buildActionButton(
            icon: Icons.logout,
            label: 'Đăng xuất',
            onTap: () => _showLogoutDialog(context),
            isDestructive: true,
          ),
        ],
      ),
    );
  }

  // Helper methods for form fields
  Widget _buildEditCard(List<Widget> children) {
    return Container(
      padding: EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: Offset(0, 2),
          ),
        ],
      ),
      child: Column(children: children),
    );
  }

  Widget _buildTextFormField({
    required TextEditingController controller,
    required String label,
    required IconData icon,
    String? Function(String?)? validator,
    TextInputType? keyboardType,
    int maxLines = 1,
  }) {
    return TextFormField(
      controller: controller,
      validator: validator,
      keyboardType: keyboardType,
      maxLines: maxLines,
      decoration: InputDecoration(
        labelText: label,
        prefixIcon: Icon(icon, color: Color(0xFF2E5077)),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide(color: Colors.grey[300]!),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide(color: Color(0xFF1E5A96), width: 2),
        ),
        filled: true,
        fillColor: Colors.grey[50],
      ),
    );
  }

  Widget _buildDateField({
    required String label,
    required IconData icon,
    required DateTime? selectedDate,
    required Function(DateTime) onDateSelected,
  }) {
    return InkWell(
      onTap: () async {
        final DateTime? picked = await showDatePicker(
          context: context,
          initialDate: selectedDate ?? DateTime.now(),
          firstDate: DateTime(2020),
          lastDate: DateTime.now().add(Duration(days: 365 * 5)),
        );
        if (picked != null) {
          onDateSelected(picked);
        }
      },
      child: Container(
        padding: EdgeInsets.symmetric(horizontal: 12, vertical: 16),
        decoration: BoxDecoration(
          border: Border.all(color: Colors.grey[300]!),
          borderRadius: BorderRadius.circular(12),
          color: Colors.grey[50],
        ),
        child: Row(
          children: [
            Icon(icon, color: Color(0xFF2E5077)),
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
                    selectedDate != null
                        ? _formatDate(selectedDate.toIso8601String())
                        : 'Chọn ngày',
                    style: TextStyle(
                      fontSize: 16,
                      color:
                          selectedDate != null
                              ? Colors.grey[800]
                              : Colors.grey[500],
                    ),
                  ),
                ],
              ),
            ),
            Icon(Icons.calendar_today, color: Colors.grey[400], size: 20),
          ],
        ),
      ),
    );
  }

  Widget _buildInterestsSelector() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Sở thích',
          style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: Colors.grey[700]),
        ),
        SizedBox(height: 12),
        availableInterests.isEmpty
            ? Text(
              'Đang tải danh sách sở thích...',
              style: TextStyle(color: Colors.grey[500]),
            )
            : GridView.count(
              crossAxisCount: 2,
              childAspectRatio: 1.2,
              crossAxisSpacing: 12,
              mainAxisSpacing: 12,
              shrinkWrap: true,
              physics: NeverScrollableScrollPhysics(),
              children: availableInterests.map((interest) {
                final interestName = interest['InterestName'] as String;
                final isSelected = selectedInterests.contains(interestName);
                
                return Container(
                  decoration: BoxDecoration(
                    border: Border.all(
                      color: isSelected ? Color(0xFF1E5A96) : Colors.grey[300]!,
                      width: isSelected ? 2 : 1,
                    ),
                    borderRadius: BorderRadius.circular(12),
                    color: isSelected 
                        ? Color(0xFF1E5A96).withOpacity(0.1)
                        : Colors.white,
                  ),
                  child: Material(
                    color: Colors.transparent,
                    child: InkWell(
                      borderRadius: BorderRadius.circular(12),
                      onTap: () {
                        setState(() {
                          if (isSelected) {
                            selectedInterests.remove(interestName);
                          } else {
                            selectedInterests.add(interestName);
                          }
                        });
                      },
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(
                            Icons.favorite_border,
                            size: 24,
                            color: isSelected 
                                ? Color(0xFF1E5A96)
                                : Colors.grey[400],
                          ),
                          SizedBox(height: 8),
                          Padding(
                            padding: EdgeInsets.symmetric(horizontal: 4),
                            child: Text(
                              interestName,
                              textAlign: TextAlign.center,
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                              style: TextStyle(
                                fontSize: 12,
                                fontWeight: FontWeight.w500,
                                color: isSelected 
                                    ? Color(0xFF1E5A96)
                                    : Colors.grey[700],
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                );
              }).toList(),
            ),
      ],
    );
  }

  String _formatDate(String? dateString) {
    if (dateString == null || dateString.isEmpty) {
      return 'Chưa có thông tin';
    }
    try {
      final date = DateTime.parse(dateString);
      return '${date.day.toString().padLeft(2, '0')}/${date.month.toString().padLeft(2, '0')}/${date.year}';
    } catch (e) {
      return 'Chưa có thông tin';
    }
  }

  Widget _buildStatCard({
    required IconData icon,
    required String label,
    required String value,
    required Color color,
  }) {
    return Container(
      padding: EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        children: [
          Container(
            padding: EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: color.withOpacity(0.15),
              shape: BoxShape.circle,
            ),
            child: Icon(icon, color: color, size: 24),
          ),
          SizedBox(height: 8),
          Text(
            value,
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: Colors.grey[800],
            ),
          ),
          SizedBox(height: 2),
          Text(
            label,
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 11,
              color: Colors.grey[600],
              height: 1.2,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInfoCard({required List<Widget> children}) {
    return Container(
      padding: EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: Offset(0, 2),
          ),
        ],
      ),
      child: Column(children: children),
    );
  }

  Widget _buildInfoRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Container(
          padding: EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: Color(0xFF2E5077).withOpacity(0.1),
            borderRadius: BorderRadius.circular(10),
          ),
          child: Icon(icon, color: Color(0xFF2E5077), size: 20),
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

  Widget _buildActionButton({
    required IconData icon,
    required String label,
    required VoidCallback onTap,
    bool isDestructive = false,
  }) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: Offset(0, 2),
          ),
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap: onTap,
          child: Padding(
            padding: EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            child: Row(
              children: [
                Icon(
                  icon,
                  color: isDestructive ? Colors.red : Color(0xFF2E5077),
                  size: 24,
                ),
                SizedBox(width: 12),
                Text(
                  label,
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                    color: isDestructive ? Colors.red : Colors.grey[800],
                  ),
                ),
                Spacer(),
                Icon(
                  Icons.arrow_forward_ios,
                  color: Colors.grey[400],
                  size: 16,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
