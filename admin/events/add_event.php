<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: sign-in");
    exit;
}
include '../../database/db_connect.php';

// Fetch hosts for the dropdown
$hosts_sql = "SELECT id, username FROM hosts";
$hosts_result = $conn->query($hosts_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_host = $_POST['event_host'];
    $description = $_POST['description'];
    $host_id = $_POST['host_id'];

    $sql = "INSERT INTO events (event_name, event_date, event_host, description, host_id)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $event_name, $event_date, $event_host, $description, $host_id);
    if ($stmt->execute()) {
        header("Location: events.php");
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
    <title>Add Event</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Event</h1>
        <?php if (isset($error)): ?>
        <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        <form action="add_event.php" method="post">
            <label for="event_name">Event Name:</label>
            <input type="text" id="event_name" name="event_name" required>
            
            <label for="event_date">Event Date:</label>
            <input type="date" id="event_date" name="event_date">
            
            <label for="event_host">Event Host:</label>
            <input type="text" id="event_host" name="event_host">
            
            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea>
            
            <label for="host_id">Host:</label>
            <select id="host_id" name="host_id" required>
                <?php while ($host = $hosts_result->fetch_assoc()): ?>
                <option value="<?= $host['id']; ?>"><?= htmlspecialchars($host['username']); ?></option>
                <?php endwhile; ?>
            </select>
            
            <button type="submit" class="button">Add Event</button>
        </form>
    </div>
</body>
</html>
