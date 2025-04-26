<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
        <p id="sub-msg">Enter guest details.</p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="contents">
            <div class="input-content-1">
                  <div class="namelabel-flex">
                      <label for="book-fname-input" class="form-label">FIRST NAME</label>
                      <label for="book-lname-input" class="form-label">LAST NAME</label>
                  </div>
                  <div class="name-flex">
                      <input type="text" class="form-control" id="book-fname-input" placeholder="ex. Peter">
                      <input type="text" class="form-control" id="book-lname-input" placeholder="ex. Vakla">
                  </div>
                  <div class="contactLabel-flex">
                      <label for="book-email-input" class="form-label">EMAIL</label>
                      <label for="book-phone-input" class="form-label">MOBILE NO.</label>
                  </div>
                  <div class="contact-flex">
                      <input type="email" class="form-control" id="book-email-input" placeholder="ex. hellobiokid@gmail.com">
                      <input type="tel" class="form-control" id="book-phone-input" placeholder="ex. 0912345654">
                  </div>
                  <div class="address-gen-label-flex">
                      <label for="book-address-input" class="form-label" id="book-address-label">ADDRESS</label>
                      <label for="book-gender-select" class="form-label" id="book-gen-label">SEX</label>
                  </div>
                  <div class="address-gen-flex">
                      <input type="text" class="form-control" id="book-address-input" placeholder="ex. Sagpon, Daraga, Albay">
                      <select class="form-select" aria-label="Default select" id="book-gender-select">
                          <option selected="">Select Gender</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                      </select>
                  </div>
                  <div class="separate-flex">
                      <hr>
                      <p>OR</p>
                      <hr>
                  </div>
                  <p id="book-qr-ask-label">Guest have an existing account?</p>
                  <button type="button" class="btn btn-light" id="book-qr-button" onclick="user_qr_book()">
                      <img src="{{ asset('images/qr2.svg') }}" width="30px" height="30px">
                      SCAN QR CODE
                  </button>
                  <hr class="bottom-line">
              </div>
              <!--end first input-->
              <div class="input-content-2">
                  <div class="scanner-holder">
                    <div id="qr-reader3" style="width: 100%; height: 100%;"></div>
                  </div>
                  <p>Please position the QR code in front of the camera.</p>
                  <hr class="bottom-line">
              </div>

              <div class="input-content-3">
                  <hr id="ic3hr1">
                  <div class="ic3-container">
                      <div class="existing-user-labels">
                          <p>FIRST NAME:</p>
                          <p>LAST NAME:</p>
                          <p>SEX:</p>
                          <p>PERSONAL EMAIL:</p>
                          <p>MOBILE NUMBER:</p>
                          <p>ADDRESS:</p>
                          <p>LAST CHECK IN:</p>
                      </div>
                      <div class="existing-user-values">
                          <p id="existing-fname">Leonard</p>
                          <p id="existing-lname">Condat</p>
                          <p id="existing-gender">Yes!</p>
                          <p id="existing-email">manok@gmail.com</p>
                          <p id="existing-phone">0931245562</p>
                          <p id="existing-address">Banao, Guinobatan, Albay</p>
                          <p id="existing-last-checkin">March 17, 2025</p>
                      </div>
                  </div>
                  <hr id="ic3hr2">
              </div>

              <div class="input-content-4">
                  <!-- Check-in Date/Time -->
                  <div class="checkin-dates-label-flex">
                      <label for="book-checkin-date" class="form-label">CHECK IN DATE</label>
                      <label for="book-checkin-time" class="form-label">CHECK IN TIME</label>
                  </div>
                  <div class="checkin-dates-flex">
                      <input type="date" class="form-control" id="book-checkin-date">
                      <input type="time" class="form-control" id="book-checkin-time">
                  </div>

                  <!-- Check-out Date/Time -->
                  <div class="checkout-dates-label-flex">
                      <label for="book-checkout-date" class="form-label">CHECK OUT DATE</label>
                      <label for="book-checkout-time" class="form-label">CHECK OUT TIME</label>
                  </div>
                  <div class="checkout-dates-flex">
                      <input type="date" class="form-control" id="book-checkout-date">
                      <input type="time" class="form-control" id="book-checkout-time">
                  </div>

                  <!-- Room Selection -->
                  <div class="roomtypes-label-flex">
                      <label for="book-room-type" class="form-label">ROOM TYPE</label>
                      <label for="book-room-number" class="form-label">ASSIGNED ROOM NUMBER</label>
                  </div>
                  <div class="room-types-flex">
                      <select class="form-select" aria-label="Room type selection" id="book-room-type">
                          <option selected disabled>Select room type</option>
                          <option value="standard">Standard</option>
                          <option value="deluxe">Deluxe</option>
                          <option value="suite">Suite</option>
                      </select>
                      <input type="text" class="form-control" id="book-room-number" disabled>
                  </div>

                  <!-- Guest Details -->
                  <div class="extra-details-label-flex">
                      <label for="book-guest-count" class="form-label">NUMBER OF GUESTS</label>
                      <label for="book-hours-stay" class="form-label">HOURS STAY</label>
                  </div>
                  <div class="extra-flex">
                      <input type="number" class="form-control" id="book-guest-count" min="1">
                      <input type="number" class="form-control" id="book-hours-stay" disabled>
                  </div>
                  <hr>
              </div>
              <div class="input-content-5">
                <hr>
                <div class="output-flex">
                    <!-- Personal Information -->
                    <div class="output-label-name">
                        <p>FIRST NAME</p>
                        <p>LAST NAME</p>
                    </div>
                    <div class="output-values-name">
                        <p id="book-output-fname">Leonard</p>
                        <p id="book-output-lname">Manok</p>
                    </div>
                    
                    <div class="output-label-numgen">
                        <p>SEX</p>
                        <p>MOBILE NUMBER</p>
                    </div>
                    <div class="output-values-numgen">
                        <p id="book-output-gender">M</p>
                        <p id="book-output-phone">091234567</p>
                    </div>

                    <p>PERSONAL EMAIL</p>
                    <p id="book-output-email">manok@gmail.com</p>
                    
                    <p>ADDRESS</p>
                    <p id="book-output-address">Sagpon, Daraga, Albay</p>

                    <hr width="500px;">

                    <!-- Booking Information -->
                    <div class="output-label-checkin">
                        <p>CHECK IN DATE</p>
                        <p>CHECK IN TIME</p>
                    </div>
                    <div class="output-values-checkin">
                        <p id="book-output-checkin-date">March 17, 2025</p>
                        <p id="book-output-checkin-time">8:00 AM</p>
                    </div>

                    <div class="output-label-checkout">
                        <p>CHECK OUT DATE</p>
                        <p>CHECK OUT TIME</p>
                    </div>
                    <div class="output-values-checkout">
                        <p id="book-output-checkout-date">March 17, 2025</p>
                        <p id="book-output-checkout-time">8:00 PM</p>
                    </div>

                    <div class="output-label-room">
                        <p>ROOM TYPE</p>
                        <p>ROOM NUMBER</p>
                    </div>
                    <div class="output-values-room">
                        <p id="book-output-room-type">Bembang Standard</p>
                        <p id="book-output-room-no">ROOM #12</p>
                    </div>

                    <div class="output-label-guestrate">
                        <p>NUMBER OF GUEST</p>
                        <p>RATE</p>
                    </div>
                    <div class="output-values-guestrate">
                        <p id="book-output-guest-count">2</p>
                        <p id="book-output-rate">P 1,499.00</p>
                    </div>
                </div>
                <hr>
            </div>
            <div class="input-content-6">
                <div class="payment-methods">
                    <p>SELECT PAYMENT METHOD</p>
                    <button type="button" class="btn btn-light payment-method-btn" id="book-payment-gcash">
                        <img src="{{ asset('images/gcash.svg') }}" width="60px" height="60px">Pay using GCash
                    </button>
                    <button type="button" class="btn btn-light payment-method-btn" id="book-payment-cash">
                        <img src="{{ asset('images/cash.svg') }}" width="60px" height="60px">Cash Payment
                    </button>
                </div>
            </div>
            <div class="input-content-7">
              <div class="payment-label-flex">
                <p>PAYMENT METHOD</p>
                <p>RATE</p>
              </div>
        
              <div class="payment-flex">
                <p id="book-method">G-Shock</p>
                <p id="book-tot-value">P 1,499.00</p>
              </div>
              <hr>
            </div>
            <!-- GCash Payment Form -->
            <div class="input-content-10" id="book-gcash-form">
                <img src="{{ asset('images/gcash.svg') }}" width="50px" height="50px">
                <h5>GCash Payment</h5>
                <label for="book-gcash-acc-name" class="form-label">ACCOUNT NAME</label>
                <input type="text" class="form-control" id="book-gcash-acc-name" placeholder="Ron Peter Vakal">

                <label for="book-gcash-acc-num" class="form-label">ACCOUNT NUMBER</label>
                <input type="tel" class="form-control" id="book-gcash-acc-num" placeholder="0987656234" maxlength="11">
            </div>

            <!-- Cash Payment Form -->
            <div class="input-content-11" id="book-cash-form">
                <img src="{{ asset('images/cash.svg') }}" width="50px" height="50px">
                <h5>Cash Payment</h5>
                <label for="book-cash-amount" class="form-label">ENTER AMOUNT (PHP)</label>
                <input type="number" class="form-control" id="book-cash-amount" placeholder="Enter amount" min="0" step="0.01">
                
                <div class="amount-labels">
                    <p>TOTAL AMOUNT</p>
                    <p>CHANGE</p>
                </div>
                <div class="amount-values">
                    <p id="book-cash-total">P 1,499.00</p>
                    <p id="book-cash-change">P 0.00</p>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="prev-button-book" onclick="modal_switch_prev_book()">Previous</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancel-button-book">Cancel</button>
        <button type="button" class="btn btn-primary" id="next-button-book" onclick="modal_switch_next_book()" disabled>Next</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="confirm-button-book">Confirm</button>
      </div>
    </div>
  </div>
</div>
