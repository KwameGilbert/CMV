<?php
// update_contestant.php

include '../../database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contestant_id = $_POST['contestant_id'];
    $contestant_name = $_POST['contestant_name'];
    $category_id = $_POST['category_id'];

    // Update the contestant's details
    $sql = "UPDATE contestants SET contestant_name = ?, category_id = ? WHERE contestant_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sii', $contestant_name, $category_id, $contestant_id);
        $stmt->execute();
        $stmt->close();
        header("Location: contestants.php");
        exit;
    } else {
        die("Database update failed: " . $conn->error);
    }
} else {
    die("Invalid request method.");
}

$conn->close();
?>
