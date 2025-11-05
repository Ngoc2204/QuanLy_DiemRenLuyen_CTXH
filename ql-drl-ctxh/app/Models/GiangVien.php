<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiangVien extends Model
{
    protected $table = 'giangvien';
    protected $primaryKey = 'MaGV';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaGV', 'TenGV', 'Email', 'SDT', 'GioiTinh', 'MaLop'];

    public function lopPhuTrach()
    {
        return $this->belongsToMany(Lop::class, 'covanht', 'MaGiangVien', 'MaLop');
    }

    public function hoatDongsPhuTrach()
    {
        return $this->hasMany(HoatDongDRL::class, 'MaGV', 'MaGV');
    }
}
