<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: sign-in");
    exit;
}
include '../../database/db_connect.php';

// Fetch categories
$sql = "SELECT categories.*, events.event_name FROM categories JOIN events ON categories.event_id = events.event_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Manage Categories</h1>
        <a href="add_category.php" class="button">Add Category</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Event</th>
                    <th>Cost per Vote</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['category_id']); ?></td>
                    <td><?= htmlspecialchars($row['category_name']); ?></td>
                    <td><?= htmlspecialchars($row['event_name']); ?></td>
                    <td><?= htmlspecialchars($row['cost_per_vote']); ?></td>
                    <td>
                        <a href="edit_category.php?id=<?= $row['category_id']; ?>" class="button">Edit</a>
                        <a href="delete_category.php?id=<?= $row['category_id']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
