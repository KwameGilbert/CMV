<?php

// Database connection details
$servername = "localhost";
$username = "gugyasoj_voting";
$password = "LZ3AhBq5TsNVHKWQCP49";
$dbname = "gugyasoj_voting";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON contents
$input = @file_get_contents("php://input");
$event = json_decode($input, true);

// Verify the event
if ($event['event'] == 'charge.success') {
    $reference = $event['data']['reference'];
    $amount = $event['data']['amount']; // amount in kobo
    $amountGHS = $amount / 100; // convert to GHS
    $phone = $event['data']['customer']['phone'];

    // Retrieve transaction details from your database using the reference
    $stmt = $conn->prepare("SELECT contestant_id, votes FROM transactions WHERE reference = ?");
    $stmt->bind_param("s", $reference);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $transaction = $result->fetch_assoc();
        $contestant_id = $transaction['contestant_id'];
        $votes = $transaction['votes'];

        // Insert into votes table
        $sql = "INSERT INTO votes (contestant_id, phone, votes, reference) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssis', $contestant_id, $phone, $votes, $reference);
        $stmt->execute();
        $stmt->close();

        // Update the votes count in the contestants table
        $sql = "UPDATE contestants SET votes = (SELECT SUM(votes) FROM votes WHERE contestant_id = ?) WHERE contestant_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $contestant_id, $contestant_id);
        $stmt->execute();
        $stmt->close();
    }
    $stmt->close();
}

$conn->close();

// Respond with 200 OK to acknowledge receipt of the webhook
http_response_code(200);
echo json_encode(["status" => "success"]);

?>