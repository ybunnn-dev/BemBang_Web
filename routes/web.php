<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Frontdesk\FrontdeskDashboardController;
use App\Http\Controllers\RolesController;

Route::get('/', function () {
    return view('auth/login');
});


Route::prefix('frontdesk')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('frontdesk.dashboard'); // Redirect to dashboard
    })->name('frontdesk');

    Route::get('/dashboard', [FrontdeskDashboardController::class, 'index'])
        ->name('frontdesk.dashboard');

    // Add additional routes inside this group
    Route::get('/view-rooms', function () {
        return view('frontdesk.view_rooms');
    })->name('frontdesk.view_rooms');

    Route::get('/room-details', function () {
        return view('frontdesk.specific_room');
    })->name('frontdesk.room-details');

    Route::get('/bookings', function () {
        return view('frontdesk.bookings');
    })->name('frontdesk.bookings');

    Route::get('/reservations', function () {
        return view('frontdesk.reservations');
    })->name('frontdesk.reservations');

    Route::get('/frontdesk/msg', function () {
        return view('frontdesk.msg');
    })->name('frontdesk.msg');
    
    // Route for frontdesk/guest.blade.php
    Route::get('/frontdesk/guest', function () {
        return view('frontdesk.guest');
    })->name('frontdesk.guest');
    
});

// Management Dashboard Route with Authentication
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/management/dashboard', function () {
        return view('management.dashboard'); // Uses layouts/management.blade.php
    })->name('management.dashboard');
});

Route::get('/employee/home', function () {
    return view('employee.home'); // Use dot notation for views
})->middleware(['auth', 'verified'])->name('employee.home'); // Use dot notation for route name

Route::get('/redirect', [RolesController::class, 'redirectBasedOnRole'])
    ->middleware(['auth', 'verified'])
    ->name('role.redirect');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
