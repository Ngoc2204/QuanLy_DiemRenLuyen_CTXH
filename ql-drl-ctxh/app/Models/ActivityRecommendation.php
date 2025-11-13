<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityRecommendation extends Model
{
    protected $table = 'activity_recommendations';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'MSSV',
        'MaHoatDong',
        'activity_type',
        'recommendation_score',
        'recommendation_reason',
        'priority',
        'recommended_at',
        'viewed_at',
    ];

    protected $casts = [
        'recommendation_score' => 'float',
        'priority' => 'integer',
        'recommended_at' => 'datetime',
        'viewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    public function hoatDongDRL()
    {
        return $this->belongsTo(HoatDongDRL::class, 'MaHoatDong', 'MaHoatDong');
    }

    public function hoatDongCTXH()
    {
        return $this->belongsTo(HoatDongCTXH::class, 'MaHoatDong', 'MaHoatDong');
    }
}
