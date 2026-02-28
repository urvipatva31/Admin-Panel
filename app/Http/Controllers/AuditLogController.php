<?php

namespace App\Http\Controllers;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('member')
            ->whereDate('created_at', now())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.audit-logs', compact('logs'));
    }
}
