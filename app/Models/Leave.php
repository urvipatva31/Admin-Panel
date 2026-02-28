<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'leaves';

     protected $fillable = [
        'member_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'status',
        'approved_by',
        'approved_at'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
