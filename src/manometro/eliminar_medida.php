<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}

include('db.php');

if ($_GET['id']) {
    $medida_id =  pg_escape_string($con, $_GET['id']);
    $result = pg_query($con, "DELETE FROM manometros_medida WHERE id = '{$medida_id}'");

    if (!$result) {
        $_SESSION['error'] = pg_last_error($con);
        header('Location: index.php');
        exit();
    }

    header('Location: index.php');
    exit();
} else {
    $_SESSION['error'] = 'Medida inválida';
    header('Location: index.php');
    exit();
}
