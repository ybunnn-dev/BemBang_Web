@extends('layouts.management')
@section('title', 'Room Types')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/specific-type.css') }}">
    <link rel="stylesheet" href="{{ asset('css/management/type-details-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/management/edit-type-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/management/features-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/management/edit-rates-modal.css') }}">
@endsection

@section('content')
    <script>
        window.origFeatures = @json($features->toArray());
        window.currentFeatures = @json($features->toArray());
        window.all_Features  = @json($all_features->toArray());
        window.allRoom = @json($roomType);
    </script>
    <button id="exit-button" onclick="goBacktoRoomTypes()"><img src="{{ asset('images/arrow-back.svg') }}" width="14px" height="14px">Return</button>
    <img src="{{ asset($roomType->images[0]) }}" id="room-profile-pic">
    <div class="details-card">
        <div class="room-details-head">
            <img src="{{ asset('images/bed-icon-2.svg') }}">
            <h4>{{ $roomType->type_name }}</h4>
            <button data-bs-toggle="modal" data-bs-target="#edit-type">Edit Details</button>
        </div>
        <hr>
        <div class="features">
            <div class="features-head">
                <img src="{{ asset('images/info.svg') }}" width="15px" height="15px">
                <h6>Room Features</h6>
            </div>
            <div class="features-list">
                @isset($roomType->room_features)
                    @php $count = 0; @endphp
                    @foreach($roomType->room_features as $featureId)
                        @php
                            if ($count >= 4) break;
                            $feature = \App\Models\Features::find($featureId);
                        @endphp
                        
                        @if($feature)
                            <div class="feature-item">
                                <img src="{{ asset($feature->feature_icon) }}" alt="{{ $feature->feature_name }}">
                                <p>{{ $feature->feature_name }}</p>
                            </div>
                            @php $count++; @endphp
                        @endif
                    @endforeach
                @else
                    <p>No features available for this room</p>
                @endisset
            </div>
        </div>
        <hr>
        <div class="description">
            <div class="description-head">
                <img src="{{ asset('images/description.svg') }}" width="15px" height="15px">
                <h6>Description</h6>
            </div>
            <p id="description-content">{!! str_replace('\n', '<br>', $roomType->description) !!}</p>
        </div>
        <div id="see-more-details" data-bs-toggle="modal" data-bs-target="#view_specific_type_details">
            <p>See More</p>   
            <img src="{{ asset('images/arrow-down.svg') }}" width="20px" height="20px">
        </div>
       
    </div>
    <button id="edit-photos">
        Edit Images
        <img src="{{ asset('images/edit.svg') }}" width="25px" height="25px;">
    </button>
    <div class="reviews-card">
        <div class="review-head">
            <img src="{{ asset('images/comments.svg') }}" width="25px" height="25px">
            <h5>Reviews</h5>
        </div>
        <div class="review-count">
            <h1>4.5</h1>
            <div class="review-stars-num">
                <div class="stars">
                    <img src="{{ asset('images/stars/active.svg') }}" width="20px" height="20px">
                    <img src="{{ asset('images/stars/active.svg') }}" width="20px" height="20px">
                    <img src="{{ asset('images/stars/active.svg') }}" width="20px" height="20px">
                    <img src="{{ asset('images/stars/active.svg') }}" width="20px" height="20px">
                    <img src="{{ asset('images/stars/inactive.svg') }}" width="20px" height="20px">
                </div>
                <p>500 Reviews</p>
            </div>
        </div>
        <div class="review-sample">
            <div class="review-sample-head">
                <img src="{{ asset('images/wally-bayola.jpg') }}" id="user-img">
                <div class="user-head">
                    <p id="user-name">Wally Bayola</p>
                    <div class="user-items">
                        <div class="user-stars">
                            <img src="{{ asset('images/stars/active.svg') }}" width="15px" height="15px">
                            <img src="{{ asset('images/stars/active.svg') }}" width="15px" height="15px">
                            <img src="{{ asset('images/stars/active.svg') }}" width="15px" height="15px">
                            <img src="{{ asset('images/stars/active.svg') }}" width="15px" height="15px">
                            <img src="{{ asset('images/stars/inactive.svg') }}" width="15px" height="15px">
                        </div>
                        <p>Posted at March 1, 2025 6:00 PM</p>
                    </div>
                </div>
            </div>
            <div class="review-sample-body">
                <p>Dad, I want PC. Here you go my niggwa. Tadaima. Nag-cantunan.</p>
            </div>
        </div>
        <div id="see-more-details2">
            <p>See More</p>   
            <img src="{{ asset('images/arrow-down.svg') }}" width="20px" height="20px">
        </div>
    </div>
    <div class="rates-card">
        <img src="{{ asset('images/price-tag.svg') }}" width="20px" height="20px">
        <h5>Room Rates</h5> 
        
        <div class="rate-content">
            <div class="rate-item">
                <h5>P {{ number_format($roomType->rates['checkin_12h'], 2) }}</h5>
                <p>/12 HRS</p>
            </div>
            <div class="rate-item">
                <h5>P {{ number_format($roomType->rates['checkin_24h'], 2) }}</h5>
                <p>/24 HRS</p>
            </div>
            <hr>
            <div class="rate-item" id="part2-rate">
                <h5>P {{ number_format($roomType->rates['reservation_12h'], 2) }}</h5>
                <p>/12 HRS</p>
            </div>
            <div class="rate-item">
                <h5>P {{ number_format($roomType->rates['reservation_24h'], 2) }}</h5>
                <p>/24 HRS</p>
            </div>
        </div>
        <button data-bs-toggle="modal" data-bs-target="#editRates">Edit Rates</button>
    </div>    
    </div>
    @include('components.type-details-modal', ['modalId' => 'view_specific_type_details', 'title' => $roomType->type_name])
    @include('components.edit-type-modal', ['modalId' => 'edit-type', 'title' => 'Edit Room Details'])
    @include('components.features-modal', ['modalId' => 'edit-features', 'title' => 'Add Features'])
    @include('components.confirm-edit-type-details', ['modalId' => 'confirm-details', 'title' => 'Edit Details'])
    @include('components.edit-rates-modal', ['modalId' => 'editRates', 'title' => 'Edit Rates'])
    @include('components.confirm-edit-rates-modal', ['modalId' => 'confirm-rate-edit', 'title' => 'Confirm Changes'])
@endsection
@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/room.js') }}"></script>
    <script src="{{ asset('js/management/edit-type-modal.js') }}"></script>
    <script src="{{ asset('js/management/edit-rates.js') }}"></script>
@endsection