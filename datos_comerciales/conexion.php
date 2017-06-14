<?php 
//1. Conexion a la base de datos
function db_connect()
{

    static $connection;

    if(!isset($connection)) {
        $connection = mysqli_connect("10.60.61.181", "nmedios", "intranet", "drupal_elpais");
    }

    if($connection === false) {
        return mysqli_connect_error(); 
    }

    return $connection;
}

function db_query($query) 
{
    $connection = db_connect();

    $result = mysqli_query($connection,$query);

    return $result;
}

function limpiar($s) 
{ 
$s= str_replace('&nbsp;',' ',$s); 
return $s; 
}


?>