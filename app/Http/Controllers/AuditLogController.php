<?php

namespace App\Http\Controllers;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('member')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.audit-logs', compact('logs'));
    }
}
