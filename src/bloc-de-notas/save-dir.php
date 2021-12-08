<?php
session_start();


if ($_POST['save-dir']) {
    if (!empty($_POST['dirname'])) {
        if (mkdir(__DIR__."/archivos/{$_POST['dirname']}")) {
        } else {
            $_SESSION['msg'] = 'Unable to create directory';
        }
    } else {
        $_SESSION['msg'] = 'Empty directory name';
    }
}

header('Location: index.php');
die;