
let updateEmail = null, 
    updateNum = null,
    updateAdd = null;
var passwordInput;

document.addEventListener('DOMContentLoaded', function () {
    const orig_email = window.origEmail;
    const orig_num = window.origNum;
    const orig_add = window.origAdd;
    

    document.getElementById('formControlInput1').addEventListener('input', function() {
        const currentInput = this.value.trim();
        
        document.getElementById('email-submit').disabled = 
            (currentInput === orig_email || !currentInput.includes('@gmail.com'));
    });

    document.getElementById('formControlInput2').addEventListener('input', function() {
        const currentInput = this.value.trim(); 

        document.getElementById('change-num-confirm').disabled = 
            (currentInput === orig_num);
    });

    document.getElementById('formControlInput3').addEventListener('input', function() {
        const currentInput = this.value.trim(); 

        document.getElementById('confirm-address').disabled = 
            (currentInput === orig_add);
    });
    document.getElementById('formControlInput6').addEventListener('input', function() {
        passwordInput = this.value.trim(); 

        document.getElementById('confirmPassModalBtn').disabled = 
            (passwordInput === '' || passwordInput.length < 8);
    });

    document.getElementById('confirmPassModalBtn').addEventListener('click', function(){
        const payload = {
            email: updateEmail,
            mobileNum: updateNum,
            address: updateAdd,
            pass: passwordInput
        }

        console.log(payload);

        fetch('/profile-update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Failed to update');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            alert(data.message); // Show success message
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Update failed.');
        });

    });
});

function insertInput(docLink, key){
    const dataToInput = document.getElementById(docLink).value.trim();
    var idToPass;
    switch(key){
        case 1:
            updateEmail = dataToInput;
            document.getElementById('email_display').textContent = dataToInput;
            idToPass = document.getElementById('changeEmailModal');
            break;
        case 2: 
            updateNum = dataToInput;
            document.getElementById('mobileNum_display').textContent = dataToInput;
            idToPass = document.getElementById('changeNumModal');
            break;
        case 3:
            updateAdd = dataToInput;
            document.getElementById('address').textContent = dataToInput;
            idToPass = document.getElementById('changeAddressModal');
            break;
    }

    const currentModal = bootstrap.Modal.getInstance(idToPass);
    if(currentModal){
        currentModal.hide();
    }
}
