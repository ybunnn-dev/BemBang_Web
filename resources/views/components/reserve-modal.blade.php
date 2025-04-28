<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
        <p id="sub-msg">Enter guest details.</p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <!--First input in checking in-->
          <div class="contents">
              <div class="input-content-1">
                  <div class="namelabel-flex">
                      <label for="reserve-fname-input" class="form-label">FIRST NAME</label>
                      <label for="reserve-lname-input" class="form-label">LAST NAME</label>
                  </div>
                  <div class="name-flex">
                      <input type="text" class="form-control" id="reserve-fname-input" placeholder="ex. Peter">
                      <input type="text" class="form-control" id="reserve-lname-input" placeholder="ex. Vakla">
                  </div>
                <div class="contactLabel-flex">
                    <label for="reserve-email-input" class="form-label">EMAIL</label>
                    <label for="reserve-mnum-input" class="form-label">MOBILE NO.</label>
                </div>
                <div class="contact-flex">
                    <input type="email" class="form-control" id="reserve-email-input" placeholder="ex. hellobiokid@gmail.com">
                    <input type="tel" class="form-control" id="reserve-mnum-input" placeholder="ex. 0912345654">
                  </div>
                  <div class="address-gen-label-flex">
                      <label for="reserve-address-input" class="form-label" id="address-label">ADDRESS</label>
                      <label for="reserve-gender-select" class="form-label" id="gen-label">SEX</label>
                  </div>
                <div class="address-gen-flex">
                    <input type="text" class="form-control" id="reserve-address-input" placeholder="ex. Sagpon, Daraga, Albay">
                    <select class="form-select" aria-label="Default select" id="reserve-gender-select">
                      <option selected="">Select Gender</option>
                      <option value="1">Male</option>
                      <option value="2">Female</option>
                    </select>
                </div>
                <div class="separate-flex">
                  <hr>
                  <p>OR</p>
                  <hr>
                </div>
                <p id="qr-ask-label">Guest have an existing account?</p>
                <button type="button" class="btn btn-light" id="qr-button-reserve" onclick="user_qr_reserve()">
                  <img src="{{ asset('images/qr2.svg') }}" width="30px" height="30px">
                  SCAN QR CODE
                </button>
                <hr class="bottom-line">
              </div>

              <div class="input-content-2">
                  <div class="scanner-holder">
                    <div id="qr-reader-reserve" style="width: 100%; height: 100%;"></div>
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
                        <p id="reserve-existing-fname">Leonard</p>
                        <p id="reserve-existing-lname">Condat</p>
                        <p id="reserve-existing-gender">Male</p>
                        <p id="reserve-existing-email">manok@gmail.com</p>
                        <p id="reserve-existing-mobile">0931245562</p>
                        <p id="reserve-existing-address">Banao, Guinobatan, Albay</p>
                        <p id="reserve-existing-lastcheckin">March 17, 2025</p>
                    </div>
                  </div>
                  <hr id="ic3hr2">
              </div>

              <div class="input-content-4">
                    <div class="checkin-dates-label-flex">
                        <label for="reserve-checkin-date-input" class="form-label">CHECK IN DATE</label>
                        <label for="reserve-checkin-time-input" class="form-label">CHECK IN TIME</label>
                    </div>

                    <div class="checkin-dates-flex">
                        <input type="date" class="form-control" id="reserve-checkin-date-input">
                        <input type="time" class="form-control" id="reserve-checkin-time-input">
                    </div>

                    <div class="checkout-dates-label-flex">
                        <label for="reserve-checkout-date-input" class="form-label">CHECK OUT DATE</label>
                        <label for="reserve-checkout-time-input" class="form-label">CHECK OUT TIME</label>
                    </div>

                    <div class="checkout-dates-flex">
                        <input type="date" class="form-control" id="reserve-checkout-date-input">
                        <input type="time" class="form-control" id="reserve-checkout-time-input">
                    </div>

                    <div class="roomtypes-label-flex">
                        <label for="reserve-room-type" class="form-label">ROOM TYPE</label>
                        <label for="reserve-room-no" class="form-label">ASSIGNED ROOM NUMBER</label>
                    </div>

                    <div class="room-types-flex">
                        <select class="form-select" aria-label="Default select" id="reserve-room-type">
                          <option selected="">Open this select menu</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                        <input type="text" class="form-control" id="reserve-room-no" disabled>
                    </div>

                    <div class="extra-details-label-flex">
                        <label for="reserve-guest-num" class="form-label">NUMBER OF GUEST</label>
                        <label for="reserve-hours-stay" class="form-label">HOURS STAY</label>
                    </div>

                    <div class="extra-flex">
                        <input type="number" class="form-control" id="reserve-guest-num">
                        <input type="number" class="form-control" id="reserve-hours-stay" disabled>
                    </div>
                    <hr>
              </div>

              <div class="input-content-5">
                <hr>
                <div class="output-flex">
                    <div class="output-label-name">
                        <p>FIRST NAME</p>
                        <p>LAST NAME</p>
                    </div>
                    <div class="output-values-name">
                        <p id="reserve-output-fname">Leonard</p>
                        <p id="reserve-output-lname">Manok</p>
                    </div>
                    <div class="output-label-numgen">
                        <p>SEX</p>
                        <p>MOBILE NUMBER</p>
                    </div>
                    <div class="output-values-numgen">
                        <p id="reserve-output-gender">M</p>
                        <p id="reserve-output-mobile">091234567</p>
                    </div>

                    <p>PERSONAL EMAIL</p>
                    <p id="reserve-output-email">manok@gmail.com</p>
                    <p>ADDRESS</p>
                    <p id="reserve-output-address">Sagpon, Daraga, Albay</p>

                    <hr width="500px;">

                    <div class="output-label-checkin">
                        <p>CHECK IN DATE</p>
                        <p>CHECK IN TIME</p>
                    </div>

                    <div class="output-values-checkin">
                        <p id="reserve-output-checkin-date">March 17, 2025</p>
                        <p id="reserve-output-checkin-time">8:00 AM</p>
                    </div>

                    <div class="output-label-checkout">
                        <p>CHECK OUT DATE</p>
                        <p>CHECK OUT TIME</p>
                    </div>

                    <div class="output-values-checkout">
                        <p id="reserve-output-checkout-date">March 17, 2025</p>
                        <p id="reserve-output-checkout-time">8:00 PM</p>
                    </div>

                    <div class="output-label-room">
                        <p>ROOM TYPE</p>
                        <p>ROOM NUMBER</p>
                    </div>

                    <div class="output-values-room">
                        <p id="reserve-output-room-type">Bembang Standard</p>
                        <p id="reserve-output-room-no">ROOM #12</p>
                    </div>

                    <div class="output-label-guestrate">
                        <p>NUMBER OF GUEST</p>
                        <p>RATE</p>
                    </div>

                    <div class="output-values-guestrate">
                        <p id="reserve-output-guest-num">2</p>
                        <p id="reserve-output-rate">P 1,499.00</p>
                    </div>
                </div>
                <hr>
            </div>

            <div class="input-content-6">
              <div class="payment-methods">
                <p>SELECT PAYMENT METHOD</p>
                <button type="button" class="btn btn-light" id="reserve-gcash-btn">
                  <img src="{{ asset('images/gcash.svg') }}" width="60px" height="60px">Pay using GCash
                </button>
                <button type="button" class="btn btn-light" id="reserve-cash-btn">
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
                <p id="reserve-payment-method">G-Cash</p>
                <p id="reserve-total-amount">P 1,499.00</p>
              </div>
              <hr>
            </div>
            <div class="input-content-10">
                <img src="{{ asset('images/gcash.svg') }}" width="50px" height="50px">
                <h5>GCash Payment</h5>
                <label for="reserve-acc-name-gcash" class="form-label">ACCOUNT NAME</label>
                <input type="text" class="form-control" id="reserve-acc-name-gcash" placeholder="Ron Peter Vakal">

                <label for="reserve-acc-num-gcash" class="form-label">ACCOUNT NUMBER</label>
                <input type="tel" class="form-control" id="reserve-acc-num-gcash" placeholder="0987656234">
            </div>

            <div class="input-content-11">
                <img src="{{ asset('images/cash.svg') }}" width="50px" height="50px">
                <h5>Cash Payment</h5>
                <label for="reserve-cash-amount" class="form-label">ENTER AMOUNT (PHP)</label>
                <input type="number" class="form-control" id="reserve-cash-amount" placeholder="Enter amount">
                <div class="amount-labels">
                    <p>TOTAL AMOUNT</p>
                    <p>CHANGE</p>
                </div>
                <div class="amount-values">
                    <p id="reserve-total-amount-cash">P 1,499.00</p>
                    <p id="reserve-change-amount">P 2,000.00</p>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="prev-button-reserve" onclick="modal_switch_prev_reserve()">Previous</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancel-button-reserve">Cancel</button>
        <button type="button" class="btn btn-primary" id="next-button-reserve" onclick="modal_switch_next_reserve()">Next</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="confirm-button-reserve">Confirm</button>
      </div>
    </div>
  </div>
</div>