<!-- Checkin Modal -->
<div class="modal fade" id="checkInBook" tabindex="-1" aria-labelledby="checkInBookLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkInBookLabel">Booking Details</h5>
                <span class="ms-2 booking-id" id="booking-id-display">ID: 6ac7fb6e</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Status: <span class="badge badge-booked" id="booking-status">Booked</span></h6>
                    <h6 class="mb-0">Amount: <span id="booking-amount">₱1,800.00</span></h6>
                </div>
                
                <div class="guest-info">
                    <h6 class="mb-3">Guest Information</h6>
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
                
                <div class="room-info">
                    <h6 class="mb-3">Room Details</h6>
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
                
                <div class="stay-info">
                    <h6 class="mb-3">Stay Information</h6>
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
                
                <div class="payment-info">
                    <h6 class="mb-3">Payment Details</h6>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="cancel-booking-btn">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" id="edit-booking-btn">
                    <i class="bi bi-pencil"></i> Approve
                </button>
                <button type="button" class="btn btn-success" id="checkin-btn" onclick="check_in()">
                    <i class="bi bi-check-circle"></i> Check In
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Hidden input for storing booking ID -->
<input type="hidden" id="booking-id" value="">
