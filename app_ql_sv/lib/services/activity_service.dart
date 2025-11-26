import 'api_service.dart';

class ActivityService {
  static Future<Map<String, dynamic>> getAllActivities() async {
    try {
      print('[ActivityService] Fetching all activities...');
      final response = await ApiService.get('/activities', needAuth: true);
      final data = ApiService.parseResponse(response);
      print('[ActivityService] Response: $response');

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'data': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy danh sách hoạt động',
        };
      }
    } catch (e) {
      print('[ActivityService] Error: $e');
      return {'success': false, 'message': 'Không thể kết nối đến server: $e'};
    }
  }

  static Future<Map<String, dynamic>> getAvailableDRLActivities() async {
    try {
      final response = await ApiService.get(
        '/activities/drl/available',
        needAuth: true,
      );
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'activities': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy hoạt động DRL',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getAvailableCTXHActivities() async {
    try {
      final response = await ApiService.get(
        '/activities/ctxh/available',
        needAuth: true,
      );
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'activities': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy hoạt động CTXH',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> registerDRLActivity(
    String activityId,
  ) async {
    try {
      final response = await ApiService.post(
        '/activities/drl/$activityId/register',
        body: {},
        needAuth: true,
      );

      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'message': data['message']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể đăng ký hoạt động',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> registerCTXHActivity(
    String activityId,
  ) async {
    try {
      final response = await ApiService.post(
        '/activities/ctxh/$activityId/register',
        body: {},
        needAuth: true,
      );

      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'message': data['message']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể đăng ký hoạt động',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> unregisterDRLActivity(
    String activityId,
  ) async {
    try {
      final response = await ApiService.delete(
        '/activities/drl/$activityId/unregister',
        needAuth: true,
      );

      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'message': data['message']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể hủy đăng ký hoạt động',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> unregisterCTXHActivity(
    String activityId,
  ) async {
    try {
      final response = await ApiService.delete(
        '/activities/ctxh/$activityId/unregister',
        needAuth: true,
      );

      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'message': data['message']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể hủy đăng ký hoạt động',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getMyRegistrations() async {
    try {
      final response = await ApiService.get(
        '/my-registrations',
        needAuth: true,
      );
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'data': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy danh sách đăng ký',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getDashboardData() async {
    try {
      final response = await ApiService.get('/dashboard', needAuth: true);
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'data': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy dữ liệu dashboard',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getWeeklySchedule() async {
    try {
      final response = await ApiService.get('/schedule/weekly', needAuth: true);
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'data': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy lịch tuần',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getDRLScores() async {
    try {
      final response = await ApiService.get('/scores/drl', needAuth: true);
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'scores': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy điểm DRL',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> scanQRCode(String qrData) async {
    try {
      final response = await ApiService.post(
        '/attendance/scan',
        body: {'qr_data': qrData},
        needAuth: true,
      );

      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {
          'success': true,
          'message': data['message'],
          'data': data['data'],
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể điểm danh',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getAttendanceHistory() async {
    try {
      final response = await ApiService.get(
        '/attendance/history',
        needAuth: true,
      );
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'data': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy lịch sử điểm danh',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getDiemRenLuyenData() async {
    try {
      final response = await ApiService.get('/diem-ren-luyen', needAuth: true);
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'data': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy dữ liệu điểm rèn luyện',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getDiemCTXHData() async {
    try {
      final response = await ApiService.get('/diem-ctxh', needAuth: true);
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'data': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy dữ liệu điểm CTXH',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> cancelRegistration(String endpoint) async {
    try {
      final response = await ApiService.delete(
        endpoint,
        needAuth: true,
      );
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'message': data['message']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể hủy đăng ký',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getPendingPayments() async {
    try {
      final response = await ApiService.get(
        '/payments/pending',
        needAuth: true,
      );
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'data': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy danh sách thanh toán',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getPaymentDetails(String paymentId) async {
    try {
      final response = await ApiService.get(
        '/payments/$paymentId',
        needAuth: true,
      );
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'data': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy chi tiết thanh toán',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> confirmPaymentMethod(
    String paymentId,
    String method,
  ) async {
    try {
      final response = await ApiService.post(
        '/payments/$paymentId/confirm-method',
        body: {'method': method},
        needAuth: true,
      );
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {
          'success': true,
          'message': data['message'],
          'data': data['data'],
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể xác nhận thanh toán',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getPaymentByRegistration(
    String registrationId,
  ) async {
    try {
      final response = await ApiService.get(
        '/registrations/$registrationId/payment',
        needAuth: true,
      );
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'data': data['data']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không tìm thấy thanh toán',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> getRecommendations() async {
    try {
      final response = await ApiService.get('/recommendations', needAuth: true);
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {
          'success': true,
          'data': data['data'] ?? [],
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy gợi ý hoạt động',
        };
      }
    } catch (e) {
      print('[ActivityService] getRecommendations error: $e');
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }
}
