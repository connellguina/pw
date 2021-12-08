<?php
include_once 'only-user.php';

if ($user['role'] !== 'nurse') {
    header('Location: login.php');
    exit;
}