<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'idipubeli.php';
$usu = $_SESSION["usuario"];
$permiso =  permisos($usu, $prog);
if($permiso){
	$enlace = enlace();
		
	function array_recibe($url_codidi) { 
		$tmp = stripslashes($url_codidi); 
		$tmp = urldecode($tmp); 
		$tmp = unserialize($tmp); 
		return $tmp; 
	} 
	$codidi=$_GET['codidi']; 	
	$codidi=array_recibe($codidi); 
	
	$qryidioma = "SELECT codidi FROM idipub WHERE codidi IN (".implode(',',$codidi).") ";
	$residioma =mysql_query($qryidioma,$enlace);	
	
	while ($filidioma = mysql_fetch_assoc($residioma)){
		$codidi = $filidioma["codidi"];
	
		//elimino tipo idioma
		$qryidiomaeli="DELETE FROM idipub WHERE codidi = '$codidi' ";
		$residiomaeli=mysql_query($qryidiomaeli,$enlace);		
			
		//elimino idioma de detalle de paginas del sitio
		$qryidiomaeli="DELETE FROM pagsiteint WHERE codidi = '$codidi' ";
		$residiomaeli=mysql_query($qryidiomaeli,$enlace);				
	} //fin while
	mysql_free_result($residioma);

	echo '<script language = JavaScript>
	location = "idipub.php";
	</script>';
}
?>