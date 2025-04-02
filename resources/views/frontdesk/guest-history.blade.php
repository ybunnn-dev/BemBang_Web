@extends('layouts.frontdesk')
@section('title', 'Guest History')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/guest-history.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/frontdesk-dashboard.js') }}"></script>
@endsection

@section('content')  
    <button id="exit-button" onclick="window.location.href='/frontdesk/current-guest'"k="goBackToGuestList()">
        <img src="{{ asset('images/arrow-back.svg') }}" width="14px" height="14px">
        Return
    </button>
    <div id="content-card">
        <!-- Labels Row -->
         <div class="header-content">
            <img src="{{ asset('images/history.svg') }}" width="30px" height="30px">
            <h3>Giannis' History</h3>
         </div>
        <div class="input-labels">
            <label for="search-table" id="search-label">Search for rooms?</label>
            <label for="room-type-dropdown" id="dropdown-label-roomtype">Room Types</label>
            <label for="status-dropdown" id="dropdown-label-status">Status</label>
            <label for="status-dropdown" id="date-time-label">Date and Time</label>
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
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="status-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    All Statuses
                </button>
                <ul class="dropdown-menu" aria-labelledby="status-dropdown" id="status-menu">
                    <li><a class="dropdown-item" href="#">Available</a></li>
                    <li><a class="dropdown-item" href="#">Occupied</a></li>
                    <li><a class="dropdown-item" href="#">Under Maintenance</a></li>
                    <li><a class="dropdown-item" href="#">Cleaning</a></li>
                </ul>
            </div>
            <button type="button" class="btn btn-light">Enter Date</button>
        </div>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" id="main-header">TRANSACT ID</th>
                        <th scope="col">ROOM TYPE</th>
                        <th scope="col">STATUS</th>
                        <th scope="col">TRANSACT DATE</th>
                        <th scope="col">CHECK-IN</th>
                        <th scope="col">CHECK-OUT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr onclick="window.location.href='/frontdesk/room-details'">
                        <th scope="row">1001</th>
                        <td>Bembang Standard</td>
                        <td><div class="status-div">Available</div></td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Reserved</div></td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Maintenance</div></td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Booking</div></td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Cleaning</div></td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Maintenance</div></td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Maintenance</div></td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Maintenance</div></td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Maintenance</div></td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Maintenance</div></td>
                        <td>Jane Smith</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection


