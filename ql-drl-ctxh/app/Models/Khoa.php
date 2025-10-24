<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    protected $table = 'khoa';
    protected $primaryKey = 'MaKhoa';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaKhoa', 'TenKhoa'];

    public function lop()
    {
        return $this->hasMany(Lop::class, 'MaKhoa', 'MaKhoa');
    }



    public function nhanvien()
    {
        return $this->hasMany(NhanVien::class, 'MaKhoa', 'MaKhoa');
    }
}
