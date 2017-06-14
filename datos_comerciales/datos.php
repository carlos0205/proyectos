<?php
require 'conexion.php';

  $fecini = $_POST["txtfecini"];
  $fecfin = $_POST["txtfecfin"];

 $query_registros = "SELECT * forms where name_form ='educali-2017' ";

/*if ($fecini <> " " && $fecfin <> " "){
	 $query_registros .= " AND DATE(node.node_created) BETWEEN '$fecini' AND '$fecfin'";
	}*/

$query_registros .= " GROUP BY idforms ORDER BY idforms DESC";

$resultado1 = db_query($query_registros1);

  foreach($resultado1 as $registro){

            $codreg = $registro["idforms"]; 

            $name = $registro["name_form"]; 

            $content = $registro["content_form"]; 

//Seteamos el header de "content-type" como "JSON" para que jQuery lo reconozca como tal
header('Content-Type: application/json');
//Guardamos los datos en un array
$datos = array(
'estado' => 'ok',
'codigo' => $codreg, 
'content_form' => $content, 
'name_form' => $name
);
//Devolvemos el array pasado a JSON como objeto
echo json_encode($datos, JSON_FORCE_OBJECT);
}
?>