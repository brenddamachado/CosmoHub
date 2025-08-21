<?php

$servidor = "localhost";
$usuario = "root";       
$senha = "";           
$dbname = "cosmohub";    


$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);

if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}
?>