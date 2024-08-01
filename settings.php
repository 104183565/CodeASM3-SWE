<?php
// Kết nối với database
$host = "localhost";
$user = "root";
$pswd = "";
$dbnm = "test";

$conn = new mysqli($host, $user, $pswd, $dbnm);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}