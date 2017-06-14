<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);
	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'pagsiteeli.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
	if($permiso){
	
		$enlace = enlace();
			
		function array_recibe($url_codpag) { 
			$tmp = stripslashes($url_codpag); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codpag=$_GET['codpag']; 	
		$codpag=array_recibe($codpag); 
		
		$qrypagina = "SELECT codpag FROM pagsite WHERE codpag IN (".implode(',',$codpag).") ";
		$respagina =mysql_query($qrypagina,$enlace);	
		
	
		while ($filpagina = mysql_fetch_assoc($respagina)){
		
				$codpag = $filpagina["codpag"];
				//elimino tipo pagina
				$qrypaginaeli="DELETE FROM pagsite WHERE codpag = '$codpag' ";
				$respaginaeli=mysql_query($qrypaginaeli,$enlace);		
				
				//elimino pagina de detalle de paginas del sitio
				$qrypaginaeli="DELETE FROM pagsiteint WHERE codpag = '$codpag' ";
				$respaginaeli=mysql_query($qrypaginaeli,$enlace);				
				
			} //fin while
			
		mysql_free_result($respagina);

		echo '<script language = JavaScript>
		location = "pagsite.php";
		</script>';
	}
?>
