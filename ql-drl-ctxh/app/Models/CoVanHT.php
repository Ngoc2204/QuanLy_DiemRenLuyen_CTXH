<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoVanHT extends Model
{
    protected $table = 'covanht';
    protected $primaryKey = 'MaCVHT';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['MaCVHT', 'MaGV', 'MaLop', 'NamHoc', 'TrangThai'];

    public function giangvien()
    {
        return $this->belongsTo(GiangVien::class, 'MaGV', 'MaGV');
    }

    public function lop()
    {
        return $this->belongsTo(Lop::class, 'MaLop', 'MaLop');
    }
}
