<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyWorkReport extends Model
{

    protected $table = 'daily_work_reports';

    protected $fillable = [

        'member_id',
        'project_id',
        'task_id',
        'report_date',
        'task_title',
        'hours_worked',
        'work_description',
        'attachment',
        'status',
        'remarks',
        'reviewed_by'
    ];


    public function member()
    {
        return $this->belongsTo(Member::class,'member_id');
    }


    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }


    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Member::class, 'reviewed_by');
    }

}