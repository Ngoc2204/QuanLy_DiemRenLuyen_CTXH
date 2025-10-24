<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemRenLuyen extends Model
{
    protected $table = 'diemrenluyen';
    protected $primaryKey = 'MaDRL';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaDRL', 'MSSV', 'MaHocKy', 'TongDiem', 'XepLoai'];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    public function hocky()
    {
        return $this->belongsTo(HocKy::class, 'MaHocKy', 'MaHocKy');
    }
}
