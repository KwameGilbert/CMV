<?php
//vote.php
$contestant_id = $_GET['contestant_id'];

// Database connection
include 'database/db_connect.php';

// Fetch contestant details
$sql = "SELECT contestants.*, categories.category_name FROM contestants 
        JOIN categories ON contestants.category_id = categories.category_id 
        WHERE contestant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $contestant_id);
$stmt->execute();
$result = $stmt->get_result();
$contestant = $result->fetch_assoc();

// Fetch cost per vote
$cost_per_vote_sql = "SELECT cost_per_vote FROM events WHERE event_id = (SELECT event_id FROM categories WHERE category_id = (SELECT category_id FROM contestants WHERE contestant_id = ?))";
$event_stmt = $conn->prepare($cost_per_vote_sql);
$event_stmt->bind_param('s', $contestant_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
$event = $event_result->fetch_assoc();
$cost_per_vote = $event['cost_per_vote'];

// Fetch event name based on event id
$event_sql = "SELECT event_name, event_id FROM events WHERE event_id = (SELECT event_id FROM categories WHERE category_id = (SELECT category_id FROM contestants WHERE contestant_id = ?))";
$event_stmt = $conn->prepare($event_sql);
$event_stmt->bind_param('s', $contestant_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
$event = $event_result->fetch_assoc();
$event_name = $event['event_name'];

// Fetch categories based on event
$sql = "SELECT * FROM categories WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $event['event_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/vote.css">
    <link rel="stylesheet" href="styles/footer.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">

        <div class="contestant-details">
            <h3>Vote for <?= htmlspecialchars($contestant['contestant_name']); ?></h3>
            <?php 
            $contestantImage = 'includes/images/contestant_images/' . $contestant['contestant_name'] . '.jpg';
            $categoryImage = 'includes/images/category_images/' . $contestant['category_name'] . '.jpg';
            $eventImage = 'includes/images/event_images/' . $event_name . '.jpg';
            
            // Check if contestant image exists, if not use category image, if not use event image
             // Check if category image exists, if not use event image
            if (!file_exists($contestantImage)) {
                $contestantImage = $categoryImage; 
            }
            if (!file_exists($categoryImage)) {
                $contestantImage = $eventImage;
            }
            ?>
            <img src="<?= htmlspecialchars($contestantImage); ?>" alt="<?= htmlspecialchars($contestant['contestant_name']); ?>" class="contestant-img">
            <div class="contestant-info">
                <h2><?= htmlspecialchars($contestant['contestant_name']); ?></h2>
                <p>Category: <?= htmlspecialchars($contestant['category_name']); ?></p>
            </div>
        </div>

        <form class="vote-form" id="paymentForm">
            <h2>Please fill vote form</h2>
            <input type="hidden" name="contestant_id" value="<?= htmlspecialchars($contestant_id); ?>">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="votes">Number of Votes (₵<?php echo $cost_per_vote; ?> per vote):</label>
                <input type="number" id="votes" name="votes" min="1" required
                    oninput="calculateTotal(<?php echo $cost_per_vote; ?>)" step="1" onkeydown="return event.keyCode !== 69 && event.keyCode !== 190 && event.keyCode !== 110">
            </div>
            <div class="form-submit">
                <h3>Total Amount: GH₵<span id="amount">0</span></h3>
                <button type="submit" class="btn" onclick="payWithPaystack(event)">Submit</button>
            </div>
        </form>
    </div>

    <!-- Success Notification box -->
    <div id="good_notification" class="good_notification">
        Successfully Voted
    </div>

    <!-- Bad Notification Box -->
    <div id="bad_notification" class="bad_notification">
        Error, something went wrong. Please try again or contact support.
    </div>

    <!-- Database Error Notification Box -->
    <div id="database_error_notification" class="database_error_notification">
        Database error. Please try again or contact support.
    </div>

    <!-- Transaction Error Notification Box -->
    <div id="transaction_error_notification" class="transaction_error_notification">
        Transaction verification failed. Please contact support.
    </div>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="js/payment.js"></script>
    <?php
    $stmt->close();
    $conn->close();
    ?>
    <?php include 'footer.php'; ?>

</body>
</html>
