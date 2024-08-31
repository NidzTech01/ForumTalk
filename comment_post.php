<?php
session_start();
require 'database.php';

if (isset($_POST['post_id']) && isset($_SESSION['user_id']) && isset($_POST['comment'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $comment = $_POST['comment'];

    $sql = "INSERT INTO comments (post_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $user_id, $comment);
    $stmt->execute();

    header("Location: other_post.php");
    exit();
}
?>
