<?php
include 'database/db_connect.php';
$category_id = intval($_GET['category_id']);
$sql = "
    SELECT ct.contestant_id, ct.contestant_name, COALESCE(v.total_votes, 0) AS votes
    FROM contestants ct
    LEFT JOIN (
        SELECT contestant_id, SUM(votes) AS total_votes
        FROM votes
        GROUP BY contestant_id
    ) v ON ct.contestant_id = v.contestant_id
    WHERE ct.category_id = ?
    ORDER BY ct.contestant_name";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $category_id);
$stmt->execute();
$result = $stmt->get_result();
$results = [];
while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}
$stmt->close();
$conn->close();
header('Content-Type: application/json');
echo json_encode($results);
?>
