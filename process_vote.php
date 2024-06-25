<?php
// process_vote.php

include 'database/db_connect.php';

// Get form data
$contestant_id = $_POST['contestant_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$votes = $_POST['votes'];
$reference = $_POST['reference']; // Assuming reference is generated in the frontend

// Calculate total amount
$cost_per_vote_sql = "SELECT cost_per_vote FROM events WHERE event_id = (SELECT event_id FROM categories WHERE category_id = (SELECT category_id FROM contestants WHERE contestant_id = ?))";
$event_stmt = $conn->prepare($cost_per_vote_sql);
$event_stmt->bind_param('s', $contestant_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
$event = $event_result->fetch_assoc();
$cost_per_vote = $event['cost_per_vote'];
$total_amount = $votes * $cost_per_vote;

try {
    // Insert vote record into the database with status "pending"
    $stmt = $conn->prepare("INSERT INTO votes (contestant_id, first_name, last_name, email, votes, amount, status, reference) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)");
    $stmt->bind_param('ssssids', $contestant_id, $first_name, $last_name, $email, $votes, $total_amount, $reference);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'reference' => $reference,
        'amount' => ($total_amount * 100) * 1.02, // Paystack expects amount in kobo
        'email' => $email
    ];
    echo json_encode($response);
} catch (Exception $e) {
    $response = ['status' => 'database_error', 'message' => $e->getMessage()];
    echo json_encode($response);
}
$conn->close();
?>
