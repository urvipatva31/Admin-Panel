@extends('layouts.app')
@section('main')

<body>
    <div class="login-page">
        <div class="header-section">
            <h2>Admin Portal</h2>
            <p>Create your account to get started</p>
        </div>

        @if ($errors->any())
        <div style="color:red">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('register.post') }}" method="post" class="wide-form">

            @csrf


            <div class="form-row">
                <div class="input-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" placeholder="John Doe" required>
                </div>
                <div class="input-group">
                    <label for="useremail">Email Address</label>
                    <input type="email" name="useremail" id="useremail" placeholder="email@example.com" required>
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label for="role_selection">Select Your Role</label>
                    <select name="role_selection" id="role_selection" required>
                        <option value="">-- Choose Role --</option>
                        <option value="1">Super Admin</option>
                        <option value="2">Admin</option>
                        <option value="3">HR</option>
                        <option value="4">Manager</option>
                        <option value="5">Employee</option>
                    </select>
                </div>
                <div class="input-group">
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </div>
                <div class="input-group">
                    <label for="cpassword">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="cpassword" placeholder="••••••••" required>

                </div>
            </div>

            <button type="submit" class="btn-submit">Register</button>
            <p class="register-link">Already registered? <a href="login">Login here</a></p>
        </form>
    </div>
</body>
@endsection