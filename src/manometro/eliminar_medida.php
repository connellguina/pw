<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}

include('db.php');

if ($_GET['medida']) {
    $result = pg_query($con, "DELETE FROM manometros_medida WHERE id = {$_GET['editar_medida']}");

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
