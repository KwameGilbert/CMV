<?php
$event_id = $_GET['event_id']; // Get the event ID from the URL
// Database connection
include 'database/db_connect.php';
// Fetch categories, contestants, votes, and calculate total amount
$sql = "
    SELECT c.category_name, ct.contestant_name, ct.contestant_id, c.cost_per_vote,
           COALESCE(v.total_votes, 0) AS total_votes,
           (COALESCE(v.total_votes, 0) * c.cost_per_vote) AS total_amount
    FROM categories c
    LEFT JOIN contestants ct ON c.category_id = ct.category_id
    LEFT JOIN (
        SELECT contestant_id, SUM(votes) AS total_votes
        FROM votes
        GROUP BY contestant_id
    ) v ON ct.contestant_id = v.contestant_id
    WHERE c.event_id = ?
    ORDER BY c.category_name, ct.contestant_name";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $event_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/banner.css">
    <link rel="stylesheet" href="styles/results.css">
    <link rel="stylesheet" href="styles/footer.css">
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'banner.html'; ?>
<div class="container">
    <a href="index.php" class="back-button">Back to Events</a>
    <h1 class="header-title">Event Results</h1>
    <table class="results-table">
        <thead>
            <tr>
                <th>Category</th>
                <th>Contestant</th>
                <th>Votes</th>
                <th>Cost per Vote</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $currentCategory = '';
            while ($row = $result->fetch_assoc()):
                // Display category name only when it changes
                if ($currentCategory != $row['category_name']) {
                    $currentCategory = $row['category_name'];
                    echo "<tr><td colspan='5' class='category-header'>{$currentCategory}</td></tr>";
                }
            ?>
                <tr>
                    <td><?= $row['category_name'] ?></td>
                    <td><?= $row['contestant_name'] ?></td>
                    <td><?= $row['total_votes'] ?></td>
                    <td><?= number_format($row['cost_per_vote'], 2) ?></td>
                    <td><?= number_format($row['total_amount'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
<?php
$conn->close();
?>