<?php
session_start();
include 'db_connect.php';

// Payment failure logic here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failure</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Payment Failure</h1>
        <p>There was an issue with your payment. Please try again.</p>
        <a href="payment.php" class="button">Try Again</a>
    </div>
</body>
</html>
