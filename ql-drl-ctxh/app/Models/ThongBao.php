<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model cho bảng 'thongbao'
 */
class ThongBao extends Model
{
    use HasFactory;

    // Chỉ định tên bảng
    protected $table = 'thongbao';

    // Chỉ định khóa chính
    protected $primaryKey = 'MaThongBao';

    // Khóa chính là auto-incrementing
    public $incrementing = true;

    // Kiểu dữ liệu của khóa chính là bigint
    protected $keyType = 'bigint';

    /**
     * Bảng 'thongbao' có cột created_at và updated_at
     * (Vì file SQL của bạn có 2 cột này)
     */
    public $timestamps = true;

    /**
     * Các cột được phép gán hàng loạt (mass-assignable)
     */
    protected $fillable = [
        'TieuDe',
        'NoiDung',
        'MaNguoiTao',
        'LoaiThongBao',
    ];

    /**
     * Tự động chuyển đổi cột 'created_at' thành đối tượng Carbon
     * Điều này cho phép chúng ta dùng $notif->created_at->diffForHumans() trong view
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Lấy thông tin người tạo (Giả sử MaNguoiTao liên kết với TenDangNhap)
     * Bạn cần đảm bảo đã tạo Model 'TaiKhoan'
     */
    public function nguoiTao()
    {
        // Giả sử Model Auth của bạn là 'TaiKhoan' và dùng khóa chính là 'TenDangNhap'
        return $this->belongsTo(TaiKhoan::class, 'MaNguoiTao', 'TenDangNhap');
    }
}