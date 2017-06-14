<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);
	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'intpagedi.php';
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
		
		$qryreg = "SELECT codimgfon AS codreg , imgfondo FROM pagsitefondodiario WHERE codimgfon IN (".implode(',',$codreg).") ";
		$resreg =mysql_query($qryreg,$enlace);	
		
	
		while ($filreg = mysql_fetch_assoc($resreg)){
		
				$codreg = $filreg["codreg"];
				//elimino imagen
				unlink("../../imgfondodiaria/".$filreg["imgfondo"]."");
				
				$qrypaginaeli="DELETE FROM pagsitefondodiario WHERE codimgfon = '$codreg' ";
				$respaginaeli=mysql_query($qrypaginaeli,$enlace);		
					
	
			} //fin while
			
		mysql_free_result($resreg);

		echo '<script language = JavaScript>
		location = "pagsitefondodiario.php";
		</script>';
	}
?>
