<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClusterStatistic extends Model
{
    protected $table = 'cluster_statistics';
    protected $primaryKey = 'ClusterStatID';
    protected $fillable = ['ClusterID', 'TotalStudents', 'AvgParticipationRate', 'AvgScore', 'TopInterests', 'TopActivities', 'CreatedAt'];
    public $timestamps = false;
}
