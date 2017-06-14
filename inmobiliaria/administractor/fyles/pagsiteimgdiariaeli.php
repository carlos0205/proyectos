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
		
		$qryreg = "SELECT codpagimg AS codreg, imgpag, tipimg FROM pagsiteimgdiaria WHERE codpagimg IN (".implode(',',$codreg).") ";
		$resreg =mysql_query($qryreg,$enlace);	
		
	
		while ($filreg = mysql_fetch_assoc($resreg)){
		
				$codreg = $filreg["codreg"];
				
				$qryreg = mysql_query("SELECT imgslider FROM pagsiteimgdiariaslider WHERE codpagimg = $codreg");

					while($filreg= mysql_fetch_array($qryreg)){
						$ruta1="../../imgsecciondiariaslider/".$filreg["imgslider"];
						unlink ($ruta1);
					
						$ruta2="../../imgsecciondiariaslider/mini/".$filreg["imgslider"];
						unlink ($ruta2);
					} //fin while
					mysql_free_result($qryreg);
					
					//elimino fotos
					$qryeli=mysql_query("DELETE FROM pagsiteimgdiariaslider WHERE codpagimg = $codreg");
				
				
				if($filreg["tipimg"]<>3){
					if($filreg["tipimg"]<>1){
					unlink("../../imgsecciondiaria/mini/".$filreg["imgpag"]."");
					}
					unlink("../../imgsecciondiaria/".$filreg["imgpag"]."");
				}
				//elimino imagen
				
				
				$qrypaginaeli="DELETE FROM pagsiteimgdiaria WHERE codpagimg = '$codreg' ";
				$respaginaeli=mysql_query($qrypaginaeli,$enlace);		
					
				
			} //fin while
			
		mysql_free_result($resreg);

		echo '<script language = JavaScript>
		location = "pagsiteimgdiaria.php";
		</script>';
	}
?>
