<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChucVuSinhVien extends Model
{
    protected $table = 'chucvusinhvien';
    protected $primaryKey = 'MaChucVu';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaChucVu', 'TenChucVu', 'MoTa'];

    public function sinhvien()
    {
        return $this->hasMany(SinhVien::class, 'MaChucVu', 'MaChucVu');
    }
}
