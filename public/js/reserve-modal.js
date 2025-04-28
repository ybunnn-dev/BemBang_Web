let scanner_reserve;
let modal_part_reserve = 1;

// Input Content 1 - Guest Details
const reserveFnameInput = document.getElementById("reserve-fname-input");
const reserveLnameInput = document.getElementById("reserve-lname-input");
const reserveEmailInput = document.getElementById("reserve-email-input");
const reserveMnumInput = document.getElementById("reserve-mnum-input");
const reserveAddressInput = document.getElementById("reserve-address-input");
const reserveGenderSelect = document.getElementById("reserve-gender-select");
const reserveQrButton = document.getElementById("qr-button-reserve");

// Input Content 2 - QR Scanner
const reserveQrReader = document.getElementById("qr-reader-reserve");

// Input Content 3 - Existing User Details
const reserveExistingFname = document.getElementById("reserve-existing-fname");
const reserveExistingLname = document.getElementById("reserve-existing-lname");
const reserveExistingGender = document.getElementById("reserve-existing-gender");
const reserveExistingEmail = document.getElementById("reserve-existing-email");
const reserveExistingMobile = document.getElementById("reserve-existing-mobile");
const reserveExistingAddress = document.getElementById("reserve-existing-address");
const reserveExistingLastCheckin = document.getElementById("reserve-existing-lastcheckin");

// Input Content 4 - Reservation Details
const reserveCheckinDate = document.getElementById("reserve-checkin-date-input");
const reserveCheckinTime = document.getElementById("reserve-checkin-time-input");
const reserveCheckoutDate = document.getElementById("reserve-checkout-date-input");
const reserveCheckoutTime = document.getElementById("reserve-checkout-time-input");
const reserveRoomType = document.getElementById("reserve-room-type");
const reserveRoomNo = document.getElementById("reserve-room-no");
const reserveGuestNum = document.getElementById("reserve-guest-num");
const reserveHoursStay = document.getElementById("reserve-hours-stay");

// Input Content 5 - Summary
const reserveOutputFname = document.getElementById("reserve-output-fname");
const reserveOutputLname = document.getElementById("reserve-output-lname");
const reserveOutputGender = document.getElementById("reserve-output-gender");
const reserveOutputMobile = document.getElementById("reserve-output-mobile");
const reserveOutputEmail = document.getElementById("reserve-output-email");
const reserveOutputAddress = document.getElementById("reserve-output-address");
const reserveOutputCheckinDate = document.getElementById("reserve-output-checkin-date");
const reserveOutputCheckinTime = document.getElementById("reserve-output-checkin-time");
const reserveOutputCheckoutDate = document.getElementById("reserve-output-checkout-date");
const reserveOutputCheckoutTime = document.getElementById("reserve-output-checkout-time");
const reserveOutputRoomType = document.getElementById("reserve-output-room-type");
const reserveOutputRoomNo = document.getElementById("reserve-output-room-no");
const reserveOutputGuestNum = document.getElementById("reserve-output-guest-num");
const reserveOutputRate = document.getElementById("reserve-output-rate");

// Input Content 6 - Payment Buttons
const reserveGcashBtn = document.getElementById("reserve-gcash-btn");
const reserveCashBtn = document.getElementById("reserve-cash-btn");

// Input Content 7 - Payment Summary
const reservePaymentMethod = document.getElementById("reserve-payment-method");
const reserveTotalAmount = document.getElementById("reserve-total-amount");

// Input Content 10 - GCash Payment
const reserveAccNameGcash = document.getElementById("reserve-acc-name-gcash");
const reserveAccNumGcash = document.getElementById("reserve-acc-num-gcash");

// Input Content 11 - Cash Payment
const reserveCashAmount = document.getElementById("reserve-cash-amount");
const reserveTotalAmountCash = document.getElementById("reserve-total-amount-cash");
const reserveChangeAmount = document.getElementById("reserve-change-amount");

