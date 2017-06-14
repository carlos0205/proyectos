<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);
	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'prowebeli.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
	if($permiso){
	
		$enlace = enlace();
			
		function array_recibe($url_codprog) { 
			$tmp = stripslashes($url_codprog); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codprog=$_GET['codprog']; 	
		$codprog=array_recibe($codprog); 
		
		$qryprogweb = "SELECT codprog FROM progweb WHERE codprog IN (".implode(',',$codprog).") ";
		$resprogweb =mysql_query($qryprogweb,$enlace);	
		
		$contador = 0;
		while ($filprogweb = mysql_fetch_assoc($resprogweb)){
		
				$codprog = $filprogweb["codprog"];
				//elimino programa
				$qryprogwebeli="DELETE FROM progweb WHERE codprog = '$codprog' ";
				$resprogwebeli=mysql_query($qryprogwebeli,$enlace);		
				
				$qryprogwebeli="DELETE FROM progwebcli WHERE codprog = '$codprog' ";
				$resprogwebeli=mysql_query($qryprogwebeli,$enlace);		
				
				$qryprogwebeli="DELETE FROM gruprog WHERE codprog = '$codprog' ";
				$resprogwebeli=mysql_query($qryprogwebeli,$enlace);		
						
				
			} //fin while
			
		mysql_free_result($restipter);

		echo '<script language = JavaScript>
		location = "proweb.php";
		</script>';
	}
?>
