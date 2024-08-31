<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_register";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$otp = $_POST['otp'];

$sql = "SELECT otp_code, otp_expiry FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($db_otp, $db_expiry);
$stmt->fetch();

$current_time = date("Y-m-d H:i:s");

if ($otp == $db_otp && $current_time <= $db_expiry) {
    // OTP is valid, show reset password form
    echo '
    <div class="reset">
    <h1>New Password</h1>
    <form action="update_password.php" method="post">
            <input type="hidden" name="email" value="' . $email . '">
            <label for="new_password">Enter new password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <button type="submit">Reset Password</button>
          </form>
          </div>'
        ;
} else {
    echo 'Invalid or expired OTP.
    <div class="reset">

    <h1>Enter OTP</h1>
    <form action="reset_password.php" method="post">
    <label for="email">Enter your email:</label> 
    <input type="email" id="email" name="email" required> <br>
    <label for="otp">Enter OTP:</label>
    <input type="text" id="otp" name="otp" required> <br>
    <button type="submit">Verify OTP</button>
    <a href="login.php">Login here?</a>
</div>
    ';
    
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Enter new Password | Forgot Password</title>
    
</head>
<body>
    
</body>
</html>
