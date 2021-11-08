<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}

if ($_GET['pozo']) {
    include('db.php');

    $id = pg_escape_string($con, $_GET['pozo']);

    $result = pg_query($con, "SELECT * FROM manometro_pozos WHERE id = '{$id}'");

    if ($result) {
        $pozos_existentes = pg_fetch_all($result);

        if (count($pozos_existentes) === 1) {
            $result = pg_query($con, "DELETE FROM manometro_pozos WHERE id = '{$id}'");

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