<?php
session_start(); // Start the session

// Get the username from the session
$username = $_SESSION['username'];
$message = ''; // Variable to hold the message

include "settings.php";

// Create table if it does not exist
$table_create_query = "
CREATE TABLE IF NOT EXISTS TheParkingslot_Information (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    parking_type VARCHAR(50) NOT NULL,
    booking_date DATE NOT NULL,
    address VARCHAR(255) NOT NULL,
    slot_time VARCHAR(50) NOT NULL,
    slot_number INT NOT NULL,
    car_type VARCHAR(50) NOT NULL,
    car_type_text VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES TheParkingslot_User(id) ON DELETE CASCADE
);";

if ($conn->query($table_create_query) === TRUE) {
    // Table was created successfully or already exists
} else {
    echo "Error creating table: " . $conn->error;
}

// Get user_id from session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Assume user_id is stored in the session when the user logs in

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get information from the form
    $parking_type = $_POST['parkingType'];
    $booking_date = $_POST['dateOfBooking'];
    $address = $_POST['address'];
    $selected_slots = isset($_POST['selectedSlots']) ? $_POST['selectedSlots'] : []; // Get selected slot information
    $car_type = $_POST['carType']; // Get car type from the form
    $car_type_text = ''; // Variable to hold the name of the car type

    // Determine slot_number and car_type_text based on the car type
    $slot_number = 0;
    switch ($car_type) {
        case 'Slot 1':
            $slot_number = 1;
            $car_type_text = '4-seat car';
            break;
        case 'Slot 2':
            $slot_number = 2;
            $car_type_text = '7-seat car';
            break;
        case 'Slot 3':
            $slot_number = 3;
            $car_type_text = '12-seat car';
            break;
        case 'Slot 4':
            $slot_number = 4;
            $car_type_text = '20-seat car';
            break;
        case 'Slot 5':
            $slot_number = 5;
            $car_type_text = '36-seat car';
            break;
        case 'Slot 6':
            $slot_number = 6;
            $car_type_text = '54-seat car';
            break;
        default:
            $slot_number = 0; // If there is no valid car type
            $car_type_text = 'Unknown car type';
    }

    // Store the name and address in the session
    $_SESSION['customer_name'] = $username;
    $_SESSION['customer_address'] = $address; 
    $_SESSION['parkingType'] = $parking_type; 
    $_SESSION['dateOfBooking'] = $booking_date; 
    $_SESSION['selectedSlots'] = $selected_slots;

    // Variable to hold booking information
    $bookingDetails = [];

    // Insert information into the table for each selected slot
    foreach ($selected_slots as $slot_time) {
        $insert_query = "INSERT INTO TheParkingslot_Information (user_id, parking_type, booking_date, address, slot_time, slot_number, car_type, car_type_text) VALUES ('$user_id', '$parking_type', '$booking_date', '$address', '$slot_time', '$slot_number', '$car_type', '$car_type_text')"; // Add car type to the query

        if ($conn->query($insert_query) !== TRUE) {
            $message = "Error: " . $insert_query . "<br>" . $conn->error;
        } else {
            // Save information of booked slots into the array
            $bookingDetails[] = [
                'time' => $slot_time,
                'slot_number' => $slot_number,
                'car_type_text' => $car_type_text,
                'parking_type' => $parking_type, 
                'booking_date' => $booking_date
            ];
        }
    }

    // Save booking information into the session
    $_SESSION['bookingDetails'] = $bookingDetails;

    $message = "Reservation successful!";
    echo "<script>window.location.href = 'invoice.php';</script>";
    exit(); 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Team 2">
    <meta name="description" content="Assignment 3 - SWE30003">
    <title>Smart Parking System - Book Slot</title>

    <!-- Bootstrap CSS & JS  -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Script for CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div id="bookslotPage">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="home.php">Smart Parking System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="history.php">History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Log out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-5">
            <div class="form-container">
                <h2 class="text-center mb-4">Book a Parking Slot</h2>
                <form id="bookingForm" action="bookslot.php" method="post" class="mt-4">
                    <h4 class="text-center mb-4">User name: <?php echo htmlspecialchars($username); ?></h4>

                    <div class="mb-3">
                        <label for="carType" class="form-label">Car Type:</label>
                        <select id="carType" name="carType" class="form-select" required>
                            <option value="" disabled selected>Select a car type</option>
                            <option value="Slot 1">4-seat car</option>
                            <option value="Slot 2">7-seat car</option>
                            <option value="Slot 3">12-seat car</option>
                            <option value="Slot 4">20-seat car</option>
                            <option value="Slot 5">36-seat car</option>
                            <option value="Slot 6">54-seat car</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="parkingType" class="form-label">Parking Spot:</label>
                        <select id="parkingType" name="parkingType" class="form-select" required>
                            <option value="" disabled selected>Select a parking spot</option>
                            <option value="Outdoor">Outdoor</option>
                            <option value="Garage">Garage</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dateOfBooking" class="form-label">Booking Date:</label>
                        <input type="date" id="dateOfBooking" name="dateOfBooking" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address:</label>
                        <input type="text" id="address" name="address" class="form-control"
                            placeholder="Enter your address" required>
                    </div>

                    <!-- Slot Reservation System -->
                    <div class="container mt-5" id="slotReservationSystem" style="display: none;">
                        <h2>Slot Reservation System</h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Slot</th>
                                    <th scope="col">00:00-03:00</th>
                                    <th scope="col">03:00-06:00</th>
                                    <th scope="col">06:00-09:00</th>
                                    <th scope="col">09:00-12:00</th>
                                    <th scope="col">12:00-15:00</th>
                                    <th scope="col">15:00-18:00</th>
                                    <th scope="col">18:00-21:00</th>
                                    <th scope="col">21:00-24:00</th>
                                    <th scope="col">24:00-00:00</th>
                                </tr>
                            </thead>
                            <tbody id="slotTableBody">
                                <tr>
                                    <td id="slotName"></td>
                                    <td><input type="checkbox" name="selectedSlots" value="00:00-03:00"></td>
                                    <td><input type="checkbox" name="selectedSlots" value="03:00-06:00"></td>
                                    <td><input type="checkbox" name="selectedSlots" value="06:00-09:00"></td>
                                    <td><input type="checkbox" name="selectedSlots" value="09:00-12:00"></td>
                                    <td><input type="checkbox" name="selectedSlots" value="12:00-15:00"></td>
                                    <td><input type="checkbox" name="selectedSlots" value="15:00-18:00"></td>
                                    <td><input type="checkbox" name="selectedSlots" value="18:00-21:00"></td>
                                    <td><input type="checkbox" name="selectedSlots" value="21:00-24:00"></td>
                                    <td><input type="checkbox" name="selectedSlots" value="24:00-00:00"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="selectedSlots[]" id="selectedSlotsInput" value="">
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary" onclick="submitReservation()">Submit
                            Reservation</button>
                    </div>
                </form>
                <p class="text-center text-danger"><?php echo $message; ?></p>
            </div>
        </div>
    </div>
    

    <script>
        // This function generates the time slot table
        function generateSlotTable() {
            const slotTableBody = document.getElementById('slotTableBody');
            slotTableBody.innerHTML = ''; 

            const timeSlots = [
                '00:00-03:00', '03:00-06:00', '06:00-09:00', '09:00-12:00',
                '12:00-15:00', '15:00-18:00', '18:00-21:00', '21:00-24:00', '24:00-00:00'
            ];

            // Create a new row
            const row = document.createElement('tr');
            row.innerHTML = `<td id="slotName">${document.getElementById('carType').value}</td>`;
            
            // Create checkbox inputs for each time slot
            timeSlots.forEach(slot => {
                const cell = document.createElement('td');
                cell.innerHTML = `<input type="checkbox" name="selectedSlots" value="${slot}">`;
                row.appendChild(cell);
            });

            slotTableBody.appendChild(row);
        }

        document.getElementById('carType').addEventListener('change', function () {
            const selectedCarTypeValue = this.value;
            document.getElementById('slotName').textContent = selectedCarTypeValue; 
            generateSlotTable(); 
        });

        document.getElementById('dateOfBooking').addEventListener('change', function () {
            document.getElementById('slotReservationSystem').style.display = 'block';
            generateSlotTable();
        });

        function submitReservation() {
            const carType = document.getElementById('carType').value;
            const parkingType = document.getElementById('parkingType').value;
            const bookingDate = document.getElementById('dateOfBooking').value;
            const address = document.getElementById('address').value;
            const selectedSlots = Array.from(document.querySelectorAll('input[name="selectedSlots"]:checked'))
                .map(checkbox => checkbox.value);

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
                return; 
            }

            // If all information is valid, store the selected slots in a hidden input
            document.getElementById('selectedSlotsInput').value = selectedSlots.join(',');
            alert('Reservation successful!'); 
            document.getElementById('bookingForm').submit(); 
        }
    </script>

     <!-- Script for CSS
     <script src="javascript/bookslot.js"></script> -->
</body>

</html>
