<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
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
		
		$qryreg = "SELECT imgpub, codpub AS codreg FROM pubcon WHERE codpub IN (".implode(',',$codreg).") AND imgpub <> 'logocli.jpg' ";
		$resreg =mysql_query($qryreg,$enlace);	
		
		while ($filreg = mysql_fetch_array($resreg)){
		
				$ruta1="../publicaciones/".$filreg["imgpub"];
				unlink ($ruta1);
			
				$ruta2="../publicaciones/mini/".$filreg["imgpub"];
				unlink ($ruta2);
				
				//elimino fotos
				$qryfot = "SELECT img FROM pubconfot WHERE codpub = ".$filreg["codreg"]." ";
				$resfot = mysql_query($qryfot, $enlace);
				$numfot = mysql_num_rows($resfot);
				if ($numfot > 0){
					while($filfot = mysql_fetch_assoc($resfot)){
						$ruta="../publicaciones/fotos/".$filfot["img"];
						$ruta1 = "../publicaciones/fotos/mini/".$filfot["img"];
						unlink ($ruta);		
						unlink ($ruta1);	
					}
					$qryviseli = "DELETE FROM pubconfot WHERE codpub = ".$filreg["codreg"]." ";
					$resviseli = mysql_query($qryviseli, $enlace);
					
					auditoria($_SESSION["enlineaadm"],'Publicaciones',$filreg["codreg"],'5');	
				}
		} 
		
		mysql_free_result($resreg);
		
		//elimino publicaciones
		$qrypubeli="DELETE FROM pubcon WHERE codpub IN (".implode(',',$codreg).") ";
		$respubeli=mysql_query($qrypubeli,$enlace);	
		
		//elimino publicaciones
		$qrypubeli="DELETE FROM tblformatoinscripcioneveres WHERE codpub IN (".implode(',',$codreg).") ";
		$respubeli=mysql_query($qrypubeli,$enlace);	
		
	
		echo '<script language = JavaScript>
		location = "not.php";
		</script>';
	}	
?>
