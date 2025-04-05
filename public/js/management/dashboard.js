 // Function to format the date and time
 function updateDateTime() {
    const now = new Date();

    // Formatting the date (e.g., Friday, February 27)
    const options = { weekday: 'long', month: 'long', day: 'numeric' };
    const formattedDate = now.toLocaleDateString('en-US', options);

    // Formatting the time (e.g., 12:59 PM)
    const formattedTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

    // Update the content of the date and time elements
    document.getElementById('current-date').textContent = formattedDate;
    document.getElementById('current-time').textContent = formattedTime;
}

// Update date and time when the page loads
window.onload = updateDateTime;