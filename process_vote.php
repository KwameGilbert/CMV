<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $contestant_id = $_POST['contestant_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $votes = $_POST['votes'];
    $reference = $_POST['reference'];

    // Database connection
    include 'database/db_connect.php';

    // Verify transaction with Paystack
    $url = 'https://api.paystack.co/transaction/verify/' . $reference;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Your secret key
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer sk_live_a212cee90c9ce7e37ae5179b098e5b813e0d6daf']);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response);
    if ($result && $result->data->status === 'success') {
        $conn->begin_transaction();

        try {
            // Insert the vote into the votes table
            $sql = "INSERT INTO votes (contestant_id, first_name, last_name, email, votes, reference) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssis', $contestant_id, $first_name, $last_name, $email, $votes, $reference);
            $stmt->execute();
            $stmt->close();
            
            // Update the votes count in the contestants table
            $sql = "UPDATE contestants SET votes = (SELECT SUM(votes) FROM votes WHERE contestant_id = ?) WHERE contestant_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $contestant_id, $contestant_id);
            $stmt->execute();
            $stmt->close();
            $conn->commit();

            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['status' => 'database_error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'transaction_error', 'message' => 'Transaction verification failed.']);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>