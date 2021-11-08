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

    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
    $id = mysqli_real_escape_string($con, $_POST['id']);

    $result = mysqli_query($con, "SELECT * FROM pozos WHERE id = '{$id}'");

    if ($result) {
        $pozos_existentes = mysqli_fetch_all($result);

        if (count($pozos_existentes) === 1) {
            $result = mysqli_query($con, "UPDATE pozos SET nombre = '{$nombre}', descripcion = '{$descripcion}' WHERE id = '{$id}'");

            if ($result) {
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['error'] = mysqli_error($con);
                header('Location: index.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Pozo inválido";
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