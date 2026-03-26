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
        $reports = DailyWorkReport::with(['member', 'project', 'task', 'reviewer'])
            ->orderBy('report_date', 'desc')
            ->get();

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

        $currentUserId = session('member_id');
        $currentUserPriority = session('priority');

        $reportOwnerPriority = $report->member->priority;

        // ❌ Prevent reviewing own report
        if ($report->member_id == $currentUserId) {
            return redirect()->back()->with('error', 'You cannot review your own report.');
        }

        // ❌ Only higher priority users can review
        if ($currentUserPriority <= $reportOwnerPriority) {
            return redirect()->back()->with('error', 'You do not have permission to review this report.');
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
