<?php
session_start();
require 'database.php';

$event_id = $_POST['event_id'];
$user_id = $_SESSION['user_id'];

// Check if the user has already voted for this event
$sql = "SELECT * FROM votes WHERE event_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $event_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "You have already voted for this event.";
} else {
    // Insert a new vote if the user hasn't voted yet
    $sql = "INSERT INTO votes (event_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $event_id, $user_id);
    if ($stmt->execute()) {
        echo "Thank you for voting!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>
