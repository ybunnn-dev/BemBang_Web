@extends('layouts.frontdesk')
@section('title', 'Bookings')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/bookings.css') }}">
    <link rel="stylesheet" href="{{ asset('css/book-modal.css') }}">
@endsection

@section('scripts')
    
@endsection

@section('content')  
    <script>
        const currentId = @json($highlightId ?? null);
        
        document.addEventListener('DOMContentLoaded', function(){
            console.log("yahoo:", currentId);
        });
        books = @json($bookings);
        console.log(books);
    </script>
    <div id="main-label">
        <img src="{{ asset('images/booking2.svg') }}">
        <h3>Bookings</h3>
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
            <button type="button" class="btn btn-light" id="book-button" data-bs-toggle="modal" data-bs-target="#book-modal">Book Now</button>
            @include('components.book-modal', ['modalId' => 'book-modal', 'title' => 'Book Room'])
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
                @forelse($bookings as $booking)
                    <tr onclick="checkinExistingBook('{{ $booking['id'] }}')">
                        <th scope="row" class="book-id" style="max-width: 150px;">{{ $booking['id'] }}</th>
                        <td class="room-no">{{ $booking['room']['number'] }}</td>
                        <td>{{ $booking['room']['type'] }}</td>
                        <td>{{ $booking['guest']['firstName'] . ' ' . $booking['guest']['lastName'] }}</td>
                        <td>
                            {{ $booking['checkin']['date'] }}<br>
                            <p style="font-size: 13px;">{{ $booking['checkin']['time'] ?: '12:00' }}</p>
                        </td>
                        <td>
                            {{ $booking['checkout']['date'] }}<br>
                            <p style="font-size: 13px;">{{ $booking['checkout']['time'] ?: '12:00' }}</p>
                        </td>
                    </tr>
                @empty
                    {{-- This section will render when $bookings is empty --}}
                    <tr>
                        <td colspan="6" class="text-center">No bookings found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('components.invoice-modal')
    @include('components.checkin-book', ['modalId' => 'checkInBook'])
    @include('components.confirm-checkin-book', ['modalId' => 'confirm-check-book'])
    @include('components.cancel-transact-confirm')
    @include('components.refund')
@endsection

@section('extra-scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="{{ asset('js/checkin-modal.js') }}"></script>
    <script src="{{ asset('js/book-modal.js') }}"></script>
    <script src="{{ asset('js/reserve-modal.js') }}"></script>
    <script src="{{ asset('js/checkin-book.js') }}"></script>
@endsection

