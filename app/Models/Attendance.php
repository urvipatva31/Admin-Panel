<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'member_id',
        'attendance_date',
        'check_in',
        'check_out',
        'status',
        'is_late',
        'late_minutes',
        'total_work_minutes',
        'approval_status',
        'leave_id',
        'remarks'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
