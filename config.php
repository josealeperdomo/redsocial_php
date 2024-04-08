<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "rsocial";

$conn = new mysqli($host,$username,$password, $database);
if ($conn->connect_error) {
    die('error de conexion'. $conn->connect_error);
}


?>