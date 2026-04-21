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
                    <th>Leave Usage</th>
                    <th>Type</th>
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
                    <td style="font-size: 12px; line-height: 1.4;">

                        <div @if($leave->leave_usage['casualYear'] > 12) style="color:red;font-weight:bold;" @endif>
                            C: {{ $leave->leave_usage['casualYear'] }}/12
                            ({{ $leave->leave_usage['casualMonth'] }}M)
                        </div>

                        <div @if($leave->leave_usage['sickYear'] > 10) style="color:red;font-weight:bold;" @endif>
                            S: {{ $leave->leave_usage['sickYear'] }}/10
                            ({{ $leave->leave_usage['sickMonth'] }}M)
                        </div>

                        <div @if($leave->leave_usage['annualYear'] > 15) style="color:red;font-weight:bold;" @endif>
                            A: {{ $leave->leave_usage['annualYear'] }}/15
                        </div>

                    </td>

                    <td>
                        <select id="type_{{ $leave->id }}" style="padding:5px;">
                            <option value="auto">Auto</option>
                            <option value="1">Paid</option>
                            <option value="0">Unpaid</option>
                        </select>
                    </td>


                    <!-- Action Buttons -->
                    <td style="gap: 2px; display:flex; flex-direction:row;">
                        @if($leave->status == 'pending')

                        <form action="{{ url('leave/approve/'.$leave->id) }}" method="POST" style="display:inline;">
                            @csrf

                            <input type="hidden" name="is_paid_override" id="hidden_type_{{ $leave->id }}">

                            <button type="submit"
                                class="btn-primary"
                                style="padding:6px 10px;"
                                onclick="
                document.getElementById('hidden_type_{{ $leave->id }}').value =
                document.getElementById('type_{{ $leave->id }}').value;
            ">
                                Approve
                            </button>
                        </form>

                        <!-- REJECT -->
                        <form action="{{ url('leave/reject/'.$leave->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-secondary" style="padding:6px 10px;">
                                Reject
                            </button>
                        </form>

                        @else
                        <span>-</span>
                        @endif
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="10" style="text-align:center;">
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

    <div class="page-header" style="margin-top:40px;">
        <h1>Leave History</h1>
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
                    <th>Leave Payment</th>
                </tr>
            </thead>

            <tbody>

                @forelse($approvedLeaves as $leave)
                <tr>

                    <td>{{ $leave->member->full_name ?? 'N/A' }}</td>

                    <td>{{ ucfirst($leave->leave_type) }}</td>

                    <td>{{ $leave->start_date }}</td>

                    <td>{{ $leave->end_date }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($leave->start_date)
->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}
                    </td>

                    <td>{{ $leave->reason }}</td>

                    <td>
                        <span class="status-badge {{ $leave->status }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>
                    <td>
                        @if($leave->is_paid === 1)
                        <span style="color:green;font-weight:600;">Paid</span>
                        @elseif($leave->is_paid === 0)
                        <span style="color:red;font-weight:600;">Unpaid</span>
                        @else
                        <span style="color:gray;">Auto</span>
                        @endif
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="7" style="text-align:center;">
                        No leave history found
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $approvedLeaves->links('pagination::default') }}
    </div>
</div>