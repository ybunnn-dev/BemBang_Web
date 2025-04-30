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

const months = ['January', 'February', 'March', 'April', 'May', 'June'];

    // ====== LINE CHART (Monthly Transactions) ======
    const reservationCounts = Array(6).fill(0);
    const bookingCounts = Array(6).fill(0);
    const checkInCounts = Array(6).fill(0);

    // Process transactions for monthly counts
    transactions.forEach(tx => {
        // Date parsing (handles both MongoDB format and ISO strings)
        let createdAt;
        if (tx.created_at?.$date?.$numberLong) {
            createdAt = new Date(parseInt(tx.created_at.$date.$numberLong));
        } else if (tx.created_at) {
            createdAt = new Date(tx.created_at);
        } else {
            return; // Skip if no date
        }

        const month = createdAt.getMonth(); // 0-11 (Jan-Dec)
        if (month < 0 || month > 5) return; // Only Jan-Jun (first 6 months)

        const type = tx.transaction_type?.toLowerCase();
        const status = tx.current_status?.toLowerCase();

        // Count reservations and bookings
        if (type === 'reservation') reservationCounts[month]++;
        if (type === 'booking') bookingCounts[month]++;

        // Count check-ins (has actual_checkin date)
        if (tx.stay_details?.actual_checkin) {
            checkInCounts[month]++;
        }
    });

    // Create monthly transactions line chart
    new Chart(document.getElementById('myChart1').getContext('2d'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Check Ins',
                    data: checkInCounts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Bookings',
                    data: bookingCounts,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Reservations',
                    data: reservationCounts,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    fill: false,
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: false,
                    text: 'Monthly Transactions (Jan-Jun)'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw}`;
                        }
                    }
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

    // First process the roomTypeCounts to aggregate similar types
    const processedRoomTypeCounts = {};

    Object.entries(roomTypeCounts).forEach(([roomType, count]) => {
        // Clean up the room type name
        const cleanType = roomType
            .replace('Peter ', '')  // Remove "Peter " prefix
            .replace('Delux', 'Deluxe') // Fix common misspellings
            .trim();
        
        // Aggregate counts for similar types
        processedRoomTypeCounts[cleanType] = (processedRoomTypeCounts[cleanType] || 0) + count;
    });

    // Then create the chart with the processed data
    var ctx1 = document.getElementById('topOccupiedRoomsChart').getContext('2d');
    var occupiedRoomsChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: Object.keys(processedRoomTypeCounts), // Use cleaned labels
            datasets: [{
                label: 'Most Occupied Rooms',
                data: Object.values(processedRoomTypeCounts), // Use aggregated counts
                backgroundColor: '#003087',
                borderColor: '#003087',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Bookings'
                    }
                },
                y: {
                    title: {
                        display: false
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        // Optional: Show original room type names in tooltip
                        title: function(context) {
                            const cleanLabel = context[0].label;
                            // Find matching original names for this clean label
                            const originalNames = Object.keys(roomTypeCounts)
                                .filter(name => name.replace('Peter ', '').trim() === cleanLabel);
                            
                            return originalNames.length > 0 
                                ? originalNames.join(', ') 
                                : cleanLabel;
                        },
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw}`;
                        }
                    }
                }
            }
        }
    });