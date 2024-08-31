<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="dashboard.css">
    
    <title>BeanTalk | Events</title>

    
</head>
<body>
    
    <header>
        <label>User Dashboard</label>
        
        <nav class="nav">
        <a href="userdashboard.php" class="btn btn-warning">Profile</a>
        <a href="view_events.php" class="btn btn-warning">Events</a>
        <a href="logout.php" class="btn btn-warning">Log-Out</a>
        
        </nav>
    </header>


    <?php
session_start();
require 'database.php';

$sql = "SELECT * FROM events WHERE expiration_date > NOW() ORDER BY created_at DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<h2>" . $row['title'] . "</h2>";
    echo "<p>Posted on: " . $row['created_at'] . "</p>";
    echo "<p>" . $row['description'] . "</p>";
    echo "<img src='uploads/" . $row['image'] . "' alt='Event Image' width='300'>";
    echo "<p>Category: " . $row['category'] . "</p>";
    echo "<p>Expires on: " . $row['expiration_date'] . "</p>";

    // Voting form
    echo "<form action='vote_event.php' method='post'>
            <input type='hidden' name='event_id' value='" . $row['id'] . "'>
            <button type='submit'>Vote</button>
          </form>";

    // Comment form
    echo "<form action='comment_event.php' method='post'>
            <input type='hidden' name='event_id' value='" . $row['id'] . "'>
            <textarea name='comment' required></textarea>
            <button type='submit'>Comment</button>
          </form>";

    // Display comments
    $sql_comments = "SELECT comments.comment, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.event_id = ?";
    $stmt_comments = $conn->prepare($sql_comments);
    $stmt_comments->bind_param('i', $row['id']);
    $stmt_comments->execute();
    $result_comments = $stmt_comments->get_result();

    while ($comment_row = $result_comments->fetch_assoc()) {
        echo "<p>" . $comment_row['username'] . ": " . $comment_row['comment'] . "</p>";
    }

    echo "<hr>";
}

$conn->close();
?>



        
</body>
</html>



