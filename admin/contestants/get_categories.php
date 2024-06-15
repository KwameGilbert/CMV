<?php
//get


include '../../database/db_connect.php';

if(isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    
    // Fetch categories based on the provided event ID
    $sql = "SELECT * FROM categories WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Prepare an array to store the categories
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    // Return the categories as JSON data
    header('Content-Type: application/json');
    echo json_encode($categories);
} else {
    // If event_id is not provided, return an empty array
    echo json_encode([]);
}

// Close the database connection
$stmt->close();
$conn->close();
?>
