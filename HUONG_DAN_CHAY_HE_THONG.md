HƯỚNG DẪN CÀI ĐẶT VÀ VẬN HÀNH HỆ THỐNG QUẢN LÝ DRL & CTXH

1. Yêu Cầu Hệ Thống (Prerequisites)
Để chạy được dự án, máy tính cần cài đặt các công cụ sau:

Backend (Web Server & API)
    PHP: Phiên bản 8.2 trở lên.

    Database: MySQL 5.7+ hoặc MariaDB 10.3+.

    Composer: 2.0+ (Quản lý thư viện PHP).

    Node.js: 16+ (Để chạy Vite dev server cho giao diện web).

    Git: Để quản lý source code.

Mobile App
    Flutter SDK: 3.7.2 trở lên.

    Dart SDK: Tự động đi kèm với Flutter.

    Android Studio: Cần thiết để có máy ảo (Emulator) và Android SDK.

    Visual Studio Code: (Khuyên dùng) Editor chính.

2. Cấu Trúc Thư Mục Dự Án
Đảm bảo source code được đặt theo cấu trúc sau để các lệnh bên dưới hoạt động chính xác:

D:\HT_QuanLy_DRL_CTXH\
├── ql-drl-ctxh/              ← Source Code Backend (Laravel)
│   ├── app/
│   ├── .env                  ← File cấu hình (quan trọng)
│   ├── composer.json
│   └── ...
│
├── app_ql_sv/                ← Source Code Mobile (Flutter)
│   ├── lib/
│   ├── pubspec.yaml
│   └── ...
│
└── ql_drl_ctxh.sql           ← File Backup Database (Dữ liệu mẫu)

3. Cài Đặt Backend (Laravel)

Bước 3.1: Cài đặt thư viện
Mở Terminal (Command Prompt hoặc PowerShell) và chạy:

cd D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh

# Cài đặt các gói phụ thuộc PHP
composer install

# Cài đặt các gói frontend (nếu cần build giao diện web admin)
npm install

Bước 3.2: Cấu hình môi trường (.env)
Copy file .env.example thành .env:

copy .env.example .env

Mở file .env bằng Text Editor và cập nhật thông tin Database:

APP_NAME=QL_DRL_CTXH
APP_ENV=local
APP_KEY=                    # Để trống, sẽ tạo ở bước sau
APP_DEBUG=true
APP_URL=http://localhost:8000

# Cấu hình Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ql_drl_ctxh     # Tên database sẽ tạo
DB_USERNAME=root            # User mặc định của XAMPP/MySQL
DB_PASSWORD=                # Để trống nếu dùng XAMPP mặc định

Bước 3.3: Tạo Application Key

php artisan key:generate

Bước 3.4: Tạo Symlink cho ảnh (Storage)
Để hiển thị ảnh từ storage ra public:

php artisan storage:link

4. Cài Đặt Database
Bước 4.1: Tạo Database rỗng
Bạn có thể dùng phpMyAdmin hoặc MySQL Workbench để tạo database mới tên là ql_drl_ctxh (Encoding: utf8mb4_unicode_ci).

Hoặc dùng lệnh:

mysql -u root -e "CREATE DATABASE IF NOT EXISTS ql_drl_ctxh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

Bước 4.2: Import dữ liệu (Ưu tiên dùng file .sql có sẵn)

Dùng phpMyAdmin

Truy cập http://localhost/phpmyadmin

Chọn database ql_drl_ctxh

Vào tab Import -> Chọn file ql_drl_ctxh.sql -> Nhấn Go/Import.

5. Cài Đặt Mobile (Flutter)
Bước 5.1: Kiểm tra môi trường

cd D:\HT_QuanLy_DRL_CTXH\app_ql_sv
flutter doctor

Bước 5.2: Cài đặt thư viện

flutter pub get

Bước 5.3: Cấu hình API Endpoint (QUAN TRỌNG ⚠️)
Mobile App chạy trên thiết bị thật hoặc máy ảo không thể hiểu localhost là máy tính của bạn. Bạn cần dùng địa chỉ IP LAN.

Lấy IP máy tính:

Windows: Mở CMD gõ ipconfig (Tìm dòng IPv4 Address, ví dụ: 192.168.1.50).

Mac/Linux: Gõ ifconfig.

Cập nhật code: Mở file lib/services/api_service.dart (hoặc nơi chứa hằng số URL):

// Thay thế 192.168.x.x bằng IP vừa tìm được ở trên
static const String baseUrl = 'http://192.168.1.50:8000/api';

6. Vận Hành Hệ Thống (Run)
Cần mở 3 Terminal riêng biệt để chạy toàn bộ hệ thống.

Terminal 1: Chạy Backend (Laravel)

cd D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh
php artisan serve --host=0.0.0.0 --port=8000

Terminal 2: Chạy Web 
cd D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh
php artisan serve

Terminal 3: Chạy Mobile App (Flutter)
Kết nối điện thoại hoặc bật Android Emulator, sau đó chạy:

cd D:\HT_QuanLy_DRL_CTXH\app_ql_sv
flutter run

7. Tài Khoản Kiểm Thử (Test Accounts)
Sử dụng các tài khoản sau để đăng nhập vào App hoặc Web Admin:

Vai Trò (Role)	Tên Đăng Nhập	Mật Khẩu	
Sinh Viên	    2001223002	    123456	
NhanVien	    NV001	        123456	
Giảng viên	    GV001	        123456
ADMIN           ADMIN           123456

8. Troubleshooting (Khắc phục lỗi thường gặp)
1. Lỗi: Connection Refused / Network Error trên App

Nguyên nhân: App không kết nối được tới Server Laravel.

Khắc phục:

Kiểm tra xem Terminal 1 (Laravel) còn chạy không.

Kiểm tra lại IP trong api_service.dart. IP máy tính có thể bị đổi khi reset modem.

Tắt tường lửa (Firewall) trên Windows hoặc cho phép port 8000 đi qua.

Điện thoại và máy tính phải bắt chung 1 mạng WiFi.

2. Lỗi: SQLSTATE[HY000] [2002] No such file or directory

Khắc phục: MySQL chưa chạy. Hãy bật XAMPP và Start module MySQL.

3. Lỗi: Class '...' not found khi chạy Laravel

Khắc phục: Chạy lại lệnh composer dump-autoload và php artisan config:clear.

4. Lỗi hình ảnh không hiển thị trên App

Khắc phục: Đảm bảo đã chạy php artisan storage:link và URL ảnh trong database phải bắt đầu bằng IP (http://192.168.x.x...) chứ không phải localhost.