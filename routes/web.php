<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Frontdesk\FrontdeskDashboardController;

Route::get('/', function () {
    return view('auth/login');
});


Route::prefix('frontdesk')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('frontdesk.dashboard'); // Redirects to the dashboard
    })->name('frontdesk');

    Route::get('/dashboard', [FrontdeskDashboardController::class, 'index'])
        ->name('frontdesk.dashboard');
});

Route::get('/management/dashboard', function () {
    return view('management.dashboard'); // Uses layouts/management.blade.php
});

Route::get('/employee/home', function () {
    return view('employee.home'); // Use dot notation for views
})->middleware(['auth', 'verified'])->name('employee.home'); // Use dot notation for route name


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
