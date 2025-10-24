<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemCTXH extends Model
{
    protected $table = 'diemctxh';
    protected $primaryKey = 'MaCTXH';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaCTXH', 'MSSV', 'TongDiem', 'XepLoai'];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }
}
