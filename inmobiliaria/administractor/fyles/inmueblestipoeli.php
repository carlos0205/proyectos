<?php
session_start();
include("../../administractor/fyles/general/conexion.php") ;
include("../../administractor/fyles/general/sesion.php");
sesion(1);
	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'inmueblestipoeli.php';
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
		
		$qryreg = "SELECT codtipinmueble FROM inmuebletipo WHERE codtipinmueble IN (".implode(',',$codreg).") ";
		$resreg = mysql_query($qryreg, $enlace);
		
		$contador =0;
		
		while($filreg = mysql_fetch_assoc($resreg)){
		
		    $codreg = $filreg["codtipinmueble"];
		
			$qrytip = "SELECT codtipinmueble FROM inmuebles WHERE codtipinmueble = $codreg";
			$restip = mysql_query($qrytip,$enlace);
		
			if(mysql_num_rows($restip) > 0){
					$contador ++;
				}else{
					//elimino tipo de tercero
					$qrytipeli = "DELETE FROM inmuebletipo WHERE codtipinmueble = '$codreg' ";
					$restipeli =mysql_query($qrytipeli,$enlace);					
				}
		}//fin while
		
		mysql_free_result($restip);
		
		if ($contador > 0){
		echo '<script language = JavaScript>
		alert("No se eliminaron el tipo de inmuebles ya que esta vinculados a un inmueble");
		location = "inmueblestipo.php";
		</script>';
		}else{
		echo '<script language = JavaScript>
		location = "inmueblestipo.php";
		</script>';
		}
	}
?>