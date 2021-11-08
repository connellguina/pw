<?php


$con = mysqli_connect('localhost:3307', 'root', '', 'pozos');

if (!$con) {
    die(mysqli_connect_error());
}