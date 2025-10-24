<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DangKyHoatDongDRL extends Model
{
    protected $table = 'dangkyhoatdongdrl';
    protected $primaryKey = 'MaDangKy';
    public $incrementing = false;
    public $timestamps = false;
    protected $dates = ['NgayDangKy'];

    protected $fillable = ['MaDangKy', 'MSSV', 'MaHoatDong', 'NgayDangKy', 'TrangThaiDangKy	'];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    public function hoatdong()
    {
        return $this->belongsTo(HoatDongDRL::class, 'MaHoatDong', 'MaHoatDong');
    }
}
