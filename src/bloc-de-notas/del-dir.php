<?php
session_start();

$decoded_dir = urldecode($_GET['dir']);

if (!rmdir(__DIR__."/archivos/$decoded_dir")) {
    $_SESSION['mensaje'] = 'Unable to erase directory';
}

header("Location: index.php");
exit;