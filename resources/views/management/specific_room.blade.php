@extends('layouts.management')
@section('title', 'Room Types')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/specific-room.css') }}">
@endsection
@section('content')
<button id="exit-button" onclick="goBacktoRoomList()"><img src="{{ asset('images/arrow-back.svg') }}" width="14px" height="14px">Return</button>
    <img src="{{ asset('images/standard.jpg') }}" id="room-profile-pic">
    <div class="details-card">
        <div class="room-details-head">
            <img src="{{ asset('images/bed-icon-2.svg') }}">
            <h4>Room #{{ $room->room_no }}</h4>
            <div id="room-status">{{ $room->status }}</div>
        </div>
        <p id="room-type-label">ROOM TYPE:</p>
        <p id="room-type-value">{{ $room->room_type->type_name }}</p>
        <p id="guest-num-label">AVAILABLE GUESTS:</p>
        <p id="guest-num-value">{{ $room->room_type->guest_num }}</p>

        <hr id="line1">

        <p id="guest-name-label">GUEST:</p>
        <p id="guest-name-value">--</p>
        <p id="guest-num2-label">NUMBER OF GUESTS:</p>
        <p id="guest-num2-value">--</p>

        <p id="checkin-date-label">CHECK IN DATE:</p>
        <p id="checkin-date-value">--</p>
        <p id="checkout-date-label">CHECK OUT DATE:</p>
        <p id="checkout-date-value">--</p>

        <p id="checkin-time-label">CHECK IN TIME:</p>
        <p id="checkin-time-value">--</p>
        <p id="checkout-time-label">CHECK OUT TIME:</p>
        <p id="checkout-time-value">--</p>
    </div>
    <div class="features-card">
        <img src="{{ asset('images/info.svg') }}" width="25px" height="25px">
        <h5>Room Features</h5>
        <hr id="feature-line">  
        
        <div class="features-content">
            <ul class="feature-list">
                @foreach($features as $feature)
                    <li>
                        <div class="li-content">
                            <img src="{{ asset($feature->feature_icon) }}" width="15px" height="15px">
                            <p>{{ $feature->feature_name }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="checkin-price">
        <img src="{{ asset('images/price-tag.svg') }}" width="25px" height="25px">
        <h5>Check In Price</h5>
        <hr class="price-line">
        <div class="rate-level">
            <p>12HRS:</p>
            <p>24HRS:</p>
        </div> 
        <p id="checkin-12">P {{ number_format($room->room_type->rates->checkin_12h ,2) }}</p>
        <p id="checkin-24">P {{ number_format($room->room_type->rates->checkin_24h ,2) }}</p>
    </div>
    <div class="reserve-price">
        <img src="{{ asset('images/price-tag.svg') }}" width="25px" height="25px">
        <h5>Reserve Price</h5>
        <hr class="price-line"> 
        <div class="rate-level">
            <p>12HRS:</p>
            <p>24HRS:</p>
        </div> 
        <p id="reserve-12">P {{ number_format($room->room_type->rates->reservation_12h, 2) }}</p>
        <p id="reserve-24">P {{ number_format($room->room_type->rates->reservation_24h, 2) }}</p>
    </div>
    <div class="action-buttons">
        <ul>
            <li><button type="button" class="btn btn-primary">EDIT STATUS</button></li>
            <li><button type="button" class="btn btn-primary">CANCEL</button></li>
        </ul>
    </div>
@endsection
@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/dashboard.js') }}"></script>
    <script src="{{ asset('js/management/room.js') }}"></script>
@endsection