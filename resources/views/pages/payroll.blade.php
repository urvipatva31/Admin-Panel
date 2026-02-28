@include('components.header')
@include('components.sidebar')

<div class="main-container">

<!-- Page Header -->

<div class="page-header">
    <h1>Payroll</h1>
    <div class="page-actions">
        <button class="btn-primary">
            <i class="fas fa-file-invoice-dollar"></i> Generate Payslips
        </button>
    </div>
</div>

<!-- Dashboard Cards -->

<div class="grid-container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Total Monthly Payout</h3>
            <i class="fas fa-rupee-sign"></i>
        </div>
        <div class="card-value">₹{{ number_format($totalPayout ?? 0) }}</div>
        <p class="card-description">Total salary for {{ date('F Y') }}</p>
    </div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Employees Paid</h3>
        <i class="fas fa-check-circle"></i>
    </div>
    <div class="card-value">{{ $paidPercent ?? 0 }}%</div>
    <p class="card-description">Employees marked as paid</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pending Approvals</h3>
        <i class="fas fa-hourglass-half"></i>
    </div>
    <div class="card-value">{{ $pendingCount ?? 0 }}</div>
    <p class="card-description">Payslips awaiting approval</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Next Payroll Date</h3>
        <i class="fas fa-calendar-alt"></i>
    </div>
    <div class="card-value">{{ date('d M Y', strtotime('last day of this month')) }}</div>
    <p class="card-description">End of current payroll cycle</p>
</div>


</div>

<!-- Recent Payslips -->

<div class="table-container">
    <div class="card-header" style="padding:15px 20px; border-bottom:1px solid var(--border-color);">
        <h3 class="card-title">Recent Payslips</h3>
    </div>


<table class="data-table">
    <thead>
        <tr>
            <th>Employee</th>
            <th>Role</th>
            <th>Month</th>
            <th>Net Salary</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @forelse($salaries ?? [] as $salary)
        <tr>
            <td>{{ $salary->member->full_name ?? 'No Member' }}</td>
            <td>{{ ucfirst($salary->member->role->role_name ?? 'No Role') }}</td>
            <td>{{ $salary->month }}</td>
            <td>₹{{ number_format($salary->total_salary ?? 0) }}</td>
            <td>
                <span class="status-badge
                    {{ $salary->status == 'paid' ? 'active' :
                       ($salary->status == 'pending' ? 'pending' : 'inactive') }}">
                    {{ ucfirst($salary->status ?? 'draft') }}
                </span>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align:center;">
                No payroll data available
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>

    <div class="pagination-wrapper">
    {{ $salaries->links('pagination::default') }}
</div>

<!-- Process Payroll Form -->

<div class="form-section">
    <h2>Process Payroll</h2>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('payroll.store') }}" method="POST">
    @csrf

    <div class="grid-container" style="grid-template-columns:1fr 1fr;">

        <!-- Employee Dropdown -->
        <div class="form-group">
            <label>Employee</label>
            <select name="member_id" required>
                <option value="">Select Employee</option>
                @forelse($users ?? [] as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->full_name }}
                        ({{ ucfirst($user->role->role_name ?? 'No Role') }})
                    </option>
                @empty
                    <option disabled>No active members found</option>
                @endforelse
            </select>
        </div>

        <!-- Month -->
        <div class="form-group">
            <label>Month</label>
            <input type="month" name="month" value="{{ date('Y-m') }}" required>
        </div>

        <!-- Bonus -->
        <div class="form-group">
            <label>Bonus</label>
            <input type="number" name="bonus" step="0.01" value="0">
        </div>

        <!-- Deductions -->
        <div class="form-group">
            <label>Deductions</label>
            <input type="number" name="deductions" step="0.01" value="0">
        </div>

        <!-- Status -->
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="draft">Draft</option>
                <option value="pending">Pending</option>
                <option value="processed">Processed</option>
                <option value="paid">Paid</option>
            </select>
        </div>

        <!-- Notes -->
        <div class="form-group" style="grid-column:span 2;">
            <label>Notes</label>
            <textarea name="notes" rows="3" placeholder="Optional notes"></textarea>
        </div>

    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">Process Payroll</button>
        <button type="reset" class="btn-secondary">Reset</button>
    </div>
</form>

</div>

</div>
