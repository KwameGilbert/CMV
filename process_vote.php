<?php
//process_vote.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    //curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer sk_live_a212cee90c9ce7e37ae5179b098e5b813e0d6daf']);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer sk_test_b6d59ef887c4812d93e4c581727f75b45254824a']);
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response);
    if ($result && $result->data->status === 'success') {
        // Process the vote (e.g., update the database)
        $sql = "INSERT INTO votes (contestant_id, first_name, last_name, email, votes, reference) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssis', $contestant_id, $first_name, $last_name, $email, $votes, $reference);
        $insert_vote = $stmt->execute();

        //Add the vote to the contestants votes table
        // Prepare the SQL statement
        $stmt = $conn->prepare("UPDATE contestants SET votes = (SELECT SUM(votes) FROM votes WHERE contestant_id = ?) WHERE contestant_id = ?");

        // Bind the parameters
        $stmt->bind_param('ss', $contestant_id, $contestant_id);
        
        // Execute the statement
        $update_vote = $stmt->execute();
        if ($insert_vote && $update_vote ) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'database_error', 'message' => 'Database error.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'transaction_error', 'message' => 'Transaction verification failed.']);
    }
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>