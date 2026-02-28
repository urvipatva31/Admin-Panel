<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
     protected $table = 'audit_logs';

    public $timestamps = false;

    protected $fillable = [
        'member_id',
        'action',
        'module',
        'description',
        'ip_address',
        'created_at',
    ];

     protected $casts = [
        'created_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public static function logActivity($memberId, $action, $module, $description = null)
    {
        self::create([
            'member_id' => $memberId,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
