 let currentTransaction;

function getTransaction(id) {
    // Find the specific booking in the books array
    return books.find(book => book.id === id);
}
function populateBookingModal(bookingData) {
    // Basic Info
    document.getElementById('booking-id').value = bookingData.id;
    document.getElementById('booking-id-display').textContent = `ID: ${bookingData.short_id}`;
    document.getElementById('booking-status').textContent = bookingData.status;
    document.getElementById('booking-amount').textContent = bookingData.amount;

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
    document.getElementById('original-rate').textContent = `₱${bookingData.raw_data.meta.original_rate.toFixed(2)}`;
    document.getElementById('discount-amount').textContent = `₱${(bookingData.raw_data.meta.discount || 0).toFixed(2)}`;
    document.getElementById('total-amount').textContent = `₱${(bookingData.raw_data.meta.original_rate - (bookingData.raw_data.meta.discount || 0)).toFixed(2)}`;
    
    if (bookingData.raw_data.payments && bookingData.raw_data.payments.length > 0) {
        const payment = bookingData.raw_data.payments[0];
        document.getElementById('payment-method').textContent = payment.method;
        document.getElementById('payment-reference').textContent = payment.details.reference_no;
    }
}
function checkinExistingBook(bookId){
    console.log(bookId);
    currentBooking = getTransaction(bookId);
    console.log(currentBooking);
    populateBookingModal(currentBooking);
    currentTransaction = currentBooking;

    currentModal = new bootstrap.Modal(document.getElementById('checkInBook'));
    currentModal.show();
    
}

function check_in(){
    currentModal = bootstrap.Modal.getInstance(document.getElementById('checkInBook'));

    if(currentModal){
        currentModal.hide();
    }
    newModal = new bootstrap.Modal(document.getElementById("confirm-check-book"));
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
            guest_id: currentBooking.guest._id.$oid,
            actual_checkin: actualCheckin,
            expected_checkout: expectedCheckout,
            status: 'confirmed',
            stay_hours: currentBooking.raw_data.stay_details.stay_hours
        };

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
            
            bootstrap.Modal.getInstance(document.getElementById('checkInBook')).hide();
            
            // Show success message with formatted time
            const formattedTime = new Date(actualCheckin).toLocaleTimeString();
            alert(`Check-in successful at ${formattedTime}`);
            
            location.reload();
        } else {
            throw new Error(result.message || 'Check-in failed');
        }
    } catch (error) {
        console.error('Check-in error:', error);
        alert(`Error: ${error.message}`);
    }
}