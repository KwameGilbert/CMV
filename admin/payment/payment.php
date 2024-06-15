<?php
session_start();
include 'db_connect.php';

// Payment processing logic here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Payment</h1>
        <form action="payment.php" method="post">
            <!-- Payment form fields -->
            <button type="submit" class="button">Proceed to Payment</button>
        </form>
    </div>
</body>
</html>
