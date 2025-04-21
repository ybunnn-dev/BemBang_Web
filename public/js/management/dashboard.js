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


const labels = ['January', 'February', 'March', 'April', 'May', 'June'];

    const chartData = {
        labels: labels,
        datasets: [
            {
                label: 'Check Ins',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: false,
                tension: 0.1
            },
            {
                label: 'Bookings',
                data: [10, 14, 5, 6, 4, 8],
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                fill: false,
                tension: 0.1
            },
            {
                label: 'Reservations',
                data: [7, 11, 15, 7, 6, 9],
                borderColor: 'rgba(255, 159, 64, 1)',
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                fill: false,
                tension: 0.1
            }
        ]
    };

    const chartOptions = {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };
    new Chart(document.getElementById('myChart1').getContext('2d'), {
        type: 'line',
        data: chartData,
        options: chartOptions
    });
    const ctxTopOccupied = document.getElementById('topOccupiedRoomsChart').getContext('2d');

    new Chart(ctxTopOccupied, {
        type: 'bar',
        data: {
            labels: ['Standard', 'Twin', 'Family', 'Deluxe', 'Suite'],
            datasets: [{
                label: 'Most Occupied Rooms',
                data: [120, 95, 110, 85, 140], // Example occupancy counts
                backgroundColor: '#003087'
            }]
        },
        options: {
            indexAxis: 'y', // Horizontal bars
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Occupancy Count'
                    }
                }
            }
        }
    });