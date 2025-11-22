<?php
// logout.php

session_start();
// Remove user_requests for this user if logged in
if (isset($_SESSION['auth_user']['id'])) {
    require_once __DIR__ . '/admin/config/db_con.php';
    $user_id = $_SESSION['auth_user']['id'];
    $stmt = $con->prepare('DELETE FROM user_requests WHERE user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->close();
}
// Unset all possible session variables for both user and admin
unset($_SESSION['user_id']);
unset($_SESSION['username']);
unset($_SESSION['auth']);
unset($_SESSION['auth_user']);
session_unset();
session_destroy();
header('Location: admin/login.php');
exit();
