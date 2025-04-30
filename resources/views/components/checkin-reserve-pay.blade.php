<div class="modal fade show" id="checkin-reserve-payment-modal" tabindex="-1" aria-labelledby="checkin-reserve-payment-modal-label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="checkin-reserve-payment-modal-label">Reservation Payment</h5>
            <p id="checkin-reserve-sub-msg">Select your payment method.</p>
        </div>
        
        <div class="modal-body">
          <div class="contents">
            <!-- Payment Method Selection -->
            <div class="input-content-6" id="checkin-reserve-payment-methods">
              <div class="payment-methods">
                <p>SELECT PAYMENT METHOD</p>
                <button type="button" class="btn btn-light" id="checkin-reserve-gcash-btn" onclick="updatePaymentMethod('gcash')">
                  <img src="{{ asset('images/gcash.svg') }}" alt="GCash Logo" width="60" height="60">Pay using GCash
                </button>
                <button type="button" class="btn btn-light" id="checkin-reserve-cash-btn" onclick="updatePaymentMethod('cash')">
                  <img src="{{ asset('images/cash.svg') }}" alt="Cash Logo" width="60" height="60">Cash Payment
                </button>
              </div>
            </div>
            
            <!-- Payment Method Summary -->
            <div class="input-content-7">
              <div class="payment-label-flex">
                <p>PAYMENT METHOD</p>
                <p>RATE</p>
              </div>
              
              <div class="payment-flex">
                <p id="checkin-reserve-payment-method-display">Not Selected</p>
                <p id="checkin-reserve-transact-rate-value">P 1,499.00</p>
              </div>
              <hr>
            </div>
            
            <!-- GCash Form -->
            <div class="input-content-10" id="checkin-reserve-gcash-form">
              <div class="text-center mb-3">
                <h5>GCash Payment</h5>
              </div>
              
              <label for="checkin-reserve-gcash-reference-number" class="form-label">REFERENCE NUMBER</label>
              <input type="text" class="form-control" id="checkin-reserve-gcash-reference-number" placeholder="Enter GCash reference number">
              <div class="error-message" id="checkin-reserve-gcash-reference-number-error">Please enter a valid reference number</div>
              
            </div>
            
            <!-- Cash Payment Form -->
            <div class="input-content-11" id="checkin-reserve-cash-form">
              <div class="text-center mb-3">
                <h5>Cash Payment</h5>
              </div>
              
              <label for="checkin-reserve-cash-amount" class="form-label">ENTER AMOUNT (PHP)</label>
              <input type="number" class="form-control" id="checkin-reserve-cash-amount" placeholder="Enter amount" min="1499" oninput="calculateChange()">
              <div class="error-message" id="checkin-reserve-cash-amount-error">Please enter an amount equal to or greater than P 1,499.00</div>
              
              <div class="amount-display">
                <div class="amount-row total">
                  <span>Total Amount</span>
                  <span id="raw-value">P 1,499.00</span>
                </div>
                <div class="amount-row change">
                  <span>Change</span>
                  <span id="checkin-reserve-change-amount">P 0.00</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal-footer"> 
          <button type="button" class="btn btn-secondary" id="checkin-reserve-cancel-btn" onclick="closeModal()">Cancel</button>
          <button type="button" class="btn btn-secondary ms-auto" id="checkin-reserve-back-btn" onclick="goBack()" style="display: none;">Back</button>
          <button type="button" class="btn btn-primary" id="checkin-reserve-next-btn" onclick="proceedPayment()" disabled>Next</button>
        </div>
      </div>
    </div>
  </div>