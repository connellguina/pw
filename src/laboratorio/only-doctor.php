<?php
include_once 'only-user.php';

if ($user['role'] !== 'doctor') {
    header('Location: login.php');
    exit;
}