<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id'])) {
    $logout_id = $_SESSION['user_id'];
    $status = "Offline Now";
    $update = mysqli_query($conn, "UPDATE user_form SET status = '$status' WHERE user_id = '$logout_id'");
}

session_unset();
session_destroy();
header("Location: chat.php");
exit();
?>