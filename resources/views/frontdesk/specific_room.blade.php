@extends('layouts.frontdesk')
@section('title', 'Rooms')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/room-details.css') }}">
@endsection

@section('content')  
    <script>
        room = @json($room);
        transaction = room.transaction;
        document.addEventListener("DOMContentLoaded", function (){
            console.log(transaction);
        });
    </script>
    <button id="exit-button" onclick="goBackToViewRooms()"><img src="{{ asset('images/arrow-back.svg') }}" width="14px" height="14px">Return</button>
    <img src="{{ asset($room->room_type->images[0]) }}" id="room-profile-pic" style="object-fit: cover;">
    <div class="details-card">
        <div class="room-details-head">
            <img src="{{ asset('images/bed-icon-2.svg') }}">
            <h4>Room #{{ $room->room_no }}</h4>
            <div style="padding: 1px 10px; background-color: #ccc; font-family: 'Poppins'; border-radius: 10px;">{{ ucfirst(strtolower($room->status)) }}</div>
        </div>
        <p id="room-type-label">ROOM TYPE:</p>
        <p id="room-type-value">{{ $room->room_type->type_name }}</p>
        <p id="guest-num-label">AVAILABLE GUESTS:</p>
        <p id="guest-num-value">{{ $room->room_type->guest_num }}</p>

        <hr id="line1">

        <p id="guest-name-label">GUEST:</p>
        <p id="guest-name-value"> {{ ($room->transaction->guest->firstName ?? '') . ' ' . ($room->transaction->guest->lastName ?? '') ?: '--' }}</p>
        <p id="guest-num2-label">NUMBER OF GUESTS:</p>
        <p id="guest-num2-value">{{ $room->transaction->stay_details['guest_num'] ?? '--'}}</p>

        <!-- Check-in Date -->
        <p id="checkin-date-label">CHECK IN DATE:</p>
        <p id="checkin-date-value">
            @php
                $checkinRaw = $room->transaction->stay_details['actual_checkin'] ?? null;
                $checkinDate = null;
                
                if ($checkinRaw) {
                    try {
                        // Handle MongoDB UTCDateTime
                        if (is_object($checkinRaw) && get_class($checkinRaw) === 'MongoDB\BSON\UTCDateTime') {
                            $checkinDate = $checkinRaw->toDateTime();
                        }
                        // Handle numeric timestamp (milliseconds or seconds)
                        elseif (is_numeric($checkinRaw)) {
                            $timestamp = (int)$checkinRaw;
                            $checkinDate = new DateTime();
                            $checkinDate->setTimestamp(strlen((string)$timestamp) === 13 ? $timestamp/1000 : $timestamp);
                        }
                        // Handle string date
                        else {
                            $checkinDate = new DateTime($checkinRaw);
                        }
                    } catch (Exception $e) {
                        $checkinDate = null;
                    }
                }
            @endphp
            {{ $checkinDate ? $checkinDate->format('M j, Y') : '--' }}
        </p>

        <!-- Check-in Time -->
        <p id="checkin-time-label">CHECK IN TIME:</p>
        <p id="checkin-time-value">
            {{ $checkinDate ? $checkinDate->format('g:i A') : '--' }}
        </p>

        <!-- Check-out Date -->
        <p id="checkout-date-label">CHECK OUT DATE:</p>
        <p id="checkout-date-value">
            @php
                $checkoutRaw = $room->transaction->stay_details['expected_checkout'] ?? null;
                $checkoutDate = null;
                
                if ($checkoutRaw) {
                    try {
                        // Handle MongoDB UTCDateTime
                        if (is_object($checkoutRaw) && get_class($checkoutRaw) === 'MongoDB\BSON\UTCDateTime') {
                            $checkoutDate = $checkoutRaw->toDateTime();
                        }
                        // Handle numeric timestamp (milliseconds or seconds)
                        elseif (is_numeric($checkoutRaw)) {
                            $timestamp = (int)$checkoutRaw;
                            $checkoutDate = new DateTime();
                            $checkoutDate->setTimestamp(strlen((string)$timestamp) === 13 ? $timestamp/1000 : $timestamp);
                        }
                        // Handle string date
                        else {
                            $checkoutDate = new DateTime($checkoutRaw);
                        }
                    } catch (Exception $e) {
                        $checkoutDate = null;
                    }
                }
            @endphp
            {{ $checkoutDate ? $checkoutDate->format('M j, Y') : '--' }}
        </p>

        <!-- Check-out Time -->
        <p id="checkout-time-label">CHECK OUT TIME:</p>
        <p id="checkout-time-value">
            {{ $checkoutDate ? $checkoutDate->format('g:i A') : '--' }}
        </p>
    </div>
    <div class="features-card">
        <img src="{{ asset('images/info.svg') }}" width="25px" height="25px">
        <h5>Room Features</h5>
        <hr id="feature-line">  
        
        <div class="features-content">
            <ul class="feature-list">
                @foreach($room->room_type->features as $feature)
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
        <p id="checkin-12">P {{ number_format($room->room_type->rates['checkin_12h'] ?? 0, 2) }}</p>
        <p id="checkin-24">P {{ number_format($room->room_type->rates['checkin_24h'] ?? 0, 2) }}</p>
    </div>
    <div class="reserve-price">
        <img src="{{ asset('images/price-tag.svg') }}" width="25px" height="25px">
        <h5>Reserve Price</h5>
        <hr class="price-line"> 
        <div class="rate-level">
            <p>12HRS:</p>
            <p>24HRS:</p>
        </div> 
        <p id="reserve-12">P {{ number_format($room->room_type->rates['reservation_12h'] ?? 0, 2) }}</p>
        <p id="reserve-24">P {{ number_format($room->room_type->rates['reservation_24h'] ?? 0, 2) }}</p>
    </div>
    <div class="action-buttons">
    <ul>
        <li>
            <button type="button" 
                    class="btn btn-primary" onclick="confirmCheckout()"
                    @if($room->status == 'available') disabled @endif>
                CHECKOUT
            </button>
        </li>
        <li>
            <button type="button" 
                    class="btn btn-primary" 
                    @if($room->status == 'occupied') disabled @endif>
                EDIT STATUS
            </button>
        </li>
    </ul>
    </div>
    <script>
        function goBackToViewRooms() {
            window.location.href = "{{ route('frontdesk.view_rooms') }}";
        }
    </script>

@include('components.invoice-modal')
@include('components.confirm-checkout')
@endsection

@section('scripts')
    <script src="{{ asset('js/frontdesk-room-details.js') }}"></script>
    <script src="{{ asset('js/checkout.js') }}"></script>
@endsection
