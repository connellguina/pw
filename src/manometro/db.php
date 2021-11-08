<?php


$con = pg_connect('host=localhost port=5432 dbname=manometro user=guina password=1234');

if (!$con) {
    exit(pg_last_error());
}