@extends('layouts.management')
@section('title', 'Room Types')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/room-types.css') }}">
@endsection

@section('content')
    <div id="main-label">
        <img src="{{ asset('images/bed-icon.svg') }}">
        <h3>Room Types</h3>
    </div>
    <div class="main-container-flex">
        <div class="rooms-row1">
            <div class="card1" onclick="gotoSpecificType()">
                <div class="contents-flex">
                    <img src="{{ asset('images/rooms/standard.jpg') }}" width="140px" height="150px;">
                    <div class="type-labels">
                        <h5>Bembang Standard</h5>
                        <div class="bembang-values">
                            <div class="ratings">
                                <img src="{{ asset('images/star.svg') }}" width="18px" height="18px;">
                                <p>4.5 (499 Reviews)</p>
                            </div>
                            <div class="guest-num">
                                <img src="{{ asset('images/users.svg') }}" width="18px" height="18px;">
                                <p>2 Guests</p>
                            </div>
                            <div class="room-am">
                                <img src="{{ asset('images/wifi.svg') }}" width="18px" height="18px;">
                                <p>WiFi</p>
                            </div>
                            <h6 class="rate">P 1,499.00/12HR</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card2">
                <div class="contents-flex">
                    <img src="{{ asset('images/rooms/twin.jpg') }}" width="140px" height="150px;">
                    <div class="type-labels">
                        <h5>Bembang Twin</h5>
                        <div class="bembang-values">
                            <div class="ratings">
                                <img src="{{ asset('images/star.svg') }}" width="18px" height="18px;">
                                <p>5.0 (602 Reviews)</p>
                            </div>
                            <div class="guest-num">
                                <img src="{{ asset('images/users.svg') }}" width="18px" height="18px;">
                                <p>2 Guests</p>
                            </div>
                            <div class="room-am">
                                <img src="{{ asset('images/wifi.svg') }}" width="18px" height="18px;">
                                <p>WiFi</p>
                            </div>
                            <h6 class="rate">P 1,999.00/12HR</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="rooms-row2">
            <div class="card3">
                <div class="contents-flex">
                    <img src="{{ asset('images/rooms/family.jpg') }}" width="140px" height="150px;">
                    <div class="type-labels">
                        <h5>Bembang Family</h5>
                        <div class="bembang-values">
                            <div class="ratings">
                                <img src="{{ asset('images/star.svg') }}" width="18px" height="18px;">
                                <p>4.5 (299 Reviews)</p>
                            </div>
                            <div class="guest-num">
                                <img src="{{ asset('images/users.svg') }}" width="18px" height="18px;">
                                <p>10 Guests</p>
                            </div>
                            <div class="room-am">
                                <img src="{{ asset('images/wifi.svg') }}" width="18px" height="18px;">
                                <p>WiFi</p>
                            </div>
                            <h6 class="rate">P 5,499.00/12HR</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card4">
                <div class="contents-flex">
                    <img src="{{ asset('images/rooms/deluxe.jpg') }}" width="140px" height="150px;">
                    <div class="type-labels">
                        <h5>Bembang Deluxe</h5>
                        <div class="bembang-values">
                            <div class="ratings">
                                <img src="{{ asset('images/star.svg') }}" width="18px" height="18px;">
                                <p>4.5 (499 Reviews)</p>
                            </div>
                            <div class="guest-num">
                                <img src="{{ asset('images/users.svg') }}" width="18px" height="18px;">
                                <p>2 Guests</p>
                            </div>
                            <div class="room-am">
                                <img src="{{ asset('images/wifi.svg') }}" width="18px" height="18px;">
                                <p>WiFi</p>
                            </div>
                            <h6 class="rate">P 5,499.00/12HR</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card5">
            <div class="contents-flex">
                    <img src="{{ asset('images/rooms/suite.jpg') }}" width="140px" height="150px;">
                    <div class="type-labels">
                        <h5>Bembang Suite</h5>
                        <div class="bembang-values">
                            <div class="ratings">
                                <img src="{{ asset('images/star.svg') }}" width="18px" height="18px;">
                                <p>4.5 (499 Reviews)</p>
                            </div>
                            <div class="guest-num">
                                <img src="{{ asset('images/users.svg') }}" width="18px" height="18px;">
                                <p>2 Guests</p>
                            </div>
                            <div class="room-am">
                                <img src="{{ asset('images/wifi.svg') }}" width="18px" height="18px;">
                                <p>WiFi</p>
                            </div>
                            <h6 class="rate">P 7,499.00/12HR</h6>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection
@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/room.js') }}"></script>
@endsection