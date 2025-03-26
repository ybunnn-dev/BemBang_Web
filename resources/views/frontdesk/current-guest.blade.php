@extends('layouts.frontdesk')
@section('title', 'Guest Profile')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/current-guest.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/frontdesk-dashboard.js') }}"></script>
@endsection

@section('content')
    <button id="exit-button" onclick="goBackToViewRooms()">
        <img src="{{ asset('images/arrow-back.svg') }}" width="14px" height="14px">
        Return
    </button>
    <div class="guest-info-card">
        <div class="profile-pic-holder">
            <div id="profile-pic">
                <img src="{{ asset('images/giannis.jpg') }}" alt="Profile Picture">
            </div>
            <h5>Giannis Akoynagtatampo</h5>
            <div class="membership-type">
                <img src="{{ asset('images/elite-icon.svg') }}" width="20px" height="20px">
                <p>BEMBANG ELITE</p>
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
            <p>Giannis</p>
            <p>Akoynagtatampo</p>
            <p>hellokim@gmail.com</p>
            <p>0912345567</p>
            <p>Purok 1, Cm. Recto, St. San Julian Irosin Sorsogon 4707</p>
        </div>
    </div>
    <div class="current-transact-card">
        <h5>Current Transaction</h5>
        <img src="{{ asset('images/msg.svg') }}" width="25px" height="25px">
    </div>
    <div class="guest-num">
        <h5>Guest Activities</h5>
    </div>
    <div class="buttons-flex">
        <button type="button" class="btn btn-light" id="msg-button">
            <img src="{{ asset('images/msg.svg') }}" width="25px" height="25px">
            Message
        </button>
        <button type="button" class="btn btn-light" id="history-button">
            <img src="{{ asset('images/history.svg') }}" width="25px" height="25px">
            History
        </button>
        <button type="button" class="btn btn-light" id="transact-button">
            <img src="{{ asset('images/transact.svg') }}" width="25px" height="25px">
            Transaction
        </button>
    </div>
@endsection