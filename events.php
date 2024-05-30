<?php
// Database connection
include 'database/db_connect.php';

// Fetch voting events
$sql = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);

// Store fetched events in an array
$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

// Close database connection
$conn->close();
?>

    <div class="container">
        <h1 class="header-title">Events</h1>
        <div class="search-container">
            <input type="text" id="search-input" class="search-input" placeholder="Search for events...">
        </div>
        <div class="cards" id="event-cards">
            <?php foreach ($events as $event): ?>
                <div class="card" data-event-name="<?= htmlspecialchars($event['event_name']) ?>">
                    <img src="includes/images/event_images/<?= htmlspecialchars($event['event_name']) ?>.jpg" alt="<?= htmlspecialchars($event['event_name']) ?>" class="card-img">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($event['event_name']) ?></h5>
                        <p><strong><?= htmlspecialchars($event['event_host']) ?></strong></p>
                        <!--<p><strong>Date:</strong> <?= htmlspecialchars($event['event_date']) ?></p>-->
                        <!--<p><?= htmlspecialchars($event['description']) ?></p>-->
                        <a href="categories.php?event_id=<?= htmlspecialchars($event['event_id']) ?>" class="card-link">Select Event</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        document.getElementById('search-input').addEventListener('input', function() {
            var searchQuery = this.value.toLowerCase();
            var eventCards = document.querySelectorAll('.card');

            eventCards.forEach(function(card) {
                var eventName = card.getAttribute('data-event-name').toLowerCase();
                if (eventName.includes(searchQuery)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
