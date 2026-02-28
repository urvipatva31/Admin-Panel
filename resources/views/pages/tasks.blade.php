@include('components.header')
@include('components.sidebar')

<div class="main-container">
        @if(session('error'))
    <div class="alert alert-danger">
        <span>{{ session('error') }}</span>
        <button type="button" onclick="this.parentElement.remove()">×</button>
    </div>
@endif

@if(session('success'))
<div class="alert alert-success">
    <span>{{ session('success') }}</span>
    <button type="button" onclick="this.parentElement.remove()">×</button>
</div>
@endif
    <div class="page-header">
        <h1>Tasks</h1>
        <div class="page-actions">
            <a href="#add-task-form" class="btn-primary">
                <i class="fas fa-plus"></i> Assign New Task
            </a>
        </div>
    </div>

    {{-- TASK LIST --}}
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Assigned To</th>
                    <th>Project</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->task_title }}</td>

                    <td>{{ $task->member->full_name ?? '-' }}</td>

                    <td>{{ $task->project->project_name ?? '-' }}</td>

                    <td>{{ $task->due_date }}</td>

                    <td>
                        <span class="status-badge {{ $task->status }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>

                    <td>{{ ucfirst($task->priority ?? 'Medium') }}</td>

                    <td>
                        <a href="{{ route('tasks.show', $task->id) }}" class="icon-button">
        <i class="fas fa-eye"></i>
    </a>

    <a href="{{ route('tasks.edit', $task->id) }}" class="icon-button">
        <i class="fas fa-edit"></i>
    </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
    {{ $tasks->links('pagination::default') }}
</div>

    {{-- ADD TASK FORM --}}
    <div class="form-section" id="add-task-form">
        <h2>Assign New Task</h2>

        <form method="POST"
    action="{{ isset($editTask) ? route('tasks.update', $editTask->id) : route('tasks.store') }}">
    @csrf

            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">

                <div class="form-group">
                    <label>Task Name</label>
                    <input type="text" name="task_title"
    value="{{ $editTask->task_title ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label>Assigned To</label>
                    <select name="assigned_to" required>
    <option value="">Select Employee</option>
    @foreach($employees as $emp)
        <option value="{{ $emp->id }}"
            {{ (isset($editTask) && $editTask->assigned_to == $emp->id) ? 'selected' : '' }}>
            {{ $emp->full_name }}
        </option>
    @endforeach
</select>
                </div>

                <div class="form-group">
                    <label>Project</label>
                    <select name="project_id" required>
    @foreach($projects as $project)
        <option value="{{ $project->id }}"
            {{ (isset($editTask) && $editTask->project_id == $project->id) ? 'selected' : '' }}>
            {{ $project->project_name }}
        </option>
    @endforeach
</select>
                </div>

                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" required>
                </div>

                <div class="form-group">
                    <label>Priority</label>
                    <select name="priority">
                        <option value="high">High</option>
                        <option value="medium" selected>Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label>Description</label>
                    <textarea name="task_description" rows="4"></textarea>
                </div>

            </div>

           <div class="form-actions">
    @if(isset($editTask))
        <button type="submit" class="btn-primary">Update Task</button>
        <a href="{{ route('tasks') }}" class="btn-secondary">Cancel</a>
    @else
        <button type="submit" class="btn-primary">Assign Task</button>
    @endif
</div>
        </form>
    </div>
</div>