// Footer Buttons
const reservePrevButton = document.getElementById("prev-button-reserve");
const reserveCancelButton = document.getElementById("cancel-button-reserve");
const reserveNextButton = document.getElementById("next-button-reserve");


document.addEventListener("DOMContentLoaded", function () {
    let scanner_reserve = null; // Declare scanner_reserve variable

    // Function to start scanning
    function startScanner() {
        // Choose the correct QR reader based on modal_part_reserve
        const qrReaderDiv = (modal_part_reserve === 2 ? document.querySelector("#reserve-modal #qr-reader5") : document.querySelector("#reserve-modal #qr-reader6"));
        
        if (!scanner_reserve) {
            scanner_reserve = new Html5Qrcode(qrReaderDiv.id); // Use dynamic element ID

            // Request camera and start scanning
            scanner_reserve.start(
                { facingMode: "environment" }, // "environment" for rear cam, "user" for front cam
                { fps: 20, qrbox: 250 },
                (decodedText) => {
                    scanner_reserve.stop();
                },
                (errorMessage) => {
                    console.warn("QR Scan Error:", errorMessage);
                }
            );
        }
    }

    // Function to stop scanning
    function stopScanner() {
        if (scanner_reserve) {
            scanner_reserve.stop().then(() => {
                scanner_reserve.clear();
                scanner_reserve = null;
            });
        }
    }

    // Monitor modal part dynamically
    let modalPartInterval = setInterval(() => {
        const modal = document.querySelector("#reserve-modal");
        if (modal && modal.classList.contains("show")) {
            // Check if modal_part_reserve is 2 or 8
            if ((modal_part_reserve === 2 || modal_part_reserve === 8) && !scanner_reserve) {
                startScanner(); // Start scanner_reserve only if modal_part_reserve is 2 or 8
            } else if ((modal_part_reserve !== 2 && modal_part_reserve !== 8) && scanner_reserve) {
                stopScanner(); // Stop scanner_reserve if modal_part_reserve is not 2 or 8
            }
        }
    }, 1000); // Check every second

    // Clean up the interval when the modal is closed
    document.querySelector("#reserve-modal").addEventListener("hidden.bs.modal", function () {
        clearInterval(modalPartInterval); // Stop checking modal part
        stopScanner(); // Stop the scanner_reserve when the modal is closed
    });

    // Ensure that qr-reader2 adjusts layout properly when modal is shown
    document.querySelector("#reserve-modal").addEventListener("shown.bs.modal", function () {
        setTimeout(() => {
            const qrReaderDiv = (modal_part_reserve === 2 ? document.querySelector("#reserve-modal #qr-reader5") : document.querySelector("#reserve-modal #qr-reader6"));
            qrReaderDiv.style.width = '100%'; // Ensure the scanner_reserve div takes full width
            qrReaderDiv.style.height = '100%'; // Adjust height accordingly
        }, 300); // Wait a bit to ensure modal is fully shown before recalculating layout
    });
});

        
var current, next, prevButton, cancelButton;

