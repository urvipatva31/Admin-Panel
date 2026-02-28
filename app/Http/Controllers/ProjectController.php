<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount(['tasks', 'members'])->paginate(5);

        // managers = members with role manager (role_id = 4)
        $managers = Member::where('role_id', 4)->get();

        return view('pages.projects', compact('projects', 'managers'));
    }

    public function store(Request $request)
    {
      $project = Project::create([
            'project_name' => $request->project_name,
            'client_name'  => $request->client_name,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'status'       => 'pending'
        ]);

        AuditLog::logActivity(
            session('member_id'),
            'Created',
            'Project',
            'Created project: ' . $project->project_name
        );

        return redirect()->route('projects')->with('success', 'Project Created');
    }

    public function edit($id)
    {
        $projects = Project::withCount(['tasks', 'members'])->paginate(5);
        $editProject = Project::findOrFail($id);

     return view('pages.projects', compact('projects', 'editProject'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $project->update([
            'project_name' => $request->project_name,
            'client_name'  => $request->client_name,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'status'       => $request->status
        ]);

        AuditLog::logActivity(
            session('member_id'),
            'Updated',
            'Project',
            'Updated project: ' . $project->project_name
        );

        return redirect()->route('projects')->with('success', 'Project Updated');
    }

    public function show($id)
    {
        $project = Project::with(['tasks', 'members'])->findOrFail($id);

        AuditLog::logActivity(
            session('member_id'),
            'Viewed',
            'Project',
            'Viewed project: ' . $project->project_name
        );

        return view('pages.project-view', [
            'project' => $project,
            'members' => $project->members
        ]);
    }

    // public function destroy($id)
    // {
    //     $project = Project::findOrFail($id);
    //     $projectName = $project->project_name;

    //     $project->delete();

    //     AuditLog::logActivity(
    //         session('member_id'),
    //         'Deleted',
    //         'Project',
    //         'Deleted project: ' . $projectName
    //     );

    //     return redirect()->route('projects')->with('success', 'Project Deleted');
    // }
}
