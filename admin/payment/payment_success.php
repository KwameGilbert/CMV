<?php
session_start();
include 'db_connect.php';

// Payment success logic here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Payment Success</h1>
        <p>Thank you for your payment! Your transaction has been completed successfully.</p>
        <a href="index.php" class="button">Go to Home</a>
    </div>
</body>
</html>
