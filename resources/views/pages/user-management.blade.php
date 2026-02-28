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
        <h1>User Management</h1>
        <div class="page-actions">
            <a href="#add-user-form" id="addUserBtn" class="btn-primary">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ ucfirst($user->role->role_name) }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="status-badge {{ $user->status == 'active' ? 'active' : ($user->status == 'inactive' ? 'inactive' : 'pending') }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>
                        @if($user->role->role_name !== 'superadmin')
                        <a href="{{ route('users.edit', $user->id) }}" class="icon-button">
                            <i class="fas fa-edit"></i>
                        </a>

                        <a href="{{ route('users.delete', $user->id) }}"
                            class="icon-button"
                            onclick="return confirm('Are you sure you want to delete this user?');">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                        @else
                        <span style="color:#999;">Protected</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
    {{ $users->links('pagination::default') }}
</div>

    @if ($errors->any())
    <div class="alert-error">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="form-section" id="add-user-form">
        <h2>Add New User</h2>
        <form method="POST"
            action="{{ isset($editUser) ? route('users.update', $editUser->id) : route('users.store') }}">
            @csrf
            @if(isset($editUser))
            @method('PUT')
            @endif


            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $editUser->full_name ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $editUser->email ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role_id" required>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ (isset($editUser) && $editUser->role_id == $role->id) ? 'selected' : '' }}>
                            {{ ucfirst($role->role_name) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>

                    <select name="status">
                        <option value="active" {{ (isset($editUser) && $editUser->status=='active')?'selected':'' }}>Active</option>
                        <option value="pending" {{ (isset($editUser) && $editUser->status=='pending')?'selected':'' }}>Pending</option>
                        <option value="inactive" {{ (isset($editUser) && $editUser->status=='inactive')?'selected':'' }}>Inactive</option>
                    </select>

                </div>
            </div>

            <div class="form-actions">
                @if(isset($editUser))
                <button type="submit" class="btn-primary">Update User</button>

                <a href="{{ route('users') }}" class="btn-secondary">
                    Cancel
                </a>
                @else
                <button type="submit" class="btn-primary">Create User</button>
                @endif
            </div>

        </form>

    </div>

</div>
<script>

</script>