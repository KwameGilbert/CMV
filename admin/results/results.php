<?php
session_start();
include 'db_connect.php';

$event_id = $_GET['event_id'];

// Fetch categories for the event
$sql = "SELECT * FROM categories WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $event_id);
$stmt->execute();
$categories_result = $stmt->get_result();

// Fetch votes for each category
$votes_sql = "SELECT contestants.contestant_name, categories.category_name, SUM(votes.votes) as total_votes
              FROM votes
              JOIN contestants ON votes.contestant_id = contestants.contestant_id
              JOIN categories ON votes.category_id = categories.category_id
              WHERE categories.event_id = ?
              GROUP BY votes.contestant_id, votes.category_id";
$votes_stmt = $conn->prepare($votes_sql);
$votes_stmt->bind_param('i', $event_id);
$votes_stmt->execute();
$votes_result = $votes_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Results</h1>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Contestant</th>
                    <th>Total Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $votes_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['category_name']); ?></td>
                    <td><?= htmlspecialchars($row['contestant_name']); ?></td>
                    <td><?= htmlspecialchars($row['total_votes']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
