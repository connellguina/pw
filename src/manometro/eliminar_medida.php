<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}

include('db.php');

if ($_GET['id']) {
    $medida_id =  pg_escape_string($con, $_GET['id']);

    $result = pg_query($con, "SELECT * FROM manometro_medidas WHERE id = '$medida_id'");

    $medida = pg_fetch_assoc($result);

    if (!$medida) {
        $_SESSION['error'] = "Ya hay una medida para $tiempo";
        header('Location: index.php');
        exit();
    }

    $result = pg_query($con, "DELETE FROM manometro_medidas WHERE id = '$medida_id'");

    if (!$result) {
        $_SESSION['error'] = pg_last_error($con);
        header("Location: medidas.php?pozo=".$medida['id_pozo']);
        exit();
    }

    header("Location: medidas.php?pozo=".$medida['id_pozo']);
    exit();
} else {
    $_SESSION['error'] = 'Medida inválida';
    header('Location: index.php');
    exit();
}