function switchContent(currentSelector, nextSelector, prevButtonSelector, cancelButtonSelector, modalTitleSelector, modalSubMsgSelector, newTitle, newSubMsg) {
    // Adjusted to select elements within #reserve-modal
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

function user_qr_reserve() {
    switchContent(
        "#reserve-modal .input-content-1",   // Current content within the modal
        "#reserve-modal .input-content-2",   // Next content within the modal
        "prev-button-reserve",                       // Previous button
        "cancel-button-reserve",                     // Cancel button
        "#reserve-modal #reserve-modalLabel", // Modal title
        "#reserve-modal #sub-msg",           // Modal subtitle
        "Scan QR Code",                      // New title text
        ""                                   // New subtitle text
    );
    modal_part_reserve = 2;
}

function openVoucherScannerReserve() {
    switchContent(
        "#reserve-modal .input-content-7",   // Current content within the modal
        "#reserve-modal .input-content-8",   // Next content within the modal
        "prev-button-reserve",                       // Previous button
        "cancel-button-reserve",                     // Cancel button
        "#reserve-modal #reserve-modalLabel", // Modal title
        "#reserve-modal #sub-msg",           // Modal subtitle
        "Scan Voucher",                      // New title text
        ""                                   // New subtitle text
    );
    modal_part_reserve = 8;
}

function modal_switch_next_reserve() {

    switch (modal_part_reserve) {
        case 1:
            switchContent(
                "#reserve-modal .input-content-1",   // Current content
                "#reserve-modal .input-content-4",   // Next content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",           // Modal subtitle
                "Reserve Room",           // New title text
                "Enter reservation details." // New subtitle text
            );
        
            modal_part_reserve = 4;
            break;
        case 2:
            switchContent(
                "#reserve-modal .input-content-2",   // Current content
                "#reserve-modal .input-content-1",   // Next content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",           // Modal subtitle
                "Reserve Room",           // New title text
                "Enter reservation details." // New subtitle text
            );
            
            modal_part_reserve = 1;
            break;
        case 3:
            switchContent(
                "#reserve-modal .input-content-3",   // Current content
                "#reserve-modal .input-content-1",   // Next content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",           // Modal subtitle
                "Reserve Room",           // New title text
                "Enter reservation details." // New subtitle text
            );

            modal_part_reserve = 1;
            break;
        case 4:
            switchContent(
                "#reserve-modal .input-content-4",   // Current content
                "#reserve-modal .input-content-5",   // Next content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",           // Modal subtitle
                "Confirm Details",           // New title text
                "" // New subtitle text
            );

            modal_part_reserve = 5;
            break;
        case 5:
            switchContent(
                "#reserve-modal .input-content-5",   // Current content
                "#reserve-modal .input-content-6",   // Next content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",           // Modal subtitle
                "Payment Method",           // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 6;
            break;
        case 6:
            switchContent(
                "#reserve-modal .input-content-6",   // Current content
                "#reserve-modal .input-content-7",   // Next content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",           // Modal subtitle
                "Confirm Payment Details",           // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 7;
            break;
        case 7:
            switchContent(
                "#reserve-modal .input-content-7",   // Current content
                "#reserve-modal .input-content-10",   // Next content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",           // Modal subtitle
                "",         // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 10;
            break;
        case 8:
            switchContent(
                "#reserve-modal .input-content-8",   // Current content
                "#reserve-modal .input-content-10",   // Next content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",           // Modal subtitle
                "SCAN VOUCHER",           // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 7;
            break;
        case 9:
            switchContent(
                "#reserve-modal .input-content-9",   // Current content
                "#reserve-modal .input-content-12",   // Next content
                "confirm-button-reserve",        // Previous button
                "next-button-reserve",       // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",           // Modal subtitle
                "",           // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 12;
            break;
        case 10:
            switchContent(
                "#reserve-modal .input-content-10",   // Current content
                "#reserve-modal .input-content-12",   // Next content
                "confirm-button-reserve",        // Previous button
                "next-button-reserve",       // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",           // Modal subtitle
                "",           // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 12;
            break;
        case 11:
            switchContent(
                "#reserve-modal .input-content-11",   // Current content
                "#reserve-modal .input-content-12",   // Next content
                "confirm-button-reserve",        // Previous button
                "next-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",           // Modal subtitle
                "",           // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 12;
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

function modal_switch_prev_reserve() {
    switch (modal_part_reserve) {
        case 2:
            switchToPreviousContent(
                "#reserve-modal .input-content-2",   // Current content
                "#reserve-modal .input-content-1",   // Previous content
                "prev-button-reserve",        // Previous button (should be hidden)
                "cancel-button-reserve",      // Cancel button (should be shown)
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",            // Modal subtitle
                "Reserve Room",           // New title text
                "Enter guest details." // New subtitle text
            );         
            setTimeout(() => {   
                let confirmButton = document.querySelector('#reserve-modal #prev-button-reserve');
                let nextButton = document.querySelector('#reserve-modal #cancel-button-reserve');
            
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

            modal_part_reserve = 1;
            break;
        case 3:
            switchToPreviousContent(
                "#reserve-modal .input-content-3",   // Current content
                "#reserve-modal .input-content-1",   // Previous content
                "prev-button-reserve",        // Previous button (should be hidden)
                "cancel-button-reserve",      // Cancel button (should be shown)
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",            // Modal subtitle
                "Reserve Room",           // New title text
                "Enter guest details." // New subtitle text
            );    
            setTimeout(() => {   
                let confirmButton = document.querySelector('#reserve-modal #prev-button-reserve');
                let nextButton = document.querySelector('#reserve-modal #cancel-button-reserve');
            
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
            modal_part_reserve = 1;
            break;
        case 4:
            switchToPreviousContent(
                "#reserve-modal .input-content-4",   // Current content
                "#reserve-modal .input-content-1",   // Previous content
                "prev-button-reserve",        // Previous button (should be hidden)
                "cancel-button-reserve",      // Cancel button (should be shown)
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",            // Modal subtitle
                "Reserve Room",           // New title text
                "Enter guest details." // New subtitle text
            );    
            setTimeout(() => {   
                let confirmButton = document.querySelector('#reserve-modal #prev-button-reserve');
                let nextButton = document.querySelector('#reserve-modal #cancel-button-reserve');
            
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
            modal_part_reserve = 1;
            break;
        case 5:
            switchToPreviousContent(
                "#reserve-modal .input-content-5",   // Current content
                "#reserve-modal .input-content-4",   // Previous content
                "cancel-button-reserve",
                "prev-button-reserve",        // Previous button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",            // Modal subtitle
                "Reserve Room",           // New title text
                "Enter reservation details." // New subtitle text
            );
            modal_part_reserve = 4;
            break;

        case 6:
            switchToPreviousContent(
                "#reserve-modal .input-content-6",   // Current content
                "#reserve-modal .input-content-5",   // Previous content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",            // Modal subtitle
                "Confirm Details",    // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 5;
            break;

        case 7:
            switchToPreviousContent(
                "#reserve-modal .input-content-7",   // Current content
                "#reserve-modal .input-content-6",   // Previous content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 6;
            break;
        case 8:
            switchToPreviousContent(
                "#reserve-modal .input-content-8",   // Current content
                "#reserve-modal .input-content-7",   // Previous content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 7;
            break;
         case 9:
            switchToPreviousContent(
                "#reserve-modal .input-content-9",   // Current content
                "#reserve-modal .input-content-7",   // Previous content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 7;
            break;
         case 10:
            switchToPreviousContent(
                "#reserve-modal .input-content-10",   // Current content
                "#reserve-modal .input-content-7",   // Previous content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 7;
            break;
         case 11:
            switchToPreviousContent(
                "#reserve-modal .input-content-11",   // Current content
                "#reserve-modal .input-content-7",   // Previous content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            modal_part_reserve = 7;
            break;
         case 12:
            switchToPreviousContent(
                "#reserve-modal .input-content-12",   // Current content
                "#reserve-modal .input-content-10",   // Previous content
                "prev-button-reserve",        // Previous button
                "cancel-button-reserve",      // Cancel button
                "#reserve-modal #reserve-modalLabel", // Modal title
                "#reserve-modal #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            setTimeout(() => {   
                let confirmButton = document.querySelector('#reserve-modal #confirm-button-reserve');
                let nextButton = document.querySelector('#reserve-modal #next-button-reserve');
            
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
            modal_part_reserve = 10;
            break;
    }
}
