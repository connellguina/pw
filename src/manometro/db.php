<?php


//$con = pg_connect('host=localhost port=5432 dbname=manometro user=guina password=1234');
$con = pg_connect(getenv("DATABASE_URL"));

if (!$con) {
    exit(pg_last_error());
}