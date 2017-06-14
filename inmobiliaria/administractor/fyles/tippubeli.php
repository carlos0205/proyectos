<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);
	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'tippubeli.php';
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
		
		$qrytipter = "SELECT codtippub FROM tippub WHERE codtippub IN (".implode(',',$codreg).")";
		$restipter = mysql_query($qrytipter, $enlace);
		
		$contador = 0;
		while ($filtipter = mysql_fetch_assoc($restipter)){
			$codreg = $filtipter["codtippub"];
				
			//consulto si existen noticias asociadas al tipo depublicacion
			$qrypub = "SELECT codpub FROM pubcon WHERE codtippub = '$codreg'";
			$respub = mysql_query($qrypub, $enlace);
			$numpub = mysql_num_rows($respub);
				
			if($numpub > 0){
				$contador ++;
			}else{
				//elimino tipo de tercero
				$qryeli="DELETE FROM tippub WHERE codtippub = '$codreg' ";
				$reseli = mysql_query($qryeli, $enlace);

			}
		} //fin while
		mysql_free_result($restipter);
		if ($contador > 0){
			echo '<script language = JavaScript>
			alert("No se eliminaron algunos tipos de publicación ya que se encuestran asociadas a noticias o eventos");
			location = "tippub.php";
			</script>';
		}else{
			echo '<script language = JavaScript>
			location = "tippub.php";
			</script>';
		}
	}
?>