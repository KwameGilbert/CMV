<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: sign-in");
    exit;
}
include '../../database/db_connect.php';

// Fetch events
$sql = "SELECT events.*, hosts.username FROM events JOIN hosts ON events.host_id = hosts.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Manage Events</h1>
        <a href="add_event.php" class="button">Add Event</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Host</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['event_id']); ?></td>
                    <td><?= htmlspecialchars($row['event_name']); ?></td>
                    <td><?= htmlspecialchars($row['username']); ?></td>
                    <td><?= htmlspecialchars($row['event_date']); ?></td>
                    <td>
                        <a href="edit_event.php?id=<?= $row['event_id']; ?>" class="button">Edit</a>
                        <a href="delete_event.php?id=<?= $row['event_id']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
