 let currentTransaction;
 let currentTransactId;
 let refundValue;
 let currentBooking;

function getTransaction(id) {
    // Find the specific booking in the books array
    return books.find(book => book.id === id);
}
function populateBookingModal(bookingData) {
    // Basic Info
    document.getElementById('booking-id').value = bookingData.id;
    document.getElementById('booking-id-display').textContent = `ID: ${bookingData.short_id}`;

    if(bookingData.status === "booked"){
        document.getElementById('booking-status').style.color = "#00FF1E";
        document.getElementById('booking-status').style.border = " 1px solid #00FF1E";
        document.getElementById('checkin-btn').disabled = false;
        document.getElementById('refund-booking-btn').disabled = true;
    }
    else if(bookingData.status === "no show" || bookingData.status === "cancelled"){
        document.getElementById('booking-status').style.color = "#FF0B55";
        document.getElementById('booking-status').style.border = "1px solid #FF0B55";
        document.getElementById('checkin-btn').disabled = true;
        document.getElementById('refund-booking-btn').disabled = false;
    }
    else if(bookingData.status === "refunded"){
        document.getElementById('booking-status').style.color = "#A1E3F9";
        document.getElementById('booking-status').style.border = "1px solid #A1E3F9";
        document.getElementById('checkin-btn').disabled = true;
        document.getElementById('refund-booking-btn').disabled = true;
    }
    else{
        document.getElementById('booking-status').style.color = "#ffab00";
        document.getElementById('booking-status').style.border = "1px solid #ffab00";
        document.getElementById('checkin-btn').disabled = false;
        document.getElementById('refund-booking-btn').disabled = true;
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
let refundModal; // Make refundModal accessible globally

async function refundConfirm() {
    currentTransactId = document.getElementById('booking-id').value;
    const currentModal = bootstrap.Modal.getInstance(document.getElementById('checkInBook'));
    if (currentModal) currentModal.hide();

    currentBooking = getTransaction(currentTransactId);
    const paidAmount = currentBooking.raw_data.meta.total_rate;
    refundValue = paidAmount * 0.90;

    refundModal = new bootstrap.Modal(document.getElementById('refundModal'));
    document.getElementById('refundAmountDisplay').textContent = "₱" + refundValue.toFixed(2);
    refundModal.show();
}

// Standalone refund processing function
async function processRefund() {
    try {
        const response = await fetch('/transactions/refund', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                transaction_id: currentTransactId,
                refundValue: refundValue
            })
        });

        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Refund failed');
        
        alert('Refund processed successfully!');
        if (refundModal) {
            refundModal.hide();
        }
        window.location.reload();
    } catch (error) {
        console.error('Refund error:', error);
        alert(`Refund failed: ${error.message}`);
    }
}
document.addEventListener('DOMContentLoaded', function(){
    if(currentId){
        checkinExistingBook(currentId);
    }
});

function checkinExistingBook(bookId){
    console.log(bookId);
    currentBooking = getTransaction(bookId);
    console.log(currentBooking);

    populateBookingModal(currentBooking);
    currentTransaction = currentBooking;

    currentModal = new bootstrap.Modal(document.getElementById('checkInBook'));
    currentModal.show();
    
}
function cancelConfirm(){
    const thisModal = bootstrap.Modal.getInstance(document.getElementById('checkInBook'));

    if(thisModal){
      thisModal.hide();
    }
    const cancelModal = new bootstrap.Modal(document.getElementById('cancelConfirm'));
    cancelModal.show();
}
async function cancelReserve() {
  const transactionId = document.getElementById('booking-id').value;

  try {
    const response = await fetch('/transactions/cancel', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({
        transaction_id: transactionId,
      }),
    });

    const result = await response.json();

    if (response.ok) {
      alert('Booking cancelled successfully!');
      location.reload(); // Refresh page
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('Failed to cancel reservation. Please try again.');
  }
}

document.addEventListener('DOMContentLoaded', function() {
  document.querySelector('#finalCancel').addEventListener('click', function() {
    cancelReserve();
  });
});

function check_in(){
    currentModal = bootstrap.Modal.getInstance(document.getElementById('checkInBook'));

    if(currentModal){
        currentModal.hide();
    }
    newModal = new bootstrap.Modal(document.getElementById("confirm-check-in-book"));
    newModal.show();
    
}

