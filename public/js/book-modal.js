let bookingScanner;
let book_modal_part = 1;
const nextButtonBook = document.getElementById('next-button-book');
let bookData;
let currentRoomType;
let computedBookingRate;
let specificRoom;
let theMethod;

const bookingOutput = {
    // Guest Information
    firstName: document.getElementById('book-output-fname'),
    lastName: document.getElementById('book-output-lname'),
    gender: document.getElementById('book-output-gender'),
    phone: document.getElementById('book-output-phone'),
    email: document.getElementById('book-output-email'),
    address: document.getElementById('book-output-address'),
    
    // Booking Information
    checkinDate: document.getElementById('book-output-checkin-date'),
    checkinTime: document.getElementById('book-output-checkin-time'),
    checkoutDate: document.getElementById('book-output-checkout-date'),
    checkoutTime: document.getElementById('book-output-checkout-time'),
    roomType: document.getElementById('book-output-room-type'),
    roomNumber: document.getElementById('book-output-room-no'),
    guestCount: document.getElementById('book-output-guest-count'),
    rate: document.getElementById('book-output-rate'),
    
    // Method to update all output fields
    updateOutput: function(guestData, bookingData, currentType) {
        // Guest Information
        if (guestData) {
            this.firstName.textContent = guestData.firstName || '';
            this.lastName.textContent = guestData.lastName || '';
            this.gender.textContent = guestData.gender || '';
            this.phone.textContent = guestData.mobileNumber || '';
            this.email.textContent = guestData.email || '';
            this.address.textContent = guestData.address || '';
        }
        
        // Booking Information
        if (bookingData) {
            this.checkinDate.textContent = bookingData.checkinDate || '';
            this.checkinTime.textContent = bookingData.checkinTime || '';
            this.checkoutDate.textContent = bookingData.checkoutDate || '';
            this.checkoutTime.textContent = bookingData.checkoutTime || '';
            this.roomType.textContent = currentType || '';
            this.roomNumber.textContent = bookingData.roomNumber || '';
            this.guestCount.textContent = bookingData.guestCount || '';
            this.rate.textContent = computedBookingRate ? `P ${computedBookingRate.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}` : '';
        }
    },
    
    // Method to clear all output fields
    clearOutput: function() {
        const fields = [
            this.firstName, this.lastName, this.gender, this.phone,
            this.email, this.address, this.checkinDate, this.checkinTime,
            this.checkoutDate, this.checkoutTime, this.roomType,
            this.roomNumber, this.guestCount, this.rate
        ];
        
        fields.forEach(field => {
            field.textContent = '';
        });
    }
};

const bookingDetails = {
    // Date/Time Fields
    checkinDate: document.getElementById('book-checkin-date'),
    checkinTime: document.getElementById('book-checkin-time'),
    checkoutDate: document.getElementById('book-checkout-date'),
    checkoutTime: document.getElementById('book-checkout-time'),
    
    // Room Fields
    roomType: document.getElementById('book-room-type'),
    roomNumber: document.getElementById('book-room-number'),
    
    // Guest Fields
    guestCount: document.getElementById('book-guest-count'),
    hoursStay: document.getElementById('book-hours-stay'),
    
    // Methods
    getBookingData: function() {
        // Helper function to format date (e.g., "April 25, 2024")
        const formatDate = (date) => {
            return new Date(date).toLocaleString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            }).replace(',', ''); // Remove comma after year
        };
    
        // Helper function to format time (e.g., "9:49 pm")
        const formatTime = (time) => {
            const [hours, minutes] = time.split(':');
            const date = new Date();
            date.setHours(hours, minutes);
            return date.toLocaleString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        };

        return {
            checkinDate: formatDate(this.checkinDate.value),
            checkinTime: formatTime(this.checkinTime.value),
            checkoutDate: formatDate(this.checkoutDate.value),
            checkoutTime: formatTime(this.checkoutTime.value),
            roomType: this.roomType.value,
            roomNumber: this.roomNumber.value,
            guestCount: this.guestCount.value,
            hoursStay: this.hoursStay.value
        };
    },
    
    clearBookingForm: function() {
        this.checkinDate.value = '';
        this.checkinTime.value = '';
        this.checkoutDate.value = '';
        this.checkoutTime.value = '';
        this.roomType.selectedIndex = 0;
        this.roomNumber.value = '';
        this.guestCount.value = '';
        this.hoursStay.value = '';
    },
    
    calculateHoursStay: function() {
        if (this.checkinDate.value && this.checkinTime.value && 
            this.checkoutDate.value && this.checkoutTime.value) {
            const checkin = new Date(`${this.checkinDate.value}T${this.checkinTime.value}`);
            const checkout = new Date(`${this.checkoutDate.value}T${this.checkoutTime.value}`);
            const diff = checkout - checkin;
            this.hoursStay.value = Math.round(diff / (1000 * 60 * 60)); // Convert ms to hours
        }
    }
};
const existingUserData = {
    firstName: document.getElementById('existing-fname'),
    lastName: document.getElementById('existing-lname'),
    gender: document.getElementById('existing-gender'),
    email: document.getElementById('existing-email'),
    phone: document.getElementById('existing-phone'),
    address: document.getElementById('existing-address'),
    lastCheckIn: document.getElementById('existing-last-checkin'),
    
    // Method to update all values at once
    updateValues: function(data) {
        if (data.firstName) this.firstName.textContent = data.firstName;
        if (data.lastName) this.lastName.textContent = data.lastName;
        if (data.gender) this.gender.textContent = data.gender;
        if (data.email) this.email.textContent = data.email;
        if (data.phone) this.phone.textContent = data.phone;
        if (data.address) this.address.textContent = data.address;
        if (data.last_checkin) this.lastCheckIn.textContent = data.last_checkin;
    },
    
    // Method to clear all values
    clearValues: function() {
        this.firstName.textContent = '';
        this.lastName.textContent = '';
        this.gender.textContent = '';
        this.email.textContent = '';
        this.phone.textContent = '';
        this.address.textContent = '';
        this.lastCheckIn.textContent = '';
    }
};
// Payment elements
const bookForm = {
    // Name fields
    firstName: document.getElementById('book-fname-input'),
    lastName: document.getElementById('book-lname-input'),
    
    // Contact fields
    email: document.getElementById('book-email-input'),
    mobileNumber: document.getElementById('book-phone-input'),
    
    // Address and gender
    address: document.getElementById('book-address-input'),
    gender: document.getElementById('book-gender-select'),
    
    // QR related elements
    qrAskLabel: document.getElementById('book-qr-ask-label'),
    qrButton: document.getElementById('book-qr-button'),
    
    // Utility methods
    getFormData: function() {
      return {
        firstName: this.firstName.value,
        lastName: this.lastName.value,
        email: this.email.value,
        mobileNumber: this.mobileNumber.value,
        address: this.address.value,
        gender: this.gender.value
      };
    },
    
    clearForm: function() {
      this.firstName.value = '';
      this.lastName.value = '';
      this.email.value = '';
      this.phone.value = '';
      this.address.value = '';
      this.gender.selectedIndex = 0; // Reset to "Select Gender"
    },
    
    isValid: function() {
      // Basic validation - check required fields
      return this.firstName.value.trim() !== '' && 
             this.lastName.value.trim() !== '' && 
             this.email.value.trim() !== '' && 
             this.phone.value.trim() !== '';
    }
  };

  
