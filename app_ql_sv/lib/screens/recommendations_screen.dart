import 'package:flutter/material.dart';
import '../services/activity_service.dart';
import 'package:intl/intl.dart';

class RecommendationsScreen extends StatefulWidget {
  const RecommendationsScreen({super.key});

  @override
  State<RecommendationsScreen> createState() => _RecommendationsScreenState();
}

class _RecommendationsScreenState extends State<RecommendationsScreen> {
  List<Map<String, dynamic>> _recommendations = [];
  bool _isLoading = true;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _loadRecommendations();
  }

  Future<void> _loadRecommendations() async {
    try {
      setState(() {
        _isLoading = true;
        _errorMessage = null;
      });

      final result = await ActivityService.getRecommendations();
      print('[RecommendationsScreen] Result: $result');

      if (result['success']) {
        final recommendations = result['data'] as List? ?? [];
        setState(() {
          _recommendations = List<Map<String, dynamic>>.from(
            recommendations.map((item) => {
              'id': item['id'] ?? '',
              'ma_hoat_dong': item['MaHoatDong'] ?? item['ma_hoat_dong'] ?? '',
              'ten': item['ten'] ?? item['activity']?['ten'] ?? 'N/A',
              'type': item['activity_type'] ?? 'DRL',
              'recommendation_score': (item['recommendation_score'] ?? 0).toDouble(),
              'recommendation_reason': item['recommendation_reason'] ?? 'Hoạt động được gợi ý',
              'ngay_to_chuc': item['activity']?['ngay_to_chuc'] ?? item['ngay_to_chuc'] ?? '',
              'dia_diem': item['activity']?['dia_diem'] ?? item['dia_diem'] ?? 'Chưa xác định',
              'diem_rl': item['activity']?['diem_rl'] ?? item['diem_rl'] ?? 0,
              'diem_ctxh': item['activity']?['diem_ctxh'] ?? item['diem_ctxh'] ?? 0,
              'mo_ta': item['activity']?['mo_ta'] ?? item['mo_ta'] ?? '',
            }),
          );
          _isLoading = false;
        });
      } else {
        setState(() {
          _errorMessage = result['message'] ?? 'Không thể tải gợi ý hoạt động';
          _isLoading = false;
        });
      }
    } catch (e) {
      print('[RecommendationsScreen] Error: $e');
      setState(() {
        _errorMessage = 'Lỗi: $e';
        _isLoading = false;
      });
    }
  }

  String _formatDate(String dateStr) {
    if (dateStr.isEmpty) return 'N/A';
    try {
      final date = DateTime.parse(dateStr);
      return DateFormat('dd/MM/yyyy HH:mm').format(date);
    } catch (e) {
      return dateStr;
    }
  }

  String _getTypeLabel(String type) {
    return type.toUpperCase() == 'DRL' ? 'Rèn Luyện' : (type.toUpperCase() == 'CTXH' ? 'Xã Hội' : type);
  }

  Color _getTypeColor(String type) {
    return type.toUpperCase() == 'DRL' ? Colors.blue : Colors.green;
  }

  Widget _buildDetailRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Icon(icon, size: 18, color: Colors.grey.shade600),
        const SizedBox(width: 8),
        Text(
          '$label: ',
          style: TextStyle(
            fontSize: 12,
            color: Colors.grey.shade600,
            fontWeight: FontWeight.w500,
          ),
        ),
        Expanded(
          child: Text(
            value,
            style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold),
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
        title: const Text('Hoạt Động Được Gợi Ý'),
        centerTitle: true,
        elevation: 0,
        backgroundColor: Colors.white,
        foregroundColor: Colors.black87,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _errorMessage != null
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.error_outline, size: 48, color: Colors.red.shade300),
                      const SizedBox(height: 16),
                      Text(_errorMessage!, textAlign: TextAlign.center, style: const TextStyle(color: Colors.grey)),
                      const SizedBox(height: 16),
                      ElevatedButton.icon(
                        onPressed: _loadRecommendations,
                        icon: const Icon(Icons.refresh),
                        label: const Text('Thử Lại'),
                      ),
                    ],
                  ),
                )
              : _recommendations.isEmpty
                  ? Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.lightbulb_outline, size: 48, color: Colors.amber.shade300),
                          const SizedBox(height: 16),
                          const Text('Chưa có gợi ý hoạt động nào', style: TextStyle(fontSize: 14, color: Colors.grey)),
                          const SizedBox(height: 8),
                          const Text('Tham gia thêm hoạt động để nhận gợi ý phù hợp', style: TextStyle(fontSize: 12, color: Colors.grey)),
                        ],
                      ),
                    )
                  : ListView.builder(
                      padding: const EdgeInsets.all(16),
                      itemCount: _recommendations.length,
                      itemBuilder: (context, index) {
                        final rec = _recommendations[index];
                        final score = rec['recommendation_score'] as double? ?? 0.0;
                        final scorePercent = (score / 100 * 100).toStringAsFixed(0);

                        return Card(
                          margin: const EdgeInsets.only(bottom: 16),
                          elevation: 2,
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                          child: Column(
                            children: [
                              // Header
                              Container(
                                padding: const EdgeInsets.all(16),
                                decoration: BoxDecoration(
                                  gradient: LinearGradient(
                                    colors: [
                                      _getTypeColor(rec['type']).withOpacity(0.7),
                                      _getTypeColor(rec['type']),
                                    ],
                                  ),
                                  borderRadius: const BorderRadius.only(
                                    topLeft: Radius.circular(12),
                                    topRight: Radius.circular(12),
                                  ),
                                ),
                                child: Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Container(
                                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                                      decoration: BoxDecoration(
                                        color: Colors.white.withOpacity(0.2),
                                        borderRadius: BorderRadius.circular(20),
                                      ),
                                      child: Text(
                                        _getTypeLabel(rec['type']),
                                        style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.white),
                                      ),
                                    ),
                                    Column(
                                      crossAxisAlignment: CrossAxisAlignment.end,
                                      children: [
                                        const Text('Độ Phù Hợp', style: TextStyle(fontSize: 11, color: Colors.white70)),
                                        Text('$scorePercent%', style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white)),
                                      ],
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
                                    Text(rec['ten'] ?? 'N/A', style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold), maxLines: 2, overflow: TextOverflow.ellipsis),
                                    const SizedBox(height: 12),
                                    Container(
                                      padding: const EdgeInsets.all(10),
                                      decoration: BoxDecoration(color: Colors.blue.withOpacity(0.1), borderRadius: BorderRadius.circular(8)),
                                      child: Row(
                                        children: [
                                          Icon(Icons.lightbulb, size: 16, color: Colors.blue.shade600),
                                          const SizedBox(width: 8),
                                          Expanded(
                                            child: Text(
                                              rec['recommendation_reason'] ?? '',
                                              style: TextStyle(fontSize: 12, color: Colors.blue.shade700, fontStyle: FontStyle.italic),
                                              maxLines: 3,
                                              overflow: TextOverflow.ellipsis,
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                    const SizedBox(height: 12),
                                    Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text('Độ Phù Hợp: ${scorePercent}%', style: TextStyle(fontSize: 12, color: Colors.grey.shade600, fontWeight: FontWeight.w500)),
                                        const SizedBox(height: 6),
                                        ClipRRect(
                                          borderRadius: BorderRadius.circular(4),
                                          child: LinearProgressIndicator(
                                            value: score / 100,
                                            minHeight: 6,
                                            backgroundColor: Colors.grey.shade200,
                                            valueColor: AlwaysStoppedAnimation<Color>(
                                              score >= 75 ? Colors.green : (score >= 50 ? Colors.orange : Colors.red),
                                            ),
                                          ),
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 12),
                                    _buildDetailRow(Icons.calendar_today, 'Ngày Tổ Chức', _formatDate(rec['ngay_to_chuc'] ?? '')),
                                    const SizedBox(height: 8),
                                    _buildDetailRow(Icons.location_on, 'Địa Điểm', rec['dia_diem'] ?? 'N/A'),
                                    const SizedBox(height: 8),
                                    _buildDetailRow(
                                      Icons.emoji_events,
                                      'Điểm',
                                      rec['type'].toString().toUpperCase() == 'DRL'
                                          ? '${rec['diem_rl'] ?? 0} RènLuyện'
                                          : '${rec['diem_ctxh'] ?? 0} CTXH',
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
                                  border: Border(top: BorderSide(color: Colors.grey.shade200)),
                                ),
                                child: SizedBox(
                                  width: double.infinity,
                                  child: ElevatedButton.icon(
                                    onPressed: () {
                                      ScaffoldMessenger.of(context).showSnackBar(
                                        SnackBar(content: Text('Mở hoạt động: ${rec['ten']}'), duration: const Duration(seconds: 1)),
                                      );
                                    },
                                    icon: const Icon(Icons.arrow_forward),
                                    label: const Text('Xem Chi Tiết'),
                                    style: ElevatedButton.styleFrom(
                                      backgroundColor: _getTypeColor(rec['type']),
                                      foregroundColor: Colors.white,
                                      padding: const EdgeInsets.symmetric(vertical: 12),
                                    ),
                                  ),
                                ),
                              ),
                            ],
                          ),
                        );
                      },
                    ),
    );
  }
}
