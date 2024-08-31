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
$new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

$sql = "UPDATE users SET password = ?, otp_code = NULL, otp_expiry = NULL WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $new_password, $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo '
    <div class="reset">
        <h1>Password reset successfully.</h1>
        <a href="login.php">Login here?</a>
    </div>';
    
} else {
    echo "Failed to reset password.";
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
    <title>Password reset successfully</title>
</head>
<body>
    
</body>
</html>