let currentBookingGuest = null;
let selectedBookingRoom;
let computedBookingAmount;
let bookingRoomInput = null;

// Booking payment info object
let bookingPaymentInfo = {
    method: null,               // 'cash' or 'gcash'
    amountPaid: 0,              // Amount paid
    baseRate: computedBookingAmount, // The base rate/price
    changeDue: 0,               // Change (always 0 for GCash)
    gcashAccountName: '',       // GCash account name
    gcashReferenceNumber: ''    // GCash reference number
};

let currentBookingRate;

function prepareBookData() {
    const roomInput = bookingDetails.getBookingData();
    const guestData = currentBookingGuest;
    const paymentDetails = bookingPaymentInfo;
    
    console.log("Room Input:", roomInput);
    console.log("Guest Data:", guestData);
    console.log("Payment Details:", paymentDetails);
    
    // Parse the date and time strings correctly
    // First, convert date and time to a proper format
    const parseDateTime = (dateStr, timeStr) => {
        // Handle formats like "April 26 2025", "3:20 PM"
        const dateParts = dateStr.split(' ');
        const month = dateParts[0]; // April
        const day = parseInt(dateParts[1]); // 26
        const year = parseInt(dateParts[2]); // 2025
        
        // Parse time with AM/PM
        let [hours, minutesPart] = timeStr.split(':');
        let minutes = minutesPart.substring(0, 2);
        const isPM = timeStr.toLowerCase().includes('pm');
        
        hours = parseInt(hours);
        if (isPM && hours < 12) hours += 12;
        if (!isPM && hours === 12) hours = 0;
        
        // Create a date object using UTC to avoid timezone issues
        const date = new Date(Date.UTC(year, getMonthNumber(month), day, hours, parseInt(minutes)));
        return date;
    };
    
    // Helper function to convert month name to month number (0-11)
    const getMonthNumber = (monthName) => {
        const months = {
            'january': 0, 'february': 1, 'march': 2, 'april': 3,
            'may': 4, 'june': 5, 'july': 6, 'august': 7,
            'september': 8, 'october': 9, 'november': 10, 'december': 11
        };
        return months[monthName.toLowerCase()];
    };

    // Parse dates
    const checkin = parseDateTime(roomInput.checkinDate, roomInput.checkinTime);
    const checkout = parseDateTime(roomInput.checkoutDate, roomInput.checkoutTime);
    
    // Check if dates are valid
    if (isNaN(checkin.getTime()) || isNaN(checkout.getTime())) {
        console.error("Invalid date/time values:", {
            checkinDate: roomInput.checkinDate,
            checkinTime: roomInput.checkinTime,
            checkoutDate: roomInput.checkoutDate,
            checkoutTime: roomInput.checkoutTime
        });
        throw new Error("Invalid date or time format");
    }

    // Create a structured guest data object
    const formattedGuestData = {
        fname: guestData.firstName,
        lname: guestData.lastName,
        gender: guestData.gender,
        email: guestData.email,
        mobileNum: guestData.mobileNumber,
        address: guestData.address,
        level: 1
    };
    
    // Determine if guest exists already based on if the _id exists
    const isExist = guestData._id && guestData._id.$oid ? true : false;
    // Set payment details
    
    

    // Base transaction structure
    const transactionData = {
        is_guest: isExist,
        guest_id: isExist ? guestData._id.$oid : null,
        employee_id: userId, // Assuming userId is defined elsewhere
        room_id: roomInput.roomType, // Using the roomType ID
        transaction_type: "Booking",
        payments: [{
            method: paymentDetails.method || 'cash',
            details: {},
            amount: paymentDetails.amountPaid,
            currency: "PHP",
            status: "completed",
            processed_at: new Date().toISOString()
        }],
        stay_details: {
            expected_checkin: checkin.toISOString(),
            expected_checkout: checkout.toISOString(),
            actual_checkin: checkin.toISOString(),
            actual_checkout: null,
            guest_num: parseInt(roomInput.guestCount) || 1,
            stay_hours: parseInt(roomInput.hoursStay) || 24,
            time_allowance: 4
        },
        current_status: "booked",
        audit_log: [{
            action: "created",
            by: userId,
            timestamp: new Date().toISOString(),
            points_earned: 0
        }],
        meta: {
            original_rate: paymentDetails.baseRate,
            change_given: paymentDetails.changeDue
        },
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
        new_guest: formattedGuestData
    };

    // Add payment method specific details
    if (paymentDetails.method === 'gcash') {
        transactionData.payments[0].details = {
            reference_no: paymentDetails.gcashReferenceNumber || '',
            account_name: paymentDetails.gcashAccountName || ''
        };
    }

    console.log("Prepared Transaction Data:", transactionData);
    return transactionData;
}

