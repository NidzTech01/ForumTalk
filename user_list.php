<?php
// admindashboard.php
session_start();
require_once "database.php";

// Check if the user is an admin
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all users
$sql = "SELECT id, username, email, status FROM users WHERE user_type = 'user'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | BeanTalk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
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
    <h1>User Lists</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['status']; ?></td>
                    <td>
                        <?php if ($user['status'] !== 'disabled') : ?>
                            <form action="disable_account.php" method="post" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="disable" class="btn btn-danger">Disable</button>
                            </form>
                        <?php else : ?>
                            <form action="enable_account.php" method="post" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="enable" class="btn btn-success">Enable</button>
                            </form>
                            <span class="text-danger">Disabled</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
