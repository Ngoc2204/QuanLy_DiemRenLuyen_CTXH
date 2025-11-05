<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhanBoSoLuong extends Model
{
    use HasFactory;

    protected $table = 'phan_bo_so_luong';

    protected $fillable = [
        'MaHoatDong',
        'MaKhoa',
        'SoLuongPhanBo',
    ];

    // Quan hệ: Thuộc về 1 hoạt động DRL
    public function hoatDong()
    {
        return $this->belongsTo(HoatDongDrl::class, 'MaHoatDong', 'MaHoatDong');
    }

    // Quan hệ: Thuộc về 1 khoa
    public function khoa()
    {
        return $this->belongsTo(Khoa::class, 'MaKhoa', 'MaKhoa');
    }
}