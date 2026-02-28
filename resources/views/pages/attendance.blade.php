@include('components.header')
@include('components.sidebar')

@php
use Illuminate\Support\Facades\Auth;

// Logged user
$userId = Auth::id();

// Today's attendance for logged user
$myAttendance = $attendance->where('member_id', $userId)->first();

// Dashboard calculations
$presentCount = $attendance->whereIn('status', ['present','late'])->count();
$absentCount = $attendance->where('status','absent')->count();
$lateCount = $attendance->where('status','late')->count();
$leaveCount = $attendance->where('status','leave')->count();

$presentPercent = $totalEmployees > 0
? round(($presentCount / $totalEmployees) * 100)
: 0;
@endphp

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
<!-- Page Header -->

<div class="page-header">
    <h1>Attendance</h1>


<div class="page-actions">

    {{-- Check In / Check Out --}}
    @if(!$myAttendance || !$myAttendance->check_in)
        <form action="{{ url('login-attendance') }}" method="POST">
            @csrf
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i> Check In
            </button>
        </form>

    @elseif(!$myAttendance->check_out)
        <form action="{{ url('logout-attendance') }}" method="POST">
            @csrf
            <button type="submit">
                <i class="fas fa-sign-out-alt"></i> Check Out
            </button>
        </form>
    @else
        <button disabled>Attendance Completed</button>
    @endif

</div>


</div>

<!-- Dashboard Cards -->

<div class="grid-container">

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Today's Present</h3>
        <i class="fas fa-user-check"></i>
    </div>
    <div class="card-value">{{ $presentPercent }}%</div>
    <p class="card-description">Employees present today</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Total Absentees</h3>
        <i class="fas fa-user-times"></i>
    </div>
    <div class="card-value">{{ $absentCount }}</div>
    <p class="card-description">Employees absent today</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Late Arrivals</h3>
        <i class="fas fa-clock"></i>
    </div>
    <div class="card-value">{{ $lateCount }}</div>
    <p class="card-description">Late logins today</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">On Leave</h3>
        <i class="fas fa-plane-departure"></i>
    </div>
    <div class="card-value">{{ $leaveCount }}</div>
    <p class="card-description">Employees on leave</p>
</div>

</div>

<!-- Attendance Table -->

<div class="table-container">
    <div class="card-header" style="padding: 15px 20px; border-bottom: 1px solid var(--border-color);">
        <h3 class="card-title">Daily Attendance Log (Today)</h3>
    </div>

<table class="data-table">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
            <th>Hours Worked</th>
        </tr>
    </thead>
    <tbody>
    @foreach($attendance as $row)
    <tr>
        <td>{{ $row->member->full_name ?? 'N/A' }}</td>
        <td>{{ $row->check_in ?? '--' }}</td>
        <td>{{ $row->check_out ?? '--' }}</td>

        <td>
            <span class="status-badge {{ $row->status == 'absent' ? 'rejected' : 'active' }}">
                {{ ucfirst($row->status) }}
            </span>
        </td>

        <td>
            @if($row->total_work_minutes)
                {{ floor($row->total_work_minutes / 60) }}h
                {{ $row->total_work_minutes % 60 }}m
            @else
                --
            @endif
        </td>

    </tr>
    @endforeach
    </tbody>
</table>
</div>

<div class="pagination-wrapper">
    {{ $attendance->links('pagination::default') }}
</div>
<!-- Leave Apply Section -->

<div class="form-section">
    <h2>Apply Leave / Work From Home</h2>


<form action="{{ url('leave/apply') }}" method="POST">
    @csrf

    <div class="grid-container" style="grid-template-columns: 1fr 1fr;">

        <div class="form-group">
            <label>Leave Type</label>
            <select name="leave_type" required>
                <option value="sick">Sick Leave</option>
                <option value="casual">Casual Leave</option>
                <option value="annual">Annual Leave</option>
                <option value="half_day">Half Day</option>
                <option value="wfh">Work From Home</option>
            </select>
        </div>

        <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date" required>
        </div>

        <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date" required>
        </div>

        <div class="form-group" style="grid-column: span 2;">
            <label>Reason</label>
            <textarea name="reason" rows="3" required></textarea>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">Submit Request</button>
    </div>
</form>

</div>

</div>
