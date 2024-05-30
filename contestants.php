<?php
$category_id = $_GET['category_id'];
$event_id = $_GET['event_id'];
// Database connection
include 'database/db_connect.php';
// Fetch contestants based on category
$sql = "SELECT * FROM contestants WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $category_id);
$stmt->execute();
$contestants_result = $stmt->get_result(); // Use a different variable to store contestants result
// Fetch category name based on category id passed
$sql = "SELECT category_name FROM categories WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $category_id);
$stmt->execute();
$category_result = $stmt->get_result(); // Use a different variable to store category result
$category_row = $category_result->fetch_assoc(); // Fetch the category name into a new variable
// Fetch event name from based on event idate
$event_sql = "SELECT event_name FROM events WHERE event_id = ?";
$event_stmt = $conn->prepare($event_sql);
$event_stmt->bind_param('i', $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
$event = $event_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contestants</title>
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/banner.css">
    <link rel="stylesheet" href="styles/contestants.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/modal.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'banner.html'; ?>
    <div class="container">
        <div class="header-buttons">
            <a href="categories.php?event_id=<?= $event_id ?>" class="back-button">Back</a>
            <button class="results-button" id="show-results" data-category-id="<?= $category_id ?>">Results</button>
        </div>
        <center>
            <h2 class="header-title">Contestants for <?= htmlspecialchars($category_row['category_name']); ?></h2>
        </center>
        <div class="cards">
            <?php while ($row = $contestants_result->fetch_assoc()): ?>
            <div class="card">
                <?php
            //Path to contestants image
            $contestantImage = 'includes/images/contestant_images/' . $row['contestant_name'] . '.jpg';
            // Path to category image
            $categoryImage = 'includes/images/category_images/' . $category_row['category_name'] . '.jpg';
            // Path to event image
            $eventImage = 'includes/images/event_images/' . $event['event_name'] . '.jpg';
            // Check if category image exists, if not use event image
            if (!file_exists($contestantImage)) {
                $contestantImage = $categoryImage;
                if (!file_exists($categoryImage)) {
                    $contestantImage = $eventImage;
                }
            }
            ?>
                <img src="<?= $contestantImage ?>" alt="<?= htmlspecialchars($row['contestant_name']); ?>"
                    class="card-img">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['contestant_name']); ?></h5>
                    <a href="vote.php?contestant_id=<?= htmlspecialchars($row['contestant_id']); ?>"
                        class="card-link">Vote</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <div id="results-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Results</h2>
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Contestant ID</th>
                        <th>Contestant Name</th>
                        <th>Votes</th>
                    </tr>
                </thead>
                <tbody id="results-body">
                    <!-- Results will be injected here by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="js/modal.js"></script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
