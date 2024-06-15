<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: sign-in");
    exit;
}
include '../../database/db_connect.php';

$id = $_GET['id'];
$sql = "DELETE FROM hosts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();

header("Location: hosts.php");
exit;
?>
