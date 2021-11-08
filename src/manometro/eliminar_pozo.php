<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}

if ($_GET['pozo']) {
    include('db.php');

    $id = mysqli_real_escape_string($con, $_GET['pozo']);

    $result = mysqli_query($con, "SELECT * FROM pozos WHERE id = '{$id}'");

    if ($result) {
        $pozos_existentes = mysqli_fetch_all($result);

        if (count($pozos_existentes) === 1) {
            $result = mysqli_query($con, "DELETE FROM pozos WHERE id = '{$id}'");

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