<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCluster extends Model
{
    protected $table = 'student_clusters';
    protected $primaryKey = 'ClusterAssignmentID';
    protected $fillable = ['MSSV', 'ClusterID', 'ClusterName', 'AssignmentDate'];
    public $timestamps = false;

    public function student()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }
}
