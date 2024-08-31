<?php
session_start();
require 'database.php';

if (isset($_POST['post_id']) && isset($_SESSION['user_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    // Check if user has already liked the post
    $checkSql = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Insert like
        $insertSql = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
    }

    header("Location: other_post.php");
    exit();
}
?>
