<?php
session_start();
require 'database.php';

$event_id = $_POST['event_id'];
$user_id = $_SESSION['user_id'];
$comment = $_POST['comment'];

$sql = "INSERT INTO comments (event_id, user_id, comment) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iis', $event_id, $user_id, $comment);
$stmt->execute();

echo "Comment added successfully.";
?>
