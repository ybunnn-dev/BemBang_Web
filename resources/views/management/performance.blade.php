@extends('layouts.management')
@section('title', 'Performance')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/performance.css') }}">
@endsection

@section('content')
    <script>
        transactions = @json($transactions);
    
        // Count transactions per room type
        const roomTypeCounts = {};
        
        transactions.forEach(transaction => {
            if (transaction.room_details && transaction.room_details.type_name) {
                const roomType = transaction.room_details.type_name;
                roomTypeCounts[roomType] = (roomTypeCounts[roomType] || 0) + 1;
            }
        });
        
        console.log('Transactions per room type:', roomTypeCounts);
    </script>
    <div id="main-label">
        <img src="{{ asset('images/performance.svg') }}">
        <h3>Business Performance</h3>
    </div>
    <div class="main-contents">
        <div class="actions">
            <div class="select-grp">
                <h6>Filter</h6>
                <select class="form-select" aria-label="Default select">
                    <option selected="">Open this select menu</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
            <button type="button" id="pdf-btn" class="btn btn-primary">Export PDF</button>
            <button type="button" class="btn btn-primary">Export CSV</button>
        </div>
        <div class="reports">
            <div class="rev-card">
                <img src="{{ asset('images/rev.svg') }}">
                <h5>P {{ number_format($metrics['total_revenue'], 2) }}</h5>
                <p>Revenue</p>
            </div>
            <div class="checkin-card">
                <img src="{{ asset('images/check-in.svg') }}">
                <h5>{{ $metrics['active_transactions_count'] }}</h5>
                <p>Total Check Ins</p>
            </div>
            <div class="book-card">
                <img src="{{ asset('images/book-logo.svg') }}">
                <h5>{{ $metrics['booking_count'] }}</h5>
                <p>Total Bookings</p>
            </div>
            <div class="reserve-card">
                <img src="{{ asset('images/reserve.svg') }}">
                <h5>{{ $metrics['reservation_count'] }}</h5>
                <p>Total Reservation</p>
            </div>
        </div>
        <div class="metrics">
            <div class="metrics-head">
                <h5>Occupancy Rate</h5>
            </div>
            <canvas id="myChart" width="300px" height="80px"></canvas>
        </div>
        <div class="top-rooms">
            <div class="top1">
                <h5>Most Occupied Rooms</h5>
                <canvas id="occupiedRoomsChart" width="400" height="200"></canvas>
            </div>
            <div class="top2">
                <h5>Top Rated Rooms</h5>
                <canvas id="topRatedRoomsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
@endsection
@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/room.js') }}"></script>
    <script src="{{ asset('js/management/performance.js') }}"></script>
@endsection