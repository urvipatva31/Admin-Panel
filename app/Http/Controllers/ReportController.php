<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;

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
            ->join('members', 'members.id', '=', 'reports.generated_by')
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

    public function view($id)
    {
        $report = Report::with('user')->findOrFail($id);

        return view('pages.report-view', compact('report'));
    }

    public function download($id)
{
    $report = Report::findOrFail($id);

    // $pdf = Pdf::loadView('reports.pdf', compact('report'));

    // return $pdf->download($report->report_name . '.pdf');
}
}