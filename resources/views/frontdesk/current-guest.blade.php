@extends('layouts.frontdesk')
@section('title', 'Guest Profile')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/current-guest.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/frontdesk-dashboard.js') }}"></script>
@endsection

@section('content')
    <script>
        document.addEventListener("DOMContentLoaded", function (){
            console.log(@json($guest));
        });
    </script>
    <button id="exit-button" onclick="goBackToGuestList()">
        <img src="{{ asset('images/arrow-back.svg') }}" width="14px" height="14px">
        Return
    </button>
    <div class="guest-info-card">
        <div class="profile-pic-holder">
            <div id="profile-pic">
                <img src="{{ asset('images/yahoo.jpg') }}" alt="Profile Picture" style="width: 100%;
                    height: 100%;
                    object-fit: cover;">
            </div>
            <h5>{{ $guest->firstName . ' ' . $guest->lastName }}</h5>
            <div class="membership-type">
            <img src="{{ 
                    match(strtolower($guest->membership_details->membership_name ?? '')) {
                        'explorer' => asset('images/explorer-icon.svg'),
                        'regular' => asset('images/regular-icon.svg'),
                        'expert' => asset('images/expert-icon.svg'),
                        'prime' => asset('images/prime-icon.svg'),
                        'elite' => asset('images/elite-icon.svg'),
                        default => asset('images/leaf.svg') // Fallback icon
                    }
                }}" width="20px" height="20px" alt="{{ $guest->membership_details->membership_name ?? 'Standard' }} membership">
                <p>
                    
                    @if(isset($guest->membership_details->membership_name))
                        <span>{{ strtoupper($guest->membership_details->membership_name) }}</span>
                    @else
                        <span>NOT YET JOINED</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="guest-info-title">
            <img src="{{ asset('images/profile-head.svg') }}" width="25px" height="25px">
            <h5>Guest Info</h5>
        </div>
        <div class="info-labels">
            <p>FIRST NAME:</p>
            <p>LAST NAME:</p>
            <p>PERSONAL EMAIL:</p>
            <p>MOBILE NUMBER:</p>
            <p>ADDRESS:</p>
        </div>
        <div class="info-values">
            <p>{{ $guest->firstName }}</p>
            <p>{{ $guest->lastName }}</p>
            <p>{{ $guest->email }}</p>
            <p>{{ $guest->mobileNumber }}</p>
            <p>{{ $guest->address }}</p>
        </div>
    </div>
    <div class="current-transact-card">
        <h5>Current Transaction</h5>
        <img src="{{ asset('images/standard.jpg') }}" width="125px" height="145px">
        <div class="room-details-label">
            <p>ROOM #:</p>
            <p>ROOM TYPE:</p>
            <p>STATUS:</p>
        </div>
        <div class="room-details-values">
            <p>123</p>
            <p>Peter Standard</p>
            <div class="status-div">
                <p>Reservation</p>
            </div>
        </div>
    </div>
    <div class="guest-num">
        <h5>Guest Activities</h5>
        <div class="icon-holder">
            <img src="{{ asset('images/guest-icons/checkin.svg') }}" width="40px" height="40px" id="checkin-icon">
            <img src="{{ asset('images/guest-icons/booking.svg') }}" width="35px" height="35px" id="checkin-icon">
            <img src="{{ asset('images/guest-icons/reserve.svg') }}" width="35px" height="35px" id="checkin-icon">
        </div>
        <p id="checkin-value">{{ $guest->checkin_count }}</p>
        <p id="act-checkin-label">Check Ins</p>

        <p id="book-value">9</p>
        <p id="act-book-label">Bookings</p>
        
        <p id="reserve-value">9</p>
        <p id="act-reserve-label">Reservations</p>
    </div>
    <div class="buttons-flex">
        <button type="button" class="btn btn-light" id="msg-button">
            <img src="{{ asset('images/msg.svg') }}" width="25px" height="25px">
            Message
        </button>
        <button type="button" class="btn btn-light" id="history-button" onclick="goToHistory()">
            <img src="{{ asset('images/history.svg') }}" width="25px" height="25px">
            History
        </button>
        <button type="button" class="btn btn-light" id="transact-button">
            <img src="{{ asset('images/transact.svg') }}" width="25px" height="25px">
            Transaction
        </button>
    </div>
    <script>
       function goBackToGuestList() {
            window.location.href = "{{ route('frontdesk.guest') }}";
        }
        function goToHistory(){
             window.location.href = "{{ route('frontdesk.guest-history') }}";
        }
    </script>
@endsection