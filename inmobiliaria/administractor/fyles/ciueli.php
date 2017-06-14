<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);

	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'ciueli.php';
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
		
		$qryciu = "SELECT codciu FROM ciudad WHERE codciu IN (".implode(',',$codreg).") ";
		$resciu =mysql_query($qryciu,$enlace);	
		
		$contador = 0;
		while ($filciu = mysql_fetch_array($resciu)){
		
				$codreg = $filciu["codciu"];
				
				//consulto si existen productos ciuendeientes de la clase
				$qryciu1="SELECT t.codciu FROM tercli t WHERE t.codciu = '$codreg'";
				$resciu1=mysql_query($qryciu1, $enlace);
				$numciu = mysql_num_rows($resciu1);
	
				if($numciu > 0 ){
					$contador ++;
				}else{
				
					//elimino clase
					$qryciueli="DELETE FROM ciudad WHERE codciu = '$codreg' ";
					$resciueli=mysql_query($qryciueli,$enlace);	
					
					auditoria($_SESSION["enlineaadm"],'Ciudad',$codreg,'5');
				
				}
				
			} //fin whike
			
		mysql_free_result($resciu);
		
		if ($contador > 0){
		echo '<script language = JavaScript>
		alert("No se eliminaron algunas ciudades ya que se encuestran vinculadas a terceros ");
		location = "ciu.php";
		</script>';
		}else{
		echo '<script language = JavaScript>
		location = "ciu.php";
		</script>';
		}

	}
?>
