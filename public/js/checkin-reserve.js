let currentTransaction;
let currentTransactId;

let selectedPaymentMethod = null;
let totalAmount = 0; // Total amount in PHP

function getTransaction(id) {
   // Find the specific booking in the books array
   return reserves.find(reserve => reserve.id === id);
}

function populateBookingModal(bookingData) {
   // Basic Info
   document.getElementById('booking-id').value = bookingData.id;
   document.getElementById('booking-id-display').textContent = `ID: ${bookingData.short_id}`;

   if(bookingData.status === "reserved"){
       document.getElementById('booking-status').style.color = "#00FF1E";
       document.getElementById('booking-status').style.border = " 1px solid #00FF1E";
       document.getElementById('edit-booking-btn').disabled = true;
       document.getElementById('checkin-btn').disabled = false;
       document.getElementById('cancel-booking-btn').disabled = false;
   }
   else if(bookingData.status === "no show" || bookingData.status === "cancelled"){
       document.getElementById('booking-status').style.color = "#FF0B55";
       document.getElementById('booking-status').style.border = "1px solid #FF0B55";
       document.getElementById('checkin-btn').disabled = true;
       document.getElementById('edit-booking-btn').disabled = true;
       document.getElementById('cancel-booking-btn').disabled = true;
   }
   else{
       document.getElementById('booking-status').style.color = "#ffab00";
       document.getElementById('booking-status').style.border = "1px solid #ffab00";
       document.getElementById('edit-booking-btn').disabled = false;
       document.getElementById('checkin-btn').disabled = false;
       document.getElementById('cancel-booking-btn').disabled = false;
   }

   document.getElementById('booking-status').textContent = 
       bookingData.status
           .split(' ') // Split into words
           .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // Capitalize each
           .join(' '); // Rejoin with spaces

   // Guest Info
   document.getElementById('guest-name').textContent = `${bookingData.guest.firstName} ${bookingData.guest.lastName}`;
   document.getElementById('guest-email').textContent = bookingData.guest.email;
   document.getElementById('guest-phone').textContent = bookingData.guest.mobileNumber;
   document.getElementById('guest-gender').textContent = bookingData.guest.gender;
   document.getElementById('guest-address').textContent = bookingData.guest.address;
   document.getElementById('guest-points').textContent = bookingData.guest.points;

   // Room Info
   document.getElementById('room-number').textContent = bookingData.room.number;
   document.getElementById('room-type').textContent = bookingData.room.type;
   document.getElementById('guest-count').textContent = bookingData.raw_data.stay_details.guest_num;

   // Stay Info
   document.getElementById('checkin-date').textContent = bookingData.checkin.date;
   document.getElementById('checkin-time').textContent = bookingData.checkin.time;
   document.getElementById('checkout-date').textContent = bookingData.checkout.date;
   document.getElementById('checkout-time').textContent = bookingData.checkout.time;
   document.getElementById('stay-duration').textContent = `${bookingData.raw_data.stay_details.stay_hours} hours`;
   document.getElementById('time-allowance').textContent = `${bookingData.raw_data.stay_details.time_allowance} hours`;

   // Payment Info
   document.getElementById('original-rate').textContent = `₱${(bookingData.raw_data.meta.original_rate || 0).toFixed(2)}`;
   document.getElementById('discount-amount').textContent = `₱${(bookingData.raw_data.meta.discount || 0).toFixed(2)}`;
   document.getElementById('total-amount').textContent = `₱${(bookingData.raw_data.meta.original_rate - (bookingData.raw_data.meta.discount || 0)).toFixed(2)}`;
   
   if (bookingData.raw_data.payments && bookingData.raw_data.payments.length > 0) {
       const payment = bookingData.raw_data.payments[0];
       document.getElementById('payment-method').textContent = payment.method;
       document.getElementById('payment-reference').textContent = payment.details.reference_no;
   }
}

