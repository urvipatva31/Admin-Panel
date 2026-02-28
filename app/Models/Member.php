<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'address',
        'password',
        'role_id',
        'designation_id',
        'base_salary',
        'status'
    ];

    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function projects()
    {
        return $this->belongsToMany(
            Project::class,
            'project_members',
            'member_id',
            'project_id'
        );
    }
}
