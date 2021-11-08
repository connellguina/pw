<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}

if ($_POST['crear-pozo']) {
    if (!$_POST['nombre'] || !$_POST['descripcion']) {
        $_SESSION['error'] = 'Se necesita nombre y descripción del pozo';
        header('Location: index.php');
        exit();
    }

    include('db.php');

    $nombre = pg_escape_string($con, $_POST['nombre']);
    $descripcion = pg_escape_string($con, $_POST['descripcion']);

    $result = pg_query($con, "SELECT * FROM manometro_pozos WHERE name = '{$nombre}'");

    if ($result) {
        $pozos_existentes = pg_fetch_all($result, PGSQL_ASSOC);

        if ($pozos_existentes === false || count($pozos_existentes) === 0) {
            $result = pg_query($con, "INSERT INTO manometro_pozos (name, descripcion) VALUES ('{$nombre}', '{$descripcion}')");

            if ($result) {
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['error'] = pg_last_error($con);
                header('Location: index.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Ya hay un pozo con nombre \"$nombre\"";
            header('Location: index.php');
            exit();
        }
    } else {
        $_SESSION['error'] = pg_last_error($con);
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>