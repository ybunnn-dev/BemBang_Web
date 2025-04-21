var allRoomData = window.allRoom;

document.addEventListener('DOMContentLoaded', function () {
    const saveratesBtn = document.getElementById('save-edit-rate-modal');
    const rateModalContent = document.getElementById('editRates');
    const cancelBtn = document.getElementById('close-edit-rate-modal');
    var updatedData;

    // Get all rate input fields
    const rateInputs = {
        checkin_12h: document.getElementById('edit-check-12'),
        checkin_24h: document.getElementById('edit-check-24'),
        reservation_12h: document.getElementById('edit-reserve-12'),
        reservation_24h: document.getElementById('edit-reserve-24')
    };

    // Store original values
    const originalValues = {
        checkin_12h: rateInputs.checkin_12h.value.trim(),
        checkin_24h: rateInputs.checkin_24h.value.trim(),
        reservation_12h: rateInputs.reservation_12h.value.trim(),
        reservation_24h: rateInputs.reservation_24h.value.trim()
    };

    // Function to check if any rate field has changed
    function checkRateChanges() {
        console.log("hi");
        // Get current rate values
        const currentRates = {
            checkin_12h: rateInputs.checkin_12h.value.trim(),
            checkin_24h: rateInputs.checkin_24h.value.trim(),
            reservation_12h: rateInputs.reservation_12h.value.trim(),
            reservation_24h: rateInputs.reservation_24h.value.trim()
        };

        // Check if any rate has changed
        const ratesChanged = 
            currentRates.checkin_12h !== originalValues.checkin_12h ||
            currentRates.checkin_24h !== originalValues.checkin_24h ||
            currentRates.reservation_12h !== originalValues.reservation_12h ||
            currentRates.reservation_24h !== originalValues.reservation_24h;

        // Enable save button if rates have changed
        if (saveratesBtn) {
            saveratesBtn.disabled = !ratesChanged;
        }
    }

    Object.values(rateInputs).forEach(input => {
        console.log('Attaching listener to:', input); // Debug
        input.addEventListener('input', checkRateChanges);
        input.addEventListener('change', checkRateChanges);
    });
    function updateRates(){

        const payload = {
            id: allRoomData._id.$oid,
            c12: updatedData.checkin_12h,
            c24: updatedData.checkin_24h,
            r12: updatedData.reservation_12h,
            r24: updatedData.reservation_24h
        }

        console.log('Data to save:',payload);

        fetch('/update-rates', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to update');
            return response.json();
        })
        .then(data => {
            console.log(data);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Update failed.');
        });
    }
    document.getElementById('confirm-changes-rates').addEventListener('click', updateRates);

    function confirm_rates(){
        updatedData = {
            checkin_12h: parseFloat(rateInputs.checkin_12h.value),
            checkin_24h: parseFloat(rateInputs.checkin_24h.value),
            reservation_12h: parseFloat(rateInputs.reservation_12h.value),
            reservation_24h: parseFloat(rateInputs.reservation_24h.value)
        };
        
        const modalElement = document.getElementById('editRates');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        
        if(modalInstance){
            modalInstance.hide();
        }

        const newModal = new bootstrap.Modal(document.getElementById('confirm-rate-edit'));
        newModal.show();
        // Add your AJAX save logic here
    }
    // Example usage in save function
    function closeRateConfirm(){
        const currentModal = bootstrap.Modal.getInstance(document.getElementById('confirm-rate-edit'));

        if(currentModal){
            currentModal.hide();
        }
        const newModal = new bootstrap.Modal(document.getElementById('editRates'));
        newModal.show();
    }
    saveratesBtn.addEventListener('click', confirm_rates);
    document.getElementById('cancel-button-rate-confirm').addEventListener('click', closeRateConfirm);
    // Reset to original values on cancel
    cancelBtn.addEventListener('click', function() {
        console.log(rateInputs);

        rateInputs.checkin_12h.value = originalValues.checkin_12h;
        rateInputs.checkin_24h.value = originalValues.checkin_24h;
        rateInputs.reservation_12h.value = originalValues.reservation_12h;
        rateInputs.reservation_24h.value = originalValues.reservation_24h;

        const modalElement = document.getElementById('editRates');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);

        if(modalInstance){
            modalInstance.hide();
        }
    });
});
