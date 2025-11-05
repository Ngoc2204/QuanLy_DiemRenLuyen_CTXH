<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    protected $table = 'sinhvien';
    protected $primaryKey = 'MSSV';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MSSV', 'HoTen', 'Email', 'SDT', 'NgaySinh', 'GioiTinh',
        'MaLop', 'ThoiGianTotNghiepDuKien', 'SoThich'
    ];

    public function lop()
    {
        return $this->belongsTo(Lop::class, 'MaLop', 'MaLop');
    }

    public function diemrenluyen()
    {
        return $this->hasMany(DiemRenLuyen::class, 'MSSV', 'MSSV');
    }

    public function diemctxh()
    {
        return $this->hasOne(DiemCTXH::class, 'MSSV', 'MSSV');
    }

    public function taikhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'MSSV', 'TenDangNhap');
    }
    
    public function chucVus()
    {
        return $this->hasMany(ChucVuSinhVien::class, 'MSSV', 'MSSV');
    }
}
