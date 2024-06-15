<?php
// edit_contestants.php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../sign-in/");
    exit;
}
include '../../database/db_connect.php';

$contestant_id = $_GET['id'];
if (!isset($contestant_id)) {
    die('No contestant ID provided.');
}

// Fetch contestant details
$sql = "SELECT * FROM contestants WHERE contestant_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $contestant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $contestant = $result->fetch_assoc();
    $stmt->close();
} else {
    die("Database query failed: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contestant_name = $_POST['contestant_name'];
    $category_id = $_POST['category_id'];

    // Update the contestant's details
    $sql = "UPDATE contestants SET contestant_name = ?, category_id = ? WHERE contestant_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sii', $contestant_name, $category_id, $contestant_id);
        if ($stmt->execute()) {
            header("Location: contestants.php");
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        die("Database update failed: " . $conn->error);
    }
}

// Fetch events and categories
$events_sql = "SELECT event_id, event_name FROM events";
$events_result = $conn->query($events_sql);
if (!$events_result) {
    die("Database query failed: " . $conn->error);
}

$categories_sql = "SELECT categories.category_id, categories.category_name, events.event_id 
                   FROM categories 
                   JOIN events ON categories.event_id = events.event_id";
$categories_result = $conn->query($categories_sql);
if (!$categories_result) {
    die("Database query failed: " . $conn->error);
}

// Get the event_id of the current category
$current_category_sql = "SELECT event_id FROM categories WHERE category_id = ?";
$current_category_stmt = $conn->prepare($current_category_sql);
if ($current_category_stmt) {
    $current_category_stmt->bind_param('i', $contestant['category_id']);
    $current_category_stmt->execute();
    $current_category_result = $current_category_stmt->get_result();
    $current_category = $current_category_result->fetch_assoc();
    $current_event_id = $current_category['event_id'];
    $current_category_stmt->close();
} else {
    die("Database query failed: " . $conn->error);
}

$conn->close();
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

        <?php if (isset($error)): ?>
            <p class="error">
                <?= $error; ?>
            </p>
        <?php endif; ?>

        <form method="post" action="edit_contestant.php">
            <input type="hidden" name="contestant_id" value="<?= htmlspecialchars($contestant['contestant_id']); ?>">

            <label for="contestant_name">Contestant Name:</label>
            <input type="text" id="contestant_name" name="contestant_name" value="<?= htmlspecialchars($contestant['contestant_name']); ?>" required><br>

            <label for="event_id">Event:</label>
            <select id="event_id" name="event_id" required onchange="updateCategories(this.value)">
                <?php while ($row = $events_result->fetch_assoc()): ?>
                    <option value="<?= $row['event_id']; ?>" <?= ($row['event_id'] == $current_event_id) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($row['event_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br>

            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <?php 
                $categories_result->data_seek(0);
                while ($row = $categories_result->fetch_assoc()): 
                ?>
                    <option value="<?= $row['category_id']; ?>" <?= ($row['category_id'] == $contestant['category_id']) ? 'selected' : ''; ?> data-event-id="<?= $row['event_id']; ?>">
                        <?= htmlspecialchars($row['category_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br>

            <button type="submit">Update Contestant</button>
        </form>
    </div>

    <script>
        function updateCategories(eventId) {
            const categorySelect = document.getElementById('category_id');
            const categories = categorySelect.querySelectorAll('option');
            categories.forEach(category => {
                category.style.display = category.getAttribute('data-event-id') == eventId ? 'block' : 'none';
            });

            // If no category is currently selected, select the first visible one
            const selectedCategory = categorySelect.querySelector('option[selected]');
            if (!selectedCategory || selectedCategory.style.display === 'none') {
                for (let i = 0; i < categories.length; i++) {
                    if (categories[i].style.display === 'block') {
                        categories[i].selected = true;
                        break;
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const currentEventId = document.getElementById('event_id').value;
            updateCategories(currentEventId);
        });
    </script>
</body>
</html>
