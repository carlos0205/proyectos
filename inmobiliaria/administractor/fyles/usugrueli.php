<?php
	session_start();
	include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'usugrueli.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
	if($permiso){
	
		$enlace = enlace();
			
		function array_recibe($url_codgru) { 
			$tmp = stripslashes($url_codgru); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codgru=$_GET['codgru']; 	
		$codgru=array_recibe($codgru); 
		
		$qrygru = "SELECT codgru FROM gruusu WHERE codgru IN (".implode(',',$codgru).") ";
		$resgru =mysql_query($qrygru,$enlace);	
		
		$contador = 0;
		while ($filgru = mysql_fetch_assoc($resgru)){
		
				$codgru = $filgru["codgru"];
				
				//consulto si existen pclientes dependeientes de l tipo de tercero
				$qryusu="SELECT codgru FROM usuadm WHERE codgru = '$codgru'";
				$resusu=mysql_query($qryusu, $enlace);
				$numusu = mysql_num_rows($resusu);
				
				if($numusu > 0){
					$contador ++;
				}else{
					
					//elimino grupo
					$qrygrueli="DELETE FROM gruusu WHERE codgru = '$codgru' ";
					$resgrueli=mysql_query($qrygrueli,$enlace);		
					//elimino progamas de  grupo	
					$qrygrueli="DELETE FROM gruprog WHERE codgru = '$codgru' ";
					$resgrueli=mysql_query($qrygrueli,$enlace);		
					
					auditoria($_SESSION["enlineaadm"],'Grupos de Usuario',$codgru,'5');		
				}
				
			} //fin whike
			
		mysql_free_result($resgru);
		
		if ($contador > 0){
		echo '<script language = JavaScript>
		alert("No se eliminaron algunos grupos de usuario ya que se encuestran vinculados a usuarios");
		location = "usugru.php";
		</script>';
		}else{
		echo '<script language = JavaScript>
		location = "usugru.php";
		</script>';
		}
	}
?>
