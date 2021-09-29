<?php
    session_start();
    unset($_SESSION['nama']);
    unset($_SESSION['password']);

    session_destroy();
    header('location: /admin/login.php');
?>