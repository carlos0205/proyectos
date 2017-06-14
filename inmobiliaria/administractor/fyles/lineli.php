<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);

	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'lineli.php';
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
		
		$qryimglin = "SELECT  ld.codlin, ld.codregdet, l.imglin FROM  linnegdet ld WHERE ld.codlin IN (".implode(',',$codreg).") ";
		$resimglin =mysql_query($qryimglin,$enlace);	
		
		$contador = 0;
		while ($filimglin = mysql_fetch_array($resimglin)){
		
				$codreg = $filimglin["codlin"];

				//consulto si existen subgrupos dependeientes de la linea
				$qrysubgru="SELECT codsubgru FROM subgru WHERE codlin = '$codreg'";
				$ressubgru=mysql_query($qrysubgru, $enlace);
				$numsubgru = mysql_num_rows($ressubgru);
				
				//consulto si existen productos dependeientes de la linea
				$qrypro="SELECT codlin FROM pro WHERE codlin = '$codreg'";
				$respro=mysql_query($qrypro, $enlace);
				$numpro = mysql_num_rows($respro);
				
				
				if($numsubgru > 0 || $numpro > 0 ){
					$contador ++;
				}else{
		
						$ruta1="../lineas/".$filimglin["imglin"];
						unlink ($ruta1);
					
						$ruta2="../lineas/mini/".$filimglin["imglin"];
						unlink ($ruta2);
					
					}
					
					//elimino lineas
					$qrylineli="DELETE FROM linneg WHERE codlin = '$codreg' ";
					$reslineli=mysql_query($qrylineli,$enlace);
					
					$qrylineli="DELETE FROM linnegdet WHERE codlin = '$codreg' ";
					$reslineli=mysql_query($qrylineli,$enlace);		
					
					auditoria($_SESSION["enlineaadm"],'Lineas',$codreg,'5');
				
				}
				
			} //fin while
			
		mysql_free_result($resimglin);
		
		if ($contador > 0){
		echo '<script language = JavaScript>
		alert("No se eliminaron algunas líneas ya que se encuestran vinculadas a sub-grupos y/o productos");
		location = "lin.php";
		</script>';
		}else{
		echo '<script language = JavaScript>
		location = "lin.php";
		</script>';
		}
}
	
?>
