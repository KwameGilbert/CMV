<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
include 'db_connect.php';

$id = $_GET['id'];
$sql = "DELETE FROM payments WHERE payment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();

header("Location: manage_payments.php");
exit;
?>
