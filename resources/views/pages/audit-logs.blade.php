@include('components.header')
@include('components.sidebar')
<div class="main-container">

    <div class="page-header">
        <h1>Audit Logs</h1>

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

    </div>
    <div class="pagination-wrapper">
        {{ $logs->links('pagination::default') }}
    </div>
</div>