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
        <h1>Task Details</h1>
        <div class="page-actions">
            <a href="{{ route('tasks.edit', $task->id) }}" class="btn-primary">Edit Task</a>
            <a href="{{ route('tasks') }}" class="btn-secondary">Back</a>
        </div>
    </div>

    <div class="page-section">
        <div class="grid-container" style="grid-template-columns: 1fr 1fr;">

            <div class="form-group">
                <label>Task Name</label>
                <input type="text" value="{{ $task->task_title }}" readonly>
            </div>

            <div class="form-group">
                <label>Assigned To</label>
                <input type="text" value="{{ $task->member->full_name ?? '-' }}" readonly>
            </div>

            <div class="form-group">
                <label>Project</label>
                <input type="text" value="{{ $task->project->project_name ?? '-' }}" readonly>
            </div>

            <div class="form-group">
                <label>Due Date</label>
                <input type="text" value="{{ $task->due_date }}" readonly>
            </div>

            <div class="form-group">
                <label>Status</label>
                <span class="status-badge {{ $task->status }}">
                    {{ ucfirst(str_replace('_',' ',$task->status)) }}
                </span>
            </div>

            <div class="form-group">
                <label>Priority</label>
                <input type="text" value="{{ ucfirst($task->priority) }}" readonly>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label>Description</label>
                <textarea readonly rows="4">{{ $task->task_description }}</textarea>
            </div>

        </div>
    </div>

</div>