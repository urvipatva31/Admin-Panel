<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Member;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project', 'member'])->paginate(5);

        $employees = Member::where('role_id', 5)->get();

        $projects = Project::all();

        return view('pages.tasks', compact('tasks', 'employees', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_title' => 'required',
            'assigned_to' => 'required',
            'project_id' => 'required',
            'status' => 'required',
            'due_date' => 'required'
        ]);

        $task = Task::create($request->all());

        AuditLog::logActivity(
            session('member_id'),
            'Created',
            'Task Management',
            'Assigned task: ' . $task->task_title
        );

        return redirect()->back()->with('success', 'Task assigned successfully');
    }

    public function show($id)
{
    $task = Task::with(['project', 'member'])->findOrFail($id);

    AuditLog::logActivity(
        session('member_id'),
        'Viewed',
        'Task Management',
        'Viewed task: ' . $task->task_title
    );

    return view('pages.task-view', compact('task'));
}

public function edit($id)
{
    $tasks = Task::with(['project', 'member'])->paginate(5);
    $editTask = Task::findOrFail($id);

    $employees = Member::where('role_id', 5)->get();
    $projects = Project::all();

    return view('pages.tasks', compact('tasks', 'editTask', 'employees', 'projects'));
}

public function update(Request $request, $id)
{
    $task = Task::findOrFail($id);

    $task->update([
        'task_title'       => $request->task_title,
        'assigned_to'      => $request->assigned_to,
        'project_id'       => $request->project_id,
        'due_date'         => $request->due_date,
        'priority'         => $request->priority,
        'status'           => $request->status,
        'task_description' => $request->task_description,
    ]);

    AuditLog::logActivity(
            session('member_id'),
            'Updated',
            'Task Management',
            'Updated task: ' . $task->task_title
        );

    return redirect()->route('tasks')->with('success', 'Task Updated Successfully');
}

// public function destroy($id)
//     {
//         $task = Task::findOrFail($id);
//         $taskTitle = $task->task_title;

//         $task->delete();

//         AuditLog::logActivity(
//             session('member_id'),
//             'Deleted',
//             'Task Management',
//             'Deleted task: ' . $taskTitle
//         );

//         return redirect()->route('tasks')->with('success', 'Task Deleted Successfully');
//     }
}
