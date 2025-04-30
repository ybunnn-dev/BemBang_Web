@extends('layouts.frontdesk')
@section('title', 'Rooms')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/view-rooms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/book-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reserve-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkin-modal.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/frontdesk-dashboard.js') }}"></script>
@endsection

@section('content')  
    <script>
        document.addEventListener("DOMContentLoaded", function (){
            console.log(@json($rooms));
            
        });
    </script>
    <div id="main-label">
        <img src="{{ asset('images/bed-icon.svg') }}">
        <h3>Rooms</h3>
    </div>
    <div class="transact-actions">
        <button type="button" class="btn btn-light" id="checkin-button" data-bs-toggle="modal" data-bs-target="#checkInModal1">Check In</button>
        @include('components.check-in-modal1', ['modalId' => 'checkInModal1', 'title' => 'Check In'])
        <button type="button" class="btn btn-light" id="book-button" data-bs-toggle="modal" data-bs-target="#book-modal">Book Now</button>
        @include('components.book-modal', ['modalId' => 'book-modal', 'title' => 'Book Room'])
        <button type="button" class="btn btn-light" id="reserve-button" data-bs-toggle="modal" data-bs-target="#reserve-modal">Reserve Now</button>
        @include('components.reserve-modal', ['modalId' => 'reserve-modal', 'title' => 'Reserve Room'])
    </div>
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
                        <th scope="col">ROOM #</th>
                        <th scope="col">ROOM TYPE</th>
                        <th scope="col">STATUS</th>
                        <th scope="col">GUEST NAME</th>
                        <th scope="col">CHECK-IN</th>
                        <th scope="col">CHECK-OUT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                        <tr onclick="window.location.href='/frontdesk/room-details/{{ $room->id }}'">
                            <th scope="row">{{ $room->room_no }}</th>
                            <td>{{ $room->room_type['type_name'] ?? 'N/A' }}</td>
                            <td>
                                <div class="status-div {{ strtolower($room->status) }}">
                                    {{ ucfirst(strtolower($room->status)) }}
                                </div>
                            </td>
                            <td>
                                {{ ($room->transaction->guest->firstName ?? '') . ' ' . ($room->transaction->guest->lastName ?? '') ?: '--' }}
                            </td>
                            <td>
                            @php
                                $rawValue = $room->transaction->stay_details['actual_checkin'] ?? null;
                                $checkinDate = null;

                                if (!empty($rawValue)) {
                                    if ($rawValue instanceof \MongoDB\BSON\UTCDateTime) {
                                        $checkinDate = \Carbon\Carbon::instance($rawValue->toDateTime());
                                    } elseif (is_string($rawValue) && ctype_digit($rawValue)) {
                                        $checkinDate = \Carbon\Carbon::createFromTimestampMs((int)$rawValue);
                                    } elseif (is_numeric($rawValue)) {
                                        $checkinDate = \Carbon\Carbon::createFromTimestampMs((int)$rawValue);
                                    } elseif (is_array($rawValue) && isset($rawValue['$date']['$numberLong'])) {
                                        $checkinDate = \Carbon\Carbon::createFromTimestampMs((int)$rawValue['$date']['$numberLong']);
                                    } else {
                                        try {
                                            $checkinDate = \Carbon\Carbon::parse($rawValue);
                                        } catch (\Exception $e) {
                                            // invalid format
                                        }
                                    }
                                }
                            @endphp
                            @if($checkinDate)
                                {{ $checkinDate->format('M j, Y') }}<br>
                                <span style="font-size: 13px;">{{ $checkinDate->format('g:i A') }}</span>
                            @else
                                --
                            @endif
                            </td>
                            <td>
                                @php
                                    $rawCheckoutValue = $room->transaction->stay_details['expected_checkout'] ?? null;
                                    $checkoutDate = null;

                                    if (!empty($rawCheckoutValue)) {
                                        if ($rawCheckoutValue instanceof \MongoDB\BSON\UTCDateTime) {
                                            $checkoutDate = \Carbon\Carbon::instance($rawCheckoutValue->toDateTime());
                                        } elseif (is_string($rawCheckoutValue) && ctype_digit($rawCheckoutValue)) {
                                            $checkoutDate = \Carbon\Carbon::createFromTimestampMs((int)$rawCheckoutValue);
                                        } elseif (is_numeric($rawCheckoutValue)) {
                                            $checkoutDate = \Carbon\Carbon::createFromTimestampMs((int)$rawCheckoutValue);
                                        } elseif (is_array($rawCheckoutValue) && isset($rawCheckoutValue['$date']['$numberLong'])) {
                                            $checkoutDate = \Carbon\Carbon::createFromTimestampMs((int)$rawCheckoutValue['$date']['$numberLong']);
                                        } else {
                                            try {
                                                $checkoutDate = \Carbon\Carbon::parse($rawCheckoutValue);
                                            } catch (\Exception $e) {
                                                // invalid format
                                            }
                                        }
                                    }
                                @endphp
                                @if($checkoutDate)
                                    {{ $checkoutDate->format('M j, Y') }}<br>
                                    <span style="font-size: 13px;">{{ $checkoutDate->format('g:i A') }}</span>
                                @else
                                    --
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="{{ asset('js/checkin-modal.js') }}"></script>
    <script src="{{ asset('js/book-modal.js') }}"></script>
    <script src="{{ asset('js/reserve-modal.js') }}"></script>
@endsection


