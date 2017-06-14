<?php
	session_start();
	include("../../administractor/fyles/general/conexion.php") ;
	include("../../administractor/fyles/general/sesion.php");
	sesion(1);

	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'noteli.php';
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
		
		//consulto nombres de imagen de la publicacion para eliminarlas
		
		$qryreg = "SELECT imginmueble, codinmueble AS codreg FROM inmuebles WHERE codinmueble IN (".implode(',',$codreg).")";
		$resreg =mysql_query($qryreg,$enlace);	
		
		while ($filreg = mysql_fetch_array($resreg)){
		
				$ruta1="../inmuebles/".$filreg["imginmueble"];
				unlink ($ruta1);
			
				$ruta2="../inmuebles/mini/".$filreg["imginmueble"];
				unlink ($ruta2);
				
				//elimino fotos
				$qryfot = "SELECT imginmueble FROM inmueblesvis WHERE codinmueble = ".$filreg["codreg"]." ";
				$resfot = mysql_query($qryfot, $enlace);
				$numfot = mysql_num_rows($resfot);
				if ($numfot > 0){
					while($filfot = mysql_fetch_assoc($resfot)){
						$ruta="../inmuebles/".$filfot["imginmueble"];
						$ruta1 = "../inmuebles/mini/".$filfot["imginmueble"];
						unlink ($ruta);		
						unlink ($ruta1);	
					}
					$qryviseli = "DELETE FROM inmueblesvis WHERE codinmueble = ".$filreg["codreg"]." ";
					$resviseli = mysql_query($qryviseli, $enlace);
					
					auditoria($_SESSION["enlineaadm"],'inmuebles',$filreg["codreg"],'5');	
				}
		} 
		
		mysql_free_result($resreg);
		
		//elimino publicaciones
		$qrypubeli="DELETE FROM inmuebles WHERE codinmueble IN (".implode(',',$codreg).") ";
		$respubeli=mysql_query($qrypubeli,$enlace);	
		
		
		
	
		echo '<script language = JavaScript>
		location = "inmuebles.php";
		</script>';
	}	
?>
