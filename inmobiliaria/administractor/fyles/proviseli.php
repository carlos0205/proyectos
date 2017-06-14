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
			
		function array_recibe($url_codprovis) { 
			$tmp = stripslashes($url_codprovis); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codprovis=$_GET['codprovis']; 
		//codigo de producto
		$cod=$_GET['codpro']; 	
			
		$codprovis=array_recibe($codprovis); 
		
		$qryimgpro = "SELECT imgpro FROM provis WHERE codprovis IN (".implode(',',$codprovis).") ";
		$resimgpro =mysql_query($qryimgpro,$enlace);	
		
	
		while ($filimgpro = mysql_fetch_array($resimgpro)){
			
					$ruta1="../productos/vistas/".$filimgpro["imgpro"];
					unlink ($ruta1);
				
					$ruta2="../productos/vistas/mini/".$filimgpro["imgpro"];
					unlink ($ruta2);
				
			} //fin while
			
		mysql_free_result($resimgpro);
		
		//elimino fotos
		$qryprofoteli="DELETE FROM provis WHERE codprovis IN (".implode(',',$codprovis).") ";
		$ressprofoteli=mysql_query($qryprofoteli,$enlace);	
	?>
		<script language="javascript" type="text/javascript">
		location = "provis.php?cod=<?php echo $cod?>";
		</script>
	<?php
	}
	?>