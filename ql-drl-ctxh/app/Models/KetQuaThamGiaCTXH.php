<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KetQuaThamGiaCTXH extends Model
{
    protected $table = 'ketquathamgiactxh';
    protected $primaryKey = 'MaKetQua';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaKetQua', 'MSSV', 'MaHoatDong', 'DiemCong', 'TrangThai'
    ];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    public function hoatdong()
    {
        return $this->belongsTo(HoatDongCTXH::class, 'MaHoatDong', 'MaHoatDong');
    }
}
