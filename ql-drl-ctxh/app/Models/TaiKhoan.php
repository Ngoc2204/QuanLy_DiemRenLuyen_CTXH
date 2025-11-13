<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class TaiKhoan extends Authenticatable
{
    protected $table = 'taikhoan';
    protected $primaryKey = 'TenDangNhap';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // Các trường có thể gán hàng loạt (mass assignable)
    protected $fillable = ['TenDangNhap', 'MatKhau', 'VaiTro', 'Avatar'];

    protected $hidden = ['MatKhau'];

    public function getAuthPassword(): string
    {
        return $this->MatKhau;
    }

    public function sinhvien()
    {
        return $this->hasOne(SinhVien::class, 'MSSV', 'TenDangNhap');
    }

    public function giangvien()
    {
        return $this->hasOne(GiangVien::class, 'MaGV', 'TenDangNhap');
    }

    public function nhanvien()
    {
        return $this->hasOne(NhanVien::class, 'MaNV', 'TenDangNhap');
    }
}
