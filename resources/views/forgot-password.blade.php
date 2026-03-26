@extends('layouts.app')

@section('main')

<div class="login-page">

    <div class="header-section">
        <h2>Forgot Password</h2>
        <p>Enter your email to receive reset link</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label>Email Address</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <button type="submit" class="btn-submit">Send Reset Link</button>

        <p style="text-align:center; margin-top:10px;">
            <a href="{{ route('login') }}">Back to Login</a>
        </p>

        {{-- Error --}}
        @if ($errors->any())
            <div style="color:red; margin-top:10px; text-align:center;">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Success --}}
        @if(session('success'))
            <div style="color:green; margin-top:10px; text-align:center;">
                {{ session('success') }}
            </div>
        @endif

    </form>
</div>

@endsection