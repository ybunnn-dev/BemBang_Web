let qr_main; // Single declaration at the top level

document.addEventListener("DOMContentLoaded", function () {
    // Function to handle scanned data
    function handleScannedData(decodedText) {
        console.log("QR Code scanned:", decodedText);
        processScannedData(decodedText);
        
        qr_main.stop().then(() => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('qr-modal'));
            modal.hide();
        });
    }

    function processScannedData(data) {
        // Validate the data is a valid ID (numeric or UUID)
        if (!data || !data.trim()) {
            console.error("Invalid QR data");
            alert("Invalid QR code data");
            return;
        }
    
        const trimmedData = data.trim();
        
        // Try to find matching transaction
        const matchingTransaction = transactionsData.find(transaction => {
            return transaction.id === trimmedData;
        });
    
        if (matchingTransaction) {
            console.log("Matching Transaction Found:", matchingTransaction);
            if(matchingTransaction.transaction_type === 'Booking') {
                if(matchingTransaction.current_status !== 'confirmed' && matchingTransaction.current_status !== 'completed') {
                    window.location.href = `/frontdesk/bookings/${trimmedData}`;
                } else {
                    if(matchingTransaction.current_status === 'confirmed') {
                        window.location.href = `/frontdesk/room-details/${trimmedData}`;
                    }
                    alert("Booking transaction already completed");
                } 
            } else if(matchingTransaction.transaction_type === 'Reservation') {
                if(matchingTransaction.current_status !== 'confirmed' && matchingTransaction.current_status !== 'completed') {
                    window.location.href = `/frontdesk/reservations/${trimmedData}`;
                } else {
                    if(matchingTransaction.current_status === 'confirmed') {
                        window.location.href = `/frontdesk/room-details/${trimmedData}`;
                    }
                    alert("Reservation already completed");
                } 
            }
            return;
        }
    
        // Check if the data exists in the guests object (if it exists)
        if (typeof guests !== 'undefined' && Array.isArray(guests)) {
            const matchingGuest = guests.find(guest => {
                // Safely check for $oid property
                return (guest._id && guest._id.$oid === trimmedData) || 
                       (guest.user_id && guest.user_id.$oid === trimmedData);
            });
            
            if (matchingGuest) {
                window.location.href = `/frontdesk/current-guest/${trimmedData}`;
                return;
            }
        }
    
        // If we get here, no match was found
        alert("Invalid QR code - no matching transaction or guest found");
        console.log("No transaction or guest found with ID:", trimmedData);
    }

    function startqr_main() {
        const qrReaderDiv = document.getElementById('qr-reader-main');

        if (!qr_main) {
            qr_main = new Html5Qrcode(qrReaderDiv.id);

            qr_main.start(
                { facingMode: "environment" },
                { 
                    fps: 20, 
                    qrbox: 250,
                    aspectRatio: 1.0,
                    disableFlip: true
                },
                (decodedText) => {
                    handleScannedData(decodedText);
                },
                (errorMessage) => {
                    console.warn("QR Scan Error:", errorMessage);
                }
            ).then(() => {
                qrReaderDiv.style.borderRadius = '10px';
                qrReaderDiv.style.overflow = 'hidden';
                const videoElement = qrReaderDiv.querySelector('video');
                if (videoElement) {
                    videoElement.style.width = '100%';
                    videoElement.style.height = '100%';
                    videoElement.style.objectFit = 'cover';
                    videoElement.style.borderRadius = '10px';
                }
            }).catch(err => {
                console.error("Failed to start QR scanner:", err);
                alert("Failed to start camera: " + err.message);
            });
        }
    }

    function stopqr_main() {
        if (qr_main) {
            qr_main.stop().then(() => {
                qr_main = null; // Clear the reference
            }).catch(err => {
                console.error("Failed to stop QR scanner:", err);
                qr_main = null; // Clear reference even if stop fails
            });
        }
    }

    // Modal event listeners
    const qrModal = document.getElementById("qr-modal");
    if (qrModal) {
        qrModal.addEventListener("shown.bs.modal", startqr_main);
        qrModal.addEventListener("hidden.bs.modal", stopqr_main);
    }
});