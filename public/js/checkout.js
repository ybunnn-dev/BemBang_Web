function confirmCheckout() {
    // Get modal element first
    const modalElement = document.getElementById('confirm-checkOut');
    
    // Create new instance if none exists, or get existing one
    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    
    // Show the modal
    modal.show();
}

async function checkout() {
    try {
        const transactionId = room.transaction.id;
        const roomId = room.id;
        
        console.log('Initiating checkout:', { transactionId, roomId });


        // 2. Send checkout request to server
        const response = await fetch('/transactions/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json' // Add this to ensure JSON response
            },
            body: JSON.stringify({
                transaction_id: transactionId,
                room_id: roomId,
                status: "completed"
            })
        });

        // Debug the raw response
        console.log('Response status:', response.status);
        
        const data = await response.json();
        console.log('Response data:', data);

        if (!response.ok) {
            throw new Error(data.message || 'Checkout failed');
        }

        // 3. Handle success
        console.log('Checkout successful:', data);
        
        // 4. Update UI
        setTimeout(() => {
            const modal = bootstrap.Modal.getInstance(document.getElementById("confirm-checkOut"));

            if(modal){
                modal.hide();
            }
            const invoice = new bootstrap.Modal(document.getElementById("invoiceModal"));

            populateInvoiceModal(transaction, transaction.id);
            invoice.show();
        }, 1500);

    } catch (error) {
        console.error('Checkout error:', error);
        // More descriptive error message instead of "pota"
        alert("Checkout failed: " + error.message);
        
    }
}
document.addEventListener('DOMContentLoaded', function(){
    console.log(transaction, "yawa" ,transaction.id);
})
function populateInvoiceModal(transaction, transactId) {
    // Format currency (PHP)
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            minimumFractionDigits: 2
        }).format(amount);
    };

    // Format dates (e.g., "Apr 29, 2025, 08:00 AM")
    const formatDate = (dateString) => {
        if (!dateString) return "N/A";
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    // --- Invoice Header ---
    document.getElementById('invoice-number').textContent = transactId;
    document.getElementById('invoice-date').textContent = formatDate(transaction.created_at);

    // --- Guest Information ---
    document.getElementById('guest-name').textContent = `${transaction.guest?.firstName || 'Guest'} ${transaction.guest?.lastName || ''}`.trim();
    document.getElementById('guest-email').textContent = transaction.guest?.email || 'N/A';
    document.getElementById('guest-contact').textContent = transaction.guest?.mobileNumber || 'N/A';
    document.getElementById('guest-address').textContent = transaction.guest?.address || 'N/A';

    // --- Stay Details (Using ACTUAL checkout for invoices) ---
    document.getElementById('checkin-date').textContent = formatDate(transaction.stay_details?.actual_checkin);
    document.getElementById('checkout-date').textContent = formatDate(transaction.stay_details?.actual_checkout); // Critical: Uses actual_checkout
    document.getElementById('stay-duration').textContent = `${transaction.stay_details?.stay_hours || 0} hours`;
    document.getElementById('guest-count').textContent = `${transaction.stay_details?.guest_num || 0} person(s)`;
    document.getElementById('stay-status').textContent = transaction.current_status || 'N/A';

    // --- Payment Breakdown ---
    const originalRate = transaction.meta?.original_rate || 0; // 250 PHP (base rate       // 0 PHP (no discount)
    const totalRate = transaction.meta?.total_rate || 0;      // 750 PHP (adjusted total)

    document.getElementById('room-charge').textContent = formatCurrency(originalRate);
    document.getElementById('subtotal').textContent = formatCurrency(totalRate);

    // --- Payments (Sum all amounts) ---
    const totalPaid = transaction.payments?.reduce((sum, p) => sum + (p.amount || 0), 0) || 0;
    const paymentMethods = transaction.payments?.map(p => 
        p.method ? p.method.charAt(0).toUpperCase() + p.method.slice(1) : 'N/A'
    ).join(', ') || 'N/A';

    document.getElementById('payment-method-invoice').textContent = paymentMethods;
    document.getElementById('amount-paid').textContent = formatCurrency(totalPaid);

    // --- Balance & Change ---
    const changeGiven = transaction.meta?.change_given || 0;
    const balance = totalRate - totalPaid; // Could be negative (overpayment)

    document.getElementById('change-given').textContent = formatCurrency(changeGiven);
    document.getElementById('balance').textContent = formatCurrency(balance > 0 ? balance : 0); // No negative balances

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
