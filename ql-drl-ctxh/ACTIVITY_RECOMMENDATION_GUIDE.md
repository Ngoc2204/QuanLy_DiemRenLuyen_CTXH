# Hệ Thống Đề Xuất Hoạt Động (Activity Recommendation System)

## Tổng Quan

Hệ thống tính toán đề xuất hoạt động cho sinh viên dựa trên:
- **Điểm rèn luyện (RL)**: Nếu < 60 → Ưu tiên cao
- **Điểm CTXH**: Nếu < 170 → Đề xuất hoạt động CTXH
- **Thời gian tốt nghiệp**: Nếu sắp tốt nghiệp (< 3 tháng) nhưng CTXH chưa đạt → Ưu tiên cao
- **Sở thích sinh viên**: Hoạt động phù hợp với sở thích được ưu tiên
- **Loại hoạt động**: Khớp với `category` của hoạt động

## Cấu Trúc Dữ Liệu

### 1. Bảng `activity_recommendations`
```sql
- id: ID duy nhất
- MSSV: Mã sinh viên
- MaHoatDong: Mã hoạt động
- activity_type: 'drl' hoặc 'ctxh'
- recommendation_score: Điểm (0-100)
- recommendation_reason: Lý do ('low_rl_score', 'incomplete_ctxh', 'graduating_soon', 'preference_match', 'red_address')
- priority: Mức ưu tiên (0-10, cao hơn = ưu tiên hơn)
- recommended_at: Thời gian tính toán
- viewed_at: Thời gian sinh viên xem
```

### 2. Trường mới trong Models

**SinhVien**:
- `SoThich` (đã có): String, lưu sở thích ("Tình nguyện,Thể thao,Hội thảo")
- `ThoiGianTotNghiepDuKien`: Date, dự kiến thời gian tốt nghiệp

**HoatDongDRL & HoatDongCTXH**:
- `category`: String, loại/danh mục ("Tình nguyện", "Hội thảo", "Thể thao", etc.)

## Cách Sử Dụng

### 1. Chạy Migration
```bash
php artisan migrate
```

Điều này sẽ:
- Thêm cột `category` vào `hoatdongdrl` và `hoatdongctxh`
- Thêm cột `ThoiGianTotNghiepDuKien` vào `sinhvien`
- Tạo bảng `activity_recommendations`

### 2. Tính Toán Đề Xuất
```bash
php artisan cluster:generate
```

Command này sẽ:
- Lấy tất cả sinh viên
- Tính toán đề xuất cho mỗi sinh viên dựa trên tiêu chí
- Lưu vào bảng `activity_recommendations`

**Cách sử dụng tự động (Schedule)**:
Thêm vào `app/Console/Kernel.php`:
```php
$schedule->command('cluster:generate')
         ->daily()  // Chạy hàng ngày
         ->at('02:00'); // Lúc 02:00 sáng
```

### 3. Xem Đề Xuất
Sinh viên truy cập: `/sinhvien/de-xuat-hoat-dong`

## Thuật Toán Tính Điểm Đề Xuất

### Priority Levels
| Level | Tình Huống |
|-------|-----------|
| 10 | Điểm RL < 60 |
| 9 | Sắp tốt nghiệp (< 3 tháng) + CTXH < 170 |
| 7 | Địa chỉ đỏ (nếu CTXH < 170) |
| 6 | CTXH < 170 |
| 5 | Phù hợp sở thích |

### Công Thức Điểm Số
```
Score = Base Score + Bonus
- Base Score:
  - DRL (RL < 60): 70
  - CTXH thường: 50
  - Địa chỉ đỏ: 60

- Bonus:
  - Phù hợp sở thích: +15
  - CTXH chưa đủ: +30
  - Sắp tốt nghiệp: +20
  - Địa chỉ đỏ (khi CTXH < 170): +25

Max Score: 100
```

## Ví Dụ

### Sinh viên A
- Điểm RL: 50 (< 60) → **Ưu tiên cao**
- Điểm CTXH: 120 (< 170)
- Sở thích: "Tình nguyện"
- Tốt nghiệp: 2026

**Đề xuất**:
1. Hoạt động DRL "Hội đồng tiên phong" (category: "Tình nguyện")
   - Score: 70 (base) + 15 (preference) = 85
   - Priority: 10
   - Reason: low_rl_score, preference_match

2. Hoạt động CTXH "Tình nguyện xanh"
   - Score: 50 (base) + 30 (incomplete) + 15 (preference) = 95
   - Priority: 6
   - Reason: incomplete_ctxh, preference_match

### Sinh viên B
- Điểm RL: 65 (✓)
- Điểm CTXH: 160 (< 170)
- Sở thích: "Thể thao"
- Tốt nghiệp: 2025-01-15 (< 3 tháng từ bây giờ)

**Đề xuất**:
1. Địa chỉ đỏ "Vì cộng đồng"
   - Score: 60 (base) + 25 (incomplete) + 15 (graduating) = 100
   - Priority: 9
   - Reason: red_address, incomplete_ctxh, graduating_soon

## API Endpoints

### Lấy danh sách đề xuất
```
GET /sinhvien/de-xuat-hoat-dong
```

### Xem chi tiết 1 đề xuất
```
GET /sinhvien/de-xuat-hoat-dong/{id}
```

### Lấy thông tin hoạt động (API)
```
GET /sinhvien/api/activity/{id}/{type}
Parameters:
- id: MaHoatDong
- type: 'drl' hoặc 'ctxh'
Response: JSON hoạt động
```

## Tối Ưu Hóa

### Performance
- Lệnh `cluster:generate` nên chạy vào giờ thấp điểm (02:00 sáng)
- Bảng `activity_recommendations` có index trên (MSSV, priority, score)
- Khuyến khích dùng `paginate(10)` thay vì lấy toàn bộ

### Dữ Liệu
- Cập nhật `category` cho tất cả hoạt động đã tạo
- Nhập `SoThich` cho sinh viên (tách bằng dấu phẩy)
- Cập nhật `ThoiGianTotNghiepDuKien` khi có quyết định thay đổi

## Troubleshooting

### Không có đề xuất
- Kiểm tra: Sinh viên có được load bằng `whereHas('lop')` không?
- Kiểm tra: `DiemRenLuyen` và `DiemCTXH` có tồn tại không?

### Điểm RL không cập nhật
- Run migration: `php artisan migrate`
- Đảm bảo dữ liệu tồn tại ở `diem_ren_luyen` table

### Score quá cao/thấp
- Điều chỉnh Base Score và Bonus trong `ActivityRecommendationService.php`
- Test với sinh viên cụ thể và xem log

## Mở Rộng

### Thêm Tiêu Chí Mới
Sửa `ActivityRecommendationService::generateRecommendationForStudent()`:

```php
// Thêm điểm GPA
if ($student->gpa < 2.5) {
    $score += 10;
    $reason[] = 'low_gpa';
}
```

### Thay Đổi Trọng Số
Tất cả base score và bonus nằm ở method `generateRecommendationForStudent()`, có thể tùy chỉnh.

## Support
- Check logs: `storage/logs/laravel.log`
- Run command with verbose: `php artisan cluster:generate --verbose`
