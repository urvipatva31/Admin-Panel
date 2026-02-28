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
        <h1>Projects</h1>
        <div class="page-actions">
            <a href="#add-project-form" id="addUserBtn" class="btn-primary">
                <i class="fas fa-plus"></i> Create New Project
            </a>
        </div>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Client</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Employees</th>
                    <th>Tasks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                <tr>
                    <td>{{ $project->project_name }}</td>
                    <td>{{ $project->client_name }}</td>
                    <td>{{ $project->start_date }}</td>
                    <td>{{ $project->end_date ?? 'Ongoing' }}</td>

                    <td>
                        <span class="status-badge {{ $project->status }}">
                            {{ ucfirst($project->status) }}
                        </span>
                    </td>

                    <td>{{ $project->members_count }}</td>

                    <td>{{ $project->tasks_count }}</td>

                    <td>
                        <a href="{{ route('projects.show', $project->id) }}" class="icon-button">
                            <i class="fas fa-eye"></i>
                        </a>

                        <a href="{{ route('projects.edit', $project->id) }}" class="icon-button">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">No Projects Found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">
    {{ $projects->links('pagination::default') }}
</div>

    <div class="form-section" id="add-project-form">
        <h2>Create New Project</h2>

        <form method="POST"
            action="{{ isset($editProject) ? route('projects.update', $editProject->id) : route('projects.store') }}">
            @csrf

            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">

                <div class="form-group">
                    <label>Project Name</label>
                    <input type="text" name="project_name" value="{{ $editProject->project_name ?? '' }}" required>

                </div>

                <div class="form-group">
                    <label>Client Name</label>
                    <input type="text" name="client_name" value="{{ $editProject->client_name ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ $editProject->start_date ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="end_date" value="{{ $editProject->end_date ?? '' }}">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label>Status</label>
                    <select name="status">
                        <option value="pending" {{ (isset($editProject) && $editProject->status=='pending') ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ (isset($editProject) && $editProject->status=='active') ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ (isset($editProject) && $editProject->status=='completed') ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                @if(isset($editProject))
                <button type="submit" class="btn-primary">Update Project</button>
                <a href="{{ route('projects') }}" class="btn-secondary">Cancel</a>
                @else
                <button type="submit" class="btn-primary">Create Project</button>
                @endif
            </div>
        </form>
    </div>

</div>