<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lop extends Model
{
    protected $table = 'lop';
    protected $primaryKey = 'MaLop';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaLop', 'TenLop', 'MaKhoa'];

    public function khoa()
    {
        return $this->belongsTo(Khoa::class, 'MaKhoa', 'MaKhoa');
    }

    public function sinhvien()
    {
        return $this->hasMany(SinhVien::class, 'MaLop', 'MaLop');
    }

    public function coVanHocTap()
    {
        return $this->belongsToMany(GiangVien::class, 'covanht', 'MaLop', 'MaGiangVien');
    }
}
