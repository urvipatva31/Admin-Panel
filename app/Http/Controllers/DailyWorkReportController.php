<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\DailyWorkReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyWorkReportController extends Controller
{

    // Show all reports
    public function index()
    {
        $reports = DailyWorkReport::with(['member', 'project', 'task', 'reviewer', 'remarks'])
            ->where('report_date', '>=', now()->subDays(3)) // last 3 days
            ->orderBy('report_date', 'desc')
            ->orderBy('created_at', 'desc') // newest first
            ->paginate(10);

        $projects = Project::all();

        return view('pages.daily-work-report', compact('reports', 'projects'));
    }


    // Store new report
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'project' => 'required',
            'task_title' => 'required',
            'hours_worked' => 'required',
            'description' => 'required'
        ]);

        $fileName = null;

        if ($request->hasFile('attachment')) {

            $file = $request->file('attachment');

            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('work-reports'), $fileName);
        }

        DailyWorkReport::create([
            'member_id' => session('member_id'),
            'project_id' => $request->project,
            'report_date' => $request->date,
            'task_title' => $request->task_title,
            'hours_worked' => $request->hours_worked,
            'work_description' => $request->description,
            'attachment' => $fileName,
            'status' => 'Submitted'
        ]);

        return redirect()->route('daily-work-report')
            ->with('success', 'Report submitted successfully');
    }


    // Review page
    public function review($id)
    {
        $report = DailyWorkReport::with(['member', 'project', 'task', 'reviewer'])
            ->findOrFail($id);

        return view('pages.review-work-report', compact('report'));
    }


    // Update report status (Approve / Reject)
    public function updateStatus(Request $request, $id)
    {
        $report = DailyWorkReport::with('member')->findOrFail($id);

        $currentUser = \App\Models\Member::find(session('member_id'));
        $currentUserId = $currentUser->id;

        $currentUserRoleId = $currentUser->role_id;
        $reportOwnerRoleId = $report->member->role_id;


       
        if ($currentUserId == $report->member_id) {
            return back()->with('error', 'You cannot review your own report.');
        }

        if ($currentUserRoleId == 1 && $reportOwnerRoleId == 1) {
            // allowed
        }

        elseif ($currentUserRoleId == $reportOwnerRoleId) {
            return back()->with('error', 'Same role cannot review this report.');
        }

        
        elseif ($currentUserRoleId > $reportOwnerRoleId) {
            return back()->with('error', 'You do not have permission to review this report.');
        }

        // Update report
        $report->status = $request->status;
        $report->reviewed_by = $currentUserId;
        $report->save();

        // Save remark if exists
        if (!empty($request->remarks)) {

            DB::table('daily_work_report_remarks')->insert([
                'report_id' => $report->id,
                'member_id' => $currentUserId,
                'remark' => $request->remarks,
                'created_at' => now()
            ]);
        }

        return redirect()->route('daily-work-report')
            ->with('success', 'Report reviewed successfully');
    }
}
