<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
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
    <title>Smart Parking System - Home</title>

    <!-- Bootstrap CSS & JS  -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Script for CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div id="homePage">
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
                            <a class="nav-link" href="bookslot.php">Book Slot</a>
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

        <div class="main-container mt-4">
            <div class="info-section text-center mb-4">
                <h2>Welcome to the Smart Parking System</h2>
                <p>The Smart Parking System helps you easily find and reserve parking spots quickly and efficiently.
                    With a user-friendly interface, users can easily manage their parking spots.</p>
            </div>

            <div class="info-section mb-4">
                <h3>How to Use the System</h3>
                <p>
                    The system is developed with an easy-to-use interface. Here are some tips for using the main pages
                    of the application:
                </p>
                <ul>
                    <li>Go to the 'Book Slot' section to fill out the parking reservation form.</li>
                    <li>Go to the "Invoice" section to see the information you filled out in the "Booking Slot" section
                        previously.</li>
                    <li>Go to the "Receipt" section to see all the information you confirmed.</li>
                    <li>Access the 'History' section to review the booking history on the system.</li>
                    <li>If you don't have an account, you can easily sign up from the 'Sign Up' page.</li>
                </ul>
            </div>

            <div class="info-section">
                <h3>About the Author</h3>
                <p>
                    This project was created as part of our SWE30003 course assignment for group 2. With the use of a
                    smart parking system applied to real life for users.
                </p>
                <p>
                    <strong>Course:</strong> SWE30003 - Software Architectures and Design
                    <br>
                    <strong>Assignment:</strong> 3 - Object Design Implementation
                    <br>
                    <strong>Team:</strong> 2
                    <br>
                    <strong>Members:</strong> <br>
                    - Nguyen Dinh Tan Dang <br>
                    - Vu Minh Quang <br>
                    - Pham Tuan Minh <br>
                    - Vu Minh Duc
                </p>
            </div>
        </div>
    </div>
</body>

</html>