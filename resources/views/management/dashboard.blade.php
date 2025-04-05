@extends('layouts.management')
@section('title', 'Dashboard')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/dashboard.css') }}">
@endsection

@section('content')
    <div id="main-label">
        <img src="{{ asset('images/dashboard-log.svg') }}">
        <h3>Dashboard</h3>
    </div>
    <div class="top-card">
        <div class="checkin-card">
            <img src="{{ asset('images/check-in.svg') }}">
            <h5>999</h5>
            <p>Total Check Ins</p>
        </div>
        <div class="booking-card">
            <img src="{{ asset('images/book-logo.svg') }}">
            <h5>999</h5>
            <p>Total Bookings</p>
        </div>
        <div class="reservation-card">
            <img src="{{ asset('images/reserve.svg') }}">
            <h5>999</h5>
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
            </div>
            <div class="room-stat-card">
                <div class="p1">
                    <div class="av-room">
                        <img src="{{ asset('images/avaiable-icon.svg') }}" width="70px" height="70px">
                        <div class="room-contents">
                            <h2>999</h2>
                            <p>Available Rooms</p>
                        </div>
                    </div>
                    <div class="oc-room">
                        <img src="{{ asset('images/reserved.svg') }}" width="70px" height="70px">
                        <div class="room-contents">
                            <h2>999</h2>
                            <p>Occupied Rooms</p>
                        </div>
                    </div>
                </div>
                <div class="p2">
                    <div class="uc-room">
                        <img src="{{ asset('images/cleaning.svg') }}" width="70px" height="70px">
                        <div class="room-contents">
                            <h2>999</h2>
                            <p>Under Cleaning</p>
                        </div>
                    </div>
                    <div class="um-room">
                        <img src="{{ asset('images/maintenance.svg') }}" width="70px" height="70px">
                        <div class="room-contents">
                            <h2>999</h2>
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
                    <h1>P 100K</h1>
                </div>
            </div>
            <div class="top-rooms">
                <div class="card-title">
                    <img src="{{ asset('images/rankings.svg') }}" width="27px" height="27px">
                    <h5>Top Rooms</h5>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/dashboard.js') }}"></script>
@endsection