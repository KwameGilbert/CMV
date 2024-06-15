<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
include 'db_connect.php';

$id = $_GET['id'];
$sql = "SELECT * FROM payments WHERE payment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>View Payment</h1>
        <table>
            <tr>
                <th>ID</th>
                <td><?= htmlspecialchars($payment['payment_id']); ?></td>
            </tr>
            <tr>
                <th>User</th>
                <td><?= htmlspecialchars($payment['user_id']); ?></td>
            </tr>
            <tr>
                <th>Amount</th>
                <td><?= htmlspecialchars($payment['amount']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= htmlspecialchars($payment['status']); ?></td>
            </tr>
            <tr>
                <th>Transaction Date</th>
                <td><?= htmlspecialchars($payment['transaction_date']); ?></td>
            </tr>
        </table>
        <a href="manage_payments.php" class="button">Back to Payments</a>
    </div>
</body>
</html>
