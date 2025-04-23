let scanner;
let modal_part = 1;
const transactRate = document.getElementById('transactRateValue');  // "P 1,499.00"
const payMethod = document.getElementById('payment-method');

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

    console.log(current);
    console.log(next);
    console.log(prevButton);
    console.log(cancelButton);
    console.log(modalTitle);
    console.log(modalSubMsg);

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
            
            console.log('Current Guest: ', currentGuest);
            outputFname.textContent = currentGuest.firstName;
            outputLname.textContent = currentGuest.lastName;
            outputGender.textContent = currentGuest.gender;
            outputMobileNum.textContent = currentGuest.mobileNumber;
            outputEmail.textContent = currentGuest.email;
            outputAddress.textContent = currentGuest.address;

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
            modal_part = paymentMethod == "cashPayment" ? 11 : 10;
            break;;
        case 10:
            switchContent(
                "#checkInModal1 .input-content-10",   // Current content
                "#checkInModal1 .input-content-12",   // Next content
                "confirm-button",        // Previous button
                "next-button",       // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",           // Modal subtitle
                "",           // New title text
                "" // New subtitle text
            );
            modal_part = 12;
            break;
        case 11:
            switchContent(
                "#checkInModal1 .input-content-11",   // Current content
                "#checkInModal1 .input-content-12",   // Next content
                "confirm-button",        // Previous button
                "next-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",           // Modal subtitle
                "",           // New title text
                "" // New subtitle text
            );
            modal_part = 12;
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
    
    console.log(paymentMethod);
}