async function completeCheckinBook() {
    try {
        const bookingId = document.getElementById('booking-id').value;
        if (!bookingId) throw new Error('No booking selected');

        // Get current datetime in ISO 8601 format (UTC)
        const now = new Date();
        const actualCheckin = now.toISOString(); // "2025-04-21T14:00:00.000Z"
        console.log(currentBooking.raw_data.stay_details.stay_hours);
        // Calculate expected checkout (actual checkin + stay hours)
        const stayHours = currentBooking.raw_data.stay_details.stay_hours || 24;
        const checkoutDateTime = new Date(now.getTime() + stayHours * 60 * 60 * 1000);
        const expectedCheckout = checkoutDateTime.toISOString(); // "2025-04-22T12:00:00.000Z"

        // Prepare checkin data with ISO formatted datetimes
        const checkinData = {
            booking_id: bookingId,
            room_id: currentBooking.raw_data.room_id.$oid,
            guest_id: currentBooking.raw_data.guest_id.$oid,
            actual_checkin: actualCheckin,
            expected_checkout: expectedCheckout,
            status: 'confirmed',
            stay_hours: currentBooking.raw_data.stay_details.stay_hours
        };
        console.log(checkinData);
        const response = await fetch('/bookings/checkin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(checkinData)
        });

        if (!response.ok) {
            const errorResult = await response.json();
            throw new Error(errorResult.message || 'Check-in failed');
        }

        const result = await response.json();

        if (result.success) {
            
            const modal = bootstrap.Modal.getInstance(document.getElementById("confirm-check-in-book"));

            if(modal){
                modal.hide();
            }
            const invoice = new bootstrap.Modal(document.getElementById("invoiceModal"));

            populateInvoiceModal(currentTransaction, currentTransactId);
            invoice.show();
        } else {
            throw new Error(result.message || 'Check-in failed');
        }
    } catch (error) {
        console.error('Check-in error:', error);
        alert(`Error: ${error.message}`);
    }
}

function approve(){
    const currentModal = bootstrap.Modal.getInstance(document.getElementById('checkInBook'));

    if(currentModal){
        currentModal.hide();
    }
    const approveModal = new bootstrap.Modal(document.getElementById('confirmApprove'));

    approveModal.show();
}



document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('confirmApproval').addEventListener('click', function() {
        const transactId = document.getElementById('booking-id').value;
        const status = "booked"; // Changed from "booked" to "confirmed" as it's more standard

        console.log('Updating status:', status, 'for transaction:', transactId);

        // Call API to update status
        fetch('/transactions/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                transaction_id: transactId,
                status: status
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Booking status updated successfully!');
                window.location.reload();
            } 
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the booking status');
        });
    });
});

function populateInvoiceModal(transaction, transactId) {
    // Helper functions
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            minimumFractionDigits: 2
        }).format(amount || 0);
    };

    const formatDate = (dateObj) => {
        if (!dateObj) return "N/A";
        // Handle MongoDB timestamp objects
        if (dateObj.$date && dateObj.$date.$numberLong) {
            return new Date(parseInt(dateObj.$date.$numberLong))
                .toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
        }
        return dateObj;
    };

    // Data sources
    const mainGuest = transaction.guest || {};
    const rawData = transaction.raw_data || {};
    const rawGuest = rawData.guest_id || {}; // Additional guest details might be here
    const payments = rawData.payments || [];

    // --- Invoice Header ---
    document.getElementById('invoice-number').textContent = transaction.short_id || transactId;
    document.getElementById('invoice-date').textContent = formatDate(rawData.created_at);

    // --- Guest Information (merged from both objects) ---
    document.getElementById('guest-name').textContent = 
        `${mainGuest.firstName || rawGuest.firstName || ''} ${mainGuest.lastName || rawGuest.lastName || ''}`.trim() || 'Guest';
    
    document.getElementById('guest-email').textContent = 
        mainGuest.email || rawGuest.email || 'N/A';
    
    document.getElementById('guest-contact').textContent = 
        mainGuest.mobileNumber || rawGuest.mobileNumber || 'N/A';
    
    document.getElementById('guest-address').textContent = 
        mainGuest.address || rawGuest.address || 'N/A';

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
        transaction.room?.number ? `Room ${transaction.room.number} (${transaction.room.type})` : 'N/A';
    
    document.getElementById('stay-status').textContent = 
        (transaction.status || rawData.current_status || 'N/A').toUpperCase();

    // --- Payment Breakdown ---
    const totalRate = rawData.meta?.total_rate || 
                    parseFloat((transaction.amount || '0').replace(',', '')) || 0;
    
    document.getElementById('subtotal').textContent = formatCurrency(totalRate);

    // --- Payments ---
    const totalPaid = payments.reduce((sum, p) => sum + (p.amount || 0), 0);
    const paymentMethods = payments.map(p => 
        p.method ? p.method.toUpperCase() : 'N/A'
    ).join(', ') || 'N/A';

    document.getElementById('payment-method-invoice').textContent = paymentMethods;
    document.getElementById('amount-paid').textContent = formatCurrency(totalPaid);

    // --- Balance ---
    const balance = totalRate - totalPaid;
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
