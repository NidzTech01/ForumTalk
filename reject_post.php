<?php
require 'database.php';

$post_id = $_POST['post_id'];

$sql = "DELETE FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $post_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Post rejected.";
} else {
    echo "Failed to reject post.";
}

$stmt->close();
$conn->close();
?>
