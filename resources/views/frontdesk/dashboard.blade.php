@extends('layouts.frontdesk')
@section('title', 'Dashboard')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkin-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/book-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reserve-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/qr-scanner.css') }}">
@endsection
@section('scripts')
    <script src="{{ asset('js/frontdesk-dashboard.js') }}"></script>
@endsection

@section('content')
    <script>
        const transactionsData = @json($transactions);
        const guests = @json($guests);
        const userId = "{{ Auth::user()->_id }}";
    </script>
    <div id="main-label">
        <img src="{{ asset('images/dashboard-log.svg') }}">
        <h3>Dashboard</h3>
    </div>
    <div id="msg_content">
        <h4>Welcome, {{ Auth::user()->firstName }}!</h4>
        <p>We're excited to have you on the team! Your skills are invaluable, and we’re confident you’ll make a great impact. Here’s to a successful future together!</p>
        <img src="{{ asset('images/welcomevai.png') }}">
    </div>
    <div id="info_content">
        <div id="avail-room">
            <img src="{{ asset('images/avaiable-icon.svg') }}" width="70px" height="70px">
            <h1 class="count">{{ $roomCounts['available'] }}</h1>
            <p>Avaiable Rooms</p>
        </div>
        <div id="occupied-room">
            <img src="{{ asset('images/reserved.svg') }}" width="80px" height="80px">
            <h1 class="count">{{ $roomCounts['occupied'] }}</h1>
            <p>Occupied Rooms</p>
        </div>
        <div id="under-cleaning">
            <img src="{{ asset('images/cleaning.svg') }}" width="70px" height="70px">
            <h1 class="count">{{ $roomCounts['maintenance'] }}</h1>
            <p>Under Cleaning</p>
        </div>
        <div id="under-maintenance">
            <img src="{{ asset('images/maintenance.svg') }}" width="70px" height="70px">
            <h1 class="count">{{ $roomCounts['cleaning'] }}</h1>
            <p>Under Cleaning</p>
        </div>
    </div>
    <div id="calendar">
        <div id="outer-circle">
            <div id="inner-circle">
                <h3>23</h3>
                <p>FRIDAY</p>
            </div>
        </div>
        <h4>FEBRUARY</h4>
        <h5>12:19 PM</h5>
    </div>
    <div id="incoming">
        <h5>Incoming Check Ins</h5>
            <div class="incoming-container">  
                <div id="table-border">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="table-contents">
                                        <div class="profile-icon"> </div>
                                        <p class="arrive-time">at 12:19 PM</p>
                                        <h5>Ron Peter Vakal</h5>
                                        <div class="status-arrival">Reserved</div>
                                        <p class="arrival-room-no">#Room 10</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-contents">
                                        <div class="profile-icon"> </div>
                                        <p class="arrive-time">at 12:19 PM</p>
                                        <h5>Ron Peter Vakal</h5>
                                        <div class="status-arrival">Reserved</div>
                                        <p class="arrival-room-no">#Room 10</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-contents">
                                        <div class="profile-icon"> </div>
                                        <p class="arrive-time">at 12:19 PM</p>
                                        <h5>Ron Peter Vakal</h5>
                                        <div class="status-arrival">Reserved</div>
                                        <p class="arrival-room-no">#Room 10</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-contents">
                                        <div class="profile-icon"> </div>
                                        <p class="arrive-time">at 12:19 PM</p>
                                        <h5>Ron Peter Vakal</h5>
                                        <div class="status-arrival">Reserved</div>
                                        <p class="arrival-room-no">#Room 10</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-contents">
                                        <div class="profile-icon"> </div>
                                        <p class="arrive-time">at 12:19 PM</p>
                                        <h5>Ron Peter Vakal</h5>
                                        <div class="status-arrival">Reserved</div>
                                        <p class="arrival-room-no">#Room 10</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-contents">
                                        <div class="profile-icon"> </div>
                                        <p class="arrive-time">at 12:19 PM</p>
                                        <h5>Ron Peter Vakal</h5>
                                        <div class="status-arrival">Reserved</div>
                                        <p class="arrival-room-no">#Room 10</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-contents">
                                        <div class="profile-icon"> </div>
                                        <p class="arrive-time">at 12:19 PM</p>
                                        <h5>Ron Peter Vakal</h5>
                                        <div class="status-arrival">Reserved</div>
                                        <p class="arrival-room-no">#Room 10</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-contents">
                                        <div class="profile-icon"> </div>
                                        <p class="arrive-time">at 12:19 PM</p>
                                        <h5>Ron Peter Vakal</h5>
                                        <div class="status-arrival">Reserved</div>
                                        <p class="arrival-room-no">#Room 10</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-contents">
                                        <div class="profile-icon"> </div>
                                        <p class="arrive-time">at 12:19 PM</p>
                                        <h5>Ron Peter Vakal</h5>
                                        <div class="status-arrival">Reserved</div>
                                        <p class="arrival-room-no">#Room 10</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-contents">
                                        <div class="profile-icon"> </div>
                                        <p class="arrive-time">at 12:19 PM</p>
                                        <h5>Ron Peter Vakal</h5>
                                        <div class="status-arrival">Reserved</div>
                                        <p class="arrival-room-no">#Room 10</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-contents">
                                        <div class="profile-icon"> </div>
                                        <p class="arrive-time">at 12:19 PM</p>
                                        <h5>Ron Peter Vakal</h5>
                                        <div class="status-arrival">Reserved</div>
                                        <p class="arrival-room-no">#Room 10</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
    <div id="check-in" data-bs-toggle="modal" data-bs-target="#checkInModal1">
        <img src="{{ asset('images/check-in.svg') }}" width="40px" height="40px">
        <p>Check In</p>
    </div>
    @include('components.check-in-modal1', ['modalId' => 'checkInModal1', 'title' => 'Check In'])
    <div id="book-now" data-bs-toggle="modal" data-bs-target="#book-modal">
        <img src="{{ asset('images/book-logo.svg') }}" width="40px" height="40px">
        <p>Book Now</p>
    </div>
    @include('components.book-modal', ['modalId' => 'book-modal', 'title' => 'Book Room'])
    <div id="reserve-now" data-bs-toggle="modal" data-bs-target="#reserve-modal">
        <img src="{{ asset('images/reserve.svg') }}" width="40px" height="40px">
        <p>Reserve Now</p>
    </div>
    @include('components.reserve-modal', ['modalId' => 'reserve-modal', 'title' => 'Reserve Room'])
    <div id="scan-qr" data-bs-toggle="modal" data-bs-target="#qr-modal">
        <img src="{{ asset('images/qr.svg') }}" width="40px" height="40px">
        <p id="qrlabel">Scan <br> QR Code</p>
        <p id="qrlowermsg">Instant Transaction</p>
    </div>
    @include('components.qr-scanner-modal', ['modalId' => 'qr-modal', 'title' => 'Scan QR Code'])
    @include('components.invoice-modal')
    @include('components.confirm_transaction', ['modalId' => 'confirm-transact', 'title' => 'Confirm Transaction'])
    @include('components.confirm_reserve', ['modalId' => 'confirm-reserve', 'title' => 'Confirm Transaction'])
    @include('components.confirm_book', ['modalId' => 'confirm-book', 'title' => 'Confirm- Transaction'])
    @php
        $modalId = $modalId ?? 'defaultModalId';
    @endphp
    
@endsection

@section('extra-scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="{{ asset('js/checkin-modal.js') }}"></script>
    <script src="{{ asset('js/book-modal.js') }}"></script>
    <script src="{{ asset('js/reserve-modal.js') }}"></script>
    <script src="{{ asset('js/qr-code.js') }}"></script>
@endsection
