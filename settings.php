<?php
// Connect to the database
$host = "localhost";
$user = "root";
$pswd = "";
$dbnm = "test";

$conn = new mysqli($host, $user, $pswd, $dbnm);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>