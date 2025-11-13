<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemRenLuyen extends Model
{
    
    protected $table = 'diemrenluyen';
    protected $primaryKey = 'MaDiemRenLuyen'; 
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'MSSV',
        'MaHocKy',
        'TongDiem',
        'XepLoai',
        'NgayCapNhat',
    ];

    protected $casts = [
        'NgayCapNhat' => 'datetime',
    ];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    public function hocky()
    {
        return $this->belongsTo(HocKy::class, 'MaHocKy', 'MaHocKy');
    }
}
