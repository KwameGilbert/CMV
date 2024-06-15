<?php
// Database configuration
$servername = "sql309.infinityfree.com";
$username = "if0_36570237";
$password = "eUAxGwX2wakU5";
$dbname = "if0_36570237_voting_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>