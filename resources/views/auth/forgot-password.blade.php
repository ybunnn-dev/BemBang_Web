<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
        <link rel="icon" type="image/png" href="{{ asset('images/hotel-logo.svg') }}">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/forgot-pass.css') }}"> 
        <link rel="stylesheet" href="{{ asset('css/app.css') }}"> 
    </head>

    <body>
        <div class="container" id="login-box">
            <form id="loginForm" method="POST" action="{{ route('password.email') }}">
                @csrf
                <h1>Forgot Password</h1>
                <p>Please enter your email.</p>
                <input type="email" name="email" class="form-control" id="formControlInput" placeholder="Enter E-mail" required>
                <button type="submit" class="btn btn-primary" id="loginButton">Send Code</button>
                <a href="{{ url('/') }}">Return to Login</a>
            </form>
        </div>
    </body>
</html>

<!--
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
-->
