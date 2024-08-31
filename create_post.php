<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$image = $_FILES['image'];

$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}
$target_file = $target_dir . basename($image["name"]);

if (move_uploaded_file($image["tmp_name"], $target_file)) {
    $sql = "INSERT INTO posts (user_id, title, description, image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isss', $user_id, $title, $description, $target_file);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script type='text/javascript'>
                    alert('Post Submitted to Admin, Pending for Approval');
                    window.location.href = 'userdashboard.php'; // Redirect to user dashboard
                  </script>";
        
    } else {
        echo "Failed to create post.";
    }

    $stmt->close();
} else {
    echo "Failed to upload image.";
}

$conn->close();
?>
