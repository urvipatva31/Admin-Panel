<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class ReportController extends Controller
{
    public function index()
    {
        $totalProjects = DB::table('projects')->count();
        $completedProjects = DB::table('projects')
            ->where('status', 'completed')
            ->count();

        $projectCompletionRate = $totalProjects > 0
            ? round(($completedProjects / $totalProjects) * 100)
            : 0;

        $totalTasks = DB::table('tasks')->count();
        $overdueTasks = DB::table('tasks')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        $taskOverdueRate = $totalTasks > 0
            ? round(($overdueTasks / $totalTasks) * 100)
            : 0;

        $totalBase = DB::table('salaries')->sum('base_salary');
        $totalPaid = DB::table('salaries')->sum('total_salary');

        $budgetUtilization = $totalBase > 0
            ? round(($totalPaid / $totalBase) * 100)
            : 0;

        $reports = DB::table('reports')
            ->leftJoin('members', 'members.id', '=', 'reports.generated_by')
            ->select(
                'reports.id',
                'reports.report_name',
                'reports.status',
                'reports.created_at',
                'members.full_name'
            )
            ->orderBy('reports.created_at', 'desc')
            ->paginate(5);

        return view('pages.reports', compact(
            'projectCompletionRate',
            'taskOverdueRate',
            'budgetUtilization',
            'reports'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'report_type' => 'required',
            'report_name' => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date',
        ]);

        $reportType = $request->report_type;
        $data = [];

        try {

            if ($reportType == 'project_summary') {
                // Filter by the project's 'start_date' column instead of 'created_at'
                $data['projects'] = DB::table('projects')
                    ->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->get();

                $data['total_projects'] = DB::table('projects')
                    ->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->count();

                $data['completed_projects'] = DB::table('projects')
                    ->where('status', 'completed')
                    ->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->count();

                $data['in_progress_projects'] = DB::table('projects')
                    ->where('status', 'Active') // Match the status string 'Active' from your screenshot
                    ->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->count();
            }

            if ($reportType == 'attendance_summary') {
                $data['attendances'] = DB::table('attendances')
                    ->whereBetween('attendance_date', [$request->start_date, $request->end_date])
                    ->get();

                $data['total_days'] = DB::table('attendances')
                    ->whereBetween('attendance_date', [$request->start_date, $request->end_date])
                    ->count();

                $data['present_days'] = DB::table('attendances')
                    ->where('status', 'present')
                    ->whereBetween('attendance_date', [$request->start_date, $request->end_date])
                    ->count();

                $data['absent_days'] = DB::table('attendances')
                    ->where('status', 'absent')
                    ->whereBetween('attendance_date', [$request->start_date, $request->end_date])
                    ->count();
            }

            if ($reportType == 'task_summary') {
                $data['tasks'] = DB::table('tasks')
                    ->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->get();

                $data['total_tasks'] = DB::table('tasks')
                    ->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->count();

                $data['completed_tasks'] = DB::table('tasks')
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->count();

                $data['pending_tasks'] = DB::table('tasks')
                    ->where('status', 'pending')
                    ->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->count();

                $data['overdue_tasks'] = DB::table('tasks')
                    ->where('due_date', '<', now())
                    ->where('status', '!=', 'completed')
                    ->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->count();
            }

            if ($reportType == 'financial_overview') {
                $data['salaries'] = DB::table('salaries')
                    ->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->get();

                $data['total_base_salary'] = DB::table('salaries')
                    ->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->sum('base_salary');

                $data['total_paid_salary'] = DB::table('salaries')
                    ->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->sum('total_salary');
            }

            // ✅ Create report (pending)
            $report = Report::create([
                'report_name' => $request->report_name,
                'report_type' => $reportType,
                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,
                'generated_by' => session('member_id'),
                'status'      => 'pending',
                'file_path'   => null
            ]);

            // 🔥 Generate PDF
            $pdf = Pdf::loadView('pages.pdf.dynamic-report', [
                'data' => $data,
                'report' => $report
            ]);

            // ✅ Save file
            $fileName = 'report_' . $report->id . '.pdf';
            $filePath = 'reports/' . $fileName;

            Storage::disk('public')->put($filePath, $pdf->output());

            // ✅ Update to ready
            $report->update([
                'file_path' => $filePath,
                'status'    => 'ready'
            ]);

            // ✅ Audit log
            AuditLog::logActivity(
                session('member_id'),
                'Create',
                'Reports',
                'Generated dynamic report: ' . $report->report_name
            );

            return redirect()->route('reports')->with('success', 'Report generated successfully');
        } catch (\Exception $e) {

            // ❗ VERY IMPORTANT
            if (isset($report)) {
                $report->update([
                    'status' => 'failed'
                ]);
            }

            return redirect()->back()->with('error', 'Report generation failed');
        }
    }

    public function view($id)
    {
        $report = Report::findOrFail($id);

        if (!$report->file_path || !Storage::disk('public')->exists($report->file_path)) {
            return redirect()->back()->with('error', 'File not found');
        }

        return response()->file(
            storage_path('app/public/' . $report->file_path)
        );
    }

    public function download($id)
    {
        $report = Report::findOrFail($id);

        if (!$report->file_path || !Storage::disk('public')->exists($report->file_path)) {
            return redirect()->back()->with('error', 'File not found');
        }

        return response()->download(
            storage_path('app/public/' . $report->file_path),
            $report->report_name . '.pdf'
        );
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        $reportName = $report->report_name;

        // 🔥 DELETE FILE FROM STORAGE
        if ($report->file_path && Storage::exists('public/' . $report->file_path)) {
            Storage::delete('public/' . $report->file_path);
        }

        // 🔥 DELETE REPORT
        $report->delete();

        // ✅ AUDIT LOG (SAME STYLE AS USER)
        AuditLog::logActivity(
            session('member_id'),
            'Delete',
            'Reports',
            'Deleted report: ' . $reportName
        );

        return redirect()->route('reports')
            ->with('success', 'Report deleted successfully');
    }
}
