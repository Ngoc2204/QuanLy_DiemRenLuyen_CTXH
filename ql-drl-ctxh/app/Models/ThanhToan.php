<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    use HasFactory;

    /**
     * Tên bảng trong CSDL
     */
    protected $table = 'thanh_toan';

    /**
     * Các trường được phép gán hàng loạt (Mass Assignable)
     */
    protected $fillable = [
        'MSSV',
        'TongTien',
        'TrangThai',
        'PhuongThuc',
        'MaGiaoDich',
        'NgayThanhToan',
    ];

    /**
     * Ép kiểu dữ liệu (Casting)
     * Giúp cột 'NgayThanhToan' luôn là đối tượng Carbon (ngày tháng)
     * và 'TongTien' là số nguyên (vì ta set decimal = 0)
     */
    protected $casts = [
        'NgayThanhToan' => 'datetime',
        'TongTien' => 'decimal:0',
    ];

    /**
     * Mối quan hệ: Một hóa đơn thuộc về MỘT sinh viên.
     */
    public function sinhVien()
    {
        // 'MSSV' ở bảng 'thanh_toan' liên kết với 'MSSV' ở bảng 'sinhvien'
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    /**
     * Mối quan hệ: Một hóa đơn có thể có NHIỀU đơn đăng ký.
     * (Mô hình 1 Hóa đơn - Nhiều Đơn đăng ký)
     */
    public function dangKyHoatDong()
    {
        return $this->hasOne(DangKyHoatDongCtxh::class, 'thanh_toan_id', 'id');
    }
}