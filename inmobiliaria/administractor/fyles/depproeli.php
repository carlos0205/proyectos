<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);

	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'depproeli.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
	if($permiso){
		
		$enlace = enlace();
			
		function array_recibe($url_coddep) { 
			$tmp = stripslashes($url_coddep); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$coddep=$_GET['coddep']; 	
		$coddep=array_recibe($coddep); 
		
		$qrydep = "SELECT coddep FROM deppro WHERE coddep IN (".implode(',',$coddep).") ";
		$resdep =mysql_query($qrydep,$enlace);	
		
		$contador = 0;
		while ($fildep = mysql_fetch_array($resdep)){
		
				$coddep = $fildep["coddep"];
				
				//consulto si existen productos dependeientes de la clase
				$qrydep1="SELECT c.codciu FROM ciudad c WHERE c.coddep = '$coddep'";
				$resdep1=mysql_query($qrydep1, $enlace);
				$numciu = mysql_num_rows($resdep1);
	
				if($numciu > 0 ){
					$contador ++;
				}else{
				
					//elimino clase
					$qrydepeli="DELETE FROM deppro WHERE coddep = '$coddep' ";
					$resdepeli=mysql_query($qrydepeli,$enlace);	
					
					auditoria($_SESSION["enlineaadm"],'Departamentos',$coddep,'5');
				
				}
				
			} //fin whike
			
		mysql_free_result($resdep);
		
		if ($contador > 0){
		echo '<script language = JavaScript>
		alert("No se eliminaron algunos departamentos ya que se encuestran vinculados a ciudades ");
		location = "deppro.php";
		</script>';
		}else{
		echo '<script language = JavaScript>
		location = "deppro.php";
		</script>';
		}
	}
	
?>
