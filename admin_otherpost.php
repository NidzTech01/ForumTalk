<?php
session_start();

// Check if user is logged in
if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $username = $user['username'];
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="other_post.css">
    
    <title>Welcome: <?php echo htmlspecialchars($username); ?></title>
    
</head>
<body>
    <header>
        <label>Admin Dashboard</label>
        
        <nav class="nav">
            <a href="admin_profile.php" class="btn btn-warning">Profile</a>
            <a href="admindashboard.php" class="btn btn-warning">Approval Page</a>
            <a href="user_list.php" class="btn btn-warning">User List</a>
            <a href="index-chat.php" class="btn btn-warning">Channel</a>
            <a href="logout.php" class="btn btn-warning">Log-Out</a>
        
        </nav>
    </header>

    <div class = "main-profile">
                
    <div class="profile">
   
    
    
    </div>
        <h1> <?php echo htmlspecialchars($username); ?></h1>
        
        <nav>
            
            <li><a href="admin_otherpost.php"><button>Post</button></a></li>
            <li><a href="admin_editprofile.php"><button>Edit Profile</button></a></li>
            <li><a href="admin_profile.php"><button>Create a Post</button></a></li>
        </nav> 
        
    </div>

    <div class="other-post">
    <?php
require 'database.php';

// Fetch all posts and their details
$sql = "SELECT posts.id, posts.title, posts.description, posts.image, posts.created_at, users.username,
            (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count
        FROM posts
        JOIN users ON posts.user_id = users.id
        WHERE posts.approved = TRUE
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<div class='post'>";
    echo "<h2>" . $row['title'] . "</h2>";
    echo "<p class='post-meta'>Posted by: " . $row['username'] . " on " . $row['created_at'] . "</p>";
    echo "<p>" . $row['description'] . "</p>";
    if ($row['image']) {
        echo "<img src='" . $row['image'] . "' alt='Post Image' class='post-image'>";
    }
    echo "<p>Likes: " . $row['like_count'] . "</p>";
    echo "<p>Comments: " . $row['comment_count'] . "</p>";

    echo "<form action='like_post.php' method='post'>
            <input type='hidden' name='post_id' value='" . $row['id'] . "'>
            <button type='submit'>Like</button>
        </form>";

    echo "<form action='comment_post.php' method='post'>
            <input type='hidden' name='post_id' value='" . $row['id'] . "'>
            <textarea name='comment' placeholder='Comment' required></textarea>
            <button type='submit'>Comment</button>
        </form>";

    echo "<form action='report_post.php' method='post'>
            <input type='hidden' name='post_id' value='" . $row['id'] . "'>
            <button type='submit' class='report-button'>Report</button>
        </form>";

    // Fetch comments
    $commentSql = "SELECT comments.id as comment_id, comments.comment, users.username, comments.created_at 
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE comments.post_id = ? 
                ORDER BY comments.created_at ASC";
    $stmt = $conn->prepare($commentSql);
    $stmt->bind_param("i", $row['id']);
    $stmt->execute();
    $commentsResult = $stmt->get_result();

    if ($commentsResult->num_rows > 0) {
        echo "<div class='comments'>";
        while ($commentRow = $commentsResult->fetch_assoc()) {
            echo "<div class='comment'>";
            echo "<p><strong>" . $commentRow['username'] . ":</strong> " . $commentRow['comment'] . " <em>on " . $commentRow['created_at'] . "</em></p>";
            echo "<form action='reply_comment.php' method='post' class='reply-form'>
                    <input type='hidden' name='comment_id' value='" . $commentRow['comment_id'] . "'>
                    <textarea name='reply' placeholder='Reply' required></textarea>
                    <button type='submit'>Reply</button>
                </form>";

            // Fetch replies
            $replySql = "SELECT replies.id, replies.reply, replies.created_at, users.username
                        FROM replies
                        JOIN users ON replies.user_id = users.id
                        WHERE replies.comment_id = ?
                        ORDER BY replies.created_at ASC";
            $replyStmt = $conn->prepare($replySql);
            $replyStmt->bind_param("i", $commentRow['comment_id']);
            $replyStmt->execute();
            $repliesResult = $replyStmt->get_result();

            if ($repliesResult->num_rows > 0) {
                echo "<div class='replies'>";
                while ($replyRow = $repliesResult->fetch_assoc()) {
                    echo "<div class='reply'>";
                    echo "<p><strong>" . $replyRow['username'] . ":</strong> " . $replyRow['reply'] . " <em>on " . $replyRow['created_at'] . "</em></p>";
                    echo "</div>";
                }
                echo "</div>";
            }

            echo "</div>";
        }
        echo "</div>";
    }

    echo "<hr>";
    echo "</div>";
}

$conn->close();
?>

</div>
