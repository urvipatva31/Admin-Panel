@include('components.header')
@include('components.sidebar')
<div class="main-container">
    
<div class="page-header">
    <h1>Audit Logs</h1>
    <div class="page-actions">
        <button><i class="fas fa-download"></i> Export Logs</button>
    </div>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>User</th>
                <th>Action</th>
                <th>Module</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
           @forelse($logs as $log)
        <tr>
            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>

            <td>
                {{ $log->member->full_name ?? 'N/A' }}
            </td>

            <td>{{ $log->action }}</td>

            <td>{{ $log->module }}</td>

            <td>{{ $log->ip_address }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align:center;">
                No audit logs found.
            </td>
        </tr>
    @endforelse
        </tbody>
    </table>

    <div class="pagination-wrapper">
    {{ $logs->links('pagination::default') }}
</div>
</div>

<div class="form-section">
    <h2>Filter Audit Logs</h2>
    <form>
        <div class="grid-container" style="grid-template-columns: 1fr 1fr;">
            <div class="form-group">
                <label for="filterUser">User</label>
                <input type="text" id="filterUser" placeholder="Filter by username or name">
            </div>
            <div class="form-group">
                <label for="filterAction">Action</label>
                <input type="text" id="filterAction" placeholder="e.g., Logged in, Updated">
            </div>
            <div class="form-group">
                <label for="filterModule">Module</label>
                <select id="filterModule">
                    <option value="">All Modules</option>
                    <option value="authentication">Authentication</option>
                    <option value="user_management">User Management</option>
                    <option value="projects">Projects</option>
                    <option value="hr_management">HR Management</option>
                    <option value="tasks">Tasks</option>
                    <option value="system_settings">System Settings</option>
                </select>
            </div>
            <div class="form-group">
                <label for="filterDate">Date Range</label>
                <input type="date" id="filterDate">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Apply Filters</button>
            <button type="reset" class="btn-secondary">Clear Filters</button>
        </div>
    </form>
</div>
</div>