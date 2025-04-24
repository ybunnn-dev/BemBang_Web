<!-- Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header" style="border-bottom: none;">
      </div>
      
      <!-- Modal Body -->
      <div class="modal-body" style="font-family: 'Poppins', sans-serif; padding-left: 50px; padding-right: 50px;">
        <!-- Hotel Info -->
        <div class="text-center mb-4">
          <h1 class="fs-3 fw-bold" style="color: #566A7F;">PETER'S HOTEL YAHOO</h1>
          <p class="mb-0" style="color: #697A8D;">123 Hotel Street, City, Country</p>
          <p style="color: #697A8D;">Tel: (123) 456-7890</p>
        </div>
        
        <!-- Invoice Header -->
        <div class="row mb-4">
          <div class="col-6">
            <p class="small fw-semibold mb-1" style="color: #566A7F;">INVOICE NO.</p>
            <p id="invoice-number" style="color: #697A8D;">-</p>
          </div>
          <div class="col-6 text-end">
            <p class="small fw-semibold mb-1" style="color: #566A7F;">DATE</p>
            <p id="invoice-date" style="color: #697A8D;">-</p>
          </div>
        </div>
        
        <!-- Guest Info -->
        <div class="mb-4 bg-light p-3 rounded">
          <h3 class="fs-6 fw-semibold mb-3" style="color: #566A7F;">GUEST INFORMATION</h3>
          <div class="row">
            <div class="col-md-6 mb-2">
              <p class="small mb-1" style="color: #566A7F;">NAME</p>
              <p id="guest-name" class="fw-medium" style="color: #697A8D;">-</p>
            </div>
            <div class="col-md-6 mb-2">
              <p class="small mb-1" style="color: #566A7F;">EMAIL</p>
              <p id="guest-email" style="color: #697A8D;">-</p>
            </div>
            <div class="col-md-6 mb-2">
              <p class="small mb-1" style="color: #566A7F;">CONTACT</p>
              <p id="guest-contact" style="color: #697A8D;">-</p>
            </div>
            <div class="col-md-6 mb-2">
              <p class="small mb-1" style="color: #566A7F;">ADDRESS</p>
              <p id="guest-address" style="color: #697A8D;">-</p>
            </div>
          </div>
        </div>
        
        <!-- Stay Details -->
        <div class="mb-4">
          <h3 class="fs-6 fw-semibold mb-3" style="color: #566A7F;">STAY DETAILS</h3>
          <div class="bg-light p-3 rounded">
            <div class="row">
              <div class="col-md-6 mb-2">
                <p class="small mb-1" style="color: #566A7F;">CHECK-IN</p>
                <p id="checkin-date" style="color: #697A8D;">-</p>
              </div>
              <div class="col-md-6 mb-2">
                <p class="small mb-1" style="color: #566A7F;">CHECK-OUT</p>
                <p id="checkout-date" style="color: #697A8D;">-</p>
              </div>
              <div class="col-md-6 mb-2">
                <p class="small mb-1" style="color: #566A7F;">DURATION</p>
                <p id="stay-duration" style="color: #697A8D;">-</p>
              </div>
              <div class="col-md-6 mb-2">
                <p class="small mb-1" style="color: #566A7F;">GUESTS</p>
                <p id="guest-count" style="color: #697A8D;">-</p>
              </div>
              <div class="col-md-6 mb-2">
                <p class="small mb-1" style="color: #566A7F;">ROOM ID</p>
                <p id="room-id" style="color: #697A8D;">-</p>
              </div>
              <div class="col-md-6 mb-2">
                <p class="small mb-1" style="color: #566A7F;">STATUS</p>
                <p id="stay-status" class="text-uppercase" style="color: #697A8D;">-</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Payment Info -->
        <div class="mb-4">
          <h3 class="fs-6 fw-semibold mb-3" style="color: #566A7F;">PAYMENT DETAILS</h3>
          <table class="table">
            <thead>
              <tr class="bg-light">
                <th style="color: #566A7F;">DESCRIPTION</th>
                <th style="color: #566A7F; text-align: right;">AMOUNT</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="color: #697A8D;">Room Charge</td>
                <td id="room-charge" style="color: #697A8D; text-align: right;">-</td>
              </tr>
              <tr class="bg-light">
                <td class="fw-semibold" style="color: #566A7F;">SUBTOTAL</td>
                <td id="subtotal" class="fw-semibold" style="color: #566A7F; text-align: right;">-</td>
              </tr>
              <tr>
                <td style="color: #566A7F;">PAYMENT METHOD</td>
                <td id="payment-method-invoice" style="color: #697A8D; text-align: right;">-</td>
              </tr>
              <tr>
                <td style="color: #566A7F;">AMOUNT PAID</td>
                <td id="amount-paid" style="color: #697A8D; text-align: right;">-</td>
              </tr>
              <tr>
                <td style="color: #566A7F;">CHANGE</td>
                <td id="change-given" style="color: #697A8D; text-align: right;">-</td>
              </tr>
              <tr class="bg-light">
                <td class="fw-bold" style="color: #566A7F;">BALANCE</td>
                <td id="balance" class="fw-bold" style="color: #566A7F; text-align: right;">-</td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Terms -->
        <div class="mt-5 text-center small" style="color: #697A8D;">
          <p>Thank you for choosing our hotel!</p>
          <p class="mt-2">This is a computer-generated invoice. No signature required.</p>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div class="modal-footer" style="padding-left: 30px; border-top: none">
        <button type="button" class="btn" data-bs-dismiss="modal" style="color: #578FCA; background-color: #f7f7f7; border-color: #578FCA; font-weight: 600; width: 100px; font-family: 'Poppins'">Close</button>
        <button type="button" class="btn" id="print-invoice" style="color: #f7f7f7; font-weight: 600; width: 150px; background-color: #578FCA; border-color: #578FCA; font-family: 'Poppins'">
          <i class="bi bi-printer me-1"></i> Print Invoice
        </button>
      </div>
    </div>
  </div>
</div>