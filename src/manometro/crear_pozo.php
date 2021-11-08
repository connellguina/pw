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

    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);

    $result = mysqli_query($con, "SELECT * FROM pozos WHERE nombre = '{$nombre}'");

    if ($result) {
        $pozos_existentes = mysqli_fetch_all($result);

        if (count($pozos_existentes) === 0) {
            $result = mysqli_query($con, "INSERT INTO pozos (nombre, descripcion) VALUES ('{$nombre}', '{$descripcion}')");

            if ($result) {
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['error'] = mysqli_error($con);
                header('Location: index.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Ya hay un pozo con nombre \"$nombre\"";
            header('Location: index.php');
            exit();
        }
    } else {
        $_SESSION['error'] = mysqli_error($con);
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>