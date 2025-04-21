var allFeatures = window.all_Features;
var originalFeatures = JSON.parse(JSON.stringify(window.origFeatures)); // Deep copy to preserve original state
var currentFeatures;
var saveBtn; // Declare globally but assign in DOMContentLoaded
var type_name, guestNum, description;
var allRoomData = window.allRoom;

function takeDescriptionChanges(name, num, des, feature){
    type_name = name;
    guestNum = num;
    description = des;
    currentFeatures = feature;
    
    console.log("beki_na_shalala:");
    console.log(originalFeatures);
    console.log(currentFeatures);
}

document.addEventListener('DOMContentLoaded', function () {
    function applyChanges() {
        // Prepare features array with just IDs (if that's all your backend needs)
        const featureIds = currentFeatures.map(f => f._id?.$oid || f.id);
    
        const payload = {
            id: allRoomData._id.$oid, // or allRoomData.id if using string ID
            updates: {
                type_name: type_name,
                guest_num: parseInt(guestNum), // Ensure number type
                description: description,
                features: featureIds // Array of feature IDs
            },
            changed_fields: {
                name: allRoomData.type_name !== type_name,
                guest_num: parseInt(allRoomData.guest_num) !== parseInt(guestNum),
                description: allRoomData.description !== description,
                features: JSON.stringify(currentFeatures.map(f => f._id?.$oid || f.id).sort()) !== 
                         JSON.stringify(originalFeatures.map(f => f._id?.$oid || f.id).sort())
            }
        };
    
        console.log('Final payload:', payload);
        
        fetch('/update-type', {
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
    document.getElementById('confirm-changes-desc').addEventListener('click', function (event) {
        applyChanges()
    });

    saveBtn = document.getElementById('save-edit-modal'); // Assign when DOM is ready
    
    const modalElement = document.getElementById('edit-type'); // The modal wrapper
    const modalContent = document.getElementById('edit-modal'); // Modal data container

    const typeNameInput = document.getElementById('edit-type-name');
    const guestNumInput = document.getElementById('edit-guest-num');
    const descriptionInput = document.getElementById('edit-description');
    const cancelBtn = document.getElementById('close-edit-modal');

    // Store original values from the modal's data attributes
    const originalData = {
        type_name: (modalContent.dataset.name || '').trim(),
        guest_num: (modalContent.dataset.guest || '').trim(),
        description: (modalContent.dataset.description || '').replace(/\s+/g, ' ').trim()
    };

    // Function to check if any field has changed
    function checkChanges() {
        const typeName = typeNameInput.value.trim();
        const guestNum = guestNumInput.value.trim();
        const description = descriptionInput.value.replace(/\s+/g, ' ').trim();

        // Check if text fields have changed
        const fieldsChanged = 
            typeName !== originalData.type_name ||
            guestNum !== originalData.guest_num ||
            description !== originalData.description;
        
        // Check if features have changed
        const originalFeatureIds = originalFeatures.map(f => f.feature_id).sort();
        const currentFeatureIds = currentFeatures.map(f => f.feature_id).sort();
        
        // Compare arrays to see if they're different
        const featuresChanged = JSON.stringify(originalFeatureIds) !== JSON.stringify(currentFeatureIds);
        
        // Enable save button if either fields or features have changed
        const anyChanges = fieldsChanged || featuresChanged;
        
        if (saveBtn) {
            saveBtn.disabled = !anyChanges;
        }
    }

    // Reset fields to their original values
    function resetFields() {
        typeNameInput.value = originalData.type_name;
        guestNumInput.value = originalData.guest_num;
        descriptionInput.value = originalData.description.replace(/\\n/g, "\n");
        if (saveBtn) {
            saveBtn.disabled = true; // Disable save until changes occur
        }
    }

    function closeModal() {
        const modalElement = document.getElementById('edit-type');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        resetFields();
        
        if (modalInstance) {
            modalInstance.hide();
        }
    }

    // Cancel button logic
    cancelBtn.addEventListener('click', function (e) {
        // Prevent default to ensure no form submission
        e.preventDefault();
        // Reset features to original state
        currentFeatures = JSON.parse(JSON.stringify(originalFeatures));
        renderCurrentFeatures();
        checkChanges(); // Check if any other changes remain
        closeModal(); // Close modal and reset fields
    });

    // Escape key detection
    document.addEventListener('keydown', (e) => {
        const isModalOpen = modalElement.classList.contains('show');
        if (e.key === 'Escape' && isModalOpen) {
            e.preventDefault();
            currentFeatures = JSON.parse(JSON.stringify(originalFeatures));
            renderCurrentFeatures();
            checkChanges();
            closeModal();
        }
    });

    // Backdrop click detection
    modalElement.addEventListener('click', (e) => {
        if (e.target === modalElement) {
            e.preventDefault();
            currentFeatures = JSON.parse(JSON.stringify(originalFeatures));
            renderCurrentFeatures();
            checkChanges();
            closeModal();
        }
    });

    // Listen to input changes to track if fields are modified
    typeNameInput.addEventListener('input', checkChanges);
    guestNumInput.addEventListener('input', checkChanges);
    descriptionInput.addEventListener('input', checkChanges);

    // Close modal when clicking outside (on backdrop)
    modalElement.addEventListener('click', function (event) {
        if (event.target === modalElement) {
            closeModal(); // Close modal if backdrop is clicked
        }
    });

    // Escape key close functionality
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal(); // Close modal if Escape key is pressed
        }
    });
});

