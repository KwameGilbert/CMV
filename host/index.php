<?php
session_start();
if (!isset($_SESSION['host_id'])) {
    header("Location: ./sign-in/");
    exit;
}
include '../database/db_connect.php';
// Assume host ID is stored in session after login
$host_id = $_SESSION['host_id'];
// Fetch host's events
$events_sql = "SELECT event_id, event_name FROM events WHERE host_id = ?";
$events_stmt = $conn->prepare($events_sql);
$events_stmt->bind_param('i', $host_id);
$events_stmt->execute();
$events_result = $events_stmt->get_result();
// Fetch event statistics
$events = [];
while ($event = $events_result->fetch_assoc()) {
    $event_id = $event['event_id'];
    // Fetch number of categories
    $categories_sql = "SELECT COUNT(*) AS num_categories FROM categories WHERE event_id = ?";
    $categories_stmt = $conn->prepare($categories_sql);
    $categories_stmt->bind_param('i', $event_id);
    $categories_stmt->execute();
    $categories_result = $categories_stmt->get_result();
    $num_categories = $categories_result->fetch_assoc()['num_categories'];
    // Fetch total number of contestants
    $contestants_sql = "SELECT COUNT(*) AS num_contestants FROM contestants WHERE category_id IN (SELECT category_id FROM categories WHERE event_id = ?)";
    $contestants_stmt = $conn->prepare($contestants_sql);
    $contestants_stmt->bind_param('i', $event_id);
    $contestants_stmt->execute();
    $contestants_result = $contestants_stmt->get_result();
    $num_contestants = $contestants_result->fetch_assoc()['num_contestants'];
    // Add event details to array
    $events[] = [
        'event_id' => $event_id,
        'event_name' => $event['event_name'],
        'num_categories' => $num_categories,
        'num_contestants' => $num_contestants,
    ];
}
// Fetch voting statistics
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
$first_day_of_month = date('Y-m-01');
$votes_sql = "
    SELECT 
        SUM(CASE WHEN DATE(vote_date) = ? THEN votes ELSE 0 END) AS votes_today,
        SUM(CASE WHEN DATE(vote_date) = ? THEN votes ELSE 0 END) AS votes_yesterday,
        SUM(CASE WHEN DATE(vote_date) >= ? THEN votes ELSE 0 END) AS votes_this_month,
        SUM(votes) AS votes_total
    FROM votes
    WHERE contestant_id IN (SELECT contestant_id FROM contestants WHERE category_id IN (SELECT category_id FROM categories WHERE event_id IN (SELECT event_id FROM events WHERE host_id = ?)))
";
$votes_stmt = $conn->prepare($votes_sql);
$votes_stmt->bind_param('sssi', $today, $yesterday, $first_day_of_month, $host_id);
$votes_stmt->execute();
$votes_result = $votes_stmt->get_result();
$votes_stats = $votes_result->fetch_assoc();
// Fetch daily votes for the current month
$daily_votes_sql = "
    SELECT DATE(vote_date) AS vote_day, SUM(votes) AS daily_votes
    FROM votes
    WHERE contestant_id IN (SELECT contestant_id FROM contestants WHERE category_id IN (SELECT category_id FROM categories WHERE event_id IN (SELECT event_id FROM events WHERE host_id = ?)))
    AND vote_date >= ?
    GROUP BY DATE(vote_date)
    ORDER BY vote_day
";
$daily_votes_stmt = $conn->prepare($daily_votes_sql);
$daily_votes_stmt->bind_param('is', $host_id, $first_day_of_month);
$daily_votes_stmt->execute();
$daily_votes_result = $daily_votes_stmt->get_result();
$daily_votes = [];
while ($row = $daily_votes_result->fetch_assoc()) {
    $daily_votes[] = $row;
}
// Fetch all categories and votes for each contestant
$categories_sql = "
    SELECT 
        categories.category_id, 
        categories.category_name, 
        categories.cost_per_vote,
        contestants.contestant_id, 
        contestants.contestant_name, 
        COALESCE(SUM(votes.votes), 0) AS total_votes
    FROM categories
    LEFT JOIN contestants ON categories.category_id = contestants.category_id
    LEFT JOIN votes ON contestants.contestant_id = votes.contestant_id
    WHERE categories.event_id IN (SELECT event_id FROM events WHERE host_id = ?)
    GROUP BY categories.category_id, contestants.contestant_id
    ORDER BY categories.category_id, contestants.contestant_name
