@include('components.header')
@include('components.sidebar')

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
<div class="main-container">
    <div class="page-header">
        <h1>HR Management</h1>
        <div class="page-actions">
            <a href="#add-employee-form" class="btn-primary">
                <i class="fas fa-user-plus"></i> Add Employee
            </a>
        </div>
    </div>

    {{-- EMPLOYEE LIST --}}
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $emp)
                <tr>
                    <!-- <td>EMP{{ str_pad($emp->id, 3, '0', STR_PAD_LEFT) }}</td> -->
                    <td>EMP{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $emp->full_name }}</td>
                    <td>{{ $emp->email }}</td>
                    <td>{{ ucfirst($emp->role->role_name) }}</td>
                    <td>
                        <span class="status-badge {{ $emp->status == 'active' ? 'active' : 'inactive' }}">
                            {{ ucfirst($emp->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('hr-management.edit', $emp->id) }}" class="icon-button">
                            <i class="fas fa-edit"></i>
                        </a>

                        <a href="{{ route('hr-management.delete', $emp->id) }}"
                           class="icon-button"
                           onclick="return confirm('Are you sure you want to delete this employee?');">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
    {{ $employees->links('pagination::default') }}
</div>

    <!-- {{-- ERROR MESSAGE --}}
    @if ($errors->any())
    <div class="alert-error">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif -->

    {{-- ADD / EDIT EMPLOYEE FORM --}}
    <div class="form-section" id="add-employee-form">
        <h2>{{ isset($editEmployee) ? 'Edit Employee' : 'Add New Employee' }}</h2>

        <form method="POST"
            action="{{ isset($editEmployee)
                ? route('hr-management.update', $editEmployee->id)
                : route('hr-management.store') }}">

            @csrf
            @if(isset($editEmployee))
                @method('PUT')
            @endif

            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name"
                        value="{{ old('full_name', $editEmployee->full_name ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email"
                        value="{{ old('email', $editEmployee->email ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="active"
                            {{ (isset($editEmployee) && $editEmployee->status=='active') ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive"
                            {{ (isset($editEmployee) && $editEmployee->status=='inactive') ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                @if(isset($editEmployee))
                    <button type="submit" class="btn-primary">Update Employee</button>
                    <a href="{{ route('hr-management') }}" class="btn-secondary">Cancel</a>
                @else
                    <button type="submit" class="btn-primary">Create Employee</button>
                @endif
            </div>

        </form>
    </div>

</div>