<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}

if ($_POST['editar-pozo']) {
    if (!$_POST['nombre'] || !$_POST['descripcion'] || !$_POST['id']) {
        $_SESSION['error'] = 'Se necesita nombre y descripción del pozo';
        header('Location: index.php');
        exit();
    }

    include('db.php');

    $nombre = pg_escape_string($con, $_POST['nombre']);
    $descripcion = pg_escape_string($con, $_POST['descripcion']);
    $id = pg_escape_string($con, $_POST['id']);

    $result = pg_query($con, "SELECT * FROM manometro_pozos WHERE id = '{$id}'");

    if ($result) {
        $pozos_existentes = pg_fetch_all($result, PGSQL_ASSOC);

        if (count($pozos_existentes) === 1) {
            $result = pg_query($con, "UPDATE manometro_pozos SET name = '{$nombre}', descripcion = '{$descripcion}' WHERE id = '{$id}'");

            if ($result) {
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['error'] = pg_last_error($con);
                header('Location: index.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Pozo inválido";
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