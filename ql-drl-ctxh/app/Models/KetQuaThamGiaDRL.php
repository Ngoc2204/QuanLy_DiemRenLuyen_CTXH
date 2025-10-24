<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KetQuaThamGiaDRL extends Model
{
    protected $table = 'ketquathamgiadrl';
    protected $primaryKey = 'MaKetQua';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaKetQua', 'MSSV', 'MaHoatDong', 'Diem', 'TrangThai'
    ];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    public function hoatdong()
    {
        return $this->belongsTo(HoatDongDRL::class, 'MaHoatDong', 'MaHoatDong');
    }
}
