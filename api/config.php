<?php

$dbHost = "localhost";
$username = "root";
$password = "";
$dbName = "esprit";

// Create a new connection
$conn = new mysqli($dbHost, $username, $password, $dbName);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>