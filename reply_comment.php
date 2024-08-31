<?php
session_start();
require 'database.php';

if (isset($_POST['comment_id']) && isset($_SESSION['user_id']) && isset($_POST['reply'])) {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['user_id'];
    $reply = $_POST['reply'];

    $sql = "INSERT INTO replies (comment_id, user_id, reply, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $comment_id, $user_id, $reply);
    $stmt->execute();

    header("Location: other_post.php");
    exit();
}
?>
