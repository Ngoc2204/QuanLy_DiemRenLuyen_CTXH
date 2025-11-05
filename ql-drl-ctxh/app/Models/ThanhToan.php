<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    use HasFactory;

    protected $table = 'thanhtoans';

    /**
     * SỬA 1: Cập nhật fillable theo CSDL
     * Bỏ MSSV, MaHoatDong
     * Thêm MaDangKy
     */
    protected $fillable = [
        'MaDangKy', // <-- Thêm
        'SoTien',
        'TrangThai',
        'MaGiaoDich',
        'PhuongThuc',
    ];

    /**
     * SỬA 2: Đổi sang 'belongsTo'
     * Một hóa đơn "thuộc về" một đơn đăng ký.
     */
    public function dangKyHoatDong()
    {
        // 'MaDangKy' là khóa ngoại trong bảng 'thanhtoans'
        // 'MaDangKy' là khóa chính trong bảng 'dangkyhoatdongctxh'
        return $this->belongsTo(DangKyHoatDongCTXH::class, 'MaDangKy', 'MaDangKy');
    }

}