@extends('layouts.management')
@section('title', 'My Profile')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/myprofile.css') }}">
@endsection

@section('scripts')
    
@endsection

@section('content')  
    <script>
        window.origEmail = {{ Js::from(Auth::user()->email) }};
        window.origNum = {{ Js::from(Auth::user()->mobileNumber) }}
        window.origAdd = {{ Js::from(Auth::user()->address) }}
    </script>
    <button id="exit-button" onclick="window.location.href='/management/dashboard'">
        <img src="{{ asset('images/arrow-back.svg') }}" width="14px" height="14px">
        Return
    </button>
    <div class="main-container">
        <div class="profile-holder">
            <div class="profile-img">
                <img src="{{ asset('images/beki.jpg') }}">
            </div>
            <button type="button" class="btn btn-light" id="edit-button">
                Edit Profile Photo
                <img src="{{ asset('images/edit.svg') }}" width="20px" height="20px">
            </button>
        </div>
        <div class="profile-type">
            <div class="type-header">
                <img src="{{ asset('images/receptionist.svg') }}" width="16px" height="16px">
                <h5>BEMBANG RECEPTIONIST</h5>
            </div>
            <div class="type-contents">
                <div class="type-labels">
                    <p>EMPLOYEE ID:</p>
                    <p>DATE JOINED:</p>
                    <p>YEARS ACTIVE:</p>
                </div>
                <div class="type-values">
                    <p>1234</p>
                    <p>March 17, 2003</p>
                    <p>22 Years</p>
                </div>
            </div>
        </div>
        <div class="profile-details">
            <div class="details-header">
                <img src="{{ asset('images/profile-head.svg') }}" width="25px" height="25px">
                <h5>My Profile</h5>
            </div>
            <div class="details-flex">
                <div class="details-label">
                    <p>FIRST NAME:</p>
                    <p>LAST NAME:</p>
                    <p>PERSONAL EMAIL:</p>
                    <p>MOBILE NUMBER:</p>
                    <p>ADDRESS:</p>
                </div>
                <div class="details-values">
                    <p>{{ Auth::user()->firstName }}</p>
                    <p>{{ Auth::user()->lastName}}</p>
                    <div class="button-change">
                        <p id="email_display">{{ Auth::user()->email }}</p>
                        <img src="{{ asset('images/edit.svg') }}" width="15px" id="changeEmailButton" height="15px" data-bs-toggle="modal" data-bs-target="#changeEmailModal">
                    </div>
                    
                    <div class="button-change">
                        <p id="mobileNum_display">{{ Auth::user()->mobileNumber }}</p>
                        <img src="{{ asset('images/edit.svg') }}" width="15px" height="15px" data-bs-toggle="modal" data-bs-target="#changeNumModal" id="changeNumButton">
                    </div>
                    <div class="button-change2">
                        <p id="address">{{ Auth::user()->address }}</p>
                        <img src="{{ asset('images/edit.svg') }}" width="15px" height="15px" data-bs-toggle="modal" data-bs-target="#changeAddressModal" id="changeAddressButton">
                    </div>
                </div>
                <div>
                    <div class="action-buttons">
                        <button type="button" class="btn btn-light" id="change-pass" data-bs-toggle="modal" data-bs-target="#changePassModal" id="changePassButton">Change Password</button>
                        <button type="button" class="btn btn-primary" id="save-button" data-bs-toggle="modal" data-bs-target="#confirmPassModal">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>  
    </div>
@endsection
@include('components.change-email-modal', ['id' => 'changeEmailModal', 'title' => 'Change Email'])
@include('components.change-num-modal', ['id' => 'changeNumModal', 'title' => 'Change Number'])
@include('components.address-modal', ['id' => 'changeAddressModal', 'title' => 'Change Address'])
@include('components.change-pass-modal', ['id' => 'changePassModal', 'title' => 'Change Password'])
@include('components.confirm-pass-modal', ['id' => 'confirmPassModal', 'title' => 'Confirm Changes'])
@include('components.save-changes-confirm', ['id' => 'saveChangesConfirmModal', 'title' => 'Save Changes'])

@section('extra-scripts')
    <script src="{{ asset('js/myprofile.js') }}"></script>
    <script src="{{ asset('js/profile-edit.js') }}"></script>
@endsection