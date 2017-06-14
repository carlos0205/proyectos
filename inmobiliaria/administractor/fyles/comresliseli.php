<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);

	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'comresliseli.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
	if($permiso){
		
		$enlace = enlace();
			
		function array_recibe($url_codcon) { 
			$tmp = stripslashes($url_codcon); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codcon=$_GET['codcon']; 	
		$codcon=array_recibe($codcon); 
		
		$qryreg = "SELLECT codconweb FROM conweb WHERE codconweb IN (".implode(',',$codcon).") ";
		$resreg =mysql_query($qryreg,$enlace);
		
		while($filreg = mysql_fetch_array($resreg)){
		
			$codreg = $filreg["codconweb"];
			
			$qryeli = mysql_query("DELETE FROM conweb WHERE codconweb =$codreg";
			$reseli = mysql_query($qryeli, $enlace);
			
			$qryeli = mysql_query("DELETE FROM resconweb WHERE codconweb =$codreg";
			$reseli = mysql_query($qryeli, $enlace);
	
			auditoria($_SESSION["enlineaadm"],'Contacto Web',$codreg,'5');

		}
			
	
		echo '<script language = JavaScript>
		location = "comreslis.php";
		</script>';
	}
?>
