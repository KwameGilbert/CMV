<?php
$event_id = $_GET['event_id']; // Get the event ID from the URL
// Database connection
include 'database/db_connect.php';
//Fetch event name from based on event idate
$event_sql = "SELECT event_name FROM events WHERE event_id = ?";
$event_stmt = $conn->prepare($event_sql);
$event_stmt->bind_param('i', $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
$event = $event_result->fetch_assoc();
// Fetch categories based on event
$sql = "SELECT * FROM categories WHERE event_id = ?";
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
    <title>Categories</title>
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/banner.css">
    <link rel="stylesheet" href="styles/event & categories.css">
    <link rel="stylesheet" href="styles/footer.css">
</head>
<body>
<?php include 'header.php' ?> 
<?php include 'banner.html' ?>
    <div class="container">
    <a href="index.php" class="back-button">Back</a>
        <h1 class="header-title">Select a Category</h1>
        <div class="cards">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                <?php
            // Path to category image
            $categoryImage = 'includes/images/category_images/' . $row['category_name'] . '.jpg';
            // Path to event image
            $eventImage = 'includes/images/event_images/' . $event['event_name'] . '.jpg';
            // Check if category image exists, if not use event image
            if (!file_exists($categoryImage)) {
                $categoryImage = $eventImage;
            }
            ?>
                    <img src="<?= $categoryImage ?>" alt="<?= $row['category_name'] ?>" class="card-img">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['category_name'] ?></h5>
                        <a href="contestants.php?category_id=<?= $row['category_id'] ?>&event_id=<?= $event_id ?>" class="card-link">View Contestants</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php include 'footer.php' ?>
</body>
</html>
<?php
$conn->close();
?>
