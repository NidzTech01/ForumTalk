<?php
session_start();
require 'database.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $expiration_date = $_POST['expiration_date'];
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        // Insert the event details into the database
        $sql = "INSERT INTO events (title, description, image, category, expiration_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $title, $description, $image, $category, $expiration_date);
        $stmt->execute();

        // Send email notifications to all users
        $sql = "SELECT email FROM users";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            sendEmailNotification($row['email'], $title, $description);
        }

        echo "<script type='text/javascript'>
        alert('Event Created Sucessfully')
        </script>";
    } else {
        echo "Failed to upload image.";
    }
}

function sendEmailNotification($email, $title, $description) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com'; // Brevo SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = '7808b0001@smtp-brevo.com'; // Your Brevo SMTP username
        $mail->Password = 'xsmtpsib-d82dc99042b79f2fdd3fedc9244b88695909169f4895d878eda6e8a0cf509827-6ChA18Wc5TNwY4xp'; // Your Brevo SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('jhonmurcia143@gmail.com', 'BeanTalk Notifications'); // Your sender email and name
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Event: ' . $title;
        $mail->Body    = '<h1>' . $title . '</h1><p>' . $description . '</p><p>Please log in to vote on this event.</p>';
        $mail->AltBody = "New Event: $title\n\n$description\n\nPlease log in to vote on this event.";

        $mail->send();
        echo 'Notification email has been sent.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event | Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <header>
                <label>Admin Dashboard</label>
                
                <nav class="nav">
                    
                    <a href="admin_profile.php" class="btn btn-warning">Profile</a>
                    <a href="admindashboard.php" class="btn btn-warning">Approval Page</a>
                    <a href="admin_create_event.php" class="btn btn-warning">Events</a>
                    <a href="user_list.php" class="btn btn-warning">User List</a>
                    <a href="index-chat.php" class="btn btn-warning">Channel</a>
                    <a href="logout.php" class="btn btn-warning">Log-Out</a>
                </nav>
        </header>
    <div class="container">
        <h1>Create Event</h1>
        <form action="admin_create_event.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" name="category" id="category" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="expiration_date">Expiration Date</label>
                <input type="datetime-local" name="expiration_date" id="expiration_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="image">Upload Image</label>
                <input type="file" name="image" id="image" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Create Event</button>
        </form>

                        <?php
                require 'database.php';

                // Select all events and their vote counts, marking those that have expired
                $sql = "SELECT events.title, events.expiration_date, 
                            CASE WHEN events.expiration_date < NOW() THEN 'Expired' ELSE 'Active' END AS event_status, 
                            COUNT(votes.id) AS vote_count
                        FROM events
                        LEFT JOIN votes ON events.id = votes.event_id
                        GROUP BY events.id";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<h2>" . $row['title'] . "</h2>";
                    echo "<p>Status: " . $row['event_status'] . "</p>";
                    echo "<p>Expiration Date: " . $row['expiration_date'] . "</p>";
                    echo "<p>Votes: " . $row['vote_count'] . "</p>";
                    echo "<hr>";
                }

                $conn->close();
                ?>


    </div>
</body>
</html>
