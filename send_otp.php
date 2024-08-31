<?php
require 'vendor/autoload.php'; // Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_register";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];

// Generate a 6-digit OTP
$otp = rand(100000, 999999);
$expiry = date("Y-m-d H:i:s", strtotime('+10 minutes'));

$sql = "UPDATE users SET otp_code = ?, otp_expiry = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $otp, $expiry, $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    sendOtpEmail($email, $otp);
    echo "OTP sent to your email.";
    
    
} else {
    echo "Email not found.";
}

$stmt->close();
$conn->close();

function sendOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = '7808b0001@smtp-brevo.com';
        $mail->Password = 'xsmtpsib-d82dc99042b79f2fdd3fedc9244b88695909169f4895d878eda6e8a0cf509827-6ChA18Wc5TNwY4xp';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('jhonmurcia143@gmail.com', 'BeanTalk');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = 'Your OTP code is ' . $otp;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Enter Otp | Forgot Password</title>
</head>
<body>
<div class="reset">

    <h1>Enter OTP</h1>
    <form action="reset_password.php" method="post">
    <label for="email">Enter your email:</label> 
    <input type="email" id="email" name="email" required> <br>
    <label for="otp">Enter OTP:</label>
    <input type="text" id="otp" name="otp" required> <br>
    <button type="submit">Verify OTP</button>
</div>

</form>
</body>
</html>