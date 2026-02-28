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
        <h1>Role & Permission Management</h1>
    </div>

    <div class="form-section">
        <div class="table-container" style="margin-bottom:20px;">
            <div class="card-header">
                <h3 class="card-title">Select Role</h3>
            </div>

            <div style="padding:20px;">
                <form method="GET" action="{{ url('/role-permission') }}">
                    <select name="role_id"
                        onchange="this.form.submit()"
                        class="form-control"
                        style="max-width:300px;">
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ $selectedRoleId == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->role_name) }}
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <div class="form-section">
        <form method="POST" action="{{ url('/role-permission') }}">
            @csrf
            <input type="hidden" name="role_id" value="{{ $selectedRoleId }}">

            @foreach($permissions as $module => $modulePermissions)

            <div class="table-container" style="margin-bottom:25px;">
                <div class="card-header">
                    <h3 class="card-title">{{ ucfirst($module) }}</h3>
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            @foreach($modulePermissions as $permission)
                            <th style="text-align:center;">
                                {{ ucfirst($permission->action) }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($modulePermissions as $permission)
                            <td style="text-align:center;">
                                <input type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->id }}"
                                    {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

            @endforeach

            <div class="form-actions">
                <button class="btn-primary">
                    Save Permissions
                </button>
            </div>

        </form>
    </div>

</div>