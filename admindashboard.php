<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Dashboard</title>
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


   
<?php

require_once "database.php";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if user is an admin


// Fetch pending user registrations
$sql = "SELECT id, username, email FROM users WHERE status = 'pending' and user_type = 'user'";
$result = mysqli_query($conn, $sql);

if (isset($_POST["approve"]) || isset($_POST["deny"])) {
    $userId = $_POST["user_id"];
    $action = isset($_POST["approve"]) ? 'approved' : 'denied';
    
    // Update user status
    $updateSql = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $updateSql)) {
        mysqli_stmt_bind_param($stmt, "si", $action, $userId);
        mysqli_stmt_execute($stmt);
        
        // Fetch user email
        $userEmailSql = "SELECT email FROM users WHERE id = ?";
        $userStmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($userStmt, $userEmailSql)) {
            mysqli_stmt_bind_param($userStmt, "i", $userId);
            mysqli_stmt_execute($userStmt);
            mysqli_stmt_bind_result($userStmt, $userEmail);
            mysqli_stmt_fetch($userStmt);
            mysqli_stmt_close($userStmt);
            
            // Send email notification
            sendEmailNotification($userEmail, $action);
        }
        
        echo "<div class='alert alert-success'>User has been $action.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating user status.</div>";
    }
}


function sendEmailNotification($email, $status) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '7808b0001@smtp-brevo.com'; // Your Brevo SMTP username
        $mail->Password   = 'xsmtpsib-d82dc99042b79f2fdd3fedc9244b88695909169f4895d878eda6e8a0cf509827-6ChA18Wc5TNwY4xp'; // Your Brevo SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('jhonmurcia143@gmail.com', 'BeanTalk');
        $mail->addAddress($email);

        // Content
        if ($status == 'approved') {
            $mail->Subject = 'Account Approved';
            $mail->Body    = 'Congratulations, your account has been approved! -BeanTalk';
        } else {
            $mail->Subject = 'Account Denied';
            $mail->Body    = 'We are sorry, your account has been denied.';
        }

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if (isset($_POST['Display_post'])) {
    $postId = $_POST['post_id'];
    $stmt = $conn->prepare("UPDATE posts SET approved = TRUE WHERE id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $stmt->close();

    echo "<div class='alert alert-success'>Post approved.</div>";
}

$pendingPosts = $conn->query("SELECT * FROM posts WHERE approved = FALSE");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Member Approval</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row["username"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="user_id" value="<?php echo $row["id"]; ?>">
                                <button type="submit" name="approve" class="btn btn-success">Approve</button>
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="user_id" value="<?php echo $row["id"]; ?>">
                                <button type="submit" name="deny" class="btn btn-danger">Deny</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    
    
    <div class="container">
        <h1>Post Approval</h1>
        <h3>   pending Posts</h3>
                            <?php
                    // Check if session is not started, then start the session
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }

                    require 'database.php';

                    // Ensure only admin can access this page
                    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
                        header("Location: login.php");
                        exit();
                    }

                    $sql = "SELECT posts.id, posts.title, posts.description, posts.image, posts.created_at, users.username
                            FROM posts
                            JOIN users ON posts.user_id = users.id
                            WHERE posts.approved = FALSE
                            ORDER BY posts.created_at DESC";
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        echo "<h2>" . $row['title'] . "</h2>";
                        echo "<p>Posted by: " . $row['username'] . " on " . $row['created_at'] . "</p>";
                        echo "<p>" . $row['description'] . "</p>";
                        if ($row['image']) {
                            echo "<img src='" . $row['image'] . "' alt='Post Image' width='300'>";
                        }
                        echo "<form action='approve_post.php' method='post'>
                                <input type='hidden' name='post_id' value='" . $row['id'] . "'>
                                <button type='submit'>Approve</button>
                            </form>";
                        echo "<form action='reject_post.php' method='post'>
                                <input type='hidden' name='post_id' value='" . $row['id'] . "'>
                                <button type='submit'>Reject</button>
                            </form>";
                        echo "<hr>";
                    }

                    $conn->close();
                    ?>


      





</body>
</html>

    </div>
</body>
</html>