";
$categories_stmt = $conn->prepare($categories_sql);
$categories_stmt->bind_param('i', $host_id);
$categories_stmt->execute();
$categories_result = $categories_stmt->get_result();
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[$row['category_id']]['category_name'] = $row['category_name'];
    $categories[$row['category_id']]['cost_per_vote'] = $row['cost_per_vote'];
    $categories[$row['category_id']]['contestants'][] = [
        'contestant_id' => $row['contestant_id'],
        'contestant_name' => $row['contestant_name'],
        'total_votes' => $row['total_votes'],
        'equivalent_amount' => $row['total_votes'] * $row['cost_per_vote'],
    ];
}

// Fetch the current value of show_results from the events table
$sql = "SELECT show_results FROM events WHERE host_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $host_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$show_results = $row['show_results'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host Dashboard</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/header.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <br>
    <div class="container">
        <h1 class="header-title">Dashboard</h1>
        <?php foreach ($events as $event):?>
        <div class="event-demographics">
            <h3 class="event-title"><?= htmlspecialchars($event['event_name']); ?>:</h3>
            <p><b>Categories:</b><?= $event['num_categories']; ?></p>
            <p><b>Contestants:</b><?= $event['num_contestants']; ?></p>
        </div>

        <!--View Results Toggle Button-->
        <div class="toggle-container">
            <label for="show_results" class="show_results">Show Results : </label>
            <input type="checkbox" id="toggle" class="toggle-checkbox" onchange="updateDatabase(this)" <?php if ($show_results) echo 'checked'; ?>>
            <label for="toggle" class="toggle-label"></label>
        </div>

        <?php endforeach; ?>        
        <div class="stats">
            <div class="stat">
                <h4>Votes Today</h4>
                <p><?= $votes_stats['votes_today']; ?></p>
            </div>
            <div class="stat">
                <h4>Votes Yesterday</h4>
                <p><?= $votes_stats['votes_yesterday']; ?></p>
            </div>
            <div class="stat">
                <h4>Votes This Month</h4>
                <p><?= $votes_stats['votes_this_month']; ?></p>
            </div>
            <div class="stat">
                <h4>Total Votes</h4>
                <p><?= $votes_stats['votes_total']; ?></p>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="votesChart"></canvas>
        </div>
        <div class="categories-table">
            <?php foreach ($categories as $category_id => $category): ?>
            <div class="category">
                <h3 class="category-title"><?= htmlspecialchars($category['category_name']); ?> (Cost per vote:
                GH₵<?= number_format($category['cost_per_vote'], 2); ?>)</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Contestant ID</th>
                            <th>Contestant Name</th>
                            <th>Total Votes</th>
                            <th>Equivalent Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($category['contestants'] as $contestant): ?>
                        <tr>
                            <td><?= htmlspecialchars($contestant['contestant_id']); ?></td>
                            <td><?= htmlspecialchars($contestant['contestant_name']); ?></td>
                            <td><?= $contestant['total_votes']; ?></td>
                            <td>₵<?= number_format($contestant['equivalent_amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endforeach; ?> 
            <a href="logout.php">
                <button class="logout-button">Logout</button>
            </a>
        </div> 
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('votesChart').getContext('2d');
        var votesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($daily_votes, 'vote_day')); ?>,
                datasets: [{
                    label: 'Daily Votes',
                    data: <?= json_encode(array_column($daily_votes, 'daily_votes')); ?>,
                    backgroundColor: 'rgba(255, 165, 0, 0.2)',
                    borderColor: '#ffa500',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>
    <script>
        const eventId = <?php echo json_encode($event_id); ?>;
    </script>
    <script src="js/toggle.js"></script>
</body>
</html>