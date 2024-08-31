<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="reset">
        <h1>Forgot Password</h1>
        <form action="send_otp.php" method="post">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Send OTP</button>
        
        <p>Not registered yet? <a href="registration.php">Register Here</a></p>
        <p>Already Registered? <a href="login.php">Login Here</a></p>
    </div>


</form>
</body>
</html>