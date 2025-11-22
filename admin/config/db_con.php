<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'chaircraft_admin';

$con = mysqli_connect("$host", "$username", "$password", "$dbname");

if (!$con) {
    header("Location: errors/db.php");
    exit();
}
?>