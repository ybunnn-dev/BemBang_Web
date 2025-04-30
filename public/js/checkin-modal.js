let scanner;
let modal_part = 1;
const transactRate = document.getElementById('transactRateValue');  // "P 1,499.00"
const payMethod = document.getElementById('payment-method');
let isExist = false;
const userFirstName = document.getElementById('first-name');
const userLastName = document.getElementById('last-name');
const userSex = document.getElementById('sex');
const userEmail = document.getElementById('personal-email');
const userPhone = document.getElementById('mobile-number');
const userAddress = document.getElementById('address');
const userLastCheckin = document.getElementById('last-check-in');
let currentGuest = null;
let selectedRoom;
let computedAmount;
let roomInput= null;
// Global payment info object (example structure)
// Global payment info object
const paymentInfo = {
    payMethod: null,       // 'cash' or 'gcash'
    amount: 0,             // Amount paid
    rate: computedAmount,   // The base rate/price
    change: 0,             // Change (always 0 for GCash)
    accountName: '',       // GCash account name
    referenceNumber: ''    // GCash reference number
};


let paymentMethod;
let currentRate;

// Personal Information
const outputFname = document.getElementById('output-fname');       // "Leonard"
const outputLname = document.getElementById('output-lname');       // "Manok"
const outputGender = document.getElementById('output-gender');     // "M"
const outputMobileNum = document.getElementById('output-mobileNum'); // "091234567"
const outputEmail = document.getElementById('output-email');        // "manok@gmail.com"
const outputAddress = document.getElementById('output-address');    // "Sagpon, Daraga, Albay"

// Check-in/Check-out Details
const outputCheckinDate = document.getElementById('output-checkin-date');    // "March 17, 2025"
const outputCheckoutDate = document.getElementById('output-checkout-date');  // "March 17, 2025"
const outputCheckoutTime = document.getElementById('output-checkout-time');  // "8:00 PM"
const outputCheckinTime = document.getElementById('output-checkin-time');

// Room & Booking Details
const outputRoomType = document.getElementById('output-room-type');  // "Bembang Standard"
const outputRoomNo = document.getElementById('output-room-no');      // "ROOM #12"
const outputGuestNum = document.getElementById('output-guest-num');  // "2"
const outputRate = document.getElementById('output-rate');           // "P 1,499.00"

//room inputs
const typeSelect = document.getElementById('room-type');
const checkinDateInput = document.getElementById('checkin-date-input');
const checkinTimeInput = document.getElementById('checkin-time-input');
const checkoutDateInput = document.getElementById('checkout-date-input');
const checkoutTimeInput = document.getElementById('checkout-time-input');
const roomNoInput = document.getElementById('room-no');
const guestNumInput = document.getElementById('guest-num-input');
const hoursStayInput = document.getElementById('hours-stay-input');




