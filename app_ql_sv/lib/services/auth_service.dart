import 'api_service.dart';

class AuthService {
  static Future<Map<String, dynamic>> login(
    String username,
    String password,
  ) async {
    try {
      final response = await ApiService.post(
        '/login',
        body: {'username': username, 'password': password},
      );

      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        // Lưu token
        final token = data['data']['token'];
        await ApiService.saveToken(token);

        return {
          'success': true,
          'message': data['message'],
          'user': data['data']['user'],
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Đăng nhập thất bại',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> logout() async {
    try {
      final response = await ApiService.post(
        '/logout',
        body: {},
        needAuth: true,
      );

      // Xóa token cục bộ dù có lỗi hay không
      await ApiService.deleteToken();

      if (ApiService.isSuccessResponse(response)) {
        return {'success': true, 'message': 'Đăng xuất thành công'};
      } else {
        return {'success': true, 'message': 'Đăng xuất thành công'};
      }
    } catch (e) {
      // Vẫn xóa token cục bộ
      await ApiService.deleteToken();
      return {'success': true, 'message': 'Đăng xuất thành công'};
    }
  }

  static Future<Map<String, dynamic>> getCurrentUser() async {
    try {
      final response = await ApiService.get('/user', needAuth: true);
      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'user': data['data']['user']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể lấy thông tin người dùng',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<bool> isLoggedIn() async {
    final token = await ApiService.getToken();
    if (token == null) return false;

    // Kiểm tra token có còn hợp lệ không
    final userResult = await getCurrentUser();
    return userResult['success'] == true;
  }

  static Future<Map<String, dynamic>> refreshToken() async {
    try {
      final response = await ApiService.post(
        '/refresh',
        body: {},
        needAuth: true,
      );

      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        // Lưu token mới
        final newToken = data['data']['token'];
        await ApiService.saveToken(newToken);

        return {'success': true, 'message': 'Làm mới token thành công'};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể làm mới token',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> updateProfile({
    String? hoTen,
    String? email,
    String? soDienThoai,
    String? diaChi,
  }) async {
    try {
      Map<String, dynamic> body = {};

      if (hoTen != null) body['HoTen'] = hoTen;
      if (email != null) body['email'] = email;
      if (soDienThoai != null) body['SoDienThoai'] = soDienThoai;
      if (diaChi != null) body['DiaChi'] = diaChi;

      final response = await ApiService.put(
        '/user/profile',
        body: body,
        needAuth: true,
      );

      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {
          'success': true,
          'message': data['message'],
          'user': data['data']['user'],
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể cập nhật thông tin',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }

  static Future<Map<String, dynamic>> changePassword({
    required String currentPassword,
    required String newPassword,
    required String confirmPassword,
  }) async {
    try {
      final response = await ApiService.post(
        '/user/change-password',
        body: {
          'current_password': currentPassword,
          'new_password': newPassword,
          'new_password_confirmation': confirmPassword,
        },
        needAuth: true,
      );

      final data = ApiService.parseResponse(response);

      if (ApiService.isSuccessResponse(response) && data['success'] == true) {
        return {'success': true, 'message': data['message']};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Không thể đổi mật khẩu',
          'errors': data['errors'],
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Không thể kết nối đến server'};
    }
  }
}
