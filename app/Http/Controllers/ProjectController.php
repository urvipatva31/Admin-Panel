<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Project;
use Illuminate\Http\Request;

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
        Project::create([
            'project_name' => $request->project_name,
            'client_name'  => $request->client_name,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'status'       => 'pending'
        ]);

        return redirect()->route('projects')->with('success', 'Project Created');
    }

    public function edit($id)
    {
        $projects = Project::withCount(['tasks', 'members'])->get();
    $editProject = Project::findOrFail($id);

    // $managers = Member::where('role_id', 4)->get();

    // return view('pages.projects', compact('projects', 'editProject', 'managers'));
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

        return redirect()->route('projects')->with('success', 'Project Updated');
    }

    public function show($id)
    {
        $project = Project::with(['tasks', 'members'])->findOrFail($id);

        return view('pages.project-view', [
            'project' => $project,
            'members' => $project->members
        ]);
    }
}