function checkinExistingReserve(bookId){
   console.log(bookId);
   currentBooking = getTransaction(bookId);
   totalAmount = currentBooking.raw_data.meta.total_rate;
   console.log(currentBooking);

   populateBookingModal(currentBooking);
   currentTransaction = currentBooking;

   currentModal = new bootstrap.Modal(document.getElementById('reserveCheckIn'));
   currentModal.show();
}

function check_in(){
   currentModal = bootstrap.Modal.getInstance(document.getElementById('reserveCheckIn'));
    console.log(currentTransaction.amount);
   if(currentModal){
       currentModal.hide();
   }
   const fixedAmount = totalAmount.toFixed(2);

    // Add commas as thousand separators
    const formattedAmount = 'P ' + totalAmount.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
      
    document.getElementById('checkin-reserve-transact-rate-value').textContent = formattedAmount;
    document.getElementById('raw-value').textContent = formattedAmount;
   newModal = new bootstrap.Modal(document.getElementById("checkin-reserve-payment-modal"));
   newModal.show();
}

async function submitPayment() {
    try {
      const transactionId = document.getElementById('booking-id').value;
      if (!transactionId) {
        throw new Error('Transaction ID is missing');
      }

      // Add validation that currentTransaction exists
      if (!currentTransaction || !currentTransaction.raw_data || !currentTransaction.raw_data.guest_id) {
        throw new Error('Transaction data is not loaded properly');
      }

      let paymentData;
      const now = new Date();
      
      if (selectedPaymentMethod === 'gcash') {
        if (!validateGcashForm()) return;
        
        paymentData = {
          transaction_id: transactionId,
          guest_id: currentTransaction.raw_data.guest_id.$oid,
          payment: {
            method: 'gcash',
            details: {
              reference_no: document.getElementById('checkin-reserve-gcash-reference-number').value.trim()
            },
            amount: totalAmount,
            currency: 'PHP',
            status: 'pending',
            processed_at: now.toISOString()
          },
          update_status: 'confirmed',
          // Add any additional required fields here
          current_status: currentTransaction.status // Send current status if backend needs it
        };
      } else {
        if (!calculateChange()) return;
        
        const cashAmount = parseFloat(document.getElementById('checkin-reserve-cash-amount').value);
        
        paymentData = {
          transaction_id: transactionId,
          guest_id: currentTransaction.raw_data.guest_id.$oid,
          payment: {
            method: 'cash',
            details: {
              cash_received: cashAmount,
              change_given: cashAmount - totalAmount
            },
            amount: totalAmount,
            currency: 'PHP',
            status: 'completed',
            processed_at: now.toISOString()
          },
          update_status: 'confirmed',
          // Add any additional required fields here
          current_status: currentTransaction.status // Send current status if backend needs it
        };
      }

      // Add debug logging before sending
      console.log('Submitting payment data:', paymentData);

      const response = await fetch('/transactions/process-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(paymentData)
      });

      const responseData = await response.json(); // Always parse the response
      
      if (!response.ok) {
        // More detailed error handling
        console.error('Backend error response:', responseData);
        throw new Error(responseData.message || 
                       responseData.error || 
                       `Payment failed with status ${response.status}`);
      }

        const modal = bootstrap.Modal.getInstance(document.getElementById("checkin-reserve-payment-modal"));

            if(modal){
                modal.hide();
            }
            const invoice = new bootstrap.Modal(document.getElementById("invoiceModal"));

            populateInvoiceModal(currentTransaction, currentTransactId);
            invoice.show();
      
    } catch (error) {
      console.error('Payment submission error:', error);
      alert(`Payment failed: ${error.message}`);
      // Consider showing more details to the user or implementing retry logic
    }
}

function approve(){
   const currentModal = bootstrap.Modal.getInstance(document.getElementById('reserveCheckIn'));

   if(currentModal){
       currentModal.hide();
   }
   const approveModal = new bootstrap.Modal(document.getElementById('confirmApprove'));

   approveModal.show();
}

