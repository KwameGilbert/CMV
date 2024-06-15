<?php
// contestants.php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../sign-in/");
    exit;
}
include '../../database/db_connect.php';

// Fetch all contestants with event details
$sql = "SELECT contestants.*, categories.category_name, events.event_name 
        FROM contestants
        JOIN categories ON contestants.category_id = categories.category_id
        JOIN events ON categories.event_id = events.event_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contestants</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container">
        <h1>Manage Contestants</h1>
        <a href="add_contestant.php" class="button">Add Contestant</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Contestant Name</th>
                    <th>Category</th>
                    <th>Event</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['contestant_id']); ?></td>
                    <td><?= htmlspecialchars($row['contestant_name']); ?></td>
                    <td><?= htmlspecialchars($row['category_name']); ?></td>
                    <td><?= htmlspecialchars($row['event_name']); ?></td>
                    <td>
                        <a href="edit_contestant.php?id=<?= $row['contestant_id']; ?>" class="button">Edit</a>
                        <a href="delete_contestant.php?id=<?= $row['contestant_id']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this contestant?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
