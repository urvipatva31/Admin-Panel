@extends('layouts.app')

@section('main')

<div class="login-page">

    <div class="header-section">
        <h2>Reset Password</h2>
        <p>Enter your new password</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <label>Email</label>
        <input type="email" name="email" value="{{ $email }}" required>

        <label>New Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit" class="btn-submit">Reset Password</button>

        {{-- Error --}}
        @if ($errors->any())
            <div style="color:red; margin-top:10px; text-align:center;">
                {{ $errors->first() }}
            </div>
        @endif

    </form>
</div>

@endsection