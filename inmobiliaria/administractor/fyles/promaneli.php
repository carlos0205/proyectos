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
			
		function array_recibe($url_codproman) { 
			$tmp = stripslashes($url_codproman); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codproman=$_GET['codproman']; 
		//codigo de producto
		$cod=$_GET['codpro']; 	
			
		$codproman=array_recibe($codproman); 
		
		$qrymanpro = "SELECT docman FROM proman WHERE codproman IN (".implode(',',$codproman).") ";
		$resmanpro =mysql_query($qrymanpro,$enlace);	
		
	
		while ($filmanpro = mysql_fetch_array($resmanpro)){
			
					$ruta1="../productos/manuales/".$filmanpro["docman"];
					unlink ($ruta1);
	
				
			} //fin while
			
		mysql_free_result($resmanpro);
		
		//elimino fotos
		$qrypromaneli="DELETE FROM proman WHERE codproman IN (".implode(',',$codproman).") ";
		$respromaneli=mysql_query($qrypromaneli,$enlace);	
	?>
		<script language="javascript" type="text/javascript">
		location = "proman.php?cod=<?php echo $cod?>";
		</script>
	<?php 
	}
	?>