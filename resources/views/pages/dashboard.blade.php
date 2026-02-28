@include('components.header')
@include('components.sidebar')
<div class="main-container">
    <div class="page-header">
        <h1>Dashboard</h1>
    </div>

    <div class="grid-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Total Users</h3>
                <i class="fas fa-users"></i>
            </div>
            <div class="card-value">{{ $totalUsers }}</div>
            <p class="card-description">Registered users in the system</p>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Active Projects</h3>
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="card-value">{{ $activeProjects }}</div>
            <p class="card-description">Currently ongoing projects</p>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pending Tasks</h3>
                <i class="fas fa-tasks"></i>
            </div>
            <div class="card-value">{{ $pendingTasks }}</div>
            <p class="card-description">Tasks awaiting completion</p>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">New Employees</h3>
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="card-value">{{ $newEmployees }}</div>
            <p class="card-description">Hired this month</p>
        </div>
    </div>

    <div class="table-container">
        <div class="card-header" style="padding: 15px 20px; border-bottom: 1px solid var(--border-color);">
            <h3 class="card-title">Recent Activities</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentActivities as $activity)
                <tr>
                    <td>
                        Task "{{ $activity->task_title }}" in
                        project "{{ $activity->project->project_name }}"
                    </td>
                    <td>{{ $activity->member->full_name }}</td>
                    <td>{{ $activity->created_at->format('Y-m-d') }}</td>
                    <td>
                        <span class="status-badge {{ $activity->status == 'completed' ? 'active' : 'pending' }}">
                            {{ ucfirst(str_replace('_',' ', $activity->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;">No recent activities</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>