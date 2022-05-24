<?php

$host="172.17.0.1:3306";
$user="root";
$pass="my-secret-pw";
$db="mydb";

$mysqli= new mysqli($host, $user, $pass, $db)
or die ("<br>Connessione non riuscita" . $mysqli->connect_error . " " .$mysqli->connect_errno)

?>