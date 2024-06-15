<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: sign-in");
    exit;
}
include '../../database/db_connect.php';

$id = $_GET['id'];
$sql = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

// Fetch hosts for the dropdown
$hosts_sql = "SELECT id, username FROM hosts";
$hosts_result = $conn->query($hosts_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_host = $_POST['event_host'];
    $description = $_POST['description'];
    $host_id = $_POST['host_id'];

    $sql = "UPDATE events SET event_name = ?, event_date = ?, event_host = ?, description = ?, host_id = ? WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssii', $event_name, $event_date, $event_host, $description, $host_id, $id);
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
    <title>Edit Event</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Event</h1>
        <?php if (isset($error)): ?>
        <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        <form action="edit_event.php?id=<?= $id; ?>" method="post">
            <label for="event_name">Event Name:</label>
            <input type="text" id="event_name" name="event_name" value="<?= htmlspecialchars($event['event_name']); ?>" required>
            
            <label for="event_date">Event Date:</label>
            <input type="date" id="event_date" name="event_date" value="<?= htmlspecialchars($event['event_date']); ?>">
            
            <label for="event_host">Event Host:</label>
            <input type="text" id="event_host" name="event_host" value="<?= htmlspecialchars($event['event_host']); ?>">
            
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?= htmlspecialchars($event['description']); ?></textarea>
            
            <label for="host_id">Host:</label>
            <select id="host_id" name="host_id" required>
                <?php while ($host = $hosts_result->fetch_assoc()): ?>
                <option value="<?= $host['id']; ?>" <?= $host['id'] == $event['host_id'] ? 'selected' : ''; ?>><?= htmlspecialchars($host['username']); ?></option>
                <?php endwhile; ?>
            </select>
            
            <button type="submit" class="button">Update Event</button>
        </form>
    </div>
</body>
</html>
