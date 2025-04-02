let scanner;
let modal_part = 1;

document.addEventListener("DOMContentLoaded", function () {
    let scanner = null; // Declare scanner variable

    // Function to start scanning
    function startScanner() {
        // Choose the correct QR reader based on modal_part
        const qrReaderDiv = (modal_part === 2 ? document.querySelector("#checkInModal1 #qr-reader") : document.querySelector("#checkInModal1 #qr-reader2"));
        
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
            const qrReaderDiv = (modal_part === 2 ? document.querySelector("#checkInModal1 #qr-reader") : document.querySelector("#checkInModal1 #qr-reader2"));
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

    switch (modal_part) {
        case 1:
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
        case 3:
            switchContent(
                "#checkInModal1 .input-content-3",   // Current content
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
            modal_part = 7;
            break;
        case 7:
            switchContent(
                "#checkInModal1 .input-content-7",   // Current content
                "#checkInModal1 .input-content-10",   // Next content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",           // Modal subtitle
                "",         // New title text
                "" // New subtitle text
            );
            modal_part = 10;
            break;
        case 8:
            switchContent(
                "#checkInModal1 .input-content-8",   // Current content
                "#checkInModal1 .input-content-10",   // Next content
                "prev-button",        // Previous button
                "cancel-button",      // Cancel button
                "#checkInModal1 #checkInModal1Label", // Modal title
                "#checkInModal1 #sub-msg",           // Modal subtitle
                "SCAN VOUCHER",           // New title text
                "" // New subtitle text
            );
            modal_part = 7;
            break;
        case 9:
            switchContent(
                "#checkInModal1 .input-content-9",   // Current content
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
        case 8:
            switchToPreviousContent(
                "#checkInModal1 .input-content-8",   // Current content
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
         case 9:
            switchToPreviousContent(
                "#checkInModal1 .input-content-9",   // Current content
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
