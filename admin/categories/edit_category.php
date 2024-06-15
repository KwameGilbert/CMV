<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../sign-in/");
    exit;
}
include '../../database/db_connect.php';

// Fetch events to populate the dropdown
$sql = "SELECT * FROM events";
$events_result = $conn->query($sql);

// Fetch contestant details based on ID
if (isset($_GET['id'])) {
    $contestant_id = $_GET['id'];
    $sql = "SELECT * FROM contestants WHERE contestant_id = $contestant_id";
    $result = $conn->query($sql);
    $contestant = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contestant</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Contestant</h1>
        <form action="process_edit_contestant.php" method="POST">
            <input type="hidden" name="contestant_id" value="<?= $contestant['contestant_id']; ?>">
            <div class="form-group">
                <label for="contestant_name">Contestant Name:</label>
                <input type="text" id="contestant_name" name="contestant_name" value="<?= htmlspecialchars($contestant['contestant_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id" required>
                    <?php while ($row = $categories_result->fetch_assoc()): ?>
                        <option value="<?= $row['category_id']; ?>" <?= ($row['category_id'] == $contestant['category_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($row['category_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="event_id">Event:</label>
                <select id="event_id" name="event_id" required>
                    <?php while ($row = $events_result->fetch_assoc()): ?>
                        <option value="<?= $row['event_id']; ?>" <?= ($row['event_id'] == $contestant['event_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($row['event_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="button">Save Changes</button>
        </form>
    </div>
</body>
</html>
