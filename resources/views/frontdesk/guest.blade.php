@extends('layouts.frontdesk')
@section('title', 'Guests')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/guest.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/frontdesk-dashboard.js') }}"></script>
@endsection

@section('content')  
    <div id="main-label">
        <img src="{{ asset('images/profile.svg') }}">
        <h3>Guests</h3>
    </div>
    <div id="content-card">
        <!-- Labels Row -->
        <div class="input-labels">
            <label for="search-table" id="search-label">Search for guests?</label>
            <label for="room-type-dropdown" id="dropdown-label-roomtype">Room Types</label>
            <label for="status-dropdown" id="dropdown-label-status">Status</label>
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
            <button type="button" class="btn btn-primary" id="scan-qr-button">
                <img src="{{ asset('images/qr2.svg') }}" width="15px" height="16px">
                Scan QR Code
            </button>
        </div>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="guest-id-column">GUEST ID</th>
                        <th scope="col" class="guest-name-column">GUEST NAME</th>
                        <th scope="col">MEMBERSHIP STATUS</th>
                        <th scope="col">RECENT TRANSACTION</th>
                        <th scope="col">TRANSACTION DATE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr onclick="window.location.href='/frontdesk/current-guest'">
                        <th scope="row" class="guest-id-column2">1001</th>
                        <td class="guest-name-column2">Giannis Akoynagtatampo</td>
                        <td>
                            <div class="membership-type">
                                <img src="{{ asset('images/elite-icon.svg') }}" width="20px" height="20px">
                                <p>ELITE</p>
                            </div>
                        </td>   
                        <td><div class="status-div">Reservation</div></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr onclick="window.location.href='/frontdesk/room-details'">
                        <th scope="row" class="guest-id-column2">1001</th>
                        <td class="guest-name-column2">Giannis Akoynagtatampo</td>
                        <td>
                            <div class="membership-type">
                                <img src="{{ asset('images/elite-icon.svg') }}" width="20px" height="20px">
                                <p>ELITE</p>
                            </div>
                        </td>   
                        <td><div class="status-div">Reservation</div></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr onclick="window.location.href='/frontdesk/room-details'">
                        <th scope="row" class="guest-id-column2">1001</th>
                        <td class="guest-name-column2">Giannis Akoynagtatampo</td>
                        <td>
                            <div class="membership-type">
                                <img src="{{ asset('images/elite-icon.svg') }}" width="20px" height="20px">
                                <p>ELITE</p>
                            </div>
                        </td>   
                        <td><div class="status-div">Reservation</div></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr onclick="window.location.href='/frontdesk/room-details'">
                        <th scope="row" class="guest-id-column2">1001</th>
                        <td class="guest-name-column2">Giannis Akoynagtatampo</td>
                        <td>
                            <div class="membership-type">
                                <img src="{{ asset('images/elite-icon.svg') }}" width="20px" height="20px">
                                <p>ELITE</p>
                            </div>
                        </td>   
                        <td><div class="status-div">Reservation</div></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr onclick="window.location.href='/frontdesk/room-details'">
                        <th scope="row" class="guest-id-column2">1001</th>
                        <td class="guest-name-column2">Giannis Akoynagtatampo</td>
                        <td>
                            <div class="membership-type">
                                <img src="{{ asset('images/elite-icon.svg') }}" width="20px" height="20px">
                                <p>ELITE</p>
                            </div>
                        </td>   
                        <td><div class="status-div">Reservation</div></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr onclick="window.location.href='/frontdesk/room-details'">
                        <th scope="row" class="guest-id-column2">1001</th>
                        <td class="guest-name-column2">Giannis Akoynagtatampo</td>
                        <td>
                            <div class="membership-type">
                                <img src="{{ asset('images/elite-icon.svg') }}" width="20px" height="20px">
                                <p>ELITE</p>
                            </div>
                        </td>   
                        <td><div class="status-div">Reservation</div></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr onclick="window.location.href='/frontdesk/room-details'">
                        <th scope="row" class="guest-id-column2">1001</th>
                        <td class="guest-name-column2">Giannis Akoynagtatampo</td>
                        <td>
                            <div class="membership-type">
                                <img src="{{ asset('images/elite-icon.svg') }}" width="20px" height="20px">
                                <p>ELITE</p>
                            </div>
                        </td>   
                        <td><div class="status-div">Reservation</div></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr onclick="window.location.href='/frontdesk/room-details'">
                        <th scope="row" class="guest-id-column2">1001</th>
                        <td class="guest-name-column2">Giannis Akoynagtatampo</td>
                        <td>
                            <div class="membership-type">
                                <img src="{{ asset('images/elite-icon.svg') }}" width="20px" height="20px">
                                <p>ELITE</p>
                            </div>
                        </td>   
                        <td><div class="status-div">Reservation</div></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr onclick="window.location.href='/frontdesk/room-details'">
                        <th scope="row" class="guest-id-column2">1001</th>
                        <td class="guest-name-column2">Giannis Akoynagtatampo</td>
                        <td>
                            <div class="membership-type">
                                <img src="{{ asset('images/elite-icon.svg') }}" width="20px" height="20px">
                                <p>ELITE</p>
                            </div>
                        </td>   
                        <td><div class="status-div">Reservation</div></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection

