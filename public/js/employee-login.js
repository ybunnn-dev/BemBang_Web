document.addEventListener("DOMContentLoaded", function() {
    let getStartedBtn = document.querySelector("#msg-container .btn");
    let msgContainer = document.getElementById("msg-container");
    let logoContainer = document.getElementById("logo-container");

    getStartedBtn.addEventListener("click", function() {
        msgContainer.classList.add("hidden"); // Add fade-out effect
        logoContainer.style.left = "0px"; // Move logo to 0px
    });
});