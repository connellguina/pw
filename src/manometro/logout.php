<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}

session_destroy();
header('Location: login.php');
exit();
