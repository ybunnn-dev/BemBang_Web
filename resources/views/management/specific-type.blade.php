@extends('layouts.management')
@section('title', 'Room Types')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/specific-type.css') }}">
@endsection

@section('content')
    <button id="exit-button" onclick="goBacktoRoomList()"><img src="{{ asset('images/arrow-back.svg') }}" width="14px" height="14px">Return</button>
    <img src="{{ asset('images/rooms/standard.jpg') }}" id="room-profile-pic">
    <div class="details-card">
        <div class="room-details-head">
            <img src="{{ asset('images/bed-icon-2.svg') }}">
            <h4>Bembang Standard</h4>
            <button>Edit Details</button>
        </div>
        <hr>
        <div class="features">
            <div class="features-head">
                <img src="{{ asset('images/info.svg') }}" width="15px" height="15px">
                <h6>Room Features</h6>
            </div>
            <div class="features-list">
                <div class="feature-item">
                    <img src="{{ asset('images/features/bed.svg') }}">
                    <p>1 Single Bed</p>
                </div>
                <div class="feature-item">
                    <img src="{{ asset('images/features/bed.svg') }}">
                    <p>1 Single Bed</p>
                </div>
                <div class="feature-item">
                    <img src="{{ asset('images/features/bed.svg') }}">
                    <p>1 Single Bed</p>
                </div>
                <div class="feature-item">
                    <img src="{{ asset('images/features/bed.svg') }}">
                    <p>1 Single Bed</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="description">
            <div class="description-head">
                <img src="{{ asset('images/description.svg') }}" width="15px" height="15px">
                <h6>Description</h6>
            </div>
            <p id="description-content">
                Affordable Comfort for Practical Travelers<br>

                The Bembang Standard room is designed for guests seeking a comfortable and budget-friendly stay without compromising on quality. This room type features a well-appointed space with modern amenities to ensure a relaxing experience. Perfect for solo travelers, couples, or business guests, the Bembang Standard offers a cozy atmosphere, making it an excellent choice for short-term stays or transit accommodations.
                The room includes a plush double bed, ensuring a restful night's sleep, complemented by air conditioning to maintain an optimal temperature. Guests can stay connected with complimentary Wi-Fi and enjoy entertainment on the flat-screen TV with cable channels. A private bathroom with a hot-and-cold shower, fresh towels, and essential toiletries provides added convenience.
                Whether for a brief overnight stop or a short stay, the Bembang Standard ensures comfort, privacy, and essential amenities at an affordable rate.
            </p>
        </div>
        <div id="see-more-details">
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
                <h5>P 1,499.00</h5>
                <p>/12 HRS</p>
            </div>
            <div class="rate-item">
                <h5>P 1,499.00</h5>
                <p>/12 HRS</p>
            </div>
            <hr>
            <div class="rate-item" id="part2-rate">
                <h5>P 1,499.00</h5>
                <p>/12 HRS</p>
            </div>
            <div class="rate-item">
                <h5>P 1,499.00</h5>
                <p>/12 HRS</p>
            </div>
        </div>
        <button>Edit Rates</button>
    </div>
@endsection
@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/room.js') }}"></script>
@endsection