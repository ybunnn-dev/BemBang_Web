function gotoSpecificRoom(id){
    let targetLink = "/management/specific-room/"+id;

    window.location.href=targetLink;
}

function goBacktoRoomList(){
    let targetLink = "/management/manage-rooms";

    window.location.href=targetLink;
}

function goBacktoRoomTypes(){
    let targetLink = "/management/room-types/";

    window.location.href=targetLink;
}
function gotoSpecificType(id){
    console.log(id);
    let targetLink = "/management/specific-type/"+id;

    window.location.href=targetLink;
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    const mainModalEl = document.getElementById('add-room-modal');
    const confirmationModalEl = document.getElementById('confirmationModal');
    
    if (!mainModalEl || !confirmationModalEl) {
        console.error('Required modals not found!');
        return;
    }

    const mainModal = new bootstrap.Modal(mainModalEl);
    const confirmationModal = new bootstrap.Modal(confirmationModalEl);

    // Form elements
    const roomTypeSelect = document.getElementById('roomTypeSelect');
    const confirmBtn = document.getElementById('confirm-add-room');
    const finalConfirmBtn = document.getElementById('finalConfirm');
    const confirmRoomTypeName = document.getElementById('confirmRoomTypeName');
    document.getElementById('confirmRoomNumber').textContent = window.allRoomNum+1;

    if (!roomTypeSelect || !confirmBtn || !finalConfirmBtn || !confirmRoomTypeName) {
        console.error('Required elements not found!');
        return;
    }

    // Event listeners
    confirmBtn.addEventListener('click', function() {
        const selectedOption = roomTypeSelect.options[roomTypeSelect.selectedIndex];
        
        if (selectedOption.value === "0") {
            alert('Please select a room type');
            return;
        }

        // Update confirmation modal content
        confirmRoomTypeName.textContent = selectedOption.text;
        // If you have a room number input, include it here:
        // confirmRoomNumber.textContent = document.getElementById('roomNumberInput').value;
        if(mainModal){
            mainModal.hide();
        }
        confirmationModal.show();
    });

    finalConfirmBtn.addEventListener('click', function() {
        const selectedOption = roomTypeSelect.options[roomTypeSelect.selectedIndex];
        
        // Create proper payload structure
        const payload = {
            room_type_id: selectedOption.value, // Send just the ID
            room_type_name: selectedOption.text,
            room_no: window.allRoomNum + 1 // Assuming you want to increment
        };
        
        // Disable button and show loading state
        finalConfirmBtn.disabled = true;
        finalConfirmBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Processing...
        `;
    
        // AJAX request to add room
        fetch('/management/insert-room', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload) // Send payload directly, no nested payload object
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.message || 'Request failed'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                confirmationModal.hide();
                // Show success toast instead of alert
                alert('success', 'Room added successfully!');
                window.location.reload();
            }
        })
        .catch(error => {
            showToast('error', error.message);
        })
        .finally(() => {
            finalConfirmBtn.disabled = false;
            finalConfirmBtn.textContent = 'Confirm';
        });
    });
    
});