<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamHoc extends Model
{
    protected $table = 'namhoc';
    protected $primaryKey = 'MaNamHoc';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['TenNamHoc', 'NgayBatDau', 'NgayKetThuc'];

    public function hockys()
    {
        return $this->hasMany(HocKy::class, 'MaNamHoc', 'MaNamHoc');
    }
}
