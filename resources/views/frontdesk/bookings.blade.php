@extends('layouts.frontdesk')
@section('title', 'Bookings')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/bookings.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/frontdesk-dashboard.js') }}"></script>
@endsection

@section('content')  
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
            <button type="button" class="btn btn-light" id="book-button">Book Now</button>
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
                    <tr onclick="window.location.href='/frontdesk/room-details'">
                        <th scope="row">1001</th>
                        <td>8</td>
                        <td>Bembang Standard</td>
                        <td>John Doe</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>10</td>
                        <td>Bembang Twin</td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>11</td>
                        <td>Bembang Twin</td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>13</td>
                        <td>Bembang Twin</td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection


