<?php
//add_contestant.php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../sign-in/");
    exit;
}
include '../../database/db_connect.php';

// Fetch events to populate the dropdown
$sql = "SELECT * FROM events";
$events_result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contestant</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Contestant</h1>
        <form action="process_add_contestant.php" method="POST">
            <div class="form-group">
                <label for="contestant_name">Contestant Name:</label>
                <input type="text" id="contestant_name" name="contestant_name" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id" required>
                    <?php while ($row = $categories_result->fetch_assoc()): ?>
                        <option value="<?= $row['category_id']; ?>"><?= htmlspecialchars($row['category_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="event_id">Event:</label>
                <select id="event_id" name="event_id" required>
                    <?php while ($row = $events_result->fetch_assoc()): ?>
                        <option value="<?= $row['event_id']; ?>"><?= htmlspecialchars($row['event_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="button">Add Contestant</button>
        </form>
    </div>
</body>
</html>
