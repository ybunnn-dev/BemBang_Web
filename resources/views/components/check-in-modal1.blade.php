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
                      <label for="formControlInput2" class="form-label">FIRST NAME</label>
                      <label for="formControlInput2" class="form-label">LAST NAME</label>
                  </div>
                  <div class="name-flex">
                      <input type="text" class="form-control" id="fname-input" placeholder="ex. Peter">
                      <input type="text" class="form-control" id="lname-input" placeholder="ex. Vakla">
                  </div>
                <div class="contactLabel-flex">
                    <label for="formControlInput2" class="form-label">EMAIL</label>
                    <label for="formControlInput2" class="form-label">MOBILE NO.</label>
                </div>
                <div class="contact-flex">
                    <input type="email" class="form-control" id="email-input" placeholder="ex. hellobiokid@gmail.com">
                    <input type="tel" class="form-control" id="mnum-input" placeholder="ex. 0912345654">
                  </div>
                  <div class="address-gen-label-flex">
                      <label for="formControlInput5" class="form-label" id="address-label">ADDRESS</label>
                      <label for="gender-select" class="form-label" id="gen-label">GENDER</label>
                  </div>
                <div class="address-gen-flex">
                    <input type="text" class="form-control" id="formControlInput5" placeholder="ex. Sagpon, Daraga, Albay">
                    <select class="form-select" aria-label="Default select" id="gender-select">
                      <option selected="">Select Gender</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                </div>
                <div class="separate-flex">
                  <hr>
                  <p>OR</p>
                  <hr>
                </div>
                <p id="qr-ask-label">Guest have an existing account?</p>
                <button type="button" class="btn btn-light" id="qr-button" onclick="user_qr()">
                  <img src="{{ asset('images/qr2.svg') }}" width="30px" height="30px">
                  SCAN QR CODE
                </button>
                <hr class="bottom-line">
              </div>

              <!--a qr code scanner will riderect to part 3 if succes-->
              <div class="input-content-2">
                  <div class="scanner-holder">
                    <div id="qr-reader" style="width: 100%; height: 100%;"></div>
                  </div>
                  <p>Please position the QR code in front of the camera.</p>
                  <hr class="bottom-line">
              </div>

              <!-- you can skip this part-->
              <div class="input-content-3">
                  <hr id="ic3hr1">
                  <div class="ic3-container">
                    <div class="existing-user-labels">
                        <p>FIRST NAME:</p>
                        <p>LAST NAME:</p>
                        <p>GENDER:</p>
                        <p>PERSONAL EMAIL:</p>
                        <p>MOBILE NUMBER:</p>
                        <p>ADDRESS:</p>
                        <p>LAST CHECK IN:</p>
                    </div>
                    <div class="existing-user-values">
                        <p id="first-name">Leonard</p>
                        <p id="last-name">Cock</p>
                        <p id="sex">Yes!</p>
                        <p id="personal-email">manok@gmail.com</p>
                        <p id="mobile-number">0931245562</p>
                        <p id="address">Banao, Guinobatan, Albay</p>
                        <p id="last-check-in">March 17, 2025</p>
                    </div>
                  </div>
                  <hr id="ic3hr2">
              </div>

              <!--checkin input-->
              <div class="input-content-4">
                    <div class="checkin-dates-label-flex">
                        <label for="formControlInput7" class="form-label">CHECK IN DATE</label>
                        <label for="formControlInput8" class="form-label">CHECK IN TIME</label>
                    </div>

                    <div class="checkin-dates-flex">
                        <input type="date" class="form-control" id="checkin-date-input" disabled>
                        <input type="time" class="form-control" id="checkin-time-input" disabled>
                    </div>

                    <div class="checkout-dates-label-flex">
                        <label for="formControlInput9" class="form-label">CHECK OUT DATE</label>
                        <label for="formControlInput10" class="form-label">CHECK OUT TIME</label>
                    </div>

                    <div class="checkout-dates-flex">
                        <input type="date" class="form-control" id="checkout-date-input" placeholder="ex. hellobiokid@gmail.com">
                        <input type="time" class="form-control" id="checkout-time-input" placeholder="ex. 0912345654">
                    </div>

                    <div class="roomtypes-label-flex">
                        <label for="formControlInput7" class="form-label">ROOM TYPE</label>
                        <label for="formControlInput8" class="form-label">ASSIGNED ROOM NUMBER</label>
                    </div>

                    <div class="room-types-flex">
                        <select class="form-select" aria-label="Default select" id="room-type">
                          <option selected="">Open this select menu</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                        <input type="text" class="form-control" id="room-no" disabled>
                    </div>

                    <div class="extra-details-label-flex">
                        <label for="formControlInput9" class="form-label">NUMBER OF GUEST</label>
                        <label for="formControlInput10" class="form-label">HOURS STAY</label>
                    </div>

                    <div class="extra-flex">
                        <input type="number" class="form-control" id="guest-num-input">
                        <input type="number" class="form-control" id="hours-stay-input" disabled>
                    </div>
                    <hr>
              </div>
              <!--re confirming-->
              <div class="input-content-5">
                <hr>
                <div class="output-flex">
                    <div class="output-label-name">
                        <p>FIRST NAME</p>
                        <p>LAST NAME</p>
                    </div>
                    <div class="output-values-name">
                        <p id="output-fname">Leonard</p>
                        <p id="output-lname">Manok</p>
                    </div>
                    <div class="output-label-numgen">
                        <p>GENDER</p>
                        <p>MOBILE NUMBER</p>
                    </div>
                    <div class="output-values-numgen">
                        <p id="output-gender">M</p>
                        <p id="output-mobileNum">091234567</p>
                    </div>

                    <p>PERSONAL EMAIL</p>
                    <p id="output-email">manok@gmail.com</p>
                    <p>ADDRESS</p>
                    <p id="output-address">Sagpon, Daraga, Albay</p>

                    <hr width="500px;">

                    <div class="output-label-checkin">
                        <p>CHECK IN DATE</p>
                        <p>CHECK IN TIME</p>
                    </div>

                    <div class="output-values-checkin">
                        <p id="output-checkin-date">March 17, 2025</p>
                        <p id="output-checkin-time">8:00 AM</p>
                    </div>

                    <div class="output-label-checkout">
                        <p>CHECK OUT DATE</p>
                        <p>CHECK OUT TIME</p>
                    </div>

                    <div class="output-values-checkout">
                        <p id="output-checkout-date">March 17, 2025</p>
                        <p id="output-checkout-time">8:00 PM</p>
                    </div>

                    <div class="output-label-room">
                        <p>ROOM TYPE</p>
                        <p>ROOM NUMBER</p>
                    </div>

                    <div class="output-values-room">
                        <p id="output-room-type">Bembang Standard</p>
                        <p id="output-room-no" >ROOM #12</p>
                    </div>

                    <div class="output-label-guestrate">
                        <p>NUMBER OF GUEST</p>
                        <p>RATE</p>
                    </div>

                    <div class="output-values-guestrate">
                        <p id="output-guest-num">2</p>
                        <p id="output-rate">P 1,499.00</p>
                    </div>
                </div>
                <hr>
            </div>
          
            <!--choosing a payment method-->
            <div class="input-content-6">
              <div class="payment-methods">
                <p>SELECT PAYMENT METHOD</p>
                <button type="button" class="btn btn-light"  id="gcash" onclick="updatePaymentMethod('gcash')">
                  <img src="{{ asset('images/gcash.svg') }}" width="60px" height="60px">Pay using GCash
                </button>
                <button type="button" class="btn btn-light" id="cashPayment" onclick="updatePaymentMethod('cashPayment')">
                  <img src="{{ asset('images/cash.svg') }}" width="60px" height="60px">Cash Payment
                </button>
              </div>
            </div>

        <!--Rechecking the payment method if you want, you can merge this instead in the part6-->
            <div class="input-content-7">
              <div class="payment-label-flex">
                <p>PAYMENT METHOD</p>
                <p>RATE</p>
              </div>
        
              <div class="payment-flex">
                <p id="payment-method">G-Shock</p>
                <p id="transactRateValue">P 1,499.00</p>
              </div>
              <hr class="bottom-hr">
            </div>

            <!--for gcash-->
            <div class="input-content-10">
                <img src="{{ asset('images/gcash.svg') }}" width="50px" height="50px">
                <h5>GCash Payment</h5>
                <label for="acc-name-gcash" class="form-label">ACCOUNT NAME</label>
                <input type="text" class="form-control" id="acc-name-gcash" placeholder="Ron Peter Vakal">

                <label for="acc-num-gcash" class="form-label">REFERENCE NUMBER</label>
                <input type="text" class="form-control" id="acc-num-gcash" placeholder="0987656234">
            </div>

            <!--for cash payment-->
            <div class="input-content-11">
                <img src="{{ asset('images/cash.svg') }}" width="50px" height="50px">
                <h5>Cash Payment</h5>
                <label for="cass-amount" class="form-label">ENTER AMOUNT (PHP)</label>
                <input type="number" class="form-control" id="cash-amount" placeholder="Enter amount">
                <div class="amount-labels">
                    <p>TOTAL AMOUNT</p>
                    <p>CHANGE</p>
                </div>
                <div class="amount-values">
                    <p id="amount-value">P 1,499.00</p>
                    <p id="change">P 0.00</p>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="prev-button" onclick="modal_switch_prev()">Previous</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancel-button">Cancel</button>
        <button type="button" class="btn btn-primary" id="next-button" disabled onclick="modal_switch_next()">Next</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="confirm-button">Confirm</button>
      </div>
    </div>
  </div>
</div>
