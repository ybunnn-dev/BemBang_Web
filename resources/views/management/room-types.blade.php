@extends('layouts.management')
@section('title', 'Room Types')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/room-types.css') }}">
@endsection

@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            console.log(@json($roomTypes));
        });
    </script>
    <div id="main-label">
        <img src="{{ asset('images/bed-icon.svg') }}">
        <h3>Room Types</h3>
    </div>

    <div class="main-container-flex">
        <div class="rooms-row1">
            @foreach ($roomTypes->take(2) as $roomType)
            <div class="card1" onclick="gotoSpecificType('{{ $roomType->_id }}')">
                    <div class="contents-flex">
                        <img src="{{ asset($roomType->images[0]) }}" width="140px" height="150px;">
                        <div class="type-labels">
                            <h5>{{ $roomType->type_name }}</h5>
                            <div class="bembang-values">
                                <div class="ratings">
                                    <img src="{{ asset('images/star.svg') }}" width="18px" height="18px;">
                                    <p>
                                        @if(isset($roomType->average_rating))
                                            {{ $roomType->average_rating }} ({{ count($roomType->reviews) }} Reviews)
                                        @else
                                            No reviews yet
                                        @endif
                                    </p>
                                </div>
                                <div class="guest-num">
                                    <img src="{{ asset('images/users.svg') }}" width="18px" height="18px;">
                                    <p>{{ $roomType->guest_num }} Guests</p>
                                </div>
                                <div class="room-am">
                                    <img src="{{ asset('images/wifi.svg') }}" width="18px" height="18px;">
                                    <p>WiFi</p>
                                </div>
                                <h6 class="rate">P {{ number_format($roomType->rates->checkin_12h, 2) }}/12HR</h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="rooms-row2">
            @foreach ($roomTypes->skip(2)->take(2) as $roomType)
                <div class="card1" onclick="gotoSpecificType('{{ $roomType->_id }}')">
                    <div class="contents-flex">
                        <img src="{{ asset($roomType->images[0]) }}" width="140px" height="150px;">
                        <div class="type-labels">
                            <h5>{{ $roomType->type_name }}</h5>
                            <div class="bembang-values">
                                <div class="ratings">
                                    <img src="{{ asset('images/star.svg') }}" width="18px" height="18px;">
                                    <p>
                                        @if(isset($roomType->average_rating))
                                            {{ $roomType->average_rating }} ({{ count($roomType->reviews) }} Reviews)
                                        @else
                                            No reviews yet
                                        @endif
                                    </p>
                                </div>
                                <div class="guest-num">
                                    <img src="{{ asset('images/users.svg') }}" width="18px" height="18px;">
                                    <p>{{ $roomType->guest_num }} Guests</p>
                                </div>
                                <div class="room-am">
                                    <img src="{{ asset('images/wifi.svg') }}" width="18px" height="18px;">
                                    <p>WiFi</p>
                                </div>
                                <h6 class="rate">P {{ number_format($roomType->rates->checkin_12h, 2) }}/12HR</h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card5" onclick="gotoSpecificType('{{ $roomType->_id }}')">
            @foreach ($roomTypes->skip(4)->take(1) as $roomType)
                <div class="contents-flex">
                    <img src="{{ asset($roomType->images[0]) }}" width="140px" height="150px;">
                    <div class="type-labels">
                        <h5>{{ $roomType->type_name }}</h5>
                        <div class="bembang-values">
                            <div class="ratings">
                                <img src="{{ asset('images/star.svg') }}" width="18px" height="18px;">
                                <p>
                                    @if(isset($roomType->average_rating))
                                        {{ $roomType->average_rating }} ({{ count($roomType->reviews) }} Reviews)
                                    @else
                                        No reviews yet
                                    @endif
                                </p>
                            </div>
                            <div class="guest-num">
                                <img src="{{ asset('images/users.svg') }}" width="18px" height="18px;">
                                <p>{{ $roomType->guest_num }} Guests</p>
                            </div>
                            <div class="room-am">
                                <img src="{{ asset('images/wifi.svg') }}" width="18px" height="18px;">
                                <p>WiFi</p>
                            </div>
                            <h6 class="rate">P {{ number_format($roomType->rates->checkin_12h, 2) }}/12HR</h6>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/room.js') }}"></script>
@endsection