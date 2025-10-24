<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuyDinhDiemRL extends Model
{
    protected $table = 'quydinhdiemrl';
    protected $primaryKey = 'MaDiem';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaDiem', 'TenCongViec', 'DiemNhan'];

    public function hoatdong()
    {
        return $this->hasMany(HoatDongDRL::class, 'MaQuyDinhDiem', 'MaDiem');
    }
}