// Function to update payment method selection
function updatePaymentMethod(method) {
  selectedPaymentMethod = method;
  
  // Reset all buttons
  document.querySelectorAll('#checkin-reserve-payment-modal .payment-methods .btn').forEach(btn => {
    btn.classList.remove('selected');
  });
  
  // Highlight selected button
  document.getElementById(`checkin-reserve-${method}-btn`).classList.add('selected');
  
  // Update payment method display
  document.getElementById('checkin-reserve-payment-method-display').textContent = 
    method === 'gcash' ? 'GCash' : 'Cash';
  
  // Enable next button
  document.getElementById('checkin-reserve-next-btn').disabled = false;
}

// Function to proceed with payment
function proceedPayment() {
  if (!selectedPaymentMethod) return;
  
  // Hide both forms first
  document.getElementById('checkin-reserve-gcash-form').style.display = 'none';
  document.getElementById('checkin-reserve-cash-form').style.display = 'none';
  
  // Show appropriate form based on selection
  if (selectedPaymentMethod === 'gcash') {
    document.getElementById('checkin-reserve-gcash-form').style.display = 'block';
    document.getElementById('checkin-reserve-gcash-reference-number').focus();
    // Add event listener for validation
    document.getElementById('checkin-reserve-gcash-reference-number').addEventListener('input', validateGcashForm);
  } else {
    document.getElementById('checkin-reserve-cash-form').style.display = 'block';
    // Add event listener for validation
    document.getElementById('checkin-reserve-cash-amount').addEventListener('input', calculateChange);
  }
  
  // Update buttons
  document.getElementById('checkin-reserve-next-btn').textContent = 'Submit';
  document.getElementById('checkin-reserve-next-btn').onclick = submitPayment;
  document.getElementById('checkin-reserve-next-btn').disabled = true;
  document.getElementById('checkin-reserve-back-btn').style.display = 'block';
  document.getElementById('checkin-reserve-cancel-btn').style.display = 'none';
  
  // Hide payment method selection
  document.getElementById('checkin-reserve-payment-methods').style.display = 'none';
}

// Function to go back to payment method selection
function goBack() {
  // Reset forms
  document.getElementById('checkin-reserve-gcash-form').style.display = 'none';
  document.getElementById('checkin-reserve-cash-form').style.display = 'none';
  document.getElementById('checkin-reserve-cancel-btn').style.display = 'block';
  
  // Show payment method selection
  document.getElementById('checkin-reserve-payment-methods').style.display = 'block';
  
  // Update buttons
  document.getElementById('checkin-reserve-next-btn').textContent = 'Next';
  document.getElementById('checkin-reserve-next-btn').disabled = false;
  document.getElementById('checkin-reserve-next-btn').onclick = proceedPayment;
  document.getElementById('checkin-reserve-back-btn').style.display = 'none';
}

// Function to calculate change for cash payment
function calculateChange() {
  const cashAmount = parseFloat(document.getElementById('checkin-reserve-cash-amount').value) || 0;
  const errorElement = document.getElementById('checkin-reserve-cash-amount-error');
  
  if (cashAmount < totalAmount) {
    errorElement.style.display = 'block';
    document.getElementById('checkin-reserve-next-btn').disabled = true;
    document.getElementById('checkin-reserve-change-amount').textContent = 'P 0.00';
    return false;
  } else {
    const change = cashAmount - totalAmount;
    document.getElementById('checkin-reserve-change-amount').textContent = `P ${change.toFixed(2)}`;
    errorElement.style.display = 'none';
    document.getElementById('checkin-reserve-next-btn').disabled = false;
    return true;
  }
}

// Validate GCash reference number
function validateGcashForm() {
  const refNumber = document.getElementById('checkin-reserve-gcash-reference-number').value.trim();
  const errorElement = document.getElementById('checkin-reserve-gcash-reference-number-error');
  
  if (refNumber.length < 5) {
    errorElement.style.display = 'block';
    document.getElementById('checkin-reserve-next-btn').disabled = true;
    return false;
  } else {
    errorElement.style.display = 'none';
    document.getElementById('checkin-reserve-next-btn').disabled = false;
    return true;
  }
}

