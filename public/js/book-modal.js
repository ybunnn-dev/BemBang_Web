let scanner_book;
let book_modal_part = 1;

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

    // Ensure that qr-reader2 adjusts layout properly when modal is shown
    document.querySelector("#book-modal").addEventListener("shown.bs.modal", function () {
        setTimeout(() => {
            const qrReaderDiv = (book_modal_part === 2 ? document.querySelector("#book-modal #qr-reader5") : document.querySelector("#book-modal #qr-reader6"));
            qrReaderDiv.style.width = '100%'; // Ensure the scanner_book div takes full width
            qrReaderDiv.style.height = '100%'; // Adjust height accordingly
        }, 300); // Wait a bit to ensure modal is fully shown before recalculating layout
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

function openVoucherScannerBook() {
    switchContent(
        "#book-modal .input-content-7",   // Current content within the modal
        "#book-modal .input-content-8",   // Next content within the modal
        "prev-button-book",                       // Previous button
        "cancel-button-book",                     // Cancel button
        "#book-modal #book-modalLabel", // Modal title
        "#book-modal #sub-msg",           // Modal subtitle
        "Scan Voucher",                      // New title text
        ""                                   // New subtitle text
    );
    book_modal_part = 8;
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
        
            book_modal_part = 4;
            break;
        case 2:
            switchContent(
                "#book-modal .input-content-2",   // Current content
                "#book-modal .input-content-1",   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "Book Room",           // New title text
                "Enter booking details." // New subtitle text
            );
            
            book_modal_part = 1;
            break;
        case 3:
            switchContent(
                "#book-modal .input-content-3",   // Current content
                "#book-modal .input-content-1",   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "Book Room",           // New title text
                "Enter booking details." // New subtitle text
            );

            book_modal_part = 1;
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
            book_modal_part = 7;
            break;
        case 7:
            switchContent(
                "#book-modal .input-content-7",   // Current content
                "#book-modal .input-content-10",   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "",         // New title text
                "" // New subtitle text
            );
            book_modal_part = 10;
            break;
        case 8:
            switchContent(
                "#book-modal .input-content-8",   // Current content
                "#book-modal .input-content-10",   // Next content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "SCAN VOUCHER",           // New title text
                "" // New subtitle text
            );
            book_modal_part = 7;
            break;
        case 9:
            switchContent(
                "#book-modal .input-content-9",   // Current content
                "#book-modal .input-content-12",   // Next content
                "confirm-button-book",        // Previous button
                "next-button-book",       // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "",           // New title text
                "" // New subtitle text
            );
            book_modal_part = 12;
            break;
        case 10:
            switchContent(
                "#book-modal .input-content-10",   // Current content
                "#book-modal .input-content-12",   // Next content
                "confirm-button-book",        // Previous button
                "next-button-book",       // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "",           // New title text
                "" // New subtitle text
            );
            book_modal_part = 12;
            break;
        case 11:
            switchContent(
                "#book-modal .input-content-11",   // Current content
                "#book-modal .input-content-12",   // Next content
                "confirm-button-book",        // Previous button
                "next-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",           // Modal subtitle
                "",           // New title text
                "" // New subtitle text
            );
            book_modal_part = 12;
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
        case 8:
            switchToPreviousContent(
                "#book-modal .input-content-8",   // Current content
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
         case 9:
            switchToPreviousContent(
                "#book-modal .input-content-9",   // Current content
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
         case 10:
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
            book_modal_part = 7;
            break;
         case 12:
            switchToPreviousContent(
                "#book-modal .input-content-12",   // Current content
                "#book-modal .input-content-10",   // Previous content
                "prev-button-book",        // Previous button
                "cancel-button-book",      // Cancel button
                "#book-modal #book-modalLabel", // Modal title
                "#book-modal #sub-msg",            // Modal subtitle
                "Payment Method",     // New title text
                "" // New subtitle text
            );
            setTimeout(() => {   
                let confirmButton = document.querySelector('#book-modal #confirm-button-book');
                let nextButton = document.querySelector('#book-modal #next-button-book');
            
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
            book_modal_part = 10;
            break;
    }
}