document.addEventListener("DOMContentLoaded", function () {


    let scanner = null; // Declare scanner variable
    let allGuests = []; // Global variable to store guests
    let checkinData = [];
 
    // Global variable to store the current guest data
    
    // Get references to the input fields
    const fnameInput = document.getElementById('fname-input');
    const lnameInput = document.getElementById('lname-input');

    // Function to check if all fields have values
    function checkAllFieldsFilled() {
        return checkinDateInput.value && 
            checkinTimeInput.value && 
            checkoutDateInput.value && 
            checkoutTimeInput.value && 
            typeSelect.value && 
            typeSelect.value !== '' // Ensure a room type is actually selected
    }

    function mergeDateTime(dateInput, timeInput) {
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

    // Function to display all values
    function displayAllValues() {
        const values = {
            checkinDate: checkinDateInput.value,
            checkinTime: checkinTimeInput.value,
            checkoutDate: checkoutDateInput.value,
            checkoutTime: checkoutTimeInput.value,
            roomType: {
                id: typeSelect.value,
                name: typeSelect.options[typeSelect.selectedIndex].text
            }
        };
        return values;
    }

    function getSchedule(id) {
        // Log the input for debugging
        console.log("Fetching schedule for room type:", id);
    
        // Validate checkinData structure
        if (!checkinData || !checkinData.rooms || !Array.isArray(checkinData.rooms)) {
            console.error('Invalid checkinData structure');
            return null;
        }
    
        // Get check-in and check-out datetimes
        const checkinDateTime = mergeDateTime(checkinDateInput, checkinTimeInput);
        const checkoutDateTime = mergeDateTime(checkoutDateInput, checkoutTimeInput);
    
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
        const filteredRoomsByTypeId = checkinData.rooms.filter(room => {
            // Handle room_type as string or ObjectId
            return room.room_type == id || (room.room_type && room.room_type['$oid'] == id);
        });
    
        // Filter rooms with no conflicting schedules
        const filteredRooms = filteredRoomsByTypeId.filter(room => {
            // Find schedules for this room
            const roomSchedules = checkinData.schedules.filter(schedule => 
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
        const selectedRoom = filteredRooms.length > 0 ? filteredRooms[0] : null;
        console.log('Selected room:', selectedRoom);
        return selectedRoom;
    }
    // Add event listeners to all inputs

    /**  Compute Muna ang hours stay  ** */
    function computeHoursStay(checkinDate, checkinTime, checkoutDate, checkoutTime) {
        const checkin = new Date(`${checkinDate}T${checkinTime}`);
        const checkout = new Date(`${checkoutDate}T${checkoutTime}`);
        const hoursStay = (checkout - checkin) / (1000 * 60 * 60); // Total hours
      
        if (hoursStay <= 12) return 12; // Minimum 12h charge
        else if (hoursStay <= 24) return 24; // 1-day flat rate
        else return Math.ceil(hoursStay / 12) * 12; // Beyond 24h → round up to nearest 12h
      }
      
      /**  Then compute mo na si bayad  ** */
      function computeRate(hoursStay, rate12h, rate24h) {
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

    [checkinDateInput, checkinTimeInput, checkoutDateInput, checkoutTimeInput, typeSelect, roomNoInput, guestNumInput].forEach(input => {
        input.addEventListener('change', () => {
            if (checkAllFieldsFilled()) {
                const value = displayAllValues();
                selectedRoom = getSchedule(value.roomType.id);
                
                let specificRoom = checkinData.all_types.find(type => {
                    return value.roomType.id === type._id.$oid
                });

                console.log("specificRoom: ", specificRoom);
                guestNumInput.max = specificRoom.guest_num;
                roomNoInput.value = selectedRoom.room_no;
                hoursStayInput.value = computeHoursStay(checkinDateInput.value, checkinTimeInput.value, checkoutDateInput.value, checkoutTimeInput.value);
                // Compute amount using correct rate keys
                computedAmount = computeRate(
                    hoursStayInput.value,
                    specificRoom.rates.checkin_12h,
                    specificRoom.rates.checkin_24h
                );
            }
        });
    });

    // Add event listener for change events
    typeSelect.addEventListener('change', function() {
        // Get the selected option
        const selectedOption = this.options[this.selectedIndex];
        const selectedValue = selectedOption.value;
        const selectedText = selectedOption.text;
        
        // Check if a real option was selected (not the default placeholder)
        if (selectedValue && selectedText !== 'Open this select menu') {
            // Log both the ID and room name
            console.log('Room Type Selected:', {
                id: selectedValue,
                name: selectedText
            });
            
            // Or as a formatted string:
            console.log(`Selected Room - ID: ${selectedValue}, Name: ${selectedText}`);

        } else {
            console.log('Default option selected - no room chosen');
        }
    });

    document.getElementById('check-in').addEventListener('click', async function() {
        try {
            // Fetch data
            const [guestsResponse, checkinResponse] = await Promise.all([
                fetch('/get-guests'),
                fetch('/get-checkin-data')
            ]);
            
            if (!guestsResponse.ok || !checkinResponse.ok) throw new Error('Failed to fetch data');
            
            
            allGuests = await guestsResponse.json(),
            checkinData = await checkinResponse.json();
            
            console.log(checkinData);
            // Populate room types
            const roomTypeSelect = document.getElementById('room-type');
            
            // Clear existing options (keep first default option)
            while (roomTypeSelect.options.length > 1) {
                roomTypeSelect.remove(1);
            }
            
            // Add new options from API data
            checkinData.all_types.forEach(room => {
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


    // Function to find guest by name
    function findGuestByName(firstName, lastName) {
        return allGuests.find(guest => 
            guest.firstName.toLowerCase() === firstName.toLowerCase() && 
            guest.lastName.toLowerCase() === lastName.toLowerCase()
        );
    }

    // Event listener for both fields
    function handleNameInput() {
        const firstName = fnameInput.value.trim();
        const lastName = lnameInput.value.trim();
        
        if (firstName && lastName) {
            currentGuest = findGuestByName(firstName, lastName);
            
            if (currentGuest) {
                // Name exists - show the guest data and highlight fields
                console.log('Current guest data:', currentGuest);
                // Example of accessing guest data
                modal_part = 20;
                modal_switch_next(); 
                // You can now use currentGuest throughout your application
            } else {
                // Name doesn't exist - reset any warnings and clear variable
                isExist = true;
                fnameInput.style.borderColor = '';
                lnameInput.style.borderColor = '';
                currentGuest = null;
            }
        } else {
            currentGuest = null;
        }
    }

    // Add event listeners
    fnameInput.addEventListener('input', handleNameInput);
    lnameInput.addEventListener('input', handleNameInput);

    async function fetchGuests() {
        try {
            const response = await fetch('/get-guests');
            const guests = await response.json();
            
            // Update your UI with the guest data
            displayGuests(guests);
        } catch (error) {
            console.error('Failed to fetch guests:', error);
        }
    }

    // Function to start scanning
    function startScanner() {
        // Choose the correct QR reader based on modal_part
        const qrReaderDiv =  document.querySelector("#checkInModal1 #qr-reader");
        
        if (!scanner) {
            scanner = new Html5Qrcode(qrReaderDiv.id); // Use dynamic element ID

            // Request camera and start scanning
            scanner.start(
                { facingMode: "environment" }, // "environment" for rear cam, "user" for front cam
                { fps: 20, qrbox: 250 },
                (decodedText) => {
                    scanner.stop();
                },
                (errorMessage) => {
                    console.warn("QR Scan Error:", errorMessage);
                }
            );
        }
    }

    // Function to stop scanning
    function stopScanner() {
        if (scanner) {
            scanner.stop().then(() => {
                scanner.clear();
                scanner = null;
            });
        }
    }

    // Monitor modal part dynamically
    let modalPartInterval = setInterval(() => {
        const modal = document.querySelector("#checkInModal1");
        if (modal && modal.classList.contains("show")) {
            // Check if modal_part is 2 or 8
            if ((modal_part === 2 || modal_part === 8) && !scanner) {
                startScanner(); // Start scanner only if modal_part is 2 or 8
            } else if ((modal_part !== 2 && modal_part !== 8) && scanner) {
                stopScanner(); // Stop scanner if modal_part is not 2 or 8
            }
        }
    }, 1000); // Check every second

    // Clean up the interval when the modal is closed
    document.querySelector("#checkInModal1").addEventListener("hidden.bs.modal", function () {
        clearInterval(modalPartInterval); // Stop checking modal part
        stopScanner(); // Stop the scanner when the modal is closed
    });

    // Ensure that qr-reader2 adjusts layout properly when modal is shown
    document.querySelector("#checkInModal1").addEventListener("shown.bs.modal", function () {
        setTimeout(() => {
            const qrReaderDiv =  document.querySelector("#checkInModal1 #qr-reader");
            qrReaderDiv.style.width = '100%'; // Ensure the scanner div takes full width
            qrReaderDiv.style.height = '100%'; // Adjust height accordingly
        }, 300); // Wait a bit to ensure modal is fully shown before recalculating layout
    });
});

        
var current, next, prevButton, cancelButton;

function switchContent(currentSelector, nextSelector, prevButtonSelector, cancelButtonSelector, modalTitleSelector, modalSubMsgSelector, newTitle, newSubMsg) {
    // Adjusted to select elements within #checkInModal1
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

    /**
     * Populates the invoice modal with transaction data
     * @param {Object} transaction - The transaction data object
     */
    function populateInvoiceModal(transaction, transactId) {
        // Format currency
        const formatCurrency = (amount) => {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            minimumFractionDigits: 2
        }).format(amount);
        };
        
        // Format date strings to more readable format
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
        
        // Generate random invoice number (in production, use a real invoice number)
        const invoiceNumber = transactId;
        
        // Set invoice details
        document.getElementById('invoice-number').textContent = invoiceNumber;
        document.getElementById('invoice-date').textContent = formatDate(transaction.created_at);
        
        // Set guest information
        const guestName = transaction.new_guest 
        ? `${transaction.new_guest.fname} ${transaction.new_guest.lname}`
        : 'Guest';
        document.getElementById('guest-name').textContent = guestName;
        document.getElementById('guest-email').textContent = transaction.new_guest?.email || 'N/A';
        document.getElementById('guest-contact').textContent = transaction.new_guest?.mobileNum || 'N/A';
        document.getElementById('guest-address').textContent = transaction.new_guest?.address || 'N/A';
        
        // Set stay details
        document.getElementById('checkin-date').textContent = formatDate(transaction.stay_details?.actual_checkin);
        document.getElementById('checkout-date').textContent = formatDate(transaction.stay_details?.expected_checkout);
        document.getElementById('stay-duration').textContent = transaction.stay_details?.stay_hours + ' hours';
        document.getElementById('guest-count').textContent = transaction.stay_details?.guest_num + ' person(s)';
        document.getElementById('room-id').textContent = transaction.room_id || 'N/A';
        document.getElementById('stay-status').textContent = transaction.current_status || 'N/A';
        
        // Set payment details
        const originalRate = transaction.meta?.original_rate || 0;
        document.getElementById('room-charge').textContent = formatCurrency(originalRate);
        document.getElementById('subtotal').textContent = formatCurrency(originalRate);
        
        // Handle payments
        if (transaction.payments && transaction.payments.length > 0) {
        const payment = transaction.payments[0];
        document.getElementById('payment-method-invoice').textContent = payment.method ? payment.method.charAt(0).toUpperCase() + payment.method.slice(1) : 'N/A';
        document.getElementById('amount-paid').textContent = formatCurrency(payment.amount || 0);
        } else {
        document.getElementById('payment-method-invoice').textContent = 'N/A';
        document.getElementById('amount-paid').textContent = formatCurrency(0);
        }
        
        // Set change and balance
        const changeGiven = transaction.meta?.change_given || 0;
        document.getElementById('change-given').textContent = formatCurrency(changeGiven);
        document.getElementById('balance').textContent = formatCurrency(0); // Assuming paid in full
    }
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
function prepareTransactionData() {
    // Convert string amounts to numbers safely
    const checkin = new Date(`${roomInput.checkinDate}T${roomInput.checkinTime}:00`);
    const checkout = new Date(`${roomInput.checkoutDate}T${roomInput.checkoutTime}:00`);

    const amountPaid = parseFloat(paymentInfo.amount) || 0;
    const roomRate = parseFloat(paymentInfo.rate) || 0;
    guestData ={
        fname: currentGuest.firstName,
        lname: currentGuest.lastName,
        gender: currentGuest.gender,
        email: currentGuest.email,
        mobileNum: currentGuest.mobileNumber,
        address: currentGuest.mobileNumber,
        level: 1
    }
    // Base transaction structure
    const transactionData = {
        is_guest: isExist,
        guest_id: currentGuest._id,
        employee_id: userId,
        room_id: selectedRoom.id,
        transaction_type: "Check-In",
        payments: [{
            method: paymentInfo.payMethod || 'cash',
            details: {},
            amount: amountPaid,
            currency: "PHP",
            status: "completed",
            processed_at: new Date().toISOString()
        }],
        stay_details: {
            expected_checkin: checkin.toISOString(),
            expected_checkout: checkout.toISOString(),
            actual_checkin: checkin.toISOString(),
            actual_checkout: null,
            guest_num: parseInt(currentGuest.guestNumber) || 1,
            stay_hours: parseInt(roomInput.hoursStay) || 24,
            time_allowance: 4
        },
        current_status: "checked-in",
        audit_log: [{
            action: "checked-in",
            by: userId,
            timestamp: new Date().toISOString(),
            points_earned: 0
        }],
        meta: {
            original_rate: roomRate,
            change_given: parseFloat(paymentInfo.change) || 0
        },
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
        new_guest: guestData
    };

    // Add payment method specific details
    if (paymentInfo.payMethod.toLowerCase() === 'gcash') {
        transactionData.payments[0].details = {
            reference_no: paymentInfo.referenceNumber || ''
        };
        // Add account name if needed
        if (paymentInfo.accountName) {
            transactionData.payments[0].details.account_name = paymentInfo.accountName;
        }
    }

    console.log("Prepared Transaction Data:", transactionData);
    //return transactionData;
}

async function completeTransaction() {
    // 1. Prepare the transaction data
    const data = prepareTransactionData();
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
        
        document.getElementById('prev-button').style.display = "none";
        
        // Show input-content-12
        const showElements = document.querySelectorAll('.input-content-12');
        showElements.forEach(element => {
            element.style.display = 'block';
        });
        
        // Handle modals
        const currentModal = bootstrap.Modal.getInstance(document.getElementById('confirm-transact'));
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
function user_qr() {
    switchContent(
        "#checkInModal1 .input-content-1",   // Current content within the modal
        "#checkInModal1 .input-content-2",   // Next content within the modal
        "prev-button",                       // Previous button
        "cancel-button",                     // Cancel button
        "#checkInModal1 #checkInModal1Label", // Modal title
        "#checkInModal1 #sub-msg",           // Modal subtitle
        "Scan QR Code",                      // New title text
        ""                                   // New subtitle text
    );
    modal_part = 2;
}

function openVoucherScanner() {
    switchContent(
        "#checkInModal1 .input-content-7",   // Current content within the modal
        "#checkInModal1 .input-content-8",   // Next content within the modal
        "prev-button",                       // Previous button
        "cancel-button",                     // Cancel button
        "#checkInModal1 #checkInModal1Label", // Modal title
        "#checkInModal1 #sub-msg",           // Modal subtitle
        "Scan Voucher",                      // New title text
        ""                                   // New subtitle text
    );
    modal_part = 8;
}

function modal_switch_next() {
    // Get the current date and time
    const now = new Date();

    // Format the date as YYYY-MM-DD (required for date inputs)
    const currentDate = now.toISOString().split('T')[0];

    // Format the time as HH:MM (24-hour format)
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const currentTime = `${hours}:${minutes}`;
    
    switch (modal_part) {
        case 1:
            document.getElementById('checkin-date-input').value = currentDate;
            document.getElementById('checkin-time-input').value = currentTime;

            currentGuest = {
                firstName: document.getElementById('fname-input').value,
                lastName: document.getElementById('lname-input').value,
                email: document.getElementById('email-input').value,
                mobileNumber: document.getElementById('mnum-input').value,
                address: document.getElementById('formControlInput5').value,
                gender: document.getElementById('gender-select').value
              };
            
            isExist = false;

            // Update check-in/check-out and room details
            switchContent(
                "#checkInModal1 .input-content-1",   // Current content
                "#checkInModal1 .input-content-4",   // Next content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",           // Modal subtitle
                "Check In",           // New title text
                "Enter check in details." // New subtitle text
            );
            
            document.getElementById('next-button').disabled = true;
            modal_part = 4;
            break;
        case 2:
            switchContent(
                "#checkInModal1 .input-content-2",   // Current content
                "#checkInModal1 .input-content-1",   // Next content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",           // Modal subtitle
                "Check In",           // New title text
                "Enter check in details." // New subtitle text
            );
            modal_part = 1;
            break;
        case 20:
                // Switch modal content
                switchContent(
                    "#checkInModal1 .input-content-1",   // Current content
                    "#checkInModal1 .input-content-3",   // Next content
                    "prev-button",        // Previous button
                    "cancel-button",      // Cancel button
                    "#checkInModal1 #checkInModal1Label", // Modal title
                    "#checkInModal1 #sub-msg",           // Modal subtitle
                    "Existing Guest",           // New title text
                    "Existing Guest Detected." // New subtitle text
                );
            
                // Update guest details in the UI using currentGuest
                if (currentGuest) {
                    if (userFirstName) userFirstName.textContent = currentGuest.firstName || 'Not provided';
                    if (userLastName) userLastName.textContent = currentGuest.lastName || 'Not provided';
                    if (userSex) userSex.textContent = currentGuest.gender || 'Not provided';
                    if (userEmail) userEmail.textContent = currentGuest.email || 'Not provided';
                    if (userPhone) userPhone.textContent = currentGuest.mobileNumber || 'Not provided';
                    if (userAddress) userAddress.textContent = currentGuest.address || 'Not provided';
                    if (userLastCheckin) userLastCheckin.textContent = currentGuest.last_checkin || 'Unknown';
                } else {
                    console.error('currentGuest is null or undefined');
                    // Optionally, update UI to show an error or fallback
                    if (userFirstName) userFirstName.textContent = 'Error: No guest data';
                    if (userLastName) userLastName.textContent = 'Error: No guest data';
                    if (userSex) userSex.textContent = 'Error: No guest data';
                    if (userEmail) userEmail.textContent = 'Error: No guest data';
                    if (userPhone) userPhone.textContent = 'Error: No guest data';
                    if (userAddress) userAddress.textContent = 'Error: No guest data';
                    if (userLastCheckin) userLastCheckin.textContent = 'Error: No guest data';
                }
                document.getElementById('next-button').disabled = false;
                modal_part = 3;
                break;
            case 3:
                    // Set the values in the inputs
                    document.getElementById('checkin-date-input').value = currentDate;
                    document.getElementById('checkin-time-input').value = currentTime;

                    outputFname.textContent = currentGuest.firstName;
                    outputLname.textContent = currentGuest.lastName;
                    outputGender.textContent = currentGuest.gender;
                    outputMobileNum.textContent = currentGuest.mobileNumber;
                    outputEmail.textContent = currentGuest.email;
                    outputAddress.textContent = currentGuest.address;

                    switchContent(
                        "#checkInModal1 .input-content-3",   // Current content
                        "#checkInModal1 .input-content-4",   // Next content
                        "prev-button",        // Previous button
                        "cancel-button",      // Cancel button
                        "#checkInModal1 #checkInModal1Label", // Modal title
                        "#checkInModal1 #sub-msg",           // Modal subtitle
                        "Check In",           // New title text
                        "Enter check in details." // New subtitle text
                    );
                    document.getElementById('next-button').disabled = true;
                    modal_part = 4;
                    break;
        case 4:
            switchContent(
                "#checkInModal1 .input-content-4",   // Current content
                "#checkInModal1 .input-content-5",   // Next content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",           // Modal subtitle
                "Confirm Details",           // New title text
                "" // New subtitle text
            );
            outputCheckinDate.textContent = formatDate(checkinDateInput.value);
            outputCheckoutDate.textContent = formatDate(checkoutDateInput.value);
            outputCheckinTime.textContent = formatTime(checkinTimeInput.value);
            outputCheckoutTime.textContent = formatTime(checkoutTimeInput.value);
            outputRoomType.textContent = typeSelect.options[typeSelect.selectedIndex].text;
            outputRoomNo.textContent = `ROOM #${roomNoInput.value}`;
            outputGuestNum.textContent = guestNumInput.value;
            outputRate.textContent = `P ${computedAmount.toLocaleString('en-PH')}.00`;

            roomInput = {
                checkinDate: checkinDateInput.value,
                checkinTime: checkinTimeInput.value,
                checkoutDate: checkoutDateInput.value,
                checkoutTime: checkoutTimeInput.value,
                roomType: typeSelect.options[typeSelect.selectedIndex].text,
                roomNumber: roomNoInput.value,
                guestNumber: guestNumInput.value,
                rate: `P ${computedAmount.toLocaleString('en-PH')}.00`,
                formattedCheckinDate: formatDate(checkinDateInput.value),
                formattedCheckoutDate: formatDate(checkoutDateInput.value),
                formattedCheckinTime: formatTime(checkinTimeInput.value),
                formattedCheckoutTime: formatTime(checkoutTimeInput.value)
              };
            function formatTime(timeString) {
                // Split into hours and minutes
                const [hours, minutes] = timeString.split(':');
                
                // Convert to number
                const hourNum = parseInt(hours);
                
                // Determine AM/PM
                const period = hourNum >= 12 ? 'PM' : 'AM';
                
                // Convert to 12-hour format
                const twelveHour = hourNum % 12 || 12; // Converts 0 to 12
                
                // Return formatted string (e.g., "8:00 AM")
                return `${twelveHour}:${minutes.padStart(2, '0')} ${period}`;
            }
            // Helper function to format date (e.g., "2025-03-17" → "March 17, 2025")
            function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
            }
            modal_part = 5;
            break;
        case 5:
            switchContent(
                "#checkInModal1 .input-content-5",   // Current content
                "#checkInModal1 .input-content-6",   // Next content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",           // Modal subtitle
                "Payment Method",           // New title text
                "" // New subtitle text
            );
            document.getElementById('next-button').disabled = true;
            modal_part = 6;
            break;
        case 6:
            switchContent(
                "#checkInModal1 .input-content-6",   // Current content
                "#checkInModal1 .input-content-7",   // Next content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",           // Modal subtitle
                "Confirm Payment Details",           // New title text
                "" // New subtitle text
            );
            transactRate.textContent = `P ${computedAmount.toLocaleString('en-PH')}.00`;
            payMethod.textContent = paymentMethod === 'cashPayment' ? 'Cash Payment' : 'GCash';
            modal_part = 7;
            break;
        case 7:
            let payNext = paymentMethod == "cashPayment" ? "#checkInModal1 .input-content-11" : "#checkInModal1 .input-content-10";
            switchContent(
                "#checkInModal1 .input-content-7",   // Current content
                payNext,   // Next content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",           // Modal subtitle
                "",         // New title text
                "" // New subtitle text
            );
            document.getElementById("amount-value").textContent = 
                'P ' + computedAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            modal_part = paymentMethod == "cashPayment" ? 11 : 10;
            document.getElementById('next-button').disabled = true;
            break;
            case 10:
            case 11:
                // Close current modal
                const checkInModal = bootstrap.Modal.getInstance(document.getElementById('checkInModal1'));
                checkInModal.hide();
                
                const confirmModal = new bootstrap.Modal(document.getElementById('confirm-transact'));
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

function modal_switch_prev() {
    switch (modal_part) {
        case 2:
            switchToPreviousContent(
                "#checkInModal1 .input-content-2",   // Current content
                "#checkInModal1 .input-content-1",   // Previous content
                "prev-button",        // Previous button (should be hidden)
                "cancel-button",      // Cancel button (should be shown)
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",            // Modal subtitle
                "Check In",           // New title text
                "Enter guest details." // New subtitle text
            );         
            setTimeout(() => {   
                let confirmButton = document.querySelector('#checkInModal1 #prev-button');
                let nextButton = document.querySelector('#checkInModal1 #cancel-button');
            
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

            modal_part = 1;
            break;
        case 3:
            switchToPreviousContent(
                "#checkInModal1 .input-content-3",   // Current content
                "#checkInModal1 .input-content-1",   // Previous content
                "prev-button",        // Previous button (should be hidden)
                "cancel-button",      // Cancel button (should be shown)
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",            // Modal subtitle
                "Check In",           // New title text
                "Enter guest details." // New subtitle text
            );    
            setTimeout(() => {   
                let confirmButton = document.querySelector('#checkInModal1 #prev-button');
                let nextButton = document.querySelector('#checkInModal1 #cancel-button');
            
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
            modal_part = 1;
            break;
        case 4:
            switchToPreviousContent(
                "#checkInModal1 .input-content-4",   // Current content
                "#checkInModal1 .input-content-1",   // Previous content
                "prev-button",        // Previous button (should be hidden)
                "cancel-button",      // Cancel button (should be shown)
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",            // Modal subtitle
                "Check In",           // New title text
                "Enter guest details." // New subtitle text
            );    
            setTimeout(() => {   
                let confirmButton = document.querySelector('#checkInModal1 #prev-button');
                let nextButton = document.querySelector('#checkInModal1 #cancel-button');
            
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
            modal_part = 1;
            break;
        case 5:
            switchToPreviousContent(
                "#checkInModal1 .input-content-5",   // Current content
                "#checkInModal1 .input-content-4",   // Previous content
                "cancel-button",
                "prev-button",        // Previous button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",            // Modal subtitle
                "Check In",           // New title text
                "Enter check-in details." // New subtitle text
            );
            modal_part = 4;
            break;

        case 6:
            switchToPreviousContent(
                "#checkInModal1 .input-content-6",   // Current content
                "#checkInModal1 .input-content-5",   // Previous content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",            // Modal subtitle
                "Confirm Details",    // New title text
                "" // New subtitle text
            );
            modal_part = 5;
            break;

        case 7:
            switchToPreviousContent(
                "#checkInModal1 .input-content-7",   // Current content
                "#checkInModal1 .input-content-6",   // Previous content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            modal_part = 6;
            break;
         case 10:
            switchToPreviousContent(
                "#checkInModal1 .input-content-10",   // Current content
                "#checkInModal1 .input-content-7",   // Previous content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            modal_part = 7;
            break;
         case 11:
            console.log("putanginamo",document.querySelector("#checkInModal1 .input-content-11"));
            switchToPreviousContent(
                "#checkInModal1 .input-content-11",   // Current content
                "#checkInModal1 .input-content-7",   // Previous content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            modal_part = 7;
            break;
         case 12:
            switchToPreviousContent(
                "#checkInModal1 .input-content-12",   // Current content
                "#checkInModal1 .input-content-10",   // Previous content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            setTimeout(() => {   
                let confirmButton = document.querySelector('#checkInModal1 #confirm-button');
                let nextButton = document.querySelector('#checkInModal1 #next-button');
            
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
            modal_part = 10;
            break;
    }
}
function updatePaymentMethod(method){
    if(method == 'gcash'){
        document.getElementById(method).style.border = "1px solid #578FCA";
        document.getElementById(method).style.color = "#578FCA";
        document.getElementById('cashPayment').style.border = "1px solid #566A7F";
        document.getElementById('cashPayment').style.color = "#566A7F";
        paymentMethod = method;
    }else{
        document.getElementById(method).style.border = "1px solid #578FCA";
        document.getElementById(method).style.color = "#578FCA";
        document.getElementById('gcash').style.border = "1px solid #566A7F";
        document.getElementById('gcash').style.color = "#566A7F";
        paymentMethod = method;
    }
    
    document.getElementById('next-button').disabled = false;
    console.log(paymentMethod);
}

document.addEventListener('DOMContentLoaded', function() {
    // Get all required input elements
    const inputs = [
        document.getElementById('fname-input'),
        document.getElementById('lname-input'),
        document.getElementById('email-input'),
        document.getElementById('mnum-input'),
        document.getElementById('formControlInput5'),
        document.getElementById('gender-select')
    ];
    
    // Get the next button (assuming it exists with id="next-button")
    const nextButton = document.getElementById('next-button');
    
    // Function to check if all inputs are filled
    function checkInputs() {
        if(modal_part !== 3){
            const allFilled = inputs.every(input => {
                if (input.type === 'select-one') {
                    return input.value !== 'Select Gender'; // Special check for select
                }
                return input.value.trim() !== '';
            });
            
            // Enable/disable the next button
            if (nextButton) {
                nextButton.disabled = !allFilled;
            }
        }
    }
    
    // Add event listeners to all inputs
    inputs.forEach(input => {
        input.addEventListener('input', checkInputs);
        input.addEventListener('change', checkInputs);
    });
    
    // Initial check in case some fields are pre-filled
    checkInputs();
});


document.addEventListener('DOMContentLoaded', function() {
    // Required fields (excluding disabled inputs)
    const requiredInputs = [
        document.getElementById('checkout-date-input'),  // Checkout Date
        document.getElementById('checkout-time-input'),  // Checkout Time
        document.getElementById('room-type'),           // Room Type (select)
        document.getElementById('guest-num-input')      // Number of Guests
    ];

    // Get the next button (must exist in HTML with id="next-button")
    const nextButton = document.getElementById('next-button');

    // Function to check if all inputs are filled
    function checkAllInputsFilled() {
        const isFormValid = requiredInputs.every(input => {
            if (input.tagName === 'SELECT') {
                return input.value !== 'Open this select menu'; // Ensure room type is selected
            }
            return input.value.trim() !== ''; // Ensure other inputs are not empty
        });

        // Enable/disable the next button
        if (nextButton) {
            nextButton.disabled = !isFormValid;
        }
    }

    // Add event listeners to all inputs
    requiredInputs.forEach(input => {
        input.addEventListener('input', checkAllInputsFilled);  // For text/number inputs
        input.addEventListener('change', checkAllInputsFilled); // For select dropdown
    });

    // Initial check (in case of pre-filled values)
    checkAllInputsFilled();
});

document.addEventListener('DOMContentLoaded', function() {
    const gcashInputs = [
        document.getElementById('acc-name-gcash'),  // Account Name
        document.getElementById('acc-num-gcash')   // Reference Number
    ];
    const nextButton = document.getElementById('next-button');

    function checkGcashInputs() {
        const isGcashValid = gcashInputs.every(input => {
            return input.value.trim() !== '';
        });

        // Update paymentInfo for GCash
        if (isGcashValid) {
            paymentInfo.payMethod = 'gcash';
            paymentInfo.amount = computedAmount;  // Full amount for digital payments
            paymentInfo.change = 0;               // No change for GCash
            paymentInfo.accountName = document.getElementById('acc-name-gcash').value.trim();
            paymentInfo.referenceNumber = document.getElementById('acc-num-gcash').value.trim();
        }

        // Enable/disable next button
        if (nextButton) {
            nextButton.disabled = !isGcashValid;
        }

        console.log('GCash Payment Info:', paymentInfo);
    }

    // Add event listeners
    gcashInputs.forEach(input => {
        input.addEventListener('input', checkGcashInputs);
    });

    // Initial check
    checkGcashInputs();
});

document.addEventListener('DOMContentLoaded', function() {
    const cashAmountInput = document.getElementById('cash-amount');
    const nextButton = document.getElementById('next-button');
    
    // Function to validate cash payment
    function validateCashPayment() {
        const enteredAmount = parseFloat(cashAmountInput.value);
        const isValid = !isNaN(enteredAmount) && enteredAmount >= computedAmount;
        
        // Update paymentInfo
        paymentInfo.payMethod = 'cash';
        paymentInfo.amount = enteredAmount;
        paymentInfo.rate = computedAmount;
        
        // Visual feedback
        cashAmountInput.classList.toggle('is-valid', isValid);
        cashAmountInput.classList.toggle('is-invalid', !isValid && cashAmountInput.value.trim() !== '');
        
        // Enable/disable next button
        if (nextButton) {
            nextButton.disabled = !isValid;
        }
        
        // Update change calculation
        if (isValid) {
            paymentInfo.change = enteredAmount - computedAmount;
            document.querySelector('.amount-values p:last-child').textContent = 
                'P ' + paymentInfo.change.toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        } else {
            paymentInfo.change = 0;
            document.querySelector('.amount-values p:last-child').textContent = 'P 0.00';
        }
        
        console.log('Payment Info:', paymentInfo);
    }
    
    cashAmountInput.addEventListener('input', validateCashPayment);
});



