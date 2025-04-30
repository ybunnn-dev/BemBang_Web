@extends('layouts.management')
@section('title', 'Dashboard')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/dashboard.css') }}">
@endsection

@section('content')
    <script>
        transactions = @json($transactions);
    
        // Count transactions per room type
        const roomTypeCounts = {};
        document.addEventListener('DOMContentLoaded', function(){
            console.log(@json($metrics));
        });
        transactions.forEach(transaction => {
            if (transaction.room_details && transaction.room_details.type_name) {
                const roomType = transaction.room_details.type_name;
                roomTypeCounts[roomType] = (roomTypeCounts[roomType] || 0) + 1;
            }
        });
        
        console.log('Transactions per room type:', roomTypeCounts);
    </script>
    <div id="main-label">
        <img src="{{ asset('images/dashboard-log.svg') }}">
        <h3>Dashboard</h3>
    </div>
    <div class="top-card">
        <div class="checkin-card">
            <img src="{{ asset('images/check-in.svg') }}">
            <h5>{{ $metrics['active_transactions_count'] }}</h5>
            <p>Total Check Ins</p>
        </div>
        <div class="booking-card">
            <img src="{{ asset('images/book-logo.svg') }}">
            <h5>{{ $metrics['booking_count'] }}</h5>
            <p>Total Bookings</p>
        </div>
        <div class="reservation-card">
            <img src="{{ asset('images/reserve.svg') }}">
            <h5>{{ $metrics['reservation_count'] }}</h5>
            <p>Total Reservations</p>
        </div>
        <div class="date-card">
            <img src="{{ asset('images/date-time.svg') }}">
            <div class="date-flex">
                <p id="current-date"></p>
                <h2 id="current-time"></h2>
            </div>
        </div>
    </div>
    <div class="bottom-card">
        <div class="bottom-1"> 
            <div class="stat-card">
                <h5>Performance</h5>
                <canvas id="myChart1" width="300px" height="55px"></canvas>
            </div>
            <div class="room-stat-card">
                <div class="p1">
                    <div class="av-room">
                        <img src="{{ asset('images/avaiable-icon.svg') }}" width="70px" height="70px">
                        <div class="room-contents">
                            <h2>{{ $metrics['room_status_count']['available'] }}</h2>
                            <p>Available Rooms</p>
                        </div>
                    </div>
                    <div class="oc-room">
                        <img src="{{ asset('images/reserved.svg') }}" width="70px" height="70px">
                        <div class="room-contents">
                            <h2>{{ $metrics['room_status_count']['occupied'] }}</h2>
                            <p>Occupied Rooms</p>
                        </div>
                    </div>
                </div>
                <div class="p2">
                    <div class="uc-room">
                        <img src="{{ asset('images/cleaning.svg') }}" width="70px" height="70px">
                        <div class="room-contents">
                            <h2>{{ $metrics['room_status_count']['cleaning'] }}</h2>
                            <p>Under Cleaning</p>
                        </div>
                    </div>
                    <div class="um-room">
                        <img src="{{ asset('images/maintenance.svg') }}" width="70px" height="70px">
                        <div class="room-contents">
                            <h2>{{ $metrics['room_status_count']['maintenance'] }}</h2>
                            <p>Under Maintenance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-2">
            <div class="revenue-card">
                <img src="{{ asset('images/revenue.svg') }}">
                    <div class="revenue-flex">
                    <p>Revenue</p>
                    <h1>
                    @php
                        $amount = $metrics['total_revenue'];
                        if ($amount >= 1000000) {
                            echo number_format($amount / 1000000, 1) . 'M';
                        } elseif ($amount >= 1000) {
                            echo number_format($amount / 1000, 1) . 'K';
                        } else {
                            echo number_format($amount);
                        }
                    @endphp
                    </h1>
                </div>
            </div>
            <div class="top-rooms">
                <div class="card-title">
                    <img src="{{ asset('images/rankings.svg') }}" width="27px" height="27px">
                    <h5>Top Rooms</h5>
                </div>
                <canvas id="topOccupiedRoomsChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
@endsection
@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/dashboard.js') }}"></script>
@endsection