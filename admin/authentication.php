<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "You need to log in first";
    header("Location: login.php");
    exit();
}
else{
    if($_SESSION['auth'] == "1"){

    }
    else{
        header("Location: ../index.php");
        exit();
    }
}
?>
