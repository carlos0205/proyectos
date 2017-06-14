<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);

	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'proedi.php';
	$usu = $_SESSION["usuario"];
	permisos($usu, $prog);
	
	$enlace = enlace();
		
	function array_recibe($url_codcol) { 
		$tmp = stripslashes($url_codcol); 
		$tmp = urldecode($tmp); 
		$tmp = unserialize($tmp); 
	    return $tmp; 
	} 
	
	$codcol=$_GET['codcol']; 	
	$codcol=array_recibe($codcol); 
	$cod=$_GET['cod']; 

	//elimino colores
	$totalcolores = count($codcol);
	
	$contador = 0;
	while ($contador < $totalcolores){
	$qrycolasi="INSERT INTO procol VALUES('$cod','$codcol[$contador]') ";
	$rescolasi=mysql_query($qrycolasi,$enlace);	
	$contador++;
	}

?>

	<script language = JavaScript>
	location = "procol.php?cod=<?php echo $cod?>";
	</script>';

