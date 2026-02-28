<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
   protected $fillable = [
        'member_id',
        'month',
        'base_salary',
        'bonus',
        'deductions',
        'total_salary',
        'status'
    ];

public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