// Function to close the modal
function closeModal() {
 const currentModal = bootstrap.Modal.getInstance(document.getElementById('checkin-reserve-payment-modal'));

 if(currentModal){
    currentModal.hide();
 }
  const newModal = new bootstrap.Modal(document.getElementById('reserveCheckIn'));
  newModal.show();
}
function populateInvoiceModal(transaction, transactId) {
  // Helper functions
  const formatCurrency = (amount) => {
      return new Intl.NumberFormat('en-PH', {
          style: 'currency',
          currency: 'PHP',
          minimumFractionDigits: 2
      }).format(amount || 0);
  };

  const formatDate = (dateString) => {
      if (!dateString) return "N/A";
      if (typeof dateString === 'object' && dateString.$date) {
          return new Date(parseInt(dateString.$date.$numberLong))
              .toLocaleDateString('en-US', {
                  year: 'numeric',
                  month: 'short',
                  day: 'numeric',
                  hour: '2-digit',
                  minute: '2-digit'
              });
      }
      return dateString;
  };

  // Data extraction
  const guest = transaction.guest || {};
  const room = transaction.room || {};
  const rawData = transaction.raw_data || {};
  const payments = rawData.payments || [];
  const meta = rawData.meta || {};

  // --- Invoice Header ---
  document.getElementById('invoice-number').textContent = transaction.short_id || transactId;
  document.getElementById('invoice-date').textContent = formatDate(rawData.created_at);

  // --- Guest Information ---
  document.getElementById('guest-name').textContent = 
      `${guest.firstName || ''} ${guest.lastName || ''}`.trim() || 'Guest';
  document.getElementById('guest-email').textContent = guest.email || 'N/A';
  document.getElementById('guest-contact').textContent = guest.mobileNumber || 'N/A';
  document.getElementById('guest-address').textContent = guest.address || 'N/A';

  // --- Stay Details ---
  document.getElementById('checkin-date').textContent = 
      transaction.checkin ? `${transaction.checkin.date} ${transaction.checkin.time}` : 
      formatDate(rawData.stay_details?.expected_checkin);
  
  document.getElementById('checkout-date').textContent = 
      transaction.checkout ? `${transaction.checkout.date} ${transaction.checkout.time}` : 
      formatDate(rawData.stay_details?.expected_checkout);
  
  document.getElementById('stay-duration').textContent = 
      `${rawData.stay_details?.stay_hours || 0} hours`;
  
  document.getElementById('guest-count').textContent = 
      `${rawData.stay_details?.guest_num || 0} person(s)`;
  
  document.getElementById('room-id').textContent = 
      room.number ? `Room ${room.number} (${room.type})` : 'N/A';
  
  document.getElementById('stay-status').textContent = 
      (transaction.status || rawData.current_status || 'N/A').toUpperCase();

  // --- Payment Breakdown ---
  const originalRate = meta.original_rate || 
                     parseFloat((transaction.amount || '0').replace(',', '')) || 0;
  const totalRate = meta.total_rate || originalRate;
  
  document.getElementById('room-charge').textContent = formatCurrency(originalRate);
  document.getElementById('subtotal').textContent = formatCurrency(totalRate);

  // --- Payments ---
  const totalPaid = payments.reduce((sum, p) => sum + (p.amount || 0), 0);
  const paymentMethods = payments.map(p => 
      p.method ? p.method.toUpperCase() : 'N/A'
  ).join(', ') || 'N/A';

  document.getElementById('payment-method-invoice').textContent = paymentMethods;
  document.getElementById('amount-paid').textContent = formatCurrency(totalPaid);

  // --- Balance & Change ---
  const changeGiven = meta.change_given || 0;
  const balance = totalRate - totalPaid;

  document.getElementById('change-given').textContent = formatCurrency(changeGiven);
  document.getElementById('balance').textContent = formatCurrency(Math.max(0, balance));
}
 

