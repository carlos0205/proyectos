<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);

	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'ayudaeli.php';
	$usu = $_SESSION["usuario"];
	permisos($usu, $prog);
if($permiso){	
	$enlace = enlace();
		
	function array_recibe($url_codreg) { 
		$tmp = stripslashes($url_codreg); 
		$tmp = urldecode($tmp); 
		$tmp = unserialize($tmp); 
	    return $tmp; 
	} 
	
	$codreg=$_GET['codreg']; 	
	$codreg=array_recibe($codreg); 

	$qryreg = "SELECT codayuda FROM  ayudavisitante WHERE codayuda IN (".implode(',',$codreg).") ";
	$resreg =mysql_query($qryreg,$enlace);	
	
	$contador = 0;
	while ($filreg = mysql_fetch_array($resreg)){
			$codreg = $filreg["codayuda"];
			$qryeli="DELETE FROM ayudavisitante WHERE codayuda = $codreg ";
			$reseli=mysql_query($qryeli,$enlace);
			
			auditoria($_SESSION["enlineaadm"],'Ayuda Visitante',$codreg,'5');
		
	}
	mysql_free_result($resreg);

	echo '<script language = JavaScript>
	location = "ayuda.php";
	</script>';
}

?>