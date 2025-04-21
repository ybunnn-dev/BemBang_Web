var ctx = document.getElementById('myChart').getContext('2d');

var myChart = new Chart(ctx, {
    type: 'line', // Line chart type
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June'], // x-axis labels
        datasets: [{
            label: 'Check Ins', // Label for the first line
            data: [12, 19, 3, 5, 2, 3], // Data for the first line
            borderColor: 'rgba(75, 192, 192, 1)', // Line color
            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Background color for points
            fill: false, // Do not fill the area under the line
            tension: 0.1 // Line smoothness
        },
        {
            label: 'Bookings', // Label for the second line
            data: [10, 14, 5, 6, 4, 8], // Data for the second line
            borderColor: 'rgba(153, 102, 255, 1)', // Line color for the second line
            backgroundColor: 'rgba(153, 102, 255, 0.2)', // Background color for points
            fill: false, // Do not fill the area under the line
            tension: 0.1 // Line smoothness
        },
        {
            label: 'Reservations', // Label for the third line
            data: [7, 11, 15, 7, 6, 9], // Data for the third line
            borderColor: 'rgba(255, 159, 64, 1)', // Line color for the third line
            backgroundColor: 'rgba(255, 159, 64, 0.2)', // Background color for points
            fill: false, // Do not fill the area under the line
            tension: 0.1 // Line smoothness
        }]
    },
    options: {
        responsive: true, // Make the chart responsive
        scales: {
            y: {
                beginAtZero: true // Y-axis starts at 0
            }
        }
    }
});

var ctx1 = document.getElementById('occupiedRoomsChart').getContext('2d');
var occupiedRoomsChart = new Chart(ctx1, {
    type: 'bar', // Vertical bar chart
    data: {
        labels: ['Standard', 'Twin', 'Family', 'Deluxe', 'Suite'], // Room types
        datasets: [{
            label: 'Most Occupied Rooms',
            data: [30, 45, 40, 25, 35], // Example data: number of occupied rooms
            backgroundColor: '#003087', // Bar color
            borderColor: '#003087', // Border color
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
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