import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import '../services/api_service.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ContactScreen extends StatefulWidget {
  const ContactScreen({super.key});

  @override
  State<ContactScreen> createState() => _ContactScreenState();
}

class _ContactScreenState extends State<ContactScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _subjectController = TextEditingController();
  final _messageController = TextEditingController();
  String? _selectedCategory;
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    _loadUserInfo();
  }

  void _loadUserInfo() async {
    try {
      const storage = FlutterSecureStorage();

      // Lấy thông tin từ storage
      final name = await storage.read(key: 'user_name');
      final email = await storage.read(key: 'user_email');

      // Nếu không có trong storage, gọi API để lấy profile
      if (name == null || email == null) {
        await _fetchUserProfile();
      } else {
        setState(() {
          _nameController.text = name;
          _emailController.text = email;
        });
      }
    } catch (e) {
      print('Error loading user info: $e');
      // Thử gọi API nếu storage bị lỗi
      await _fetchUserProfile();
    }
  }

  Future<void> _fetchUserProfile() async {
    try {
      final response = await ApiService.get('/profile', needAuth: true);

      if (ApiService.isSuccessResponse(response)) {
        final data = ApiService.parseResponse(response);

        if (data['success'] == true && data['data'] != null) {
          final userInfo = data['data'];
          final name = userInfo['HoTen'] ?? 'Chưa có thông tin';
          final email = userInfo['Email'] ?? 'Chưa có thông tin';

          // Lưu vào storage
          const storage = FlutterSecureStorage();
          await storage.write(key: 'user_name', value: name);
          await storage.write(key: 'user_email', value: email);

          setState(() {
            _nameController.text = name;
            _emailController.text = email;
          });
        }
      } else {
        setState(() {
          _nameController.text = 'Không thể tải thông tin';
          _emailController.text = 'Không thể tải thông tin';
        });
      }
    } catch (e) {
      print('Error fetching profile: $e');
      setState(() {
        _nameController.text = 'Lỗi kết nối';
        _emailController.text = 'Lỗi kết nối';
      });
    }
  }

  final List<Map<String, dynamic>> _contactInfo = [
    {
      'title': 'HUIT - Đại học Công Thương TP.HCM',
      'items': [
        {
          'icon': Icons.location_on,
          'label': 'Địa chỉ',
          'value': '140 Lê Trọng Tấn, P.Tây Thạnh, TP.HCM',
        },
        {
          'icon': Icons.phone,
          'label': 'Điện thoại',
          'value': '0283 8163 318',
          'action': 'phone',
        },
        {
          'icon': Icons.email,
          'label': 'Email',
          'value': 'info@huit.edu.vn',
          'action': 'email',
        },
        {
          'icon': Icons.language,
          'label': 'Website',
          'value': 'https://huit.edu.vn/',
          'action': 'web',
        },
      ],
    },
    {
      'title': 'Phòng Công tác Sinh viên',
      'items': [
        {'icon': Icons.location_on, 'label': 'Phòng', 'value': 'D001 - Tòa D'},
        {
          'icon': Icons.phone,
          'label': 'Điện thoại',
          'value': '(028) 3816 3320',
          'action': 'phone',
        },
        {
          'icon': Icons.email,
          'label': 'Email',
          'value': 'dungnv@huit.edu.vn',
          'action': 'email',
        },
        {
          'icon': Icons.access_time,
          'label': 'Giờ làm việc',
          'value': 'T2-T6: 7:30-11:30, 13:30-17:00',
        },
      ],
    },
  ];

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _subjectController.dispose();
    _messageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F5F5),
      appBar: AppBar(
        backgroundColor: const Color(0xFF1E3A8A),
        centerTitle: true,
        title: const Text(
          'Liên hệ',
          style: TextStyle(
            color: Colors.white,
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
        ),
        automaticallyImplyLeading: false,
        elevation: 0,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Feedback form at the top
            _buildFeedbackForm(),

            const SizedBox(height: 32),

            // Header Image
            Container(
              width: double.infinity,
              height: 200,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(12),
                gradient: LinearGradient(
                  colors: [
                    const Color(0xFF1E3A8A),
                    const Color(0xFF1E3A8A).withOpacity(0.8),
                  ],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
              ),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(50),
                    ),
                    child: const Icon(
                      Icons.school,
                      size: 50,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 16),
                  const Text(
                    'Liên hệ với chúng tôi',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'Chúng tôi luôn sẵn sàng hỗ trợ bạn',
                    style: TextStyle(color: Colors.white, fontSize: 16),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 20),

            // Contact sections (only university and CTSV office)
            ..._contactInfo.map((section) => _buildContactSection(section)),
          ],
        ),
      ),
    );
  }

  Widget _buildFeedbackForm() {
    return Container(
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
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: const Color(0xFF1E3A8A).withOpacity(0.1),
              borderRadius: const BorderRadius.only(
                topLeft: Radius.circular(12),
                topRight: Radius.circular(12),
              ),
            ),
            child: const Row(
              children: [
                Icon(Icons.feedback, color: Color(0xFF1E3A8A), size: 20),
                SizedBox(width: 8),
                Text(
                  'Gửi phản hồi',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF1E3A8A),
                  ),
                ),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16),
            child: Form(
              key: _formKey,
              child: Column(
                children: [
                  // Dòng 1: Họ và tên (readonly)
                  TextFormField(
                    controller: _nameController,
                    decoration: const InputDecoration(
                      labelText: 'Họ và tên',
                      border: OutlineInputBorder(),
                      prefixIcon: Icon(Icons.person),
                      filled: true,
                      fillColor: Color(0xFFF8F9FA),
                    ),
                    readOnly: true,
                  ),
                  const SizedBox(height: 16),

                  // Dòng 2: Email (readonly)
                  TextFormField(
                    controller: _emailController,
                    decoration: const InputDecoration(
                      labelText: 'Email',
                      border: OutlineInputBorder(),
                      prefixIcon: Icon(Icons.email),
                      filled: true,
                      fillColor: Color(0xFFF8F9FA),
                    ),
                    readOnly: true,
                  ),
                  const SizedBox(height: 20),
                  const Divider(),
                  const SizedBox(height: 20),

                  // Dòng 3: Phân loại phản hồi
                  DropdownButtonFormField<String>(
                    value: _selectedCategory,
                    decoration: const InputDecoration(
                      labelText: 'Phân loại *',
                      border: OutlineInputBorder(),
                      prefixIcon: Icon(Icons.category),
                    ),
                    items: const [
                      DropdownMenuItem(
                        value: 'BaoLoi',
                        child: Text('Báo lỗi kỹ thuật'),
                      ),
                      DropdownMenuItem(
                        value: 'GopY',
                        child: Text('Đóng góp ý kiến'),
                      ),
                      DropdownMenuItem(
                        value: 'HoTro',
                        child: Text('Yêu cầu hỗ trợ'),
                      ),
                      DropdownMenuItem(value: 'Khac', child: Text('Khác')),
                    ],
                    onChanged: (value) {
                      setState(() {
                        _selectedCategory = value;
                      });
                    },
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Vui lòng chọn loại phản hồi';
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 16),

                  // Dòng 4: Tiêu đề
                  TextFormField(
                    controller: _subjectController,
                    decoration: const InputDecoration(
                      labelText: 'Tiêu đề *',
                      border: OutlineInputBorder(),
                      prefixIcon: Icon(Icons.subject),
                    ),
                    validator: (value) {
                      if (value == null || value.trim().isEmpty) {
                        return 'Vui lòng nhập tiêu đề';
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 16),
                  TextFormField(
                    controller: _messageController,
                    decoration: const InputDecoration(
                      labelText: 'Nội dung chi tiết *',
                      border: OutlineInputBorder(),
                      prefixIcon: Icon(Icons.message),
                      alignLabelWithHint: true,
                      hintText:
                          'Vui lòng mô tả chi tiết vấn đề bạn gặp phải hoặc ý kiến đóng góp của bạn...',
                    ),
                    maxLines: 6,
                    validator: (value) {
                      if (value == null || value.trim().isEmpty) {
                        return 'Vui lòng nhập nội dung chi tiết';
                      }
                      if (value.trim().length < 30) {
                        return 'Nội dung phản hồi cần ít nhất 30 ký tự';
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 24),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: _isSubmitting ? null : _submitFeedback,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF1E3A8A),
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 16),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      ),
                      child:
                          _isSubmitting
                              ? const Row(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  SizedBox(
                                    width: 20,
                                    height: 20,
                                    child: CircularProgressIndicator(
                                      color: Colors.white,
                                      strokeWidth: 2,
                                    ),
                                  ),
                                  SizedBox(width: 8),
                                  Text('Đang gửi...'),
                                ],
                              )
                              : const Text(
                                'Gửi phản hồi',
                                style: TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Future<void> _submitFeedback() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    setState(() {
      _isSubmitting = true;
    });

    try {
      // Gửi phản hồi lên server
      final response = await ApiService.post(
        '/lienhe',
        needAuth: true,
        body: {
          'LoaiPhanHoi': _selectedCategory,
          'TieuDe': _subjectController.text.trim(),
          'NoiDung': _messageController.text.trim(),
        },
      );

      if (ApiService.isSuccessResponse(response)) {
        final data = ApiService.parseResponse(response);

        if (data['success'] == true) {
          // Show success message
          if (mounted) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(
                content: Text(
                  'Gửi phản hồi thành công! Cảm ơn bạn đã đóng góp ý kiến.',
                ),
                backgroundColor: Colors.green,
                behavior: SnackBarBehavior.floating,
              ),
            );

            // Clear form (chỉ clear các field có thể edit)
            _subjectController.clear();
            _messageController.clear();
            setState(() {
              _selectedCategory = null;
            });
          }
        } else {
          // Hiển thị lỗi từ server
          if (mounted) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(
                  data['message'] ?? 'Có lỗi xảy ra. Vui lòng thử lại!',
                ),
                backgroundColor: Colors.red,
                behavior: SnackBarBehavior.floating,
              ),
            );
          }
        }
      } else {
        // Lỗi HTTP
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Không thể kết nối đến server. Vui lòng thử lại!'),
              backgroundColor: Colors.red,
              behavior: SnackBarBehavior.floating,
            ),
          );
        }
      }
    } catch (e) {
      print('Error submitting feedback: $e');
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Có lỗi xảy ra. Vui lòng kiểm tra kết nối mạng!'),
            backgroundColor: Colors.red,
            behavior: SnackBarBehavior.floating,
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isSubmitting = false;
        });
      }
    }
  }

  Widget _buildContactSection(Map<String, dynamic> section) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
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
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: const Color(0xFF1E3A8A).withOpacity(0.1),
              borderRadius: const BorderRadius.only(
                topLeft: Radius.circular(12),
                topRight: Radius.circular(12),
              ),
            ),
            child: Row(
              children: [
                Icon(Icons.business, color: const Color(0xFF1E3A8A), size: 20),
                const SizedBox(width: 8),
                Text(
                  section['title'],
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF1E3A8A),
                  ),
                ),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              children:
                  (section['items'] as List).map<Widget>((item) {
                    return _buildContactItem(item);
                  }).toList(),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildContactItem(Map<String, dynamic> item) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      child: InkWell(
        onTap:
            item['action'] != null
                ? () => _handleAction(item['action'], item['value'])
                : null,
        borderRadius: BorderRadius.circular(8),
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: const Color(0xFF1E3A8A).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Icon(
                  item['icon'],
                  color: const Color(0xFF1E3A8A),
                  size: 20,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      item['label'],
                      style: const TextStyle(
                        fontSize: 14,
                        color: Colors.grey,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      item['value'],
                      style: const TextStyle(
                        fontSize: 16,
                        color: Color(0xFF1E3A8A),
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ],
                ),
              ),
              if (item['action'] != null)
                const Icon(Icons.open_in_new, color: Colors.grey, size: 20),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _handleAction(String action, String value) async {
    try {
      Uri uri;
      switch (action) {
        case 'phone':
          uri = Uri(scheme: 'tel', path: value);
          break;
        case 'email':
          uri = Uri(scheme: 'mailto', path: value);
          break;
        case 'web':
          uri = Uri.parse(value);
          break;
        default:
          return;
      }

      if (await canLaunchUrl(uri)) {
        await launchUrl(uri);
      } else {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Không thể mở $action'),
              backgroundColor: Colors.red,
            ),
          );
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Có lỗi xảy ra'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }
}
