<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamHoc extends Model
{
    protected $table = 'namhoc';
    protected $primaryKey = 'MaNamHoc';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaNamHoc', 'TenNamHoc'];
}
