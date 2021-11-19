<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}

include('db.php');

if ($_POST['editar_medida']) {
    $id_medida =  pg_escape_string($con, $_POST['id']);
    $lectura =  pg_escape_string($con, $_POST['lectura']);
    $tiempo =  pg_escape_string($con, "{$_POST['fecha']} {$_POST['hora']}");

    $result = pg_query($con, "UPDATE manometro_medidas SET lectura = '{$lectura}', tiempo = '{$tiempo}' WHERE id = '{$id_medida}'");

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