async function completeBook() {
  
    // 1. Prepare the transaction data
    const data = prepareBookData();
    console.log('Transaction data:', data);
    
    try {
        // 2. Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // 3. Show loading state (optional)
        const submitBtn = document.getElementById('submit-btn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
        }

        // 4. Send data to server
        const response = await fetch('/frontdesk/transactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        // 5. Handle response
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Transaction failed');
        }

        const responseData = await response.json();
        const transactionId = responseData.debug.transaction_id; // Get the ID

        // 6. UI Updates (your existing code)
        // Hide all input-content-1 through input-content-11
        for (let i = 1; i <= 11; i++) {
            const contentElements = document.querySelectorAll(`.input-content-${i}`);
            contentElements.forEach(element => {
                element.style.display = 'none';
            });
        }
        
        document.getElementById('prev-button-book').style.display = "none";
        
        // Handle modals
        const currentModal = bootstrap.Modal.getInstance(document.getElementById('confirm-book'));
        const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));

        if (currentModal) {
            currentModal.hide();
        }

        populateInvoiceModal(data, transactionId);
        invoiceModal.show();

        // 7. Show success message (optional)
        console.log('Transaction completed successfully');
        
    } catch (error) {
        // 8. Error handling
        console.error('Transaction error:', error);
        alert('Error: ' + error.message);
        
    } finally {
        // 9. Reset button state (optional)
        const submitBtn = document.getElementById('submit-btn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Complete Transaction';
        }
    }
}
document.addEventListener("DOMContentLoaded", function () {
    let scanner_book = null; // Declare scanner_book_book variable

    // Function to start scanning
    function startscanner_book() {
        // Choose the correct QR reader based on book_modal_part
        const qrReaderDiv = (book_modal_part === 2 ? document.querySelector("#book-modal #qr-reader3") : document.querySelector("#book-modal #qr-reader4"));
        
        if (!scanner_book) {
            scanner_book = new Html5Qrcode(qrReaderDiv.id); // Use dynamic element ID

            // Request camera and start scanning
            scanner_book.start(
                { facingMode: "environment" }, // "environment" for rear cam, "user" for front cam
                { fps: 20, qrbox: 250 },
                (decodedText) => {
                    scanner_book.stop();
                },
                (errorMessage) => {
                    console.warn("QR Scan Error:", errorMessage);
                }
            );
        }
    }

    // Function to stop scanning
    function stopscanner_book() {
        if (scanner_book) {
            scanner_book.stop().then(() => {
                scanner_book.clear();
                scanner_book = null;
            });
        }
    }

    // Monitor modal part dynamically
    let modalPartInterval = setInterval(() => {
        const modal = document.querySelector("#book-modal");
        if (modal && modal.classList.contains("show")) {
            // Check if book_modal_part is 2 or 8
            if ((book_modal_part === 2 || book_modal_part === 8) && !scanner_book) {
                startscanner_book(); // Start scanner_book only if book_modal_part is 2 or 8
            } else if ((book_modal_part !== 2 && book_modal_part !== 8) && scanner_book) {
                stopscanner_book(); // Stop scanner_book if book_modal_part is not 2 or 8
            }
        }
    }, 1000); // Check every second

    // Clean up the interval when the modal is closed
    document.querySelector("#book-modal").addEventListener("hidden.bs.modal", function () {
        clearInterval(modalPartInterval); // Stop checking modal part
        stopscanner_book(); // Stop the scanner_book when the modal is closed
    });
});

        
var current, next, prevButton, cancelButton;

function switchContent(currentSelector, nextSelector, prevButtonSelector, cancelButtonSelector, modalTitleSelector, modalSubMsgSelector, newTitle, newSubMsg) {
    // Adjusted to select elements within #book-modal
    let current = document.querySelector(currentSelector);
    let next = document.querySelector(nextSelector);
    let prevButton = document.getElementById(prevButtonSelector);
    let cancelButton = document.getElementById(cancelButtonSelector);
    let modalTitle = document.querySelector(modalTitleSelector);
    let modalSubMsg = document.querySelector(modalSubMsgSelector);

    if (!current || !next || !prevButton || !cancelButton || !modalTitle || !modalSubMsg) {
        console.error("One or more elements not found.");
        return;
    }

    // Fade out title and subtitle
    modalTitle.classList.add("fade-out");
    modalSubMsg.classList.add("fade-out");

    setTimeout(() => {
        // Change text after fading out
        modalTitle.textContent = newTitle;
        modalSubMsg.textContent = newSubMsg;

        // Fade in title and subtitle
        modalTitle.classList.remove("fade-out");
        modalTitle.classList.add("fade-in");

        modalSubMsg.classList.remove("fade-out");
        modalSubMsg.classList.add("fade-in");

        setTimeout(() => {
            modalTitle.classList.remove("fade-in");
            modalSubMsg.classList.remove("fade-in");
        }, 500);
    }, 500); // Delay to allow fade-out animation

    // Fade out current content
    current.classList.add("fade-out");

    setTimeout(() => {
        current.style.display = "none";
        current.classList.remove("fade-out");

        next.style.display = "block";
        next.classList.add("fade-in");

        setTimeout(() => {
            next.classList.remove("fade-in");
        }, 500);

        // Handle button transitions
        cancelButton.style.opacity = "1"; // Reset opacity
        cancelButton.classList.add("fade-out");

        setTimeout(() => {
            cancelButton.style.display = "none";
            cancelButton.classList.remove("fade-out");
            prevButton.style.display = "block";

            setTimeout(() => {
                prevButton.classList.remove("fade-in");
                prevButton.style.opacity = "1"; // Ensure visibility is fully restored
            }, 500);
        }, 500);
    }, 500); // Wait for fade-out transition
}

