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
        <h1>Project Details</h1>
        <div class="page-actions">
            <a href="{{ route('projects.edit', $project->id) }}" class="btn-primary">
                Edit Project
            </a>
            <a href="{{ route('projects') }}" class="btn-secondary">
                Back
            </a>
        </div>
    </div>



    <div class="page-section">

        <div class="grid-container" style="grid-template-columns: 1fr 1fr;">

            <div class="form-group">
                <label>Project Name</label>
                <input type="text" value="{{ $project->project_name }}" readonly>
            </div>

            <div class="form-group">
                <label>Client Name</label>
                <input type="text" value="{{ $project->client_name }}" readonly>
            </div>

            <div class="form-group">
                <label>Start Date</label>
                <input type="text" value="{{ $project->start_date }}" readonly>
            </div>

            <div class="form-group">
                <label>Due Date</label>
                <input type="text" value="{{ $project->end_date ?? 'Ongoing' }}" readonly>
            </div>

            <div class="form-group">
                <label>Status</label>
                <div style="margin-top:8px;">
                    <span class="status-badge {{ $project->status }}">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label>Total Tasks</label>
                <input type="text" value="{{ $project->tasks->count() }}" readonly>
            </div>

        </div>

    </div>


    @if($members->count())
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Member Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                <tr>
                    <td>{{ $member->full_name }}</td>
                    <td>{{ $member->email }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p>No members assigned to this project.</p>
    @endif

</div>