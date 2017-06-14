<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);

	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'proedi.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
	if($permiso){
	
		$enlace = enlace();
			
		function array_recibe($url_codcol) { 
			$tmp = stripslashes($url_codcol); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codcol=$_GET['codcol']; 	
		$codcol=array_recibe($codcol); 
		$cod=$_GET['cod']; 
	
		//elimino colores
		$qrycoleli="DELETE FROM procol WHERE codcarcol IN (".implode(',',$codcol).") AND codpro = '$cod' ";
		$rescoleli=mysql_query($qrycoleli,$enlace);	
	?>
		<script language = JavaScript>
		location = "procol.php?cod=<?php echo $cod?>";
		</script>';
	<?php
	}
	?>
	