function gotoEditDetails() {
    // Get references to the existing modal instances
    const currentModalElement = document.getElementById('view_specific_type_details');
    const newModalElement = document.getElementById('edit-type');
    
    const currentModal = bootstrap.Modal.getInstance(currentModalElement);
    
    // Hide the current modal if it exists
    if (currentModal) {
        currentModal.hide();
    }
    
    // Show the new modal
    const newModal = new bootstrap.Modal(newModalElement);
    newModal.show();
}

function addFeatures(){
    const currentModal = bootstrap.Modal.getInstance(document.getElementById('edit-type'));
    
    if(currentModal){
        currentModal.hide();
    }

    const featureModal = new bootstrap.Modal(document.getElementById('edit-features'));
    featureModal.show();
}

function switchModal(){
    const currentModal = bootstrap.Modal.getInstance(document.getElementById('edit-features'));
    
    if(currentModal){
        currentModal.hide();
    }

    const featureModal = new bootstrap.Modal(document.getElementById('edit-type'));
    featureModal.show();
}

function renderFeatureTable() {
    const tbody = document.getElementById("features-table-body");
    tbody.innerHTML = "";

    console.log(currentFeatures);
    const currentIds = currentFeatures.map(f => f.id); 

    allFeatures.forEach(feature => {
        if (!currentIds.includes(feature._id.$oid)) {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>
                    <input type="checkbox" name="features[]" value="${feature._id.$oid}" class="form-check-input">
                </td>
                <td class="feature-name">${feature.feature_name}</td>
                <td>
                    ${feature.feature_icon 
                        ? `<img src="/${feature.feature_icon}" alt="${feature.feature_name}" class="feature-icon">` 
                        : `<span class="text-muted">No icon</span>`
                    }
                </td>
            `;

            tbody.appendChild(row);
        }
    });
}

function addCheckedFeatures() {
    const checkboxes = document.querySelectorAll('.form-check-input:checked');
    let featuresAdded = false;
    
    // Helper function to get clean feature data
    const getCleanFeatureData = (feature) => {
        return {
            created_at: feature.created_at?.$date 
                ? new Date(feature.created_at.$date.$numberLong * 1).toISOString()
                : feature.created_at || null,
            feature_icon: feature.feature_icon,
            feature_name: feature.feature_name,
            id: feature._id?.$oid || feature.id,
            status: feature.status,
            updated_at: feature.updated_at?.$date 
                ? new Date(feature.updated_at.$date.$numberLong * 1).toISOString()
                : feature.updated_at || null
        };
    };

    checkboxes.forEach(checkbox => {
        const featureId = checkbox.value;
        console.log('Adding feature with ID:', featureId);

        // Find the feature - checking both _id.$oid and direct id fields
        const feature = allFeatures.find(f => 
            f._id?.$oid === featureId || f.id === featureId
        );

        if (feature) {
            // Get clean feature data
            const cleanFeature = getCleanFeatureData(feature);
            
            // Check if feature already exists (using cleaned id)
            if (!currentFeatures.some(f => f.id === cleanFeature.id)) {
                currentFeatures.push(cleanFeature);
                featuresAdded = true;
                console.log('Added feature:', cleanFeature.feature_name);
            }
        }
    });
    
    // Update save button state if features were added
    if (featuresAdded && saveBtn) {
        saveBtn.disabled = false;
    }
    
    renderFeatureTable();      // Refresh the modal table (removing added ones)
    renderCurrentFeatures();   // Refresh the displayed list
    switchModal();             // Switch back to the edit modal
}

document.addEventListener("DOMContentLoaded", renderFeatureTable);

function renderCurrentFeatures() {
    const container = document.getElementById('current-feature-list');
    container.innerHTML = '';

    // Create and append the "Add Feature" button first
    const addButton = document.createElement('button');
    addButton.type = 'button';
    addButton.className = 'btn btn-primary mb-3'; // Optional spacing
    addButton.textContent = 'Add Feature';
    addButton.onclick = addFeatures;

    container.appendChild(addButton);

    currentFeatures.forEach(feature => {
        const item = document.createElement('div');
        item.className = 'edit-features-items';
        item.id = `feature-${feature.feature_id}`;

        item.innerHTML = `
            <img src="/${feature.feature_icon}" alt="${feature.feature_name}">
            <p>${feature.feature_name}</p>
        `;

        container.appendChild(item);
    });
}

document.addEventListener("DOMContentLoaded", renderCurrentFeatures);

function confirm_details(){
    const type_name = document.getElementById('edit-type-name').value.trim();
    const guestNum = document.getElementById('edit-guest-num').value.trim();
    const description = document.getElementById('edit-description').value.trim();
    const feature = JSON.parse(JSON.stringify(window.currentFeatures));

    takeDescriptionChanges(type_name, guestNum, description, feature);

    const current = bootstrap.Modal.getInstance(document.getElementById('edit-type'));

    if(current){
        current.hide();
    }

    const confirmModal = new bootstrap.Modal(document.getElementById('confirm-details'));
    confirmModal.show();
}

function switchBackFromConfirm(){
    const current = bootstrap.Modal.getInstance(document.getElementById('confirm-details'));

    if(current){
        current.hide();
    }

    const confirmModal = new bootstrap.Modal(document.getElementById('edit-type'));
    confirmModal.show();
}


