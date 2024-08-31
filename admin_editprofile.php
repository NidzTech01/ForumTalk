<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include 'database.php';
$user_id = $_SESSION['user']['id'];
$user_sql = "SELECT * FROM users WHERE id='$user_id'";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $age = $_POST['age'];

    $profile_pic = $user['profile_pic'];
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);
        $profile_pic = $target_file;
    }

    $update_sql = "UPDATE users SET username='$username', email='$email', bio='$bio', gender='$gender', birthday='$birthday', age='$age', profile_pic='$profile_pic' WHERE id='$user_id'";

    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['profile_updated'] = true;
        header("Location: admin_profile.php");
        exit;
    } else {
        echo "Error: " . $update_sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 50%; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="date"], input[type="number"], textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .alert { padding: 15px; background-color: #4CAF50; color: white; margin-bottom: 15px; }
    </style>
</head>
<body>
    <header>
        <label>Admin Dashboard</label>
        <nav class="nav">

            <a href="admin_profile.php" class="btn btn-warning">Profile</a>
            <a href="admindashboard.php" class="btn btn-warning">Approval Page</a>
            <a href="user_list.php" class="btn btn-warning">User List</a>
            <a href="index-chat.php" class="btn btn-warning">Channel</a>
            <a href="logout.php" class="btn btn-warning">Logout</a>

        </nav>
    </header>
    
    <div class="container">
        
        <h2>Edit Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea name="bio" id="bio" rows="4"><?php echo $user['bio']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender" id="gender">
                    <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if ($user['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="birthday">Birthday:</label>
                <input type="date" name="birthday" id="birthday" value="<?php echo $user['birthday']; ?>">
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" name="age" id="age" value="<?php echo $user['age']; ?>">
            </div>
            <div class="form-group">
                
            </div>
            <button type="submit">Save Changes</button>
            <a href="admin_profile.php"><button type="button">Cancel</button></a>
        </form>
    </div>
</body>
</html>
