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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $contestant_id = $_POST['contestant_id'];
    $votes = $_POST['votes'];

    // Fetch the cost per vote for the category
    $category_sql = "SELECT cost_per_vote FROM categories WHERE category_id = ?";
    $category_stmt = $conn->prepare($category_sql);
    $category_stmt->bind_param('i', $category_id);
    $category_stmt->execute();
    $category_result = $category_stmt->get_result();
    $category = $category_result->fetch_assoc();

    $cost_per_vote = $category['cost_per_vote'];
    $total_cost = $votes * $cost_per_vote;

    // Insert the vote
    $sql = "INSERT INTO votes (category_id, contestant_id, votes, total_cost) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiid', $category_id, $contestant_id, $votes, $total_cost);
    if ($stmt->execute()) {
        $success = "Your vote has been cast successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }
}

// Fetch contestants for the dropdown
$contestants_sql = "SELECT contestant_id, contestant_name FROM contestants WHERE event_id = ?";
$contestants_stmt = $conn->prepare($contestants_sql);
$contestants_stmt->bind_param('i', $event_id);
$contestants_stmt->execute();
$contestants_result = $contestants_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Vote</h1>
        <?php if (isset($error)): ?>
        <p class="error"><?= $error; ?></p>
        <?php elseif (isset($success)): ?>
        <p class="success"><?= $success; ?></p>
        <?php endif; ?>
        <form action="vote.php?event_id=<?= $event_id; ?>" method="post">
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <?php while ($category = $categories_result->fetch_assoc()): ?>
                <option value="<?= $category['category_id']; ?>"><?= htmlspecialchars($category['category_name']); ?></option>
                <?php endwhile; ?>
            </select>
            
            <label for="contestant_id">Contestant:</label>
            <select id="contestant_id" name="contestant_id" required>
                <?php while ($contestant = $contestants_result->fetch_assoc()): ?>
                <option value="<?= $contestant['contestant_id']; ?>"><?= htmlspecialchars($contestant['contestant_name']); ?></option>
                <?php endwhile; ?>
            </select>
            
            <label for="votes">Number of Votes:</label>
            <input type="number" id="votes" name="votes" required>
            
            <button type="submit" class="button">Vote</button>
        </form>
    </div>
</body>
</html>
