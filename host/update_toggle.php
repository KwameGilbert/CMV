<?php
include '../database/db_connect.php'; // Include your database connection file

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Extract the toggle value and event_id
$show_results = $data['show_results'];
$event_id = $data['event_id'];

// Update the database with the new toggle value
$sql = "UPDATE events SET show_results = ? WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $show_results, $event_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$conn->close();
?>
