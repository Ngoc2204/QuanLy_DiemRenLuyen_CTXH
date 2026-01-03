import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ApiService {
  static const String baseUrl =
      'http://192.168.1.94:8000/api/v1'; // IP WiFi máy tính
  static const _storage = FlutterSecureStorage();

  static Future<String?> getToken() async {
    return await _storage.read(key: 'auth_token');
  }

  static Future<void> saveToken(String token) async {
    await _storage.write(key: 'auth_token', value: token);
  }

  static Future<void> deleteToken() async {
    await _storage.delete(key: 'auth_token');
  }

  static Future<Map<String, String>> getHeaders({bool needAuth = false}) async {
    Map<String, String> headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };

    if (needAuth) {
      final token = await getToken();
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }

    return headers;
  }

  static Future<http.Response> get(
    String endpoint, {
    bool needAuth = false,
  }) async {
    final url = Uri.parse('$baseUrl$endpoint');
    final headers = await getHeaders(needAuth: needAuth);

    return await http.get(url, headers: headers);
  }

  static Future<http.Response> post(
    String endpoint, {
    required Map<String, dynamic> body,
    bool needAuth = false,
  }) async {
    final url = Uri.parse('$baseUrl$endpoint');
    final headers = await getHeaders(needAuth: needAuth);

    print('[ApiService] POST Request: $url');
    print('[ApiService] Headers: $headers');
    print('[ApiService] Body: $body');
    
    try {
      final response = await http.post(url, headers: headers, body: json.encode(body)).timeout(
        Duration(seconds: 10),
        onTimeout: () {
          print('[ApiService] POST Request Timeout for $url');
          throw Exception('Connection timeout');
        },
      );
      print('[ApiService] POST Response: ${response.statusCode}');
      return response;
    } catch (e) {
      print('[ApiService] POST Error: $e');
      rethrow;
    }
  }

  static Future<http.Response> put(
    String endpoint, {
    required Map<String, dynamic> body,
    bool needAuth = false,
  }) async {
    final url = Uri.parse('$baseUrl$endpoint');
    final headers = await getHeaders(needAuth: needAuth);

    return await http.put(url, headers: headers, body: json.encode(body));
  }

  static Future<http.Response> delete(
    String endpoint, {
    bool needAuth = false,
  }) async {
    final url = Uri.parse('$baseUrl$endpoint');
    final headers = await getHeaders(needAuth: needAuth);

    return await http.delete(url, headers: headers);
  }

  static Map<String, dynamic> parseResponse(http.Response response) {
    try {
      return json.decode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'Có lỗi xảy ra khi xử lý dữ liệu'};
    }
  }

  static bool isSuccessResponse(http.Response response) {
    return response.statusCode >= 200 && response.statusCode < 300;
  }
}
