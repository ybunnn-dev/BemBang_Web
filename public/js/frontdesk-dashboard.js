function updateDateTime() {
    const now = new Date();

    // Get current date parts
    const dayNumber = now.getDate();
    const dayName = now.toLocaleString('en-us', { weekday: 'long' }).toUpperCase();
    const monthName = now.toLocaleString('en-us', { month: 'long' }).toUpperCase();
    
    // Format time (12-hour format with AM/PM)
    let hours = now.getHours();
    let minutes = now.getMinutes();
    let ampm = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12 || 12; // Convert 0 to 12
    minutes = minutes < 10 ? '0' + minutes : minutes; // Add leading zero if needed
    const currentTime = `${hours}:${minutes} ${ampm}`;

    // Update HTML content
    console.log(dayNumber);
    document.querySelector("#calendar #inner-circle h3").innerText = dayNumber;
    document.querySelector("#calendar #inner-circle p").innerText = dayName;
    document.querySelector("#calendar h4").innerText = monthName;
    document.querySelector("#calendar h5").innerText = `${hours}:${minutes} ${ampm}`;
}

document.addEventListener("DOMContentLoaded", function () {
    updateDateTime(); // Run immediately
    setInterval(updateDateTime, 1000); // Update every second
});