<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyWorkReportRemark extends Model
{
    protected $table = 'daily_work_report_remarks';

    public $timestamps = false; // because you only have created_at

    protected $fillable = [
        'report_id',
        'member_id',
        'remark',
        'created_at'
    ];

    // Relation → belongs to report
    public function report()
    {
        return $this->belongsTo(DailyWorkReport::class, 'report_id');
    }

    // Relation → who wrote the remark
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}