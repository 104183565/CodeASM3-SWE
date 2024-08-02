// This function generates the time slot table
function generateSlotTable() {
    const slotTableBody = document.getElementById('slotTableBody');
    slotTableBody.innerHTML = ''; // Clear existing table rows

    // Define the time slots available for selection
    const timeSlots = [
        '00:00-03:00', '03:00-06:00', '06:00-09:00', '09:00-12:00',
        '12:00-15:00', '15:00-18:00', '18:00-21:00', '21:00-24:00', '24:00-00:00'
    ];

    // Create a new row for the selected car type
    const row = document.createElement('tr');
    row.innerHTML = `<td id="slotName">${document.getElementById('carType').value}</td>`;

    // Create checkbox inputs for each time slot
    timeSlots.forEach(slot => {
        const cell = document.createElement('td');
        cell.innerHTML = `<input type="checkbox" name="selectedSlots" value="${slot}">`;
        row.appendChild(cell);
    });

    // Append the new row to the table body
    slotTableBody.appendChild(row);
}

// Event listener for changes in the car type dropdown
document.getElementById('carType').addEventListener('change', function () {
    const selectedCarTypeValue = this.value;
    document.getElementById('slotName').textContent = selectedCarTypeValue; // Display selected car type
    generateSlotTable(); // Generate slot table based on selected car type
});

// Event listener for changes in the booking date
document.getElementById('dateOfBooking').addEventListener('change', function () {
    document.getElementById('slotReservationSystem').style.display = 'block'; // Show slot reservation system
    generateSlotTable(); // Generate the slot table
});

// Function to validate and submit the reservation
function submitReservation() {
    // Get values from form fields
    const carType = document.getElementById('carType').value;
    const parkingType = document.getElementById('parkingType').value;
    const bookingDate = document.getElementById('dateOfBooking').value;
    const address = document.getElementById('address').value;
    const selectedSlots = Array.from(document.querySelectorAll('input[name="selectedSlots"]:checked'))
        .map(checkbox => checkbox.value); // Get selected slots

    // Variable to hold error messages
    let errorMessage = '';

    // Check required fields and create corresponding error messages
    if (!carType) {
        errorMessage += 'Please select a car type.\n';
    }
    if (!parkingType) {
        errorMessage += 'Please select a parking spot.\n';
    }
    if (!bookingDate) {
        errorMessage += 'Please select a booking date.\n';
    }
    if (!address) {
        errorMessage += 'Please enter your address.\n';
    }
    if (selectedSlots.length === 0) {
        errorMessage += 'Please select at least one time slot.\n';
    }

    // If there are errors, display the error messages
    if (errorMessage) {
        alert(errorMessage);
        return; // Stop execution if there are errors
    }

    // If all information is valid, store the selected slots in a hidden input
    document.getElementById('selectedSlotsInput').value = selectedSlots.join(',');
    alert('Reservation successful!'); // Notify the user
    document.getElementById('bookingForm').submit(); // Submit the form
}