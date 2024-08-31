<?php
session_start();
require_once "database.php";

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            if (password_verify($password, $user["password"])) {
                if ($user['status'] === 'pending') {
                    echo "<div class='alert alert-warning'>Your account is pending approval.</div>";
                } elseif ($user['status'] === 'disabled') {
                    echo "<div class='alert alert-danger'>Your account is disabled.</div>";
                } elseif ($user['status'] === 'denied') {
                    echo "<div class='alert alert-danger'>Your account is denied.</div>";
                } else {
                    $_SESSION["user"] = $user;
                    $_SESSION["user_id"] = $user['id'];
                    $_SESSION["user_type"] = $user['user_type'];
                    $_SESSION["username"] = $user['username'];
                    if ($user['user_type'] === 'admin') {
                        header("Location: admin_profile.php");
                    } else {
                        header("Location: userdashboard.php");
                    }
                    exit();
                }
            } else {
                echo "<div class='alert alert-danger'>Password does not match</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Email not found</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error executing SQL statement</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form | BeanTalk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="landing.php">Back</a>
        <h1>Login here</h1>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Login" name="login" class="btn">
            </div>
        </form>
        <div><p>Not registered yet? <a href="registration.php">Register Here</a></p></div>
        <div><p>Forgot Password? <a href="forgotpassword.php">Forgot Password</a></p></div>
    </div>
</body>
</html>
