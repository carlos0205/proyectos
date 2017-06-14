<?php
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
$listadoSelects=array(
"select1"=>"tipconter",
"select2"=>"tippqrs",
);

function validaSelect($selectDestino)
{
	// Se valida que el select enviado via GET exista
	global $listadoSelects;
	if(isset($listadoSelects[$selectDestino])) return true;
	else return false;
}

function validaOpcion($opcionSeleccionada)
{
	// Se valida que la opcion seleccionada por el usuario en el select tenga un valor numerico
	if(is_numeric($opcionSeleccionada)) return true;
	else return false;
}

$selectDestino=$_GET["select"]; $opcionSeleccionada=$_GET["opcion"];$idioma = $_GET['idi'];

if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
{
	$tabla=$listadoSelects[$selectDestino];
	
	
	
	include '../../../administractor/fyles/general/conexion.php';
	enlace();
	if ($tabla == "tipconter"){
	$qry=mysql_query("SELECT tc.codtipcon, tcd.nomtipcon FROM tipconter tc, tipconterdet tcd WHERE tc.codtipcon ='$opcionSeleccionada' AND tc.codtipcon = tcd.codtipcon AND tcd.codidi = 1") or die(mysql_error());
	}else{
	$qry=mysql_query("SELECT tpq.codtippqrs, tpqd.nomtippqrs FROM tippqrs tpq, tippqrsdet tpqd WHERE tpq.codtippqrs ='$opcionSeleccionada' AND tpq.codtippqrs = tpqd.codtippqrs AND tpqd.codidi = '$idioma'") or die(mysql_error());
	}

	desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>Elige / Select</option>";
	while($registro=mysql_fetch_row($qry))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}			
	echo "</select>";
}
?>