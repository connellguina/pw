<?php
session_start();

if (!unlink("archivos/".urldecode($_GET['file']))) {
    $_SESSION['mensaje'] = 'Unable to erase file';
    header('Location: index.php');
}

header("Location: index.php");
exit;
