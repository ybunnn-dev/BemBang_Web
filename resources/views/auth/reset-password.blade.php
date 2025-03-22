<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
        <link rel="icon" type="image/png" href="{{ asset('images/hotel-logo.svg') }}">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/reset-pass.css') }}"> 
        <link rel="stylesheet" href="{{ asset('css/app.css') }}"> 
    </head>

    <body>
        <div class="container" id="login-box">
            <form id="loginForm" method="POST" action="{{ route('password.email') }}">
                @csrf
                <h1>Reset Password</h1>
                <p>Please enter your new password.</p>
                <input type="email" name="email" class="form-control" id="formControlInput" placeholder="Enter new password" required>
                <input type="email" name="email" class="form-control" id="formControlInput" placeholder="Confirm Password" required>
                <button type="submit" class="btn btn-primary" id="loginButton">Reset Password</button>
                <a href="{{ url('/') }}">Return to Login</a>
            </form>
        </div>
    </body>
</html>

<!--
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        Password Reset Token 
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

      
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
-->