function user_qr_book() {
    switchContent(
        "#book-modal .input-content-1",   // Current content within the modal
        "#book-modal .input-content-2",   // Next content within the modal
        "prev-button-book",                       // Previous button
        "cancel-button-book",                     // Cancel button
        "#book-modal #book-modalLabel", // Modal title
        "#book-modal #sub-msg",           // Modal subtitle
        "Scan QR Code",                      // New title text
        ""                                   // New subtitle text
    );
    book_modal_part = 2;
}
function modal_switch_next_book() {

    switch (book_modal_part) {
        case 1:
            switchContent(
                "#book-modal .input-content-1",   // Current content
                "#book-modal .input-content-4",   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "Book Room",           // New title text
                "Enter booking details." // New subtitle text
            );
            currentBookingGuest = bookForm.getFormData();
            nextButtonBook.disabled = true;
            book_modal_part = 4;
            break;
        case 2:
            switchContent(
                "#book-modal .input-content-2",   // Current content
                "#book-modal .input-content-3",   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "Book Room",           // New title text
                "Enter booking details." // New subtitle text
            );
            
            book_modal_part = 20;
            break; 
        case 20:
            switchContent(
                "#book-modal .input-content-1",   // Current content
                "#book-modal .input-content-3",   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "Existing Guest",           // New title text
                "An exisisting guest has been detected." // New subtitle text
            );
            nextButtonBook.disabled = false;
            book_modal_part = 3;
            break;
        case 3:
            switchContent(
                "#book-modal .input-content-3",   // Current content
                "#book-modal .input-content-4",   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "Book Room",           // New title text
                "Enter booking details." // New subtitle text
            );
            nextButtonBook.disabled = true;
            book_modal_part = 4;
            break;
        case 4:
            switchContent(
                "#book-modal .input-content-4",   // Current content
                "#book-modal .input-content-5",   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "Confirm Details",           // New title text
                "" // New subtitle text
            );
            bookingOutput.updateOutput(currentBookingGuest, bookingDetails.getBookingData(), currentRoomType);
            nextButtonBook.disabled = false;
            book_modal_part = 5;
            break;
        case 5:
            switchContent(
                "#book-modal .input-content-5",   // Current content
                "#book-modal .input-content-6",   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "Payment Method",           // New title text
                "" // New subtitle text
            );
            nextButtonBook.disabled = true;
            book_modal_part = 6;
            break;
        case 6:
            switchContent(
                "#book-modal .input-content-6",   // Current content
                "#book-modal .input-content-7",   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "Confirm Payment Details",           // New title text
                "" // New subtitle text
            );
            document.getElementById('book-method').textContent = paymentMethods.selectedMethod == 'gcash' ? "GCash" : 'Cash';
            document.getElementById('book-tot-value').textContent = `P ${computedBookingRate.toFixed(2)}`;
            book_modal_part = 7;
            break;
        case 7:
            let payNext = paymentMethods.selectedMethod == "cash" ? "#book-modal .input-content-11" : "#book-modal .input-content-10";
            switchContent(
                "#book-modal .input-content-7",   // Current content
                payNext,   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "",         // New title text
                "" // New subtitle text
            );
            console.log("putnagafgsdfgasdfgsdfg");
            book_modal_part = paymentMethods.selectedMethod == "cash" ? 11 : 10;
            document.getElementById('book-cash-total').textContent = `P ${computedBookingRate.toFixed(2)}`;
            nextButtonBook.disabled = true;
            break;
        case 10:
        case 11:
            updateBookingPaymentInfo();
            // Close current modal
            const checkInModal = bootstrap.Modal.getInstance(document.getElementById('book-modal'));
            checkInModal.hide();
                    
            const confirmModal = new bootstrap.Modal(document.getElementById('confirm-book'));
            confirmModal.show();
            break;
    }
}

function switchToPreviousContent(currentSelector, previousSelector, prevButtonSelector, cancelButtonSelector, modalTitleSelector, modalSubMsgSelector, newTitle, newSubMsg) {
    let current = document.querySelector(currentSelector);
    let previous = document.querySelector(previousSelector);
    let prevButton = document.getElementById(prevButtonSelector);
    let cancelButton = document.getElementById(cancelButtonSelector);
    let modalTitle = document.querySelector(modalTitleSelector); // Changed to querySelector
    let modalSubMsg = document.querySelector(modalSubMsgSelector); // Changed to querySelector


    console.log(current);
    console.log(previous);
    console.log(prevButton);
    console.log(cancelButton);
    console.log(modalTitle);
    console.log(modalSubMsg);


    if (!current || !previous || !prevButton || !cancelButton || !modalTitle || !modalSubMsg) {
        console.error("One or more elements not found.");
        return;
    }

    // Fade out title and subtitle
    modalTitle.classList.add("fade-out");
    modalSubMsg.classList.add("fade-out");

    setTimeout(() => {
        // Change text after fading out
        modalTitle.textContent = newTitle;
        modalSubMsg.textContent = newSubMsg;

        // Fade in title and subtitle
        modalTitle.classList.replace("fade-out", "fade-in");
        modalSubMsg.classList.replace("fade-out", "fade-in");

        setTimeout(() => {
            modalTitle.classList.remove("fade-in");
            modalSubMsg.classList.remove("fade-in");
        }, 500);
    }, 500); // Delay to allow fade-out animation

    // Fade out current content
    current.classList.add("fade-out");

    setTimeout(() => {
        current.style.display = "none";
        current.classList.remove("fade-out");

        previous.style.display = "block";
        previous.classList.add("fade-in");

        setTimeout(() => {
            previous.classList.remove("fade-in");
        }, 500);
     
    }, 500); // Wait for fade-out transition
}

