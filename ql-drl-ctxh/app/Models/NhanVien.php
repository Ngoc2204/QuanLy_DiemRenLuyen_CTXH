<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    protected $table = 'nhanvien';
    protected $primaryKey = 'MaNV';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaNV',
        'TenNV',
        'Email',
        'SDT',
        'GioiTinh',
    ];

    public function khoa()
    {
        return $this->belongsTo(Khoa::class, 'MaKhoa', 'MaKhoa');
    }
}
