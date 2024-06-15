<?php
session_start();
include '../../database/db_connect.php';';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_email = $_POST['username_email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM admin WHERE (username = ? OR email = ?) AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $username_email, $username_email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $host = $result->fetch_assoc();
        $_SESSION['admin_id'] = $host['admin_id'];
        header("Location: ../dashboard.php");
    } else {
        echo "Invalid credentials. Please try again.";
    }
    $stmt->close();
}
$conn->close();
?>