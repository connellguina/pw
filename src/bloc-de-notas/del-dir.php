<?php
session_start();

if (!rmdir(__DIR__."/archivos/".urldecode($_GET['dir']))) {
    $_SESSION['mensaje'] = 'Unable to erase directory';
    header('Location: index.php');
}

header("Location: index.php");
exit;