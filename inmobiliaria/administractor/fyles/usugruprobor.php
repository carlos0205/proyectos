<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);

	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'usugruedi.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
	if($permiso){
	
		$enlace = enlace();
			
		function array_recibe($url_codprog) { 
			$tmp = stripslashes($url_codprog); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codprog=$_GET['codprog']; 	
		$cod=$_GET['cod']; 	
	
		$codprog=array_recibe($codprog); 
		
		$qryimgfab = "DELETE FROM gruprog WHERE  codprog IN (".implode(',',$codprog).")AND codgru = '$cod' ";
		$resimgfab =mysql_query($qryimgfab,$enlace);		
		}

?>
	<script language = JavaScript type="text/javascript">
	location = "usugruedi.php?cod=<?php echo $cod?>";
	</script>';

