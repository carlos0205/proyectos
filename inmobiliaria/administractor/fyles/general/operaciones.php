<?php

//guardar datos
function guardar($tabla, $accion, $llave, $retorna){
//$accion 1 = guardar , 2 = aplicar

global $enlace;
$fecha = date("Y-n-j H:i:s");

//inicio la cadena de consulta
$campos = "";
$valores = "";

//armo los campos de la consulta de insercion
foreach($_POST as $campo => $valor)
	{
	   //todo los dos ultimos caracteres del campo para verificar si hace parte de la consulta o no
	   $concatena = substr($campo,-2);
	  
	   if($concatena == "si"){
	   //elimino primeros 4 y ultimos dos caracteres del campo para hallar el nombre del campo de la BD
	   $campos .= substr($campo,4,-2).",";
	   $valores .= "'".$valor."',";
	   }
	}
	
	//elimino la coma del final de la cadena
	$campos = substr($campos,0,-1);
	$valores = substr($valores,0,-1);

	$qry = "INSERT INTO $tabla ($llave,$campos) VALUES('0', $valores )";
		$res= mysql_query($qry, $enlace);
		$siguiente = mysql_insert_id($enlace);
//echo $qry;
		
		if($retorna==1){
			if($accion == 1){
			//refresco contenido
				?>
				<script language = JavaScript>
				location = "<?php echo $tabla?>.php";
				</script>
				<?php
			}else{
				?>
				<script language="javascript1.2" type="text/javascript">
				location = "<?php echo $tabla?>edi.php?cod=<?php echo $siguiente ?>";
				</script>
				<?php
			}
		}else{
			return($siguiente);
		}
}


function guardar1($tabla, $accion, $llave, $retorna){
//$accion 1 = guardar , 2 = aplicar

global $enlace;
$fecha = date("Y-n-j H:i:s");

//inicio la cadena de consulta
$campos = "";
$valores = "";

//armo los campos de la consulta de insercion
foreach($_POST as $campo => $valor)
	{
	   //todo los dos ultimos caracteres del campo para verificar si hace parte de la consulta o no
	   $concatena = substr($campo,-2);
	  
	   if($concatena == "si"){
	   //elimino primeros 4 y ultimos dos caracteres del campo para hallar el nombre del campo de la BD
	   $campos .= substr($campo,4,-2).",";
	   $valores .= "'".$valor."',";
	   }
	}
	
	//elimino la coma del final de la cadena
	$campos = substr($campos,0,-1);
	$valores = substr($valores,0,-1);
	
	if($campos <> ""){
	$qry = "INSERT INTO $tabla ($llave,$campos) VALUES('0', $valores )";
	}else{
	$qry = "INSERT INTO $tabla ($llave) VALUES('0')";
	}
		$res= mysql_query($qry, $enlace);
		$siguiente = mysql_insert_id($enlace);
	

		if($retorna==1){
			if($accion == 1){
			//refresco contenido
				?>
				<script language = JavaScript>
				location = "<?php echo $tabla?>.php";
				</script>
				<?php
			}else{
				?>
				<script language="javascript1.2" type="text/javascript">
				location = "<?php echo $tabla?>edi.php?cod=<?php echo $siguiente ?>";
				</script>
				<?php
			}
		}else{
			return($siguiente);
		}

}

//actualizar datos
function actualizar($tabla, $accion, $codigo, $llave, $retorna){
//$accion 1 = guardar , 2 = aplicar

global $enlace;
$fecha = date("Y-n-j H:i:s");

//inicio la cadena de consulta
$campos = "";

foreach($_POST as $campo => $valor)
	{
	   //todo los dos ultimos caracteres del campo para verificar si hace parte de la consulta o no
	   $concatena = substr($campo,-2);
	  
	   if($concatena == "si"){
	   //elimino primeros 4 y ultimos dos caracteres del campo para hallar el nombre del campo de la BD
	   $campos .= substr($campo,4,-2)."='".$valor."',";
	   }
	}
	
	//elimino la coma del final de la cadena
	$campos = substr($campos,0,-1);
	
		$qry = "UPDATE $tabla SET $campos WHERE $llave = $codigo";
		$res= mysql_query($qry, $enlace);
		//echo $qry;

		if($accion == 1){
		
		//refresco contenido
			?>
			<script language = JavaScript>
			location = "<?php echo $retorna?>";
			</script>
			<?php
		}else{
			
			?>
			<script language="javascript1.2" type="text/javascript">
			location = "<?php echo $retorna?>";
			</script>
			<?php
		}
	

}

function actualizar1($tabla, $accion, $codigo, $llave, $retorna){
//$accion 1 = guardar , 2 = aplicar

global $enlace;
$fecha = date("Y-n-j H:i:s");

//inicio la cadena de consulta
$campos = "";

foreach($_POST as $campo => $valor)
	{
	   //todo los dos ultimos caracteres del campo para verificar si hace parte de la consulta o no
	   $concatena = substr($campo,-2);
	  
	   if($concatena == "si"){
	   //elimino primeros 4 y ultimos dos caracteres del campo para hallar el nombre del campo de la BD
	   $campos .= substr($campo,4,-2)."='".$valor."',";
	   }
	}
	
	//elimino la coma del final de la cadena
	$campos = substr($campos,0,-1);
	
		$qry = "UPDATE tbl$tabla SET $campos WHERE $llave = $codigo";
		$res= mysql_query($qry, $enlace);
		
		if($accion == 1){
		
		//refresco contenido
			?>
			<script language = JavaScript>
			location = "<?php echo $retorna?>";
			</script>
			<?php
		}else{
			
			?>
			<script language="javascript1.2" type="text/javascript">
			location = "<?php echo $retorna?>";
			</script>
			<?php
		}


}
/*function destruyesesiones($excepto){
	$sesiones = array("enlinea", "usuario", "grupo", "ultimoacceso" , $excepto);
	do{
		if (!in_array(key($_SESSION),$sesiones)){
			//echo key($_SESSION);
			//echo current($_SESSION);
			session_unregister(key($_SESSION));
		}
	} while(next($_SESSION));
}*/

function codifica($registro){
	$registro = serialize($registro); 
    $registro = urlencode($registro); 
	$registro = base64_encode($registro);
	return ($registro);
}


function decodifica($registro){
	$registro = base64_decode($registro);
	$registro = urldecode($registro); 
	$registro = unserialize($registro);
	return ($registro);
}
?>