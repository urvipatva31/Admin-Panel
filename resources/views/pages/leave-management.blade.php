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
        <h1>Leave Requests</h1>
    </div>


    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Days</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($leaves as $leave)
                <tr>
                    <!-- Employee Name -->
                    <td>{{ $leave->member->full_name ?? 'N/A' }}</td>

                    <!-- Leave Type -->
                    <td>{{ ucfirst($leave->leave_type) }}</td>

                    <!-- Dates -->
                    <td>{{ $leave->start_date }}</td>
                    <td>{{ $leave->end_date }}</td>

                    <!-- Total Days -->
                    <td>
                        {{ \Carbon\Carbon::parse($leave->start_date)
                            ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}
                    </td>

                    <!-- Reason -->
                    <td>{{ $leave->reason }}</td>

                    <!-- Status -->
                    <td>
                        <span class="status-badge {{ $leave->status }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>

                    <!-- Action Buttons -->
                    <td style="gap: 2px;">
                        @if($leave->status == 'pending')

                        <form action="{{ url('leave/approve/'.$leave->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-primary" style="border: none; padding: 5px; border-radius: 4px;">Approve</button>
                        </form>

                        <form action="{{ url('leave/reject/'.$leave->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-secondary" style="border: none; padding: 5px; border-radius: 4px;">Reject</button>
                        </form>

                        @else
                        <span>-</span>
                        @endif
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="8" style="text-align:center;">
                        No leave requests found
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
    <div class="pagination-wrapper">
    {{ $leaves->links('pagination::default') }}
</div>

</div>