<?php

//host,php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: sign-in");
    exit;
}
include '../../database/db_connect.php';

$id = $_GET['id'];
$sql = "SELECT * FROM hosts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$host = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password_sql = ", password = '$password'";
    } else {
        $password_sql = "";
    }
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $company = $_POST['company'];
    $bio = $_POST['bio'];

    $sql = "UPDATE hosts SET username = ?, firstname = ?, lastname = ?, email = ?, phone_number = ?, address = ?, company = ?, bio = ? $password_sql WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssi', $username, $firstname, $lastname, $email, $phone_number, $address, $company, $bio, $id);
    if ($stmt->execute()) {
        header("Location: hosts.php");
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Host</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Host</h1>
        <?php if (isset($error)): ?>
        <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        <form action="edit_host.php?id=<?= $id; ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($host['username']); ?>" required>
            
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($host['firstname']); ?>">
            
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($host['lastname']); ?>">
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($host['email']); ?>" required>
            
            <label for="password">Password (leave blank to keep current):</label>
            <input type="password" id="password" name="password">
            
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($host['phone_number']); ?>">
            
            <label for="address">Address:</label>
            <textarea id="address" name="address"><?= htmlspecialchars($host['address']); ?></textarea>
            
            <label for="company">Company:</label>
            <input type="text" id="company" name="company" value="<?= htmlspecialchars($host['company']); ?>">
            
            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio"><?= htmlspecialchars($host['bio']); ?></textarea>
            
            <button type="submit" class="button">Update Host</button>
        </form>
    </div>
</body>
</html>
