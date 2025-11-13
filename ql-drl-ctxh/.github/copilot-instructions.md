# AI Coding Agent Instructions - Student Activity Management System

This is a Vietnamese university system for managing "Dìu Rèn Luyện" (DRL - Moral Development) and "Công Tác Xã Hội" (CTXH - Social Work) activities with attendance tracking and point scoring.

## Architecture Overview

**Tech Stack**: Laravel 12, Vite (TailwindCSS 4), PHP 8.2, Eloquent ORM

**Key Subsystems**:
- **Authentication**: Custom `TaiKhoan` model (not Laravel User) with roles: Admin, NhanVien (Staff), GiangVien (Lecturer), SinhVien (Student)
- **Activity Management**: DRL and CTXH activities with QR code check-in/check-out attendance system
- **Point System**: `QuyDinhDiemRL` and `QuyDinhDiemCTXH` (rules) → `DiemRenLuyen` and `DiemCTXH` (student scores)
- **Special Feature**: "Địa Chỉ Đỏ" (Red Address) - batch activities under campaigns (`DotDiaChiDo` period + `DiaDiemDiaChiDo` locations)
- **Payment System**: `ThanhToan` model for registration fees

## Data Model Patterns

**Critical Convention**: Models use Vietnamese primary keys with `public $incrementing = false`:
```php
// Example: app/Models/HoatDongCTXH.php
protected $table = 'hoatdongctxh';
protected $primaryKey = 'MaHoatDong';  // NOT 'id'
public $incrementing = false;
protected $keyType = 'string';
```

**Eloquent Relationships** use compound keys consistently:
- `belongsToMany()` for many-to-many (e.g., `SinhVien` ↔ `HoatDongCTXH` through `DangKyHoatDongCTXH`)
- `belongsTo()` for foreign keys (always explicit keys: `foreignKey, ownerKey`)
- `hasMany()` for one-to-many
- **Query scopes** used for business logic (see `DangKyHoatDongCTXH::kiemTraTrungDiaDiem()` example)

**Timestamps**: Most models have `public $timestamps = false` - dates are explicit fields (ThoiGianBatDau, NgayDangKy, etc.)

## Development Workflow

**Setup**:
```bash
composer setup          # Runs full initialization
npm run dev             # Start Vite dev server with hot reload
composer run dev        # Starts Laravel + queue + pail + Vite concurrently
```

**Testing**: `composer run test` (runs migration clear + phpunit)

**Key Commands**:
- Migrations: `php artisan migrate --force`
- Tinker: `php artisan tinker` (craft DB queries interactively)
- Queue: `php artisan queue:listen --tries=1` (for background jobs if added)
- Logs: `php artisan pail --timeout=0` (real-time log streaming)

## URL & Route Patterns

**Role-Based Routing** (see `routes/web.php`):
- **Admin**: `/admin/*` - resource CRUD for users, departments, semesters, rules
- **NhanVien**: `/nhanvien/*` - activity creation, attendance management, batch approval
- **GiangVien**: `/giangvien/*` - class view-only, attendance reports, distribute activities
- **SinhVien**: `/sinhvien/*` - register activities, scan QR, view scores, manage payments

**Dynamic QR Routes**: `/sinhvien/scan/{token}` (base64 encoded) for attendance via mobile

## Project-Specific Patterns

**1. QR Token Generation**:
- Used for check-in/check-out without database lookup
- Tokens stored in `HoatDongCTXH.CheckInToken`, `CheckOutToken`, `TokenExpiresAt`
- Controller: `NhanVienHoatDongCTXHController::generateQrTokens()` POST method

**2. Batch "Địa Chỉ Đỏ" Creation**:
- Special form `/nhanvien/create-batch-diachido` creates multiple activities at once
- Links to `DotDiaChiDo` (campaign periods) and `DiaDiemDiaChiDo` (venue list)
- Method: `storeBatchDiaChiDo()` - validates no duplicate registrations same venue/campaign

**3. Registration Status Pipeline**:
```
ChưaDuyệt → DaDuyet → ChoThanhToan → (optional payment) → ThamGia
```
- Staff must **batch approve** registrations: `DuyetDangKyController::batchApprove()`
- Students cannot register same activity twice (scope: `kiemTraTrungDiaDiem()`)

**4. Notification System** (AppServiceProvider):
- View composer binds `$unreadCount` and `$notifications` to `layouts.sinhvien`
- Counts approved activities in next 7 days
- Queries check `Schema::hasTable()` for safety (tables may not exist in fresh install)

## File Organization

- **Models**: `app/Models/*.php` - one file per model, no trait bloat
- **Controllers**: `app/Http/Controllers/{Admin,NhanVien,GiangVien,SinhVien}/`
- **Views**: `resources/views/{admin,nhanvien,sinhvien,giangvien}/` (Blade templates)
- **Routes**: `routes/web.php` (all routes, grouped by role middleware)
- **Exports**: `app/Exports/LopDiemExport.php` for Excel (uses `maatwebsite/excel`)

## When Adding Features

1. **New Activity Type** → Create model, migration, controller in role folder, add routes to `/web.php`
2. **New Role** → Add `TaiKhoan.VaiTro` enum value, create controller namespace, protect routes with `middleware(['auth'])`
3. **Attendance/Scoring** → Use `DangKyHoatDong*` as pivot, update `Diem*` after finalization
4. **Batch Operations** → Follow `storeBatchDiaChiDo()` pattern (validate, loop, log errors individually)

## Integration Points

- **Queue**: If heavy processing needed, dispatch jobs to queue listener (already running in `dev` script)
- **Mail**: Configured via `config/mail.php` (not in use yet, but ready)
- **Excel Export**: `maatwebsite/excel` v3.1 loaded; see `LopDiemExport` for usage pattern
- **QR Codes**: `simplesoftwareio/simple-qrcode` v4.2 (generates SVG via `/qr-generator` endpoint)
- **Captcha**: Custom simple captcha in `AuthController` (session-based, not image library)

## Common Gotchas

- **Auth Guard**: Uses `TaiKhoan::class` not Laravel's User model (check `config/auth.php`)
- **Pivot Data**: Always use `->withPivot()` when querying belongsToMany (see `sinhVienDangKy()`)
- **Status Strings**: Use exact Vietnamese strings: "Đã duyệt", "ChưaDuyệt", "DaDuyet" (inconsistent casing exists)
- **Timezone**: Carbon locale set to 'vi' (Vietnamese) in `AppServiceProvider::boot()`
- **Pagination**: Default 10 items per page (set in controllers, not config)
