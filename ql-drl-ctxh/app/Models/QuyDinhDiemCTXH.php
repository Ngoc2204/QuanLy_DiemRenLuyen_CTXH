<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuyDinhDiemCTXH extends Model
{
    protected $table = 'quydinhdiemctxh';
    protected $primaryKey = 'MaDiem';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = ['MaDiem', 'TenCongViec', 'DiemNhan'];

    public function hoatdong()
    {
        return $this->hasMany(HoatDongCTXH::class, 'MaQuyDinhDiem', 'MaDiem');
    }
}
