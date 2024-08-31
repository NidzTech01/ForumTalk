<?php
require 'database.php';

$post_id = $_POST['post_id'];

$sql = "UPDATE posts SET approved = TRUE WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $post_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Post approved.";
} else {
    echo "Failed to approve post.";
}

$stmt->close();
$conn->close();
?>
