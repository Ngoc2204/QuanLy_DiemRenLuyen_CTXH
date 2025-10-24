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

    public function diemrenluyen()
    {
        return $this->hasMany(DiemRenLuyen::class, 'MaHocKy', 'MaHocKy');
    }

    public function hoatDongDrl()
    {
        return $this->hasMany(HoatDongDrl::class, 'MaHocKy', 'MaHocKy');
    }
}
