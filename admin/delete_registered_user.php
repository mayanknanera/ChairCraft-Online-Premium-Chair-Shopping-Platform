<?php
include 'authentication.php';
include 'config/db_con.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete user from database
    $sql = "DELETE FROM users WHERE id='$user_id'";
    if (mysqli_query($con, $sql)) {
        $_SESSION['message'] = "User deleted successfully";
        header("Location: registered_users.php");
        exit();
    } else {
        $_SESSION['message'] = "Error deleting user";
        header("Location: registered_users.php");
        exit();
    }
}
?>