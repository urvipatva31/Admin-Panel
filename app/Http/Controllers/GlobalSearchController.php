<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $term = $request->get('term');
            if (empty($term)) return response()->json(['results' => [], 'total' => 0]);

            $results = collect();

            // 1. Members
            if (Schema::hasTable('members')) {
                $members = DB::table('members')
                    ->where(function ($query) use ($term) {
                        $query->where('full_name', 'LIKE', "%{$term}%")
                              ->orWhere('phone', 'LIKE', "%{$term}%");
                    })
                    ->select('id', 'full_name as label', DB::raw("'MEMBER' as type"), DB::raw("'/user-management' as url"))
                    ->limit(5)->get();
                $results = $results->concat($members);
            }

            // 2. Projects
            if (Schema::hasTable('projects')) {
                $projects = DB::table('projects')
                    ->where('project_name', 'LIKE', "%{$term}%")
                    ->select('id', 'project_name as label', DB::raw("'PROJECT' as type"), DB::raw("'/projects' as url"))
                    ->limit(5)->get();
                $results = $results->concat($projects);
            }

            // 3. Tasks
            if (Schema::hasTable('tasks')) {
                $tasks = DB::table('tasks')
                    ->where('task_title', 'LIKE', "%{$term}%")
                    ->select('id', 'task_title as label', DB::raw("'TASK' as type"), DB::raw("'/tasks' as url"))
                    ->limit(5)->get();
                $results = $results->concat($tasks);
            }

            // 4. Payroll
            if (Schema::hasTable('salaries') && Schema::hasTable('members')) {
                $salaries = DB::table('salaries')
                    ->join('members', 'salaries.member_id', '=', 'members.id')
                    ->where(function ($query) use ($term) {
                        $query->where('members.full_name', 'LIKE', "%{$term}%")
                              ->orWhere('salaries.month', 'LIKE', "%{$term}%")
                              ->orWhere('salaries.status', 'LIKE', "%{$term}%");
                    })
                    ->select(
                        'salaries.id',
                        DB::raw("CONCAT(members.full_name, ' (', salaries.month, ')') as label"),
                        DB::raw("'PAYROLL' as type"),
                        DB::raw("'/payroll' as url")
                    )
                    ->limit(5)->get();
                $results = $results->concat($salaries);
            }

            // 5. Attendance (FIXED TABLE NAME)
            if (Schema::hasTable('attendances')) {
                $attendance = DB::table('attendances')
                    ->join('members', 'attendances.member_id', '=', 'members.id')
                    ->where('members.full_name', 'LIKE', "%{$term}%")
                    ->select(
                        'attendances.id', // Corrected from attendance.id
                        DB::raw("CONCAT('Attendance: ', members.full_name) as label"),
                        DB::raw("'ATTENDANCE' as type"),
                        DB::raw("'/attendance' as url")
                    )
                    ->groupBy('members.id', 'attendances.id', 'members.full_name') // Corrected from attendance.id
                    ->limit(5)->get();
                $results = $results->concat($attendance);
            }

            // 6. Audit Logs (FIXED DESCRIPTION COLUMN)
          
if (Schema::hasTable('audit_logs')) {
    $audit = DB::table('audit_logs')
        ->where(function ($query) use ($term) {
            $query->where('description', 'LIKE', "%{$term}%") 
                  ->orWhere('action', 'LIKE', "%{$term}%")
                  ->orWhere('module', 'LIKE', "%{$term}%");
        })
        ->select(
            'audit_logs.id',
            // CHANGE THIS LINE BELOW to show the description so you see "Krisha logged in"
            DB::raw("CONCAT('Log: ', description) as label"), 
            DB::raw("'AUDIT' as type"),
            DB::raw("'/audit-logs' as url")
        )
        ->orderBy('created_at', 'desc')
        ->limit(5)->get();
    $results = $results->concat($audit);
}

            return response()->json([
                'results' => $results,
                'total' => $results->count()
            ]);

        } catch (\Exception $e) {
            Log::error("Global Search Error: " . $e->getMessage());
            return response()->json(['error' => 'Search failed: ' . $e->getMessage()], 500);
        }
    }
}