<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'report_name',
        'report_type',
        'generated_by',
        'start_date',
        'end_date',
        'status',
        'file_path',
        'summary'
    ];

    public function user()
    {
        return $this->belongsTo(Member::class, 'generated_by');
    }
}