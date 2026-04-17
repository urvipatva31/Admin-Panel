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
    <!-- Page Header -->

    <div class="page-header">
        <h1>Payroll</h1>
        <div class="page-actions">

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
                    <th>Actions</th>
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
                    <td>
        <a href="{{ route('payroll.delete', $salary->id) }}" 
           class="icon-button" 
           onclick="return confirm('Are you sure you want to delete this payroll record?');"
           title="Delete">
            <i class="fas fa-trash-alt"></i>
        </a>
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



        <form action="{{ route('payroll.store') }}" method="POST">
            @csrf

            <div class="grid-container" style="grid-template-columns:1fr 1fr;">

                <!-- Employee Dropdown -->
                <div class="form-group">
                    <label>Employee</label>
                    <select name="member_id" id="member_select" required onchange="updatePayrollForm()">
                        <option value="">Select Employee</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            data-salary="{{ $user->base_salary }}"
                            data-deduction="{{ $user->suggested_deduction }}"
                            data-leaves="{{ $user->leave_days }}">
                            {{ $user->full_name }} (Base: ₹{{ number_format($user->base_salary) }})
                        </option>
                        @endforeach
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
    <input type="number" name="bonus" id="bonus_input" step="0.01" value="0">
</div>

<div class="form-group">
    <label>Deductions</label>
    <input type="number" name="deductions" id="deduction_input" step="0.01" value="0">
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
<script>
 function updatePayrollForm() {
    const select = document.getElementById('member_select');
    const selectedOption = select.options[select.selectedIndex];
    
    const deductionInput = document.getElementById('deduction_input');
    const bonusInput = document.getElementById('bonus_input');

    if (!selectedOption.value) {
        deductionInput.value = 0;
        bonusInput.value = 0;
        return;
    }

    const suggestedDeduction = selectedOption.getAttribute('data-deduction');
    const leaveDays = selectedOption.getAttribute('data-leaves');

    // Auto-fill
    deductionInput.value = suggestedDeduction || 0;
    
    // Log to console so you can check (Press F12 in browser)
    console.log("Member: " + selectedOption.text);
    console.log("Calculated Deduction: ₹" + suggestedDeduction);

    // Friendly Alert if it's 0
    if (parseFloat(suggestedDeduction) === 0) {
        console.warn("No deduction calculated. This is likely because no attendance is marked for this month yet.");
    }
}
</script>