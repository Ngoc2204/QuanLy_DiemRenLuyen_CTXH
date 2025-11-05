<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HocKy extends Model
{
    protected $table = 'hocky';
    protected $primaryKey = 'MaHocKy';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaHocKy', 'TenHocKy', 'NgayBatDau', 'NgayKetThuc', 'MaNamHoc'];

    public function diemrenluyens()
    {
        return $this->hasMany(DiemRenLuyen::class, 'MaHocKy', 'MaHocKy');
    }

    public function hoatDongDrl()
    {
        return $this->hasMany(HoatDongDrl::class, 'MaHocKy', 'MaHocKy');
    }

    public function namhoc()
    {
        return $this->belongsTo(NamHoc::class, 'MaNamHoc', 'MaNamHoc');
    }

    public function chucvusinhviens()
    {
        return $this->hasMany(ChucVuSinhVien::class, 'MaHocKy', 'MaHocKy');
    }
    public function bangdiemhockys()
    {
        return $this->hasMany(BangDiemHocKy::class, 'MaHocKy', 'MaHocKy');
    }
}
