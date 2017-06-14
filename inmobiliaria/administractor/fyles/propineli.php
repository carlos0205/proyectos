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
			
		function array_recibe($url_codpropin) { 
			$tmp = stripslashes($url_codpropin); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codpropin=$_GET['codpropin']; 
		//codigo de producto
		$cod=$_GET['codpro']; 	
			
		$codpropin=array_recibe($codpropin); 
		
		$qryimgpro = "SELECT imgpropin, imgpropinmini FROM propin WHERE codpropin IN (".implode(',',$codpropin).") ";
		$resimgpro =mysql_query($qryimgpro,$enlace);	
		
	
		while ($filimgpro = mysql_fetch_array($resimgpro)){
			
					$ruta1="../productos/pintas/".$filimgpro["imgpropin"];
					unlink ($ruta1);
					
					
					$ruta2="../productos/pintas/mini/".$filimgpro["imgpropin"];
					unlink ($ruta2);
					
					$ruta3="../productos/pintas/minisec/".$filimgpro["imgpropinmini"];
					unlink ($ruta3);
	
				
			} //fin while
			
		mysql_free_result($resimgpro);
		
		//elimino fotos
		$qryprofoteli="DELETE FROM propin WHERE codpropin IN (".implode(',',$codpropin).") ";
		$ressprofoteli=mysql_query($qryprofoteli,$enlace);	
	?>
		<script language="javascript" type="text/javascript">
		location = "propin.php?cod=<?php echo $cod?>";
		</script>
	<?php
	}
	?>