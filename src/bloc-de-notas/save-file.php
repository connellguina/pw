<?php
session_start();


if ($_POST['save-file']) {
    if (!empty($_POST['filename'])) {
        if ($file = fopen(__DIR__."/archivos/{$_POST['filename']}".'.txt', 'w')) {
            fwrite($file, $_POST['contents']);
            fclose($file);
        } else {
            $_SESSION['msg'] = 'Unable to open file';
        }
    } else {
        $_SESSION['msg'] = 'Empty filename';
    }
}

header("Location: index.php?file={$_POST['filename']}");
die;
