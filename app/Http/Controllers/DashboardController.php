<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
class DashboardController extends Controller
{
     public function index()
    {
        // Cards
        $totalUsers = Member::count();

        $activeProjects = Project::where('status', 'active')->count();

        $pendingTasks = Task::where('status', 'pending')->count();

        $newEmployees = Member::whereMonth('created_at', Carbon::now()->month)
                               ->whereYear('created_at', Carbon::now()->year)
                               ->count();

        // Recent Activities (latest tasks)
        $recentActivities = Task::with(['project', 'member'])
                                ->latest()
                                ->take(5)
                                ->get();

        return view('pages.dashboard', compact(
            'totalUsers',
            'activeProjects',
            'pendingTasks',
            'newEmployees',
            'recentActivities'
        ));
    }
}
