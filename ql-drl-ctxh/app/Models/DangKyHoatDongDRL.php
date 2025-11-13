<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DangKyHoatDongDRL extends Model
{
    protected $table = 'dangkyhoatdongdrl';
    protected $primaryKey = 'MaDangKy';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $casts = [
        'NgayDangKy'   => 'datetime',
        'CheckInAt'    => 'datetime',
        'CheckOutAt'   => 'datetime',
    ];

    protected $fillable = ['MSSV', 'MaHoatDong', 'NgayDangKy', 'TrangThaiDangKy'];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    public function hoatdong()
    {
        return $this->belongsTo(HoatDongDRL::class, 'MaHoatDong', 'MaHoatDong');
    }
}
