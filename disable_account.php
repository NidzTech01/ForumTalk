<?php
// disable_account.php
session_start();
require_once "database.php";

// Check if the user is an admin
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['disable'])) {
    $user_id = $_POST['user_id'];

    $sql = "UPDATE users SET status = 'disabled' WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        echo "Success disabling account";
        
    } else {
        echo "Error disabling account";
    }
}
?>
