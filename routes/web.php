<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Frontdesk\FrontdeskDashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\MongoRoomController;
use App\Http\Controllers\Management\RoomTypeController;
use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Log;
use App\Models\MongoRoomType;
use Illuminate\Http\Request;
use App\Http\Controllers\SpecificRoomController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\MongoMembership;
use App\Http\Controllers\TransactionController;
use App\Models\Transaction;
use MongoDB\BSON\ObjectId;
use App\Http\Controllers\ManagementController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BookingController;

// For web-based check-in (if needed)
Route::post('/bookings/checkin', [BookingController::class, 'checkin'])
    ->name('bookings.checkin.web')
    ->middleware('auth'); // Add auth middleware if needed
Route::get('/mongo-check', function () {
    try {
        $count = MongoRoomType::count();
        Log::info("MongoDB connection successful. Documents in 'room_types': $count");
        return "MongoDB connection successful. room_types count: $count";
    } catch (\Exception $e) {
        Log::error("MongoDB connection error: " . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
});

Route::get('/', function(){
    return view('auth/login');
})->name('def');

Route::get('/home', function () {
    return view('auth/login');
})->name('home');

Route::post('/transactions/update-status', [TransactionController::class, 'updateStatus']);
Route::post('/transactions/checkout', [TransactionController::class, 'checkout']);
Route::post('/transactions/cancel', [TransactionController::class, 'cancelTransaction']);
Route::post('/transactions/refund', [TransactionController::class, 'refundTransaction']);

Route::prefix('frontdesk')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('frontdesk.dashboard'); // Redirect to dashboard
    })->name('frontdesk');

    Route::get('/dashboard', [FrontdeskDashboardController::class, 'index'])
        ->name('frontdesk.dashboard');

    Route::post('/transactions', [TransactionController::class, 'store']);

    Route::get('/view-rooms', [MongoRoomController::class, 'frontdeskRoom'])->name('frontdesk.view_rooms');
    
    Route::get('/room-details/{id}', [MongoRoomController::class, 'redirectToRoomDetails'])
    ->name('frontdesk.room-details');

    Route::get('/bookings/{id?}', [TransactionController::class, 'getBooking'])
    ->name('frontdesk.bookings');
    
    Route::get('/reservations/{id?}', [TransactionController::class, 'getReservation'])->name('frontdesk.reservations');

    Route::get('/frontdesk/msg', function () {
        return view('frontdesk.msg');
    })->name('frontdesk.msg');
    
    Route::get('/frontdesk/guest', [GuestController::class, 'index'])->name('frontdesk.guest');

    Route::get('/current-guest/{id}', [GuestController::class, 'gotoSpecificGuest'])
    ->name('frontdesk.current-guest');
    
    Route::get('/guest-history', function () {
        return view('frontdesk.guest-history');
    })->name('frontdesk.guest-history');
    
    Route::get('/myprofile', function () {
        return view('frontdesk.myprofile');
    })->name('frontdesk.myprofile');
    
});

Route::post('/transactions/process-payment', [TransactionController::class, 'processPayment']);

// Management Dashboard Route with Authentication
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/get-guests', [GuestController::class, 'getGuests'])->name('get-guest');
    Route::get('/get-checkin-data', [CheckinController::class, 'getCheckInDetails'])->name('get-checkin-data');

    // Add the new route for manage rooms
    Route::get('/management/manage-rooms', [MongoRoomController::class, 'view'])->name('management.manage-rooms');

    // New route for specific-room
    Route::get('/management/specific-room/{id}', [SpecificRoomController::class, 'show'])->name('management.specific-room');

    Route::get('/management/room-types', [RoomTypeController::class, 'index'])->name('management.room-types');

    Route::get('/management/specific-type/{id}', [RoomTypeController::class, 'show'])->name('management.specific-type');

    // New route for promo
    Route::get('/management/promo', function () {
        return view('management.promo'); // Uses layouts/management.blade.php
    })->name('management.promo');

    // New route for discounts
    Route::get('/management/discounts', function () {
        return view('management.discounts'); // Uses layouts/management.blade.php
    })->name('management.discounts');
    
    // New route for vouchers
    Route::get('/management/vouchers', function () {
        return view('management.voucher'); // Uses layouts/management.blade.php
    })->name('management.vouchers');
    
    // New route for points
    Route::get('/management/points', [MongoMembership::class, 'index'])->name('management.points');

     // Guest Route
     Route::get('/management/guest', function () {
        return view('management.guest'); // Uses layouts/management.blade.php
    })->name('management.guest');

    // Employee Route
    Route::get('/management/employee', function () {
        return view('management.employee'); // Uses layouts/management.blade.php
    })->name('management.employee');

    // History Route
    Route::get('/management/history', function () {
        return view('management.history'); // Uses layouts/management.blade.php
    })->name('management.history');

    // FAQ Route
    Route::get('/management/faq', function () {
        return view('management.faq'); // Uses layouts/management.blade.php
    })->name('management.faq');

    // In routes/web.php
    Route::get('/management/performance', [ManagementController::class, 'getPerformance'])
    ->name('management.performance');

    Route::get('/management/dashboard', [ManagementController::class, 'getPerformanceDashboard'])
    ->name('management.dashboard');

    Route::get('/management/myprofile', function () {
        return view('management.myprofile');
    })->name('management.myprofile');
    });


Route::post('/update-type', [RoomTypeController::class, 'update'])->name('management.update-type');
Route::post('/update-rates', [RoomTypeController::class, 'updateRates'])->name('management.update-rates');
Route::post('/management/insert-room', [MongoRoomController::class, 'addRoom'])->name('management.insert-room');


/*since this one worked?
Route::post('/update-type', function(Request $request) {
    // Get the payload sent by the JavaScript
    $payload = $request->all();

    // Log the payload for debugging
    Log::info('Received payload:', $payload);

    // Return the payload back to JavaScript
    return response()->json($payload);
});*/

Route::post('/test-update', function () {
    return response()->json(['putanginamo' => true]);
})->name('test.update');

Route::get('/redirect', [RolesController::class, 'redirectBasedOnRole'])
    ->middleware(['auth', 'verified'])
    ->name('role.redirect');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile-update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/mongo-check', function () {
    try {
        // Get the MongoDB client directly
        $client = DB::connection('mongodb')->getMongoClient();
        $database = $client->bembang_hotel;  // Your DB name
        $collection = $database->users;  // Collection name

        // Fetch all documents from the collection
        $documents = $collection->find()->toArray();  // Use the find method for all documents

        // Log the documents to verify
        Log::info('Fetched MongoDB Documents: ', $documents);

        // Return as JSON for inspection
        return response()->json($documents);
    } catch (\Exception $e) {
        Log::error("MongoDB connection error: " . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
});
require __DIR__.'/auth.php';
