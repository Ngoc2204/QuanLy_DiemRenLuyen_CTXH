<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhanHoiSinhVien extends Model
{
    protected $table = 'phanhoisinhvien';
    protected $primaryKey = 'MaPhanHoi';
    // public $incrementing = false; // Bỏ dòng này nếu MaPhanHoi là auto-increment
    public $timestamps = false; // Bảng này không có created_at/updated_at

    protected $fillable = ['MSSV', 'NoiDung', 'NgayGui', 'TrangThai'];

    // Quan hệ với SinhVien
    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }
}