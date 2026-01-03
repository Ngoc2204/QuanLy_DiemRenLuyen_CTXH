import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../services/activity_service.dart';
import 'package:intl/intl.dart';

class MyRegistrationsScreen extends StatefulWidget {
  const MyRegistrationsScreen({super.key});

  @override
  State<MyRegistrationsScreen> createState() => _MyRegistrationsScreenState();
}

class _MyRegistrationsScreenState extends State<MyRegistrationsScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  bool _isLoading = true;
  String? _errorMessage;

  List<Map<String, dynamic>> _drlRegistrations = [];
  List<Map<String, dynamic>> _ctxhRegistrations = [];

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
    _loadRegistrations();
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  Future<void> _loadRegistrations() async {
    try {
      setState(() {
        _isLoading = true;
        _errorMessage = null;
      });

      final result = await ActivityService.getMyRegistrations();
      print('Registration result: $result');

      if (result['success']) {
        final data = result['data'];

        setState(() {
          // Parse DRL registrations
          final drlList = (data['drl_registrations'] as List?) ?? [];
          _drlRegistrations = List<Map<String, dynamic>>.from(
            drlList.map((item) => item as Map<String, dynamic>),
          );

          // Parse CTXH registrations
          final ctxhList = (data['ctxh_registrations'] as List?) ?? [];
          _ctxhRegistrations = List<Map<String, dynamic>>.from(
            ctxhList.map((item) => item as Map<String, dynamic>),
          );

          print('DRL: ${_drlRegistrations.length}, CTXH: ${_ctxhRegistrations.length}');
          _isLoading = false;
        });
      } else {
        setState(() {
          _errorMessage = result['message'] ?? 'Failed to load registrations';
          _isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Error: $e';
        _isLoading = false;
      });
    }
  }

  Future<void> _cancelRegistration(String maDangKy, String type) async {
    // Show confirmation dialog
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Xác nhận hủy đăng ký'),
        content: const Text('Bạn có chắc chắn muốn hủy đăng ký hoạt động này?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Không'),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Có'),
          ),
        ],
      ),
    );

    if (confirmed != true) return;

    try {
      final endpoint = type == 'DRL'
          ? '/my-registrations/cancel-drl/$maDangKy'
          : '/my-registrations/cancel-ctxh/$maDangKy';

      final response = await ActivityService.cancelRegistration(endpoint);

      if (response['success']) {
        // Show success message
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(response['message'] ?? 'Đã hủy đăng ký thành công'),
              backgroundColor: Colors.green,
            ),
          );
          // Reload list
          _loadRegistrations();
        }
      } else {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(response['message'] ?? 'Không thể hủy đăng ký'),
              backgroundColor: Colors.red,
            ),
          );
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Lỗi: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  String _formatDate(String? dateStr) {
    if (dateStr == null) return 'N/A';
    try {
      final date = DateTime.parse(dateStr);
      return DateFormat('dd/MM/yyyy HH:mm').format(date);
    } catch (e) {
      return dateStr;
    }
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Đã duyệt':
        return Colors.green;
      case 'Chờ duyệt':
        return Colors.orange;
      case 'Bị từ chối':
        return Colors.red;
      case 'Chờ thanh toán':
        return Colors.deepOrange;
      default:
        return Colors.grey;
    }
  }

  Widget _buildRegistrationCard(Map<String, dynamic> registration, String type) {
    final activity = registration['hoatdong'] as Map<String, dynamic>? ?? {};
    final status = registration['trang_thai_dang_ky'] ?? 'Unknown';
    final pointsRaw = activity['diem_rl'] ?? activity['diem_ctxh'] ?? 0;
    final points = pointsRaw is int ? pointsRaw : int.tryParse(pointsRaw.toString()) ?? 0;
    final maDangKyRaw = registration['ma_dang_ky'];
    final maDangKy = maDangKyRaw is int ? maDangKyRaw.toString() : (maDangKyRaw ?? '').toString();
    final canCancel = registration['can_cancel'] ?? false;

    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      child: Column(
        children: [
          // Header
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: type == 'DRL' ? Colors.blue.shade50 : Colors.pink.shade50,
              borderRadius: const BorderRadius.only(
                topLeft: Radius.circular(12),
                topRight: Radius.circular(12),
              ),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Row(
                  children: [
                    Icon(
                      type == 'DRL' ? Icons.star : Icons.favorite,
                      color: type == 'DRL' ? Colors.blue : Colors.pink,
                      size: 24,
                    ),
                    const SizedBox(width: 8),
                    Text(
                      type,
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: type == 'DRL' ? Colors.blue : Colors.pink,
                      ),
                    ),
                  ],
                ),
                Text(
                  '+$points điểm',
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Colors.green,
                  ),
                ),
              ],
            ),
          ),
          // Body
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Activity name
                Text(
                  activity['ten'] ?? 'N/A',
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 12),
                // Activity details
                _buildDetailRow(
                  Icons.calendar_today,
                  'Bắt đầu',
                  _formatDate(activity['ngay_to_chuc']),
                ),
                const SizedBox(height: 8),
                _buildDetailRow(
                  Icons.location_on,
                  'Địa điểm',
                  activity['dia_diem'] ?? 'N/A',
                ),
                const SizedBox(height: 8),
                _buildDetailRow(
                  Icons.schedule,
                  'Hạn hủy',
                  activity['thoi_han_huy'] != null
                      ? _formatDate(activity['thoi_han_huy'])
                      : 'Không có',
                ),
              ],
            ),
          ),
          // Footer
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.grey.shade50,
              borderRadius: const BorderRadius.only(
                bottomLeft: Radius.circular(12),
                bottomRight: Radius.circular(12),
              ),
              border: Border(
                top: BorderSide(color: Colors.grey.shade200),
              ),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                // Status badge
                Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                  decoration: BoxDecoration(
                    color: _getStatusColor(status).withOpacity(0.1),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    status,
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.bold,
                      color: _getStatusColor(status),
                    ),
                  ),
                ),
                // Action buttons
                if (type == 'CTXH' && status == 'Chờ thanh toán')
                  ElevatedButton.icon(
                    onPressed: () => _showPaymentDialog(registration),
                    icon: const Icon(Icons.payment, size: 16),
                    label: const Text('Thanh toán'),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.orange,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(
                        horizontal: 12,
                        vertical: 8,
                      ),
                    ),
                  )
                else if (canCancel)
                  ElevatedButton.icon(
                    onPressed: () => _cancelRegistration(maDangKy, type),
                    icon: const Icon(Icons.close, size: 16),
                    label: const Text('Hủy'),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.red,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(
                        horizontal: 12,
                        vertical: 8,
                      ),
                    ),
                  )
                else
                  Chip(
                    label: const Text(
                      'Đã qua hạn hủy',
                      style: TextStyle(fontSize: 12),
                    ),
                    backgroundColor: Colors.grey.shade300,
                    labelPadding: const EdgeInsets.symmetric(horizontal: 8),
                  ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDetailRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Icon(icon, size: 18, color: Colors.grey.shade600),
        const SizedBox(width: 8),
        Text(
          '$label: ',
          style: TextStyle(
            fontSize: 13,
            color: Colors.grey.shade600,
            fontWeight: FontWeight.w500,
          ),
        ),
        Expanded(
          child: Text(
            value,
            style: const TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.bold,
            ),
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
          ),
        ),
      ],
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Quản Lý Đăng Ký'),
        centerTitle: true,
        elevation: 0,
        bottom: TabBar(
          controller: _tabController,
          tabs: [
            Tab(
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(Icons.star),
                  const SizedBox(width: 8),
                  Text('DRL (${_drlRegistrations.length})'),
                ],
              ),
            ),
            Tab(
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(Icons.favorite),
                  const SizedBox(width: 8),
                  Text('CTXH (${_ctxhRegistrations.length})'),
                ],
              ),
            ),
          ],
        ),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _errorMessage != null
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(
                        Icons.error_outline,
                        size: 48,
                        color: Colors.red.shade300,
                      ),
                      const SizedBox(height: 16),
                      Text(_errorMessage!),
                      const SizedBox(height: 16),
                      ElevatedButton.icon(
                        onPressed: _loadRegistrations,
                        icon: const Icon(Icons.refresh),
                        label: const Text('Thử lại'),
                      ),
                    ],
                  ),
                )
              : TabBarView(
                  controller: _tabController,
                  children: [
                    // DRL Tab
                    _buildTabContent(_drlRegistrations, 'DRL'),
                    // CTXH Tab
                    _buildTabContent(_ctxhRegistrations, 'CTXH'),
                  ],
                ),
      floatingActionButton: FloatingActionButton(
        onPressed: _loadRegistrations,
        child: const Icon(Icons.refresh),
      ),
    );
  }

  Widget _buildTabContent(
      List<Map<String, dynamic>> registrations, String type) {
    if (registrations.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              Icons.inbox_outlined,
              size: 48,
              color: Colors.grey.shade400,
            ),
            const SizedBox(height: 16),
            Text(
              type == 'DRL'
                  ? 'Bạn chưa đăng ký hoạt động rèn luyện nào'
                  : 'Bạn chưa đăng ký hoạt động CTXH nào',
              style: TextStyle(
                fontSize: 14,
                color: Colors.grey.shade600,
              ),
            ),
          ],
        ),
      );
    }

    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: registrations.length,
      itemBuilder: (context, index) {
        return _buildRegistrationCard(registrations[index], type);
      },
    );
  }

  void _showPaymentDialog(Map<String, dynamic> registration) {
    final activity = registration['hoatdong'] as Map<String, dynamic>? ?? {};
    final diaDiemDetail = activity['dia_diem_detail'] as Map<String, dynamic>? ?? {};
    
    // Handle amount - can be int or string
    int amount = 0;
    final rawAmount = diaDiemDetail['gia_tien'];
    if (rawAmount != null) {
      if (rawAmount is int) {
        amount = rawAmount;
      } else if (rawAmount is String) {
        amount = int.tryParse(rawAmount) ?? 0;
      } else {
        amount = int.tryParse(rawAmount.toString()) ?? 0;
      }
    }

    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Thanh toán'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Hoạt động: ${activity['ten'] ?? 'N/A'}'),
            const SizedBox(height: 12),
            Text(
              'Số tiền cần thanh toán: ${amount.toString().replaceAllMapped(RegExp(r'\B(?=(\d{3})+(?!\d))'), (Match m) => ',')}đ',
              style: const TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 16,
              ),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Hủy'),
          ),
          ElevatedButton(
            onPressed: () async {
              Navigator.pop(context);
              _processPayment(registration);
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.orange,
            ),
            child: const Text('Chọn phương thức thanh toán'),
          ),
        ],
      ),
    );
  }

  void _processPayment(Map<String, dynamic> registration) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Chọn phương thức thanh toán'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            ListTile(
              leading: const Icon(Icons.money, color: Colors.green),
              title: const Text('Tiền mặt'),
              subtitle: const Text('Thanh toán tại quầy'),
              onTap: () async {
                Navigator.pop(context);
                await _confirmPayment(registration, 'Tiền mặt');
              },
            ),
            ListTile(
              leading: const Icon(Icons.credit_card, color: Colors.blue),
              title: const Text('Thanh toán online'),
              subtitle: const Text('Qua ngân hàng/ví điện tử'),
              onTap: () async {
                Navigator.pop(context);
                await _confirmPayment(registration, 'Online');
              },
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Đóng'),
          ),
        ],
      ),
    );
  }

  void _showOnlinePaymentDialog(Map<String, dynamic> paymentData) {
    final amount = paymentData['amount'] ?? 0;
    final bankInfo = paymentData['bank_info'] as Map<String, dynamic>? ?? {};
    final transferContent = paymentData['transfer_content'] ?? '';
    final qrUrl = paymentData['qr_url'] ?? '';

    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (BuildContext context) {
        return Dialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          child: SingleChildScrollView(
            child: Padding(
              padding: const EdgeInsets.all(24),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  // Header
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.blue.shade50,
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Column(
                      children: [
                        const Icon(Icons.qr_code_2, size: 40, color: Colors.blue),
                        const SizedBox(height: 12),
                        const Text(
                          'Thanh Toán Online (VietQR)',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                            color: Colors.blue,
                          ),
                        ),
                        const SizedBox(height: 4),
                        const Text(
                          'Sử dụng ứng dụng ngân hàng để quét mã',
                          style: TextStyle(fontSize: 12, color: Colors.grey),
                        ),
                        const SizedBox(height: 12),
                        Text(
                          '${amount.toString().replaceAllMapped(RegExp(r'\B(?=(\d{3})+(?!\d))'), (Match m) => '.')} đ',
                          style: const TextStyle(
                            fontSize: 28,
                            fontWeight: FontWeight.bold,
                            color: Colors.red,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 24),

                  // QR Code
                  if (qrUrl.isNotEmpty)
                    Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        border: Border.all(color: Colors.grey.shade300, width: 2),
                        borderRadius: BorderRadius.circular(12),
                        color: Colors.grey.shade100,
                      ),
                      child: Image.network(
                        qrUrl,
                        width: 280,
                        height: 280,
                        errorBuilder: (context, error, stackTrace) {
                          return Container(
                            width: 280,
                            height: 280,
                            decoration: BoxDecoration(
                              color: Colors.grey.shade200,
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: const Center(
                              child: Icon(Icons.broken_image, size: 50, color: Colors.grey),
                            ),
                          );
                        },
                      ),
                    ),
                  const SizedBox(height: 24),

                  // Warning
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.orange.shade50,
                      border: Border.all(color: Colors.orange.shade200),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Row(
                      children: [
                        Icon(Icons.warning_rounded, color: Colors.orange.shade700),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'Quan trọng!',
                                style: TextStyle(
                                  fontWeight: FontWeight.bold,
                                  color: Colors.orange.shade700,
                                ),
                              ),
                              const SizedBox(height: 4),
                              const Text(
                                'Vui lòng nhập đúng nội dung chuyển khoản',
                                style: TextStyle(fontSize: 12),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),

                  // Transfer Info
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.grey.shade50,
                      border: Border.all(color: Colors.grey.shade300),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Thông tin chuyển khoản:',
                          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
                        ),
                        const SizedBox(height: 12),
                        _buildTransferInfoRow('Ngân hàng:', 'Vietcombank'),
                        const SizedBox(height: 8),
                        _buildTransferInfoRow('Số tài khoản:', bankInfo['account_no'] ?? 'N/A'),
                        const SizedBox(height: 8),
                        _buildTransferInfoRow('Chủ tài khoản:', bankInfo['account_name'] ?? 'N/A'),
                        const SizedBox(height: 8),
                        _buildTransferInfoRow('Số tiền:', '${amount.toString().replaceAllMapped(RegExp(r'\B(?=(\d{3})+(?!\d))'), (Match m) => '.')} đ'),
                        const SizedBox(height: 12),
                        Container(
                          padding: const EdgeInsets.all(10),
                          decoration: BoxDecoration(
                            color: Colors.blue.shade50,
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text(
                                'Nội dung chuyển khoản:',
                                style: TextStyle(
                                  fontWeight: FontWeight.bold,
                                  fontSize: 12,
                                  color: Colors.blue,
                                ),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                transferContent,
                                style: const TextStyle(
                                  fontSize: 14,
                                  fontWeight: FontWeight.bold,
                                  color: Colors.blue,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 24),

                  // Instructions
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.blue.shade50,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Hướng dẫn thanh toán:',
                          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
                        ),
                        const SizedBox(height: 12),
                        _buildInstructionStep('1', 'Mở ứng dụng ngân hàng hoặc Momo của bạn'),
                        const SizedBox(height: 8),
                        _buildInstructionStep('2', 'Chọn "Quét mã QR" hoặc "Scan"'),
                        const SizedBox(height: 8),
                        _buildInstructionStep('3', 'Quét mã ở trên. Số tiền sẽ được điền tự động'),
                        const SizedBox(height: 8),
                        _buildInstructionStep('4', 'Kiểm tra nội dung chuyển khoản ở trên'),
                        const SizedBox(height: 8),
                        _buildInstructionStep('5', 'Xác nhận và hoàn tất chuyển khoản'),
                      ],
                    ),
                  ),
                  const SizedBox(height: 24),

                  // Buttons
                  Row(
                    children: [
                      Expanded(
                        child: OutlinedButton(
                          onPressed: () {
                            Navigator.of(context).pop();
                            _loadRegistrations();
                          },
                          style: OutlinedButton.styleFrom(
                            padding: const EdgeInsets.symmetric(vertical: 12),
                            side: const BorderSide(color: Colors.grey),
                          ),
                          child: const Text('Đóng'),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: ElevatedButton(
                          onPressed: () {
                            // Copy transfer content to clipboard
                            _copyToClipboard(transferContent);
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text('Đã sao chép nội dung chuyển khoản'),
                                duration: Duration(seconds: 2),
                              ),
                            );
                          },
                          style: ElevatedButton.styleFrom(
                            padding: const EdgeInsets.symmetric(vertical: 12),
                            backgroundColor: Colors.blue,
                          ),
                          child: const Text(
                            'Sao chép nội dung',
                            style: TextStyle(color: Colors.white),
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }

  Widget _buildTransferInfoRow(String label, String value) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: const TextStyle(fontSize: 12, color: Colors.grey),
        ),
        Text(
          value,
          style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 12),
        ),
      ],
    );
  }

  Widget _buildInstructionStep(String step, String text) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Container(
          width: 28,
          height: 28,
          decoration: BoxDecoration(
            color: Colors.blue,
            borderRadius: BorderRadius.circular(14),
          ),
          child: Center(
            child: Text(
              step,
              style: const TextStyle(
                color: Colors.white,
                fontWeight: FontWeight.bold,
                fontSize: 12,
              ),
            ),
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Padding(
            padding: const EdgeInsets.only(top: 4),
            child: Text(
              text,
              style: const TextStyle(fontSize: 12),
            ),
          ),
        ),
      ],
    );
  }

  void _copyToClipboard(String text) {
    Clipboard.setData(ClipboardData(text: text));
  }

  Future<void> _confirmPayment(
      Map<String, dynamic> registration, String method) async {
    try {
      final registrationIdRaw = registration['ma_dang_ky'];
      final registrationId = registrationIdRaw.toString();
      print('=== PAYMENT DEBUG ===');
      print('Registration ID: $registrationId (type: ${registrationIdRaw.runtimeType})');
      print('Method: $method');
      
      // First, get payment ID from registration
      final paymentResult =
          await ActivityService.getPaymentByRegistration(registrationId);
      
      print('Payment Result: $paymentResult');
      
      if (!paymentResult['success']) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Lỗi: ${paymentResult['message']}'),
              backgroundColor: Colors.red,
            ),
          );
        }
        return;
      }

      final paymentData = paymentResult['data'] as Map<String, dynamic>? ?? {};
      print('Payment Data: $paymentData');
      final paymentId = paymentData['id'];
      print('Payment ID (raw): $paymentId, Type: ${paymentId.runtimeType}');
      
      // Convert to string if needed
      final paymentIdStr = paymentId.toString();
      print('Payment ID (string): $paymentIdStr');

      // Then confirm payment with correct payment ID
      final result =
          await ActivityService.confirmPaymentMethod(paymentIdStr, method);

      print('Confirm Result: $result');

      if (mounted) {
        if (result['success']) {
          // For online payment, show QR code dialog
          if (method == 'Online') {
            final paymentData = result['data'] as Map<String, dynamic>? ?? {};
            _showOnlinePaymentDialog(paymentData);
          } else {
            // For cash payment, show success message
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(
                content: Text('Đã ghi nhận thanh toán tiền mặt'),
                backgroundColor: Colors.green,
              ),
            );
            // Reload registrations
            _loadRegistrations();
          }
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Lỗi: ${result['message']}'),
              backgroundColor: Colors.red,
            ),
          );
        }
      }
    } catch (e) {
      print('Exception: $e');
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Lỗi: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }
}
