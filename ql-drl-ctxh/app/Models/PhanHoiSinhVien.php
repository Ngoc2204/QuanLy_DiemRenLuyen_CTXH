<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhanHoiSinhVien extends Model
{
    protected $table = 'phanhoisinhvien';
    protected $primaryKey = 'MaPhanHoi';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaPhanHoi', 'MSSV', 'NoiDung', 'NgayGui', 'TrangThai'];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }
}
