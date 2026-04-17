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
    'lunch_out',
    'check_out',
    'post_lunch_in',
    'status',
    'is_late',
    'late_minutes',
    'total_work_minutes',
    'approval_status',
    'leave_id',
    'remarks',
    'is_full_day',
    'is_afternoon_late',
    'afternoon_late_minutes'
];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
