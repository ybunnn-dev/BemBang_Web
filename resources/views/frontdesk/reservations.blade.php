@extends('layouts.frontdesk')
@section('title', 'Reservations')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/reservations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reserve-modal.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/frontdesk-dashboard.js') }}"></script>
@endsection

@section('content')  
    <script>
        current_id = @json($current_id ?? null);
        reserves = @json($reservation);
    </script>
    <div id="main-label">
        <img src="{{ asset('images/reserve2.svg') }}">
        <h3>Reservations</h3>
    </div>
    <div id="content-card">
        <!-- Labels Row -->
        <div class="input-labels">
            <label for="search-table" id="search-label">Search for rooms?</label>
            <label for="room-type-dropdown" id="dropdown-label-roomtype">Room Types</label>
            <label for="status-dropdown" id="date-time-label">Date</label>
        </div>

        <!-- Input Fields Row -->
        <div class="input-group">
            <input type="text" class="form-control" id="search-table" placeholder="Search...">

            <!-- Room Types Dropdown -->
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="room-type-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    All Room Types
                </button>
                <ul class="dropdown-menu" aria-labelledby="room-type-dropdown">
                    <li><a class="dropdown-item" href="#">Bembang Standard</a></li>
                    <li><a class="dropdown-item" href="#">Bembang Twin</a></li>
                    <li><a class="dropdown-item" href="#">Bembang Family</a></li>
                    <li><a class="dropdown-item" href="#">Deluxe</a></li>
                    <li><a class="dropdown-item" href="#">Suite</a></li>
                </ul>
            </div>

            <!-- Status Dropdown -->
            <button type="button" class="btn btn-light">Enter Date</button>
            <button type="button" class="btn btn-light" id="reserve-button" data-bs-toggle="modal" data-bs-target="#reserve-modal">Reserve Now</button>
            @include('components.reserve-modal', ['modalId' => 'reserve-modal', 'title' => 'Reserve Room'])
            <button type="button" class="btn btn-primary" id="scan-qr-button">
                <img src="{{ asset('images/qr2.svg') }}" width="15px" height="16px">
                Scan QR Code
            </button>
        </div>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">TRANSACT ID</th>
                        <th scope="col">ROOM #</th>
                        <th scope="col">ROOM TYPE</th>
                        <th scope="col">GUEST NAME</th>
                        <th scope="col">CHECK-IN</th>
                        <th scope="col">CHECK-OUT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservation as $reserve)
                    <tr onclick="checkinExistingReserve('{{ $reserve['id'] }}')">
                        <th scope="row"  class="reserve-id">{{ $reserve['id'] }}</th>
                        <td>{{ $reserve['room']['number'] }}</td>
                        <td>{{ $reserve['room']['type'] }}</td>
                        <td>{{ $reserve['guest']['firstName'] . ' ' . $reserve['guest']['lastName'] }}</td>
                        <td>
                            {{ $reserve['checkin']['date'] }}<br>
                            <p style="font-size: 13px;">{{ $reserve['checkin']['time'] ? date('h:i A', strtotime($reserve['checkin']['time'])) : '12:00 PM' }}</p>
                        </td>
                        <td>
                            {{ $reserve['checkout']['date'] }}<br>
                            <p style="font-size: 13px;">{{ $reserve['checkout']['time'] ? date('h:i A', strtotime($reserve['checkout']['time'])) : '12:00 PM' }}</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('components.invoice-modal')
    @include('components.checkin-reserve-pay')
    @include('components.checkin-reserve')
    @include('components.confirm-cancel')
    @include('components.cancel-transact-confirm')
@endsection
@section('extra-scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="{{ asset('js/checkin-modal.js') }}"></script>
    <script src="{{ asset('js/book-modal.js') }}"></script>
    <script src="{{ asset('js/reserve-modal.js') }}"></script>
    <script src="{{ asset('js/checkin-reserve.js') }}"></script>
@endsection

