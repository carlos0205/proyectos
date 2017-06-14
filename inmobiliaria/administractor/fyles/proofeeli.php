<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);
	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'proofeeli.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
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
		
		$qryproofe = "SELECT codproofe, codpro FROM proofe WHERE codproofe IN (".implode(',',$codreg).") ";
		$resproofe =mysql_query($qryproofe,$enlace);	
		
		$contador = 0;
		while ($filproofe = mysql_fetch_assoc($resproofe)){
				//consulto si existen pproentes dependeientes de l tipo de tercero
				$qrypro="UPDATE pro SET codtippro = 1 WHERE codpro = '".$filproofe["codpro"]."'";
				$respro=mysql_query($qrypro, $enlace);
		
				//elimino tipo de tercero
				$qryproofeeli="DELETE FROM proofe WHERE codproofe = '".$filproofe["codproofe"]."'";
				$resproofeeli=mysql_query($qryproofeeli,$enlace);			
				
				$qryproofeeli="DELETE FROM proofedet WHERE codproofe = '".$filproofe["codproofe"]."'";
				$resproofeeli=mysql_query($qryproofeeli,$enlace);	
				
				auditoria($_SESSION["enlineaadm"],'Productos Oferta',$filproofe["codproofe"],'5');				
		} //fin while
		mysql_free_result($resproofe);

		echo '<script language = JavaScript>
		location = "proofe.php";
		</script>';
	}
?>
