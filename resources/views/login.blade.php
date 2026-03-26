@extends('layouts.app')
@section('main')

<div class="login-page">

    <div class="header-section">
        <h2>Admin Portal</h2>
        <p>Please enter your credentials to login</p>
    </div>

    <form action="{{ route('login.post') }}" method="post">
        @csrf

        <label for="username">Username (Email)</label>
        <input type="email" name="useremail" id="username" placeholder="admin@example.com" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>

        <button type="submit" class="btn-submit">Login</button>
        <p style="text-align:right; margin-top: 4px;">
            <a href="{{ route('password.request') }}">Forgot Password?</a>
        </p>
        <p class="register-link">Not registered yet? <a href="register">Create an account</a></p>

        @if ($errors->any())
        <div style="color: red; margin-top: 10px; text-align: center;">
            {{ $errors->first() }}
        </div>
        @endif
    </form>
</div>

@endsection