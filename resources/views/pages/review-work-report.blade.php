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
    <h1>Review Work Report</h1>

<div class="page-actions">
    <a href="{{ route('daily-work-report') }}" class="btn-secondary">
        Back
    </a>
</div>


</div>

<!-- REPORT DETAILS -->

<div class="form-section">

<div class="section-header">
    <h2>Report Details</h2>
</div>

<div class="grid-container" style="grid-template-columns: 1fr 1fr;">

    <div class="form-group">
        <label>Employee</label>
        <input type="text" value="{{ $report->member->full_name ?? '-' }}" readonly>
    </div>

    <div class="form-group">
        <label>Project</label>
        <input type="text" value="{{ $report->project->project_name ?? '-' }}" readonly>
    </div>

    <div class="form-group">
        <label>Task</label>
        <input type="text" value="{{ $report->task->task_name ?? $report->task_title }}" readonly>
    </div>

    <div class="form-group">
        <label>Hours Worked</label>
        <input type="text" value="{{ $report->hours_worked }} Hours" readonly>
    </div>

</div>

<div class="form-group">
    <label>Description</label>
    <textarea rows="4" readonly>{{ $report->work_description }}</textarea>
</div>

@if($report->attachment)
<div class="form-group">
    <!-- <label>Attachment</label> -->
    <div class="page-actions">
        <a href="{{ asset('work-reports/'.$report->attachment) }}" class="btn-secondary" target="_blank">
            View Atteched File
        </a>
    </div>
</div>
@endif


</div>

<!-- REVIEW DECISION -->

<div class="form-section">

<div class="section-header">
    <h2>Review Decision</h2>
</div>

<form action="{{ route('daily-work-report.updateStatus',$report->id) }}" method="POST">
    @csrf

    <div class="grid-container" style="grid-template-columns: 1fr;">

        <div class="form-group">
            <label>Status Decision</label>
            <select name="status" required>
                <option value="">Select Decision</option>
                <option value="Approved">Approve Report</option>
                <option value="Rejected">Reject Report</option>
                <option value="Returned">Return For Edit</option>
            </select>
        </div>

        <div class="form-group">
            <label>Manager Remarks</label>
            <textarea 
                name="remarks" 
                rows="4"
                placeholder="Add remarks or instructions for the employee (optional)">
            </textarea>
        </div>

    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            Submit Review
        </button>

        <a href="{{ route('daily-work-report') }}" class="btn-secondary">
            Cancel
        </a>
    </div>

</form>


</div>

</div>
