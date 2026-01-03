<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentInterest extends Model
{
    protected $fillable = [
        'MSSV',
        'InterestID',
        'InterestLevel',
    ];

    protected $table = 'student_interests';
    protected $primaryKey = 'StudentInterestID';
    public $timestamps = false;

    /**
     * Get the interest associated with this student interest
     */
    public function interest()
    {
        return $this->belongsTo(Interest::class, 'InterestID', 'InterestID');
    }

    /**
     * Get the student associated with this interest
     */
    public function student()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }
}
