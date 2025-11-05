<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChucVuSinhVien extends Model
{
    protected $table = 'chucvusinhvien';
    protected $primaryKey = 'MaChucVu';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'MSSV',
        'MaLop',
        'MaHocKy',
        'ChucVu',
        'NgayBatDau',
        'NgayKetThuc',
    ];

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    public function lop()
    {
        return $this->belongsTo(Lop::class, 'MaLop', 'MaLop');
    }

    public function hocKy()
    {
        return $this->belongsTo(HocKy::class, 'MaHocKy', 'MaHocKy');
    }
}
