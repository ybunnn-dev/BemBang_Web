@extends('layouts.management')
@section('title', 'Manage Rooms')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/manage-rooms.css') }}">
@endsection

@section('content')
    <script>
        // Safely pass PHP data to JavaScript
        window.allRoomNum = @json($rooms->count());
        console.log(@json($rooms));
    </script>
    <div id="main-label">
        <img src="{{ asset('images/bed-icon.svg') }}">
        <h3>Manage Rooms</h3>
    </div>
    <button type="button" class="btn btn-primary" id="add-button" data-bs-toggle="modal" data-bs-target="#add-room-modal">Add Room</button>
    @include('components.add-modal', ['modalId' => 'add-room-modal', 'title' => 'Add Room'])
    <div id="content-card">
        <!-- Labels Row -->
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
                        <th scope="col" class="wan">ROOM #</th>
                        <th scope="col">ROOM TYPE</th>
                        <th scope="col">STATUS</th>
                        <th scope="col">GUEST NAME</th>
                        <th scope="col">CHECK-IN</th>
                        <th scope="col">CHECK-OUT</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($rooms as $room)
                    <tr onclick="gotoSpecificRoom('{{ $room->id }}')">
                        <th scope="row" class="wan">{{ $room->room_no }}</th>
                        <td>{{ $room->room_type->type_name }}</td>
                        <td><div class="status-div">{{ ucfirst($room->status) }}</div></td>
                        <td>John Doe</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('components.add-room-confirmation')
@endsection
@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/room.js') }}"></script>
@endsection