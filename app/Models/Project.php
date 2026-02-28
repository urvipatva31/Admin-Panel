<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'project_name',
        'client_name',
        'start_date',
        'end_date',
        'status'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function members()
    {
        return $this->belongsToMany(
            Member::class,
            'project_members',
            'project_id',
            'member_id'
        )->where('members.role_id', 5); 
    }
}