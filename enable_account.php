<?php
// enable_account.php
session_start();
require_once "database.php";

// Check if the user is an admin
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['enable'])) {
    $user_id = $_POST['user_id'];

    $sql = "UPDATE users SET status = 'approved' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    echo "Success enabling account";
    
    exit();
}
?>
