var ctx = document.getElementById('myChart').getContext('2d');
const months = ['January', 'February', 'March', 'April', 'May', 'June'];
const reservationCounts = Array(6).fill(0);
const bookingCounts = Array(6).fill(0);
const checkInCounts = Array(6).fill(0);

// Debug: Log transactions to verify structure
console.log('Transactions:', transactions);

transactions.forEach(tx => {
    // Date parsing (handles MongoDB format)
    const createdAt = tx.created_at?.$date?.$numberLong 
        ? new Date(parseInt(tx.created_at.$date.$numberLong))
        : new Date(tx.created_at);
    
    const month = createdAt.getMonth(); // 0-11 (Jan-Dec)
    if (month < 0 || month > 5) return; // Only Jan-Jun

    const status = tx.current_status?.toLowerCase();
    const type = tx.transaction_type?.toLowerCase();

    // Count all reservations and bookings
    if (type === 'reservation') reservationCounts[month]++;
    if (type === 'booking') bookingCounts[month]++;

    // Check if actually checked in (has actual_checkin date)
    if (tx.stay_details?.actual_checkin) {
        checkInCounts[month]++;
    }
});
    
// Debug: Log the counts
console.log('Reservation Counts:', reservationCounts);
console.log('Booking Counts:', bookingCounts);
console.log('CheckIn Counts:', checkInCounts);

var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Check Ins',
                data: checkInCounts,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                tension: 0.1
            },
            {
                label: 'Bookings',
                data: bookingCounts,
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderWidth: 2,
                tension: 0.1
            },
            {
                label: 'Reservations',
                data: reservationCounts,
                borderColor: 'rgba(255, 159, 64, 1)',
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderWidth: 2,
                tension: 0.1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Monthly Transactions'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Transactions'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Month'
                }
            }
        }
    }
});

// Create the chart with real data
var ctx1 = document.getElementById('occupiedRoomsChart').getContext('2d');
var occupiedRoomsChart = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: Object.keys(roomTypeCounts), // Dynamically get room types from your data
        datasets: [{
            label: 'Most Occupied Rooms',
            data: Object.values(roomTypeCounts), // Use the actual counts
            backgroundColor: '#003087',
            borderColor: '#003087',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Bookings'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Room Types'
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.raw}`;
                    }
                }
            }
        }
    }
});

// Data for the second chart (Top Rated Rooms)
var ctx2 = document.getElementById('topRatedRoomsChart').getContext('2d');
var topRatedRoomsChart = new Chart(ctx2, {
    type: 'bar', // Vertical bar chart
    data: {
        labels: ['Standard', 'Twin', 'Family', 'Deluxe', 'Suite'], // Room types
        datasets: [{
            label: 'Top Rated Rooms',
            data: [4.2, 4.8, 4.5, 4.1, 4.7], // Example data: room ratings (out of 5)
            backgroundColor: '#003087', // Bar color
            borderColor: '#003087', // Border color
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1, // Set step size to 1 for ratings (integers)
                    max: 5 // Maximum rating is 5
                }
            }
        }
    }
});