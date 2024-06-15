<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: sign-in");
    exit;
}
include '../../database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $company = $_POST['company'];
    $bio = $_POST['bio'];

    $sql = "INSERT INTO hosts (username, firstname, lastname, email, password, phone_number, address, company, bio)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssss', $username, $firstname, $lastname, $email, $password, $phone_number, $address, $company, $bio);
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
    <title>Add Host</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Host</h1>
        <?php if (isset($error)): ?>
        <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        <form action="add_host.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname">
            
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname">
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number">
            
            <label for="address">Address:</label>
            <textarea id="address" name="address"></textarea>
            
            <label for="company">Company:</label>
            <input type="text" id="company" name="company">
            
            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio"></textarea>
            
            <button type="submit" class="button">Add Host</button>
        </form>
    </div>
</body>
</html>
