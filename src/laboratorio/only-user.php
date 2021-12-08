<?php
session_start();

include_once 'db.php';

if (!$_SESSION['user_id']) {
    header('Location: login.php');
    exit;
}

$result = mysqli_query($db, "SELECT * FROM users WHERE id = {$_SESSION['user_id']}");

if (!$resultado) {
    die(mysqli_error($db));
}

$user = mysqli_fetch_assoc($result);