function modal_switch_prev_book() {
    switch (book_modal_part) {
        case 2:
            switchToPreviousContent(
                "#book-modal .input-content-2",   // Current content
                "#book-modal .input-content-1",   // Previous content
                "prev-button-book",        // Previous button (should be hidden)
                "cancel-button-book",      // Cancel button (should be shown)
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",            // Modal subtitle
                "Book Room",           // New title text
                "Enter guest details." // New subtitle text
            );         
            setTimeout(() => {   
                let confirmButton = document.querySelector('#book-modal #prev-button-book');
                let nextButton = document.querySelector('#book-modal #cancel-button-book');
            
                if (confirmButton) {
                    confirmButton.style.display = 'none'; // Instantly hide confirm button
                }
            
                if (nextButton) {
                    nextButton.style.display = 'block';
                    nextButton.classList.add('fade-in'); // Apply fade-in effect
                    setTimeout(() => {
                        nextButton.classList.remove('fade-in'); // Remove class after fading in
                    }, 500);
                }
            }, 500);       

            book_modal_part = 1;
            break;
        case 3:
            switchToPreviousContent(
                "#book-modal .input-content-3",   // Current content
                "#book-modal .input-content-1",   // Previous content
                "prev-button-book",        // Previous button (should be hidden)
                "cancel-button-book",      // Cancel button (should be shown)
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",            // Modal subtitle
                "Book Room",           // New title text
                "Enter guest details." // New subtitle text
            );    
            setTimeout(() => {   
                let confirmButton = document.querySelector('#book-modal #prev-button-book');
                let nextButton = document.querySelector('#book-modal #cancel-button-book');
            
                if (confirmButton) {
                    confirmButton.style.display = 'none'; // Instantly hide confirm button
                }
            
                if (nextButton) {
                    nextButton.style.display = 'block';
                    nextButton.classList.add('fade-in'); // Apply fade-in effect
                    setTimeout(() => {
                        nextButton.classList.remove('fade-in'); // Remove class after fading in
                    }, 500);
                }
            }, 500);                 
            book_modal_part = 1;
            break;
        case 4:
            switchToPreviousContent(
                "#book-modal .input-content-4",   // Current content
                "#book-modal .input-content-1",   // Previous content
                "prev-button-book",        // Previous button (should be hidden)
                "cancel-button-book",      // Cancel button (should be shown)
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",            // Modal subtitle
                "Book Room",           // New title text
                "Enter guest details." // New subtitle text
            );    
            setTimeout(() => {   
                let confirmButton = document.querySelector('#book-modal #prev-button-book');
                let nextButton = document.querySelector('#book-modal #cancel-button-book');
            
                if (confirmButton) {
                    confirmButton.style.display = 'none'; // Instantly hide confirm button
                }
            
                if (nextButton) {
                    nextButton.style.display = 'block';
                    nextButton.classList.add('fade-in'); // Apply fade-in effect
                    setTimeout(() => {
                        nextButton.classList.remove('fade-in'); // Remove class after fading in
                    }, 500);
                }
            }, 500);                 
            book_modal_part = 1;
            break;
        case 5:
            switchToPreviousContent(
                "#book-modal .input-content-5",   // Current content
                "#book-modal .input-content-4",   // Previous content
                "cancel-button-book",
                "prev-button-book",        // Previous button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",            // Modal subtitle
                "Book Room",           // New title text
                "Enter check-in details." // New subtitle text
            );
            book_modal_part = 4;
            break;

        case 6:
            switchToPreviousContent(
                "#book-modal .input-content-6",   // Current content
                "#book-modal .input-content-5",   // Previous content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",            // Modal subtitle
                "Confirm Details",    // New title text
                "" // New subtitle text
            );
            book_modal_part = 5;
            break;

        case 7:
            switchToPreviousContent(
                "#book-modal .input-content-7",   // Current content
                "#book-modal .input-content-6",   // Previous content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            book_modal_part = 6;
            break;
         case 10:
            console.log("putaningamo: ", book_modal_part);
            switchToPreviousContent(
                "#book-modal .input-content-10",   // Current content
                "#book-modal .input-content-7",   // Previous content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            book_modal_part = 7;
            break;
         case 11:
            switchToPreviousContent(
                "#book-modal .input-content-11",   // Current content
                "#book-modal .input-content-7",   // Previous content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            paymentForms.gcashForm.style.display = 'none';
            paymentForms.cashForm.style.display = 'none';
            paymentMethods.clearSelection();
            book_modal_part = 7;
            break;
    }
}

function isBookFormEmpty() {
    if (!bookForm) {
        console.log("Form not found");
        return true;
    }
    return (
        !bookForm.firstName?.value?.trim() ||  // If ANY field is empty,
        !bookForm.lastName?.value?.trim() ||   // return true (disable button)
        !bookForm.email?.value?.trim() ||
        !bookForm.mobileNumber?.value?.trim() ||
        !bookForm.address?.value?.trim() ||
        !bookForm.gender?.value || bookForm.gender.value === 'Select Gender'
    );
}
  // Function to update button state
  function updateNextButtonState1() {
    console.log("putanginga");
    nextButtonBook.disabled = isBookFormEmpty();
  }
  
  // Add input event listeners to all form fields
  const formFields = [
    bookForm.firstName,
    bookForm.lastName,
    bookForm.email,
    bookForm.mobileNumber,
    bookForm.address,
    bookForm.gender
  ];
  
  formFields.forEach(field => {
    field.addEventListener('input', updateNextButtonState1);
    // For select elements, we need the change event
    if (field.tagName === 'SELECT') {
      field.addEventListener('change', updateNextButtonState1);
    }
  });
  

  // Initialize button state on page load
  document.addEventListener('DOMContentLoaded', function() {
    let allGuests = []; 

     // Function to find guest by name
     function findGuestByName(firstName, lastName) {
        return allGuests.find(guest => 
            guest.firstName.toLowerCase() === firstName.toLowerCase() && 
            guest.lastName.toLowerCase() === lastName.toLowerCase()
        );
    }
   
    function handleNameInput() {
        const firstName = bookForm.firstName.value.trim();
        const lastName = bookForm.lastName.value.trim();
        
        if (firstName && lastName) {
            currentBookingGuest = findGuestByName(firstName, lastName);
            
            if (currentBookingGuest) {
                // Name exists - show the guest data and highlight fields
                console.log('Current guest data:', currentBookingGuest);
                existingUserData.updateValues(currentBookingGuest);
                book_modal_part = 20;
                modal_switch_next_book(currentBookingGuest); 
                // You can now use currentBookingGuest throughout your application
            } else {
                // Name doesn't exist - reset any warnings and clear variable
                isExist = true;
                fnameInput.style.borderColor = '';
                lnameInput.style.borderColor = '';
                currentBookingGuest = null;
            }
        } else {
            currentBookingGuest = null;
        }
    }

    // Add event listeners
    bookForm.firstName.addEventListener('input', handleNameInput);
    bookForm.lastName.addEventListener('input', handleNameInput);

    document.getElementById('book-now').addEventListener('click', async function() {
        try {
            // Fetch data
            const [guestsResponse, checkinResponse] = await Promise.all([
                fetch('/get-guests'),
                fetch('/get-checkin-data')
            ]);
            
            if (!guestsResponse.ok || !checkinResponse.ok) throw new Error('Failed to fetch data');
            
            
            allGuests = await guestsResponse.json(),
            bookData = await checkinResponse.json();
            
            // Populate room types
            const roomTypeSelect = document.getElementById('book-room-type');
            
            // Clear existing options (keep first default option)
            while (roomTypeSelect.options.length > 1) {
                roomTypeSelect.remove(1);
            }
            
            // Add new options from API data
            bookData.all_types.forEach(room => {
                if (room.status === 'active') {
                    const option = new Option(room.type_name, room._id.$oid);
                    roomTypeSelect.add(option);
                }
            });
            
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to load room types');
        }
    });
  });
  function mergeBookDateTime(dateInput, timeInput) {
    // Get the date and time values
    const dateValue = dateInput.value;
    const timeValue = timeInput.value;
    
    // Return null if either value is missing
    if (!dateValue || !timeValue) return null;
    
    // Create a Date object combining both values
    const combined = new Date(`${dateValue}T${timeValue}`);
    
    // Format as ISO string with milliseconds and Z timezone
    const isoString = combined.toISOString();
    
    // For the exact format you requested (with 6 decimal places)
    return isoString.replace(/(\.\d{3})Z$/, '$1000Z');
}

  function getSchedule(id) {
    // Log the input for debugging
    console.log("Fetching schedule for room type:", id);

    // Validate bookData structure
    if (!bookData || !bookData.rooms || !Array.isArray(bookData.rooms)) {
        console.error('Invalid bookData structure');
        return null;
    }

    // Get check-in and check-out datetimes
    const checkinDateTime = mergeBookDateTime(bookingDetails.checkinDate, bookingDetails.checkinTime);
    const checkoutDateTime = mergeBookDateTime(bookingDetails.checkoutDate, bookingDetails.checkoutTime);

    // Validate datetimes
    if (!checkinDateTime || !checkoutDateTime) {
        console.error('Invalid check-in or check-out datetime');
        return null;
    }

    // Convert to Date objects for comparison
    const checkinDate = new Date(checkinDateTime);
    const checkoutDate = new Date(checkoutDateTime);

    // Validate that check-out is after check-in
    if (checkoutDate <= checkinDate) {
        console.error('Check-out must be after check-in');
        return null;
    }

    // Filter rooms by room type
    const filteredRoomsByTypeId = bookData.rooms.filter(room => {
        // Handle room_type as string or ObjectId
        return room.room_type == id || (room.room_type && room.room_type['$oid'] == id);
    });

    // Filter rooms with no conflicting schedules
    const filteredRooms = filteredRoomsByTypeId.filter(room => {
        // Find schedules for this room
        const roomSchedules = bookData.schedules.filter(schedule => 
            schedule.room_id === room.id
        );

        // Check for any conflicting schedules
        const hasConflict = roomSchedules.some(schedule => {
            const scheduleCheckin = new Date(schedule.expected_checkin);
            const scheduleCheckout = new Date(schedule.expected_checkout);

            // Validate schedule dates
            if (isNaN(scheduleCheckin.getTime()) || isNaN(scheduleCheckout.getTime())) {
                console.warn('Invalid schedule dates:', schedule);
                return false; // Skip invalid schedules
            }

            // Check for overlap: start1 <= end2 AND start2 <= end1
            return checkinDate <= scheduleCheckout && scheduleCheckin <= checkoutDate;
        });

        return !hasConflict;
    });

    // Return the first available room or null
    selectedBookingRoom = filteredRooms.length > 0 ? filteredRooms[0] : null;
    console.log('Selected room:', selectedBookingRoom);
}
  // Function to check if booking form is valid
function isBookingFormValid() {
    return (
      bookingDetails.checkinDate.value &&
      bookingDetails.checkinTime.value &&
      bookingDetails.checkoutDate.value &&
      bookingDetails.checkoutTime.value &&
      bookingDetails.roomType.value !== 'Select room type'
    );
  }
  function computeHoursStay(checkinDate, checkinTime, checkoutDate, checkoutTime) {
    // Convert your date/time strings to proper format
    const formatTime = (timeStr) => {
        const [time, period] = timeStr.split(' ');
        let [hours, minutes] = time.split(':');
        hours = parseInt(hours);
        minutes = parseInt(minutes || '0');
        
        if (period === 'PM' && hours < 12) hours += 12;
        if (period === 'AM' && hours === 12) hours = 0;
        
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:00`;
    };

    const formatDate = (dateStr) => {
        const months = {
            January: '01', February: '02', March: '03', April: '04', May: '05', June: '06',
            July: '07', August: '08', September: '09', October: '10', November: '11', December: '12'
        };
        const parts = dateStr.split(' ');
        const month = months[parts[0]];
        const day = parts[1].padStart(2, '0');
        const year = parts[2];
        return `${year}-${month}-${day}`;
    };

    try {
        const checkin = new Date(`${formatDate(checkinDate)}T${formatTime(checkinTime)}`);
        const checkout = new Date(`${formatDate(checkoutDate)}T${formatTime(checkoutTime)}`);
        
        if (isNaN(checkin.getTime()) || isNaN(checkout.getTime())) {
            console.error('Invalid date calculation');
            return 0;
        }

        const hoursStay = (checkout - checkin) / (1000 * 60 * 60);
        
        if (hoursStay <= 12) return 12;
        else if (hoursStay <= 24) return 24;
        else return Math.ceil(hoursStay / 12) * 12;
    } catch (e) {
        console.error('Date calculation error:', e);
        return 0;
    }
}
  /**  Then compute mo na si bayad  ** */
  function computeBookRate(hoursStay, rate12h, rate24h) {
    // First, determine which rate blocks apply
    if (hoursStay <= 12) {
      return rate12h; // Charge for 12 hours
    } else if (hoursStay <= 24) {
      return rate24h; // Charge for 24 hours
    } else {
      // For stays longer than 24 hours:
      const fullDays = Math.floor(hoursStay / 24); // Count complete 24h periods
      const remainingHours = hoursStay % 24; // Get leftover hours
      
      // Calculate cost: full days at 24h rate + remaining hours at 12h rate (if any)
      let total = fullDays * rate24h;
      if (remainingHours > 0) {
        total += (remainingHours <= 12) ? rate12h : rate24h;
      }
      return total;
    }
  }

  function part4Complete(){
    return (
        
        document.getElementById('book-checkin-date').value &&
        document.getElementById('book-checkin-time').value &&

        // Check-out elements
        document.getElementById('book-checkout-date').value &&
        document.getElementById('book-checkout-time').value &&

        // Room selection elements
        document.getElementById('book-room-type').value != 'Select room type' &&
        document.getElementById('book-room-number').value &&

        // Guest details elements
        document.getElementById('book-guest-count').value &&
        document.getElementById('book-hours-stay').value
    )
  }
  // Function to update button state
  function updateBookingNextButtonState() {
    if(isBookingFormValid()){
        const value = displayAllBookValues();
        console.log("yahoo: ", value);
        getSchedule(value.roomType.id);

        specificRoom = bookData.all_types.find(type => {
            return value.roomType.id === type._id.$oid
        });

        console.log("bading: ",specificRoom.type_name);

        document.getElementById('book-room-number').value = selectedBookingRoom.room_no;
        document.getElementById('book-hours-stay').value = computeHoursStay(value.checkinDate, value.checkinTime, value.checkoutDate, value.checkoutTime)
        currentRoomType = specificRoom.type_name;
        computedBookingRate = computeBookRate(
            document.getElementById('book-hours-stay').value, 
            specificRoom.rates.checkin_12h,  // Fixed: checkin_12h (not checkin_12)
            specificRoom.rates.checkin_24h
        );
        nextButtonBook.disabled = !part4Complete();
    }
  }
  
// Add event listeners to all booking fields
const bookingFields = [
    bookingDetails.checkinDate,
    bookingDetails.checkinTime,
    bookingDetails.checkoutDate,
    bookingDetails.checkoutTime,
    bookingDetails.roomType,
    bookingDetails.guestCount
];

// Helper functions for formatting date and time
const formatDate = (date) => {
    return new Date(date).toLocaleString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    }).replace(',', '');
};

const formatTime = (time) => {
    const [hours, minutes] = time.split(':');
    const date = new Date();
    date.setHours(hours, minutes);
    return date.toLocaleString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
};
// Function to display all booking values
function displayAllBookValues() {
    return {
        checkinDate: formatDate(bookingFields[0].value),
        checkinTime: formatTime(bookingFields[1].value),
        checkoutDate: formatDate(bookingFields[2].value),
        checkoutTime: formatTime(bookingFields[3].value),
        roomType: {
            id: bookingFields[4].value,
            name: bookingFields[4].options[bookingFields[4].selectedIndex].text
        },
        guestCount: bookingFields[5].value
    };
}

  bookingFields.forEach(field => {
    field.addEventListener('change', updateBookingNextButtonState);
    // For number input, also listen to input event for immediate feedback
    if (field.type === 'number') {
      field.addEventListener('input', updateBookingNextButtonState);
    }
  });
  
  // Initialize button state on page load
  document.addEventListener('DOMContentLoaded', function() {
    updateBookingNextButtonState();
  });

  // Payment Method Variables
const paymentMethods = {
    selectedMethod: null,
    gcashBtn: document.getElementById('book-payment-gcash'),
    cashBtn: document.getElementById('book-payment-cash'),
    nextButton: nextButtonBook,

    init: function() {
        // Add event listeners
        this.gcashBtn.addEventListener('click', () => this.selectMethod('gcash'));
        this.cashBtn.addEventListener('click', () => this.selectMethod('cash'));

        // Initialize button states
        this.updateButtonStates();
    },

    selectMethod: function(method) {
        this.selectedMethod = method;
        this.updateButtonStates();
        this.enableNextButton();
    },

    updateButtonStates: function() {
        // Reset all buttons first
        const buttons = [this.gcashBtn, this.cashBtn];
        buttons.forEach(btn => {
            btn.style.borderColor = '';
            btn.style.color = '';
            btn.style.backgroundColor = '';
        });

        // Highlight selected button
        if (this.selectedMethod === 'gcash') {
            this.gcashBtn.style.borderColor = '#578FCA';
            this.gcashBtn.style.color = '#578FCA';
            this.gcashBtn.style.backgroundColor = '#F0F7FF';
        } else if (this.selectedMethod === 'cash') {
            this.cashBtn.style.borderColor = '#578FCA';
            this.cashBtn.style.color = '#578FCA';
            this.cashBtn.style.backgroundColor = '#F0F7FF';
        }
    },

    enableNextButton: function() {
        if (this.selectedMethod) {
            this.nextButton.disabled = false;
        }else{
            this.nextButton.disabled = true;
        }
    },

    getSelectedMethod: function() {
        return this.selectedMethod;
    },

    clearSelection: function() {
        this.selectedMethod = null;
        this.updateButtonStates();
    }
};

// Payment Forms Manager
const paymentForms = {
    // References to both forms
    gcashForm: document.getElementById('book-gcash-form'),
    cashForm: document.getElementById('book-cash-form'),
    
    // GCash Form Elements
    gcash: {
        accountName: document.getElementById('book-gcash-acc-name'),
        accountNumber: document.getElementById('book-gcash-acc-num'),
        
        isValid: function() {
            return this.accountName.value.trim() !== '' && 
                   this.accountNumber.value.trim().length === 11;
        },
        
        getData: function() {
            return {
                type: 'gcash',
                accountName: this.accountName.value.trim(),
                accountNumber: this.accountNumber.value.trim()
            };
        },
        
        clear: function() {
            this.accountName.value = '';
            this.accountNumber.value = '';
        }
    },
    
    // Cash Form Elements
    cash: {
        amount: document.getElementById('book-cash-amount'),
        total: document.getElementById('book-cash-total'),
        change: document.getElementById('book-cash-change'),
        
        isValid: function() {
            const amount = parseFloat(this.amount.value);
            const total = parseFloat(this.total.textContent.replace(/[^0-9.]/g, ''));
            return !isNaN(amount) && amount >= total;
        },
        
        getData: function() {
            return {
                type: 'cash',
                amount: parseFloat(this.amount.value),
                total: parseFloat(this.total.textContent.replace(/[^0-9.]/g, '')),
                change: parseFloat(this.change.textContent.replace(/[^0-9.]/g, ''))
            };
        },
        
        calculateChange: function() {
            const amount = parseFloat(this.amount.value) || 0;
            const change = amount - computedBookingRate;
            this.change.textContent = `P ${change.toFixed(2)}`;
        },
        
        clear: function() {
            this.amount.value = '';
            this.change.textContent = 'P 0.00';
        }
    },
    
    // Initialize payment forms
    init: function() {
        // Hide both forms initially
        this.gcashForm.style.display = 'none';
        this.cashForm.style.display = 'none';
        
        // Set up cash amount calculation
        this.cash.amount.addEventListener('input', () => {
            this.cash.calculateChange();
            paymentMethods.enableNextButtonIfValid();
        });
        
        // Set up GCash validation
        this.gcash.accountNumber.addEventListener('input', (e) => {
            // Limit to 11 digits
            if (e.target.value.length > 11) {
                e.target.value = e.target.value.slice(0, 11);
            }
            paymentMethods.enableNextButtonIfValid();
        });
        
        this.gcash.accountName.addEventListener('input', () => {
            paymentMethods.enableNextButtonIfValid();
        });
    },
    
    // Show the appropriate form based on payment method
    showForm: function(method) {
        if(method === 'gcash'){
            this.gcashForm.style.display = 'block';
            document.getElemetnById('book-method').textContent = 'GCash';
            this.cashForm.style.display = 'none';
        }else{
            document.getElemetnById('book-method').textContent = 'Cash';
            this.cashForm.style.display = 'block';
            this.gcashForm.style.display = 'none';
        }
        
    },
    
    // Validate current visible form
    isValid: function() {
        const method = paymentMethods.getSelectedMethod();
        if (method === 'gcash') return this.gcash.isValid();
        if (method === 'cash') return this.cash.isValid();
        return false;
    },
    
    // Get payment data
    getPaymentData: function() {
        const method = paymentMethods.getSelectedMethod();
        if (method === 'gcash') return this.gcash.getData();
        if (method === 'cash') return this.cash.getData();
        return null;
    },
    
    // Clear all forms
    clearAll: function() {
        this.gcash.clear();
        this.cash.clear();
    }
};

// Update the paymentMethods object to handle form visibility
paymentMethods.showPaymentForm = function() {
    paymentForms.showForm(this.selectedMethod);
    this.enableNextButtonIfValid();
};

paymentMethods.enableNextButtonIfValid = function() {
    this.nextButton.disabled = !paymentForms.isValid();
};
// Define the function to update bookingPaymentInfo outside the DOM event handler
function updateBookingPaymentInfo() {
    // Get payment data
    const paymentData = paymentForms.getPaymentData();
    
    if (!paymentData) {
        console.error('No payment method selected or payment data is invalid');
        return false;
    }
    
    // Update bookingPaymentInfo based on payment method
    bookingPaymentInfo.method = paymentData.type;
    
    if (paymentData.type === 'cash') {
        bookingPaymentInfo.amountPaid = paymentData.amount;
        bookingPaymentInfo.baseRate = computedBookingRate;// Should match computedBookingAmount
        bookingPaymentInfo.changeDue = paymentData.change;
        bookingPaymentInfo.gcashAccountName = '';
        bookingPaymentInfo.gcashReferenceNumber = '';
    } else if (paymentData.type === 'gcash') {
        bookingPaymentInfo.amountPaid = computedBookingRate; // Assuming full payment for GCash
        bookingPaymentInfo.baseRate = computedBookingRate;
        bookingPaymentInfo.changeDue = 0;
        bookingPaymentInfo.gcashAccountName = paymentData.accountName;
        bookingPaymentInfo.gcashReferenceNumber = paymentData.accountNumber; // Using account number as reference
    }
    
    return true;
}
// Initialize payment methods on DOM load
document.addEventListener('DOMContentLoaded', function() {
    paymentMethods.init();
    paymentForms.init();


    function checkAllBookingFieldsFilled(){
        return checkinDateInput.value && 
            checkinTimeInput.value && 
            checkoutDateInput.value && 
            checkoutTimeInput.value && 
            typeSelect.value && 
            typeSelect.value !== '' // Ensure a room type is actually selected
    }
});





