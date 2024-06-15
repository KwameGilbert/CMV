<?php
session_start();
// Dummy authentication check
if (!isset($_SESSION['admin_id'])) {
    header("Location: sign-in/");
    exit;
}
include '../database/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <?php include 'sidebar.html' ?>
    <?php include 'header.html' ?>
    <?php 
</body>
</html>
