<!-- Checkin Modal -->
<div class="modal fade" id="reserveCheckIn" tabindex="-1" aria-labelledby="reserveCheckInLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="reserveCheckInLabel">Booking Details</h4>
                <span class="ms-2 booking-id" id="booking-id-display">ID: 6ac7fb6e</span>
                <h5 class="mb-0"><span class="badge" id="booking-status">Booked</span></h5>
            </div>
            <div class="modal-body">
                <div class="guest-info">
                    <h5 class="mb-3">Guest Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Name:</span> 
                            <span id="guest-name">TEST TEST</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Email:</span> 
                            <span id="guest-email">notverified@gmail.com</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Phone:</span> 
                            <span id="guest-phone">09123456789</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Gender:</span> 
                            <span id="guest-gender">Male</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Address:</span> 
                            <span id="guest-address">EDIT TEST</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Points:</span> 
                            <span id="guest-points">300</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="room-info">
                    <h5 class="mb-3">Room Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Room Number:</span> 
                            <span id="room-number">2</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Room Type:</span> 
                            <span id="room-type">Peter Standard</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Number of Guests:</span> 
                            <span id="guest-count">4</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="stay-info">
                    <h5 class="mb-3">Stay Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Check-in Date:</span> 
                            <span id="checkin-date">2025-04-27</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Check-in Time:</span> 
                            <span id="checkin-time">00:00</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Check-out Date:</span> 
                            <span id="checkout-date">2025-04-28</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Check-out Time:</span> 
                            <span id="checkout-time">00:00</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Stay Duration:</span> 
                            <span id="stay-duration">24 hours</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Time Allowance:</span> 
                            <span id="time-allowance">4 hours</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="payment-info">
                    <h5 class="mb-3">Payment Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Original Rate:</span> 
                            <span id="original-rate">₱1,800.00</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Discount:</span> 
                            <span id="discount-amount">₱0.00</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Total Amount:</span> 
                            <span id="total-amount">₱1,800.00</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Payment Method:</span> 
                            <span id="payment-method">GCash</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <span class="booking-detail-label">Reference No:</span> 
                            <span id="payment-reference">1234567890123</span>
                        </div>
                    </div>
                </div>
                <hr>
                <!-- Action Buttons inside modal body -->
                <div class="action-buttons mt-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-danger" id="cancel-booking-btn">
                        <i class="bi bi-x-circle"></i> Cancel Booking
                    </button>
                    <button type="button" class="btn btn-primary" id="edit-booking-btn" onclick="approve()">
                        <i class="bi bi-pencil"></i> Approve
                    </button>
                    <button type="button" class="btn btn-success" id="checkin-btn" onclick="check_in()">
                        <i class="bi bi-check-circle"></i> Check In
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden input for storing booking ID -->
<input type="hidden" id="booking-id" value="">
@include('components.approve-confirm')