document.addEventListener('DOMContentLoaded', function(){
  document.getElementById('print-invoice').addEventListener('click', function() {
      // Get the transaction ID from the invoice
      const transactionId = document.getElementById('invoice-number').textContent;
      
      // Create data for QR code
      const qrData = JSON.stringify({
          transactionId: transactionId,
          type: 'transaction'
      });
      
      // Get modal content
      const modalContent = document.querySelector('#invoiceModal .modal-content').innerHTML;
      const printWindow = window.open('', '_blank');
      
      // Write the HTML structure with compact styling and 20% size reduction
      printWindow.document.write(`
      <!DOCTYPE html>
      <html>
      <head>
          <title>Invoice</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
          <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
          <style>
          body {
              font-family: 'Poppins', sans-serif;
              padding: 15px;
              font-size: 0.85rem;
              transform: scale(0.8); /* Reduce size by 20% */
              transform-origin: top left; /* Scale from top-left to avoid clipping */
              width: 125%; /* Compensate for scaling to fit page */
          }
          h1, h2, h3, h4, h5, h6 {
              margin-bottom: 0.5rem;
          }
          .fs-3 {
              font-size: 1.4rem !important;
          }
          .fs-6 {
              font-size: 0.9rem !important;
          }
          .modal-body {
              padding: 0.75rem !important;
          }
          .table {
              margin-bottom: 0.5rem;
          }
          .table td, .table th {
              padding: 0.35rem;
          }
          p {
              margin-bottom: 0.2rem;
          }
          .mb-1 {
              margin-bottom: 0.1rem !important;
          }
          .mb-2 {
              margin-bottom: 0.2rem !important;
          }
          .mb-3 {
              margin-bottom: 0.3rem !important;
          }
          .mb-4 {
              margin-bottom: 0.5rem !important;
          }
          .p-3 {
              padding: 0.5rem !important;
          }
          .modal-header {
              padding: 0.5rem 1rem;
          }
          .modal-footer {
              display: none !important;
          }
          @media print {
              .no-print {
                  display: none;
              }
          }
          .qr-container {
              text-align: center;
              margin: 10px auto;
              display: flex;
              flex-direction: column;
              align-items: center;
              justify-content: center;
          }
          #qrcode {
              margin: 0 auto;
              display: flex;
              justify-content: center;
          }
          #qrcode img, #qrcode canvas {
              margin: 0 auto;
          }
          </style>
      </head>
      <body>
          <div class="container">
              ${modalContent}
              <!-- QR Code container -->
              <div class="qr-container">
                  <div id="qrcode"></div>
                  <p style="color: #697A8D; font-size: 11px; margin-top: 3px;">Transaction ID: ${transactionId}</p>
              </div>
          </div>
      </body>
      </html>
      `);
      
      // Close the document to finish writing the HTML
      printWindow.document.close();
      
      // Load QR code library dynamically
      const script = printWindow.document.createElement('script');
      script.src = "https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js";
      script.onload = function() {
          // Generate the QR code
          const qrCode = new printWindow.QRCode(printWindow.document.getElementById("qrcode"), {
              text: qrData,
              width: 100,  // Keep QR code size; scaling is handled by body transform
              height: 100,
              colorDark: "#000000",
              colorLight: "#ffffff",
              correctLevel: printWindow.QRCode.CorrectLevel.H
          });
          
          // Wait to ensure QR code is rendered
          setTimeout(function() {
              printWindow.print();
              setTimeout(() => printWindow.close(), 1000);
          }, 500);
      };
      
      // Handle QR code library loading errors
      script.onerror = function() {
          printWindow.document.getElementById("qrcode").innerHTML = 
              "<p style='color: red'>QR code generation failed. Please try again.</p>";
          setTimeout(function() {
              printWindow.print();
              setTimeout(() => printWindow.close(), 1000);
          }, 500);
      };
      
      // Add the script to the new window
      printWindow.document.head.appendChild(script);
  });
});
